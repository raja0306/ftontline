<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-livecall_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapselivecall_summary" aria-expanded="true" aria-controls="flush-collapselivecall_summary">
    Live Agents On Calls
    </button>
</h2>
<div id="flush-collapselivecall_summary" class="accordion-collapse collapse show" aria-labelledby="flush-livecall_summary">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-nowrap mb-0">
                <thead class="table-light">
                    <tr>
                        <th> Station</th>
                        <th> UserID</th>
                        <th> SessionID</th>
                        <th> Status</th>
                        <th> Campaign</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($liveagents as $live)
                    <tr>
                        <td>{{$live->extension}}</td>
                        <td>{{$live->user}}</td>
                        <td>{{$live->conf_exten}}</td>
                        <td>@if($live->status == 'CLOSER')<span class="label label-success label-mini">READY</span> @elseif($live->status == 'INCALL')<span 
    class="label label-success label-mini">{{$live->status}}</span>  @elseif($live->status == 'PAUSED')<span class="label label-warning 
    label-mini">{{$live->status}}</span> @else <span class="label label-info label-mini">{{$live->status}}</span> @endif</td>
                        <td>{{$live->campaign_id}}</td>
                        <!-- <td>{{$live->calls_today}}</td> -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>