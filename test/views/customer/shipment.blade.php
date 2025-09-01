<div class="row">
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Barcode:</label>
                <h5 class="font-size-12">{{$shipment->barcode}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Status:</label>
                <h5 class="font-size-12">{{$shipment->cstatus}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Consignee name:</label>
                <h5 class="font-size-12">{{$shipment->consignee_name}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Civil Id:</label>
                <h5 class="font-size-12">{{$shipment->customer_civil_Id}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Alternate phone:</label>
                <h5 class="font-size-12">{{$shipment->alternate_phone}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Card Type:</label>
                <h5 class="font-size-12">{{$shipment->description}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Commodity name:</label>
                <h5 class="font-size-12">{{$shipment->commodity_name}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Guardian Name:</label>
                <h5 class="font-size-12">{{$shipment->guardian_name}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Receiver Civil Id:</label>
                <h5 class="font-size-12">{{$shipment->receiver_civil_id}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Branch name:</label>
                <h5 class="font-size-12">{{$shipment->branch_name}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Pickup Date:</label>
                <h5 class="font-size-12">{{$shipment->pickup_date}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Reference:</label>
                <h5 class="font-size-12">{{$shipment->reference}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Tray No:</label>
                <h5 class="font-size-12">{{$shipment->tray_no}}</h5>
        </div>
        
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Menifest Number:</label>
                <h5 class="font-size-12">{{$shipment->manifest_number}}</h5>
        </div>
        <!-- <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Appointment Id:</label>
                <h5 class="font-size-12">{{$shipment->appointment_id}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Created at:</label>
                <h5 class="font-size-12">{{date("d M Y H:i A",strtotime($shipment->created_at))}}</h5>
        </div>
        <div class="col-md-6 mb-2">
                <label class="text-muted  font-size-11">Last Updated at:</label>
                <h5 class="font-size-12">{{date("d M Y H:i A",strtotime($shipment->updated_at))}}</h5>
        </div> -->
</div>