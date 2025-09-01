<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
	$mstatus = \App\Settings::getColumn('name','missed','nameval');


        $outbound = \App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->count();
        $outbound_connected = \App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('length_in_sec','>','0')->count();
        $inbound = \App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->count();
        $missed = \App\VicidialCloserLog::whereIn('status',explode(",", $mstatus))->whereBetween('call_date',[$fromdate, $todate1])->count();
        $drop = \App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('status','DROP')->count();
        $na = \App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('status','NA')->count();
        $busy = \App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->whereIn('status',['AB','B'])->count();

        return view('dashboard.dashboard', compact('fromdate','todate','outbound','outbound_connected','inbound','missed','drop','na','busy'));
    }

    public function net_missed()
    {
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $mstatus = \App\Settings::getColumn('name','missed','nameval');
	$missedcalls = \App\VicidialCloserLog::whereIn('status',explode(",", $mstatus))->whereBetween('call_date',[$fromdate, $todate1]);
	        $stype = null; if(!empty($_GET['type'])){ $stype = $_GET['type']; }
	        if(!empty($stype)){
			            if($stype=='work'){ $missedcalls = $missedcalls->where('campaign_id','like','%after%'); }
				                else if($stype=='after'){ $missedcalls = $missedcalls->where('campaign_id','not like','%after%'); }
				            }
	
	        $missedcalls = $missedcalls->get();
        $net_missed = 0;
        foreach ($missedcalls as $missed) {
            $phone = substr($missed->phone_number, -8);
            $status_log = \App\VicidialLog::where('phone_number',$phone)->whereBetween('call_date',[$missed->call_date, date('Y-m-d', strtotime("+7 days", strtotime($missed->call_date)))])->where('length_in_sec','>','0')->count();
            if($status_log == '0'){ 
                $status_answer = \App\VicidialCloserLog::where('phone_number',$phone)->whereBetween('call_date',[$missed->call_date, date('Y-m-d', strtotime("+7 days", strtotime($missed->call_date)))])->whereNotIn('status',explode(",", $mstatus))->count();
                if($status_log == 0){ $net_missed++; }
            }
	}
	$net_missed_work = ''; $net_missed_after = '';
	$inbound = 0; if(!empty($_GET['inbound'])){ $inbound = $_GET['inbound']; }
	if(!empty($stype)){
		            $inbound = 0; if(!empty($_GET['inbound'])){ $inbound = $_GET['inbound']; }
			            if($stype=='work'){ $net_missed = $net_missed.' - '.\App\Average::MathPER($net_missed,$inbound).'%'; }
				                else if($stype=='after'){ $net_missed = $net_missed.' - '.\App\Average::MathPER($net_missed,$inbound).'%'; }
				            }
	//	echo $net_missed_work; exit();
	$net_missed = $net_missed.' - '.\App\Average::MathPER($net_missed,$inbound).'%';
        return view('dashboard.net_missed', compact('net_missed','net_missed_work','net_missed_after'));
    }

    public function outbound_summary(){

        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

        $upload_lists = \App\VicidialList::whereBetween('entry_date',[$fromdate, $todate1])->count();
        $dial_dialable = \App\VicidialList::whereBetween('entry_date',[$fromdate, $todate1])->where('status','NEW')->count();

        $attemps = \App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->select(DB::raw('count(*) as attemps'))->groupBy('phone_number')->get();
        $attempts = $attemps->count();
        $outbound = \App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->count();
        $outbound_connected =\App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('length_in_sec','>','0')->count();

        $outbound_avg = \App\Average::MathZDC($attempts,$outbound);
        $outbound_success = \App\Average::MathPER($outbound_connected,$outbound); 
        
        return view('dashboard.outbound_summary', compact('outbound','attempts','outbound_avg','upload_lists','dial_dialable','outbound_connected','outbound_success'));

    }

    public function agent_summary(){

        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $top_agents = \App\VicidialAgentLog::whereBetween('event_time',[$fromdate, $todate1])->where('talk_sec','<','65000')->where('pause_sec','<','65000')->where('wait_sec','<','65000')->where('dispo_sec','<','65000')->where('status','!=',null)->select('user',DB::raw('count(*) as calls'), DB::raw('sum(talk_sec) as talk_secs'), DB::raw('sum(pause_sec) as pause_secs'), DB::raw('sum(wait_sec) as wait_secs'), DB::raw('sum(dead_sec) as dead_secs'))->groupBy('user')->get();
        //print_r($top_agents); exit();
        $agentcounts = $top_agents->count();
        
        return view('dashboard.agent_summary', compact('top_agents','agentcounts','fromdate','todate','todate1'));

    }

    public function hourly_calls()
    {
        $day = date('d'); $month = date('m'); $year = date('Y');
                if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate'];
                $day = date('d', strtotime($fromdate)); $month = date('m', strtotime($fromdate)); $year = date('Y', strtotime($fromdate)); 
                    }
        $hricalls = '';
        $hrmcalls = '';
        $hrocalls = '';
        $hroccalls = '';
        for ($time=0; $time <= 24 ; $time++) { 
            $time1 = $time+1;
            $fromtime = date("$year-$month-$day $time:00:00");
            $totime = date("$year-$month-$day $time1:00:00");
            $mstatus = \App\Settings::getColumn('name','missed','nameval');
            $hourcalls = \App\VicidialCloserLog::whereBetween('call_date',[$fromtime, $totime])->count();
            $mcalls = \App\VicidialCloserLog::whereBetween('call_date',[$fromtime, $totime])->whereIn('status',explode(",", $mstatus))->count();
            $ocalls = \App\VicidialLog::whereBetween('call_date',[$fromtime, $totime])->count();
            $occalls = \App\VicidialLog::whereBetween('call_date',[$fromtime, $totime])->where('length_in_sec','>','0')->count();

            $hricalls .= $hourcalls.',';
            $hrmcalls .= $mcalls.',';
            $hrocalls .= $ocalls.',';
            $hroccalls .= $occalls.',';

        }

        $startOfWeek = now()->startOfWeek(\Carbon\Carbon::SATURDAY)->format('Y-m-d');
        $endOfWeek = now()->endOfWeek()->format('Y-m-d');
        $weekly = \App\VicidialCloserLog::
            select(DB::raw('DATE(call_date) as day'), DB::raw('COUNT(*) as total'))
            ->whereBetween(DB::raw('DATE(call_date)'), [$startOfWeek, $endOfWeek])
            ->groupBy(DB::raw('DATE(call_date)'))
            ->orderBy('day')
            ->get();

        $wkicalls = '';
        $wkmcalls = '';
        $wkocalls = '';
        $wkoccalls = '';
        $wnames = '';
        foreach ($weekly as $wkly) {
            //echo $wkly->day.' - '.$wkly->total.'<br>';
            $wkfromdate = date("$wkly->day 00:00:00");
            $wktodate = date("$wkly->day 23:59:59");
            $wnames .= '"'.date("d M",strtotime(date("$wkly->day"))).'",';
            $wkicalls .= $wkly->total.',';
            $wkmcalls .= \App\VicidialCloserLog::whereBetween('call_date',[$wkfromdate, $wktodate])->whereIn('status',explode(",", $mstatus))->count().',';
            $wkocalls .= \App\VicidialLog::whereBetween('call_date',[$wkfromdate, $wktodate])->count().',';
            $wkoccalls .= \App\VicidialLog::whereBetween('call_date',[$wkfromdate, $wktodate])->where('length_in_sec','>','0')->count().',';
        }

        $date30 = now()->subDays(30);
        $monthly = \App\VicidialCloserLog::select(DB::raw('DATE(call_date) as date, COUNT(*) as total'))
            ->where('call_date', '>=', $date30)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $mnicalls = '';
        $mnmcalls = '';
        $mnocalls = '';
        $mnoccalls = '';
        $dnames = '';
        foreach ($monthly as $mnly) {
            $mnfromdate = date("$mnly->date 00:00:00");
            $mntodate = date("$mnly->date 23:59:59");
            $dnames .= '"'.date("d",strtotime(date("$mnly->date"))).'",';
            $mnicalls .= $mnly->total.',';
            $mnmcalls .= \App\VicidialCloserLog::whereBetween('call_date',[$mnfromdate, $mntodate])->whereIn('status',explode(",", $mstatus))->count().',';
            $mnocalls .= \App\VicidialLog::whereBetween('call_date',[$mnfromdate, $mntodate])->count().',';
            $mnoccalls .= \App\VicidialLog::whereBetween('call_date',[$mnfromdate, $mntodate])->where('length_in_sec','>','0')->count().',';
        }

        $yearly = \App\VicidialCloserLog::select(DB::raw('YEAR(call_date) as year, MONTH(call_date) as month, COUNT(*) as total'))
            ->where('call_date', '>=', Carbon::now()->subMonths(12)) // Get records from the last 12 months
            ->groupBy(DB::raw('YEAR(call_date), MONTH(call_date)'))
            ->orderBy(DB::raw('YEAR(call_date), MONTH(call_date)'))
            ->get();

        $yricalls = '';
        $yrmcalls = '';
        $yrocalls = '';
        $yroccalls = '';
        $mnames = '';
        foreach ($yearly as $yrly) {
            $yfromdate = date("$yrly->year-$yrly->month-01 00:00:00");
            $ytodate = date("$yrly->year-$yrly->month-t 23:59:59");
            $mnames .= '"'.date("M",strtotime(date("$yrly->year-$yrly->month-01"))).'",';
            $yricalls .= $yrly->total.',';
            $yrmcalls .= \App\VicidialCloserLog::whereBetween('call_date',[$yfromdate, $ytodate])->whereIn('status',explode(",", $mstatus))->count().',';
            $yrocalls .= \App\VicidialLog::whereBetween('call_date',[$yfromdate, $ytodate])->count().',';
            $yroccalls .= \App\VicidialLog::whereBetween('call_date',[$yfromdate, $ytodate])->where('length_in_sec','>','0')->count().',';
        }
        //print_r($yricalls);print_r($yrmcalls); exit();

        return view('dashboard.hourly_calls', compact('hricalls','hrmcalls','hrocalls','hroccalls','yricalls','yrmcalls','yrocalls','yroccalls','mnames','mnicalls','mnmcalls','mnocalls','mnoccalls','dnames','wkicalls','wkmcalls','wkocalls','wkoccalls','wnames'));
    }


    public function monthly_calls()
    {
        $month = date("m"); if(!empty($_GET['month'])){ $month=$_GET['month']; }
        $date1 = date("2024-$month-01 H:i:s"); $date30 = date("2024-$month-t H:i:s");
        $mstatus = \App\Settings::getColumn('name','missed','nameval');
        $monthly = \App\VicidialCloserLog::select(DB::raw('DATE(call_date) as date, COUNT(*) as total'))
            ->whereBetween('call_date',[$date1, $date30])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        $mnicalls = '';
        $mnmcalls = '';
        $mnocalls = '';
        $mnoccalls = '';
        $dnames = '';
        foreach ($monthly as $mnly) {
            $mnfromdate = date("$mnly->date 00:00:00");
            $mntodate = date("$mnly->date 23:59:59");
            $dnames .= '"'.date("d",strtotime(date("$mnly->date"))).'",';
            $mnicalls .= $mnly->total.',';
            $mnmcalls .= \App\VicidialCloserLog::whereBetween('call_date',[$mnfromdate, $mntodate])->whereIn('status',explode(",", $mstatus))->count().',';
            $mnocalls .= \App\VicidialLog::whereBetween('call_date',[$mnfromdate, $mntodate])->count().',';
            $mnoccalls .= \App\VicidialLog::whereBetween('call_date',[$mnfromdate, $mntodate])->where('length_in_sec','>','0')->count().',';
    }

    $type = 1; if(!empty($_GET['type'])){ $type=$_GET['type']; }
        $Mhtml = view('dashboard.monthly_calls', compact('mnicalls','mnmcalls','mnocalls','mnoccalls','dnames','type'))->render();
        echo json_encode(array("Mhtml"=>$Mhtml));
    }


    public function live_calls()
    {
        $liveagents = \App\VicidialLiveAgent::select('live_agent_id','user','extension','status','campaign_id','conf_exten','calls_today')->orderBy('status','asc')->get();
        return view('dashboard.live_calls', compact('liveagents'));
    }

    public function queue_calls()
    {
        $queue_calls = \App\VicidialAutoCalls::where('status','=','LIVE')->get();
        return view('dashboard.queue_calls', compact('queue_calls'));
    }
    
    public function list_dashboards1()
    {
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $list_idss = \App\VicidialLists::select('list_id','list_name')->orderBy('list_name','asc')->get();   
        return view('dashboard.list_details1', compact('fromdate','todate','todate1','list_idss'));
    }
    public function list_dashboards()
    {
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $list_idss = \App\VicidialLists::select('list_id','list_name')->orderBy('list_name','asc')->get();   
        return view('dashboard.list_details', compact('fromdate','todate','todate1','list_idss'));
    }


        public function gmafternetmissed()
		    {


			            $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
				            $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
				            $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
				            $mstatus = \App\Settings::getColumn('name','missed','nameval');
					            $missedcalls = \App\VicidialCloserLog::whereIn('status',explode(",", $mstatus))->whereBetween('call_date',[$fromdate, $todate1]);
					            $stype = null; if(!empty($_GET['type'])){ $stype = $_GET['type']; }
						            if(!empty($stype)){
								                if($stype=='work'){ 
											                $inbound = \App\VicidialCloserLog::where('campaign_id','not like','%after%')->whereBetween('call_date',[$fromdate, $todate1])->count();
													                $missedcalls = $missedcalls->where('campaign_id','not like','%after%'); 
													            }
										            else if($stype=='after'){ 
												                    $inbound = \App\VicidialCloserLog::where('campaign_id','like','%after%')->whereBetween('call_date',[$fromdate, $todate1])->count();
														                    $missedcalls = $missedcalls->where('campaign_id','like','%after%'); 
														                }
										        }

						            $missedcallcount = $missedcalls->count();
						            $missedcalls = $missedcalls->get();
							            $net_missed = 0;
							            foreach ($missedcalls as $missed) {
									            $phone = substr($missed->phone_number, -8);
										            $status_log = \App\VicidialLog::where('phone_number',$phone)->whereBetween('call_date',[$missed->call_date, date('Y-m-d', strtotime("+7 days", strtotime($missed->call_date)))])->where('length_in_sec','>','0')->count();
										            if($status_log == '0'){ 
												                $status_answer = \App\VicidialCloserLog::where('phone_number',$phone)->whereBetween('call_date',[$missed->call_date, date('Y-m-d', strtotime("+7 days", strtotime($missed->call_date)))])->whereNotIn('status',explode(",", $mstatus))->count();
														            if($status_log == 0){ $net_missed++; }
														        }
											            }
								            $missedpercentage = \App\Average::MathPER($net_missed,$inbound);
								            return view('dashboard.gm_netmissed', compact('net_missed','inbound','missedcallcount','missedpercentage'));
									            
									        }


    public function gmaftermissed()
    {
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

        $mstatus = \App\Settings::getColumn('name','missed','nameval');
        $netmissed = 0;
        $inbound = \App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->count();
        $missed = \App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->whereIn('status',explode(",", $mstatus))->count();

        $missedpercentage = \App\Average::MathPER($missed,$inbound);
        $netpercentage = \App\Average::MathPER($netmissed,$inbound);

        $cadins = '';
        $chevins = '';
        $ingroups = \App\UserDetails::getColumn('ingroup');
        $inarray = explode(",",$ingroups);
        foreach ($inarray as $string) {
            if(stripos($string, 'chev') !== false){ $chevins .= $string.','; }
            if(stripos($string, 'cadi') !== false){ $cadins .= $string.','; }
        }
        $chevins = rtrim($chevins, ", ");
        $cadins = rtrim($cadins, ", ");
        //echo $chevins."<br>".$cadins; exit();


        $cadincoming = \App\VicidialCloserLog::whereIn('campaign_id',explode(",", $cadins))->whereBetween('call_date',[$fromdate, $todate1])->count();
        $chevincoming = \App\VicidialCloserLog::whereIn('campaign_id',explode(",", $chevins))->whereBetween('call_date',[$fromdate, $todate1])->count();
        $cadmissed = \App\VicidialCloserLog::whereIn('campaign_id',explode(",", $cadins))->whereIn('status',explode(",",$mstatus))->whereBetween('call_date',[$fromdate, $todate1])->count();
        $chevmissed = \App\VicidialCloserLog::whereIn('campaign_id',explode(",", $chevins))->whereIn('status',explode(",",$mstatus))->whereBetween('call_date',[$fromdate, $todate1])->count();

        $avgcadmissed = \App\Average::MathPER($cadmissed,$cadincoming);
        $avgchevmissed = \App\Average::MathPER($chevmissed,$chevincoming);


        $afterinbound = \App\VicidialCloserLog::where('campaign_id','like','%after%')->whereBetween('call_date',[$fromdate, $todate1])->count();
        $aftermissed = \App\VicidialCloserLog::whereIn('status',explode(",", $mstatus))->where('campaign_id','like','%after%')->whereBetween('call_date',[$fromdate, $todate1])->count();
        $aftermissedpercentage = \App\Average::MathPER($aftermissed,$afterinbound);

        $workinbound = $inbound-$afterinbound;
        $workmissed = $missed-$aftermissed;
        $workmissedpercentage = \App\Average::MathPER($workmissed,$workinbound);

        $gmtocec_calls = \App\VicidialCloserLog::whereIn('campaign_id',['Chev_En_Status','Chev_Ar_Status','Cadi_En_Status','Cadi_Ar_Status'])->whereBetween('call_date',[$fromdate, $todate1])->count();

        $gmtocec_missedcalls = \App\VicidialCloserLog::whereIn('campaign_id',['Chev_En_Status','Chev_Ar_Status','Cadi_En_Status','Cadi_Ar_Status'])->whereIn('status',explode(",",$mstatus))->whereBetween('call_date',[$fromdate, $todate1])->count();
        $gmtocec_percentage = \App\Average::MathPER($gmtocec_missedcalls,$gmtocec_calls);

        $cectogm_calls = \App\VicidialCloserLog::whereIn('campaign_id',['Chev_En_CEC_Status','Cadi_En_CEC_Status','Chev_Ar_CEC_Status','Cadi_Ar_CEC_Status'])->whereBetween('call_date',[$fromdate, $todate1])->count();

        $cectogm_missedcalls = \App\VicidialCloserLog::whereIn('campaign_id',['Chev_En_CEC_Status','Cadi_En_CEC_Status','Chev_Ar_CEC_Status','Cadi_Ar_CEC_Status'])->whereIn('status',explode(",",$mstatus))->whereBetween('call_date',[$fromdate, $todate1])->count();
        $cectogm_percentage = \App\Average::MathPER($cectogm_missedcalls,$cectogm_calls);

        return view('dashboard.gm_missed', compact('cadincoming','cadmissed','avgcadmissed','chevincoming','chevmissed','avgchevmissed','afterinbound','aftermissed','aftermissedpercentage','workinbound','workmissed','workmissedpercentage','gmtocec_calls','gmtocec_missedcalls','gmtocec_percentage','cectogm_calls','cectogm_missedcalls','cectogm_percentage'));
        
    }

        public function pause_agents()
		    {
			            $pauseagents = DB::connection('mysql2')->table('vicidial_live_agents')->select('live_agent_id','user','extension','status','campaign_id','conf_exten','calls_today','pause_code')->where('pause_code','DCMX')->get();
				    foreach($pauseagents as $agent){
					    echo $agent->user."<br>";
						                $this->resume_dcmx_agents($agent->user);
								        }
				        }

        public function resume_dcmx_agents($user)
		    {
			            $curl = curl_init();

				            curl_setopt_array($curl, array(
						              CURLOPT_URL => 'https://172.16.4.163/agent/api.php?source=test&user=6666&pass=centrixplus&agent_user='.$user.'&function=external_pause&value=RESUME',
							                CURLOPT_RETURNTRANSFER => true,
									          CURLOPT_ENCODING => '',
										            CURLOPT_MAXREDIRS => 10,
											              CURLOPT_SSL_VERIFYHOST=>0,
												                CURLOPT_SSL_VERIFYPEER=>0,
														          CURLOPT_TIMEOUT => 0,
															            CURLOPT_FOLLOWLOCATION => true,
																              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
																	                CURLOPT_CUSTOMREQUEST => 'GET',
																			        ));

				            $response = curl_exec($curl);

				            curl_close($curl);
					            echo $response;
					        }



    public function pause_agentsbk()
    {
        $pauseagents = \App\VicidialLiveAgent::select('live_agent_id','user','extension','status','campaign_id','conf_exten','calls_today')->where('status','PAUSED')->get();

        foreach($pauseagents as $agent){
            $pausecode = \App\VicidialAgentLog::select('user','sub_status','agent_log_id')->where('user',$agent->user)->orderBy('agent_log_id','desc')->first();
            print_r($agent); echo "<br>===<br>";
            print_r($pausecode); echo "<br>===<br>";
            if($pausecode->sub_status=='DCMX'){
                print_r($agent->user); echo "<br>===<br>";
                $this->resume_dcmx_agents($agent->user);
            }
        }
    }

    public function resume_dcmx_agentsbk($user)
    {
        $this->pause_dcmx_agents($user);
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://172.16.4.163/agent/api.php?source=test&user=6666&pass=centrixplus&agent_user='.$user.'&function=external_pause&value=RESUME',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }


    public function pause_dcmx_agentsbk($user)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://172.16.4.163/agent/api.php?source=test&user=6666&pass=centrixplus&agent_user='.$user.'&function=external_pause&value=PAUSE',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }

    public function onehrmissed()
    {
        $fromdate = date('Y-m-d H:i:s');
        $todate1 = date('Y-m-d H:i:s', strtotime("-1 hour", strtotime($fromdate)));
        $mstatus = \App\Settings::getColumn('name','missed','nameval');
        $ingroups = \App\UserDetails::getColumnUser(8,'ingroup');
        $missedcalls = DB::connection('mysql2')->table('vicidial_closer_log')->select('lead_id','phone_number','call_date','campaign_id')->whereBetween('call_date',[$todate1,$fromdate])->whereIn('status',explode(",", $mstatus))->whereIn('campaign_id',explode(",", $ingroups))->get();
	//print_r($missedcalls); exit();
        $net_missed = 0;
        foreach ($missedcalls as $missed) {
            $phone = substr($missed->phone_number, -8);
            $status_log = DB::connection('mysql2')->table('vicidial_log')->where('phone_number',$phone)->whereBetween('call_date',[$missed->call_date, date('Y-m-d', strtotime("+7 days", strtotime($missed->call_date)))])->count();
            if($status_log == '0'){
                $status_answer = DB::connection('mysql2')->table('vicidial_closer_log')->where('phone_number',$phone)->whereBetween('call_date',[$missed->call_date, date('Y-m-d', strtotime("+7 days", strtotime($missed->call_date)))])->whereNotIn('status',explode(",", $mstatus))->count();
                if($status_log == 0){ 
                    $status_call = DB::connection('mysql2')->table('vicidial_list')->where('phone_number', $phone)->where('status','NEW')->count();
                    if($status_call == 0){ 
                        $net_missed++; 
                        DB::connection('mysql2')->table('vicidial_list')->insert(['phone_number' => $phone,'first_name' => null,'list_id' => 1001,'entry_date' => date("Y-m-d H:i:s"),'status'=>'NEW','called_since_last_reset'=>'N','vendor_lead_code' => 0,'source_id' => null,'update_status' => $missed->campaign_id,'batchno'=>'remised '.$fromdate]);
                       // echo $phone."<br>";
                    }
                }
            }
        }
        DB::table('cronlog')->insert(['name'=>$net_missed.' missedcall updated', 'date_added'=>$fromdate]);
    }


    public function sendwa_yellow_chery($phone="",$templateId="",$brand="",$leadid="",$user="")
    {
        if(strlen($phone) <= 8){ $phone = '965'.$phone; }

        $api_key = '6lyVid-kpZAFVJtXz0eiK3S9duVcUYHtmt6m77_8';
        $sender = '9651831111';
        $botid = 'x1685439798949';

        if($brand==1){
            $api_key = 'f8zNBqe8h-sMOSKezfJ-16E3DVIMw4iAwSOXH-Wj';
            $sender = '9651832333';
            $botid = 'x1685439074024';
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://r1.cloud.yellow.ai/api/engagements/notifications/v2/push?bot='.$botid.'',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "userDetails": {
                "number": "'.$phone.'"
            },
            "notification": {
                "type": "whatsapp",
                "sender": "'.$sender.'",
                "templateId": "'.$templateId.'"
            }
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-key: '.$api_key.''

          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo json_encode(array("response"=>$response));
    }


}

