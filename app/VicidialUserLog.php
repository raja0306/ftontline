<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VicidialUserLog extends Model
{
	protected $connection = 'mysql2';
    protected  $table="vicidial_user_log";

    public static function Login($date,$user){
        $logtime = '';
        $ftime = $date.' 00:00:00';
        $ttime = $date.' 23:59:59';
        $lists = VicidialUserLog::select('event_date')->whereBetween('event_date',[$ftime, $ttime])->where('event','LOGIN')->where('user',$user)->orderBy('user_log_id','asc')->first();
        if ($lists) {
            $logtime = $lists->event_date;
        }
        return $logtime;
    }    

    public static function Logout($date,$user){
        $logtime = '';
        $ftime = $date.' 00:00:00';
        $ttime = $date.' 23:59:59';
        $lists = VicidialUserLog::select('event_date')->whereBetween('event_date',[$ftime, $ttime])->where('event','LOGOUT')->where('user',$user)->orderBy('user_log_id','desc')->first();
        if ($lists) {
            $logtime = $lists->event_date;
        }
        return $logtime;
    }

    public static function LoginTime($id,$user,$datetime){
    	$timediff = '0';
        $lists = VicidialUserLog::select('event_date','event_epoch')->where('user_log_id','<',$id)->where('event','LOGIN')->where('user',$user)->orderBy('user_log_id','desc')->first();
        if ($lists) {
        if($lists->event_date < $datetime){
        	$timediff = strtotime($datetime)-strtotime($lists->event_date);
    return $timediff.' '.$datetime.'  '.$lists->event_date;
        }
        }
    //return $timediff;
    }
    public static function LogoutCheck($id,$user){
    	$timediff = '0';
        $lists = VicidialUserLog::select('event')->where('user_log_id','>',$id)->where('user',$user)->orderBy('user_log_id','asc')->first();
        if (!empty($lists)) {
        if($lists->event != 'LOGOUT'){
        	$timediff = '1';
        }
        }
        if (empty($lists)){ $timediff = '1'; }
    return $timediff;
    }
}

