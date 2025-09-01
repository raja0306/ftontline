<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class VicidialAgentLog extends Model
{
	protected $connection = 'mysql2';
  protected  $table="vicidial_agent_log";
  
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("user", function (Builder $builder) {
            $values = UserDetails::getColumn('agent');
            $builder->whereIn("user", explode(",", $values));
        });
    }

  public static function Status($status){
        if($status == 'Smokin'){
          $status = 'Smoking';
        }
        else if($status == 'Specia'){ 
          $status = 'Special Task';
        }
        else if($status == 'Washro'){ 
          $status = 'Washroom';
        }
        else if($status == 'Traini'){
          $status = 'Training';
        }
        else if($status == 'Meatin'){
          $status = 'Meating';
        }
        else{
          $status = $status;
        }
	return "$status";
  }
}
 