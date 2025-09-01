<label>Select Slot:</label>
@foreach($slots as $slot)
@php $slotqty = App\Slot::Availablity($slot->id,$slot->quantity,$date);  @endphp
@if($slotqty>0)
<div class="form-check mb-3">
    <input class="form-check-input" type="radio" name="slot_id"
        id="slot_id-{{$slot->id}}" value="{{$slot->id}}" required>
    <label class="form-check-label" for="slot_id-{{$slot->id}}">
        {{$slot->name}} <span class="badge rounded-pill bg-info ms-1">{{App\Slot::Availablity($slot->id,$slot->quantity,$date)}}</span>
    </label>
</div>
@endif
@endforeach