<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Vechiclemodel extends Model
{
    protected  $table="vechicle_model";
    public $timestamps = false;
}