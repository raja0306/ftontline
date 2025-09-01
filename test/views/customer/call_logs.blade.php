<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Call Log details</h4>
<div class="table-responsive">
    <table class="table align-middle table-nowrap mb-0" id="datatable">
        <thead class="table-light">
            <tr>
                <th class="align-middle">Lead #</th>
                <th class="align-middle">List Name</th>
                <th class="align-middle">Phone</th>
                <th class="align-middle">Ingroup IVR</th>
                <th class="align-middle">Call Date</th>
                <th class="align-middle">Status</th>
                <th class="align-middle">User</th>
                <th class="align-middle">Length</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($outgoings as $log)
            <tr>
                <td>{{$log->lead_id}}</td>
                <td>{{App\VicidialLists::FindName($log->list_id)}}</td>
                <td>{{$log->phone_number}}</td>
                <td>{{$log->campaign_id}}</td>
                <td>{{$log->call_date}}</td>
                <td>{{App\VicidialList::Status($log->status)}} 
                <td>{{$log->user}}</td>
                <td>{{App\Average::toMinutes($log->length_in_sec)}}</td>
            </tr>
            @endforeach
            @foreach ($incomings as $log)
            <tr>
                <td>{{$log->lead_id}}</td>
                <td>{{App\VicidialLists::FindName($log->list_id)}}</td>
                <td>{{$log->phone_number}}</td>
                <td>{{$log->campaign_id}}</td>
                <td>{{$log->call_date}}</td>
                <td>{{App\VicidialList::Status($log->status)}} 
                <td>{{$log->user}}</td>
                <td>{{App\Average::toMinutes($log->length_in_sec)}}</td>
            </tr>
            @endforeach
            
        </tbody>
    </table>
</div>

</div>
</div>
</div>
</div>
<script src="{{asset('/assets')}}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){$("#datatable").DataTable({
   "order": [[ 3, 'desc' ]]
}),$("#datatable-buttons").DataTable({pageLength: 10,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});
</script>