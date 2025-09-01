<div class="vertical-menu">
<?php $routeName = Request::route()->getName(); ?>
<?php
    $role_id=Auth::user()->role_id;
?>
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <div class="mx-3 mt-2 mt-xl-0 d-none" role="group" aria-label="Basic checkbox toggle button group">
            </div>
            <ul class="metismenu list-unstyled" id="side-menu">
                @if(App\Menurole::getMenuRole($role_id,1))
                    <li class="menu-title" key="t-menu">Menu</li>
                    @if(App\Menurole::getMenuRole($role_id,2))
                        <li @if($routeName == 'home') class="mm-active" @endif><a href="{{url('/')}}" class="waves-effect"><i class="bx bx-card"></i><span key="t-calendar">Dashboard</span></a></li>
                    @endif
                    @if(App\Menurole::getMenuRole($role_id,21))
                        <li @if($routeName == 'master') class="mm-active" @endif>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-extension"></i>
                                <span key="t-tasks">Master</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @if(App\Menurole::getMenuRole($role_id,19))
                                    <li @if($routeName == 'users') class="mm-active" @endif><a href="{{url('/users')}}" key="t-task-list">Users</a></li>
                                @endif

                                @if(App\Menurole::getMenuRole($role_id,24))
                                    <li @if($routeName == 'master.branchs') class="mm-active" @endif><a href="{{url('/master/branchs')}}" key="t-task-list">Branch</a></li>
                                @endif

                                @if(App\Menurole::getMenuRole($role_id,22))
                                    <li @if($routeName == 'master.areas') class="mm-active" @endif><a href="{{url('/master/areas')}}" key="t-task-list">Area</a></li>
                                @endif

                                @if(App\Menurole::getMenuRole($role_id,30))
                                    <li @if($routeName == 'master.slots') class="mm-active" @endif><a href="{{url('/master/slots')}}" key="t-task-list">Slots</a></li>
                                @endif

                                @if(App\Menurole::getMenuRole($role_id,23))
                                    <li @if($routeName == 'master.trays') class="mm-active" @endif><a href="{{url('/master/tray')}}" key="t-task-list">Tray</a></li>
                                @endif

                                @if(App\Menurole::getMenuRole($role_id,27))
                                    <li @if($routeName == 'master.cardtypes') class="mm-active" @endif><a href="{{url('/master/cardtypes')}}" key="t-task-list">Cardtype</a></li>
                                @endif

                                @if(App\Menurole::getMenuRole($role_id,28))
                                    <li @if($routeName == 'master.commodities') class="mm-active" @endif><a href="{{url('/master/commodities')}}" key="t-task-list">Commodity</a></li>
                                @endif
                                @if(App\Menurole::getMenuRole($role_id,33))
                                    <li @if($routeName == 'master.rejectreasons') class="mm-active" @endif><a href="{{url('/master/rejectreasons')}}" key="t-task-list">Reject Reason</a></li>
                                @endif
                                @if(App\Menurole::getMenuRole($role_id,37))
                                    <li @if($routeName == 'master.enquirycategory') class="mm-active" @endif><a href="{{url('/master/enquirycategory')}}" key="t-task-list">Enquiry Category</a></li>
                                @endif

                                @if(App\Menurole::getMenuRole($role_id,41))
                                    <li @if($routeName == 'master.vechiclemodels') class="mm-active" @endif><a href="{{url('/master/vechiclemodel')}}" key="t-task-list">Vechicle Model</a></li>
                                @endif

                                @if(App\Menurole::getMenuRole($role_id,42))
                                    <li @if($routeName == 'master.vechiclebrands') class="mm-active" @endif><a href="{{url('/master/vechiclebrand')}}" key="t-task-list">Vechicle Brand</a></li>
                                @endif

                                @if(App\Menurole::getMenuRole($role_id,43))
                                    <li @if($routeName == 'master.vechicle') class="mm-active" @endif><a href="{{url('/master/vechicle')}}" key="t-task-list">Vechicle</a></li>
                                @endif

                            </ul>
                        </li>
                    @endif
                    @if(App\Menurole::getMenuRole($role_id,3))
                        <li >
                            <a href="javascript: void(0);" class="has-arrow waves-effect"><i class="bx bx-phone-incoming"></i><span key="t-invoices">Call Logs</span></a>
                            <ul class="sub-menu" aria-expanded="false">
                                @if(App\Menurole::getMenuRole($role_id,4))
                                    <li @if($routeName == 'outbound-calls') class="mm-active" @endif><a href="{{url('/calls/outbound')}}" key="t-invoice-list">Outgoing Calls</a></li>
                                @endif
                                @if(App\Menurole::getMenuRole($role_id,6))
                                    <li @if($routeName == 'inbound-calls') class="mm-active" @endif><a href="{{url('/calls/inbound')}}" key="t-invoice-list">Incoming Calls</a></li>
                                @endif
                                @if(App\Menurole::getMenuRole($role_id,7))
                                    <li @if($routeName == 'missed-calls') class="mm-active" @endif><a href="{{url('/calls/missed')}}?status=missed" key="t-invoice-list">Missed Calls</a></li>
                                @endif
                            </ul>
                        </li>  
                    @endif
                    @if(App\Menurole::getMenuRole($role_id,5))     
                        <li >
                            <a href="javascript: void(0);" class="has-arrow waves-effect"><i class="bx bx-microphone"></i><span key="t-invoices">Recordings</span></a>
                            <ul class="sub-menu" aria-expanded="false">
                                @if(App\Menurole::getMenuRole($role_id,8))
                                    <li><a href="{{url('/recordings_all')}}" key="t-invoice-list">All Records</a></li>
                                @endif
                                @if(App\Menurole::getMenuRole($role_id,9))
                                    <li @if($routeName == 
                                    'records-inbound') class="mm-active" @endif><a href="{{url('/recordings/inbound')}}" key="t-invoice-list">Incoming Records</a></li>
                                @endif
                                @if(App\Menurole::getMenuRole($role_id,10))
                                    <li @if($routeName == 'records-outbound') class="mm-active" @endif><a href="{{url('/recordings/outbound')}}?call_type=out" key="t-invoice-list">Outgoing Records</a></li>
                                @endif
                            </ul>
                        </li> 
                    @endif 
                @endif
                @if(App\Menurole::getMenuRole($role_id,11))
                    <li class="menu-title" key="t-menu">Leads</li>

                    @if(App\Menurole::getMenuRole($role_id,13))
                        <li @if($routeName == 'leads-status') class="mm-active" @endif><a href="{{url('/leads/status')}}" class="waves-effect"><i class="bx bx-dialpad-alt"></i><span key="t-calendar">List status</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,14))
                        <li @if($routeName == 'uploadcsv') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/uploadcsv')}}" class="waves-effect"><i class="bx bx-upload"></i><span key="t-calendar">Upload</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,12))
                        <li @if($routeName == 'leads') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/leads')}}" class="waves-effect"><i class="bx bx-list-ul"></i><span key="t-calendar">Details</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,20))
                        <li @if($routeName == 'enquiries') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/enquiries')}}" class="waves-effect"><i class="bx bx-task"></i><span key="t-calendar">Enquiries</span></a></li>
                    @endif
                    
                @endif

                @if(App\Menurole::getMenuRole($role_id,32))
                    <li class="menu-title" key="t-menu">Card Shipments</li>

                    @if(App\Menurole::getMenuRole($role_id,38))
                        <li @if($routeName == 'shipment.dashboard') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/shipment/dashboard')}}" class="waves-effect"><i class="bx bx-layout"></i><span key="t-calendar">Shipment Dashboard</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,29))
                        <li @if($routeName == 'uploadshipmentform') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/upload/shipment/')}}" class="waves-effect"><i class="bx bx-task"></i><span key="t-calendar">Shipment Upload</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,25))
                        <li @if($routeName == 'bulkupload') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/shipment/bulkupload/')}}" class="waves-effect"><i class="bx bx-receipt"></i><span key="t-calendar">Shipment List</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,39))
                        <li @if($routeName == 'blockedshipment') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/shipment/blocked/')}}" class="waves-effect"><i class="bx bx-block"></i><span key="t-calendar">Blocked Shipment</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,31))
                        <li @if($routeName == 'tray.update') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('tray/update')}}" class="waves-effect"><i class="bx bx-chip"></i><span key="t-calendar">Tray Update</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,26))
                        <li @if($routeName == 'apppointments') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/apppointments')}}" class="waves-effect"><i class="bx bx-calendar"></i><span key="t-calendar">Appointments</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,34))
                        <li @if($routeName == 'shipment.status' && $_GET['statustype']=='1') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/shipment/status')}}?statustype=1" class="waves-effect"><i class="bx bx-label"></i><span key="t-calendar">Dispatch</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,36))
                        <li @if($routeName == 'shipment.status' && $_GET['statustype']=='2') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/shipment/status')}}?statustype=2" class="waves-effect"><i class="bx bx-archive-out"></i><span key="t-calendar">Out of Delivery</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,40))
                        <li @if($routeName == 'deliverylist') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('delivery/list')}}?statustype=2" class="waves-effect"><i class="bx bxs-spreadsheet"></i><span key="t-calendar">Run Sheet</span></a></li>
                    @endif

                    @if(App\Menurole::getMenuRole($role_id,35))
                        <li @if($routeName == 'shipment.status' && $_GET['statustype']=='3') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/shipment/status')}}?statustype=3" class="waves-effect"><i class="bx bx-area"></i><span key="t-calendar">Renconcilation</span></a></li>
                    @endif

                @endif

                @if(App\Menurole::getMenuRole($role_id,45))
                    <li class="menu-title" key="t-menu">E-commerce</li>
                    @if(App\Menurole::getMenuRole($role_id,44))
                        <li @if($routeName == 'pickuprequest') class="mm-active gm_user" @else class="gm_user" @endif><a href="{{url('/pickuprequest/')}}" class="waves-effect"><i class="bx bx-git-pull-request"></i><span key="t-calendar">Pickup Request</span></a></li>
                    @endif
                @endif

                @if(App\Menurole::getMenuRole($role_id,15))
                    <li class="menu-title" key="t-menu">Agent</li>
                    @if(App\Menurole::getMenuRole($role_id,16))
                        <li @if($routeName == 'agent-performance') class="mm-active" @endif><a href="{{url('/agent/performance')}}" class="waves-effect"><i class="bx bx-run"></i><span key="t-calendar">Performance</span></a></li>
                    @endif
                    @if(App\Menurole::getMenuRole($role_id,17))
                        <li @if($routeName == 'agent-time') class="mm-active" @endif><a href="{{url('/agent/time')}}" class="waves-effect"><i class="bx bx-timer"></i><span key="t-calendar">Time</span></a></li>
                    @endif
                    @if(App\Menurole::getMenuRole($role_id,18))
                        <li @if($routeName == 'agent-kpi') class="mm-active" @endif><a href="{{url('/agent/kpi')}}" class="waves-effect"><i class="bx bx-time-five"></i><span key="t-calendar">Login / Logout</span></a></li>
                    @endif
                @endif
            </ul>
        </div>
    </div>
</div> 


