<div class="row">
<?php if(isAdmin()){?>
  <div class="col-lg-3">
	  <div class="card">
		  <form id="tablefilter" name="tablefilter">
		<div class="card-header"> <i class="fa fa-align-justify"></i> Filter </div>
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
			$field = 'web_order_currency';
			?>
			
			<div id="label_<?php echo $field;?>" class="col-md-12">
			<?php
			$records = $websiteOrder->getWebOrderCurrency();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="col-xs-4 text-center">
                    <label class="switch switch-icon switch-pill switch-success">
                    <input class="switch-input form-check-input-independent field_<?php echo $field?>" checked="" id="field_<?php echo $field?>_<?php echo $i;?>" value="<?php echo htmlspecialchars($item['web_order_currency']);?>" name="<?php echo $field?>" type="radio">
                    <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label><br><strong><?php echo htmlspecialchars($item['web_order_currency']);?><br/>(<?php echo $item['record'];?>)</strong>
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
			$websiteOrder = new WebsiteOrder();
			$field = 'web_order_website_id';
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
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['store_name']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['web_order_website_id']);?>">
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
			$websiteOrder = new WebsiteOrder();
			$field = 'web_order_status';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Status <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $websiteOrder->getStatusFilteration();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['wc_status_name']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['web_order_status']);?>">
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
			$websiteOrder = new WebsiteOrder();
			$field = 'web_order_payment_method';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Payments <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $websiteOrder->getPaymentMethodFilteration();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['web_order_payment_method']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['web_order_payment_method']);?>">
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><img alt="<?php echo htmlspecialchars(ucwords($item['web_order_payment_method']));?>" class="img-avatar" src="<?php echo $app->basePath('img/system/gateway/'.$item["web_order_payment_method"].'-min.png')?>" height="24px"/> (<?php echo $item['record'];?>)</label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<!--
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			
			<?php 
			$websiteOrder = new WebsiteOrder();
			$field = 'web_order_created_date';
			$records = array(
				'today' => 'Today',
				'month' => 'Last 30 Days',
				'quarter' => 'Last 3 Months',
				'halfyear' => 'Last 6 Months',
				'year' => 'Last 1 Year',
				'all' => 'All'
			);
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Duration <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			foreach($records as $key=>$label){
			?>
			<div class="form-check checkbox filtercheckbox">
			<input class="form-check-input ml-0" data-label="<?php echo $label?>" name="duration" id="<?php echo $field.'_'.$key?>" type="radio" value="<?php echo $key?>"> &nbsp; 
			<label class="form-check-label" for="<?php echo $field.'_'.$key?>"> <?php echo $label?></label>
			</div>
			<?php
			}
			?>			
			</div>
			</div>
		</div> -->
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
					  <input type="text" class="form-control form-check-input" data-label="Date from" id="web_order_created_date_from" name="web_order_date_range][from" placeholder="YYYY-MM-DD" value="">
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
					  <input type="text" class="form-control form-check-input" data-label="Date to" id="web_order_created_date_to" name="web_order_date_range][to" placeholder="YYYY-MM-DD" value="">
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
					
		</div>
				  </div>
		</form>
	</div>
<?php }?>
  <div class="col-lg-<?php echo isAdmin() ? 9 : 12;?>">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i>  Order From Website  
		<div class="card-actions">
			<a class="orderDetailPrint"><i class="fa fa-print"></i></a>
		</div>
      </div>
       <div class="block-fluid table-sorting clearfix">
		 <table id="table_weborder" class="table table-bordered">
			<thead>
				<tr>
					<th>Website</th>
					<th>Order</th>
					<th>Total Amount</th>
					<th>Completed</th>
					<th>Comp.Amount</th>
					<th>Cancel</th>
					<th>Can.Amount</th>
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
			action	:	"weborder/viewwebsiteorderreportlist",
			filter  :   formdata
		}
$(document).ready(function() {   
	
	
	$('.form-check-input').on('change', function(){
		formdata = $("#tablefilter").serializeFormJSON();		
		
		postdata = {
			action	:	"weborder/viewwebsiteorderreportlist",
			filter  :   formdata
		}
		//console.log(postdata);
		getReport(postdata);
		
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
		formdata = $("#tablefilter").serializeFormJSON();		
		
		postdata = {
			action	:	"weborder/viewwebsiteorderreportlist",
			filter  :   formdata
		}
		//console.log(postdata);
		getReport(postdata);
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
	
	$(".orderDetailPrint").on("click", function(){
		var tab = document.getElementById('table_weborder');
            var win = window.open('', '', 'height=700,width=700');
            win.document.write(tab.outerHTML);
            win.document.close();
            win.print();
	});
} );

function getReport(data)
{	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("process|Connecting...",0);
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);
			message(arr[1],2000);
			drawWebsiteOrderSummary(arr[2], arr[3]);
		}
	})	;
}


function drawWebsiteOrderSummary(webSalesItem, currency){
	var items = webSalesItem['website_order'];
	$("#table_weborder tbody").html('');
	var html = "";
	if(items.length > 0){
		var to = 0;
		var ta = 0;
		var tcomp = 0;
		var tcomp_a = 0;
		var tcan = 0;
		var tcan_a = 0;
		html += "<tr><td colspan='7' style='background-color:#ddd;'><strong>Web Order Data</strong></td></tr>";
		for(var i=0; i<items.length; i++){
			to += parseFloat(items[i]['total_order']);
			ta += parseFloat(items[i]['total_amount']);
			tcomp += parseFloat(items[i]['completed']);
			tcomp_a += parseFloat(items[i]['completed_amount']);
			tcan += parseFloat(items[i]['cancelled']);
			tcan_a += parseFloat(items[i]['cancelled_amount']);	
			
			
		   html += "<tr><td>"+items[i]['store_name']+"</td><td class=\"text-center\">"+items[i]['total_order']+"</td><td class=\"text-right\">"+items[i]['total_amount']+"</td><td class=\"text-center\">"+items[i]['completed']+"</td><td class=\"text-right\">"+items[i]['completed_amount']+"</td><td class=\"text-center\">"+items[i]['cancelled']+"</td><td class=\"text-right\">"+items[i]['cancelled_amount']+"</td></tr>";
		}
		
		html += "<tr style=\"font-weight: bold;\"><td>Web Total ("+currency+")</td><td class=\"text-center\">"+to+"</td><td class=\"text-right\">"+ta.toFixed(2)+"</td><td class=\"text-center\">"+tcomp+"</td><td class=\"text-right\">"+tcomp_a.toFixed(2)+"</td><td class=\"text-center\">"+tcan+"</td><td class=\"text-right\">"+tcan_a.toFixed(2)+"</td></tr>";
		
		
		 html += "<tr><td colspan='7'>&nbsp;</td></tr>";
		 html += "<tr><td colspan='7' style='background-color:#ddd;'><strong>Sales Invoice Data</strong></td></tr>";
		var sto = 0;
		var sta = 0;
		var stcomp = 0;
		var stcomp_a = 0;
		var stcan = 0;
		var stcan_a = 0;
		
		var salesItem = webSalesItem['website_sales'];
		for(var i=0; i<salesItem.length; i++){
			sto += parseFloat(salesItem[i]['total_order']);
			sta += parseFloat(salesItem[i]['total_amount']);
			stcomp += parseFloat(salesItem[i]['completed']);
			stcomp_a += parseFloat(salesItem[i]['completed_amount']);
			stcan += parseFloat(salesItem[i]['cancelled']);
			stcan_a += parseFloat(salesItem[i]['cancelled_amount']);	
			
			
		   html += "<tr><td>"+salesItem[i]['store_name']+"</td><td class=\"text-center\">"+salesItem[i]['total_order']+"</td><td class=\"text-right\">"+salesItem[i]['total_amount']+"</td><td class=\"text-center\">"+salesItem[i]['completed']+"</td><td class=\"text-right\">"+salesItem[i]['completed_amount']+"</td><td class=\"text-center\">"+salesItem[i]['cancelled']+"</td><td class=\"text-right\">"+salesItem[i]['cancelled_amount']+"</td></tr>";
		}
		
		
		 html += "<tr style=\"font-weight: bold;\"><td>Sales Total ("+currency+")</td><td class=\"text-center\">"+sto+"</td><td class=\"text-right\">"+sta.toFixed(2)+"</td><td class=\"text-center\">"+stcomp+"</td><td class=\"text-right\">"+stcomp_a.toFixed(2)+"</td><td class=\"text-center\">"+stcan+"</td><td class=\"text-right\">"+stcan_a.toFixed(2)+"</td></tr>";
		 
		 html += "<tr><td colspan='7'>&nbsp;</td></tr>";
		 html += "<tr style=\"font-weight: bold;\"><td>Grand Total ("+currency+")</td><td class=\"text-center\">"+(to+sto)+"</td><td class=\"text-right\">"+(ta+sta).toFixed(2)+"</td><td class=\"text-center\">"+(tcomp+stcomp)+"</td><td class=\"text-right\">"+(tcomp_a+stcomp_a).toFixed(2)+"</td><td class=\"text-center\">"+(tcan+stcan)+"</td><td class=\"text-right\">"+(tcan_a+stcan_a).toFixed(2)+"</td></tr>";
	}
	else{
		html = "<tr><td class=\"text-center\" colspan=\"7\">No record found</td></tr>";
	}	
	
	$("#table_weborder tbody").html(html);
	
}

</script>
