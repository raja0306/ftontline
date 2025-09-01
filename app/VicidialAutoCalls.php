<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;


class VicidialAutoCalls extends Model
{
	protected $connection = 'mysql2';
	protected  $table="vicidial_auto_calls";

	protected static function boot() {
		parent::boot();
		static::addGlobalScope('campaign_id', function(Builder $builder){ $builder->where('campaign_id',explode(",",Auth::user()->ingroups)); });

	}
}

