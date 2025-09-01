<div class="card-body">
    <div class="row">
        <div class="col-3">
            <h5 class="font-size-15 text-truncate">{{$inbound}}</h5>
            <p class="text-muted mb-0 text-truncate">Incoming</p>
        </div>
        <div class="col-3">
            <h5 class="font-size-15 text-truncate">{{$missedcallcount}}</h5>
            <p class="text-muted mb-0 text-truncate">Missed</p>
        </div>
        <div class="col-3">
            <h5 class="font-size-15 text-truncate">{{$net_missed}}</h5>
            <p class="text-muted mb-0 text-truncate">Net Missed</p>
        </div>
        <div class="col-3">
            <h5 class="font-size-15 text-truncate">{{$missedpercentage}}</h5>
            <p class="text-muted mb-0 text-truncate">Missed %</p>
        </div>
    </div>
    <div class="mt-4">
    <div class="progress progress-xl">
        <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{$missedpercentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$missedpercentage}}%"></div>
    </div>
    </div>
</div>
