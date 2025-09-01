<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Slot extends Model
{
    public static function Availablity($id,$qty,$date){
    	$booked = Appointment::where('slot_id',$id)->where('appointment_date',$date)->where('delete_status',0)->count();
        return $qty-$booked;
    }

}