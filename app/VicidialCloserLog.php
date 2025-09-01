<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class VicidialCloserLog extends Model
{
protected $connection = "mysql2";
protected $table = "vicidial_closer_log";
public $timestamps = false;

protected static function boot()
{
parent::boot();
static::addGlobalScope("campaign_id", function (Builder $builder) {
$values = UserDetails::getColumn('ingroup');
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

public static function NetMissedspan($phone="",$call_date="")
{   
$statushtml = '<span class="badge font-size-12 bg-danger">Net Missed</span>';
 $phone = substr($phone, -8);
 $status_log = VicidialLog::where('phone_number',$phone)->whereBetween('call_date',[$call_date, date('Y-m-d', strtotime("+7 days", strtotime($call_date)))])->where('length_in_sec','>','0')->count();
     if($status_log > 0){ 
	             $statushtml = '<span class="badge font-size-12 bg-success">Answer</span>';
		             }
     else{
	             $status_answer = VicidialCloserLog::where('phone_number',$phone)->whereBetween('call_date',[$call_date, date('Y-m-d', strtotime("+7 days", strtotime($call_date)))])->count();
		                 if($status_log > 0){  $statushtml = '<span class="badge font-size-12 bg-warning">Call Back</span>'; }
		             }
     return $statushtml;
 }


public static function NetMissed($phone="",$call_date="")
{   
$statushtml = 'Net Missed';
 $phone = substr($phone, -8);
 $status_log = VicidialLog::where('phone_number',$phone)->whereBetween('call_date',[$call_date, date('Y-m-d', strtotime("+7 days", strtotime($call_date)))])->where('length_in_sec','>','0')->count();
     if($status_log > 0){ 
	             $statushtml = 'Answer';
		             }
     else{
	             $status_answer = VicidialCloserLog::where('phone_number',$phone)->whereBetween('call_date',[$call_date, date('Y-m-d', strtotime("+7 days", strtotime($call_date)))])->count();
		                 if($status_log > 0){  $statushtml = 'Call Back'; }
		             }
     return $statushtml;
 }
}
