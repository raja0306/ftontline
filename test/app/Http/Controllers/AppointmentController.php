<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Auth;
use App\VicidialList;
use App\VicidialLists;
use App\User;
use App\VicidialLog;
use App\Upload;
use App\Shipment;
use App\VicidialCloserLog;
use App\VicidialHopper;
use App\Appointment;
use App\Appointmentlog;
use Redirect;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class AppointmentController extends Controller
{
	public function __construct()
	{
		$this->middleware("auth");
	}

	public function index(Request $request)
	{
		$fromdate = date("Y-m-d");
		$todate = date("Y-m-d");

		if (!empty($request->fromdate)) {
			$fromdate = $request->fromdate;
		}
		if (!empty($request->todate)) {
			$todate = $request->todate;
		}
		$appointments = Appointment::whereBetween('appointment_date', [$fromdate, $todate])->where('delete_status',0);
		$appointments= $appointments->get();
		//print_r($appointments); exit();

		return view("leads.appointments", compact("fromdate", "todate", "appointments"));

	}

    public function labels(Request $request)
    {
        $fromdate = date("Y-m-d");
        if (!empty($request->fromdate)) { $fromdate = $request->fromdate; }
        //$shipments = Shipment::whereDate('appointment_date',$fromdate)->where('appointment_id','>',0)->where('is_print',0)->orderBy('appointment_id','asc')->skip(0)->take(10)->get();
        $shipments = Shipment::join('appointments','shipments.appointment_id','=','appointments.id')->select('shipments.*','appointments.appointment_date')->where('appointments.appointment_date',$fromdate)->where('shipments.appointment_id','>',0)->orderBy('shipments.tray_no','asc')->orderBy('shipments.reference','asc');
        $is_print = 'All';
        if(is_numeric($request->is_print)){
            $is_print = $request->is_print;
            $shipments = $shipments->where('shipments.is_print',$is_print);
        }
        $shipments = $shipments->get();
        //print_r($appointments); exit();

        return view("leads.label_app", compact("fromdate", "shipments","is_print"));

    }

	public function labelgenerate1(Request $request)
	{
		print_r($request->all());
		$labelHtml = view('extras.appointment',compact('shipments'))->render();
		echo $labelHtml;
	}

    public function labelfull()
    {
        $fromdate = date("Y-m-12");
        $shipments = Shipment::join('appointments','shipments.appointment_id','=','appointments.id')->select('shipments.*','appointments.appointment_date')->where('appointments.appointment_date',$fromdate)->where('shipments.appointment_id','>',0)->orderBy('shipments.tray_no','asc')->orderBy('shipments.reference','asc')->get();


        // echo count($shipments); exit();

        foreach ($shipments as $item) {
            if($item->address_type != 2){
                if(empty($item->appointment->useraddress)){
                    print_r($item->appointment->useraddress); echo "<br>";
                }
            }
        }
        exit();

        $labels = [];
        $generator = new BarcodeGeneratorPNG();
        foreach ($shipments as $item) {
            $barcode = base64_encode($generator->getBarcode($item['barcode'],$generator::TYPE_CODE_128,3,50));
            $address = $item->appointment->useraddress;
            if($address){
                $labels[] = [
                'appointment_id' => $item->appointment->id,
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => $item->appointment->useraddress,
                'branch' => $item->appointment->branch,
                'preference' => '',
                'req_from' => '',
                'area' => $address->area->name,
                'street' => $address->street,
                'block' => $address->block,
                'avenue' => $address->avenue,
                'house_no' => $address->house_no,
                'floor_no' => $address->floor_no,
                'flat_no' => $address->flat_no,
                'pacii_no' => $address->pacii_no,
                'landmark' => $address->landmark,
            ];
            }
            else{
                $labels[] = [
                'appointment_id' => $item->appointment->id,
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => '',
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'area' => '',
                'street' => '',
                'block' => '',
                'avenue' => '',
                'house_no' => '',
                'floor_no' => '',
                'flat_no' => '',
                'pacii_no' => '',
                'landmark' => '',
                ];
            }
            print_r($labels); exit();
            Shipment::where('id',$item->id)->update(['is_print'=>1]);
        }
        // echo count($labels);
        // return view('extras.appfull', ['labels' => $labels]);
        $pdf = PDF::loadView('extras.appfull', ['labels' => $labels]);
        $pdf->setPaper('a1', 'landscape');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);
        return $pdf->download('labels.pdf');
    }

	public function labelgenerate(Request $request)
    {
    	if(!empty($request->selected_ids)){
			$shipments = Shipment::whereIn('id',explode(",", $request->selected_ids))->orderBy('tray_no','asc')->orderBy('reference','asc')->get();
		}
        else{
            $shipments = Shipment::join('appointments','shipments.appointment_id','=','appointments.id')->select('shipments.*','appointments.appointment_date')->where('appointments.appointment_date',$request->fromdate)->where('shipments.appointment_id','>',0)->where('shipments.is_print',0)->orderBy('shipments.tray_no','asc')->orderBy('shipments.reference','asc')->get();
        }
        //echo count($shipments); exit();
        $labels = [];
        $generator = new BarcodeGeneratorPNG();
        $phoneicon_path = public_path('svgs/phone-solid-full.svg');
        $waicon_path = public_path('svgs/whatsapp.svg');
        $phoneicon = base64_encode(file_get_contents($phoneicon_path));
        $waicon = base64_encode(file_get_contents($waicon_path));
        foreach ($shipments as $item) {
            $barcode = base64_encode($generator->getBarcode($item['barcode'],$generator::TYPE_CODE_128,3,50));
            $address = $item->appointment->useraddress;
            if($address){
                $areaname = null; $areacode = null; if($address->area){ $areaname = $address->area->name; $areacode = $address->area->area_code; }
                $labels[] = [
                'appointment_id' => $item['appointment_id'],
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => $item->appointment->useraddress,
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'areacode' => $areacode,
                'area' => $areaname,
                'street' => $address->street,
                'block' => $address->block,
                'avenue' => $address->avenue,
                'house_no' => $address->house_no,
                'floor_no' => $address->floor_no,
                'flat_no' => $address->flat_no,
                'pacii_no' => $address->pacii_no,
                'landmark' => $address->landmark,
            ];
                $labels[] = [
                'appointment_id' => $item['appointment_id'],
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => $item->appointment->useraddress,
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'areacode' => $areacode,
                'area' => $areaname,
                'street' => $address->street,
                'block' => $address->block,
                'avenue' => $address->avenue,
                'house_no' => $address->house_no,
                'floor_no' => $address->floor_no,
                'flat_no' => $address->flat_no,
                'pacii_no' => $address->pacii_no,
                'landmark' => $address->landmark,
            ];
                $labels[] = [
                'appointment_id' => $item['appointment_id'],
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => $item->appointment->useraddress,
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'areacode' => $areacode,
                'area' => $areaname,
                'street' => $address->street,
                'block' => $address->block,
                'avenue' => $address->avenue,
                'house_no' => $address->house_no,
                'floor_no' => $address->floor_no,
                'flat_no' => $address->flat_no,
                'pacii_no' => $address->pacii_no,
                'landmark' => $address->landmark,
            ];
                $labels[] = [
                'appointment_id' => $item['appointment_id'],
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => $item->appointment->useraddress,
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'areacode' => $areacode,
                'area' => $areaname,
                'street' => $address->street,
                'block' => $address->block,
                'avenue' => $address->avenue,
                'house_no' => $address->house_no,
                'floor_no' => $address->floor_no,
                'flat_no' => $address->flat_no,
                'pacii_no' => $address->pacii_no,
                'landmark' => $address->landmark,
            ];
                $labels[] = [
                'appointment_id' => $item['appointment_id'],
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => $item->appointment->useraddress,
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'areacode' => $areacode,
                'area' => $areaname,
                'street' => $address->street,
                'block' => $address->block,
                'avenue' => $address->avenue,
                'house_no' => $address->house_no,
                'floor_no' => $address->floor_no,
                'flat_no' => $address->flat_no,
                'pacii_no' => $address->pacii_no,
                'landmark' => $address->landmark,
            ];
            }
            else{
                $labels[] = [
                'appointment_id' => $item['appointment_id'],
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => '',
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'areacode' => '',
                'area' => '',
                'street' => '',
                'block' => '',
                'avenue' => '',
                'house_no' => '',
                'floor_no' => '',
                'flat_no' => '',
                'pacii_no' => '',
                'landmark' => '',
                ];
                $labels[] = [
                'appointment_id' => $item['appointment_id'],
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => '',
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'areacode' => '',
                'area' => '',
                'street' => '',
                'block' => '',
                'avenue' => '',
                'house_no' => '',
                'floor_no' => '',
                'flat_no' => '',
                'pacii_no' => '',
                'landmark' => '',
                ];
                $labels[] = [
                'appointment_id' => $item['appointment_id'],
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => '',
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'areacode' => '',
                'area' => '',
                'street' => '',
                'block' => '',
                'avenue' => '',
                'house_no' => '',
                'floor_no' => '',
                'flat_no' => '',
                'pacii_no' => '',
                'landmark' => '',
                ];
                $labels[] = [
                'appointment_id' => $item['appointment_id'],
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => '',
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'areacode' => '',
                'area' => '',
                'street' => '',
                'block' => '',
                'avenue' => '',
                'house_no' => '',
                'floor_no' => '',
                'flat_no' => '',
                'pacii_no' => '',
                'landmark' => '',
                ];
                $labels[] = [
                'appointment_id' => $item['appointment_id'],
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'appointment' => $item->appointment,
                'address' => '',
                'branch' => $item->appointment->branch,
                'preference' => $item->appointment->preference,
                'req_from' => $item->appointment->req_from,
                'areacode' => '',
                'area' => '',
                'street' => '',
                'block' => '',
                'avenue' => '',
                'house_no' => '',
                'floor_no' => '',
                'flat_no' => '',
                'pacii_no' => '',
                'landmark' => '',
                ];
            }
            //print_r($labels); exit();
            Shipment::where('id',$item->id)->update(['is_print'=>1]);
        }



        $pdf = PDF::loadView('extras.appointment', ['labels' => $labels,'phoneicon' => $phoneicon,'waicon' => $waicon]);
        $pdf->setPaper('a1', 'landscape');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);
        return $pdf->download('labels.pdf');
    }

    private function getSampleData($selected_ids)
    {

		if(!empty($request->selected_ids)){
			$shipments = Shipment::whereIn('appointment_id',explode(",", $request->selected_ids))->orderBy('tray_no','asc')->orderBy('reference','asc')->get();
		}
        return [
            [
                'id' => 'CUST001',
                'name' => 'John Smith',
                'mobile' => '+1 (555) 123-4567',
                'tray_no' => 'T-101',
                'batch_no' => 'B-2023-05-01',
                'appointment_date' => '2023-05-15',
                'appointment_time' => '10:30 AM',
                'address' => '123 Main Street, Apt 4B, New York, NY 10001, United States'
            ],
            [
                'id' => 'CUST002',
                'name' => 'Jane Doe',
                'mobile' => '+1 (555) 987-6543',
                'tray_no' => 'T-102',
                'batch_no' => 'B-2023-05-01',
                'appointment_date' => '2023-05-15',
                'appointment_time' => '11:00 AM',
                'address' => '456 Park Avenue, Suite 789, New York, NY 10022, United States'
            ],
        ];
    }

    public function banklabel()
    {
        // print_r($request->all());
        $barcode = 'KFH0024932';
        $frontline_path = public_path('svgs/frontline-logo.png');
        $kfhbank_path = public_path('svgs/kfh-bank.png');
        $frontline = base64_encode(file_get_contents($frontline_path));
        $kfhbank = base64_encode(file_get_contents($kfhbank_path));
        // echo $kfhbank; exit();
        $generator = new BarcodeGeneratorPNG();
        $barcodeBase64 = base64_encode($generator->getBarcode($barcode,$generator::TYPE_CODE_128,3,50));
        // $labelHtml = view('extras.barcode',compact('barcodeBase64','frontline','kfhbank','barcode'))->render();
        // echo $labelHtml; exit();

        $pdf = PDF::loadView('extras.barcode',compact('barcodeBase64','frontline','kfhbank','barcode'));
        $pdf->setPaper('a1', 'landscape');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);
        return $pdf->download('labels.pdf');
    }


    public function banklabels(Request $request,$idval=null)
    {
        $fromdate = date("Y-m-d");
        if (!empty($request->fromdate)) { $fromdate = $request->fromdate; }
        if(!empty($idval)){ $upload = Upload::Find($idval); $fromdate = date("Y-m-d",strtotime($upload->created_at));  }
        $lists = Shipment::join('uploads','shipments.upload_id','=','uploads.id')->select('shipments.*')->whereDate('uploads.created_at',$fromdate)->orderBy('shipments.tray_no','asc')->orderBy('shipments.reference','asc');
        $is_print = 'All';
        if(is_numeric($request->is_print)){
            $is_print = $request->is_print2;
            // $lists = $lists->where('shipments.is_print',$is_print);
        }
        if(!empty($idval)){
            $lists = $lists->where('uploads.id',$idval);
        }
        $lists = $lists->get();

        return view("leads.label_bank", compact("fromdate", "lists","is_print"));

    }

    public function printshipmentuploads(Request $request)
    {
        if(!empty($request->selected_ids)){
            $shipments = Shipment::whereIn('id',explode(",", $request->selected_ids))->orderBy('tray_no','asc')->orderBy('reference','asc')->get();
        }
        else{
            $shipments = Shipment::join('uploads','shipments.upload_id','=','uploads.id')->select('shipments.*')->whereDate('uploads.created_at',$request->fromdate)->orderBy('shipments.tray_no','asc')->orderBy('shipments.reference','asc')->get();
        }
        $labels = [];
        $generator = new BarcodeGeneratorPNG();
        foreach ($shipments as $item) {
            $barcode = base64_encode($generator->getBarcode($item['barcode'],$generator::TYPE_CODE_128,3,50));
            $labels[] = [
                'name' => $item['consignee_name'],
                'mobile' => $item['consignee_phone'],
                'mobile2' => $item['alternate_phone'],
                'barcode' => $item['barcode'],
                'barcodeimg' => $barcode,
                'tray_no' => $item['tray_no'],
                'batch_no' => $item['reference'],
                'civil_id' => $item['customer_civil_Id'],
                'receiver_civil' => $item['receiver_civil_id'],
                'guardian' => $item['guardian_name'],
                'commodity' => $item['commodity_name'],
                'description' => $item['description'],
            ];
            Shipment::where('id',$item->id)->update(['is_print2'=>1]);
        }
        $frontline_path = public_path('svgs/frontline-logo.png');
        $kfhbank_path = public_path('svgs/kfh-bank.png');
        $frontline = base64_encode(file_get_contents($frontline_path));
        $kfhbank = base64_encode(file_get_contents($kfhbank_path));
        $pdf = PDF::loadView('extras.banklabel',compact('frontline','kfhbank','labels'));
        $pdf->setPaper('a1', 'landscape');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);
        return $pdf->download('labels.pdf');
    }
}