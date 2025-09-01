<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\VicidialCloserLog;
class CallLogController extends Controller
{
public function __construct()
{
$this->middleware('auth');
}

public function index()
{
$fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
$todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
$todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
$call_type = 'in'; if(!empty($_GET['call_type'])){ $call_type = $_GET['call_type']; }
$phone = null; 
$list_id = 'All';
$group = 'All';

if($call_type=='out'){
$old_logs = \App\Old\VicidialLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->whereBetween('call_date',[$fromdate, $todate1])->get();
$new_logs = \App\VicidialLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->whereBetween('call_date',[$fromdate, $todate1])->get();

if(!empty($_GET['phone'])){ 
$phone = $_GET['phone']; 
$old_logs = $old_logs->where('phone_number',$phone);
$new_logs = $new_logs->where('phone_number',$phone);
}

if(!empty($_GET['list_id'])&&($_GET['list_id']!='All')){ 
$list_id = $_GET['list_id']; 
$old_logs = $old_logs->where('list_id',$list_id);
$new_logs = $new_logs->where('list_id',$list_id); 
}

if(!empty($_GET['group'])&&($_GET['group']!='All')){ 
$group = $_GET['group']; 
$old_logs = $old_logs->where('campaign_id',$group);
$new_logs = $new_logs->where('campaign_id',$group); 
}

$array1 = $old_logs->toArray(); $array2 = $new_logs->toArray(); $mergedArray = array_merge($array1, $array2);
$logs = $mergedArray;
}
else{
$old_logs = \App\Old\VicidialCloserLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->where('length_in_sec','>','0')->whereBetween('call_date',[$fromdate, $todate1])->get();
$new_logs = \App\VicidialCloserLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->where('length_in_sec','>','0')->whereBetween('call_date',[$fromdate, $todate1])->get();

if(!empty($_GET['phone'])){ 
$phone = $_GET['phone']; 
$old_logs = $old_logs->where('phone_number',$phone);
$new_logs = $new_logs->where('phone_number',$phone);
}

if(!empty($_GET['list_id'])&&($_GET['list_id']!='All')){ 
$list_id = $_GET['list_id']; 
$old_logs = $old_logs->where('list_id',$list_id);
$new_logs = $new_logs->where('list_id',$list_id); 
}

if(!empty($_GET['group'])&&($_GET['group']!='All')){ 
$group = $_GET['group']; 
$old_logs = $old_logs->where('campaign_id',$group);
$new_logs = $new_logs->where('campaign_id',$group); 
}
$array1 = $old_logs->toArray(); $array2 = $new_logs->toArray(); $mergedArray = array_merge($array1, $array2);
$logs = $mergedArray;
}

return view('calls.all',compact('fromdate','todate','phone', 'logs','call_type','list_id','group'));
}

public function inbound()
{
$fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
$todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
$todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
$campaign = 'All'; if(!empty($_GET['campaign'])){ $campaign = $_GET['campaign']; }
$status = 'All'; if(!empty($_GET['status'])){ $status = $_GET['status']; }
$phone = null;  if(!empty($_GET['phone'])){ $phone = $_GET['phone']; }
$mstatus = \App\Settings::getColumn('name','missed','nameval');

if (empty($fromdate) && empty($todate)){
            $fromdate = date('Y-m-d');
	            $todate = date('Y-m-d');
	        }
    $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

    $dial_logs = \App\VicidialCloserLog::select('lead_id','phone_number','queue_seconds','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch','called_count')->whereBetween('call_date',[$fromdate, $todate1]);

        if($campaign != 'All'){ $dial_logs = $dial_logs->where('campaign_id',$campaign); } 
        if(!empty($phone)){ $dial_logs = $dial_logs->where('phone_number',$phone); }     
        if($status=='missed'){ $dial_logs = $dial_logs->whereIn('status',explode(",", $mstatus)); }     
        $dial_logs = $dial_logs->get();

        return view('calls.inbound',compact('fromdate','todate','todate1','phone','campaign','dial_logs','status'));
        }

public function missed()
{
$fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
$todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
$todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
$campaign = 'All'; if(!empty($_GET['campaign'])){ $campaign = $_GET['campaign']; }
$status = 'All'; if(!empty($_GET['status'])){ $status = $_GET['status']; }
$reset = 'false'; if(!empty($_GET['reset'])){ $reset = $_GET['reset']; }
$phone = null;  if(!empty($_GET['phone'])){ $phone = $_GET['phone']; }
$mstatus = \App\Settings::getColumn('name','missed','nameval');

if (empty($fromdate) && empty($todate)){
            $fromdate = date('Y-m-d');
                $todate = date('Y-m-d');
            }
    $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

    $dial_logs = \App\VicidialCloserLog::select('lead_id','phone_number','queue_seconds','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch','called_count')->whereBetween('call_date',[$fromdate, $todate1]);

        if($campaign != 'All'){ $dial_logs = $dial_logs->where('campaign_id',$campaign); } 
        if(!empty($phone)){ $dial_logs = $dial_logs->where('phone_number',$phone); }     
        if($status=='missed'){ $dial_logs = $dial_logs->whereIn('status',explode(",", $mstatus)); }     
        $dial_logs = $dial_logs->get();
        $reset_count=0;
        if($reset=='true'){
            $li=1001;
            foreach ($dial_logs as $dial_log) {
                
                $already = \App\VicidialList::where('phone_number',$dial_log->phone_number)->where('list_id',$li)->where('status','NEW')->count();
                $miss = VicidialCloserLog::NetMissed($dial_log->phone_number,$dial_log->call_date);
                
                if($already==0 && $miss=="Net Missed"){
                    $reset_count++;
                    \App\VicidialList::insert(['phone_number' => $dial_log->phone_number,'list_id' =>$li,'entry_date' => date("Y-m-d H:i:s"),'status'=>'NEW','called_since_last_reset'=>'N','rank'=>10]);
                }
                
            }
            $lilog = $reset_count." Missed call reset Uploaded";

            DB::table('lists_log')->insert(['log' => $lilog,'listid' => $li,'userid' => Auth::user()->id]);
        }
        return view('calls.missed',compact('fromdate','todate','todate1','phone','campaign','dial_logs','status'));
        }

public function outbound()
{
$fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
$todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
$todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
$campaign = 'All'; if(!empty($_GET['campaign'])){ $campaign = $_GET['campaign']; }
$status = 'All'; if(!empty($_GET['status'])){ $status = $_GET['status']; }
$phone = null;  if(!empty($_GET['phone'])){ $phone = $_GET['phone']; }
$mstatus = \App\Settings::getColumn('name','missed','nameval');

if (empty($fromdate) && empty($todate)){
            $fromdate = date('Y-m-d');
	            $todate = date('Y-m-d');
	        }
    $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

    $dial_logs = \App\VicidialLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch','called_count')->whereBetween('call_date',[$fromdate, $todate1]);

        if($campaign != 'All'){ $dial_logs = $dial_logs->where('list_id',$campaign); } 
        if(!empty($phone)){ $dial_logs = $dial_logs->where('phone_number',$phone); }      
        if($status=='connected'){ $dial_logs = $dial_logs->where('length_in_sec','>',0); }    
        $dial_logs = $dial_logs->get();

        return view('calls.outbound',compact('fromdate','todate','todate1','phone','campaign','dial_logs','status'));
        }
  
}
