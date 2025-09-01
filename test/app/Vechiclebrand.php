<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Vechiclebrand extends Model
{
    protected  $table="vechicle_brand";
    public $timestamps = false;
}