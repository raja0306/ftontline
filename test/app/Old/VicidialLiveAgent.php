<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class VicidialLiveAgent extends Model
{
    protected $connection = "mysql5";
    protected $table = "vicidial_live_agents";

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("user", function (Builder $builder) {
            $values = UserDetails::getColumn('agent');
            $builder->whereIn("user", explode(",", $values));
        });
    }
}
