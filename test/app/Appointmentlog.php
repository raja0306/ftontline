<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Appointmentlog extends Model
{
	public function appointment(){
        return $this->belongsTo(Appointment::class);
    }	
    public function appointmentstatus(){
       return $this->belongsTo(User::class,'status_id','id');
    }

    public function user(){
       return $this->belongsTo(User::class,'created_by','id');
    }

}