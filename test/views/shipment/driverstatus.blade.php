<div class="row">
  <div class="col-lg-12">
    <div class="card mini-stats-wid">
      <div class="card-body">
      	<center><h5 class="mb-2">Delivery Status</h5></center>
      	<table class="table align-middle mb-0">
      		<thead class="table-light">
      			<tr>
      				<th>Name</th>
      				<th>Total</th>
      				<th>Pending</th>
      				<th>Delivered</th>
      			</tr>
      		</thead>
      		<tbody>
      			@foreach($deliveryCounts as $log)
      				<tr>
      					<td>{{$log->driver->name ?? ''}}}</td>
      					<td>{{$log->total}}}</td>
      					<td></td>
      					<td></td>
      				</tr>
      			@endforeach
      		</tbody>
      	</table>
      </div>
  </div>
</div>
