<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enquiry;
use App\Enquirycategory;
use App\VicidialList;
use Auth;
use DB;

class EnquiryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $category = null; if(!empty($_GET['category'])){ $category = $_GET['category']; }
        $reset = null; if(!empty($_GET['reset'])){ $reset = $_GET['reset']; }

        $categories = DB::table('enquiry_categories')->select('category_name','id_enquiry_category')->orderBy('id_enquiry_category','asc')->get();
        $lists = Enquiry::whereBetween('created_at',[$fromdate, $todate1]);
        if(!empty($category)){ 
         $lists = $lists->where('enq_id',$category); 
        }
        $phone = null;
        if(!empty($_GET['phone'])){ 
            $phone = $_GET['phone']; 
            $lists = $lists->where('mobile',$phone);
        }
        
        if($reset=='true'){
             $resetlists = $lists;
             $resetlists = $resetlists->where('is_reset','No')->get();
             $re_count=0;
             foreach ($resetlists as $resetlist)
             {
                $re_count++;
                $category_name='';
                if($resetlist->enquirycategory)
                {
                    $category_name=$resetlist->enquirycategory->category_name;
                }
                 VicidialList::where('lead_id',$resetlist->lead_id)->update(["called_since_last_reset" => "N", "status" => "NEW", "comments" => $category_name]);
                Enquiry::where('id',$resetlist->id)->update(["is_reset" => "Yes"]);
             }
             
             $lilog = $re_count." records resetted";

             DB::table('lists_log')->insert(['log' => $lilog,'userid' => Auth::user()->id]);

             return redirect('/enquiries');
        }   

        $lists =$lists->orderBy('id','desc')->get();

        return view('leads.enquiry',compact('fromdate','todate','lists','category','categories','phone'));
    }

    public function cecindex()
    {
        $fromdate = date("Y-m-d"); if(!empty($_GET['fromdate'])){ $fromdate = $_GET['fromdate']; }
        $todate = date("Y-m-d"); if(!empty($_GET['todate'])){ $todate = $_GET['todate']; }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $listid = 'All'; if(!empty($_GET['listid'])){ $listid = $_GET['listid']; }
        $category = 'All'; if(!empty($_GET['category'])){ $category = $_GET['category']; }
        //if($fromdate == date('Y-m-d')){ $this->updatechassis(); }

        $enquiries = Enquiry::select('id_opp','first_name','last_name','mobile_number','id_list','id_agent','enquiry_category','enquiry_subcategory2','enquiry_subcategory3','enquiry_subcategory','description','campaign_id','date_add','appointment_booked','date_add','id_agent','id_process_lead','id_vehicle','chassis')->whereBetween('date_add',[$fromdate, $todate1]); 
        
        if($listid != 'All'){ $enquiries = $enquiries->where('id_list',$listid); }      
        if($category != 'All'){ $enquiries = $enquiries->where('enquiry_category',$category); }

        $enquiries= $enquiries->get();
        $enquirylists = DB::table('enquiry_categories')->where('parent_id','1000')->orderBy('id_enquiry_category','asc')->get();

        return view('leads.enquirycec',compact('fromdate','todate','todate1','listid','category','enquiries','enquirylists'));
    }
}