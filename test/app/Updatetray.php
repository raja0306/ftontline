<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Updatetray extends Model
{

    public function shipment(){
        return $this->belongsTo(Shipment::class);
    }

    public function tray(){
        return $this->belongsTo(Tray::class);
    }

    public function fromtray(){
        return $this->belongsTo(Tray::class, 'from_tray');
    }

}
