<div class="row">
<div class="col-md-6">
<div class="card overflow-hidden">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-cadmissed_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsecadmissed_summary" aria-expanded="true" aria-controls="flush-collapsecadmissed_summary">
    Cadillac - Missed Calls
    </button>
</h2>
<div id="flush-collapsecadmissed_summary" class="accordion-collapse collapse show" aria-labelledby="flush-cadmissed_summary">
    <div class="card-body">
        <div class="row">
            <div class="col-4">
                <h5 class="font-size-15 text-truncate">{{$cadincoming}}</h5>
                <p class="text-muted mb-0 text-truncate">Incoming</p>
            </div>
            <div class="col-4">
                <h5 class="font-size-15 text-truncate">{{$cadmissed}}</h5>
                <p class="text-muted mb-0 text-truncate">Missed</p>
            </div>
            <div class="col-4">
                <h5 class="font-size-15 text-truncate">{{$avgcadmissed}}</h5>
                <p class="text-muted mb-0 text-truncate">Missed %</p>
            </div>
        </div>
        <div class="mt-4">
        <div class="progress progress-xl">
            <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{$avgcadmissed}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$avgcadmissed}}%"></div>
        </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="col-md-6">
<div class="card overflow-hidden">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-chevmissed_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsechevmissed_summary" aria-expanded="true" aria-controls="flush-collapsechevmissed_summary">
    Chevrolet - Missed Calls
    </button>
</h2>
<div id="flush-collapsechevmissed_summary" class="accordion-collapse collapse show" aria-labelledby="flush-chevmissed_summary">
    <div class="card-body">
        <div class="row">
            <div class="col-4">
                <h5 class="font-size-15 text-truncate">{{$chevincoming}}</h5>
                <p class="text-muted mb-0 text-truncate">Incoming</p>
            </div>
            <div class="col-4">
                <h5 class="font-size-15 text-truncate">{{$chevmissed}}</h5>
                <p class="text-muted mb-0 text-truncate">Missed</p>
            </div>
            <div class="col-4">
                <h5 class="font-size-15 text-truncate">{{$avgchevmissed}}</h5>
                <p class="text-muted mb-0 text-truncate">Missed %</p>
            </div>
        </div>
        <div class="mt-4">
        <div class="progress progress-xl">
            <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{$avgcadmissed}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$avgcadmissed}}%"></div>
        </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>


<div class="row d-none">
<div class="col-md-6">
<div class="card overflow-hidden">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-cecmissed_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsececmissed_summary" aria-expanded="true" aria-controls="flush-collapsececmissed_summary">
    GM - Missed Calls
    </button>
</h2>
<div id="flush-collapsececmissed_summary" class="accordion-collapse collapse show" aria-labelledby="flush-cecmissed_summary">
    <div class="card-body">
        <div class="row">
            <div class="col-4">
                <h5 class="font-size-15 text-truncate">{{$cectogm_calls}}</h5>
                <p class="text-muted mb-0 text-truncate">Incoming</p>
            </div>
            <div class="col-4">
                <h5 class="font-size-15 text-truncate">{{$cectogm_missedcalls}}</h5>
                <p class="text-muted mb-0 text-truncate">Missed</p>
            </div>
            <div class="col-4">
                <h5 class="font-size-15 text-truncate">{{$cectogm_percentage}}</h5>
                <p class="text-muted mb-0 text-truncate">Missed %</p>
            </div>
        </div>
        <div class="mt-4">
        <div class="progress progress-xl">
            <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{$cectogm_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$cectogm_percentage}}%"></div>
        </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
