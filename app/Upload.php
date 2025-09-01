<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Upload extends Model
{
    protected  $table="uploads";

    public function shipments(){
        return $this->hasMany(Shipment::class);
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    public function user(){
       return $this->belongsTo(User::class,'created_by','id');
    }
}