<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class EnqCategory extends Model
{
    protected  $table="enquiry_categories";
    public $timestamps = false;

    public static function Details($id){
    }

}