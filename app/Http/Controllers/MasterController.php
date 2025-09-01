<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use Redirect;
use App\Menu;
use App\Role;
use App\Area;
use App\Governorate;
use App\User;
use App\Branch;
use App\Tray;
use App\Menurole;
use App\Appointment;
use App\VicidialList;
use App\Jobs\SendWhatsappAppointment;
use Illuminate\Support\Facades\Artisan;
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
        $data->governorate_id = $request->governorate_id;
        $data->save();

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

        // SendWhatsappAppointment::dispatch(
        //     '98810785',
        //     'Raja',
        //     '28 July,2025',
        //     '10am-12pm',
        //     'hawally',
        //     '2',
        //     'Bin Khloodeen',
        //     '1234',
        //     '2',
        //     '2',
        //     'MC Don',
        //     '123456'
        // );

        //Artisan::call("queue:work");

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
        $data->name = $request->name;
        $data->user_id = $request->bank_id;
        $data->area_id = $request->area_id;
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
}








