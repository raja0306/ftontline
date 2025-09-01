<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Settings extends Model
{
    protected  $table="settings";

    public static function getColumn($col,$val,$ret){
        $ret_val='';
        $data = Settings::where($col,$val)->where('delete_status',0)->first();
        if ($data) { $ret_val = $data->$ret; }
        return $ret_val;
    }

    public static function getColumnTable($table,$col,$val,$ret){
        $ret_val='';
        $data = DB::table($table)->where($col,$val)->where('delete_status',0)->first();
        if ($data) { $ret_val = $data->$ret; }
        return $ret_val;
    }

}
