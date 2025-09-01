<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Area extends Model
{
    protected  $table="areas";

    public function branchs(){
        return $this->hasMany(Branch::class);
    }

    public function governorate(){
        return $this->belongsTo(Governorate::class);
    }
}








