<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class VicidialLists extends Model
{
    protected $connection = "mysql2";
    protected $table = "vicidial_lists";
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("list_id", function (Builder $builder) {
            $values = UserDetails::getColumn('list_id');
            $builder->whereIn("list_id", explode(",", $values));
        });
    }

    public static function Options($id)
    {
        $divhtml = '<option value="All">Select All</option>';

        $lists = VicidialLists::where("list_id", ">", 0)->orderBy('list_name','asc')->get();
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
    public static function Options1($id)
    {
        $divhtml = '<option value="0">All</option>';

        $lists = VicidialLists::select("list_id", "list_name")->get();
        if (count($lists) > 0) {
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

    public static function FindName($id)
    {
        $listnames = VicidialLists::select("list_name")
            ->where("list_id", $id)
            ->first();
        $listname = "";
        if ($listnames) {
            $listname = $listnames->list_name;
        }
        return "$listname";
    }

    public static function Status($status)
    {
        if ($status == "B") {
            $status = "Busy";
        } elseif ($status == "NA") {
            $status = "No Answer";
        } elseif ($status == "PU") {
            $status = "Phone Picked";
        } elseif ($status == "PM") {
            $status = "Message Played";
        } elseif ($status == "AB") {
            $status = "Phone Busy";
        } elseif ($status == "ADC") {
            $status = "Disconnected";
        } elseif ($status == "NEW") {
            $status = "Dialable";
        }

        return $status;
    }

    public static function UserName($user="")
    {
        $full_name = "";
        $list = VicidialUser::select("full_name")->where("user", $user)->first();
        if (!empty($list)) { $full_name = $list->full_name; }
        return $user;
    }
}
