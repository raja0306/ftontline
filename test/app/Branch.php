<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Branch extends Model
{
    protected  $table="branchs";

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

