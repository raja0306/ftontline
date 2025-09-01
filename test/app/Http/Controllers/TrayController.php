<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;
use App\Tray;
use App\Updatetray;
use Redirect;
use Maatwebsite\Excel\Facades\Excel;

class TrayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $fromdate = date("Y-m-d");
        $trays = Tray::where('delete_status',0)->get();
        $shipments = Shipment::join('appointments','shipments.appointment_id','=','appointments.id')->select('shipments.*','appointments.appointment_date')->where('appointments.appointment_date',$fromdate)->where('shipments.appointment_id','>',0)->orderBy('shipments.tray_no','asc')->orderBy('shipments.reference','asc')->get();
        $session = strtotime(date("Y-m-d H:i:s"));
        return view('leads.tray_update',compact('shipments','trays','fromdate','session'));
    }

    public function barcodeshipment($barcode="")
    {
        $tray_id = null; if(!empty($_GET['tray'])){ $tray_id = $_GET['tray']; }
        $session = null; if(!empty($_GET['session'])){ $session = $_GET['session']; }
        $shipment = Shipment::where('barcode',$barcode)->where('delete_status',0)->orderBy('id','desc')->first();
        $resbar = false;
        if ($shipment) {
            $resbar = true;
            $this->addtraytemp($shipment,$tray_id,$session);
        }
        $html = $this->getlist_trays($session);
        return response()->json(['inhtml'=>$html,'tray_id'=>$tray_id,'is_avail'=>$resbar]);
    }

    public function transfershipments($tray_to="")
    {
        $tray_id = null; if(!empty($_GET['tray'])){ $tray_id = $_GET['tray']; }
        $session = null; if(!empty($_GET['session'])){ $session = $_GET['session']; }
        $shipments = Shipment::where('tray_id',$tray_id)->where('delete_status',0)->orderBy('id','desc')->get();
        Updatetray::where('session_id',$session)->delete();
        $resbar = false;
        foreach ($shipments as $shipment) {
            $this->addtraytemp($shipment,$tray_to,$session);
        }
        $html = $this->getlist_trays($session);
        return response()->json(['inhtml'=>$html,'tray_id'=>$tray_id,'is_avail'=>$resbar]);
    }

    public function addtraytemp($shipment,$tray_id,$session)
    {
        if(Updatetray::where('shipment_id',$shipment->id)->where('session_id',$session)->exists()){
            Updatetray::where('shipment_id',$shipment->id)->where('session_id',$session)->update(['tray_id'=>$tray_id,'from_tray'=>$shipment->tray_id,'delete_status'=>0]);
        }
        else{
            Updatetray::insert(['shipment_id'=>$shipment->id,'from_tray'=>$shipment->tray_id,'session_id'=>$session,'tray_id'=>$tray_id,'created_at'=>date("Y-m-d H:i:s")]);
        }
    }

    public function removetray($idval="")
    {
        $session = null; if(!empty($_GET['session'])){ $session = $_GET['session']; }
        Updatetray::where('session_id',$session)->where('id',$idval)->delete();
        $html = $this->getlist_trays($session);
        return response()->json(['inhtml'=>$html,'tray_id'=>$idval]);
    }

    public function cleartrays($session="")
    {
        Updatetray::where('session_id',$session)->delete();
        $html = $this->getlist_trays($session);
        return response()->json(['inhtml'=>$html]);
    }

    public function getlist_trays($session="")
    {
        $lists = Updatetray::where('session_id',$session)->where('delete_status',0)->orderBy('id','desc')->get();
        return view('leads.tray_update_lists',compact('lists'))->render();
    }

    public function update_trays_all($session="")
    {
        $lists = Updatetray::where('session_id',$session)->where('delete_status',0)->orderBy('id','desc')->get();
        foreach ($lists as $list) {
            app('App\Http\Controllers\ShipmentuploadController')->CreateShipmentinfo($list->shipment_id,'Tray updated',$list->fromtray->name ?? '',$list->tray->name);
            Shipment::where('id',$list->shipment_id)->update(['tray_id'=>$list->tray_id,'tray_no'=>$list->tray->name,'cstatus'=>'CARD STORED','status_id'=>2]);
            Updatetray::where('id',$list->id)->update(['delete_status'=>2]);
        } 
        return Redirect::back()->with('msg','Updated shipment tray details successfully');
    }

    public function upload_trays(Request $request)
    {
        header('Content-Type: text/html; charset=utf-8');

        $csvFilePath = $request->file('csvFile')->getRealPath();
        $dataPath = Excel::toCollection(null, $csvFilePath, null, \Maatwebsite\Excel\Excel::CSV);
        $lists = $dataPath[0];

        if ($request->hasFile('csvFile')) {
            $file = $request->file('csvFile');
            $data = array_map('str_getcsv', file($file));
            $tray_id = $request->input('tray_id');
            $session = $request->input('session');
            foreach ($lists as $barcode) {
                $shipment = Shipment::where('barcode',$barcode)->where('delete_status',0)->orderBy('id','desc')->first();
                if ($shipment) {
                    $this->addtraytemp($shipment,$tray_id,$session);
                }
            }
            $html = $this->getlist_trays($session);
            return response()->json(['inhtml'=>$html]);
        }

        return response()->json(['inhtml' => ''], 400);
    }


}
