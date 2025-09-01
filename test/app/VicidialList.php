<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;


class VicidialList extends Model
{
	protected $connection = 'mysql2';
    protected  $table="vicidial_list";
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("list_id", function (Builder $builder) {
            $values = UserDetails::getColumn('list_id');
            $builder->whereIn("list_id", explode(",", $values));
        });
    }

    public static function Status($status){
            if($status == 'B'){
                $status = 'Number Busy';
            }
            else if($status == 'NA'){
                $status = 'Call Not Answered';
            }
            else if($status == 'PU'){
                $status = 'Phone Picked';
            }
            else if($status == 'PM'){
                $status = 'Message Played';
            }
            else if($status == 'AB'){
                $status = 'Number Busy';
            }
            else if($status == 'ADC'){
                $status = 'Switched Off / Out of Reach';
            }
            else if($status == 'NEW'){
                $status = 'Dialable';
            }
            
            return $status;
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

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'id', 'id');
    }
}





