<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VicidialUserGroup extends Model
{
	protected $connection = 'mysql2';
    protected  $table="vicidial_user_groups";
    public $timestamps = false;

    public static function Options($id){
        $lists = VicidialUserGroup::select('user_group','group_name')->get();
        $divhtml =  '<option value="All">Select All</option>';
        
        if ($lists) {
        foreach($lists as $list){
        	if ($list->user_group == $id) { $selected =  ' selected'; }else{ $selected =  ''; }
        	$divhtml .= '<option value="'.$list->user_group.'"'.$selected.'>'.$list->group_name.'</option>';
        }
        }
    return $divhtml;
    }
}
