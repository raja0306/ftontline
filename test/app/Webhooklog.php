<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Webhooklog extends Model
{
	protected  $table="webhooklogs";
	protected $fillable = ['name','messages'];
}