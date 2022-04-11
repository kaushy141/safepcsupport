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
			$websiteOrder = new SalesInvoice();
			$field = 'sales_invoice_store_id';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Website <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $websiteOrder->getWebsiteFilteration();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['store_title']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['sales_invoice_store_id']);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><img class="img-avatar" src="<?php echo $item['store_icon']?>" height="24px"/> <?php echo htmlspecialchars($item['store_title']);?> (<?php echo $item['record'];?>)</label>
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
			$SalesInvoice = new SalesInvoice();
			$field = 'sales_invoice_status';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Status <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $SalesInvoice->getStatusFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['wc_status_name']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['wc_status_id']);?>"> &nbsp; 
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
			$SalesInvoice = new SalesInvoice();
			$field = 'sales_invoice_creator';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Invoice Creator <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $SalesInvoice->getCreatorFilteration();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['user_fname'].' '.$item['user_lname']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['user_id']);?>"> &nbsp; 
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
			$field = 'sales_invoice_is_estimate';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Estimate / Invoice <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Invoice" name="<?php echo $field?>" id="<?php echo $field.'_0'?>" type="checkbox" value="0">
				<label class="form-check-label" for="<?php echo $field.'_0'?>"> Invoice</label>
				</div>
                <div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Estimate" name="<?php echo $field?>" id="<?php echo $field.'_1'?>" type="checkbox" value="1">
				<label class="form-check-label" for="<?php echo $field.'_1'?>"> Estimate</label>
				</div>
				
			</div>
			</div>
		</div>
		
		
		<div class="block-fluid table-sorting clearfix">
            <div class="row">
              <?php 
			$field = 'sales_invoice_created_date';
			?>
              <label class="col-md-12 col-form-label">
              <h5 class="filterheading">Date Range <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5>
              </label>
              <div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
                
                <div class="form-check checkbox filtercheckbox">
				<div class="row">
				<div class="col-sm-12">
                  <div class="form-group">
					<label class="pl-0" for="sales_invoice_created_date_from">From Date<sup></sup></label>
					<div class="input-group date">
					  <input type="text" class="form-control form-check-input" data-label="Date from" id="sales_invoice_created_date_from" name="sales_invoice_created_date_from" placeholder="YYYY-MM-DD" value="">
					  <span class="input-group-addon">
					  <label style="margin-bottom:0px;" for="sales_invoice_created_date_from"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
					  </span> </div>
						<script type="text/javascript">
								$('#sales_invoice_created_date_from').datepicker({
									format: "yyyy-mm-dd",
									autoclose:true,						
									daysOfWeekHighlighted: '0,6',
									todayHighlight:true,
									showClear: true,
									showClose: true,
									showTodayButton: true
								});
						</script> 
				  </div>
                </div>
				<div class="col-sm-12">
                  <div class="form-group">
					<label class="pl-0" for="sales_invoice_created_date_to">To Date<sup></sup></label>
					<div class="input-group date">
					  <input type="text" class="form-control form-check-input" data-label="Date to" id="sales_invoice_created_date_to" name="sales_invoice_created_date_to" placeholder="YYYY-MM-DD" value="">
					  <span class="input-group-addon">
					  <label style="margin-bottom:0px;" for="sales_invoice_created_date_to"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
					  </span> </div>
						<script type="text/javascript">
								$('#sales_invoice_created_date_to').datepicker({
									format: "yyyy-mm-dd",
									autoclose:true,						
									daysOfWeekHighlighted: '0,6',
									todayHighlight:true,
									showClear: true,
									showClose: true,
									showTodayButton: true
								});
						</script> 
				  </div>
                </div>
				</div>
				</div>
                
              </div>
            </div>  
          </div>
        <div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'data_filter';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Data Filter <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Completely Paid" name="sales_invoice_is_paid" id="sales_invoice_is_paid" type="checkbox" value="1"> &nbsp; 
				<label class="form-check-label" for="sales_invoice_is_paid"> Completely Paid</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Vat Applicable" name="sales_invoice_is_vat_applicable" id="sales_invoice_is_vat_applicable" type="checkbox" value="1"> &nbsp; 
				<label class="form-check-label" for="sales_invoice_is_vat_applicable"> Vat Applicable</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Estimate only" name="sales_invoice_is_estimate" id="sales_invoice_is_estimate" type="checkbox" value="1"> &nbsp; 
				<label class="form-check-label" for="sales_invoice_is_estimate"> Estimate only</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Dummy Record" name="sales_invoice_is_dummy" id="sales_invoice_is_dummy" type="checkbox" value="1"> &nbsp; 
				<label class="form-check-label" for="sales_invoice_is_dummy"> Dummy Record</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Generated" name="sales_invoice_is_generated" id="sales_invoice_is_generated" type="checkbox" value="1"> &nbsp; 
				<label class="form-check-label" for="sales_invoice_is_generated"> Generated</label>
				</div>
			</div>
			</div>
		</div>
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
          
			
		</div>
				  </div>
		</form>
	</div>
<?php }?>
  <div class="col-lg-<?php echo isAdmin() ? 9 : 12;?>">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i>Sales Invoice/Estimate List 
      <div class="card-actions">
      <?php if(isAdmin()):?>
            <a class="redirect" data-title="Add New Sales Invoice" title="Add New Sales Invoice" href="<?php echo $app->siteUrl("addsalesinvoice");?>"><i class="icon-star icons font-2xl d-block m-t-2"></i></a>
            <a data-title="Sales Invoice PDF Report" title="Sales Invoice PDF Report" href="javascript:newWindow('<?=DOC::SALESINVLIST();?>');"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
            <?php endif; ?>
        </div>
      </div>	  
       <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Code</th>
              <th>Customer</th>
              <th>Amount</th>
              <th>Store</th>
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
			action	:	"viewsalesinvoicelist",
			filter  :   formdata
		}
$(document).ready(function() {   
	
	
	$('.form-check-input').on('change', function(){
		formdata = $("#tablefilter").serializeFormJSON();		
		
		postdata = {
			action	:	"viewsalesinvoicelist",
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
		$(nRow).find('td').not(":last").attr('onclick', "Redirect('"+aData[9]+"')"); // or whatever you choose to set as the id
		$(nRow).find('td').addClass(aData[10] == 1 ? 'text-line-through': '');
	},
	"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[8]);},
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [7], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 1,4,5,6 ] },
					 { className: "word_break", "targets": [3] }],
} );

</script> 
