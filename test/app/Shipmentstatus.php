<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Shipmentstatus extends Model
{
    protected  $table="shipmentstatus";

    public function shipment(){
        return $this->belongsTo(Shipment::class);
    }

}