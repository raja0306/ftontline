<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VicidialAgentLog; 
use App\VicidialUsers;
use App\VicidialCloserLog;
use App\VicidialLog;
use App\Appointment;
use App\Average;
use Carbon\Carbon;
use DB;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function performance(){
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $divhtml = '';
        $divhtml1 = '';
        $divhtml1head = '';

        $agent_lists = VicidialAgentLog::whereBetween('event_time',[$fromdate, $todate1])->where('talk_sec','<','65000')->where('pause_sec','<','65000')->where('wait_sec','<','65000')->where('dispo_sec','<','65000')->where('status','!=',null)->select('user',DB::raw('count(*) as calls'), DB::raw('sum(talk_sec) as talk_secs'), DB::raw('sum(pause_sec) as pause_secs'), DB::raw('sum(wait_sec) as wait_secs'), DB::raw('sum(dead_sec) as dead_secs'))->groupBy('user')->get();

        $breaks = VicidialAgentLog::whereBetween('event_time',[$fromdate, $todate1])->select('sub_status', DB::raw('count(*) as count'))->where('sub_status','!=',null)->where('sub_status','!=','LAGGED')->groupBy('sub_status')->get();

        foreach ($breaks as $break) {
            $divhtml1head .= '<th>'.VicidialAgentLog::Status($break->sub_status).'</th>';
        }

        foreach($agent_lists as $agent){

        $ainbound = VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->where('user',$agent->user)->count();
        $appointments = Appointment::whereBetween('created_at',[$fromdate, $todate1])->where('agent',$agent->user)->get();
        $aoutbound = VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('user',$agent->user)->count();
                      $Slevel = VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->where('queue_seconds','<=','20')->where('user',$agent->user)->count();  
                  $call_time = $agent->talk_secs+$agent->pause_secs+$agent->wait_secs+$agent->dispo_secs;

                  $occ_time =$agent->talk_secs+$agent->pause_secs+$agent->dispo_secs;
                  $log_time =$occ_time+$agent->wait_secs;
                  if ($log_time != '0') {
                    $logperc = 100/$log_time;
                    $occperc = $occ_time*$logperc;
                    $occperc = number_format((float)$occperc, 2, '.', '');
                  }
                  else{
                    $occperc = 0;
                  }

                  if ($ainbound != '0') {
                    $inbperc = 100/$ainbound;
                    $Slevelperc = $Slevel*$inbperc;
                    $Slevelperc = number_format((float)$Slevelperc, 2, '.', '');
                  }
                  else{
                    $Slevelperc = 0;
                  }

		  $talk_avg = Average::MathZDC($agent->talk_secs,$agent->calls);
		                    $acalls = $ainbound+$aoutbound;
                  $divhtml .= '<tr>';
                    $divhtml .= '<td>'.VicidialUsers::FindName($agent->user).'</td>';
		  //$divhtml .= '<td>'.$agent->calls.'</td>';
		  $divhtml .= '<td>'.$acalls.'</td>';
                    $divhtml .= '<td>'.$ainbound.'</td>';
                    $divhtml .= '<td>'.$aoutbound.'</td>';
                    $divhtml .= '<td>'.Average::toMinutes($call_time).'</td>';
                    $divhtml .= '<td>'.Average::toMinutes($agent->pause_secs).'</td>';
                    $divhtml .= '<td>'.Average::toMinutes($agent->wait_secs).'</td>';
                    $divhtml .= '<td>'.Average::toMinutes($agent->talk_secs).'</td>';
                    $divhtml .= '<td>'.gmdate("H:i:s", $talk_avg).'</td>';
                    
                    
                    $divhtml .= '<td>'.Average::toMinutes($agent->dead_secs).'</td>';
                    $divhtml .= '<td>'.$appointments->count().'</td>';
                    $divhtml .= '<td>'.$appointments->where('address_type','!=',2)->count().'</td>';
                    $divhtml .= '<td>'.$appointments->where('address_type',2)->count().'</td>';
                    $divhtml .= '<td>'.$Slevelperc.'</td>';
                    $divhtml .= '<td>'.$occperc.'</td>';
                  $divhtml .= '</tr>';

                $agntdays = VicidialAgentLog::select('event_time','user')->whereBetween('event_time',[$fromdate, $todate1])->where('user',$agent->user)->orderBy('event_time')->get()->groupBy(function ($val) {
                return Carbon::parse($val->event_time)->format('d');
                });

                $pause_time = $call_time-$agent->pause_secs;
                $divhtml1 .= '<tr class="">';
                $divhtml1 .= '<td>'.VicidialUsers::FindName($agent->user).'</td>';
                $divhtml1 .= '<td>'.count($agntdays).'</td>';
                $divhtml1 .= '<td>'.Average::toMinutes($call_time).'</td>';
                $divhtml1 .= '<td>'.Average::toMinutes($agent->pause_secs).'</td>';
                $divhtml1 .= '<td>'.Average::toMinutes($pause_time).'</td>';

                foreach ($breaks as $break) {
                    $breaksec = VicidialAgentLog::whereBetween('event_time',[$fromdate, $todate1])->where('user',$agent->user)->where('sub_status',$break->sub_status)->sum('pause_sec');
                    $divhtml1 .= '<td>'.Average::toMinutes($breaksec).'</td>';
                }
                  $divhtml1 .= '</tr>';
        }

        return view('agent.performance',compact('fromdate','todate','todate1','divhtml','divhtml1head','divhtml1'));
    }

    public function time(){
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $divhtml = '';
        $divhtml1 = '';
        $divhtml1head = '';

        $agent_lists = VicidialAgentLog::whereBetween('event_time',[$fromdate, $todate1])->where('talk_sec','<','65000')->where('pause_sec','<','65000')->where('wait_sec','<','65000')->where('dispo_sec','<','65000')->where('status','!=',null)->select('user',DB::raw('count(*) as calls'), DB::raw('sum(talk_sec) as talk_secs'), DB::raw('sum(pause_sec) as pause_secs'), DB::raw('sum(wait_sec) as wait_secs'), DB::raw('sum(dead_sec) as dead_secs'))->groupBy('user')->get();

        foreach($agent_lists as $agent){
              $call_time = $agent->talk_secs+$agent->pause_secs+$agent->wait_secs+$agent->dispo_secs;
              $talk_avg = Average::MathZDC($agent->talk_secs,$agent->calls);
              $pause_avg = Average::MathZDC($agent->pause_secs,$agent->calls);
              $wait_avg = Average::MathZDC($agent->wait_secs,$agent->calls);
              $dead_avg = Average::MathZDC($agent->dead_secs,$agent->calls);
              $divhtml .= '<tr>';
                $divhtml .= '<td>'.VicidialUsers::FindName($agent->user).'</td>';
                $divhtml .= '<td>'.$agent->calls.'</td>';
                $divhtml .= '<td>'.Average::toMinutes($call_time).'</td>';
                $divhtml .= '<td>'.Average::toMinutes($agent->pause_secs).'</td>';
                $divhtml .= '<td>'.gmdate("H:i:s", $pause_avg).'</td>';
                $divhtml .= '<td>'.Average::toMinutes($agent->wait_secs).'</td>';
                $divhtml .= '<td>'.gmdate("H:i:s", $wait_avg).'</td>';
                $divhtml .= '<td>'.Average::toMinutes($agent->talk_secs).'</td>';
                $divhtml .= '<td>'.gmdate("H:i:s", $talk_avg).'</td>';
                $divhtml .= '<td>'.Average::toMinutes($agent->dead_secs).'</td>';
                $divhtml .= '<td>'.gmdate("H:i:s", $dead_avg).'</td>';
              $divhtml .= '</tr>';

      }

        return view('agent.time',compact('fromdate','todate','todate1','divhtml'));
    }

    public function kpilogs($fromdate='',$todate='',$user='All'){
        $divhtml = '';
        $agentname = '';
        $agentlogs[] = '';
        if (empty($fromdate) && empty($todate)){
            $fromdate = date('Y-m-d');
            $todate = date('Y-m-d');
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
       	$startTime = null; $endTime = null; 
        $hideagents = '6666,VDAD,VDCL';
        $agents = VicidialUsers::select('user','full_name')->whereNotIn('user',explode(",", $hideagents))->orderBy('user','asc')->get();

        if ($user != 'All') {
        $agent_name = VicidialUsers::select('full_name')->where('user',$user)->first();
        if ($agent_name) {
            $agentname = $agent_name->full_name;
        }

        $startTime = strtotime($fromdate);
        $endTime = strtotime($todate);
	}
	        return view('agent.kpi',compact('fromdate','todate','todate1','user','agents','startTime','endTime','agentname'));
    }
}

