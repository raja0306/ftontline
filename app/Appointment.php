<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Appointment extends Model
{
	public function appointmentlogs(){
        return $this->hasMany(Appointmentlog::class);
    }

    public function upload(){
        return $this->belongsTo(Upload::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function customer(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function useraddress(){
       return $this->belongsTo(Useraddress::class,'useraddress_id','id');
    }

    public function driver(){
       return $this->belongsTo(User::class,'driver_id','id');
    }

    public function slot(){
        return $this->belongsTo(Slot::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function appointmentstatus(){
       return $this->belongsTo(Appointmentstatus::class,'status_id','id');
    }

    public function createduser(){
       return $this->belongsTo(User::class,'created_by','id');
    }

    public function updateduser(){
       return $this->belongsTo(User::class,'updated_by','id');
    }
    
    public function shipments(){
        return $this->hasMany(Shipment::class);
    }




}