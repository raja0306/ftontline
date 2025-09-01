<?php

namespace App\Old;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class VicidialIngroup extends Model
{
    protected $connection = "mysql5";
    protected $table = "vicidial_inbound_groups";
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("group_id", function (Builder $builder) {
            $values = UserDetails::getColumn('ingroup_1');
        	$builder->whereIn("group_id", explode(",", $values));
        });
    }

    public static function Options($id)
    {
        $lists = VicidialIngroup::select("group_id", "group_name")
            ->where("active", "Y")
            ->get();
        $divhtml = '<option value="All">Select All</option>';

        if ($lists) {
            foreach ($lists as $list) {
                if ($list->group_id == $id) {
                    $selected = " selected";
                } else {
                    $selected = "";
                }
                $divhtml .=
                    '<option value="' .
                    $list->group_id .
                    '"' .
                    $selected .
                    ">" .
                    $list->group_name .
                    "</option>";
            }
        }
        return $divhtml;
    }
}
