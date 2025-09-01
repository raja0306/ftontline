<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\VicidialLog;
use App\VicidialCloserLog;
use App\VicidialDialLog;
use App\VicidialList;
use App\VicidialLists;
use App\VicidialCampaign;
use App\VicidialAgentLog;
use App\VicidialDnc; 
use App\Upload;
use App\Shipment;
use App\Appointment;
use App\Appointmentlog;
use App\Slot;
use App\User;
use App\Useraddress;
use App\Area;
use App\Branch;
use App\Enquiry;
use App\EnqCategory;
use App\Jobs\SendWhatsappAppointment;
use Illuminate\Support\Facades\Input; //header
use Excel;
use Mail;
use Carbon\Carbon;

class CustomerbookController extends Controller
{
   public function customersearch($civil,$user,$listid=0,$campaignid=0,$ingroup=0,$leadid=0)
   {
      $shipment = Shipment::where('customer_civil_Id', $civil)->orWhere('receiver_civil_id', $civil)->first();
    //print_r($shipment); exit();
      if($shipment){
         return redirect('/book/customer/'.$shipment->consignee_phone_upload.'/'.$user.'/'.$listid.'/'.$campaignid.'/'.$ingroup.'/'.$leadid);
      }
      else{
         return redirect()->back();
      }
   }
   public function book($mobile,$user,$listid=0,$campaignid=0,$ingroup=0,$leadid=0,$appid=0)
   {
   	if (strlen($mobile) > 8) { $mobile = substr($mobile, -8); }
      VicidialList::where('phone_number',$mobile)->where('list_id',1001)->where('status','NEW')->delete();

      $list = VicidialLists::select('list_name')->where('list_id',$listid)->first();
      $listname =  'ListName';
      if($list){ $listname =  $list->list_name; }

      $slots = Slot::where('delete_status',0)->get();
      $areas = Area::where('delete_status',0)->orderBy('name','Asc')->get();
      $branches= Branch::where('delete_status',0)->orderBy('name','Asc')->get();

      $lead = \App\VicidialList::where('lead_id',$leadid)->first();

      $shipments = Shipment::where('consignee_phone_upload',$mobile)->where('appointment_id','0')->whereIn('cstatus',["inscan_at_hub","New","COURIER RETURN"])->get();

      $appoint = null;
      if($appid!=0){
         $appoint = Appointment::where('id',$appid)->first();
         $shipments = Shipment::whereIn('id',explode(",", $appoint->shipment_ids))->get();
      }
      $enq_categories = EnqCategory::where('delete_status',0)->orderBy('category_name','asc')->get();
      $enquiries = Enquiry::where('mobile',$mobile)->orderBy('id','desc')->limit(10)->get();

      

      if($shipments->count() > 0){
         $customer= User::where('id',$shipments[0]->user_id)->first();
         $customer_addresses=Useraddress::where('user_id',$customer->id)->orderBy('id','desc')->get();
         $appointments = Appointment::where('user_id',$customer->id)->get();
         $upload_id = $shipments[0]->upload_id;
      }
      else{
         $customer=User::where('email',$mobile)->first();
         if($customer){
            $customer_addresses=Useraddress::where('user_id',$customer->id)->get();
            $appointments=Appointment::where('phone_number',$mobile)->get();
            $upload_id = 0;
         }
         else{
            $customer='';
            $customer_addresses='';
            $appointments='';
            $upload_id = 0;
         }
         
      }

      return view('customer.customer',compact('mobile','user','listid','campaignid','ingroup','leadid','listname','lead','slots','areas','branches','lead','shipments','customer','customer_addresses','appointments','upload_id','enq_categories','enquiries','appid','appoint'));
   }

   public function store(Request $req)
   {
      // print_r($req->all()); exit();
      $shipment_ids = $req->shipment_ids;
      $wa_shipment_ids = $req->shipment_ids;
      if(!empty($req->shipment_ids)){ $shipment_ids = implode(",", $req->shipment_ids); }

      $useraddress_id = $req->useraddress_id;
      $area_id = $req->area_id;
      if($req->address_type=='1'){
         $useraddress_id = Useraddress::insertGetId(['user_id'=>$req->user_id,'area_id'=>$req->area_id,'street'=>$req->street,'block'=>$req->block,'avenue'=>$req->avenue,'house_no'=>$req->house_no,'floor_no'=>$req->floor_no,'flat_no'=>$req->flat_no,'pacii_no'=>$req->pacii_no,'landmark'=>$req->landmark,'lat'=>$req->lat,'longi'=>$req->longi]);
      }
      else if($req->address_type=='3'){
         if(!empty($req->useraddress_id)) { $area_id = Useraddress::Find($req->useraddress_id)->area_id; }
      }
      $branch_id=null; if($req->address_type==2) { $branch_id=$req->branch_id; }
      $lead = VicidialList::select('user','list_id')->where('lead_id',$req->leadid)->first();
      $agent=$req->agent;
      $lisid=0;
      if($lead){
         $agent=$lead->user;
         $lisid=$lead->list_id;
      }
      $app_id = Appointment::insertGetId(['shipment_ids'=>$shipment_ids,'appointment_date'=>$req->appointment_date,'upload_id'=>$req->upload_id,'slot_id'=>$req->slot_id,'area_id'=>$area_id,'address_type'=>$req->address_type,'branch_id'=>$branch_id,'useraddress_id'=>$useraddress_id,'phone_number'=>$req->phone_number,'notes'=>$req->notes,'lead_id'=>$req->leadid,'user_id'=>$req->user_id,'agent'=>$agent,'list_id'=>$lisid]);
      Shipment::whereIn('id',explode(",", $shipment_ids))->update(['appointment_id'=>$app_id,'is_print'=>0]);

      if($req->edit_id>0){ 
         Appointment::where('id',$req->edit_id)->update(['delete_status'=>1]); 
      }

      foreach ($wa_shipment_ids as $shipment_id) {
          $ship=Shipment::find($shipment_id);

         if($ship){
               $apmt= $ship->appointment;
               if($apmt){
                  if($apmt->address_type!=2){

                     //SendWhatsappAppointment::dispatch('98090263',$ship->consignee_name,date('d M,Y',strtotime($apmt->appointment_date)),$apmt->slot->name,$apmt->area->name,$apmt->useraddress->block,$apmt->useraddress->street,$apmt->useraddress->house_no,$apmt->useraddress->floor_no,$apmt->useraddress->flat_no,$apmt->useraddress->landmark,$ship->barcode);

                  }
               }
          }
          
      }

      // VicidialList::where('status','new')->where('phone_number',$req->phone_number)->update(['status'=>'Answer']); 

      self::storeAppointmentLog($app_id,1,"New Appointment Created");

      return back()->with('alert','Appointment created successfully - #'.$app_id);
   }

   public function store_enquiry(Request $req)
   {
      $app_id = Enquiry::insertGetId(['enq_id'=>$req->enq_cat_id,'lead_id'=>$req->lead_id,'mobile'=>$req->mobile,'user'=>$req->user,'description'=>$req->description,'appointment_date'=>$req->follow_date,'created_at'=>now()]);
      return back()->with('alert','Enquiry created successfully - #'.$app_id);

   }

   public function storeAppointmentLog($app_id='',$status_id,$comment)
   {
      Appointmentlog::insert(['appointment_id'=>$app_id,'status_id'=>$status_id,'comment'=>$comment]);
   }

   public function calllogs($mobile)
   {
      $incomings = \App\VicidialCloserLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->where('phone_number',$mobile)->orderBy('call_date','desc')->limit(10)->get();
      $outgoings = \App\VicidialLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->where('phone_number',$mobile)->orderBy('call_date','desc')->limit(10)->get();
      $ihtml = view('customer.call_logs',compact('incomings','outgoings'))->render();
      echo json_encode(["ihtml"=>$ihtml]);
   }

   public function apptlogs($mobile)
   {
      $appointments = Appointment::where('phone_number',$mobile)->where('delete_status',0)->orderBy('lead_id','desc')->limit(30)->get();
      $url = ''; if($_GET['url']){ $url = $_GET['url']; }
      $last5shipments = Shipment::where('consignee_phone_upload',$mobile)->orderBy('id','desc')->limit(5)->get();
      $ihtml = view('customer.appt_logs',compact('appointments','url','last5shipments'))->render();
      echo json_encode(["ihtml"=>$ihtml]);
   }

   public function date_apps($date=null)
   {
      $hide_date = false; if($date<date("Y-m-d")){ $hide_date = true; }
      $editid = ''; 
      // if(!empty($_GET['editid'])&&$_GET['editid']>0){  
      //    if($date==date("Y-m-d")){ $editid = $_GET['editid']; $hide_date = true; }
      // }
      $appointments = Appointment::where('appointment_date',$date)->where('delete_status',0)->count();
      echo json_encode(["count"=>$appointments,"is_date"=>$hide_date,"editid"=>$editid]);
   }
}