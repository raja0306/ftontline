
<div class="row">
<div class="col-lg-4">
<div class="card mini-stats-wid">
  <div class="card-body">
      <div class="d-flex flex-wrap">
          <div class="me-1">
              <p class="text-muted mb-2">Total Calls</p>
              <h5 class="mb-0">{{$inbound+$outbound}}</h5>
          </div>
      </div>
  </div>
</div>
</div>

<div class="col-lg-4">
<div class="card mini-stats-wid">
  <div class="card-body">
      <div class="d-flex flex-wrap">
          <div class="me-1">
              <p class="text-muted mb-2">Outbound Calls </p>
              <h5 class="mb-0 text-primary">{{$outbound}}</h5>
              <span style="margin: 2px;" class="badge rounded-pill bg-danger float-end">Drop - {{$drop}} </span>
              <span style="margin: 2px;" class="badge rounded-pill bg-danger float-end">NA - {{$na}}</span>
              <span style="margin: 2px;" class="badge rounded-pill bg-danger float-end">Busy - {{$busy}}</span>
          </div>
      </div>
  </div>
</div>
</div>

<div class="col-lg-4">
<div class="card mini-stats-wid">
  <div class="card-body">
      <div class="d-flex flex-wrap">
          <div class="me-1">
              <p class="text-muted mb-2">Outbound Connected</p>
              <h5 class="mb-0 text-success">{{$outbound_connected}}</h5>
          </div>
      </div>
  </div>
</div>
</div>

<div class="col-lg-4">
<div class="card mini-stats-wid">
  <div class="card-body">
      <div class="d-flex flex-wrap">
          <div class="me-1">
              <p class="text-muted mb-2">Inbound Calls</p>
              <h5 class="mb-0 text-info">{{$inbound}}</h5>
          </div>
      </div>
  </div>
</div>
</div>

<div class="col-lg-4">
<div class="card mini-stats-wid">
  <div class="card-body">
      <div class="d-flex flex-wrap">
          <div class="me-1">
              <p class="text-muted mb-2">Missed Calls</p>
              <h5 class="mb-0 text-warning">{{$missed}}</h5>
          </div>
      </div>
  </div>
</div>
</div>

<div class="col-lg-4">
<div class="card mini-stats-wid">
  <div class="card-body">
      <div class="d-flex flex-wrap">
          <div class="me-1">
              <p class="text-muted mb-2">Net Missed Calls</p>
              <h5 class="mb-0 text-danger net_missed">
                <div hx-get="{{url('/net_missed')}}?fromdate={{$fromdate}}&todate={{$todate}}&inbound={{$inbound}}" hx-trigger="load, every 120s" hx-target="#net_missed_count"><div id="net_missed_count">Calculating...</div></div>
              </h5>
          </div>
      </div>
  </div>
</div>
</div>

</div>

