<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Shipment extends Model
{
    protected  $table="shipments";

    public function upload(){
        return $this->belongsTo(Upload::class);
    }

    public function list()
    {
        return $this->belongsTo(VicidialList::class, 'lead_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    public function vicidialList()
    {
        return $this->belongsTo(VicidialList::class, 'lead_id', 'lead_id');
    }
}