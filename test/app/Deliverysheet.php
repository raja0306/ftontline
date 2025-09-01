<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Deliverysheet extends Model
{

	protected  $table="delivery_sheet";
	
	public function shipment(){
        return $this->belongsTo(Shipment::class,'id');
    }

    public function driver(){
       return $this->belongsTo(User::class,'driver_id','id');
    }
}