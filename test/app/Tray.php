<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Tray extends Model
{
    protected  $table="trays";
     protected $fillable = ['name'];

    public function trayupdate(){
        return $this->hasMany(Updatetray::class);
    }

}