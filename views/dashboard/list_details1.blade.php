
<div class="row">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{asset('/assets')}}/libs/jquery-knob/jquery.knob.min.js"></script> 
    <script src="{{asset('/assets')}}/js/pages/jquery-knob.init.js"></script> 
@foreach ($list_idss as $lids)
<div class="col-lg-6" id="listchartdiv{{$lids->list_id}}">
<div class="card">
    <div class="card-body">

      <h4 class="card-title mb-4"><span class="listname_{{$lids->list_id}}"></span><span class="float-end"><!-- <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm" onclick="getexportlists('{{$lids->list_id}}');">Export</a> --></span></h4>
      <div class="row">
        <div class="col-xl-4">
          <div class="text-center" dir="ltr">
              <h5 class="font-size-14 mb-3">Total</h5>
              <input class="knob dial_{{$lids->list_id}}" data-width="80" data-min="0" data-displayprevious="true" data-max=2000" data-step="1" value="200" data-fgcolor="#2a3142" id="total{{$lids->list_id}}" readonly="">
          </div>
        </div>

        <div class="col-xl-4">
          <div class="text-center" dir="ltr">
              <h5 class="font-size-14 mb-3">Dialable</h5>
              <input class="knob dial_{{$lids->list_id}}" data-width="80" data-min="0" data-displayprevious="true" data-max=2000" data-step="1" value="200" data-fgcolor="#50a5f1" id="dialnew{{$lids->list_id}}" readonly="">
          </div>
        </div>

        <div class="col-xl-4">
          <div class="text-center" dir="ltr">
              <h5 class="font-size-14 mb-3">Answer</h5>
              <input class="knob dial_{{$lids->list_id}}" data-width="80" data-min="0" data-displayprevious="true" data-max=2000" data-step="1" value="200" data-fgcolor="#34c38f" id="answer{{$lids->list_id}}" readonly="">
          </div>
        </div>

        <div class="col-xl-4">
          <div class="text-center" dir="ltr">
              <h5 class="font-size-14 mb-3">No Answer</h5>
              <input class="knob dial_{{$lids->list_id}}" data-width="80" data-min="0" data-displayprevious="true" data-max=2000" data-step="1" value="200" data-fgcolor="#f1b44c" id="noanswer{{$lids->list_id}}" readonly="">
          </div>
        </div>

        <div class="col-xl-4">
          <div class="text-center" dir="ltr">
              <h5 class="font-size-14 mb-3">Busy</h5>
              <input class="knob dial_{{$lids->list_id}}" data-width="80" data-min="0" data-displayprevious="true" data-max=2000" data-step="1" value="200" data-fgcolor="#6f42c1" id="busy{{$lids->list_id}}" readonly="">
          </div>
        </div>

        <div class="col-xl-4">
          <div class="text-center" dir="ltr">
              <h5 class="font-size-14 mb-3">Drop</h5>
              <input class="knob dial_{{$lids->list_id}}" data-width="80" data-min="0" data-displayprevious="true" data-max=2000" data-step="1" value="200" data-fgcolor="#f46a6a" id="drop{{$lids->list_id}}" readonly="">
          </div>
        </div>
      </div>
    </div>
</div>

</div>
@php
$listnames = App\VicidialLists::select('list_name')->where('list_id',$lids->list_id)->first();
    $listname =  '';
    if($listnames){
       $listname =  $listnames->list_name;
    }

    $dial_lists_new = App\VicidialList::where('list_id',$lids->list_id)->where('status','NEW')->count();
    if($lids->list_id=='999'){
      $dial_lists_ans = App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->where('status','Answer')->count();
      $dial_lists_b = App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->whereIn('status',['B','AB'])->count();
      $dial_lists_na = App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->where('status','NA')->count();
      $dial_lists_drop = App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->where('status','DROP')->count();
    }
    else{
      $dial_lists_ans = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->where('status','Answer')->count();
      $dial_lists_b = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->whereIn('status',['B','AB'])->count();
      $dial_lists_na = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->where('status','NA')->count();
      $dial_lists_drop = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->where('status','DROP')->count();
    }
    

  $dial_lists_total = $dial_lists_new+$dial_lists_ans+$dial_lists_b+$dial_lists_na+$dial_lists_drop;
  $chartid = $lids->list_id;
@endphp

<!-- jQuery (required before your custom script) -->
    
    <!-- Your custom script -->
    <script type="text/javascript">
        $(document).ready(function () {

            dial_lists_total=1000;
            $(".listname_<?php echo $chartid; ?>").html("{{$listname}}");

            $('.dial_<?php echo $chartid; ?>').trigger('configure', {
                max: {{$dial_lists_total}}
            });

            $('#total<?php echo $chartid; ?>').val({{$dial_lists_total}}).trigger('change');
            $('#busy<?php echo $chartid; ?>').val({{$dial_lists_b}}).trigger('change');
            $('#noanswer<?php echo $chartid; ?>').val({{$dial_lists_na}}).trigger('change');
            $('#answer<?php echo $chartid; ?>').val({{$dial_lists_ans}}).trigger('change');
            $('#dialnew<?php echo $chartid; ?>').val({{$dial_lists_new}}).trigger('change');
            $('#drop<?php echo $chartid; ?>').val({{$dial_lists_drop}}).trigger('change');
        }); 
    </script>
@endforeach

</div>
