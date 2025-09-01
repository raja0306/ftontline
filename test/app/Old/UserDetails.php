<?php

namespace App\Old;

use Illuminate\Database\Eloquent\Model;
use Auth;

class UserDetails extends Model
{
  	protected  $table="user_details";

    public static function getColumn($ret){
        $ret_val='';
        $data = UserDetails::where('user_id',Auth::user()->id)->where('delete_status',0)->orderBy('id','desc')->first();
        if ($data) { $ret_val = $data->$ret; }
        return $ret_val;
    }
}