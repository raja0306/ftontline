<?php

namespace App\Old;

use Illuminate\Database\Eloquent\Model;

class VicidialRecord extends Model
{
    protected $connection = "mysql5";
    protected $table = "recording_log";

    public static function FindURLRCD($id, $end_epoch)
    {
        $records = VicidialRecord::select("filename", "start_time")
            ->where("lead_id", $id)
            ->where("end_epoch", $end_epoch)
            ->first();
        $wav_url = "";
        if ($records) {
            $call_date = date("Y-m-d", strtotime($records->start_time));
            $wav_url =
                "/RECORDING/MP3/" .
                $call_date .
                "/" .
                $records->filename .
                "-all.mp3";
        }
        return $wav_url;
    }
}
