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

class LeadsController extends Controller
{
	public function __construct()
	{
		$this->middleware("auth");
	}

	public function index()
	{
		$fromdate = date("Y-m-d", strtotime("+1 day"));
		if (!empty($_GET["fromdate"])) {
			$fromdate = $_GET["fromdate"];
		}
			$todate = date("Y-m-d");
		if (!empty($_GET["todate"])) {
			$todate = $_GET["todate"];
		}
		$todate1 = date("Y-m-d", strtotime("+1 day", strtotime($todate)));
		$datetype = "entry_date";
		if (!empty($_GET["datetype"])) {
			$datetype = $_GET["datetype"];
		}

		$lists = VicidialList::whereBetween($datetype, [$fromdate, $todate1])
		->select("lead_id","entry_date","modify_date","list_id","status","phone_number","user","called_count","batchno","vendor_lead_code")
		->where("lead_id", ">", "0");

		if (empty($_GET["fromdate"])) {
			$fromdate = date("Y-m-d");
		}
		$listid = "All";
		if (!empty($_GET["listid"]) && $_GET["listid"] != "All") {
			$listid = $_GET["listid"];
			$lists = $lists->where("list_id", $listid);
		}

		$status = null;
		if (!empty($_GET["status"])) {
			$status = $_GET["status"];
			$lists = $lists->whereIn("status", explode(",", $status));
		}

		$phone = null;
		if (!empty($_GET["phone"])) {
			$phone = $_GET["phone"];
			$lists = $lists->where("phone_number", $phone);
		}
		$batchno = null;
		if (!empty($_GET["batchno"])) {
			$batchno = $_GET["batchno"];
			$lists = $lists->where("batchno", $batchno);
		}

		if (!empty($_GET["reset"]) && $_GET["reset"] == true) {
			$resetlists = $lists;
			$resetlists =$resetlists->whereNotIn("status", ["Answer", "answer", "New"])->get();
			$lilog = 0;
			foreach ($resetlists as $log) {
				
				$ship_count=Shipment::where('consignee_phone_upload',$log->phone_number)->where('appointment_id','0')->count();
				if($ship_count > 0){
					$lilog++;
					VicidialList::where('lead_id',$log->lead_id)->update(["called_since_last_reset" => "N", "status" => "NEW"]);
				}
			}
			$lilog = $lilog." records resetted";
			

			DB::table('lists_log')->insert(['log' => $lilog,'listid' => $listid,'userid' => Auth::user()->id]);
			return redirect("/leads");
		}

		if (!empty($_GET["clear"]) && $_GET["clear"] == true) {
			$clearlists = $lists;
			$lilog = $clearlists->count()." records cleared";
			$clearlists->delete();

			DB::table('lists_log')->insert(['log' => $lilog,'listid' => $listid,'userid' => Auth::user()->id]);
			return redirect("/leads");
		}

		$listcount = $lists->count();
		$lists = $lists->get();
		$batches = [];
		return view("leads.index",compact("fromdate","todate","todate1","status","listcount","listid","lists","datetype","batchno","batches","phone"));
	}

	public function status()
	{
		$campaign = "All";
		if (!empty($_GET["campaign"])) {
			$campaign = $_GET["campaign"];
		}
		$status = "All";
		if (!empty($_GET["status"])) {
			$status = $_GET["status"];
		}
		$lists = VicidialLists::select("list_id","list_name","campaign_id","list_description","active")->where("list_id", ">", "0");
		if ($campaign != "All") {
			$lists = $lists->where("campaign_id", $campaign);
		}
		if ($status != "All") {
			$lists = $lists->where("active", $status);
		}
		$lists = $lists->get();
		return view("leads.status", compact("campaign", "status", "lists"));
	}

	public function addnewlist(Request $request)
	{
		$list_id = $request->listid;
		$list_name = $request->listname;
		$campaign_id = $request->campaign;

		$response = "List Not Added";
		$alredylist = DB::connection("mysql2")
		->table("vicidial_lists")
		->where("list_id", $list_id)
		->count();
		if ($alredylist == "0") {
		DB::connection("mysql2")
		->table("vicidial_lists")
		->insert(["list_id" => $list_id,"list_name" => $list_name,"campaign_id" => $campaign_id,"list_description" => $request->list_description,"active" => "N","expiration_date" => "2099-12-31",]);

		$response = "List Added Successfully";
		//$this->updatelistids();
		\App\UserDetails::where("user_id", Auth::user()->id)->update([ "list_id" => \App\UserDetails::getColumn("list_id").','.$list_id ]);
		} else {
		$response = "List Already Exists";
		}

		return Redirect::back()->with("alert", $response);
	}

	public function changestatus($id, $status)
	{
		if ($status == "Y") {
			$status = "N";
		} elseif ($status == "N") {
			$status = "Y";
		}
		if ($status == "N") {
			VicidialHopper::where("list_id", $id)->delete();
		}
		VicidialLists::where("list_id", $id)->update(["active" => $status]);
		return Redirect::back()->with("alert", "Status updated");
	}

	public function delete($id)
	{
		$list = VicidialLists::where("list_id", $id)->first();
		$lilog = $id." List Removed";

		DB::table('lists_log')->insert(['log' => $lilog,'listid' => $id,'userid' => Auth::user()->id]);
		VicidialList::where("list_id", $id)
		->where("status", "NEW")
		->delete();
		VicidialLists::where("list_id", $id)->delete();
		VicidialHopper::where("list_id", $id)->delete();
		return Redirect::back()->with("alert", "List removed");
	}

	public function updatelistids()
	{
		$listids = Auth::user()->campaign;
		$values = \App\UserDetails::getColumn("campaign");
		$lists = \App\UserDetails::getColumn("list_id");
		$listnames = DB::connection("mysql2")
		->table("vicidial_lists")
		->select("list_id")
		->whereIn("campaign_id", explode(",", $values))
		->get();
		$listids = "";
		foreach ($listnames as $key => $value) {
			$listids .= $value->list_id . ",";
		}
		\App\UserDetails::where("user_id", Auth::user()->id)->update([
		"list_id" => rtrim($listids, ", "),
		]);
	}

	public function shipmentupload(Request $request)
	{
		$fromdate = date("Y-m-01");
		$todate = date("Y-m-t");

		if (!empty($request->fromdate)) {
			$fromdate = $request->fromdate;
		}
		if (!empty($request->todate)) {
			$todate = $request->todate;
		}
		
		$todate1 = date("Y-m-d", strtotime("+1 day", strtotime($todate)));

		$lists = Upload::whereBetween('created_at', [$fromdate, $todate1]);
		$listid='All';

		if ($request->listid!='All' && !empty($request->listid)) {
			$listid = $request->listid;
			$lists = $lists->where("list_id", $listid);
		}
		$lists = $lists->orderBy('id','desc')->get(); 

		if(!empty($request->searchtype) && !empty($request->searchval)){

			$lists =Shipment::where($request->searchtype,$request->searchval)->get();

			return view("leads.shipments", compact("lists"));
			
		}
		return view("leads.uploads", compact("fromdate", "todate", "lists",'listid'));
	}

	public function viewshipmentupload($id='')
	{
		$lists = Shipment::where('upload_id', $id);
		$lists = $lists->get();
		return view("leads.shipments", compact("lists"));
	}

	public function viewappointment($appointment_id='')
	{
		$appointment = Appointment::where('id', $appointment_id)->first();
		$shipments = Shipment::where('appointment_id', $appointment_id)->get();

		$viewhtml = view('leads.view',compact('appointment','shipments'))->render();
		return view("leads.appointment_view", compact("viewhtml"));
	}

	public function viewshipment($shipment_id='')
	{
		$shipment = Shipment::where('id', $shipment_id)->first();

		$viewhtml = view('shipment.shipments_view_info',compact('shipment'))->render();
		return view("shipment.shipments_view", compact("viewhtml"));
	}

	public function downloadList($appointment_id='',$shipment_id='')
	{
		return view("leads.download");
	}

	
}

