@extends('layouts.master')
@section('title', 'Run Sheet')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
@php
use Picqer\Barcode\BarcodeGeneratorPNG;
@endphp
<style> .dataTables_info, .dataTables_paginate {display: block;} </style>
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Run Sheet</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Shipments > </li>
                <li class="text-muted">Run Sheet</li>
            </ol>
        </div>

    </div>
</div>
</div>
<div class="row">
<div class="col-12">
    <div class="card" style="overflow-x: auto;">
        <div class="card-body">
            <div id="printdiv">
                <div class="row">
                    <div class="col-6">
                        <img style="margin-left: 20px;" width="20%;" src="{{ asset('/public/users/img_1.png') }}">
                    </div>
                    <div class="col-6">
                        <br><h6>{{date('d/m/y h:i:s A')}}<br>Name: {{$driver->name}}<br>
                        </h6><br>
                    </div>
                </div><br>
                
            <table class="table table-bordered" border="1">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>AWB</th>
                        <th>Customer Name & Address </th>
                        <th>Phone #</th>
                        <th>Item Description</th>
                        <th>No of Pieces</th>
                        <th>NDR Reason</th>
                        <th>Movement Type</th>
                        <th>Amount</th>
                        <th>Sign</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=0; @endphp
                    @foreach ($deliverysheets as $log)
                    @php 
                        $x=$x+1; 
                        $generator = new BarcodeGeneratorPNG();
                        $barcodeBase64 = base64_encode($generator->getBarcode($log->shipment->barcode,$generator::TYPE_CODE_128,3,50));
                    @endphp
                        @if($log->shipment_type=="Card")
                            <tr>
                                <td>{{$x}}</td>
                                <td><center>      
                                        <img src="data:image/png;base64,{{ $barcodeBase64 }}" alt="barcode" style="height:32px;"><br>
                                        {{ $log->shipment->barcode ?? '' }}
                                    </center>
                                </td>
                                <td>{{ $log->shipment->consignee_name ?? ''}}<br>
                                    @if($log->shipment->appointment->address_type!=2)
                                    <span style="font-size: 10px;">{{$log->shipment->appointment->useraddress->area->name ?? ''}}, Block {{$log->shipment->appointment->useraddress->block ?? ''}}, Street - {{$log->shipment->appointment->useraddress->street ?? ''}} <br>
                                    Building No-{{$log->shipment->appointment->useraddress->flat_no ?? ''}}, Floor{{$log->shipment->appointment->useraddress->floor_no ?? ''}} <br>
                                    Flat/House:{{$log->shipment->appointment->useraddress->house_no ?? ''}} | Landmark:{{$log->shipment->appointment->useraddress->landmark ?? ''}} <br></span>
                                    @else
                                    <span style="font-size: 10px;">{{$log->shipment->appointment->branch->name ?? ''}}</span>
                                    @endif
                                </td>
                                <td>{{ $log->shipment->consignee_phone ?? ''}}</td>
                                <td>{{$log->shipment_type}}</td>
                                <td>1</td>
                                <td></td>
                                <td>forward</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
           </div>
           <br><br>
           <h6 style="margin-left: 20px;"> Total No. of Shipments : {{$x}} <br><br>
                Customer Name and Signature : <br><br>
                Rider Name and Signature :<br><br>
            </h6>
        </div>
    </div>
</div>
</div>
@stop
@section('ScriptPage')
<script src="{{asset('/assets')}}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/jszip/jszip.min.js"></script>
<script src="{{asset('/assets')}}/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="{{asset('/assets')}}/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script>
printPage();
    function printPage() {
        var tableDiv = document.getElementById("printdiv").innerHTML;

             printContents = '';
             printContents += tableDiv;
             var originalContents = document.body.innerHTML;
             document.body.innerHTML = printContents;
             window.print();
             document.body.innerHTML = originalContents;
}
</script>
@stop



