<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\WhatsappController;

class SendWhatsappAppointment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mobile;
    protected $name;
    protected $date;
    protected $time;
    protected $area;
    protected $block;
    protected $street;
    protected $building;
    protected $floor;
    protected $flat;
    protected $landmark;

    public function __construct($mobile, $name, $date, $time, $area, $block, $street, $building, $floor, $flat, $landmark)
    {
        $this->mobile = $mobile;
        $this->name = $name;
        $this->date = $date;
        $this->time = $time;
        $this->area = $area;
        $this->block = $block;
        $this->street = $street;
        $this->building = $building;
        $this->floor = $floor;
        $this->flat = $flat;
        $this->landmark = $landmark;
    }

    public function handle()
    {
        app(WhatsappController::class)->appointment_confirmation(
            $this->mobile,
            $this->name,
            $this->date,
            $this->time,
            $this->area,
            $this->block,
            $this->street,
            $this->building,
            $this->floor,
            $this->flat,
            $this->landmark
        );
    }
}
