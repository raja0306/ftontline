<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordingController extends Controller
{
/**
*      * Create a new controller instance.
*           *
*                * @return void
*                     */
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

if($call_type=='all'){
            $old_logs = \App\VicidialLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->whereBetween('call_date',[$fromdate, $todate1])->get();
	            $new_logs = \App\VicidialLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->whereBetween('call_date',[$fromdate, $todate1])->get();

	            $old_logs_in = \App\VicidialCloserLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->whereBetween('call_date',[$fromdate, $todate1])->limit(0)->get();
		                $new_logs_in = \App\VicidialCloserLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->whereBetween('call_date',[$fromdate, $todate1])->get();

		                if(!empty($_GET['phone'])){ 
					                $phone = $_GET['phone']; 
							                $old_logs = $old_logs->where('phone_number',$phone);
							                $new_logs = $new_logs->where('phone_number',$phone);
									                $old_logs_in = $old_logs_in->where('phone_number',$phone);
									                $new_logs_in = $new_logs_in->where('phone_number',$phone);
											            }

				            if(!empty($_GET['list_id'])&&($_GET['list_id']!='All')){ 
						                    $list_id = $_GET['list_id']; 
								                    $old_logs = $old_logs->where('list_id',$list_id);
								                    $new_logs = $new_logs->where('list_id',$list_id);
										                    $old_logs_in = $old_logs->where('list_id',$list_id);
										                    $new_logs_in = $new_logs->where('list_id',$list_id);  
												                }

				            if(!empty($_GET['group'])&&($_GET['group']!='All')){ 
						                    $group = $_GET['group']; 
								                    $old_logs = $old_logs->where('campaign_id',$group);
								                    $new_logs = $new_logs->where('campaign_id',$group); 
										                    $old_logs_in = $old_logs->where('campaign_id',$group);
										                    $new_logs_in = $new_logs->where('campaign_id',$group);
												                }

				            $array1 = $old_logs->toArray(); $array2 = $new_logs->toArray(); $array3 = $old_logs_in->toArray(); $array4 = $new_logs_in->toArray(); 
				            $mergedArray = array_merge($array1, $array2, $array3, $array4);
					                $logs = $mergedArray;
					            }

    else if($call_type=='out'){
                $old_logs = \App\VicidialLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->whereBetween('call_date',[$fromdate, $todate1])->limit(0)->get();
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
                $old_logs = \App\VicidialCloserLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->whereBetween('call_date',[$fromdate, $todate1])->get();
		            $new_logs = \App\VicidialCloserLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->whereBetween('call_date',[$fromdate, $todate1])->get();

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

    return view('records.all',compact('fromdate','todate','phone', 'logs','call_type','list_id','group'));
}


public function recordall()
{
$fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
$todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
$todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
$phone = null; 
	$logs = null; 

if(!empty($_GET['phone'])){ 

				                    $phone = $_GET['phone']; 
					$logs = \App\VicidialRecord::whereBetween('start_time',[$fromdate, $todate1])->where('filename','like','%'.$phone.'%')->limit(100)->get();
//print_r($logs); exit();
}
			return view('records.recordall',compact('fromdate','todate','phone', 'logs'));

		}


public function incoming()
{
$fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
$todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
$todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
$call_type = 'in'; if(!empty($_GET['call_type'])){ $call_type = $_GET['call_type']; }
$phone = null; 

$old_logs = \App\VicidialCloserLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->where('length_in_sec','>','0')->whereBetween('call_date',[$fromdate, $todate1])->limit(0)->get();
$new_logs = \App\VicidialCloserLog::select('lead_id','phone_number','campaign_id','list_id','call_date','status','user','length_in_sec','end_epoch')->where('length_in_sec','>','0')->whereBetween('call_date',[$fromdate, $todate1])->get();

if(!empty($_GET['phone'])){ 
            $phone = $_GET['phone']; 
	            $old_logs = $old_logs->where('phone_number',$phone);
	            $new_logs = $new_logs->where('phone_number',$phone);
		            }
    $array1 = $old_logs->toArray(); $array2 = $new_logs->toArray(); $mergedArray = array_merge($array1, $array2);
    $logs = $mergedArray;

        return view('records.all',compact('fromdate','todate','phone', 'logs','call_type'));
    }
}

