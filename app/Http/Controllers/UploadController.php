<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Auth;
use App\VicidialList;
use App\Upload;
use App\Shipment;
use App\Shipmentlog;
use App\User;
use App\Tray;
use App\Commodity;
use App\Cardtype;
use Redirect;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{
public function __construct()
{
$this->middleware('auth');
}

public function index()
{
$campaigns = \App\VicidialCampaign::orderBy('campaign_name','asc')->get();
$lists = \App\VicidialLists::select('list_id','list_name')->orderBy('list_name','asc')->get();
return view('leads.uploadcsv',compact('lists','campaigns'));
}

public function upload2(Request $request)
{
    header('Content-Type: text/html; charset=utf-8');

    $csvFilePath = $request->file('import_file')->getRealPath();
    $dataPath = Excel::toCollection(null, $csvFilePath, null, \Maatwebsite\Excel\Excel::CSV);
    $lists = $dataPath[0];

    $rec_count = 0;
    $re_count = 0;
    $already_count = 0;
    $mobile_count = 0;

    $upload_id = Upload::insertGetId([
        'list_id' => $request->listid,
        'batchno' => $request->batchno,
        'created_by' => Auth::id()
    ]);

    switch ($request->fileformat) {
        case 'shipment':
            foreach ($lists as $index => $data) {
                if ($index == 0) continue; // skip header
                $re_count++;

                if (!empty($data[3]) && is_numeric($data[3])) {
                    $phone = substr($data[3], -8);
                    $al_phone = substr($data[4], -8);

                    $user = User::firstOrCreate(
                        ['email' => $phone],
                        [
                            'name' => $data[2],
                            'role_id' => 5,
                            'password' => Hash::make($phone)
                        ]
                    );

                    $existingLead = VicidialList::where([
                        ['phone_number', $phone],
                        ['list_id', $request->listid],
                        ['status', 'NEW']
                    ])->first();

                    if ($existingLead) {
                        $already_count++;
                        $shipment = Shipment::firstOrNew([
                            'barcode' => $data[12],
                            'appointment_id' => '0'
                        ]);

                        $shipment->fill([
                            'upload_id' => $upload_id,
                            'lead_id' => $existingLead->lead_id ?? null,
                            'pickup_date' => $data[0],
                            'consignee_phone' => $data[3],
                            'reference' => $data[1],
                            'consignee_name' => $data[2],
                            'alternate_phone' => $data[4],
                            'manifest_number' => $data[5],
                            'commodity_name' => $data[6],
                            'customer_civil_Id' => $data[7],
                            'receiver_civil_id' => $data[8],
                            'guardian_name' => $data[9],
                            'description' => $data[10],
                            'branch_name' => $data[11],
                            'tray_no' => $data[13],
                            'consignee_phone_upload' => $phone,
                            'alternate_phone_upload' => $al_phone,
                            'created_by' => Auth::id(),
                            'user_id' => $user->id,
                            'list_id' => $request->listid
                        ]);
                        $shipment->save();
                    } else {
                        $rec_count++;
                        VicidialList::insert([
                            'first_name' => $data[2],
                            'phone_number' => $phone,
                            'vendor_lead_code' => $data[1],
                            'list_id' => $request->listid,
                            'entry_date' => now(),
                            'status' => 'NEW',
                            'called_since_last_reset' => 'N',
                            'batchno' => $request->batchno
                        ]);

                        $lead = VicidialList::where('list_id', $request->listid)
                            ->where('phone_number', $phone)
                            ->where('status', 'NEW')
                            ->orderByDesc('lead_id')->first();

                        $shipment = Shipment::firstOrNew([
                            'barcode' => $data[12],
                            'appointment_id' => '0'
                        ]);

                        $shipment->fill([
                            'pickup_date' => $data[0],
                            'consignee_phone' => $data[3],
                            'lead_id' => $lead->lead_id ?? null,
                            'upload_id' => $upload_id,
                            'reference' => $data[1],
                            'consignee_name' => $data[2],
                            'alternate_phone' => $data[4],
                            'manifest_number' => $data[5],
                            'commodity_name' => $data[6],
                            'customer_civil_Id' => $data[7],
                            'receiver_civil_id' => $data[8],
                            'guardian_name' => $data[9],
                            'description' => $data[10],
                            'branch_name' => $data[11],
                            'tray_no' => $data[13],
                            'consignee_phone_upload' => $phone,
                            'alternate_phone_upload' => $al_phone,
                            'created_by' => Auth::id(),
                            'user_id' => $user->id,
                            'list_id' => $request->listid
                        ]);
                        $shipment->save();
                    }
                } else {
                    $mobile_count++;
                }
            }

            $logMessage = "$rec_count records Uploaded";
            DB::table('lists_log')->insert([
                'log' => $logMessage,
                'listid' => $request->listid,
                'userid' => Auth::id()
            ]);

            Upload::where('id', $upload_id)->update([
                'upload_count' => max(0, $re_count - 1),
                'uploaded_count' => $rec_count,
                'mobile_count' => max(0, $mobile_count - 1),
                'already_count' => $already_count
            ]);
            break;

        case 'bulkupload':
            foreach ($lists as $index => $data) {
                if ($index == 0) continue;
                $re_count++;

                if (!empty($data[3]) && is_numeric($data[3])) {
                    $rec_count++;
                    $phone = substr($data[3], -8);
                    $al_phone = substr($data[4], -8);

                    $user = User::firstOrCreate(
                        ['email' => $phone],
                        [
                            'name' => $data[2],
                            'role_id' => 5,
                            'password' => Hash::make($phone)
                        ]
                    );

                    $shipment = Shipment::updateOrCreate(
                        ['barcode' => $data[12]],
                        [
                            'pickup_date' => $data[0],
                            'consignee_phone' => $data[3],
                            'reference' => $data[1],
                            'consignee_name' => $data[2],
                            'alternate_phone' => $data[4],
                            'manifest_number' => $data[5],
                            'commodity_name' => $data[6],
                            'customer_civil_Id' => $data[7],
                            'receiver_civil_id' => $data[8],
                            'guardian_name' => $data[9],
                            'description' => $data[10],
                            'branch_name' => $data[11],
                            'tray_no' => $data[13],
                            'user_id' => $user->id,
                            'created_by' => Auth::id(),
                            'list_id' => $request->listid,
                            'is_call_pushed' => 0,
                            'cstatus' => $data[14]
                        ]
                    );

                    Shipmentlog::insert([
                        'barcode' => $data[12],
                        'phone' => $phone,
                        'cstatus' => $data[14]
                    ]);
                }
            }
            break;

        default:
            foreach ($lists as $index => $data) {
                if ($index == 0) continue;
                $re_count++;

                if (!empty($data[1]) && is_numeric($data[1])) {
                    VicidialList::insert([
                        'first_name' => $data[0],
                        'phone_number' => $data[1],
                        'list_id' => $request->listid,
                        'entry_date' => now(),
                        'status' => 'NEW',
                        'called_since_last_reset' => 'N',
                        'batchno' => $request->batchno
                    ]);
                    $rec_count++;
                }
            }

            DB::table('lists_log')->insert([
                'log' => "$rec_count records Uploaded",
                'listid' => $request->listid,
                'userid' => Auth::id()
            ]);
            break;
    }

    return back()->with('alert', "$rec_count Records Added Successfully");
}


public function upload(Request $request)
{
    header('Content-Type: text/html; charset=utf-8');
    // print_r($request->all());
    $csvFilePath = $request->file('import_file')->getRealPath();
    $dataPath = Excel::toCollection(null, $csvFilePath, null, \Maatwebsite\Excel\Excel::CSV);
    $lists = $dataPath[0];
    $rec_count = 0;
    $re_count = 0;
    $already_count = 0;
    $mobile_count = 0;
    $upload_id = Upload::insertGetId([
        'list_id' => $request->listid,
        'batchno' => $request->batchno,
        'created_by' => Auth::user()->id
    ]);

    if ($request->fileformat == 'shipment') {
        foreach ($lists as $data) {
            $re_count++;
            if (!empty($data[3]) && is_numeric($data[3])) {
                // $phone = substr($data[3], 0, 8);
                $phone = substr($data[3], -8);
                $al_phone = substr($data[4], -8);
                $user = User::where('email', $phone)->first();

                if ($user) {
                    $user_id = $user->id;
                } else {
                    $user_id = \App\User::insertGetId([
                        'name' => $data[2],
                        'email' => $phone,
                        'role_id' => 5,
                        'password' => Hash::make($phone),
                    ]);
                }

                $already = \App\VicidialList::where('phone_number', $phone)
                    ->where('list_id', $request->listid)
                    ->where('status', 'NEW')->first();

                if ($already) {
                    $already_count++;
                    $shipment_already = Shipment::where('barcode', $data[12])
                        ->where('appointment_id', '0')->first();

                    if ($shipment_already) {
                        Shipment::where('id', $shipment_already->id)->update([
                            'upload_id' => $upload_id,
                            'updated_by' => Auth::User()->id,'pickup_date' => $data[0],
                            'consignee_phone' => $data[3],
                            'reference' => $data[1],
                            'consignee_name' => $data[2],
                            'alternate_phone' => $data[4],
                            'manifest_number' => $data[5],
                            'commodity_name' => $data[6],
                            'customer_civil_Id' => $data[7],
                            'receiver_civil_id' => $data[8],
                            'guardian_name' => $data[9],
                            'description' => $data[10],
                            'branch_name' => $data[11],
                            'barcode' => $data[12],
                            'tray_no' => $data[13],
                            'consignee_phone_upload' => $phone,
                            'alternate_phone_upload' => $al_phone,
                            'updated_by' => Auth::User()->id
                        ]);
                    } else {
                        $shipment = Shipment::insert([
                            'pickup_date' => $data[0],
                            'consignee_phone' => $data[3],
                            'lead_id' => $already->lead_id,
                            'upload_id' => $upload_id,
                            'reference' => $data[1],
                            'consignee_name' => $data[2],
                            'alternate_phone' => $data[4],
                            'manifest_number' => $data[5],
                            'commodity_name' => $data[6],
                            'customer_civil_Id' => $data[7],
                            'receiver_civil_id' => $data[8],
                            'guardian_name' => $data[9],
                            'description' => $data[10],
                            'branch_name' => $data[11],
                            'barcode' => $data[12],
                            'tray_no' => $data[13],
                            'consignee_phone_upload' => $phone,
                            'alternate_phone_upload' => $al_phone,
                            'created_by' => Auth::User()->id,
                            'user_id' => $user_id,
                            'list_id' => $request->listid
                        ]);
                    }
                } else {
                    $rec_count++;
                    
                    $shipment_already = Shipment::where('barcode', $data[12])
                        ->first();
                    $shipment_notbooked_already = Shipment::where('barcode', $data[12])->where('appointment_id','0')
                        ->count();

                    if ($shipment_already) {
                        if($shipment_notbooked_already > 0){
                            VicidialList::insert([
                        'first_name' => $data[2],
                        'phone_number' => $phone,
                        'vendor_lead_code' => $data[1],
                        'list_id' => $request->listid,
                        'entry_date' => date("Y-m-d H:i:s"),
                        'status' => 'NEW',
                        'called_since_last_reset' => 'N',
                        'batchno' => $request->batchno
                    ]);

                    $leadids = VicidialList::where('list_id', $request->listid)
                        ->where('phone_number', $phone)
                        ->where('status', 'NEW')
                        ->orderBy('lead_id', 'desc')->first();

                    $leadid = $leadids->lead_id;
                        }
                        Shipment::where('id', $shipment_already->id)->update([
                            'upload_id' => $upload_id,
                            'updated_by' => Auth::User()->id,'pickup_date' => $data[0],
                            'consignee_phone' => $data[3],
                            'reference' => $data[1],
                            'consignee_name' => $data[2],
                            'alternate_phone' => $data[4],
                            'manifest_number' => $data[5],
                            'commodity_name' => $data[6],
                            'customer_civil_Id' => $data[7],
                            'receiver_civil_id' => $data[8],
                            'guardian_name' => $data[9],
                            'description' => $data[10],
                            'branch_name' => $data[11],
                            'barcode' => $data[12],
                            'tray_no' => $data[13],
                            'consignee_phone_upload' => $phone,
                            'alternate_phone_upload' => $al_phone,
                            'updated_by' => Auth::User()->id
                        ]);
                    } else {
                        VicidialList::insert([
                        'first_name' => $data[2],
                        'phone_number' => $phone,
                        'vendor_lead_code' => $data[1],
                        'list_id' => $request->listid,
                        'entry_date' => date("Y-m-d H:i:s"),
                        'status' => 'NEW',
                        'called_since_last_reset' => 'N',
                        'batchno' => $request->batchno
                    ]);

                    $leadids = VicidialList::where('list_id', $request->listid)
                        ->where('phone_number', $phone)
                        ->where('status', 'NEW')
                        ->orderBy('lead_id', 'desc')->first();

                    $leadid = $leadids->lead_id;
                        $shipment = Shipment::insert([
                            'pickup_date' => $data[0],
                            'consignee_phone' => $data[3],
                            'lead_id' => $leadid,
                            'upload_id' => $upload_id,
                            'reference' => $data[1],
                            'consignee_name' => $data[2],
                            'alternate_phone' => $data[4],
                            'manifest_number' => $data[5],
                            'commodity_name' => $data[6],
                            'customer_civil_Id' => $data[7],
                            'receiver_civil_id' => $data[8],
                            'guardian_name' => $data[9],
                            'description' => $data[10],
                            'branch_name' => $data[11],
                            'barcode' => $data[12],
                            'tray_no' => $data[13],
                            'consignee_phone_upload' => $phone,
                            'alternate_phone_upload' => $al_phone,
                            'created_by' => Auth::User()->id,
                            'user_id' => $user_id,
                            'list_id' => $request->listid
                        ]);
                    }
                }
            } else {
                $mobile_count++;
            }
        }

        $re_count = $re_count - 1;
        $mobile_count = $mobile_count - 1;

        $lilog = $rec_count . " records Uploaded";

        DB::table('lists_log')->insert([
            'log' => $lilog,
            'listid' => $request->listid,
            'userid' => Auth::user()->id
        ]);

        Upload::where('id', $upload_id)->update([
            'upload_count' => $re_count,
            'uploaded_count' => $rec_count,
            'mobile_count' => $mobile_count,
            'already_count' => $already_count
        ]);
    } else if ($request->fileformat == 'bulkupload') {
        $re_count = 0;
        $rec_count = 0;
        $mobile_count = 0;
        $already_count = 0;
        foreach ($lists as $data) {
            $re_count++;

            if (!empty($data[3]) && is_numeric($data[3])) {
                
                // $phone = substr($data[3], 0, 8);
                $phone = substr($data[3], -8);
                $al_phone = substr($data[4], -8);
                $shipment_count = Shipment::where('barcode', $data[12])->count();
                $user = User::where('email', $phone)->first();

                if ($user) {
                    $user_id = $user->id;
                } else {
                    $user_id = \App\User::insertGetId([
                        'name' => $data[2],
                        'email' => $phone,
                        'role_id' => 5,
                        'password' => Hash::make($phone),
                    ]);
                }
                if ($shipment_count == 0) {
                  $rec_count++;
                    $shipment = Shipment::insert([
                        'pickup_date' => $data[0],
                        'upload_id' => $upload_id,
                        'consignee_phone' => $data[3],
                        'lead_id' => 0,
                        'upload_id' => 0,
                        'reference' => $data[1],
                        'consignee_name' => $data[2],
                        'alternate_phone' => $data[4],
                        'manifest_number' => $data[5],
                        'commodity_name' => $data[6],
                        'customer_civil_Id' => $data[7],
                        'receiver_civil_id' => $data[8],
                        'guardian_name' => $data[9],
                        'description' => $data[10],
                        'branch_name' => $data[11],
                        'barcode' => $data[12],
                        'tray_no' => $data[13],
                        'consignee_phone_upload' => $phone,
                        'alternate_phone_upload' => $al_phone,
                        'created_by' => Auth::User()->id,
                        'user_id' => $user_id,
                        'is_call_pushed' => 0,
                        'list_id' => $request->listid,
                        'cstatus' => $data[14]
                    ]);
                } else {
                    $already_count++;
                    $shipment = Shipment::where('barcode', $data[12])->update([
                        'pickup_date' => $data[0],
                        'upload_id' => $upload_id,
                        'consignee_phone' => $data[3],
                        'reference' => $data[1],
                        'consignee_name' => $data[2],
                        'alternate_phone' => $data[4],
                        'manifest_number' => $data[5],
                        'commodity_name' => $data[6],
                        'customer_civil_Id' => $data[7],
                        'receiver_civil_id' => $data[8],
                        'guardian_name' => $data[9],
                        'description' => $data[10],
                        'branch_name' => $data[11],
                        'tray_no' => $data[13],
                        'updated_by' => Auth::User()->id,
                        'user_id' => $user_id,
                        'cstatus' => $data[14]
                    ]);
                }

                Shipmentlog::insert([
                    'barcode' => $data[12],
                    'phone' => $phone,
                    'cstatus' => $data[14]
                ]);
            }
            else{
                $mobile_count++;
            }
        }
        $re_count = $re_count - 1;
        $mobile_count = $mobile_count - 1;
        Upload::where('id', $upload_id)->update([
            'upload_count' => $re_count,
            'uploaded_count' => $rec_count,
            'mobile_count' => $mobile_count,
            'already_count' => $already_count
        ]);
    } 

    else if ($request->fileformat == 'reschedule') {
        $re_count = 0;
        $rec_count = 0;

        foreach ($lists as $data) {
            $re_count++;
            if (!empty($data[0])){
                $shipment = Shipment::where('barcode', $data[0])->first();
                if($shipment){

                    $rec_count++;
                    Shipment::where('id', $shipment->id)->update([
                        'cstatus' => "COURIER RETURN",
                        'appointment_id' => 0
                    ]);
                }
            }
        }
    }

    else {
        $re_count = 0;
        $rec_count = 0;

        foreach ($lists as $data) {
            $re_count++;
            if (!empty($data[1]) && is_numeric($data[1])) {
                $lists = VicidialList::insert([
                    'first_name' => $data[0],
                    'phone_number' => $data[1],
                    'list_id' => $request->listid,
                    'entry_date' => date("Y-m-d H:i:s"),
                    'status' => 'NEW',
                    'called_since_last_reset' => 'N',
                    'batchno' => $request->batchno
                ]);
                $rec_count++;
            }
        }

        $lilog = $rec_count . " records Uploaded";

        DB::table('lists_log')->insert([
            'log' => $lilog,
            'listid' => $request->listid,
            'userid' => Auth::user()->id
        ]);
    }

    $alert = $rec_count . " Records Added Successfully";
    return Redirect::back()->with('alert', $alert);
}


public function uploadbk(Request $request)
{
//$data = $data[0];

print_r($lists); exit();

//print_r($ophtml); exit();

if($data->count()){

$rec_count = 0;

foreach ($data as $key => $value) {
//print_r($value);
if ($request->fileformat == 'general') {

if(!empty($value->customer_mobile )){
$lists = VicidialList::insert(['first_name' => $value->customer_name , 'phone_number' => $value->customer_mobile, 'vendor_lead_code' => $value->chassis_no, 'source_id' => $value->car_plate_no ,'carmodel_conversion' => $value->car_model ,'list_id' => $listid,'entry_date' => date("Y-m-d H:i:s"),'status'=>'NEW','called_since_last_reset'=>'N','batchno'=>$request->batchno]);
$rec_count++;
}

}
else if ($request->fileformat == 'sss') {

if(!empty($value->contact_number )){

$leadid = VicidialList::insertGetId(['first_name' => $value->customer_name , 'phone_number' => $value->contact_number, 'vendor_lead_code' => $value->chassis_number, 'source_id' => $value->registration ,'carmodel_conversion' => $value->model_code ,'list_id' => $listid,'entry_date' => date("Y-m-d H:i:s"),'status'=>'NEW','called_since_last_reset'=>'N','batchno'=>$request->batchno]);

DB::table('gm_sss')->insert(['leadid' => $leadid,'company' => $value->company, 'target' => $value->target, 'customer_name' => $value->customer_name, 'contact_number' => $value->contact_number ,'nationality' => $value->nationality ,'dateofbirth' => $value->dateofbirth,'gender' => $value->gender,'chassis_number' => $value->chassis_number,'registration' => $value->registration,'model_code' => $value->model_code,'modelyr' => $value->modelyr,'sa_code' => $value->sa_code,'sa_name' => $value->sa_name,'invoice_date' => $value->invoice_date,'dsa_code' => $value->dsa_code,'team_code' => $value->team_code]);

$rec_count++;
}

}
else if ($request->fileformat == 'pdss') {

if(!empty($value->contact_num )){

$leadid = VicidialList::insertGetId(['first_name' => $value->customer_name , 'phone_number' => $value->contact_num, 'vendor_lead_code' => $value->full_chassis, 'source_id' => $value->registration_no ,'carmodel_conversion' => $value->model ,'list_id' => $listid,'entry_date' => date("Y-m-d H:i:s"),'status'=>'NEW','called_since_last_reset'=>'N','batchno'=>$request->batchno]);

DB::table('gm_pdss')->insert(['leadid' => $leadid,'showroom' => $value->showroom, 'target' => $value->target, 'customer_name' => $value->customer_name, 'contact_num' => $value->contact_num ,'nationality' => $value->nationality ,'dateofbirth' => $value->dateofbirth,'gender' => $value->gender,'full_chassis' => $value->full_chassis,'registration_no' => $value->registration_no,'model' => $value->model,'myear' => $value->myear,'model_desc' => $value->model_desc,'salesmancode' => $value->salesmancode,'salesman_name' => $value->salesman_name,'delivery_date' => $value->delivery_date]);

$rec_count++;
}

}
else if ($request->fileformat == 'cec') {


if(!empty($value->vin)){ 
$phone = "";
$vehicles = MKvehic::join('mk_00_vlink','mk_00_vehic.magic', '=','mk_00_vlink.vehmagic')->select('mk_00_vlink.ctmagic')->where('mk_00_vehic.chassis',$value->vin)->get();
if (count($vehicles)>0) {
$tarmagic = $vehicles[0]->ctmagic;
$mktargt = MKtargt::select('magic','firstnam','surname','phone','phone002','phone004','phone001','phone003')->where('magic',$tarmagic)->first();
if($mktargt){
//echo $mktargt->FIRSTNAM;
$cusname = $mktargt->firstnam.' '.$mktargt->surname;
if(!empty($mktargt->phone004)){
    $phone = $mktargt->phone004;
}
else if(!empty($mktargt->phone002)){
    $phone = $mktargt->phone002;

}
else if(!empty($mktargt->phone003)){
    $phone = $mktargt->phone003;

}
else if(!empty($mktargt->phone001)){
    $phone = $mktargt->phone001;

}
else{
    $phone = $mktargt->phone;

}

$phone = preg_replace("/[^0-9]{1,4}/", '', $phone);

$phone = substr($phone, 0, 8);
}
}
// print_r($phone); exit();
if (!empty($phone)) {
$leadid = VicidialList::insertGetId(['first_name' => $cusname , 'phone_number' => $phone, 'vendor_lead_code' => $value->vin, 'source_id' => $value->magic_number ,'carmodel_conversion' => "" ,'list_id' => $listid,'entry_date' => date("Y-m-d H:i:s"),'status'=>'NEW','called_since_last_reset'=>'N','batchno'=>$request->batchno]);


DB::table('gm_cec')->insert(['leadid' => $leadid, 'customer_name' => $cusname, 'contact_num' => $phone,'full_chassis' => $value->vin,'registration_no' => "",'model' => "",'myear' => ""]);

$rec_count++;
}
}


}
else{

if(!empty($value->tel)){
$lists = VicidialList::insert(['alt_lead_id' => $value->lead_id, 'Year' => $value->year, 'Sourceoflead' => $value->source_of_lead, 'Enquiry_type' => $value->enquiry_type, 'Source_of_business' => $value->source_of_business, 'Siebel' => $value->siebel_sob, 'Month' => $value->month, 'Allocation_date' => $value->allocation_date, 'Allocation_time' => $value->allocation_time, 'first_name' => $value->customer_name, 'phone_number' => $value->tel, 'cc_agent' => $value->cc_agent, 'email' => $value->email, 'modelofinterest' => $value->model_of_interest, 'callcentre_feedback' => $value->call_centre_feedback, 'appointment' => $value->appointment, 'showroom' => $value->showroom, 'sale_consultant' => $value->sales_consultant, 'test_appointment' => $value->test_drive_appointment, 'month_sent' => $value->month_sent, 'salesman_feedback' => $value->salesman_feedback, 'Visited' => $value->visited, 'Date' => $value->date, 'showup_enquiry' => $value->salesman_showup_enquiry, 'sold' => $value->sold_date, 'carmodel_conversion' => $value->car_model_conversion ,'list_id' => $listid,'entry_date' => date("Y-m-d H:i:s"),'status'=>'NEW','called_since_last_reset'=>'N','batchno'=>$request->batchno]);
$rec_count++;
}

}


// /print_r($data);
}
$alert = $rec_count." Records Added Successfully";
//exit();
}

return redirect('/auto-dial')->with('alert', $alert);

}

public function bulkupload(Request $request)
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

$trays = Tray::where('delete_status',"0")->orderBy('name','asc')->get();
$commodities = Commodity::where('delete_status',0)->orderBy('name','asc')->get();
$cardtypes = Cardtype::where('delete_status',0)->orderBy('name','asc')->get();

$lists = Shipment::whereBetween('pickup_date', [$fromdate, $todate1])->where('is_call_pushed',0);
$listid='All';
if ($request->listid!='All' && !empty($request->listid)) {
$listid = $request->listid;
$lists = $lists->where("list_id", $listid);
}
$commodity_id='';
if (!empty($request->commodity_id)) {
$commodity_id = $request->commodity_id;
$lists = $lists->where("commodity_name", $commodity_id);
}
$cardtype_id='';
if (!empty($request->cardtype_id)) {
$cardtype_id = $request->cardtype_id;
$lists = $lists->where("description", $cardtype_id);
}
$tray_id='';
if (!empty($request->tray_id)) {
$tray_id = $request->tray_id;
$lists = $lists->where("tray_no", $tray_id);
}

if (!empty($request->pushcall)) {
$pushcall=$request->pushcall;
if($pushcall=='Yes'){
$uploadcalls= $lists;
$uploadcalls= $uploadcalls->get();
$upload_id = Upload::insertGetId(['list_id' => $uploadcalls[0]->list_id, 'batchno' => $request->batchno,'created_by'=> Auth::user()->id]);
foreach ($uploadcalls as $log) {
VicidialList::insert(['first_name' => $log->consignee_name, 'phone_number' => $log->consignee_phone_upload, 'vendor_lead_code' => $log->reference ,'list_id' => $log->list_id,'entry_date' => date("Y-m-d H:i:s"),'status'=>'NEW','called_since_last_reset'=>'N','batchno'=>$request->batchno]);
$leadids =  VicidialList::where('list_id',$request->listid)->where('phone_number',$phone)->where('status','NEW')->orderBy('lead_id','desc')->first();
$leadid = $leadids->lead_id;
Shipment::where('id',$log->id)->update(['upload_id' => $upload_id,'leadi_d' => $leadid,'is_call_pushed'=>1,'updated_by' => Auth::User()->id]);
}
}
}
//print_r(request()->has('pushcall')); exit();
$lists= $lists->get();
return view('leads.bulkuploadcsv',compact('lists','listid','fromdate','todate','lists','trays','commodities','cardtypes','tray_id','commodity_id','cardtype_id'));
}

public function bulkuploadstore(Request $request)
{
header('Content-Type: text/html; charset=utf-8');
$csvFilePath = $request->file('import_file')->getRealPath();
$dataPath = Excel::toCollection(null, $csvFilePath, null, \Maatwebsite\Excel\Excel::CSV);
$lists = $dataPath[0];
$upload = 0;
$not_upload = 0;
}

public function bulkmaster()
{
$trays = Shipment::select('tray_no')
->groupBy('tray_no')
->pluck('tray_no');

foreach ($trays as $trayNo) {
Tray::firstOrCreate(['name' => $trayNo]);
}

$cardtypes = Shipment::select('description')
->groupBy('description')
->pluck('description');

foreach ($cardtypes as $desc) {
Cardtype::firstOrCreate(['name' => $desc]);
}

$commodities = Shipment::select('commodity_name')
->groupBy('commodity_name')
->pluck('commodity_name');

foreach ($commodities as $name) {
Commodity::firstOrCreate(['name' => $name]);
}


}

}
