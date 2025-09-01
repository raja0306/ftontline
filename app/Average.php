<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Average extends Model
{
    public static function toMinutes($sec){
			$hours = floor($sec / 3600);
			$hours_sec = $hours * 3600;
			$seconds = $sec - $hours_sec;
			$minutes = floor($seconds / 60);
			$seconds -= ($minutes * 60);
			$Ftime = sprintf('%s:%s:%s',
				$hours,
				str_pad($minutes,2,'0',STR_PAD_LEFT),
				str_pad($seconds,2,'0',STR_PAD_LEFT)
			);
		return "$Ftime";
	}
    public static function MathZDC($dividend, $divisor, $quotient=0){
		if ($divisor==0){
			$dvalue = $quotient;
		}else if ($dividend==0){
			$dvalue = 0;
		}else{
			$dvalue = ($dividend/$divisor);
		}
		return number_format((float)$dvalue, 2, '.', '');
	}


    public static function MathPER($seconds, $calltime, $quotient=0){
		if ($seconds==0){
			return $seconds;
		}else if ($calltime==0){
			return $seconds;
		}else{
			$perc = 100/$calltime;
			$percentage = $seconds*$perc;
			return number_format((float)$percentage, 2, '.', '');
		}
	}

}
