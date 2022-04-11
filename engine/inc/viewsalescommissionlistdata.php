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
			Modal::load(array('SalesCommission'));
			$SalesCommission = new SalesCommission();
			$field = 'sales_commission_user_id';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Employee <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $SalesCommission->getEmployeeFilteration();
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
                      <canvas id="invoice_status_chart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
          <script>
		  <?php  
		  $sumData = SalesCommission::getTotalSalesCommission();
	  	  $_arrstatus = array('Total', 'Commission '.$sumData['percentage'].'%');
		  $_arrrecord = array($sumData['total'], $sumData['amount']);
		  ?>
		  var doughnutData = {
				labels: [<?="'".implode("', '", $_arrstatus)."'"?>],
				datasets: [{
					data: [<?=implode(', ', $_arrrecord)?>],
					backgroundColor: ['#4eaafc','#8abc51'],
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
      <div class="card-header"> <i class="fa fa-align-justify"></i>Sales Commission List 
      <div class="card-actions">
      <?php if(isAdmin()):?>
            <a class="redirect" data-title="Add Sales Commission" title="Add Sales Commission" href="<?php echo $app->siteUrl("salescommission");?>"><i class="icon-plus icons font-2xl d-block m-t-2"></i></a>
            <?php endif; ?>
        </div>
      </div>	  
       <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>#img</th>
			  <th>User</th>
              <th>Code</th>
              <th>Month</th>
              <th>Amount</th>
              <th>Date</th>
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
		action	:	"viewsalescommissionlist",
		filter  :   formdata
	}
$(document).ready(function() { 
	
	$('.form-check-input').on('change', function(){
		formdata = $("#tablefilter").serializeFormJSON();		
		
		postdata = {
			action	:	"viewsalescommissionlist",
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


$(document).on("click", ".commissionsummary", function(e){
	var sales_commission_batch = $(this).attr('data-commission-code');
	var rec_user_img = $(this).attr('data-user-image');
	var rec_user_name = $(this).attr('data-user-name');
		
	setPopup(sales_commission_batch, '<img class="img-circle" src="'+rec_user_img+'"> '+rec_user_name +' - #'+ sales_commission_batch);
	var bodyHtml = '';
	bodyHtml += '<div class="col-xs-12" id="commissionsummarydata">'+LOADING_HTML +'</div>';
	modal.Body(bodyHtml);
	modal.Footer('<button onclick="printCommisionList()" type="button" class="btn btn-default">Print</button> <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
	
	var data={
					action	:	'sales/salescommissionsummary',
					sales_commission_batch : sales_commission_batch
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
				innerHtml = '<table class="table table-bordered table-striped m-0">';
				innerHtml += '<tr><th>Invoice</th><th>Date</th><th>Invoice</th><th class="text-right">Comm.%</th><th class="text-right">Comm. Amount</th></tr>';
				var total_invo_amount = 0;
				var total_comm_amount = 0;
				if(arr[2].length > 0)
				{
					for(var i=0; i< arr[2].length; i++){
						total_invo_amount += parseFloat(arr[2][i]['sales_commission_invoice_amount']);
						total_comm_amount += parseFloat(arr[2][i]['sales_commission_amount']);
						innerHtml += '<tr><td>'+arr[2][i]['sales_invoice_number']+'</td><td>'+arr[2][i]['sales_invoice_date']+'</td><td>'+arr[2][i]['sales_commission_invoice_amount']+'</td><td class="text-right">'+arr[2][i]['sales_commission_percentage']+'%</td><td class="text-right">&pound;'+arr[2][i]['sales_commission_amount']+'</td></tr>';
					}
					innerHtml += '<tr><td colspan="2"><b>Total : '+arr[2].length+'</b></td><td><b>&pound;'+total_invo_amount.toFixed(2)+'</b></td><td class="text-center">-</td><td class="text-right"><b>&pound;'+total_comm_amount.toFixed(2)+'</b></td></tr>';
				}
				else					
					innerHtml += '<tr><td colspan="4">No Commission Record found</td></tr>';
				innerHtml += '</table>';
				$("#commissionsummarydata").html(innerHtml);
			}
			message(arr[1]);
		}
	});	
	
});

function printCommisionList(){
        
	var printHtml = window.open('', 'PRINT', 'height=400,width=600');

	printHtml.document.write('<html><head><link type="text/css" media="print" href="<?php echo $app->cssPath('bootstrap.min')?>" rel="stylesheet"></head><body>');
	$("#appModal").find(".modal-footer").hide();
	printHtml.document.write($("#appModal").find(".modal-content").html());
	printHtml.document.write('</body></html>');
	$("#appModal").find(".modal-footer").show();
	printHtml.document.close(); 
	printHtml.focus(); 

	printHtml.print();
	printHtml.close();

	return true;
    
  }

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
	//'fnCreatedRow': function (nRow, aData, iDataIndex) {
	//	$(nRow).find('td').not(":last").attr('onclick', "Redirect('"+aData[9]+"')"); // or whatever you choose to set as the id
	//},
	//"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[8]);},
		"order": [[ 5, 'desc' ]],
		columnDefs: [
					 { targets: [6], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 1,2,5] },
					],
} );


</script> 
