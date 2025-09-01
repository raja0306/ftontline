<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Bank extends Model
{
    protected  $table="banks";

    public function branchs(){
        return $this->hasMany(Branch::class);
    }
}








