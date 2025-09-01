@foreach($lists as $list)
@php 
$shipment = $list->shipment;
@endphp
    <tr>
        <td>{{$shipment->id}}</td>
        <td>{{$shipment->reference}}</td>
        <td>{{$shipment->consignee_name}}</td>
        <td>{{$shipment->consignee_phone}}</td>
        <td>{{$shipment->barcode}}</td>
        <td>{{$list->tray->name}}</td>
        <td>{{$list->fromtray->name ?? ''}}</td>
        <td><a href="#" class="btn btn-danger btn-sm" onclick="removeTray({{$list->id}});">Remove</a></td>
    </tr>
@endforeach