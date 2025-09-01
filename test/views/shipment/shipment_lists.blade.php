@if($statustype==1)
    <thead>
        <tr>
            <th>Shipment ID</th>
            <th>Consignee</th>
            <th>Mobile</th>
            <th>Barcode</th>
            <th>Appointment ID</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
   <tbody>
    @foreach($lists as $list)
        @php 
        $shipment = $list->shipment;
        @endphp
    <tr>
        <td>{{$shipment->id}}</td>
        <td>{{$shipment->consignee_name}}</td>
        <td>{{$shipment->consignee_phone}}</td>
        <td>{{$shipment->barcode}}</td>
        <td>{{$shipment->appointment->id ?? ''}}</td>
        <td>{{$shipment->cardstatus->name ?? ''}}</td>
        <td><a href="#" class="btn btn-danger btn-sm" onclick="removeShipmentStatus({{$list->id}});">Remove</a></td>
    </tr>
    @endforeach
    </tbody>
@endif
@if($statustype==2 || $statustype==3)
    <thead>
        <tr>
            <th>Shipment ID</th>
            <th>Consignee</th>
            <th>Mobile</th>
            <th>Barcode</th>
            <th>Appointment ID</th>
            <th>Driver</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
   <tbody>
    @foreach($lists as $list)
        @php 
        $shipment = $list->shipment;
        @endphp
    <tr>
        <td>{{$shipment->id}}</td>
        <td>{{$shipment->consignee_name}}</td>
        <td>{{$shipment->consignee_phone}}</td>
        <td>{{$shipment->barcode}}</td>
        <td>{{$shipment->appointment->id ?? ''}}</td>
        <td>{{$shipment->appointment->driver->name ?? ''}}</td>
        <td>{{$shipment->cardstatus->name ?? ''}}</td>
        <td><a href="#" class="btn btn-danger btn-sm" onclick="removeShipmentStatus({{$list->id}});">Remove</a></td>
    </tr>
    @endforeach
    </tbody>
@endif