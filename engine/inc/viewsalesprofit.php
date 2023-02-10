<div class="row">
  <?php if(isAdmin()){?>
  <div class="col-lg-3">
    <div class="card">
      <form id="tablefilter" name="tablefilter">
        <div class="card-header"> <i class="fa fa-align-justify"></i> Filter <a id="resetfilteration" href="#" class="pull-right">Reset</a></div>
        <div class="block-fluid table-sorting clearfix">
          <div class="row">
            <div class="col-xs-12" id="appliedfilter"></div>
          </div>
        </div>
        <div style="padding: 5px">
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$websiteOrder = new WebsiteOrder();
			$field = 'currency';
			?>
			
			<div id="label_<?php echo $field;?>" class="col-md-12">
			<?php
			$records = $websiteOrder->getWebOrderCurrency();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="col-xs-12">
                    <label class="switch switch-icon switch-pill switch-success">
                    <input class="switch-input form-check-input-independent field_<?php echo $field?>" <?=$item['web_order_currency'] == 'GBP' ? "checked":""?> id="field_<?php echo $field?>_<?php echo $i;?>" value="<?php echo htmlspecialchars($item['web_order_currency']);?>" name="<?php echo $field?>" type="radio">
                    <span class="switch-label" data-on="<?php echo htmlspecialchars($item['web_order_currency']);?>" data-off="No"></span> <span class="switch-handle"></span> </label><strong>&nbsp; <?php echo htmlspecialchars($item['web_order_currency']);?>(<?php echo $item['record'];?>)</strong>
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
			$salesProfit = new SalesProfit();
			$field = 'store_id';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Website <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $salesProfit->getWebsiteFilteration();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['store_name']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['store_id']);?>">
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><img class="img-avatar" src="<?php echo $item['store_icon']?>" height="24px"/> <?php echo htmlspecialchars($item['store_official_name']);?></label>
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
			$field = 'web_order_date_range';
			?>
              <label class="col-md-12 col-form-label">
              <h5 class="filterheading">Date Range <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5>
              </label>
              <div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
                
                <div class="form-check checkbox filtercheckbox">
				<div class="row">
				<div class="col-sm-12">
                  <div class="form-group">
					<label class="pl-0" for="web_order_created_date_from">From Date<sup></sup></label>
					<div class="input-group date">
					  <input type="text" class="form-control form-check-input" data-label="Date from" id="web_order_created_date_from" name="web_order_date_range][from" placeholder="YYYY-MM-DD" value="<?php echo date("Y-m-d", strtotime("-1 month"))?>">
					  <span class="input-group-addon">
					  <label style="margin-bottom:0px;" for="web_order_created_date_from"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
					  </span> </div>
						<script type="text/javascript">
								$('#web_order_created_date_from').datepicker({
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
					<label class="pl-0" for="web_order_created_date_to">To Date<sup></sup></label>
					<div class="input-group date">
					  <input type="text" class="form-control form-check-input" data-label="Date to" id="web_order_created_date_to" name="web_order_date_range][to" placeholder="YYYY-MM-DD" value="<?php echo date("Y-m-d")?>">
					  <span class="input-group-addon">
					  <label style="margin-bottom:0px;" for="web_order_created_date_to"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
					  </span> </div>
						<script type="text/javascript">
								$('#web_order_created_date_to').datepicker({
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
				<div class="col-sm-12 mt-3">
					<div class="card card-success">
						<div class="card-header">Sales Summary</div>
						<div class="card-body p-2 statistics-summary"></div>
					</div>
				</div>
			</div>
		  </div>
        </div>
      </form>
    </div>
		
  </div>
  <?php }?>
  <div class="col-lg-<?php echo isAdmin() ? 9 : 12;?>">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Sales Profit Report
        <div class="card-actions">
          <?php if(isAdmin()):?>
          <a data-title="Get Excel File" title="Get Excel File" href="javascript:newWindow('<?=DOC::SALESPROFITEXCEL();?>');"><i class="fa fa-file-excel-o font-2xl d-block m-t-2"></i></a>
          <?php endif; ?>
        </div>
      </div>
      <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>Source</th>
			  <th>Store</th>
			  <th>Customer</th>
			  <th>OrderNo</th>
              <th>Purchase</th>
			  <th>Sell</th>
			  <th>Ship</th>
              <th>Profit</th>
			  <th>Payment</th>
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
datatable = $('#<?php echo $TABEL_ID;?>').DataTable();

function refreshRecords(){
	var validated = true;
	
	if(validated)
	{
		var data={
			action		: 'weborder/salesprofitrecord',
			interval	: 'custom',
			from_date	: $("#web_order_created_date_from").val(),
			to_date		: $("#web_order_created_date_to").val(),
			filter  	: $("#tablefilter").serializeFormJSON()
		};
		datatable.clear();
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Getting records...", 0);
				dissableSubmission();
			},		
			success:function(output){	
				var arr = JSON.parse(output);
				if(arr[0]==200)
				{	
					message("process|Records loaded now Drawing...", 0);
					var records  = arr[2]['records'];	
					for(var i=0; i<records.length; i++){
						var r = records[i];
						datatable.row.add( [ r['source'], r['store'], r['customer'], r['order_number'], r['report_currency']+r['base_purchase_price'], r['report_currency']+r['base_sell_price'], r['report_currency']+r['base_shipping_paid'], r['report_currency']+r['base_profit'], r['payment'],  '<a data-quick-view=\"true\" data-toggle=\"modal\" data-target=\"#appModalQuick\" data-quick-url=\"'+(r['source']=='Web order'?'viewweborder':'salesinvoice')+'/'+r['id']+'\" class=\"btn btn-info text-white\">View</a>' ] ).draw().node();
						//var tr = $("<tr><td>"+r['order_number']+"</td><td>"+r['base_purchase_price']+"</td><td>"+r['base_sell_price']+"</td><td>"+r['base_shipping_price']+"</td><td>"+r['base_profit']+"</td><td>"+r['product_name']+"</td><td>action</td></tr>");
						//datatable.row.add(tr[i]).draw();
					}
					
					var recordData  = arr[2]['recordData'];	
					
					$(".statistics-summary").html(`
					<p>Total sales- <b>`+recordData['sell']+`</b></p>
					<p>Total Purchasing- <b>`+recordData['purchase']+`</b></p>
					<p>Total Shipment- <b>`+recordData['shipment']+`</b></p>
					<p>Total Profit- <b>`+recordData['profit']+`</b></p>
					`);
					
				}				
				message(arr[1],2000);
			}
		})	
	}
}

$(document).ready(function() {   
	
	
	$('.form-check-input').on('change', function(){
		refreshRecords();
		
		if($(this).is(":checked")){
			if($(this).is("[type=radio]")){
				var name = $(this).attr('name');
				console.log(name);
				$('input[name="'+name+'"]').each(function(){
					$("#tag_"+$(this).attr('id')).remove();
				})
			}
			$("#appliedfilter").append(getFilterLabel($(this)));
		}
		else
			$("#tag_"+$(this).attr('id')).remove();
		addfiltersearch();
	});
	
	$(".form-check-input-independent").on('change', function(){
		refreshRecords();
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
	});
	
	$("div.col-form-label").each(function(){
		$(this).hide();
	});
	
	$(document).on("click", ".filtercloser", function(e){
		var id = $(this).attr('data-id');
		$("#"+id).prop("checked", false);
		$("#tag_"+id).remove();
		$("#"+id).trigger("change");
		addfiltersearch();
	});
	
	$(".orderDetailPrint").on("click", function(){
		var tab = document.getElementById('table_weborder');
            var win = window.open('', '', 'height=700,width=700');
            win.document.write(tab.outerHTML);
            win.document.close();
            win.print();
	});
} );
</script> 
