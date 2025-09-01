<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-queuecall_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsequeuecall_summary" aria-expanded="true" aria-controls="flush-collapsequeuecall_summary">
    Waiting calls on queue
    </button>
</h2>
<div id="flush-collapsequeuecall_summary" class="accordion-collapse collapse show" aria-labelledby="flush-queuecall_summary">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-nowrap mb-0">
                <thead class="table-light">
                    <tr>
                        <th> Call Id</th>
                        <th> Campaign</th>
                        <th> Status</th>
                        <th> Channel</th>
                        <th> Phone</th>
                        <th> Date Time</th>
                        <th> Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($queue_calls as $live)
                    <tr>
                        <td>{{$live->auto_call_id}}</td>
                        <td>{{$live->campaign_id}}</td>
                        <td>{{$live->status}}</td>
                        <td>{{$live->channel}}</td>
                        <td>{{$live->phone_number}}</td>
                        <td>{{$live->last_update_time}}</td>
                        <td>{{$live->call_type}}</td>
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