<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Shipmentinfo extends Model
{
    protected  $table="shipmentinfos";

    public function shipment(){
        return $this->belongsTo(Shipment::class);
    }

    public function user(){
       return $this->belongsTo(User::class,'created_by','id');
    }

}