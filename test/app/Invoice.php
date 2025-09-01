<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Invoice extends Model
{

    public static function no_days($subid){
    	$cdate = date("Y-m-d");
    	$workdays = 0;
        $subcription = DB::table('agent_invoices')->where('id',$subid)->first();

        if ($subcription) {

                $start = strtotime($subcription->start_date);  
                if ($subcription->status == 'Active') {
                    $end   = strtotime($cdate);
                }
                else{
                    $end   = strtotime($subcription->end_date);
                }

                for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
                $day = date("l", $i);  
                if ($day != 'Friday' ) {
                $workdays++;
                }
                }
        }
			return $workdays;
    }

    public static function amount($amount,$days,$no_days){
        $daycount = $no_days / $days;
        $totalamount = $daycount * $amount;
        return $totalamount;
    }
}
