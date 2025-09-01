<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class UserDetails extends Model
{
  	protected  $table="user_details";

    public static function getColumn($ret){
        $ret_val='';
        //$data = UserDetails::where('user_id',Auth::user()->id)->where('delete_status',0)->orderBy('id','desc')->first();
        $data = UserDetails::where('delete_status',0)->orderBy('id','desc')->first();
        if ($data) { $ret_val = $data->$ret; }
        return $ret_val;
    }

    public static function getColumnUser($uid,$ret){
        $ret_val='';
        $data = UserDetails::where('user_id',$uid)->where('delete_status',0)->orderBy('id','desc')->first();
        if ($data) { $ret_val = $data->$ret; }
        return $ret_val;
    }
}