<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Vechicle extends Model
{
    protected  $table="vechicles";
    public $timestamps = false;

    public function vechiclebrand(){
        return $this->belongsTo(Vechiclebrand::class,'brand_id','id');
    }

    public function vechiclemodel(){
        return $this->belongsTo(Vechiclemodel::class,'model_id','id');
    }

    public function driver(){
        return $this->belongsTo(User::class,'driver_id','id');
    }
}