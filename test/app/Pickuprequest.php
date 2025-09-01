<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Pickuprequest extends Model
{
    protected  $table="pickup_requests";

    protected static function boot()
    {
        parent::boot();
        if(Auth::user()->role_id==7){
        	static::addGlobalScope("vendor_id", function (Builder $builder) {
            $builder->where("vendor_id", Auth::user()->id);
        	});
        }
    }

    public function vendor(){
        return $this->belongsTo(User::class,'vendor_id','id');
    }

    public function driver(){
        return $this->belongsTo(User::class,'driver_id','id');
    }

    public function createduser(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    
}

