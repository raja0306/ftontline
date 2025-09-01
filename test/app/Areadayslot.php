<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Areadayslot extends Model
{
    protected  $table="areadayslots";

    public function area(){
        return $this->belongsTo(Area::class);
    }
}








