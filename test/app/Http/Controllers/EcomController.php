<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pickuprequest;
use App\User;
use Auth;
use DB;

class EcomController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pickuprequest(Request $request)
    {
    	$fromdate = date("Y-m-d"); if(!empty($request->fromdate)){ $fromdate = $request->fromdate; }
        $todate = date("Y-m-d"); if(!empty($request->todate)){ $todate = $request->todate; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

        $pickuprequests = Pickuprequest::whereBetween('created_at',[$fromdate, $todate1])->orderBy('id','desc')->get();

        $vendors = User::where('role_id',7)->orderBy('name','Asc')->get();
        $drivers = User::where('role_id',6)->orderBy('name','Asc')->get();

        return view('ecom.pickuprequest',compact('fromdate','todate','pickuprequests','vendors','drivers'));
    }

    public function pickuprequeststore(Request $request)
    {
        $data = new Pickuprequest; 
        $data->created_by=Auth::user()->id;
        $data->vendor_id = $request->vendor_id;
        $data->no_of_packages = $request->no_of_packages;
        $data->pickup_date = $request->pickup_date;
        $data->pickup_time = $request->pickup_time;
        $data->driver_id = $request->driver_id;
        $data->notes = $request->notes;
        $data->save();

        return redirect()->back();

    }

    public function pickuprequestsassign(Request $request)
    {
    	$data = Pickuprequest::find($request->pickuprequest_id);
        $data->updated_by=Auth::user()->id;
        $data->driver_id = $request->driver_id;
        $data->save();

        return redirect()->back();
    }

    
}