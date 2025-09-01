<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Vechicledriverlog extends Model
{
    protected  $table="vehicle_driver_logs";
    public $timestamps = false;

    public function vechicle(){
        return $this->belongsTo(Vechicle::class,'vehicle_id','id');
    }

    public function driver(){
        return $this->belongsTo(User::class,'driver_id','id');
    }
}