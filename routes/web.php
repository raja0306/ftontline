<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Dashboard
Route::get("/", "HomeController@index")->name("home");
Route::get("/home", "HomeController@index")->name("home");
Route::get("/profile", "HomeController@profile")->name("profile");
//Call
Route::get("/calls", "CallLogController@index")->name("calls");
Route::get("/calls/inbound", "CallLogController@inbound")->name("inbound-calls");
Route::get("/calls/outbound", "CallLogController@outbound")->name("outbound-calls");
Route::get("/calls/missed", "CallLogController@missed")->name("missed-calls");

//Recordings
Route::get("/recordings", "RecordingController@index")->name("recordings");
Route::get("/recordings/outbound", "RecordingController@index")->name("records-outbound");
Route::get("/recordings/inbound", "RecordingController@index")->name("records-inbound");
Route::get("/recordings_all", "RecordingController@recordall")->name("recordings.all");

//Leads
Route::get("/leads", "LeadsController@index")->name("leads");

Route::get("/leads/status", "LeadsController@status")->name("leads-status");
Route::get("/changestatus/{id}/{status}", "LeadsController@changestatus");
Route::post("/addnewlist", "LeadsController@addnewlist");
Route::get("/deletelist/{id}", "LeadsController@delete");
Route::get("/shipment/upload", "LeadsController@shipmentupload")->name("shipmentupload");
Route::post("/shipment/upload", "LeadsController@shipmentupload")->name("shipmentupload");
Route::get("upload/shipment/view/{id}", "LeadsController@viewshipmentupload")->name("viewshipmentupload");
Route::get("/book/apppointment/view/{appointment_id}", "LeadsController@viewappointment")->name("viewappointment");
Route::get("/shipment/bulkupload", "UploadController@bulkupload")->name("bulkupload");
Route::post("/shipment/bulkupload", "UploadController@bulkupload")->name("bulkupload");

Route::get("/apppointments/", "AppointmentController@index")->name("apppointments");
Route::post("/apppointments/", "AppointmentController@index")->name("apppointments");
Route::get("/apppointments/labels", "AppointmentController@labels")->name("apppointments.labels");
Route::post("/apppointments/labels", "AppointmentController@labels")->name("apppointments.labels");
Route::post("generate/apppointments/labels", "AppointmentController@labelgenerate")->name("labelgenerate");
Route::get("/labelfull", "AppointmentController@labelfull")->name("labelfull");


Route::get("/uploadcsv", "UploadController@index")->name("uploadcsv");
Route::post("/autoupload/upload", "UploadController@upload");
Route::get("/bulk/master", "UploadController@bulkmaster");
Route::get("/list/file/download", "LeadsController@downloadList")->name("downloadcsv");

Route::get("/enquiries", "EnquiryController@index")->name("enquiries");

//Agent
Route::get("/agent/performance", "AgentController@performance")->name("agent-performance");
Route::get("/agent/time", "AgentController@time")->name("agent-time");

Route::get("/agent/kpi", "AgentController@kpilogs")->name("agent-kpi");
Route::get("/agent/kpi/{fdate}/{tdate}/{agent}", "AgentController@kpilogs")->name("agent-kpi");

//Master
Route::get('/master/areas','MasterController@areas')->name('master.areas');
Route::post('/master/areas/store','MasterController@areastore')->name('master.area.store');
Route::get('/master/areas/delete/{id}','MasterController@destroyarea')->name('master.area.delete');

Route::get('/master/tray','MasterController@trays')->name('master.trays');
Route::post('/master/trays/store','MasterController@traystore')->name('master.tray.store');
Route::get('/master/trays/delete/{id}','MasterController@destroytray')->name('master.tray.delete');

Route::get('/master/branchs','MasterController@branchs')->name('master.branchs');
Route::post('/master/branchs/store','MasterController@branchstore')->name('master.branch.store');
Route::get('/master/branchs/delete/{id}/{val}','MasterController@destroybranch')->name('master.branch.delete');

Route::get('/master/role','MasterController@role');
Route::post('/master/role/store','MasterController@rolestore');

Route::get('/master/menu','MasterController@menu');
Route::post('/master/menu/store','MasterController@menustore');

Route::get("/users", "HomeController@users")->name("users");
Route::get("/all_groups", "HomeController@all_groups")->name("all_groups");
Route::post("/user_store", "HomeController@userstore")->name("userstore");
Route::post("/user_update", "HomeController@userupdate")->name("userupdate");

//Others
Route::get("/generate_metrics", "HomeController@generate_metrics")->name("generate_metrics");
Route::get("/centrix_metrics", "HomeController@centrix_metrics")->name("centrix_metrics");
Route::get("/getcampaignlists/{id}", "HomeController@getcampaignlists")->name("getcampaignlists");

//Customer Popup
Route::get("/cronmissed", "CronController@cronmissed")->name("cronmissed");
Route::get('/frontlinemessages','WhatsappController@webhooklog');
Route::post('/frontlinemessages','WhatsappController@webhooklog');
Route::get('/book/customer/{mobile}/{user}/{listid}/{campaignid}/{ingroup}/{leadid}','CustomerbookController@book');
Route::get('/book/customer/{mobile}/{user}/{listid}/{campaignid}/{ingroup}/{leadid}/{appid}','CustomerbookController@book');
Route::get('/customer/calllogs/{mobile}','CustomerbookController@calllogs');
Route::get('/customer/apptlogs/{mobile}','CustomerbookController@apptlogs');
Route::post('/bookappointment','CustomerbookController@store');
Route::post('/store_enquiry','CustomerbookController@store_enquiry');
Route::get('/customersearch/{civil}/{user}/{listid}/{campaignid}/{ingroup}/{leadid}','CustomerbookController@customersearch');
Route::get("/date_apps/{date}", "CustomerbookController@date_apps")->name("date_apps");



//PACCI API
Route::get('/paciiauth','PaciiapiController@paciiauth');
Route::get('/getGovernorate','PaciiapiController@getGovernorate');
Route::get('/getNeighborhood','PaciiapiController@getNeighborhood');
Route::get('/getBlocks','PaciiapiController@getBlocks');
Route::get('/getStreet','PaciiapiController@getStreet');
Route::get('/paciinosearch/{paciino}','PaciiapiController@paciinosearch');


Route::get("/login", function () {
    return view("auth.login");
})->name("login");
Route::post("/login", "HomeController@logincheck")->name("logincheck");
Route::get("/logout", "HomeController@logout")->name("logout");
Route::get("/authuser/{id}", "HomeController@authuser")->name("authuser");

Route::group(["middleware" => "web"], function () {
    Route::get("/dashboard", "DashboardController@dashboard");
    Route::get("/net_missed", "DashboardController@net_missed");
    Route::get("/outbound_summary", "DashboardController@outbound_summary");
    Route::get("/agent_summary", "DashboardController@agent_summary");
    Route::get("/monthly_calls", "DashboardController@monthly_calls");
    Route::get("/hourly_calls", "DashboardController@hourly_calls");
    Route::get("/live_calls", "DashboardController@live_calls");
    Route::get("/queue_calls", "DashboardController@queue_calls");
    Route::get("/list_dashboards", "DashboardController@list_dashboards");

    Route::get("/list_dashboards1", "DashboardController@list_dashboards1");
    Route::get("/gmaftermissed", "DashboardController@gmaftermissed");
        Route::get("/gmafternetmissed", "DashboardController@gmafternetmissed");
    Route::get("/pause_agents", "DashboardController@pause_agents");
    Route::get("/onehrmissed", "DashboardController@onehrmissed");
    Route::get("/sendwa_yellow_chery/{phone}/{temp}/{brand}/{lead}/{user}", "DashboardController@sendwa_yellow_chery");
});
Route::get('/listexport','ExportController@listexport')->name('listexport'); 

Route::get("/labeldesign", function () { return view("extras.label"); });

Route::get("/", "HomeController@index")->name("home");
Route::get("/phpinfo", function () {
    echo phpinfo();
});
Route::get("/clearcache", function () {
    Artisan::call("cache:clear");
    Artisan::call("config:cache");
    Artisan::call("view:clear");
    return "Cleared!";
});

Auth::routes();

// Route::get('/', function () { return view('welcome'); });
// Route::get('/home', 'HomeController@index')->name('home');
// Route::auth();
