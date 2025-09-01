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
use Redirect;
use DB;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Maatwebsite\Excel\Facades\Excel;

class DeliverysheetController extends Controller
{
	public function index(Request $request)
    {
        $appointment_date = date("Y-m-d");
        if (!empty($request->appointment_date)) {
			$appointment_date = $request->appointment_date;
		}

		$drivers = Deliverysheet::select('driver_id', DB::raw('COUNT(*) as total_deliveries'))->where('appointment_date', $appointment_date)->where('delete_status', 0)->groupBy('driver_id')->get();

		return view('delivery_list',compact('appointment_date','drivers'));
	}

	public function deliverysheet($driver_id,$appointment_date){

		$deliverysheets = Deliverysheet::where('appointment_date', $appointment_date)->where('driver_id', $driver_id)->where('delete_status', 0)->get();

		$driver = User::where('id',$driver_id)->first();

		return view('delivery_sheet',compact('appointment_date','deliverysheets','driver'));
	}
    
}