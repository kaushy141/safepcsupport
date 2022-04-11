<style>
.filterblock {
    position: absolute !important;
    z-index: 2;
    background: #FFF;
    border: 1px solid #999;
    box-shadow: 2px 3px 5px #999;
	top:40px;
}
</style>
<div class="row">
<?php if(isAdmin()){?>
  <div class="col-lg-12">
	  <div class="card">
		<form id="tablefilter" name="tablefilter">
			<div class="card-header"> <i class="fa fa-align-justify"></i> Filter 
			<a id="resetfilteration" href="#" class="pull-right">Reset</a></div>
			<div class="card-body">
				<div class="block-fluid table-sorting clearfix">
					<div class="row">
						<div class="col-xs-12" id="appliedfilter"></div>
					</div>
				</div>
				<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-lg-3">
						<div class="row">
									<?php 
									$websiteOrder = new WebsiteOrder();
									$field = 'web_order_website_id';
									?>
									<label class="col-md-12 col-form-label"><h5 class="filterheading">Website <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
									<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label filterblock">
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
					<div class="col-xs-12 col-sm-6 col-lg-3">
						<div class="row">
									<?php 
									$websiteOrder = new WebsiteOrder();
									$field = 'web_order_payment_method';
									?>
									<label class="col-md-12 col-form-label"><h5 class="filterheading">Payments <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
									<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label filterblock">
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
					<div class="col-xs-12 col-sm-6 col-lg-3">
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
					<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label filterblock">
					<?php
					foreach($records as $key=>$label){
					?>
					<div class="form-check checkbox filtercheckbox">
					<input class="form-check-input ml-0" data-label="<?php echo $label?>" name="duration" id="<?php echo $field.'_'.$key?>" type="radio" value="<?php echo $key?>">
					<label class="form-check-label" for="<?php echo $field.'_'.$key?>"> <?php echo $label?></label>
					</div>
					<?php
					}
					?>			
					</div>
				</div>
			</div>
            		<div class="col-xs-12 col-sm-6 col-lg-3">
						<div class="row">
									<?php 
									$websiteOrder = new WebsiteOrder();
									$field = 'web_order_assign_technician';
									?>
									<label class="col-md-12 col-form-label"><h5 class="filterheading">Under Technician <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
									<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label filterblock">
									<?php
									$records = $websiteOrder->getTechnicianFilteration(true);
									if(count($records)){
										$i=0;
										foreach($records as $item){
											$i++;
										?>
										<div class="form-check checkbox filtercheckbox">
										<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars(ucwords($item['user_fname']. $item['user_lname']));?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['user_id']);?>">
										<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><img alt="<?php echo htmlspecialchars(ucwords($item['user_fname']. $item['user_lname']));?>" class="img-avatar" src="<?php echo $app->basePath($item["user_image"])?>" height="24px"/> (<?php echo $item['record'];?>)</label>
										</div>
										<?php
										}
									}
									?>
                                    <div class="form-check checkbox filtercheckbox">
										<input class="form-check-input ml-0" data-label="Not Assigned" name="<?php echo $field?>" id="<?php echo $field.'_0'?>" type="checkbox" value="0">
										<label class="form-check-label" for="<?php echo $field.'_0'?>"><i class="fa fa-ban text-danger"></i> Not Assigned</label>
										</div>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php }?>
  <div class="col-lg-12 mt-1">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Pending at customer's level
		<div class="card-actions">
      <?php if(isAdmin()):?>
			<a class="card-header-action runfullfill" title="Run Order Fulfill Check" href="#">
				<i class="fa fa-search"></i>
			</a>
            <a id="exportxslx" class="card-header-action" title="Export to Excel File" href="#">
				<i class="fa fa-file-excel-o"></i>
			</a>
            <?php endif; ?>
        </div>
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>Icon</th>
              <th>Store</th>
              <th>OrderNo</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Qty</th>
              <th>status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">
$(".badge_product_image").off();
var data = {
								
		   };
	var coumnter = 0;
	var table;
	var formdata = {};
	if(app_filter_state == 1 && window.localStorage.getItem(window.location.href)){
		formdata = JSON.parse(window.localStorage.getItem(window.location.href));
		console.log(formdata);
		$('.form-check-input').each(function(){
			console.log($(this).attr('name'));
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
			action	:	"viewwebsiteundercustomerreviewlist",
			filter  :   formdata
		}
$(document).ready(function() {   
	
	
	$('.form-check-input').on('change', function(){
		formdata = $("#tablefilter").serializeFormJSON();		
		
		postdata = {
			action	:	"viewwebsiteundercustomerreviewlist",
			filter  :   formdata
		}
		//console.log(postdata);
		
		if(typeof table != 'undefined'){
			table.clear().draw();
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
	
	$("#exportxslx").on("click", function(e){
		var formdata = $("#tablefilter").serializeFormJSON();		
		var data = {
			action	:	"weborder/exportpendingordertoexcel",
			filter  :   formdata
		}
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);
				if(arr[0] == 200){
					window.open(sitePath+'report/exportpendingordertoexcel/'+arr[2], true);
				}
				message(arr[1]);
			}
		});
	});

} );


table = $('#<?php echo $TABEL_ID;?>').DataTable( {
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
		$(nRow).find('td').not(":last").not(":first").attr('onclick', "Redirect('"+aData[10]+"')"); // or whatever you choose to set as the id
	},
	"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[9]);},
		"order": [[ 3, 'desc' ]],
		columnDefs: [{ targets: [8], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 1,3,6,7 ] },
					 { className: "word_break", "targets": [4] }]
} );

$(document).ready(function(){
	// Handle click on checkbox to set state of "Select all" control
   $('#<?php echo $TABEL_ID;?> tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#example-select-all').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
   });
});

$(document).on("mouseover", ".badge_product_image", function(){
	$(this).parent('li').parent('ul').parent('td').find(".badge_product_image_box").remove();
	$(this).parent('li').parent('ul').parent('td').append("<div class='badge_product_image_box'><img src='"+$(this).attr('data-src')+"'></div>");
});
$(document).on("mouseout", ".badge_product_image", function(){
	$(this).parent('li').parent('ul').parent('td').find(".badge_product_image_box").remove();
});

</script>
