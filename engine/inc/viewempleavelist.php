<div class="row">
<?php if(isAdmin()){?>
  <div class="col-lg-3">
	  <div class="card">
		  <form id="tablefilter" name="tablefilter">
		<div class="card-header"> <i class="fa fa-align-justify"></i> Filter 
		<a id="resetfilteration" href="#" class="pull-right">Reset</a></div>
			<div class="block-fluid table-sorting clearfix">
				<div class="row">
					<div class="col-xs-12" id="appliedfilter"></div>
				</div>
			</div>
			  <div style="padding: 5px">
      			
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$employeeLeave = new EmployeeLeave();
			$field = 'leave_employee_id';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Employee <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $employeeLeave->getEmployeeFilteration();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['user_fname'].' '.$item['user_lname']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['user_id']);?>">
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><img class="img-avatar" src="<?php echo $item['user_image']?>" height="24px"/> <?php echo htmlspecialchars($item['user_fname'].' '.$item['user_lname']);?> (<?php echo $item['record'];?>)</label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$employeeLeave = new EmployeeLeave();
			$field = 'leave_from_time';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">From <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<div class="form-check checkbox filtercheckbox pl-1 mb-3">				
                <input class="form-check-input form-control" onchange="$(this).attr('data-label', 'From :'+$(this).val())" data-label="From:" id="<?php echo $field;?>" name="<?php echo $field;?>" maxlength="20" placeholder="YYYY-MM-DD" type="text" value="">             
				<script type="text/javascript">
				$(function () {
					$('#<?php echo $field;?>').datepicker({
						format: 'yyyy-mm-dd',
						autoclose:true,
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true,
						fontAwesome : true,
						showMeridian: true,
					});
				});
            </script> 
            
				</div>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$employeeLeave = new EmployeeLeave();
			$field = 'leave_end_time';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Till <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<div class="form-check checkbox filtercheckbox pl-1 mb-3">				
                <input class="form-check-input form-control" onchange="$(this).attr('data-label', 'Till :'+$(this).val())" data-label="Till:" id="<?php echo $field;?>" name="<?php echo $field;?>" maxlength="20" placeholder="YYYY-MM-DD" type="text" value="">             
				<script type="text/javascript">
				$(function () {
					$('#<?php echo $field;?>').datepicker({
						format: 'yyyy-mm-dd',
						autoclose:true,
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true,
						fontAwesome : true,
						showMeridian: true,
					});
				});
            </script> 
            
				</div>
			</div>
			</div>
		</div>
			
		</div>
				  </div>
		</form>
	</div>
<?php }?>
  <div class="col-lg-<?php echo isAdmin() ? 9 : 12;?>">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> <?php echo $formHeading; ?>
      <div class="card-actions">
      <?php if(isAdmin()):?>
            <a data-title="Add New Contract" class="redirect" title="Add New Contract" href="<?php echo $app->siteUrl("empcontract");?>"><i class="icon-star icons font-2xl d-block m-t-2"></i></a>
            <a data-title="Complaint PDF Report" title="Generate PDF Report" href="javascript:newWindow('<?=DOC::EMPLEAVELIST();?>');"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
            <?php endif; ?>
        </div>
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>#Pic</th>
			  <th>Name</th>
			  <th>Type</th>
			  <th>Duration</th>
              <th>From</th>
              <th>Till</th>
              <th>Reason</th>
              <th>status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			//$Complaint = new Complaint(0);
			//echo $Complaint->getRecords();
			?>
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">
var data = {
								
		   };
var coumnter = 0;
var datatable;
var formdata = {};
if(app_filter_state == 1 && window.localStorage.getItem(window.location.href)){
	formdata = JSON.parse(window.localStorage.getItem(window.location.href));
	console.log(formdata);
	$('.form-check-input').each(function(){
		if(formdata.hasOwnProperty($(this).attr('name'))){
			var filterVal = formdata[$(this).attr('name')];
			if(filterVal.indexOf($(this).val()) !== -1){
				$(this).attr("checked", true);					
				$("#appliedfilter").append(getFilterLabel($(this)));
			}
		}
	})
}

$(document).ready(function() { 
	
	$('.form-check-input').on('change', function(){
		formdata = $("#tablefilter").serializeFormJSON();		
		
		postdata = {
			action	:	"viewempleavelist",
			filter  :   formdata
		}
		//console.log(postdata);
		
		if(typeof datatable != 'undefined'){
			datatable.clear().draw();
		}
		if($(this).is(":checked"))
			$("#appliedfilter").append(getFilterLabel($(this)));
		else
			$("#tag_"+$(this).attr('id')).remove();
		addfiltersearch();
	});
	
	$('.filtercontroller').on('click', function(){
		var id = $(this).attr('data-id');
		if($(this).hasClass('fa-chevron-down')){
			$("#label_"+id).show();
		}
		else
			$("#label_"+id).hide();
		$(this).toggleClass('fa-chevron-down fa-chevron-up');
	})
	$("div.col-form-label").each(function(){
		$(this).hide();
	})
	$(document).on("click", ".filtercloser", function(e){
		var id = $(this).attr('data-id');
		$("#"+id).prop("checked", false);
		$("#tag_"+id).remove();
		$("#"+id).trigger("change");
		addfiltersearch();
	})
} );


$(document).on("click", ".leavesummary", function(e){
	var leave_employee_id = $(this).attr('data-user-id');
	var rec_user_img = $(this).attr('data-user-image');
	var rec_user_name = $(this).attr('data-user-name');
	var leave_from_time = $("#leave_from_time").val();
	var leave_end_time = $("#leave_end_time").val();
	var duration = "";
	if(leave_from_time != "")
		duration += "<b>From:</b>"+leave_from_time;
	if(leave_end_time != "")
		duration += " <b>To:</b> "+leave_end_time;
	if(duration == "")
		duration = "All time";
	
	setPopup(leave_employee_id, "<i class=\"icon-wallet\"></i> Leave/Extra Hours Details");
	var bodyHtml = '';
	bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-6"><label>Employee </label><br/><img class="img-circle" src="'+rec_user_img+'"> '+rec_user_name+'</div>';
	bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-6"><label>Duration</label><br/>'+duration+'</div>';
	bodyHtml += '<div class="col-xs-12" id="leavesummarydata">'+LOADING_HTML +'</div>';
	modal.Body(bodyHtml);
	modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
	
	var data={
					action	:	'employee/employeeleavesummary',
					leave_employee_id : leave_employee_id,
					leave_from_time:leave_from_time,
					leave_end_time:leave_end_time
				};
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("process|Connecting...");
			dissableSubmission();
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				innerHtml = '<table class="table table-bordered table-striped mt-2">';
				innerHtml += '<tr><td>Total Leaves Days</td><td>'+arr[2]['leave_count']+'</td></tr>';
				innerHtml += '<tr><td>Extra Working Days</td><td>'+arr[2]['extra_count']+'</td></tr>';
				innerHtml += '<tr><td>Net Leaves Time</td><td>'+arr[2]['leave_days']+'</td></tr>';
				innerHtml += '<tr><td>Extra Hours Time</td><td>'+arr[2]['extra_days']+'</td></tr>';
				innerHtml += '<tr><td>Settaled Hours</td><td>'+arr[2]['net_leaves']+''+arr[2]['net_lables']+'</td></tr>';
				innerHtml += '</table>';
				$("#leavesummarydata").html(innerHtml);
			}
			message(arr[1]);
		}
	});	
	
});

var postdata = {
		action	:	"viewempleavelist",
		filter  :   formdata
	}

datatable = $('#tblSortable').DataTable( {
	"processing": true,
	"serverSide": true,
	"bStateSave": true,
	"ajax":  {
		"url": "<?=$app->basePath("server_processing.php")?>",
		"type": "POST",
		"data": function ( d ) {
			$.extend(d, postdata);
		}
	},
	'fnCreatedRow': function (nRow, aData, iDataIndex) {
		$(nRow).attr('id', 'row_' + aData[9]); // or whatever you choose to set as the id
	},
	"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[7]);},
		"order": [[ 4, 'desc' ]],
		columnDefs: [{ targets: [8], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 0,4,5,6 ] }]
} );
	
function delEmpLeave(leave_id){
	if(leave_id != 0)
	{
		var data={
					action	:	'employee/delempleave',
					leave_id : leave_id
				};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#row_"+leave_id).remove();			
				}
				message(arr[1]);
			}
		});	
	}
}

</script> 
