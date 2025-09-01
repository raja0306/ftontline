<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Cardtype extends Model
{
    protected  $table="cardtypes";
     protected $fillable = ['name'];

}