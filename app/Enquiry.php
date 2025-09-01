<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class Enquiry extends Model
{
    protected  $table="enquiries";
    public $timestamps = false;

    public static function FindName($id){
        $name = '';
        $enqname = EnqCategory::select('category_name')->where('id_enquiry_category',$id)->first();
        if ($enqname) { $name = $enqname->category_name; }
        return $name;
    }

    public function enquirycategory()
    {
        return $this->belongsTo(Enquirycategory::class, 'enq_id', 'id_enquiry_category');
    }

    public function vicidialList()
    {
        return $this->belongsTo(VicidialList::class, 'lead_id', 'lead_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'lead_id', 'lead_id');
    }


}








