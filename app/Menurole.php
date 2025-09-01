<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Menurole extends Model
{
    protected  $table="rolemenus";

    public static function getMenuRole($role_id,$menu_id,$val="menu"){
    	$menurole=Menurole::where('role_id',$role_id)->where('menu_id',$menu_id)->first();
    	$menurole_id=0;
    	if($menurole){
    		$menurole_id=$menurole->$val;
    	}

    	return $menurole_id;
    }
}








