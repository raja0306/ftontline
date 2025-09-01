<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use Redirect;
use App\Menu;
use App\Role;
use App\Area;
use App\Areadayslot;
use App\Governorate;
use App\User;
use App\Slot;
use App\Branch;
use App\Tray;
use App\Cardtype;
use App\Commodity;
use App\Menurole;
use App\Appointment;
use App\VicidialList;
use App\Rejectreason;
use App\Enquirycategory;
use App\Vechiclemodel;
use App\Vechiclebrand;
use App\Vechicle;
use App\Vechicledriverlog;

use Auth;

class MasterController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menu
    public function menu(Request $request)
    {
        $lists = Menu::get();
        return view('master.menu',compact('lists'));
    }

    public function menustore(Request $request)
    {
        $data = new Menu; 
        $data->name = $request->name;
        $data->updated_by=Auth::user()->id;
        $data->save();
        $menu_id =$data->id;

        $lists = Role::get();
        foreach ($lists as $list) {
            $data = new Menurole; 
            $data->menu_id = $menu_id;
            $data->role_id = $list->id;
            $data->save();
        }
        return Redirect::back()->with('alert', 'Records updated');
    }

    // Role
    public function role(Request $request)
    {
        $lists = Role::get();
        return view('master.role',compact('lists'));
    }

    public function rolestore(Request $request)
    {
        $data = new Role; 
        $data->name = $request->name;
        $data->updated_by=Auth::user()->id;
        $data->save();
        $role_id =$data->id;
        $lists = Menu::get();

        foreach ($lists as $list) {
            $data = new Menurole; 
            $data->menu_id = $list->id;
            $data->role_id = $role_id;
            $data->save();
        }
        return Redirect::back()->with('alert', 'Records updated');
    }

    //Menurole
    public function index(Request $request)
    {

        // $menu=Menu::get();
        // $role=Role::get();

        // foreach ($menu as $m) {
        //     foreach ($role as $r) {
        //         $data = new Menurole; 
        //         $data->menu_id = $m->id;
        //         $data->role_id = $r->id;
        //         $data->created_by=Auth::user()->id;
        //         $data->save();
        //     }
        // }
        $menus=Menu::orderBy('name','asc')->get();
        $roles=Role::orderBy('name','asc')->get();

        $title = "Access Rights";
        $indexes = Menurole::orderBy('id','Desc');

        $menu_id='';
        if(!empty($request->menu_id)){
            $menu_id=$request->menu_id;
            $indexes=$indexes->where('menu_id',$menu_id);
        }

        $role_id='';
        if(!empty($request->role_id)){
            $role_id=$request->role_id;
            $indexes=$indexes->where('role_id',$role_id);
        }

        $indexes =$indexes->get();
        return view('master.menurole',compact('title','indexes','menus','roles','menu_id','role_id'));  
    }

    public function update($id,$val)
    {

        if(!empty($id)){
            $data = Menurole::find($id);
            if (empty($data)) { $data = new Menurole; }
            $data->menu = $val;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        
        echo json_encode(array("id"=>$id));
    }

    // Area
    public function areas(Request $request)
    {
        
        $governorates = Governorate::get();
        $areas = Area::where('delete_status','0')->get();
        return view('master.area',compact('areas','governorates'));
    }

    public function areastore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Area::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Area; 
            $data->created_by=Auth::user()->id;
        }
        $data->name_ar = $request->name_ar;
        $data->name = $request->name;
        $data->area_code = $request->area_code;
        $data->governorate_id = $request->governorate_id;
        $data->save();

        $ids = \App\Slot::pluck('id')->toArray();
        $commaSeparated = implode(',', $ids);

        if(!empty($request->editid)){
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            foreach ($days as $day) {
                $data = new Areadayslot; 
                $data->area_id = $request->area_id;
                $data->day = $day;
                $data->slot_ids = $commaSeparated;
                $data->created_by=Auth::user()->id;
                $data->save();
            }
        }

        return Redirect::back()->with('alert', 'Records updated');
    }
     
    public function destroyarea($id)
    {
        $data = Area::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    // Tray
    public function trays(Request $request)
    {

        $trays = Tray::where('delete_status','0')->get();
        return view('master.tray',compact('trays'));
    }

    public function traystore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Tray::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Tray; 
            $data->created_by=Auth::user()->id;
        }
        $data->name = $request->name;
        $data->save();

        return Redirect::back()->with('alert', 'Records updated');
    }
     
    public function destroytray($id)
    {
        $data = Tray::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    // Cardtype
    public function cardtypes(Request $request)
    {

        $cardtypes = Cardtype::where('delete_status','0')->get();
        return view('master.cardtype',compact('cardtypes'));
    }

    public function cardtypestore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Cardtype::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Cardtype; 
            $data->created_by=Auth::user()->id;
        }
        $data->name = $request->name;
        $data->save();

        return Redirect::back()->with('alert', 'Records updated');
    }
     
    public function destroycardtype($id)
    {
        $data = Cardtype::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    // Commodity
    public function commodities(Request $request)
    {

        $commodities = Commodity::where('delete_status','0')->get();
        return view('master.commodities',compact('commodities'));
    }

    public function commoditystore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Commodity::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Commodity; 
            $data->created_by=Auth::user()->id;
        }
        $data->name = $request->name;
        $data->save();

        return Redirect::back()->with('alert', 'Records updated');
    }
     
    public function destroycommodity($id)
    {
        $data = Commodity::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    // Branchs
    public function branchs(Request $request)
    {
        $banks = User::where('role_id','4')->get();
        $areas = Area::where('delete_status','0')->get();
        $branchs = Branch::get();
        return view('master.branch',compact('branchs','banks','areas'));
    }

    public function branchstore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Branch::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Branch; 
            $data->created_by=Auth::user()->id;
        }
        $workingdays= implode(',', $request->workingdays);
        $data->name = $request->name;
        $data->user_id = $request->bank_id;
        $data->governate = $request->governate;
        $data->evening_branch_start_time = $request->evening_branch_start_time;
        $data->evening_branch_end_time = $request->evening_branch_end_time;
        $data->morning_branch_start_time = $request->morning_branch_start_time;
        $data->morning_branch_end_time = $request->morning_branch_end_time;
        $data->workingdays = $workingdays;
        $data->note = $request->note;
        $data->save();

        return Redirect::back()->with('alert', 'Records updated');
    }
     
    public function destroybranch($id,$val)
    {
        $data = Branch::find($id);
        $data->delete_status=$val;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Updated');
    }

    // Slot
    public function slots(Request $request)
    {   
        $slots = Slot::where('delete_status','0')->get();
        return view('master.slot',compact('slots'));
    }

    public function slotstore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Slot::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Slot; 
            $data->created_by=Auth::user()->id;
        }
        $data->start_time = $request->start_time;
        $data->end_time = $request->end_time;
        $data->name = $request->name;
        $data->quantity = $request->quantity;
        $data->save();

        return Redirect::back()->with('alert', 'Records updated');
    }
     
    public function destroyslot($id)
    {
        $data = Slot::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    //Area Day Slot
    public function areadayslots($id)
    {
        $areadayslots= Areadayslot::where('area_id',$id)->get();
        $slots = Slot::where('delete_status','0')->get();
        return view('master.areadayslots',compact('slots','areadayslots','id'));

    }

    public function storeareadayslots(Request $request)
    {
       // print_r(implode(',', $request->slot_id)); exit();
        $data = Areadayslot::find($request->editid);
        $data->slot_ids=implode(',', $request->slot_id);
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Updated');
    }

    //Rejectreason

    public function rejectreasons(Request $request)
    {   
        $rejectreasons = Rejectreason::where('delete_status','0')->get();
        return view('master.rejectreason',compact('rejectreasons'));
    }

    public function rejectreasonstore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Rejectreason::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Rejectreason; 
            $data->created_by=Auth::user()->id;
        }
        $data->name = $request->name;
        $data->save();

        return Redirect::back()->with('alert', 'Records updated');
    }
     
    public function destroyrejectreason($id)
    {
        $data = Rejectreason::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    //Enquirycategory

    public function enquirycategory(Request $request)
    {   
        $enquirycategories = Enquirycategory::where('delete_status','0')->get();
        return view('master.enquirycategories',compact('enquirycategories'));
    }

    public function enquirycategorystore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Enquirycategory::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Enquirycategory; 
            $data->created_by=Auth::user()->id;
        }
        $data->category_name = $request->name;
        $data->is_block_shipment = $request->is_block_shipment;
        $data->is_notifed = $request->is_notifed;
        $data->save();

        return Redirect::back()->with('alert', 'Records updated');
    }
     
    public function destroyenquirycategory($id)
    {
        $data = Enquirycategory::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    //Vechiclemodel
    public function vechiclemodels(Request $request)
    {   
        $vechiclemodels = Vechiclemodel::where('delete_status','0')->get();
        return view('master.vechiclemodel',compact('vechiclemodels'));
    }

    public function vechiclemodelstore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Vechiclemodel::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Vechiclemodel; 
            $data->created_by=Auth::user()->id;
        }
        $data->name = $request->name;
        $data->save();

        return Redirect::back()->with('alert', 'Records updated');
    }
     
    public function destroyvechiclemodel($id)
    {
        $data = Vechiclemodel::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    //Vechiclebrand
    public function vechiclebrands(Request $request)
    {   
        $vechiclebrands = Vechiclebrand::where('delete_status','0')->get();
        return view('master.vechiclebrand',compact('vechiclebrands'));
    }

    public function vechiclebrandstore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Vechiclebrand::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Vechiclebrand; 
            $data->created_by=Auth::user()->id;
        }
        $data->name = $request->name;
        $data->save();

        return Redirect::back()->with('alert', 'Records updated');
    }
     
    public function destroyvechiclebrand($id)
    {
        $data = Vechiclebrand::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    //Vechicle
    public function vechicles(Request $request)
    {
        
        $vechiclebrands = Vechiclebrand::get();
        $vechiclemodels = Vechiclemodel::get();
        $vechicles = Vechicle::where('delete_status','0')->get();
        return view('master.vechicle',compact('vechicles','vechiclemodels','vechiclebrands'));
    }

    public function vechiclestore(Request $request)
    {
        if(!empty($request->editid)){
            $data = Vechicle::find($request->editid);
            $data->updated_by=Auth::user()->id;
        }
        else{
            $data = new Vechicle; 
            $data->created_by=Auth::user()->id;
        }

        $data->name = $request->name;
        $data->model_id = $request->model_id;
        $data->brand_id = $request->brand_id;
        $data->year = $request->year;
        $data->description = $request->description;
        $data->save();

        return Redirect::back()->with('alert', 'Records updated');
    }

    public function destroyvechicle($id)
    {
        $data = Vechicle::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    #Vechicledriverlog
    public function vechicledriverlog($id)
    {   
        $drivers = User::where('role_id','6')->get();
        $vechicle = Vechicle::where('id',$id)->first();
        $vechicledriverlogs = Vechicledriverlog::where('delete_status','0')->where('vehicle_id',$id)->get();
        return view('master.vechicledriverlogs',compact('vechicledriverlogs','drivers','vechicle'));
    }

    public function vechicledriverstore(Request $request)
    {
        $vechicledriverlogs= Vechicledriverlog::where('vehicle_id',$request->vehicle_id)->orderBy('id','desc')->first();
        
        if($vechicledriverlogs){
            $data = Vechicledriverlog::find($vechicledriverlogs->id);
            $data->end_date = date('Y-m-d h:i:s');
            $data->save();
        }

        $data = new Vechicledriverlog; 
        $data->created_by=Auth::user()->id;
        $data->driver_id = $request->driver_id;
        $data->vehicle_id = $request->vehicle_id;
        $data->description = $request->description;
        $data->save();

        $data = User::find($request->driver_id);
        $data->vehicle_id = $request->vehicle_id;
        $data->save();

        $data = Vechicle::find($request->vehicle_id);
        $data->driver_id = $request->driver_id;
        $data->save();
        

        return Redirect::back()->with('alert', 'Records updated');
    }

    public function destroyvechiclelog($id)
    {
        $data = Vechicledriverlog::find($id);
        $data->delete_status=1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        return Redirect::back()->with('alert', 'Record Removed');
    }

    
    
}








