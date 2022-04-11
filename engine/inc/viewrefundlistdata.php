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
			Modal::load(array('Refund')); 
			$refund = new Refund();
			$field = 'refund_type_code';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Refund Section <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $refund->getRefundSectionList();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['activity_section_name']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['activity_section_code']);?>">
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item['activity_section_name']);?> (<?php echo $item['record'];?>)</label>
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
			$refund = new Refund();
			$field = 'refund_initiated_by';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Initiated By <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $refund->getInitiatorFilteration();
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
			$refund = new Refund();
			$field = 'refund_process_by';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Process By <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $refund->getProcessorFilteration();
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
			$refund = new Refund();
			$field = 'refund_completed_by';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Completed By <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $refund->getCompletorFilteration();
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
			$wcstatus = new WcStatus();
			$field = 'refund_status';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Status <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $wcstatus->getCommonList('wc_status_name');
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['wc_status_name']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['wc_status_id']);?>">
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item['wc_status_name']);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<?php if(0){?>
        
        <div class="block-fluid table-sorting clearfix">
			<div class="row">
                <div class="col-sm-12">            
                    <div class="chart-wrapper">
                      <canvas id="invoice_status_chart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
          <script>
		  <?php  
		  $sumData = SalesInvoice::getSalesStatusCount();
	  	  $_arrstatus = array_column($sumData, 'status');
		  $_arrrecord = array_column($sumData, 'record');
		  ?>
		  var doughnutData = {
				labels: [<?="'".implode("', '", $_arrstatus)."'"?>],
				datasets: [{
					data: [<?=implode(', ', $_arrrecord)?>],
					backgroundColor: ['#f77265','#8abc51', '#f7f365', '#65cdf7'],
				}]
			};
			$(document).ready(function(){
				var ctx = document.getElementById('invoice_status_chart');
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
          
		<?php }?>	
		</div>
				  </div>
		</form>
	</div>
<?php }?>
  <div class="col-lg-<?php echo isAdmin() ? 9 : 12;?>">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i>Refund List 
      
      </div>	  
       <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>Code</th>
              <th>Customer</th>
              <th>Section</th>
              <th>Refund</th>
              <th>Pattern</th>
              <th>Status</th>
              <th>Created</th>
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
			action	:	"viewrefundlist",
			filter  :   formdata
		}
$(document).ready(function() {   
	
	
	$('.form-check-input').on('change', function(){
		formdata = $("#tablefilter").serializeFormJSON();		
		
		postdata = {
			action	:	"viewrefundlist",
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
		$(nRow).find('td').not(":last").attr('onclick', "Redirect('"+aData[8]+"')"); // or whatever you choose to set as the id
	},
	"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[9]);},
	"order": [[ 0, 'desc' ]],
	columnDefs: [{ targets: [7], orderable: false },
				 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,3,4,6 ] },
				 { className: "word_break", "targets": [1] }],
} );


</script> 
