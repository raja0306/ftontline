<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use App\VicidialList;
use App\Upload;
use App\Shipment;
use App\Shipmentlog;
use App\User;
use App\Tray;
use App\Commodity;
use App\Cardtype;
use App\Webhooklog;
use Redirect;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
   public function cronmissed()
  {
    $todate1 = date('Y-m-d H:i:s');
    $fromdate = date('Y-m-d H:i:s', strtotime("-30 minutes", strtotime($todate1)));
    $reset_count=0;
    $leads='';
    $mstatus = \App\Settings::getColumn('name','missed','nameval');
   	$dial_logs = \App\VicidialCloserLog::select('lead_id','phone_number','queue_seconds','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch','called_count')->whereBetween('call_date',[$fromdate, $todate1])->whereIn('status',explode(",", $mstatus))->get();
   	$li=1001;
   	$batch_no=date('YmdHis');

      foreach ($dial_logs as $dial_log) {
                
        $already = \App\VicidialList::where('phone_number',$dial_log->phone_number)->where('list_id',$li)->where('status','NEW')->count();
        $miss = \App\VicidialCloserLog::NetMissed($dial_log->phone_number,$dial_log->call_date);
                
        if($already==0 && $miss=="Net Missed"){
            $reset_count++;
            self::insertleadmissedlist($dial_log->phone_number);
              $leads='';
        }
      
      }
      $lilog = $reset_count."Cron Missed call reset Uploaded".$leads;

      DB::table('lists_log')->insert(['log' => $lilog,'listid' => $li,'userid' => 1]);
      return true;
  }

  public function insertleadmissedlist($mobile)
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://192.168.1.253/admin/non_agent_api.php?source=frontline&user=6666&pass=centrixplus&function=add_lead&phone_number='.$mobile.'&phone_code=1&list_id=1001&dnc_check=N&add_to_hopper=Y&hopper_local_call_time_check=Y&hopper_priority=99',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Cookie: SERVER_USED=s1|aK7O6|aK7O6'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
  }
}