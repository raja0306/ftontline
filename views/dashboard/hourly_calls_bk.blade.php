<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-ohriin_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseohriin_summary" aria-expanded="true" aria-controls="flush-collapseohriin_summary">
    Inbound Calls Per Hours For Today
    </button>
</h2>
<div id="flush-collapseohriin_summary" class="accordion-collapse collapse show" aria-labelledby="flush-ohriin_summary">
    <div class="card-body">
        <div id="inbound_hourlychart"></div>
    </div>
</div>
</div>
</div>
</div>
<div class="col-lg-12">
<div class="card">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-hrout_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsehrout_summary" aria-expanded="true" aria-controls="flush-collapsehrout_summary">
    Outbound Calls Per Hours For Today
    </button>
</h2>
<div id="flush-collapsehrout_summary" class="accordion-collapse collapse show" aria-labelledby="flush-hrout_summary">
    <div class="card-body">
        <div id="outbound_hourlychart"></div>
    </div>
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
var inbound_hourly = {
  series: [{
  name: 'Inbound Calls',
  data: [<?php echo rtrim($hricalls, ", "); ?>]
}, {
  name: 'Missed Calls',
  data: [<?php echo rtrim($hrmcalls, ", "); ?>]
}],
  chart: {
  type: 'bar',
  height: 350
},
colors: ["#70ad46","#ed7d31"],
plotOptions: {
  bar: {
    horizontal: false,
    columnWidth: '85%',
    endingShape: 'rounded'
  },
},
dataLabels: {
  enabled: true
},
stroke: {
  show: true,
  width: 2,
  colors: ['transparent']
},
xaxis: {
  categories: 
["12AM","1AM","2AM","3AM","4AM","5AM","6AM","7AM","8AM","9AM","10AM","11AM","12PM","1PM","2PM","3PM","4PM","5PM","6PM","7PM","8PM","9PM","10PM","11PM","12PM"],
},
yaxis: {
  title: {
    text: 'No Of Calls'
  }
},
fill: {
  opacity: 1
},
tooltip: {
  y: {
    formatter: function (val) {
      return val + " Calls"
    }
  }
}
};
var chart = new ApexCharts(document.querySelector("#inbound_hourlychart"), inbound_hourly);
chart.render();


var outbound_hourly = {
  series: [{
  name: 'Outbound Calls',
  data: [<?php echo rtrim($hrocalls, ", "); ?>]
}, {
  name: 'Outbound Conected Calls',
  data: [<?php echo rtrim($hroccalls, ", "); ?>]
}],
  chart: {
  type: 'bar',
  height: 350
},
colors: ["#70ad46","#ed7d31"],
plotOptions: {
  bar: {
    horizontal: false,
    columnWidth: '85%',
    endingShape: 'rounded'
  },
},
dataLabels: {
  position: 'top',
  enabled: true
},
stroke: {
  show: true,
  width: 2,
  colors: ['transparent']
},
xaxis: {
  categories: ["12AM","1AM","2AM","3AM","4AM","5AM","6AM","7AM","8AM","9AM","10AM","11AM","12PM","1PM","2PM","3PM","4PM","5PM","6PM","7PM","8PM","9PM","10PM","11PM","12PM"],
},
yaxis: {
  title: {
    text: 'No Of Calls'
  }
},
fill: {
  opacity: 1
},
tooltip: {
  y: {
    formatter: function (val) {
      return val + " Calls"
    }
  }
}
};

var chart = new ApexCharts(document.querySelector("#outbound_hourlychart"), outbound_hourly);
chart.render();
</script>