<?php

namespace App\Old;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class VicidialLists extends Model
{
    protected $connection = "mysql5";
    protected $table = "vicidial_lists";
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("list_id", function (Builder $builder) {
            $values = UserDetails::getColumn('list_id_1');
            $builder->whereIn("list_id", explode(",", $values));
        });
    }

    public static function FindName($id)
    {
        $listnames = VicidialLists::select("list_name")
            ->where("list_id", $id)
            ->first();
        $listname = "";
        if ($listnames) {
            $listname = $listnames->list_name;
        }
        return $listname;
    }

    public static function Options($id)
    {
        $divhtml = '<option value="All">Select All</option>';

        $lists = VicidialLists::where("list_id", ">", 0)->get();
        if (!empty($lists)) {
            foreach ($lists as $list) {
                if ($list->list_id == $id) {
                    $selected = " selected";
                } else {
                    $selected = "";
                }
                $divhtml .=
                    '<option value="' .
                    $list->list_id .
                    '"' .
                    $selected .
                    ">" .
                    $list->list_name .
                    "</option>";
            }
        }
        return $divhtml;
    }
}
