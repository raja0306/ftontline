<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-out_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseout_summary" aria-expanded="true" aria-controls="flush-collapseout_summary">
    Outbound Summary
    </button>
</h2>
<div id="flush-collapseout_summary" class="accordion-collapse collapse show" aria-labelledby="flush-out_summary">
    <div class="card-body">
        <div id="hchart"></div>
    </div>
</div>
</div>
</div>
</div>
</div>

<script>
var options = {
series: [{
data: [{{$outbound}},{{$attempts}},{{$outbound_avg}},{{$upload_lists}},{{$dial_dialable}},{{$outbound_connected}},{{$outbound_success}}]
}],
chart: {
type: 'bar',
height: 300
},
plotOptions: {
bar: {
  barHeight: '100%', distributed: true, horizontal: true,
  dataLabels: { position: 'bottom' },
}
},
colors: ["#4473c5","#a5a5a5","#ed7d31","#70ad46","#eb2226","#ffc000","#1fb5ad"],
dataLabels: {
enabled: true,
textAnchor: 'start',
style: { colors: ['#000'] },
formatter: function (val, opt) {
  return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
},
offsetX: 0,
dropShadow: { enabled: false }
},
stroke: {
width: 1,
colors: ['#fff']
},
xaxis: {
categories: ['TOTAL DIAL ATTEMPTS', 'UNIQUE ATTEMPTS', 'AVERAGE DIAL ATTEMPTS', 'TOTAL UPLOADED LIST', 'REMAINING DIALABLE', 'SUCCESSFUL CONNECTED CALLS', 'SUCCESS RATIO (%)'],
},
yaxis: {
labels: {
  show: false
}
},
title: {
  text: '',
  align: 'center',
  floating: false
},
subtitle: {
  text: '',
  align: 'center',
},
tooltip: {
theme: 'dark',
x: {
  show: false
},
y: {
  title: {
    formatter: function () {
      return ''
    }
  }
}
}
};
var chart = new ApexCharts(document.querySelector("#hchart"), options);
chart.render();
</script>