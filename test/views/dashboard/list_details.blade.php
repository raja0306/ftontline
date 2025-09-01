<div class="row">
@foreach ($list_idss as $lids)
<div class="col-lg-6">
<div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4"><a href="{{url('/calls')}}?fromdate={{$fromdate}}&todate={{$todate}}&call_type=out&list_id={{$lids->list_id}}" class="text-dark"> {{$lids->list_name}}</a></h4>
      <div id="listchart{{$lids->list_id}}"></div>
    </div>
</div>
</div>
@php
  $mstatusothers = 'answer,B,NA,DROP';
    $dial_lists_new = App\VicidialList::where('list_id',$lids->list_id)->where('status','NEW')->count();
    $dial_lists_ans = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->whereIn('status',['answer','DCMX'])->count();
    $dial_lists_b = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->whereIn('status',['B','AB'])->count();
    $dial_lists_na = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->where('status','NA')->count();
    $dial_lists_drop = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->where('status','DROP')->count();
    $dial_lists_others = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('list_id',$lids->list_id)->whereNotIn('status',explode(",", $mstatusothers))->count();
    $dial_lists_total = $dial_lists_others+$dial_lists_ans+$dial_lists_b+$dial_lists_na+$dial_lists_drop;
@endphp
<script type="text/javascript">
var options = {
          series: [{
          name: 'Total Outgoing',
          data: [{{$dial_lists_total}}]
        },{
          name: 'Dialable',
          data: [{{$dial_lists_new}}]
        }, {
          name: 'Answer',
          data: [{{$dial_lists_ans}}]
        }, {
          name: 'No Answer',
          data: [{{$dial_lists_na}}]
        }, {
          name: 'Rejected',
          data: [{{$dial_lists_b}}]
        }, {
          name: 'Drop',
          data: [{{$dial_lists_drop}}]
        }],
          chart: {
          type: 'bar',
          height: 430
        },
        plotOptions: {
          bar: {
            horizontal: false,
            dataLabels: {
              position: 'top',
            },
          }
        },
        colors: ["#4473c5","#ed7d31","#70ad46","#cddc39","#ffc000","#ef5734","#333f87"],
        dataLabels: {
          enabled: true,
          offsetX: 0,
          style: {
            fontSize: '12px',
            colors: ['#fff']
          }
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['#fff']
        },
        xaxis: {
          categories: ["{{$lids->list_name}}"],
        },
        fill: {
          opacity: 1
        },
        legend: {
          position: 'right',
          offsetX: 0,
          offsetY: 50
        },
        };
        var chart = new ApexCharts(document.querySelector("#listchart{{$lids->list_id}}"), options);
        chart.render();
</script>
@endforeach
</div>