<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class VicidialCampaign extends Model
{
    protected $connection = "mysql2";
    protected $table = "vicidial_campaigns";
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("campaign_id", function (Builder $builder) {
            $values = UserDetails::getColumn('campaign');
            $builder->whereIn("campaign_id", explode(",", $values));
        });
    }

    public static function Options($id)
    {
        $campaigid = Auth::user()->campaign;
        $lists = VicidialCampaign::select("campaign_id", "campaign_name")
            ->where("active", "Y")
            ->get();
        $divhtml = '<option value="All">Select All</option>';
        $selected = "";
        if ($lists) {
            foreach ($lists as $list) {
                if ($list->campaign_id == $id) {
                    $selected = " selected";
                } elseif ($list->campaign_id == $campaigid) {
                    $selected = " selected";
                }
                $divhtml .=
                    '<option value="' .
                    $list->campaign_id .
                    '"' .
                    $selected .
                    ">" .
                    $list->campaign_name .
                    "</option>";
            }
        }
        return $divhtml;
    }
}
