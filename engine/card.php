<div class="row">
  <style type="text/css">
.userimageblock{margin-top:8px; margin-bottom:20px; text-align:center; border-radius:2px; box-shadow: 1.294px 8px 25px 0 rgba(0,22,46,.1); padding: 17px 0px 12px;}
.usernameblock{ padding:3px 2px; overflow:hidden; font-weight:400;}
.userloginblock{ padding:2px 2px 2px;}
.usertimeblock{ padding:3px 2px 8px;}

.collectionblock{ border:1px solid #C8C8C8; margin-top:8px; margin-bottom:16px; text-align:center; border-radius:2px; box-shadow:0px 17px 20px -21px  #5b52ee; min-height:300px;}
.usercontainer{overflow-x:auto; min-width: 100%; min-height: 150px; margin-bottom:-20px;}
.usercontainer::-webkit-scrollbar {
  display: none;
}
.nav-tabs {
  overflow-x: auto;
  overflow-y: hidden;
  display: -webkit-box;
  display: -moz-box;
}
.award_badge{ 
background-image:url('<?php echo $app->basePath('img/award.png')?>');
background-repeat: no-repeat;
background-position: top 5px right 5px;
background-size: 40px 46px;}
.nav-tabs>li {
  float: none;
}
.user_live_burst{position: absolute;

top: -50px;

right: 20px;

margin: 60px auto;}
</style>
  <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12"> 
    <!--<div class="carouselExampleSlidesOnly carousel slide" data-ride="carousel">
      <div class="carousel-inner" style="display:flex;">-->
    <div class="container-fluid">
      <div class="row flex-row usercontainer">
        <?php 
	  if($totalLogedInUser = Activity::todayUserLogins(24))
	  {
		$month_work = array_column($totalLogedInUser, "month_work");
		$max_work_emp_pos = ($month_work && count($month_work)) ? max($month_work) : 0;
		$awrded_user = Activity::getMaxWorkedEmployee();
		foreach($totalLogedInUser  as $logedInuser)
		{
			?>
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 <?php echo $awrded_user == $logedInuser['log_user_id'] ? "award_badge" : "";?>" <?php echo $awrded_user == $logedInuser['log_user_id'] ? "title='Maximum hours work in last 30 days'" : "";?>>
          <div class="userimageblock">
            <div class="text-center"> <img class="img-circle" src="<?php echo $logedInuser['user_image']?>" height="90px" /> <span class="user_live_burst" id="user_live_burst_<?php echo $logedInuser['log_user_id'];?>"></span> </div>
            <div class="text-center usernameblock"> <?php echo limitText($logedInuser['user_name'],20)?> </div>
            <div class="text-center userloginblock text-muted"> Started: <?php echo $logedInuser['start_login']?> </div>
            <div class="text-center usertimeblock text-muted"> Total: <?php echo $logedInuser['duration']?> </div>
          </div>
        </div>
        <?php			
		}
	  }
	  
	  ?>
      </div>
    </div>
  </div>
  
  
  
  <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 my-3"> 
	  <div class="row">
	  <?php
	  $employee = new Employee();
	  $pendingWork = $employee->getTechnicianPendingWork();
	  if(count($pendingWork)){
		  foreach($pendingWork as $_work){
	  ?>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">			
			<div class="card">
				<div class="card-header"><img class="img-circle" alt="<?php echo $_work['user_fname']?>" src="<?php echo $_work['user_image'];?>" height="32px"> &nbsp; <?php echo $_work['user_fname'];?> <?php echo $_work['user_lname']?></div>
				<div class="card-body p-1">
					<ul class="list-group">
						<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Repair request
						<span class="badge badge-primary badge-pill"><?php echo $_work['complaint'];?></span>
						</li>
						<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Batch Product
						<span class="badge badge-primary badge-pill"><?php echo $_work['batch_product'];?></span>
						</li>
					</ul>
				</div>
			</div>			
		</div>
		<?php }
	  }?>
	  </div>
  </div>
  
  
  
  
  
  
  
  
  <?php include('card-location.php');?>
  <?php
  $collection = new Collection();
  $collecteionForToday = $collection->getTodayCollectionRoute(date("Y-m-d"));
  //$collecteionForToday = $collection->getTodayCollectionRoute("2018-11-06");
  if($collecteionForToday)
  { ?>
  <div class="col-lg-12 col-md-12 my-2">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Today's Collection (<?php echo count($collecteionForToday);?>)
        <div class="card-actions"> <a title="See Shortest Route" href="<?php echo $app->siteUrl("shortestroute");?>"><i class="fa fa-map    font-2xl d-block m-t-2"></i></a> </div>
      </div>
      <div class="block-fluid table-sorting clearfix">
        <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
          <div class="row">
            <table class="table mb-0">
              <tbody>
                <?php
            foreach($collecteionForToday  as $_collection)
            {
                ?>
                <tr>
                  <td class="hidden-xs"><img class="img-circle" alt="<?php echo $_collection['driver_name']?>" src="<?php echo $_collection['driver_image']?>" height="45px"/></td>
                  <td><?php echo $_collection['customer_name']?><br/>
                    <a class="text-muted" href="<?php echo $app->siteUrl("updatecollection/".$_collection['wc_id']);?>"><?php echo $_collection['wc_code']?></a></td>
                  <td><a class="btn btn-sm btn-success" href="tel:<?php echo beutifyMobileNumber($_collection['customer_phone'])?>"><i class="fa fa-phone-square fa-lg"></i> <?php echo beutifyMobileNumber($_collection['customer_phone'])?></a><br/>
                    <a class="btn btn-primary btn-sm" style="margin-top:2px;" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?=$_collection['wc_id']?>|W', '<?=$_collection['wc_code']?> Log Report')"><i class="fa fa-comments-o fa-lg m-t-2"></i> &nbsp; Log a message</a></td>
                  <td class="hidden-xs"><?php echo $_collection['customer_full_address']?></td>
                  <td><a class="btn btn-sm" style="background-color:<?=$_collection['wc_status_color_code']?>;"><?php echo $_collection['wc_status_name']?></a><br/>
                    <a class="btn btn-sm btn-success" style="margin-top:2px;" href="<?php echo $app->siteUrl("updatecollection/".$_collection['wc_id']);?>"><i class="fa fa-info-circle fa-lg m-t-2"></i> &nbsp; View</a></td>
                </tr>
                <?php			
            }          
          
          ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php 
  }?>
  
  
  <?php
  $complaint = new Complaint();
  $complaintToday = $complaint->getTodaysComplaint(date("Y-m-d"));
  if($complaintToday)
  { ?>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 my-2">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Today's Added Repair Request (<?php echo count($complaintToday);?>)       
      </div>
      <div class="block-fluid table-sorting clearfix">
        <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
          <div class="row">
            <table class="table mb-0">
              <tbody>
            <?php
            foreach($complaintToday  as $_complaint)
            {
                ?>
                <tr>
                  <td><img class="img-circle" alt="<?php echo $_complaint['technician_name']?>" src="<?php echo $_complaint['technician_image']?>" height="45px"/></td>
                  <td class="hidden-xs"><?php echo $_complaint['customer_name']?><br/>
                    <a class="text-muted" href="<?php echo $app->siteUrl("viewcomplaint/".$_complaint['complaint_id']);?>"><?php echo $_complaint['complaint_ticket_number']?></a>
					<br/><span class="text-muted">Due on - <?php echo date('D,d M', strtotime($_complaint['complaint_due_date']))?></span></td>
                  <td>
                    <a class="btn btn-primary btn-sm" style="margin-top:2px;" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?=$_complaint['complaint_id']?>|C', '<?=$_complaint['complaint_ticket_number']?> Log Report')"><i class="fa fa-comments-o fa-lg m-t-2"></i> &nbsp; Log a message</a><br/>
					<span class="text-muted visible-xs hidden-sm hidden-md hidden-lg"><?php echo $_complaint['complaint_ticket_number']?></span>
					<span class="text-muted visible-xs hidden-sm hidden-md hidden-lg">Due on - <?php echo date('D,d M', strtotime($_complaint['complaint_due_date']))?></span></td>
                  
                  <td><a class="btn btn-sm text-white" style="background-color:<?=$_complaint['complaint_status_clolor_code']?>;"><?php echo limitText($_complaint['complaint_status_name'], 20)?></a><br/>
                    <a class="btn btn-sm btn-success" style="margin-top:2px;" href="<?php echo $app->siteUrl("viewcomplaint/".$_complaint['complaint_id']);?>"><i class="fa fa-info-circle fa-lg m-t-2"></i> &nbsp; View</a></td>
                </tr>
                <?php			
            }         
          
          ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>  
  
  <?php 
  } 
  ?>
  
  <?php
  $complaint = new Complaint();
  $complaintDueToday = $complaint->getTodaysDueComplaint(date("Y-m-d"));
  if($complaintDueToday)
  { ?>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 my-2">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Today's Due Repair Request (<?php echo count($complaintDueToday);?>)       
      </div>
      <div class="block-fluid table-sorting clearfix">
        <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
          <div class="row">
            <table class="table mb-0">
              <tbody>
            <?php
            foreach($complaintDueToday  as $_complaint)
            {
                ?>
                <tr>
                  <td><img class="img-circle" alt="<?php echo $_complaint['technician_name']?>" src="<?php echo $_complaint['technician_image']?>" height="45px"/></td>
                  <td class="hidden-xs"><?php echo $_complaint['customer_name']?><br/>
                    <a class="text-muted" href="<?php echo $app->siteUrl("viewcomplaint/".$_complaint['complaint_id']);?>"><?php echo $_complaint['complaint_ticket_number']?></a>
					<br/><span class="text-muted">Added on - <?php echo date('D,d M', strtotime($_complaint['complaint_created_date']))?></span></td>
                  <td>
                    <a class="btn btn-primary btn-sm" style="margin-top:2px;" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?=$_complaint['complaint_id']?>|C', '<?=$_complaint['complaint_ticket_number']?> Log Report')"><i class="fa fa-comments-o fa-lg m-t-2"></i> &nbsp; Log a message</a><br/>
					<span class="text-muted visible-xs hidden-sm hidden-md hidden-lg"><?php echo $_complaint['complaint_ticket_number']?>
					<span class="text-muted visible-xs hidden-sm hidden-md hidden-lg">Added on - <?php echo date('D,d M', strtotime($_complaint['complaint_created_date']))?></span></span>
					</td>
                  
                  <td><a class="btn btn-sm text-white" style="background-color:<?=$_complaint['complaint_status_clolor_code']?>;"><?php echo limitText($_complaint['complaint_status_name'], 20)?></a><br/>
                    <a class="btn btn-sm btn-success" style="margin-top:2px;" href="<?php echo $app->siteUrl("viewcomplaint/".$_complaint['complaint_id']);?>"><i class="fa fa-info-circle fa-lg m-t-2"></i> &nbsp; View</a></td>
                </tr>
                <?php			
            }          
          
          ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>  
  
  <?php 
  } 
  ?>
 
  <div class="col-lg-8 col-md-8">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Activity List </div>
      <div class="block-fluid table-sorting clearfix">
        <table id="tblSortableActivity" class="table table-striped">
          <thead>
            <tr>
              <th>User</th>
              <th>Name</th>
              <!--<th>Reference</th>-->
              <th>Message</th>
              <th>Date</th>
              <th>IP-Address</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card" style="width:100%;">
          <div class="card-header"> <i class="fa fa-align-justify"></i> User's Work Sheet Timeline</div>
          <div class="block-fluid table-sorting clearfix">
            <ul class="icons-list" style="width:100%;">
              <?php 
		$activity = Activity::todayWorking();
		if($activity)
		{
			foreach($activity as $row)
			{ 			
			?>
              <li style="clear:both; height:46px;"> <img width="36px" src="<?=$row['user_image']?>" alt="<?=$row['user_name']?>" style="float:left;" />
                <div class="desc" style="border-bottom:none;">
                  <div class="title">
                    <?=$row['user_name']?>
                  </div>
                  <small>worked
                  <?=$row['working']?>
                  </small> </div>
                <div class="value">
                  <div class="small text-muted">Last Login
                    <?=$row['last_login_day']?>
                  </div>
                  <strong>
                  <?=$row['last_login']?>
                  </strong> </div>
                <div class="progress progress-xs" style="height:4px; margin-top:-2px;" data-val="<?=$row['seconds']?>" data-total="<?=$row['total']?>">
                  <div class="progress-bar bg-<?=$row['class']?>" role="progressbar" style="width: <?=$row['percent']?>%" aria-valuenow="<?=$row['percent']?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </li>
              <?php
			}	
		}
		?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
	<!--Google chart for wweb order-->
  <div class="col-md-12">
	<div id="chart_div" style="width: 100%; height: 400px;"></div>
  </div>
  <!--/col--> 
</div>

<script type="text/javascript">
	<?php 
	$woData = WebsiteOrder::getOrderStatusChart(30);
	?>
	$(document).ready(function(){
		  google.charts.load('current', {'packages':['corechart']});
		  google.charts.setOnLoadCallback(drawVisualization);


		  function drawVisualization() {
			// Some raw data (not necessarily accurate)
			var data = google.visualization.arrayToDataTable([
			  [<?php echo "'".implode("' , '", array_keys($woData[0]))."'"?>]
				<?php foreach($woData as $_woData){
					$_val = array_values($_woData);
					echo  ", ['$_val[0]',".implode(",", array_slice(array_values($_woData), 1))."]";
				}?>
			]);

			var options = {
			  title : 'Daily Web Order status',
			  vAxis: {title: 'Order'},
			  hAxis: {title: 'Day'},
			  seriesType: 'bars',
			  series: {5: {type: 'line'}}        };

			var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
			chart.draw(data, options);
		  }
	});
      
    </script>

<script type="text/javascript">
var serverdata = {
				"action"	:	"viewactivitylist",				
		   };
$(document).ready(function() {
    $('#tblSortableActivity').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax":  {
            "url": "<?=$app->basePath("server_processing.php")?>",
            "type": "POST",
			"data": serverdata
        },
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [0,1,2,3,4], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 1,4 ] }]
    });
});
</script>
<?php $cmp = new Complaint(0);?>
<?php if(1){ $emp = new Employee(0); ?>
<div class="row" style="margin-top:20px;">
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="card">
      <div class="card-header"> Repair Revenue by month </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="canvas-repair-revenuue"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="card">
      <div class="card-header"> Website Order history </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="canvas-website-order-history"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="card">
  <div class="card-block">
    <div class="row">
      <div class="col-sm-12 col-md-6 col-lg-6">
        <h4 class="card-title mb-0">Repair Request Status</h4>
        <div class="small text-muted">Last 30 days from Today</div>
        <div class="chart-wrapper" style="height:300px;margin-top:40px;">
          <canvas id="complaint_status_last_30_days" class="chart" height="300"></canvas>
        </div>
      </div>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="chart-wrapper">
          <canvas id="total_hardware_not_working_chart"></canvas>
        </div>
      </div>
    </div>
    <!--/.row--> 
    
  </div>
  <?php $mainChart = $cmp->ComplaintDueDate();?>
  <div class="card-footer" style="background:none;">
    <ul>
      <li>
        <div class="text-muted">Total Repair Request</div>
        <strong>
        <?=array_sum(explode(",",$mainChart["total"]))?>
        Repair Request</strong>
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
        Repair Request (
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
        Repair Request (
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
        Repair Request (
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
        Repair Request (
        <?= round(((array_sum(explode(",",$mainChart["other"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>
        %)</strong>
        <div class="progress progress-xs mt-h">
          <div class="progress-bar" role="progressbar" style="width: <?= isset($mainChart["other"]) ? round(((array_sum(explode(",",$mainChart["other"]))/array_sum(explode(",",$mainChart["total"])))*100),2):0?>%" aria-valuenow="<?= isset($mainChart["other"])? array_sum(explode(",",$mainChart[" other "])):0?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
    </ul>
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-6">
    <div class="card">
      <div class="card-header"> Repair Request Origin Store Chart
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
      <div class="card-header"> Total Repair Problems
        <div class="card-actions"> <a href="javascript:void(0)"> <small class="text-muted">Refresh</small> </a> </div>
      </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="total_complaints_problem_chart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="card">
      <div class="card-header"> Repair Request / Collection Request </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="canvas-complaint-collection"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="card">
      <div class="card-header"> Customer Support Conversation </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="canvas-customer-support-log"></canvas>
        </div>
      </div>
    </div>
  </div>
</div> 
<script type="application/javascript">

  var data1 = [<?=$mainChart["total"]?>];
  var data2 = [<?=$mainChart["completed"]?>];
  var data3 = [<?=$mainChart["toberepaired"]?>];
  var data4 = [<?=$mainChart["wrapproval"]?>];
  var data5 = [<?=$mainChart["other"]?>];

  
  var data = {
    labels: [<?=$mainChart["perday"]?>],
    datasets: [
      {
        label: 'Repair Collected',
        backgroundColor: convertHex($.brandInfo,10),
        borderColor: $.brandInfo,
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        data: data1
      },
      {
        label: 'Repair Completed',
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
            responsive: true,			
        }
    });
	
<?php  $DataHardware = $cmp->getHardwareNotWorkingCount(); ?>
	var DataHardware = <?php echo json_encode($DataHardware);?>;
		
	var data = {
    labels: DataHardware.label,
    datasets: [
      {
        label: 'Hardware Repair request',
		backgroundColor: getColor(DataHardware.label.length),
        hoverBackgroundColor: getColor(DataHardware.label.length),
        data: DataHardware.value
      },
    ]
  };
  var ctx = document.getElementById('total_hardware_not_working_chart');
  var cardChart3 = new Chart(ctx, {
    type: 'bar',
    data: data,
    options: {
				responsive: true,
				legend: {
					display: false            
        		} 
        	}
  });

	
	
<?php $ChartData = $cmp->complaintCollectionCountChart();?>
var randomScalingFactor = function(){ return Math.round(Math.random()*50)};
    var lineChartData = {
        labels : <?php echo $ChartData['MONTH']?>,
        datasets : [
            {
                label: 'Repair Request',
                backgroundColor : 'rgba(206,17,98,0.2)',
                borderColor : 'rgba(220,5,70,1)',
                pointBackgroundColor : 'rgba(243,18,136,1)',
                pointBorderColor : '#CD055A',
                data : <?php echo $ChartData['COMPLAINT']?>
            },
            {
                label: 'Collection Request',
                backgroundColor : 'rgba(5,115,235,0.2)',
                borderColor : 'rgba(14,124,189,1)',
                pointBackgroundColor : 'rgba(17,86,200,1)',
                pointBorderColor : '#123287',
                data : <?php echo $ChartData['COLLECTION']?>
            }
        ]
    };

    var ctx = document.getElementById('canvas-complaint-collection');
    var chart = new Chart(ctx, {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true
        }
    });
	
	
<?php 
$ComplaintLog = new ComplaintLog(0);
$ChartData = $ComplaintLog->getLogCountChartData();?>
    var lineChartData = {
        labels : <?php echo $ChartData['LABEL']?>,
        datasets : [
            {
                label: 'Message Send',
                backgroundColor : 'rgba(100,92,23,0.2)',
                borderColor : 'rgba(100,100,25,1)',
                pointBackgroundColor : 'rgba(110,102,33,1)',
                pointBorderColor : '#CD055A',
                data : <?php echo $ChartData['SENDED']?>
            },
            {
                label: 'Message Recieved',
                backgroundColor : 'rgba(1,66,96,0.2)',
                borderColor : 'rgba(5,70,100,1)',
                pointBackgroundColor : 'rgba(8,70,100,1)',
                pointBorderColor : '#123287',
                data : <?php echo $ChartData['RECEIVED']?>
            }
        ]
    };

    var ctx = document.getElementById('canvas-customer-support-log');
    var chart = new Chart(ctx, {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true
        }
    });
	
	
	
<?php $ChartData = $cmp->getRevenueReport();?>
var randomScalingFactor = function(){ return Math.round(Math.random()*50)};
    var lineChartData = {
        labels : <?php echo json_encode(array_reverse($ChartData['label']))?>,
        datasets : [
            {
				label: 'Repair Revenue (£ <?php echo array_sum($ChartData['value'])?>)',
                backgroundColor : 'rgba( 102, 255, 255,0.35)',
                borderColor : 'rgba( 0, 204, 255 ,1)',
                pointBackgroundColor : 'rgba( 51, 153, 255 ,1)',
                pointBorderColor : '#3366FF',
                data : <?php echo json_encode(array_reverse($ChartData['value']))?>
            }
        ]
    };

    var ctx = document.getElementById('canvas-repair-revenuue');
    var chart = new Chart(ctx, {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true
        }
    });
	
	
<?php $ChartData = WebsiteOrder::getOrderHistoryChart();?>
var randomScalingFactor = function(){ return Math.round(Math.random()*50)};
    var lineChartData = {
        labels : <?php echo json_encode(array_reverse(array_keys($ChartData)))?>,
        datasets : [
            {
				label: 'Website Order (<?php echo array_sum(array_values($ChartData))?>)',
                backgroundColor : 'rgba( 255, 255, 0 ,0.35)',
                borderColor : 'rgba( 255, 204, 0 ,1)',
                pointBackgroundColor : 'rgba( 255, 102, 0 ,1)',
                pointBorderColor : '#FF3300',
                data : <?php echo json_encode(array_reverse(array_values($ChartData)))?>
            }
        ]
    };

    var ctx = document.getElementById('canvas-website-order-history');
    var chart = new Chart(ctx, {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true
        }
    });
	
	
$(document).ready(function(e) {
<?php 
$ComplaintLog = new ComplaintLog(); 
echo "DashboradNotification = ".json_encode($ComplaintLog->getCustomerMessageNotification()).";";
?>
if(DashboradNotification != false)
{
	$("#appModalHtmlBody").html('');
	for(var i=0; i<DashboradNotification.length; i++)
	$("#appModalHtmlBody").append('<div class="card card-block notif_box_content"><div class="text-justify font-italic">"'+DashboradNotification[i].complaint_log_text+'"</div><div class="text-right text-success"><b>'+DashboradNotification[i].logger_name+' </b> <img src="'+DashboradNotification[i].logger_image +'" class="img-avatar icon_customer_img" height="32px" /></div><div class="text-right notif_msg_time"><small class="text-muted"><i class="icon-clock"></i> '+DashboradNotification[i].complaint_log_time+'</small></div><div class="text-right"><a data-toggle="modal" data-target="#appModal" href="#" onclick="openChatLogForm(\''+DashboradNotification[i].complaint_id+'|'+DashboradNotification[i].complaint_format+'\', \'# Reply to '+DashboradNotification[i].logger_name+'\')"><i class="fa fa-reply"></i> Reply to '+DashboradNotification[i].logger_name+'</a> </div></div>');
	$("#appModalHtmlNotification").click();
}   
});
</script>
<?php }?>
