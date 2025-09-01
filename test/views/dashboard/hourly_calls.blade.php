<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-ohriin_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseohriin_summary" aria-expanded="true" aria-controls="flush-collapseohriin_summary">
    Inbound Calls
    </button>
</h2>
<div id="flush-collapseohriin_summary" class="accordion-collapse collapse show" aria-labelledby="flush-ohriin_summary">
    <div class="card-body">
      <div class="w-50 float-end">
      <div class="ms-auto">
          <ul class="nav nav-pills nav-justified" role="tablist">
              <li class="nav-item waves-effect waves-light">
                  <a class="nav-link active" data-bs-toggle="tab" href="#hour-1" role="tab">
                      <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                      <span class="d-none d-sm-block">Hour</span> 
                  </a>
              </li>
              <li class="nav-item waves-effect waves-light">
                  <a class="nav-link" data-bs-toggle="tab" href="#week-1" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                      <span class="d-none d-sm-block">Week</span> 
                  </a>
              </li>
              <li class="nav-item waves-effect waves-light">
                  <a class="nav-link" data-bs-toggle="tab" href="#month-1" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                      <span class="d-none d-sm-block">Month</span>   
                  </a>
              </li>
              <li class="nav-item waves-effect waves-light">
                  <a class="nav-link" data-bs-toggle="tab" href="#year-1" role="tab">
                      <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                      <span class="d-none d-sm-block">Year</span>    
                  </a>
              </li>
          </ul>
      </div>
      </div>
      <div class="tab-content mt-5 p-3 text-muted">
        <div class="tab-pane active" id="hour-1" role="tabpanel">
          <div id="inbound_hourlychart"></div>
        </div>
        <div class="tab-pane" id="year-1" role="tabpanel">
          <div id="yearly-column-chart" class="apex-charts" dir="ltr"></div>
        </div>
        <div class="tab-pane" id="month-1" role="tabpanel">
		<div class="w-50 text-center">
              <div class="input-group input-group-sm">
                  <select class="form-select form-select-sm" id="monthhourly-1">
                      <option value="{{date('m')}}" selected>Dec</option>
                      <option value="{{date('11')}}">Nov</option>
                      <option value="{{date('10')}}">Oct</option>
                      <option value="{{date('09')}}">Sep</option>
                  </select>
                  <a class="input-group-text" onclick="monthhourly(1);">Month</a>
              </div>
          </div>
          <div class="month-1">
          <div id="monthly-column-chart" class="apex-charts" dir="ltr"></div></div>
        </div>
        <div class="tab-pane" id="week-1" role="tabpanel">
          <div id="weekly-column-chart" class="apex-charts" dir="ltr"></div>
        </div>
      </div>
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
    Outbound Calls
    </button>
</h2>
<div id="flush-collapsehrout_summary" class="accordion-collapse collapse show" aria-labelledby="flush-hrout_summary">
    <div class="card-body">
      <div class="w-50 float-end">
      <div class="ms-auto">
          <ul class="nav nav-pills nav-justified" role="tablist">
              <li class="nav-item waves-effect waves-light">
                  <a class="nav-link active" data-bs-toggle="tab" href="#hour-2" role="tab">
                      <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                      <span class="d-none d-sm-block">Hour</span> 
                  </a>
              </li>
              <li class="nav-item waves-effect waves-light">
                  <a class="nav-link" data-bs-toggle="tab" href="#week-2" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                      <span class="d-none d-sm-block">Week</span> 
                  </a>
              </li>
              <li class="nav-item waves-effect waves-light">
                  <a class="nav-link" data-bs-toggle="tab" href="#month-2" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                      <span class="d-none d-sm-block">Month</span>   
                  </a>
              </li>
              <li class="nav-item waves-effect waves-light">
                  <a class="nav-link" data-bs-toggle="tab" href="#year-2" role="tab">
                      <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                      <span class="d-none d-sm-block">Year</span>    
                  </a>
              </li>
          </ul>
      </div>
      </div>
      <div class="tab-content mt-5 p-3 text-muted">
        <div class="tab-pane active" id="hour-2" role="tabpanel">
          <div id="outbound_hourlychart"></div>
        </div>
        <div class="tab-pane" id="year-2" role="tabpanel">
          <div id="yearly-column-outbound" class="apex-charts" dir="ltr"></div>
        </div>
        <div class="tab-pane" id="month-2" role="tabpanel">
          <div class="w-50 text-center">
              <div class="input-group input-group-sm">
                  <select class="form-select form-select-sm" id="monthhourly-2">
                      <option value="{{date('m')}}" selected>Dec</option>
                      <option value="{{date('11')}}">Nov</option>
                      <option value="{{date('10')}}">Oct</option>
                      <option value="{{date('09')}}">Sep</option>
                  </select>
                  <a class="input-group-text" onclick="monthhourly(2);">Month</a>
              </div>
          </div>
          <div class="month-2">
          <div id="monthly-column-outbound" class="apex-charts" dir="ltr"></div></div>
        </div>
        <div class="tab-pane" id="week-2" role="tabpanel">
          <div id="weekly-column-outbound" class="apex-charts" dir="ltr"></div>
        </div>
      </div>
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
      data: [<?php echo rtrim($yricalls, ", "); ?>]
    }, {
      name: "Missed Calls",
      data: [<?php echo rtrim($yrmcalls, ", "); ?>]
    }],
    xaxis: {
      categories: [<?php echo rtrim($mnames, ", "); ?>]
    },
    colors: ["#70ad46","#ed7d31"],
    legend: {
      position: "bottom"
    },
    fill: {
      opacity: 1
    }
  },
  chart = new ApexCharts(document.querySelector("#yearly-column-chart"), options);
chart.render();

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
      data: [<?php echo rtrim($wkicalls, ", "); ?>]
    }, {
      name: "Missed Calls",
      data: [<?php echo rtrim($wkmcalls, ", "); ?>]
    }],
    xaxis: {
      categories: [<?php echo rtrim($wnames, ", "); ?>]
    },
    colors: ["#70ad46","#ed7d31"],
    legend: {
      position: "bottom"
    },
    fill: {
      opacity: 1
    }
  },
  chart = new ApexCharts(document.querySelector("#weekly-column-chart"), options);
chart.render();

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
      data: [<?php echo rtrim($yrocalls, ", "); ?>]
    }, {
      name: "Outbound Conected Calls",
      data: [<?php echo rtrim($yroccalls, ", "); ?>]
    }],
    xaxis: {
      categories: [<?php echo rtrim($mnames, ", "); ?>]
    },
    colors: ["#70ad46","#ed7d31"],
    legend: {
      position: "bottom"
    },
    fill: {
      opacity: 1
    }
  },
  chart = new ApexCharts(document.querySelector("#yearly-column-outbound"), options);
chart.render();

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
      data: [<?php echo rtrim($wkocalls, ", "); ?>]
    }, {
      name: "Outbound Conected Calls",
      data: [<?php echo rtrim($wkoccalls, ", "); ?>]
    }],
    xaxis: {
      categories: [<?php echo rtrim($wnames, ", "); ?>]
    },
    colors: ["#70ad46","#ed7d31"],
    legend: {
      position: "bottom"
    },
    fill: {
      opacity: 1
    }
  },
  chart = new ApexCharts(document.querySelector("#weekly-column-outbound"), options);
chart.render();

function monthhourly(rno) {
  var monthhourlyurl = "{{url('/monthly_calls')}}?month="+$("#monthhourly-"+rno).val()+"&type="+rno;
  $.getJSON(monthhourlyurl, function(data) {
      console.log(data.Mhtml);
      $(".month-"+rno).html(data.Mhtml);
  });
}
</script>

