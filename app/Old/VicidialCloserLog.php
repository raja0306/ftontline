<?php

namespace App\Old;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class VicidialCloserLog extends Model
{
    protected $connection = "mysql5";
    protected $table = "vicidial_closer_log";
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("campaign_id", function (Builder $builder) {
            $values = UserDetails::getColumn('ingroup_1');
            $builder->whereIn("campaign_id", explode(",", $values));
        });
    }

    public static function CheckMissed($phone, $call_date)
    {
        $phone = substr($phone, -8);
        $status_answer = VicidialCloserLog::where(
            "phone_number",
            "like",
            "%" . $phone
        )
            ->where("call_date", ">", $call_date)
            ->where("status", "!=", "DROP")
            ->count();
        $status_log = VicidialLog::where("phone_number", "like", "%" . $phone)
            ->where("call_date", ">", $call_date)
            ->count();
        $status = "Missed";
        if ($status_log > 0) {
            $status = "CallBack";
        }
        if ($status_answer > 0) {
            $status = "Answer";
        }
        return $status;
    }
    public static function CheckMissedspan($status)
    {
        if ($status == "Answer") {
            $statushtml =
                '<span class="badge font-size-12 bg-success">Answer</span>';
        } elseif ($status == "CallBack") {
            $statushtml =
                '<span class="badge font-size-12 bg-warning">Call Back</span>';
        } else {
            $statushtml =
                '<span class="badge font-size-12 bg-danger">Missed</span>';
        }
        return $statushtml;
    }
}
