<div class="row" style="margin-top:20px;">
  <div class="col-sm-6 col-lg-3">
    <div class="card card-inverse card-primary">
      <div class="card-block pb-0">
        <div class="btn-group float-right">
          <button type="button" class="btn btn-transparent active dropdown-toggle p-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-settings"></i> </button>
          
        </div>
        <h4 class="mb-0">
          <?php $emp = new Employee(0); echo $emp->Count();?>
        </h4>
        <p>Application Users</p>
      </div>
      <div class="chart-wrapper px-1" style="height:70px;">
        <canvas id="application_user_created" class="chart" height="70"></canvas>
      </div>
    </div>
  </div>
  <!--/.col-->
  
  <div class="col-sm-6 col-lg-3">
    <div class="card card-inverse card-info">
      <div class="card-block pb-0">
        <button type="button" class="btn btn-transparent active p-0 float-right"> <i class="icon-location-pin"></i> </button>
        <h4 class="mb-0">
          <?php $cmp = new Complaint(0); echo $cmp->Count('completed');?>
          / <?php echo $cmp->Count();?></h4>
        <p>Complaint Completed</p>
      </div>
      <div class="chart-wrapper px-1" style="height:70px;">
        <canvas id="total_completed_complaint_chart" class="chart" height="70"></canvas>
      </div>
    </div>
  </div>
  <!--/.col-->
  
  <div class="col-sm-6 col-lg-3">
    <div class="card card-inverse card-warning">
      <div class="card-block pb-0">        
        <h4 class="mb-0"><?=$emp->totalLive()?></h4>
        <p>Members online</p>
      </div>
      <div class="chart-wrapper" style="height:70px;">
        <canvas id="card-chart3" class="chart" height="70"></canvas>
      </div>
    </div>
  </div>
  <!--/.col-->
  
  <div class="col-sm-6 col-lg-3">
    <div class="card card-inverse card-danger">
      <div class="card-block pb-0">
        <div class="btn-group float-right">
          <button type="button" class="btn btn-transparent active dropdown-toggle p-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-settings"></i> </button>
          
        </div>
        <h4 class="mb-0">Till <?=date('dM-y')?></h4>
        <p>User Daily Login</p>
      </div>
      <div class="chart-wrapper px-1" style="height:70px;">
        <canvas id="user-perday-login-chart" class="chart" height="70"></canvas>
      </div>
    </div>
  </div>
  <!--/.col--> 
</div>
<div class="card">
  <div class="card-block">
    <div class="row">
      <div class="col-sm-5">
        <h4 class="card-title mb-0">Complaint Status</h4>
        <div class="small text-muted">Last 30 days from Today</div>
      </div>
    </div>
    <!--/.row-->
    <div class="chart-wrapper" style="height:300px;margin-top:40px;">
      <canvas id="complaint_status_last_30_days" class="chart" height="300"></canvas>
    </div>
  </div>
  <?php $mainChart = $cmp->ComplaintDueDate();?>
  <div class="card-footer">
    <ul>
      <li>
        <div class="text-muted">Total Complaint</div>
        <strong>
        <?=array_sum(explode(",",$mainChart["total"]))?>
        Complaint</strong>
        <div class="progress progress-xs mt-h">
          <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="<?= array_sum(explode(",",$mainChart["total"]))?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
      
      <!--<li>
              <div class="text-muted">Total Complaint</div>
              <strong>78.706 Views (60%)</strong>
              <div class="progress progress-xs mt-h">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </li>-->
      <li class="hidden-sm-down">
        <div class="text-muted">Completed</div>
        <strong>
        <?= array_sum(explode(",",$mainChart["completed"]))?>
        Complaint (
        <?= round(((array_sum(explode(",",$mainChart["completed"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>
        %)</strong>
        <div class="progress progress-xs mt-h">
          <div class="progress-bar bg-success" role="progressbar" style="width: <?= round(((array_sum(explode(",",$mainChart["completed"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>%" aria-valuenow="<?= array_sum(explode(",",$mainChart["completed"]))?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
      <li class="hidden-sm-down">
        <div class="text-muted">To Be Repaired</div>
        <strong>
        <?= array_sum(explode(",",$mainChart["toberepaired"]))?>
        Complaint (
        <?= round(((array_sum(explode(",",$mainChart["toberepaired"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>
        %)</strong>
        <div class="progress progress-xs mt-h">
          <div class="progress-bar bg-danger" role="progressbar" style="width: <?= round(((array_sum(explode(",",$mainChart["toberepaired"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>%" aria-valuenow="<?= array_sum(explode(",",$mainChart["toberepaired"]))?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
      <li class="hidden-sm-down" style="">
        <div class="text-muted">WR. Mgr Approval</div>
        <strong>
        <?= array_sum(explode(",",$mainChart["wrapproval"]))?>
        Complaint (
        <?= round(((array_sum(explode(",",$mainChart["wrapproval"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>
        %)</strong>
        <div class="progress progress-xs mt-h">
          <div class="progress-bar" role="progressbar" style="width: <?= round(((array_sum(explode(",",$mainChart["wrapproval"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>%" aria-valuenow="<?= array_sum(explode(",",$mainChart["wrapproval"]))?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
      <li class="hidden-sm-down">
        <div class="text-muted">Others</div>
        <strong>
        <?= array_sum(explode(",",$mainChart["other"]))?>
        Complaint (
        <?= round(((array_sum(explode(",",$mainChart["other"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>
        %)</strong>
        <div class="progress progress-xs mt-h">
          <div class="progress-bar" role="progressbar" style="width: <?= round(((array_sum(explode(",",$mainChart["other"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>%" aria-valuenow="<?= array_sum(explode(",",$mainChart[" other "]))?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
    </ul>
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-6">
    <div class="card">
      <div class="card-header"> Complaint Origin Store Chart
        <div class="card-actions"> <a href="javascript:void(0)"> <small class="text-muted">Refresh</small> </a> </div>
      </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="complaint_origin_store_chart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-12 col-lg-6">
  	<div class="card">    
      <div class="card-header"> Total Complaint Problems
        <div class="card-actions"> <a href="javascript:void(0)"> <small class="text-muted">Refresh</small> </a> </div>
      </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="total_complaints_problem_chart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
//Cards with Charts
<?php  $Data = $emp->EmployeeAddedchart(); ?>
  var labels = [<?=$Data['labels']?>];
  var data = {
    labels: labels,
    datasets: [
      {
        label: 'Employee Registered',
        backgroundColor: $.brandPrimary,
        borderColor: 'rgba(255,255,255,.55)',
        data: [<?=$Data['values']?>]
      },
    ]
  };
  var options = {
	responsive: true,
    maintainAspectRatio: false,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        gridLines: {
          color: 'transparent',
          zeroLineColor: 'transparent'
        },
        ticks: {
          fontSize: 2,
          fontColor: 'transparent',
        }

      }],
      yAxes: [{
        display: false,
        ticks: {
          display: false,
          min: Math.min.apply(Math, data.datasets[0].data) - 5,
          max: Math.max.apply(Math, data.datasets[0].data) + 5,
        }
      }],
    },
    elements: {
      line: {
        borderWidth: 1
      },
      point: {
        radius: 4,
        hitRadius: 10,
        hoverRadius: 4,
      },
    }
  };
  var ctx = $('#application_user_created');
  var cardChart1 = new Chart(ctx, {
    type: 'line',
    data: data,
    options: options
  });
</script> 
<script type="text/javascript">
<?php  $Data = $cmp->ComplaintAddedchart(); ?>
var labels2 = [<?=$Data['labels']?>];
var data = {
    labels: labels2,
    datasets: [
      {
        label: 'Complaint Collected',
        backgroundColor: $.brandInfo,
        borderColor: 'rgba(255,255,255,.55)',
        data: [<?=$Data['values']?>]
      },
    ]
  };
  var options = {
	responsive: true,
    maintainAspectRatio: false,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        gridLines: {
          color: 'transparent',
          zeroLineColor: 'transparent'
        },
        ticks: {
          fontSize: 2,
          fontColor: 'transparent',
        }

      }],
      yAxes: [{
        display: false,
        ticks: {
          display: false,
          min: Math.min.apply(Math, data.datasets[0].data) - 5,
          max: Math.max.apply(Math, data.datasets[0].data) + 5,
        }
      }],
    },
    elements: {
      line: {
        tension: 0.00001,
        borderWidth: 1
      },
      point: {
        radius: 4,
        hitRadius: 10,
        hoverRadius: 4,
      },
    }
  };
  var ctx = $('#total_completed_complaint_chart');
  var cardChart2 = new Chart(ctx, {
    type: 'line',
    data: data,
    options: options
  });
</script> 
<script type="text/javascript">
 var options = {
	responsive: true,
    maintainAspectRatio: false,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        display: false
      }],
      yAxes: [{
        display: false
      }],
    },
    elements: {
      line: {
        borderWidth: 2
      },
      point: {
        radius: 0,
        hitRadius: 10,
        hoverRadius: 4,
      },
    }
  };
  var data = {
    labels: labels,
    datasets: [
      {
        label: 'Request By Hardware',
        backgroundColor: 'rgba(255,255,255,.2)',
        borderColor: 'rgba(255,255,255,.55)',
        data: [78, 81, 80, 45, 34, 12, 40]
      },
    ]
  };
  var ctx = $('#card-chart3');
  var cardChart3 = new Chart(ctx, {
    type: 'line',
    data: data,
    options: options
  });
</script> 
<script type="application/javascript">
//Random Numbers
  function random(min,max) {
    return Math.floor(Math.random()*(max-min+1)+min);
  }
  <?php  $Data = $app->logLoginChart(); ?>
  var elements = <?=$Data['count']?>;
  var labels = [<?=$Data['labels']?>];
  var data = [<?=$Data['values']?>];

  var options = {
	responsive: true,
    maintainAspectRatio: false,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        display: false,
        barPercentage: 0.6,
      }],
      yAxes: [{
        display: false,
      }]
    },

  };
  var data = {
    labels: labels,
    datasets: [
      {
        backgroundColor: 'rgba(255,255,255,.3)',
        borderColor: 'transparent',
        data: data
      },
    ]
  };
  var ctx = $('#user-perday-login-chart');
  var cardChart4 = new Chart(ctx, {
    type: 'bar',
    data: data,
    options: options
  });
  
  
  
  //Main Chart
  
  var elements = 27;
  var data1 = [<?=$mainChart["total"]?>];
  var data2 = [<?=$mainChart["completed"]?>];
  var data3 = [<?=$mainChart["toberepaired"]?>];
  var data4 = [<?=$mainChart["wrapproval"]?>];
  var data5 = [<?=$mainChart["other"]?>];

  
  var data = {
    labels: [<?=$mainChart["perday"]?>],
    datasets: [
      {
        label: 'Complaint Collected',
        backgroundColor: convertHex($.brandInfo,10),
        borderColor: $.brandInfo,
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        data: data1
      },
      {
        label: 'Complaint Completed',
        backgroundColor: 'transparent',
        borderColor: $.brandSuccess,
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        data: data2
      },
      {
        label: 'To be Repaired',
        backgroundColor: 'transparent',
        borderColor: $.brandDanger,
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        borderDash: [8, 5],
        data: data3
      },
      {
        label: 'Approval to WH Mgr',
        backgroundColor: 'transparent',
        borderColor: '#909',
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        borderDash: [8, 5],
        data: data4
      },
      {
        label: 'Other',
        backgroundColor: 'transparent',
        borderColor: '#0FF',
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        borderDash: [8, 5],
        data: data5
      }
    ]
  };

  var options = {
	responsive: true,
    maintainAspectRatio: false,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        gridLines: {
          drawOnChartArea: false,
        }
      }],
      yAxes: [{
        ticks: {
          beginAtZero: true,
          maxTicksLimit: 5,
          stepSize: Math.ceil(<?= max(explode(" , ",$mainChart["total"]))?> / 5),
          max: <?= max(explode(" , ",$mainChart["total"]))?>
        }
      }]
    },
    elements: {
      point: {
        radius: 0,
        hitRadius: 10,
        hoverRadius: 4,
        hoverBorderWidth: 3,
      }
    },
  };
  var ctx = $('#complaint_status_last_30_days');
  var mainChart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: options
  });
  
  
  <?php  $Data = $cmp->ComplaintOriginchart(); ?>
  var doughnutData = {
        labels: [<?=$Data['labels']?>],
        datasets: [{
            data: [<?=$Data['values']?>],
            backgroundColor: getColor(<?=$Data['count']?>),
            hoverBackgroundColor: getColor(<?=$Data['count']?>)
        }]
    };
    var ctx = document.getElementById('complaint_origin_store_chart');
    var chart = new Chart(ctx, {
        type: 'pie',
        data: doughnutData,
        options: {
            responsive: true
        }
    });
	
<?php  $Data = $cmp->complaintRecordProblemCountChart(); ?>
	
	var pieData = {
        labels: [<?=$Data['labels']?>],
        datasets: [{
            data: [<?=$Data['values']?>],
            backgroundColor: getColor(<?=$Data['count']?>),
            hoverBackgroundColor: getColor(<?=$Data['count']?>)
        }]
    };
    var ctx = document.getElementById('total_complaints_problem_chart');
    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: pieData,
        options: {
            responsive: true
        }
    });
</script>