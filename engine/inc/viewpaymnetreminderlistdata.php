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
			$pr = new PaymentReminder();
			$field = 'payment_reminder_related_store';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Store <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $pr->getWebsiteFilteration();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['store_name']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['store_id']);?>">
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><img class="img-avatar" src="<?php echo $item['store_icon']?>" height="24px"/> <?php echo htmlspecialchars($item['store_name']);?> (<?php echo $item['record'];?>)</label>
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
			$pr = new PaymentReminder();
			$field = 'payment_reminder_status';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Status <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $pr->getStatusFilteration();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['wc_status_name']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['wc_status_id']);?>">
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item['wc_status_name']);?> (<?php echo $item['record'];?>)</label>
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
			$pr = new PaymentReminder();
			$field = 'payment_reminder_creator_user';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Creator <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $pr->getCreatorFilteration();
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
                <div class="col-sm-12">            
                    <div class="chart-wrapper">
                      <canvas id="payment_reminder_status_chart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
          <script>
		  <?php  
		  $sumData = PaymentReminder::getPaymentReminderStatusCount();
	  	  $_arrstatus = array_column($sumData, 'status');
		  $_arrrecord = array_column($sumData, 'record');
		  $_arrcolour = array_column($sumData, 'color');
		  ?>
		  var doughnutData = {
				labels: [<?="'".implode("', '", $_arrstatus)."'"?>],
				datasets: [{
					data: [<?=implode(', ', $_arrrecord)?>],
					backgroundColor: [<?="'".implode("', '", $_arrcolour)."'"?>],
				}]
			};
			$(document).ready(function(){
				var ctx = document.getElementById('payment_reminder_status_chart');
				var chart = new Chart(ctx, {
					type: 'pie',
					data: doughnutData,
					options: {
						responsive: true,
						maintainAspectRatio: false,						
					}
				});
			});
			
	
		  </script>
          
			
		</div>
				  </div>
		</form>
	</div>
<?php }?>
  <div class="col-lg-<?php echo isAdmin() ? 9 : 12;?>">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> <?=$formHeading?> 
		<div class="card-actions"> <a class="redirect" href="<?php echo $app->basePath("addpaymentreminder")?>"> <i class="icon-plus"></i> </a> </div>
      </div>	  
       <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>User</th>
              <th>Due Date</th>
              <th>Title</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
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
		$('.form-check-input').each(function(){
			if(formdata.hasOwnProperty($(this).attr('name'))){
				var filterVal = formdata[$(this).attr('name')];
				//console.log(filterVal);
				if(filterVal.indexOf($(this).val()) !== -1){
					$(this).attr("checked", true);					
					$("#appliedfilter").append(getFilterLabel($(this)));
				}
			}
		})
	}
	var postdata = {
			action	:	"viewpaymentreminderlist",
			filter  :   formdata
		}
$(document).ready(function() {   
	
	
	$('.form-check-input').on('change', function(){
		formdata = $("#tablefilter").serializeFormJSON();		
		
		postdata = {
			action	:	"viewpaymentreminderlist",
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


datatable = $('#<?php echo $TABEL_ID;?>').DataTable( {
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
		$(nRow).find('td').not(":last").attr('onclick', "Redirect('"+aData[6]+"')"); // or whatever you choose to set as the id
	},
	"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[7]);},
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [5], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 0,2 ] },
					 //{ className: "word_break", "targets": [3] }
					 ],
} );

</script> 
