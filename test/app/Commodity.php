<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Commodity extends Model
{
    protected  $table="commodities";
     protected $fillable = ['name'];

}