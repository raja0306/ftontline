<form method="post" action="{{url('store_enquiry')}}">
@csrf
<div class="row">
    <div class="col-md-3">
            <label>Shipments:</label>
            @foreach($shipments as $shipment)
            <div class="form-check mb-3">
                <input class="form-check-input" name="shipment_ids[]" value="{{$shipment->id}}" type="checkbox" id="shipp_{{$shipment->id}}" checked>
                <label class="form-check-label text-dark" for="shipp_{{$shipment->id}}">{{$shipment->barcode}}-{{$shipment->description}}-
                {{$shipment->commodity_name}} - {{$shipment->cstatus}}</label>
            </div>
            @endforeach
        </div>
	<div class="col-md-3">
		<div>
			<label>Call feedback</label>
			<select class="form-select" id="enq_cat_id" name="enq_cat_id" required>
				<option value="">Choose</option>
				@foreach($enq_categories as $enqc)
				<option value="{{$enqc->id_enquiry_category}}">{{$enqc->category_name}}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div>
			<label>Followup date</label>
			<input class="form-control" type="date" name="follow_date" min="{{ date('Y-m-d') }}"></textarea>
		</div>
	</div>
	<div class="col-md-3">
		<div>
            <label>Description</label>
			<textarea class="form-control" type="text" name="description"></textarea>
		</div>
	</div>
    <div class="col-md-12 offset-4 mt-2 ">
        <input type="hidden" name="user" value="{{$user}}">
        <input type="hidden" name="lead_id" value="{{$leadid}}">
        <input type="hidden" name="mobile" value="{{$mobile}}">
        <input type="hidden" name="user_id" value="{{$customer->id ?? '0'}}">
        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
    </div>
</div>
</form>
<div class="row">
<div class="col-lg-12">
<h4 class="card-title my-4">Enquiry details</h4>
<div class="table-responsive">
    <table class="table align-middle table-nowrap mb-0">
        <thead class="table-light">
            <tr>
                <th>Lead #</th>
                <th>Enquiry</th>
                <th>Phone Number</th>
                <th>Description</th>
                <th>FollowUp date</th>
                <th>User</th>
                <th>Created at</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($enquiries as $list)
            <tr>
                <td>{{ $list->lead_id }}</td>
                <td>{{ App\Enquiry::FindName($list->enq_id)}}</td>
                <td>{{ $list->mobile }}</td>
                <td>@if($list->appointment_date){{ date('d M Y',strtotime($list->appointment_date)) }}@endif</td>
                <td>{{ $list->description }}</td>
                <td>{{ $list->user }}</td>
                <td>{{ date('d M, Y H:i A',strtotime($list->created_at)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>