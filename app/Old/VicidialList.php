<?php

namespace App\Old;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;


class VicidialList extends Model
{
	protected $connection = 'mysqls';
    protected  $table="vicidial_list";
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("list_id", function (Builder $builder) {
            $values = UserDetails::getColumn('list_id_1');
            $builder->whereIn("list_id", explode(",", $values));
        });
    }

    public static function Details($id){
    $divhtml = "";
    $app = VicidialList::select('lead_id','entry_date','modify_date','list_id','status','phone_number','user','called_count')->where('lead_id',$id)->first();
   
    if ($app) {
        $divhtml = "
                    <td>".$app->lead_id."</td>
                    <td>".$app->phone_number."</td>
                    <td>".VicidialLists::FindName($app->list_id)."</td>
                    <td>".VicidialLists::Status($app->status)."</td>
                    <td>".$app->user."</td>
                    <td>".$app->entry_date."</td>
                    <td>".$app->modify_date."</td>
                    ";
    }
    else{ 
        $divhtml = "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
    }

    return "$divhtml";
    }
}





