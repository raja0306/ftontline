<div class="row">
  <div class="col-lg-4">
    <div class="card mini-stats-wid">
      <div class="card-body">
          <div class="d-flex flex-wrap">
              <div class="me-1">
                  <p class="text-muted mb-2">Card Orders</p>
                  <h5 class="mb-0">{{$cardcount}}</h5>
              </div>
          </div><br>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card mini-stats-wid">
      <div class="card-body">
          <div class="d-flex flex-wrap">
              <div class="me-1">
                  <p class="text-muted mb-2">Forward Orders</p>
                  <h5 class="mb-0 text-primary">{{$front_count}}</h5>
              </div>
          </div><br>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card mini-stats-wid">
      <div class="card-body">
          <div class="d-flex flex-wrap">
              <div class="me-1">
                  <p class="text-muted mb-2">Reverse Orders</p>
                  <h5 class="mb-0 text-success">{{$reverse_count}}</h5>
              </div>
          </div><br>
      </div>
    </div>
  </div>
</div>