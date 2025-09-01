<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;
use App\Tray;
use App\User;
use App\Shipmentstatus;
use App\Shipmentinfo;
use App\Appointment;
use App\Deliverysheet;
use Illuminate\Support\Facades\Artisan;
use Redirect;
use App\Jobs\SendWhatsappAppointment;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class ShipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $fromdate = date("Y-m-d");
        $statustype = null; if(!empty($_GET['statustype'])){ $statustype = $_GET['statustype'];  }
        if($_GET['statustype']=='1'){
        	$title="Dispatch Update";
        }
        else if($_GET['statustype']=='2'){
        	$title="Delivery Confirmation";
        }
        else if($statustype=='3'){
        	$title="Reconcilation";
        }
        $drivers = User::where('role_id','6')->get();
        
        $session = strtotime(date("Y-m-d H:i:s"));
        return view('shipment.shipment_update',compact('fromdate','session','statustype','title','drivers'));
    }

    public function fetchshipment($barcode="",$driver_id=0)
    {
        $driver_match=false;
        $session = null; if(!empty($_GET['session'])){ $session = $_GET['session']; }
        $statustype = null; if(!empty($_GET['statustype'])){ $statustype = $_GET['statustype']; }
        if($statustype==1){
            $shipment_count=0;
            $shipment = Shipment::where('barcode',$barcode)->where('delete_status',0)->orderBy('id','desc')->first();
        }
        else{
            $shipment_count = Shipment::where('barcode',$barcode)->where('delete_status',0)->orderBy('id','desc')->count();
            $shipment = Shipment::where('barcode',$barcode)->where('driver_id',$driver_id)->where('delete_status',0)->orderBy('id','desc')->first();

        }
        $resbar = false;
        if ($shipment) {
            $resbar = true;
            $this->addstatustemp($shipment,$session,$statustype);
        }
        else{
            if($shipment_count>0){
                $driver_match=true;
            }
        }
        $html = $this->getlist_shipments($session,$statustype);
        $spancount= Shipmentstatus::where('session_id',$session)->where('statustype',$statustype)->where('delete_status',0)->count();
        return response()->json(['inhtml'=>$html,'is_avail'=>$resbar,'countspan'=>$spancount,'driver_match'=>$driver_match]);
    }

    public function addstatustemp($shipment,$session,$statustype)
    {
        if(Shipmentstatus::where('shipment_id',$shipment->id)->where('statustype',$session)->exists()){
     		
        }
        else{
            Shipmentstatus::insert(['shipment_id'=>$shipment->id,'statustype'=>$statustype,'session_id'=>$session,'created_at'=>date("Y-m-d H:i:s")]);
        }
    }

    public function getlist_shipments($session="",$statustype="")
    {
        $lists = Shipmentstatus::where('session_id',$session)->where('statustype',$statustype)->where('delete_status',0)->orderBy('id','desc')->get();
        return view('shipment.shipment_lists',compact('lists','statustype'))->render();
    }

    public function removeShipmentStatus($idval="")
    {
        $session = null; if(!empty($_GET['session'])){ $session = $_GET['session']; }
        $statustype = null; if(!empty($_GET['statustype'])){ $statustype = $_GET['statustype']; }
        Shipmentstatus::where('id',$idval)->delete();
        $html = $this->getlist_shipments($session,$statustype);
        return response()->json(['inhtml'=>$html,'statusidval'=>$idval]);
    }

    public function cleartrays($session="",$statustype="")
    {
        Shipmentstatus::where('session_id',$session)->where('statustype',$statustype)->delete();
        $html = $this->getlist_shipments($session,$statustype);
        return response()->json(['inhtml'=>$html]);
    }

    public function update_sipment_all($session="",$statustype="")
    {
        $lists = Shipmentstatus::where('session_id',$session)->where('statustype',$statustype)->where('delete_status',0)->orderBy('id','desc')->get();
        foreach ($lists as $list) {
        	if($statustype=='1'){
	        	$title="Dispatch Update";
	        	$cstatus = "READY FOR DELIVERY";
	        	$status_id=6;
	        	$old=$list->shipment->cardstatus->name ?? '';

	        	app('App\Http\Controllers\ShipmentuploadController')->CreateShipmentinfo($list->shipment_id,$title,$old,$cstatus);
                $shipment= Shipment::find($list->shipment_id);
                app('App\Http\Controllers\CustomerbookController')->storeAppointmentLog($shipment->appointment_id,$status_id,'Dispatch Updated');

	            Shipment::where('id',$list->shipment_id)->update(['cstatus'=>$cstatus,'status_id'=>$status_id,'tray_id'=>0,'tray_no'=>'']);
	            if($list->shipment){
	            	Appointment::where('id',$list->shipment->appointment_id)->update(['status_id'=>$status_id]);
	            }
	            
	            

	        }
	        else if($statustype=='2'){
	        	$title="Delivery Confirmation";
	        	$cstatus = "OUT FOR DELIVERY";
	        	$status_id=8;
	        	$old=$list->shipment->cardstatus->name ?? '';

	        	app('App\Http\Controllers\ShipmentuploadController')->CreateShipmentinfo($list->shipment_id,$title,$old,$cstatus);
	            Shipment::where('id',$list->shipment_id)->update(['cstatus'=>$cstatus,'status_id'=>$status_id]);
                $shipment= Shipment::find($list->shipment_id);
                app('App\Http\Controllers\CustomerbookController')->storeAppointmentLog($shipment->appointment_id,$status_id,'Delivery Confirmation');

	            if($list->shipment){
	            	Appointment::where('id',$list->shipment->appointment_id)->update(['status_id'=>$status_id]);

                    Deliverysheet::insert(['shipment_id'=>$list->shipment_id,'driver_id'=>$list->shipment->driver_id,'appointment_date'=>$list->shipment->appointment->appointment_date,'shipment_type'=>"Card"]);
	            }

	        }
	        else if($statustype=='3'){

	        	$old=$list->shipment->cardstatus->name ?? '';

	        	$title="ORDER RESET";
                $cstatus = "RECONCILIATION DONE";
                $status_id=11;
                app('App\Http\Controllers\ShipmentuploadController')->CreateShipmentinfo($list->shipment_id,$title,$old,$cstatus);
                Shipment::where('id',$list->shipment_id)->update(['cstatus'=>$cstatus,'status_id'=>$status_id,'appointment_id'=>0]);

                $shipment= Shipment::find($list->shipment_id);
                app('App\Http\Controllers\CustomerbookController')->storeAppointmentLog($shipment->appointment_id,$status_id,'ORDER RESET');
                
                if($list->shipment){
                    Appointment::where('id',$list->shipment->appointment_id)->update(['status_id'=>$status_id]);
                }

	        }
	        
            Shipmentstatus::where('id',$list->id)->update(['delete_status'=>2]);
        } 
        return Redirect::back()->with('msg','Updated shipment details successfully');
    }

    public function shipmentinfo($shipment_id='')
    {
        $shipment = Shipment::find($shipment_id);
        if($shipment){
            $shipmentinfos=$shipment->shipmentinfos;
        }
        else{
            $shipment='';
            $shipmentinfos='';
        }

        return view('shipment.shipmenthistory',compact('shipmentinfos','shipment'))->render();
    }

    public function blockedshipment(Request $request)
    {
        $fromdate = date("Y-m-d");
        $todate = date("Y-m-d");

        if (!empty($request->fromdate)) {
            $fromdate = $request->fromdate;
        }
        if (!empty($request->todate)) {
            $todate = $request->todate;
        }

        $todate1 = date("Y-m-d", strtotime("+1 day", strtotime($todate)));

        $lists = Shipment::whereBetween('updated_at', [$fromdate, $todate1])->where('block_call',"!=",0)->get();

        return view('shipment.blocked_shipment',compact('lists','fromdate','todate'))->render();
    }

    public function removeblockedshipment($shipment_id='')
    {
        Shipment::where('id',$shipment_id)->update(['block_call'=>0]);

        app('App\Http\Controllers\ShipmentuploadController')->CreateShipmentinfo($shipment_id,"Block Removed","Blocked","Block Removed");

        return Redirect::back()->with('msg','Updated shipment details successfully');
    }

    public function dashboard($value='')
    {
        $fromdate = date("Y-m-d");
        $todate = date("Y-m-d");
        if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }

        return view('shipment.dashboard', compact('fromdate','todate'));
    }

    public function statusdashboard()
    {
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
    
        $cardcount = \App\Appointment::whereBetween('appointment_date',[$fromdate, $todate])->count();
        $front_count = 0;
        $reverse_count = 0;

        return view('shipment.statusdashboard', compact('fromdate','todate','cardcount','front_count','reverse_count'));
    }

    public function cardstatusdashboard()
    {
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $cardstatus = \App\Cardstatus::get();
        return view('shipment.cardstatus', compact('cardstatus','fromdate','todate'));
    }

    public function driverstatusdashboard()
    {
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $deliveryCounts = Deliverysheet::whereBetween('appointment_date',[$fromdate, $todate])
                        ->where('delete_status', 0)
                        ->selectRaw('driver_id, COUNT(*) as total')
                        ->groupBy('driver_id')
                        ->get();
        return view('shipment.driverstatus', compact('deliveryCounts','fromdate','todate','todate1'));
    }

    public function update_shipment_process(Request $request)
    {
        header('Content-Type: text/html; charset=utf-8');

        $csvFilePath = $request->file('csvFile')->getRealPath();
        $dataPath = Excel::toCollection(null, $csvFilePath, null, \Maatwebsite\Excel\Excel::CSV);
        $lists = $dataPath[0];

        if ($request->hasFile('csvFile')) {
            $file = $request->file('csvFile');
            $data = array_map('str_getcsv', file($file));
            $driver_id = $request->input('driver_id');
            $process_id = $request->input('process_id');
            $session = $request->input('session');
            foreach ($lists as $barcode) {
                $shipment = Shipment::where('barcode',$barcode)->where('delete_status',0)->orderBy('id','desc')->first();
                if ($shipment) {
                    $this->addstatustemp($shipment,$session,$process_id);
                }
            }
            $html=$this->getlist_shipments($session,$process_id);
            return response()->json(['inhtml'=>$html]);
        }

        return response()->json(['inhtml' => ''], 400);
    }

    public function editshipment($shipment_id='')
    {
        $shipment = \App\Shipment::where('id', $shipment_id)->first();
        $commodities =\App\Commodity::where('delete_status','0')->get();
        $cardtypes =\App\Cardtype::where('delete_status','0')->get();
        $branchs =\App\Branch::where('delete_status','0')->get();
        $trays =\App\Tray::where('delete_status','0')->get();

        return view("shipment.shipments_edit", compact("shipment","commodities","cardtypes","branchs","trays"));
    }

    public function shipmentedit(Request $request)
    {

        //check Commodity
        $commodity = \App\Commodity::where('name', $request->commodity_name)->first();
        if ($commodity) {
            $commodity_id = $commodity->id;
        } else {
            $commodity_id = \App\Commodity::insertGetId([
                'name' => $request->commodity_name,
            ]);
        }

        //check Cardtype
        $cardtype = \App\Cardtype::where('name', $request->description)->first();
        if ($cardtype) {
            $cardtype_id = $cardtype->id;
        } else {
            $cardtype_id = \App\Cardtype::insertGetId([
                'name' => $request->description,
            ]);
        }


        //check Branch
        $branch = \App\Branch::where('name', $request->branch_name)->first();
        if ($branch) {
            $branch_id = $branch->id;
        } else {
            $branch_id = \App\Branch::insertGetId([
                'name' => $request->branch_name,
            ]);
        }

        //check Tray
        $tray = \App\Tray::where('name', $request->tray_no)->first();
        if ($tray) {
            $tray_id = $tray->id;
        }
        else if(empty($request->tray_no)){
            $tray_id = 0;
        }
        else {
            $tray_id = \App\Tray::insertGetId([
                'name' => $request->tray_no,
            ]);
        }

        $phone = substr($request->consignee_phone, -8);
        $al_phone = substr($request->alternate_phone, -8);

        \App\Shipment::where('id',$request->edit_id)->update([
            'pickup_date' => $request->pickup_date,
            'reference' => $request->reference,
            'consignee_name' => $request->consignee_name,
            'consignee_phone' => $request->consignee_phone,
            'alternate_phone' => $request->alternate_phone,
            'manifest_number' => $request->manifest_number,
            'commodity_name' => $request->commodity_name,
            'customer_civil_Id' => $request->customer_civil_Id,
            'receiver_civil_id' => $request->receiver_civil_id,
            'guardian_name' => $request->guardian_name,
            'description' => $request->description,
            'branch_name' => $request->branch_name,
            'tray_no' => $request->tray_no,
            'branch_id' => $branch_id,
            'bank_id' =>$request->bank_id ,
            'cardtype_id' => $cardtype_id,
            'commodity_id' => $commodity_id,
            'tray_id' => $tray_id,
            'updated_by' => Auth::id(),
            'consignee_phone_upload' => $phone,
            'alternate_phone_upload' => $al_phone
        ]);

        app('App\Http\Controllers\ShipmentuploadController')->CreateShipmentinfo($request->edit_id,"Shipment Info Updated");

        return redirect()->back();
    }

}