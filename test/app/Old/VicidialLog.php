<?php

namespace App\Old;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class VicidialLog extends Model
{
    protected $connection = "mysql5";
    protected $table = "vicidial_log";

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("list_id", function (Builder $builder) {
            $values = UserDetails::getColumn('list_id_1');
            $builder->whereIn("list_id", explode(",", $values));
        });
    }
}
