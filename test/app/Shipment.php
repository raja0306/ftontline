<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;
use Carbon\Carbon;

class Shipment extends Model
{
    protected  $table="shipments";

    protected $fillable = [
        'barcode',
        'driver_id',
        'status_id', 
    ];

    protected static function boot()
     {
        if(Auth::user()){
            $role = Auth::user()->role_id;
        }
        else{
            $role = 1;
        }
        
        parent::boot();
        if ($role == '4') {
          static::addGlobalScope('bank_id', function(Builder $builder) {
              $builder->where('bank_id',Auth::user()->id);
          });
        }
     }

    public static function getAge($civilId){
        if (!preg_match('/^[23]\d{11}$/', $civilId)) {
        return ['error' => 'Invalid Civil ID format'];
        }

        // Get century
        $century = $civilId[0] == '2' ? 1900 : 2000;

        // Extract birth date parts
        $year = $century + (int)substr($civilId, 1, 2);
        $month = (int)substr($civilId, 3, 2);
        $day = (int)substr($civilId, 5, 2);

        // Create date of birth
        try {
            $dob = Carbon::createFromDate($year, $month, $day);
        } catch (\Exception $e) {
            return ['error' => 'Invalid date in Civil ID'];
        }

        // Calculate age
        $age = $dob->age;

        // Classify
        $status = $age >= 18 ? 'Senior' : 'Minor';
        $status = $age >= 60 ? 'Old' : $status;

        // return [
        //     'age' => $age,
        //     'status' => $status,
        //     'dob' => $dob->format('Y-m-d'),
        // ];
        return $age.' - '.$status;
    }

    public function upload(){
        return $this->belongsTo(Upload::class);
    }

    public function list()
    {
        return $this->belongsTo(VicidialList::class, 'lead_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function bank(){
       return $this->belongsTo(User::class,'bank_id','id');
    }

    public function driver(){
       return $this->belongsTo(User::class,'driver_id','id');
    }

    public function branch(){
       return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function appointment(){
        return $this->belongsTo(Appointment::class,'appointment_id','id');
    }

    public function vicidialList()
    {
        return $this->belongsTo(VicidialList::class, 'lead_id', 'lead_id');
    }

    public function Shipmentinfos(){
        return $this->hasMany(Shipmentinfo::class)->orderBy('id','desc');
    }

    public function tray(){
       return $this->belongsTo(Tray::class,'tray_id','id');
    }

    public function cardtype(){
       return $this->belongsTo(Cardtype::class,'cardtype_id','id');
    }

    public function commodity(){
       return $this->belongsTo(Commodity::class,'commodity_id','id');
    }

    public function cardstatus(){
       return $this->belongsTo(Cardstatus::class,'status_id','id');
    }

    public function blockreason()
    {
        return $this->belongsTo(Enquirycategory::class, 'block_call', 'id_enquiry_category');
    }
}