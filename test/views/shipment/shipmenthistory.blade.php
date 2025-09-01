<h4>{{$shipment->barcode ?? ''}} History</h4>
<table class="table">
	<thead>
		<tr>
			<th>Action</th>
			<th>Old Status</th>
			<th>New Status</th>
			<th>User</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody>
		@foreach($shipmentinfos as $log)
			<tr>
				<td>{{$log->message}}</td>
				<td>{{$log->old_data}}</td>
				<td>{{$log->new_data}}</td>
				<td>{{$log->user->name ?? 'N/A'}}</td>
				<td>{{date('d M,Y h:i:s',strtotime($log->created_at))}}</td>
			</tr>
		@endforeach
	</tbody>
</table>