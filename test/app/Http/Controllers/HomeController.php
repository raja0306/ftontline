<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use Redirect;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $fromdate = date("Y-m-d");
        $todate = date("Y-m-d");
        if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        return view('home',compact('fromdate', 'todate'));
    }

    public function users()
    {
        $users = \App\User::where('role_id','!=','5')->orderBy('id','asc')->get();
        $roles = \App\Role::get();
        return view('users',compact('users','roles'));
    }

    public function profile()
    {
        $users = \App\User::where('email','!=',null)->orderBy('id','asc')->get();
        return view('profile',compact('users'));
    }

    public function userstore(Request $request)
    {
        print_r($request->all()); exit();
        $user_id = \App\User::insertGetId([
            'name' => $request['name'],
            'email' => $request['email'],
            'role_id' => $request['role_id'],
            'parent_id' => $request['parent_id'],
            'password' => Hash::make($request['password']),
            'mobile' => $request['mobile'],
            'address' => $request['address']
        ]);

        if(!empty($request['file_image'])){
            $fileName = 'img_' . $user_id . '.png';
            $folder = public_path('users');
            $file = $request->file('file_image');
            $fileContents = file_get_contents($file->getRealPath());
            file_put_contents($folder . '/' . $fileName, $fileContents);
            $path = 'public/users/' . $fileName;
            \App\User::where('id',$user_id)->update(['imgae_file'=>$path]);

        }
        

        $campaign = implode(",", $request->campaign);
        $list_id = implode(",", $request->list_id);
        $ingroup = implode(",", $request->ingroup);
        $agent = implode(",", $request->agent);
        $campaign_1 = implode(",", $request->campaign_1);
        $list_id_1 = implode(",", $request->list_id_1);
        $ingroup_1 = implode(",", $request->ingroup_1);
        $agent_1 = implode(",", $request->agent_1);
        DB::table('user_details')->insert(['user_id'=>$user_id,'campaign'=>$campaign,'list_id'=>$list_id,'ingroup'=>$ingroup,'agent'=>$agent,'campaign_1'=>$campaign_1,'list_id_1'=>$list_id_1,'ingroup_1'=>$ingroup_1,'agent_1'=>$agent_1]);
        return redirect('/users');
    }

    public function userupdate(Request $request)
    {
        $campaign = implode(",", $request->campaign);
        $list_id = implode(",", $request->list_id);
        $ingroup = implode(",", $request->ingroup);
        $agent = implode(",", $request->agent);
        $campaign_1 = \App\UserDetails::getColumn('campaign_1');
        $list_id_1 = \App\UserDetails::getColumn('list_id_1');
        $ingroup_1 = \App\UserDetails::getColumn('ingroup_1');
        $agent_1 = \App\UserDetails::getColumn('agent_1');
        DB::table('user_details')->where('user_id',Auth::user()->id)->where('delete_status',0)->update(['delete_status'=>1]);
        DB::table('user_details')->where('user_id',Auth::user()->id)->insert(['user_id'=>Auth::user()->id,'campaign'=>$campaign,'list_id'=>$list_id,'ingroup'=>$ingroup,'agent'=>$agent,'campaign_1'=>$campaign_1,'list_id_1'=>$list_id_1,'ingroup_1'=>$ingroup_1,'agent_1'=>$agent_1]);
        return Redirect::back();
    }

    public function logincheck(Request $request)
    {
        $credentials = $request->only('email', 'password'); 
        if (Auth::attempt($credentials)) {
            return redirect()->intended('home');
        } else {
            return back()->withErrors([
                'error' => 'The provided credentials do not match our records.',
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function authuser($user_id)
    {
        $user = \App\User::find($user_id);
        Auth::login($user);
        return Redirect::back();
    }

    public function all_groups()
    {
        $lists = DB::connection('mysql2')->table('vicidial_campaigns')->select('campaign_id')->orderBy('campaign_id','asc')->get();
        foreach ($lists as $list) { echo $list->campaign_id.","; } echo "<br>";
        $lists = DB::connection('mysql2')->table('vicidial_inbound_groups')->select('group_id')->orderBy('group_id','asc')->get();
        foreach ($lists as $list) { echo $list->group_id.","; } echo "<br>";
        $lists = DB::connection('mysql2')->table('vicidial_lists')->select('list_id')->orderBy('list_id','asc')->get();
        foreach ($lists as $list) { echo $list->list_id.","; } echo "<br>";
        $lists = DB::connection('mysql2')->table('vicidial_users')->select('user')->orderBy('user','asc')->get();
        foreach ($lists as $list) { echo $list->user.","; } echo "<br><br><br><br>";
        $lists = DB::connection('mysql5')->table('vicidial_campaigns')->select('campaign_id')->orderBy('campaign_id','asc')->get();
        foreach ($lists as $list) { echo $list->campaign_id.","; } echo "<br>";
        $lists = DB::connection('mysql5')->table('vicidial_inbound_groups')->select('group_id')->orderBy('group_id','asc')->get();
        foreach ($lists as $list) { echo $list->group_id.","; } echo "<br>";
        $lists = DB::connection('mysql5')->table('vicidial_lists')->select('list_id')->orderBy('list_id','asc')->get();
        foreach ($lists as $list) { echo $list->list_id.","; } echo "<br>";
        $lists = DB::connection('mysql5')->table('vicidial_users')->select('user')->orderBy('user','asc')->get();
        foreach ($lists as $list) { echo $list->user.","; } echo "<br><br><br><br>";


        echo "<br><br><br><br>User:".Auth::user()->name."<br><br><br><br>";
        $campaigns = \App\UserDetails::getColumn('campaign');
        $campaigns1 = \App\UserDetails::getColumn('campaign_1');
        $lists = DB::connection('mysql2')->table('vicidial_campaigns')->select('campaign_id')->orderBy('campaign_id','asc')->get();
        foreach ($lists as $list) { echo $list->campaign_id.","; } echo "<br>";
        $lists = DB::connection('mysql2')->table('vicidial_inbound_groups')->select('group_id')->orderBy('group_id','asc')->get();
        foreach ($lists as $list) { echo $list->group_id.","; } echo "<br>";
        $lists = DB::connection('mysql2')->table('vicidial_lists')->select('list_id')->whereIn("campaign_id", explode(",", $campaigns))->orderBy('list_id','asc')->get();
        foreach ($lists as $list) { echo $list->list_id.","; } echo "<br>";
        $lists = DB::connection('mysql2')->table('vicidial_users')->select('user')->orderBy('user','asc')->get();
        foreach ($lists as $list) { echo $list->user.","; } echo "<br><br><br><br>";
        $lists = DB::connection('mysql5')->table('vicidial_campaigns')->select('campaign_id')->orderBy('campaign_id','asc')->get();
        foreach ($lists as $list) { echo $list->campaign_id.","; } echo "<br>";
        $lists = DB::connection('mysql5')->table('vicidial_inbound_groups')->select('group_id')->orderBy('group_id','asc')->get();
        foreach ($lists as $list) { echo $list->group_id.","; } echo "<br>";
        $lists = DB::connection('mysql5')->table('vicidial_lists')->select('list_id')->whereIn("campaign_id", explode(",", $campaigns1))->orderBy('list_id','asc')->get();
        foreach ($lists as $list) { echo $list->list_id.","; } echo "<br>";
        $lists = DB::connection('mysql5')->table('vicidial_users')->select('user')->orderBy('user','asc')->get();
        foreach ($lists as $list) { echo $list->user.","; } echo "<br><br><br><br>";

    }

    public function centrix_metrics()
    {
        $month = null; $year = null;
        if(!empty($_GET['month'])){ $month = $_GET['month']; }
        if(!empty($_GET['year'])){ $year = $_GET['year']; }

        $lists = DB::table('callcounts_report2')->where('delete_status',0);
        $month = '';
        if(!empty($_GET['month'])){
            $month = $_GET['month']; $lists = $lists->where('month',$_GET['month']);
        }
        $year = '';
        if(!empty($_GET['year'])){
            $year = $_GET['year']; $lists = $lists->where('year',$_GET['year']);
        }
        $lists = $lists->get(); 

        return view('centrix_metrics',compact('month','year','lists'));
    }

    public function generate_metrics()
    {
        $countall = 0;
        $months = 1; if(!empty($_GET['month'])){ $months = $_GET['month']; }
	$year = date("Y"); if(!empty($_GET['year'])){ $year = $_GET['year']; }
	//echo $months;  exit();
        for ($i=$months; $i <= $months; $i++) {
            $month = sprintf('%02d', $i);
            $isalready = DB::table('callcounts_report2')->where('month',$month)->where('year',$year)->where('delete_status',0)->count();
            if($isalready==0){
            $fdate = $year.'-'.$month.'-01';
            $ttdate = date("Y-m-t", strtotime($fdate));
            $tdate1 = date('Y-m-d', strtotime("+1 day", strtotime($ttdate)));
            $outbound_calls = \App\VicidialLog::whereBetween('call_date',[$fdate, $tdate1])->count();    
            $outbound_connected = \App\VicidialLog::where('length_in_sec','>','0')->whereBetween('call_date',[$fdate, $tdate1])->count();
            $attemps = \App\VicidialLog::whereBetween('call_date',[$fdate, $tdate1])
                 ->select(DB::raw('count(*) as attemps'))
                 ->groupBy('phone_number')
                 ->get();

            $mstatus = 'DROP,NANQUE';   
            $inbound_calls = \App\VicidialCloserLog::whereBetween('call_date',[$fdate, $tdate1])->count(); 
            $missed_total = \App\VicidialCloserLog::whereBetween('call_date',[$fdate, $tdate1])->whereIn('status',explode(",", $mstatus))->count();

            $inbound_calls_after1 = \App\VicidialCloserLog::whereBetween('call_date',[$fdate, $tdate1])->whereIn('status',explode(",", $mstatus))->where('campaign_id','like','%after%')->count(); 
            $inbound_calls_after2 = 0;
            $total_inbound_calls_after = $inbound_calls_after1+$inbound_calls_after2;

            $missedcalls = \App\VicidialCloserLog::select('phone_number','lead_id','call_date')->whereBetween('call_date',[$fdate, $tdate1])->whereIn('status',explode(",", $mstatus))->get();
            $talksecs = \App\VicidialCloserLog::whereBetween('call_date',[$fdate, $tdate1])->whereNotIn('status',explode(",", $mstatus))->sum('length_in_sec'); 
            $queuesecs = \App\VicidialCloserLog::whereBetween('call_date',[$fdate, $tdate1])->whereNotIn('status',explode(",", $mstatus))->sum('queue_seconds'); 
            $Slevel = \App\VicidialCloserLog::whereBetween('call_date',[$fdate, $tdate1])->where('queue_seconds','<=','15')->count(); 
            $slevelperc = \App\Average::MathPER($Slevel,$inbound_calls);
            $wait_sec = \App\VicidialAgentLog::select('user','status','talk_sec','pause_sec','wait_sec','dispo_sec','dead_sec','user_group','campaign_id')->where('status','!=',null)->whereBetween('event_time',[$fdate, $tdate1])->sum('wait_sec');

            $all_secs = \App\VicidialAgentLog::select('user','status','talk_sec','pause_sec','wait_sec','dispo_sec','dead_sec','user_group','campaign_id')->where('status','!=',null)->whereBetween('event_time',[$fdate, $tdate1]);
            $dispo_sec =$all_secs->sum('dispo_sec');
            $wait_sec =$all_secs->sum('wait_sec');
            $pause_sec =$all_secs->sum('pause_sec');
            $occ_time =$all_secs->sum('talk_sec')+$pause_sec+$dispo_sec;  
            $log_time =$occ_time+$wait_sec;
            $occperc = \App\Average::MathPER($occ_time,$log_time);
            
            $log_id = DB::table('callcounts_report2')->insertGetId(['outbound'=>$outbound_calls,'outbound_connected'=>$outbound_connected,'attempts'=>$attemps->count(),'inbounds'=>$inbound_calls,'missed'=>$missed_total,'missed_after'=>$total_inbound_calls_after,'missed_after1'=>$inbound_calls_after1,'missed_after2'=>$inbound_calls_after2,'net_missed'=>0,'month'=>$month,'year'=>$year,'talksecs'=>$talksecs,'queuesecs'=>$queuesecs,'wait_time'=>$wait_sec,'slevel'=>$Slevel,'slevelperc'=>$slevelperc,'log_time'=>$log_time,'occ_time'=>$occ_time,'occperc'=>$occperc ]);

            $net_missed = 0;
            foreach ($missedcalls as $missed) {
                $phone = substr($missed->phone_number, -8);
                $status_log = \App\VicidialLog::where('phone_number',$phone)->whereBetween('call_date',[$missed->call_date, date('Y-m-d', strtotime("+7 days", strtotime($missed->call_date)))])->where('length_in_sec','>','0')->count();
                if($status_log == '0'){ 
                    $status_answer = \App\VicidialCloserLog::where('phone_number',$phone)->whereBetween('call_date',[$missed->call_date, date('Y-m-d', strtotime("+7 days", strtotime($missed->call_date)))])->count();
                    if($status_log == 0){ $net_missed++; DB::table('callcounts_report2')->where('id',$log_id)->update(['net_missed'=>$net_missed]); }
                }
            }
            $missedcalls_after = \App\VicidialCloserLog::select('phone_number','call_date')->whereBetween('call_date',[$fdate, $tdate1])->whereIn('status',explode(",", $mstatus))->where('campaign_id','like','%after%')->get();
            $net_missed_after = 0;
            foreach ($missedcalls_after as $missed) {
            $phone = substr($missed->phone_number, -8);
            $status_log = \App\VicidialLog::where('phone_number',$phone)->whereBetween('call_date',[$missed->call_date, date('Y-m-d', strtotime("+7 days", strtotime($missed->call_date)))])->where('length_in_sec','>','0')->count();
            if($status_log == '0'){ 
            $status_answer = \App\VicidialCloserLog::where('phone_number',$phone)->whereBetween('call_date',[$missed->call_date, date('Y-m-d', strtotime("+7 days", strtotime($missed->call_date)))])->whereNotIn('status',explode(",", $mstatus))->count();
                    if($status_log == 0){ $net_missed_after++; DB::table('callcounts_report2')->where('id',$log_id)->update(['net_missed_after'=>$net_missed_after]); }
                }
            }
            DB::table('callcounts_report2')->where('id',$log_id)->update(['net_missed'=>$net_missed,'net_missed_work'=>$net_missed-$net_missed_after,'net_missed_after'=>$net_missed_after]);
            }
        }
        return Redirect::back()->with('msg','Enquiry added');
    }


    public function getcampaignlists($campaign_id)
    {
        $lists = \App\VicidialLists::select('list_id','list_name')->where('campaign_id',$campaign_id)->orderBy('list_name','asc')->get();
        $dropdown = '<option value="">Select</option>';
        foreach ($lists as $list) {
            $dropdown .= '<option value="'.$list->list_id.'">'.$list->list_name.'</option>';
        }
        echo json_encode(array("dropdownhtml"=>$dropdown));
    }
}
