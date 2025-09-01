<div class="row">
    <div class="col-md-4 mb-2">
        <div>
            <label>Select Area:</label><br>
            <select class="form-control new_cus select2" name="area_id" id="area_id" style="width: 100%" required>
                <option value="">Choose...</option>
                @foreach($areas as $area)
                	<option value="{{$area->id}}">{{$area->name}}</option>
            	@endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <label>Block:</label>
        <input type="text" class="form-control" placeholder="Enter Block" name="block" id="block">
    </div>
    <div class="col-md-4 mb-2">
        <label>Street:</label>
        <input type="text" class="form-control" placeholder="Enter Street" name="street" id="street">
    </div>
    <div class="col-md-4 mb-2">
        <label>Avenue:</label>
        <input type="text" class="form-control" placeholder="Enter Avenue" name="avenue" id="avenue">
    </div>
    <div class="col-md-4 mb-2">
        <label>House #:</label>
        <input type="text" class="form-control" placeholder="Enter House" name="house_no" id="house_no">
    </div>
    <div class="col-md-4 mb-2">
        <label>Floor #:</label>
        <input type="text" class="form-control" placeholder="Enter Floor" name="floor_no" id="floor_no">
    </div>
    <div class="col-md-4 mb-2">
        <label>Flat #:</label>
        <input type="text" class="form-control" placeholder="Enter Flat" name="flat_no" id="flat_no">
    </div>
    <div class="col-md-4 mb-2">
        <label>Paci #:</label>
        <input type="text" class="form-control" placeholder="Enter Paci" name="pacii_no" id="pacii_no">
    </div>
    <div class="col-md-4 mb-2">
        <label>Landmark:</label>
        <input type="text" class="form-control" placeholder="Enter Landmark" name="landmark" id="landmark">
    </div>
    <div class="col-md-4 mb-2">
        <label>Latitude #:</label>
        <input type="text" class="form-control" placeholder="Enter Latitude" name="lat" id="lat">
    </div>
    <div class="col-md-4 mb-2">
        <label>Langitude #:</label>
        <input type="text" class="form-control" placeholder="Enter Langitude" name="longi" id="longi">
    </div>
</div>