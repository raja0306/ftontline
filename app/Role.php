<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Role extends Model
{
    protected  $table="roles";

    public function users(){
        return $this->hasMany(User::class);
    }
}








