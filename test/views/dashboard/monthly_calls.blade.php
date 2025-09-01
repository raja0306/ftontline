@if($type==1)
<div id="monthly-column-chart" class="apex-charts" dir="ltr"></div>
<script type="text/javascript">
var options = {
chart: {
  height: 360,
  type: "bar",
  stacked: !0,
  toolbar: {
    show: !1
  },
  zoom: {
    enabled: !0
  }
},
plotOptions: {
  bar: {
    horizontal: !1,
    columnWidth: "15%",
    endingShape: "rounded"
  }
},
dataLabels: {
  enabled: !1
},
series: [{
  name: "Inbound Calls",
  data: [<?php echo rtrim($mnicalls, ", "); ?>]
}, {
  name: "Missed Calls",
  data: [<?php echo rtrim($mnmcalls, ", "); ?>]
}],
xaxis: {
  categories: [<?php echo rtrim($dnames, ", "); ?>]
},
colors: ["#70ad46","#ed7d31"],
legend: {
  position: "bottom"
},
fill: {
  opacity: 1
}
},
chart = new ApexCharts(document.querySelector("#monthly-column-chart"), options);
chart.render();
</script>
@else
<div id="monthly-column-outbound" class="apex-charts" dir="ltr"></div>
<script type="text/javascript">
var options = {
    chart: {
      height: 360,
      type: "bar",
      stacked: !0,
      toolbar: {
        show: !1
      },
      zoom: {
        enabled: !0
      }
    },
    plotOptions: {
      bar: {
        horizontal: !1,
        columnWidth: "15%",
        endingShape: "rounded"
      }
    },
    dataLabels: {
      enabled: !1
    },
    series: [{
      name: "Outbound Calls",
      data: [<?php echo rtrim($mnocalls, ", "); ?>]
    }, {
      name: "Outbound Conected Calls",
      data: [<?php echo rtrim($mnoccalls, ", "); ?>]
    }],
    xaxis: {
      categories: [<?php echo rtrim($dnames, ", "); ?>]
    },
    colors: ["#70ad46","#ed7d31"],
    legend: {
      position: "bottom"
    },
    fill: {
      opacity: 1
    }
  },
  chart = new ApexCharts(document.querySelector("#monthly-column-outbound"), options);
chart.render();
</script>
@endif


