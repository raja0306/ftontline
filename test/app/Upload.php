<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Upload extends Model
{
    protected  $table="uploads";

    protected static function boot()
     {
        $role = Auth::user()->role_id;
        parent::boot();
        if ($role == '4') {
          static::addGlobalScope('created_by', function(Builder $builder) {
              $builder->where('created_by',Auth::user()->id);
          });
        }
     }

    public function shipments(){
        return $this->hasMany(Shipment::class);
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    public function user(){
       return $this->belongsTo(User::class,'created_by','id');
    }

    public function bank(){
       return $this->belongsTo(User::class,'bank_id','id');
    }
}