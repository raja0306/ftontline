<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Auth;
use App\VicidialList;
use App\VicidialLists;
use App\Upload;
use App\Shipment;
use App\Shipmentlog;
use App\User;
use App\Tray;
use App\Branch;
use App\Commodity;
use App\Cardtype;
use Redirect;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\File;

class ShipmentuploadController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request)
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
        $vlists= VicidialLists::where("list_id", ">", 0)->orderBy('list_name','asc')->get();
        if ($request->listid!='All' && !empty($request->listid)) {
            $listid = $request->listid;
            $lists = $lists->where("list_id", $listid);
        }
        $lists = $lists->orderBy('id','desc')->get(); 

        if(!empty($request->searchtype) && !empty($request->searchval)){

            $lists =Shipment::where($request->searchtype,$request->searchval)->get();

            return view("leads.shipments", compact("lists"));
            
        }

        $banks= User::where('role_id','4')->get();
        return view("uploads.uploadshipment", compact("fromdate", "todate", "lists",'listid','vlists','banks'));
	}

	public function upload(Request $request)
	{
		header('Content-Type: text/html; charset=utf-8');
		$csvFilePath = $request->file('import_file')->getRealPath();
    	$dataPath = Excel::toCollection(null, $csvFilePath, null, \Maatwebsite\Excel\Excel::CSV);
    	$lists = $dataPath[0];

    	$file_count = 0;
    	$upload_count = 0;
    	$mobile_count= 0;
        $upload_id = Upload::insertGetId([
            'created_by' => Auth::id(),
            'bank_id' => $request->bank_id,
        ]);
    	foreach ($lists as $data) {
    		$file_count++;
    		if (!empty($data[3]) && is_numeric($data[3])) {
    			$phone = substr($data[3], -8);
                $al_phone = substr($data[4], -8);

                //check User
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

                //check Commodity
                $commodity = Commodity::where('name', $data[6])->first();
                if ($commodity) {
                    $commodity_id = $commodity->id;
                } else {
                    $commodity_id = \App\Commodity::insertGetId([
                        'name' => $data[6],
                    ]);
                }

                //check Cardtype
                $cardtype = Cardtype::where('name', $data[10])->first();
                if ($cardtype) {
                    $cardtype_id = $cardtype->id;
                } else {
                    $cardtype_id = \App\Cardtype::insertGetId([
                        'name' => $data[10],
                    ]);
                }


                //check Branch
                $branch = Branch::where('name', $data[11])->first();
                if ($branch) {
                    $branch_id = $branch->id;
                } else {
                    $branch_id = \App\Branch::insertGetId([
                        'name' => $data[11],
                    ]);
                }

                //check Tray
                $tray = Tray::where('name', $data[12])->first();
                if ($tray) {
                    $tray_id = $tray->id;
                }
                else if(empty($data[12])){
                	$tray_id = 0;
                }
                else {
                    $tray_id = \App\Tray::insertGetId([
                        'name' => $data[12],
                    ]);
                }

                $timestamp = strtotime($data[0]);
                $pickup_date = date('Y-m-d', $timestamp); 

                $upload_count++;
                if(empty($data[14])){
                    $shipment_id = \App\Shipment::insertGetId([
                        'upload_id' => $upload_id,
                        'pickup_date' => $pickup_date,
                        'reference' => $data[1],
                        'consignee_name' => $data[2],
                        'consignee_phone' => $data[3],
                        'alternate_phone' => $data[4],
                        'manifest_number' => $data[5],
                        'commodity_name' => $data[6],
                        'customer_civil_Id' => $data[7],
                        'receiver_civil_id' => $data[8],
                        'guardian_name' => $data[9],
                        'description' => $data[10],
                        'branch_name' => $data[11],
                        'tray_no' => $data[12],
                        'user_id' => $user_id,
                        'status_id' => 1,
                        'branch_id' => $branch_id,
                        'bank_id' =>$request->bank_id,
                        'cardtype_id' => $cardtype_id,
                        'commodity_id' => $commodity_id,
                        'tray_id' => $tray_id,
                        'created_by' => Auth::id(),
                        'consignee_phone_upload' => $phone,
                        'alternate_phone_upload' => $al_phone,
                        'is_call_pushed' => 0,
                    ]);
                    if(empty($data[13])){
                        $barcode = self::genrateBarcode($shipment_id,$branch_id,$request->bank_id);
                    }
                    else{
                        $barcode = $data[13];
                    }
                    
                    $fileName = 'barcode_' . $barcode . '.png';
                    $file_name = 'public/barcodes/' . $fileName;

                    Shipment::where('id', $shipment_id)->update(['barcode'=>$barcode,'file_name'=>$file_name]);

                    self::CreateShipmentinfo($shipment_id,"New Shipment Created");
                    if($tray_id!=0){
                        self::CreateShipmentinfo($shipment_id,$data[12]."Tray Updated");
                    }
                }
                else{
                     \App\Shipment::where('id',$data[14])->update([
                        'upload_id' => $upload_id,
                        'pickup_date' => $pickup_date,
                        'reference' => $data[1],
                        'consignee_name' => $data[2],
                        'consignee_phone' => $data[3],
                        'alternate_phone' => $data[4],
                        'manifest_number' => $data[5],
                        'commodity_name' => $data[6],
                        'customer_civil_Id' => $data[7],
                        'receiver_civil_id' => $data[8],
                        'guardian_name' => $data[9],
                        'description' => $data[10],
                        'branch_name' => $data[11],
                        'tray_no' => $data[12],
                        'user_id' => $user_id,
                        'status_id' => 1,
                        'branch_id' => $branch_id,
                        'bank_id' =>$request->bank_id ,
                        'cardtype_id' => $cardtype_id,
                        'commodity_id' => $commodity_id,
                        'tray_id' => $tray_id,
                        'created_by' => Auth::id(),
                        'consignee_phone_upload' => $phone,
                        'alternate_phone_upload' => $al_phone,
                        'is_call_pushed' => 0,
                    ]);

                    self::CreateShipmentinfo($data[14],"Shipment Info Updated");

                }
                

    		}
    		else{
    			$mobile_count++;
    		}
    	}

        Upload::where('id', $upload_id)->update([
                'upload_count' => max(0, $file_count - 1),
                'uploaded_count' => $upload_count,
                'mobile_count' => max(0, $mobile_count - 1)
            ]);

        return back()->with('alert', "$upload_count Records Added Successfully");
	}

    public function CreateShipmentinfo($shipment_id='',$message='',$old_data='',$new_data='')
    {
        if (Auth::check()) {
            $created_by = Auth::id();
        }
        else{
            $created_by = 0;
        }
        $shipmentinfo_id = \App\Shipmentinfo::insertGetId([
                        'shipment_id' => $shipment_id,
                        'message' => $message,
                        'old_data' => $old_data,
                        'new_data' => $new_data,
                        'created_by' => $created_by
                    ]);
        return $shipmentinfo_id;
    }

	public function genrateBarcode($shipment_id='',$branch_id,$bank_id)
	{
		$barcode='';
		
        $bank=User::find($bank_id)->name;

		$barcode =str_pad($shipment_id, 7, '0', STR_PAD_LEFT);
		$barcode=$bank.$barcode;

        // Generate barcode image
        // $generator = new BarcodeGeneratorPNG();
        // $barcodeData = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);

        // // Define path
        // $fileName = 'barcode_' . $barcode . '.png';
        // $folder = public_path('barcodes'); // You can change to storage_path if needed
        // //print_r($folder); exit();

        // // Ensure folder exists
        // if (!File::exists($folder)) {
        //     File::makeDirectory($folder, 0755, true);
        // }

        // // Save image
        // file_put_contents($folder . '/' . $fileName, $barcodeData);

        return $barcode; // Return URL to access the image

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

		$trays = Shipment::with('tray')->whereBetween('created_at', [$fromdate, $todate1])
                ->where('appointment_id', 0)
                ->select('tray_id')
                ->groupBy('tray_id')
                ->get();
        //dd($trays); exit();
		$commodities = Shipment::with('commodity')->whereBetween('created_at', [$fromdate, $todate1])
                ->where('appointment_id', 0)
                ->select('commodity_id')
                ->groupBy('commodity_id')
                ->get();
		$cardtypes = Shipment::with('cardtype')->whereBetween('created_at', [$fromdate, $todate1])
                ->where('appointment_id', 0)
                ->select('cardtype_id')
                ->groupBy('cardtype_id')
                ->get();

		$lists = Shipment::whereBetween('created_at', [$fromdate, $todate1])->where('appointment_id',0);
        $vlists= VicidialLists::where("list_id", ">", 0)->orderBy('list_name','asc')->get();
		$listid='All';
		if ($request->listid!='All' && !empty($request->listid)) {
			$listid = $request->listid;
			$lists = $lists->where("list_id", $listid);
		}
		$commodity_id='';
		if (!empty($request->commodity_id)) {
			$commodity_id = $request->commodity_id;
			$lists = $lists->where("commodity_id", $commodity_id);
		}
		$cardtype_id='';
		if (!empty($request->cardtype_id)) {
			$cardtype_id = $request->cardtype_id;
			$lists = $lists->where("cardtype_id", $cardtype_id);
		}
		$tray_id='';
		if (!empty($request->tray_id)) {
			$tray_id = $request->tray_id;
			$lists = $lists->where("tray_id", $tray_id);
		}

		if (!empty($request->pushcall)) {
			$pushcall=$request->pushcall;
			if($pushcall=='Yes'){
                $request->validate([
                    'batchno' => 'required',
                    'listid' => 'required',
                ], [
                    'batchno.required' => 'Batch number is required.',
                    'listid.required' => 'List ID is required.',
                ]);
                $upload_count=0;
                $already_count=0;
				$uploadcalls= $lists;
				$uploadcalls= $uploadcalls->where('block_call','0')->get();
				$upload_id = Upload::insertGetId(['list_id' => $uploadcalls[0]->list_id, 'batchno' => $request->batchno,'created_by'=> Auth::user()->id]);
				foreach ($uploadcalls as $log) {
                    $existingLead = VicidialList::where([
                        ['phone_number', $log->consignee_phone_upload],
                        ['list_id', $request->listid],
                        ['status', 'NEW']
                    ])->count();
                    if ($existingLead==0) {
                        $upload_count++;
    					VicidialList::insert(['first_name' => $log->consignee_name, 'phone_number' => $log->consignee_phone_upload, 'vendor_lead_code' => $log->reference ,'list_id' => $request->listid,'entry_date' => date("Y-m-d H:i:s"),'status'=>'NEW','called_since_last_reset'=>'N','batchno'=>$request->batchno,'rank'=>$request->priority]);
    					$leadids =  VicidialList::where('list_id',$request->listid)->where('phone_number',$log->consignee_phone_upload)->where('status','NEW')->orderBy('lead_id','desc')->first();
    					$leadid = $leadids->lead_id;
                        $is_call_pushed= $log->is_call_pushed;
                        $is_call_pushed= $is_call_pushed+1;
    					Shipment::where('id',$log->id)->update(['upload_id' => $upload_id,'lead_id' => $leadid,'list_id' => $request->listid,'is_call_pushed'=>$is_call_pushed,'updated_by' => Auth::User()->id,'status_id'=>3]);

                        self::CreateShipmentinfo($log->id,"Call Initiated");
                    }
                    else{
                        $already_count++;
                        $leadids =  VicidialList::where('list_id',$request->listid)->where('phone_number',$log->consignee_phone_upload)->where('status','NEW')->orderBy('lead_id','desc')->first();
                        $leadid = $leadids->lead_id;
                        $is_call_pushed= $log->is_call_pushed;
                        $is_call_pushed= $is_call_pushed+1;
                        Shipment::where('id',$log->id)->update(['upload_id' => $upload_id,'lead_id' => $leadid,'list_id' => $request->listid,'is_call_pushed'=>$is_call_pushed,'updated_by' => Auth::User()->id,'status_id'=>3]);

                        self::CreateShipmentinfo($log->id,"Call Initiated");
                    }
                    app('App\Http\Controllers\ShipmentuploadController')->CreateShipmentinfo($log->id,'CALLING ASSIGNED');
				}
                $alert = $upload_count . " Records Added Successfully";
                return Redirect::back()->with('alert', $alert);
			}
		}
		//print_r(request()->has('pushcall')); exit();
		$lists= $lists->get();
		return view('leads.bulkuploadcsv',compact('lists','listid','fromdate','todate','lists','trays','commodities','cardtypes','tray_id','commodity_id','cardtype_id','vlists'));
	}
}