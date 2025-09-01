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
        foreach ($shipments as $item) {
            $barcode = base64_encode($generator->getBarcode($item['barcode'],$generator::TYPE_CODE_128,3,50));
            $address = $item->appointment->useraddress;
            if($address){
                $areaname = null; if($address->area){ $areaname = $address->area->name; }
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

        // return view('extras.appointment', ['labels' => $labels]);
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
}