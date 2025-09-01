<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Cardstatus extends Model
{
    protected  $table="cardstatuses";

    public function shipemnts(){
        return $this->hasMany(Shipment::class,'status_id','id');
    }

}