<div class="card">
    <div class="card-body">
        <div class="row">
            <center><h5 class="mb-2">Card Status</h5></center>
            <div class="col-lg-12 align-self-center">
                <div class="text-lg-center mt-4 mt-lg-0">
                    <div class="row">
                        @foreach($cardstatus as $row)
                          <div class="col-1">
                              <div>
                                  <p class="text-muted  mb-2">{{$row->name}}</p><br>
                              </div>
                          </div>
                        @endforeach
                    </div>
                    <div class="row">
                        @foreach($cardstatus as $row)
                          <div class="col-1">
                              <div>
                                  <h5 class="mb-0">{{$row->shipemnts->count()}}</h5>
                              </div>
                          </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
</div>