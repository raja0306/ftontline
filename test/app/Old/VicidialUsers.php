<?php

namespace App\Old;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class VicidialUsers extends Model
{
    protected $connection = 'mysql5';
    protected  $table="vicidial_users";
    // public $timestamps = false;
  
    protected static function boot()
    {
        // parent::boot();
        // static::addGlobalScope("user", function (Builder $builder) {
        //     $values = UserDetails::getColumn('agent');
        //     $builder->whereIn("user", explode(",", $values));
        // });
    }


    public static function FindName($user="")
    {
        $full_name = $user;
        $list = DB::connection('mysql5')->table('vicidial_users')->select("full_name")->where("user", $user)->first();
        if (!empty($list)) { $full_name = $list->full_name; }
        return $full_name;
    }

    public static function Options($id)
    {
        $lists = VicidialUsers::select("user", "full_name")
            ->where("active", "Y")
            ->get();
        $divhtml = '<option value="All">Select All</option>';

        if ($lists) {
            foreach ($lists as $list) {
                if ($list->user == $id) {
                    $selected = " selected";
                } else {
                    $selected = "";
                }
                $divhtml .=
                    '<option value="' .
                    $list->user .
                    '"' .
                    $selected .
                    ">" .
                    $list->full_name .
                    "</option>";
            }
        }
        return $divhtml;
    }
}
