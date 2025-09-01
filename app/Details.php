<?php



namespace App;



use Illuminate\Database\Eloquent\Model;

use DB;



class Details extends Model

{

     public static function getColumnWhere($table,$col,$val,$ret){
        $ret_val='';
        $data = DB::table($table)->where($col,$val)->first();
        if ($data) { $ret_val = $data->$ret; }
     
        return $ret_val;
    }

    

}

