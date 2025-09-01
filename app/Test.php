<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Test extends Model
{
	protected $connection = 'mysql3';
    protected  $table="APPS.RKCL_FOLLOWUP_CUSTOMERS_V";

    public static function Options($id){
        $lists = DB::table('agent_lists')->select('agentid','agentname')->where('delete_status','0')->get();
        $divhtml =  '<option value="All">Select All</option>';
        
        if ($lists) {
        foreach($lists as $list){
        	if ($list->agentid == $id) { $selected =  ' selected'; }else{ $selected =  ''; }
        	$divhtml .= '<option value="'.$list->agentid.'"'.$selected.'>'.$list->agentname.'</option>';
        }
        }
    return $divhtml;
    }
}
