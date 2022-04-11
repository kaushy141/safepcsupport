<div class="row" id="purchase_order_form_container">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<form id="addpurchaseorder" name="addpurchaseorder">
			<div class="card">
				<div class="card-header">
					<i class="fa fa-plus"/>
					<strong>
						<?=$formHeading?>
					</strong>
				</div>
				<div class="card-block">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group">
								<label for="po_suplier_id">Supplier<sup>*</sup>
								</label>
								<select data-step="1" data-intro="Select Supplier" data-label="Supplier" id="po_suplier_id" name="po_suplier_id" class="form-control" size="1">
									<?php $supplier = new Supplier(0);echo $supplier->getOptions(isset($po_suplier_id)?$po_suplier_id:"0");
?>
								</select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group">
								<label for="po_order_date">Order date<sup>*</sup>
								</label>
								<div class="input-group date">
									<input data-label="Order date" class="form-control" id="po_order_date" name="po_order_date" placeholder="YYYY-MM-DD" value="<?php echo isset($po_order_date)?$po_order_date:date('Y-m-d')?>" type="text">
										<span class="input-group-addon">
											<label style="margin-bottom:0px;" for="po_order_date">
												<i class="fa fa-calendar fa-lg m-t-2"/>
											</label>
										</span>
									</div>
									<script type="text/javascript">
										$('#po_order_date').datepicker({
										format: "yyyy-mm-dd",
										autoclose:true,
										endDate  : '<?php echo date('Y-m-d' )?>',
										daysOfWeekHighlighted: '0,6',
										todayHighlight:true
										});
									</script>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label for="po_store_id">Store<sup>*</sup>
									</label>
									<select data-step="2" data-intro="Store for which you are requesting purchase order" data-label="Store" id="po_store_id" name="po_store_id" class="form-control" size="1">
										<?php $store = new Store(0);echo $store->getOptions(isset($po_store_id)?$po_store_id:"0");
?>
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label for="po_currency">Currency<sup>*</sup>
									</label>
									<select data-step="3" data-intro="Currency in which your purchase order will be available. All calculation for purchase order will be based on selected currency" data-label="Order currency" id="po_currency" name="po_currency" class="form-control" size="1">
										<?php echo getCurrencyType(isset($po_currency)?$po_currency:null); ?>
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label for="po_shipping_via">Shipping via<sup></sup>
									</label>
									<input type="text" id="po_shipping_via" name="po_shipping_via" class="form-control" value="<?php echo isset($po_shipping_via)?$po_shipping_via:''?>" placeholder="Enter purchase order shipping via info">
									</input>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label for="po_crew">Crew members</label>
									<input type="text" id="po_crew" name="po_crew" class="form-control" value="<?php echo isset($po_crew)?$po_crew:''?>" placeholder="Enter purchase order crew members">
									</input>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label for="po_approved_by">Approved by</label>
									<input type="text" id="po_approved_by" name="po_approved_by" class="form-control" value="<?php echo isset($po_approved_by)?$po_approved_by:''?>" onkeyup="getDropdown(this, 'Employee<=>username', false)" placeholder="Enter purchase order approved by">
									</input>
								</div>
							</div>							
							<div class="col-sm-12">
								<div class="form-group">
									<label for="po_description">Description</label>
									<textarea data-step="4" data-intro="Details about purchase order you are going to place" data-label="Description" id="po_description" rows="4" name="po_description" class="form-control" placeholder="Enter purchase order description"><?php echo isset($po_description)?$po_description:''?></textarea>
								</input>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="po_shipping_address">Suplier warehouse address</label>
								<input data-step="5" data-intro="Supplier warehouse address to whoom your purchase order will be sent" data-label="Suplier warehouse address" id="po_shipping_address" name="po_shipping_address" class="inputbox form-control" value="<?php echo isset($po_shipping_address)?$po_shipping_address:''?>" placeholder="Enter shipping address" onFocus="geolocate()" type="text">
								</input>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group">
								<label>
									<i class="fa fa-check-circle"/> is Approved <a data-html="true" data-trigger="hover" data-toggle="popover" title="About purchase order Approved" data-content="Check will ensure deal confirmation with supplier. <br/>Once checked you can't revert. <br/>Item's quantity, price and vat details can't modify">
										<i class="fa fa-question-circle text-warning" data-placement="top" data-html="true" data-trigger="hover" data-toggle="popover-ajax" title="Information" data-content="If Checked, Purchase order and it's items will no longer available to update"/>
									</a>
								</label>
								
								<?php if(isset($po_is_approved) && $po_is_approved == 1){?>
								<span class="badge badge-success pull-right"> Approved</span>
								<?php }else{?>
								<label class="switch switch-icon switch-pill switch-success pull-right">
									<input class="switch-input po_is_approved" data-id="<?=isset($po_id)?$po_id:" 0";?>" id="po_is_approved" value="1" name="po_is_approved" type="checkbox" <?php echo (isset($po_is_approved) && $po_is_approved == 1) ? "checked=\" checked\"" :""?> >
										<span class="switch-label" data-on="Yes" data-off="No"/>
										<span class="switch-handle"/>
								</label>
									<?php }?>
									
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label>
										<i class="fa fa-square"/> is Closed <i class="fa fa-question-circle text-warning"  data-placement="top" data-html="true" data-trigger="hover" data-toggle="popover-ajax" title="Information" data-content="If Checked, Invoicing on this purchase order will be cloased for it's all items"/>
									</label>
									<label class="switch switch-icon switch-pill switch-success pull-right">
										<input class="switch-input po_is_closed" data-id="<?=isset($po_id)?$po_id:" 0";?>" id="po_is_closed" value="1" name="po_is_closed" type="checkbox" <?php echo (isset($po_is_closed) && $po_is_closed == 1) ? "checked=\" checked\"" :""?> >
											<span class="switch-label" data-on="Yes" data-off="No"/>
											<span class="switch-handle"/>
										</label>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4">
									<div class="form-group">
										<label>
											<i class="fa fa-circle"/> Active Status <i class="fa fa-question-circle text-warning"  data-placement="top" data-html="true" data-trigger="hover" data-toggle="popover-ajax" title="Information" data-content="Should be checked for available to use. Unchecked will lead to disapear this purchase order."/>
										</label>
										<label class="switch switch-icon switch-pill switch-success pull-right">
											<input class="switch-input" id="po_status" value="1" name="po_status" type="checkbox" <?php echo (!isset($po_status) || $po_status == 1) ? "checked=\" checked\"" :""?> >
												<span class="switch-label" data-on="Yes" data-off="No"/>
												<span class="switch-handle"/>
											</label>
										</div>
									</div>
								</div>
								
							</div>
							</div>
							<div class="card">
										<div class="card-header">
											<i class="fa fa-cart-plus"/>
											<strong>
												Item Details
											</strong>
											<div class="card-actions"> 
											<?php if(!isset($po_is_approved) || $po_is_approved == 0){?>
												<a data-step="6" data-intro="Click to add products on the purchase order" class="addpositem bg-info" data-title="Add Item" title="Add Item" ><i class="fa fa-plus fa-lg m-t-2"></i></a> 
											<?php }?>
											</div>
										</div>
										<div class="card-block">
											<div class="row">
												<div class="col-md-12">
													<table id="postitembox" class="table tbale-bordered tbale-striped mb-1">
														<thead>
															<tr>
																<th width="3%">#</th>
																<th width="12%">Item</th>
																<th>Description</th>
																<th width="8%">Qty</th>
																<th width="8%">Rate</th>
																<th width="8%">Vat</th>
																<th width="10%">Amount</th>
																<?php if(isset($po_id) && $po_id > 0){?>
																<th class="text-center" width="8%">Received</th>
																<th class="text-center" width="8%">Close</th>
																<?php }?>
																<?php if(!isset($po_is_approved) || $po_is_approved == 0){?>
																<th width="3%">Remove</th>
																<?php }?>
															</tr>
														</thead>
														<tbody>
															
														</tbody>
														<tfoot class="mt-2">
															<tr class="added_item_summary"></tr>
														</tfoot>
													</table>
												</div>
											</div>
										</div>
									</div>
							<div class="card">				
								<div class="card-block">
									<div class="row">
										<div class="col-xs-12 col-sm-6 col-md-4">
											<div class="form-group">
												<label for="po_amount_discount">Discount amount<sup></sup>
												</label>
												<input data-step="7" data-intro="Discount amount if got from supplier" type="number" step="0.01" id="po_amount_discount" name="po_amount_discount" class="form-control pr-0" min="0" value="<?php echo isset($po_amount_discount)?$po_amount_discount:'0'?>" placeholder="Enter purchase order discount amount">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
							<div class="card-footer">
								<div class="row">
									<div class="col-sm-12">
									<?php if(!isset($po_is_approved) || $po_is_approved == 0){?>
										<button type="reset" class="btn btn-outline-danger">
											<i class="fa fa-refresh m-t-2"/> Reset</button>
										&nbsp;
										<button type="button" id="btn_complaint_submit" onClick="confirmMessage.Set('Are you sure to save purchase order...?', 'addPurchaseOrder');" class="btn btn-success mt-0 submission_handler_btn">
											<i class="fa fa-check"/> Save Purchase Order</button>
									<?php }?>
											
										<?php if(isset($po_id) && $po_id > 0){?>
										  <div class="btn-group dropup">
											<button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action<span class="sr-only"></span></button>
											<div class="dropdown-menu dropdown-menu-left">
												
												<a class="dropdown-item redirect" href="<?php echo $app->basePath("purchaseorder/$po_id"); ?>"><i class="fa fa-refresh"></i> Reload</a>
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?php echo $po_id;?>|U', '<?php echo $po_code;?> Log Report')"><i class="fa fa-comments-o"></i> Log Comments</a>
											<?php if(isset($po_is_approved) && $po_is_approved == 1){?>
												<a class="dropdown-item" href="javascript:newWindow('<?php echo DOC::PO($po_id)?>');"><i class="fa fa-file-pdf-o text-danger"></i> Download </a>
											<?php }?>
												<a class="dropdown-item redirect" href="<?php echo $app->basePath("addpurchaseorderinvoice/".md5($po_id)); ?>"><i class="fa fa-plus-circle text-success"></i> Add Invoice</a>	
											
											</div>
										</div>
										
										<button type="button" onClick="confirmMessage.Set('Are you sure to create a copy of this purchase order...?', 'copyPurchaseOrder');" class="btn btn-success mt-0 submission_handler_btn">
											<i class="fa fa-check"/> Copy to New Purchase Order</button>
										  <?php }?>	
									</div>
								</div>
							</div>
							<input type="hidden" id="action" name="action" value="<?=isset($action)?$action:" ";?>"/>
							<input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"/>
							<input type="hidden" id="po_id" name="po_id" value="<?=isset($po_id)?$po_id:" 0";?>"/>
						</div>
					</form>
				</div>
			</div>
<script>
<?php  $wcItem = new WcItem(); ?>
updateItemSummary();
var no_item_addedd = '<tr class="no_item_added"><td class="text-center text-muted" colspan="'+($("#postitembox thead tr th").length)+'">No item added</td></tr>';
$("#postitembox tbody").html(no_item_addedd);

var items = <?php echo json_encode($wcItem->getList());?>;
var poItemsRecord = <?php echo json_encode($poItemsRecord);?>;
$(document).off('click', '.addpositem');
$(document).off('click', '.po_item_remove');
$(document).off('change', '.po_suplier_id');
$(document).ready(function(){	
	showMeIntro('purchase-order');
	$(document).on('click', '.addpositem', function(e){
		e.preventDefault();	
		addItemRows({});
	});
	
	$(document).on('keyup', '#po_approved_by', function(){
		
	});
	
	$(document).on('change', '#po_suplier_id', function(){
		$("#po_shipping_address").val($(this).find("option:selected").attr('data-address'));
	});
	
	$(document).on('click', '.po_item_remove', function(e){
		e.preventDefault();
		$(this).closest('tr').remove();
		autoAdjustRowNumber();
		if($("#postitembox tbody tr").length == 0)
		$("#postitembox tbody").html(no_item_addedd);
	});	
	
	$(document).on("change", ".po_qty, .po_rate, .po_vat", function(){
		var row = $(this).closest('tr');
		var qty = parseFloat(row.find('.po_qty').val());
		var rate = parseFloat(row.find('.po_rate').val());		
		var vat = parseFloat(row.find('.po_vat').val());		
		row.find('.po_amount').val(round((qty*rate) + ((qty*(rate*vat))/100), 2));
		updateItemSummary();
	});
	
	$(document).on("change", ".poi_is_closed", function(e){
		if( $(this).attr('data-id') != '0|0|0'){
			var data = {
							'action' 	: 'po/changepoitemclosestatus',
							'item_id'	:  $(this).attr('data-id'),
							'status'	:	$(this).is(':checked') ? 1 : 0
					   };
			
			$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
					beforeSend: function(){
				},		
				success:function(output){
					enableSubmission(output);
					var arr	=	JSON.parse(output);	
					if(arr[0]==200)
					{
						toastMessage(arr[1]);
					}
					
				}
			});
		}
	});
	
		
	if(poItemsRecord != null){
		for(var i=0; i < poItemsRecord.length; i++){
			addItemRows(poItemsRecord[i]);
		}
	}
});

function addItemRows(roecord){
	$("#postitembox tbody tr.no_item_added").remove();
	$("#postitembox tbody").append(getPoItemRows(roecord));
	autoAdjustRowNumber();
}

function updateItemSummary(){
	var items = $("#postitembox tbody tr:not(.po_item_remove)").length;
	var amount = 0.00;
	$('.po_amount').each(function() {
        amount += Number($(this).val());
    });
	$(".added_item_summary").html('<td class="text-center text-muted" colspan="'+($("#postitembox thead tr th").length)+'">Purchase order has <b>'+items+'</b> item with sum amount <b>'+round(amount,2)+'<b></td>');	
}

function copyPurchaseOrder(){
	var data = {
		action : 'po/copytonewpurchaseorder',
		po_id  : $("#po_id").val()
	}
		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Creating new purchase order copy...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					setTimeout(function(){Redirect('purchaseorder/'+arr[2])}, 2000);
				}
				message(arr[1],2000);
			}
		});
}

function addPurchaseOrder(){
	var formFields	=	"po_suplier_id,po_order_date,po_store_id,po_currency,po_description,po_shipping_address";
	
	if(validateFields(formFields))
	{
		
		if(!isItemAdded())
		{
			toastMessage("danger|Please add product on purchase order");
			return false;
		}
		var isFormFilled = true;
		
		$(".po_item").each(function(){
			if($(this).val() == 0){
				toastMessage('error|Item should be selected');
				$(this).focus();
				isFormFilled = false;
				return false;
			}
		});
		$(".po_description").each(function(){
			if($(this).val() == ""){
				toastMessage('error|All Description should be filled');
				$(this).focus();
				isFormFilled = false;
				return false;
			}
		});
		$(".po_qty").each(function(){
			if($(this).val() == 0){
				toastMessage('error|All Description should be filled');
				$(this).focus();
				isFormFilled = false;
				return false;
			}
		});
		$(".po_rate").each(function(){
			if($(this).val() == 0){
				toastMessage('error|All Rates should be filled');
				$(this).focus();
				isFormFilled = false;
				return false;
			}
		});
		
		if(isFormFilled == false)
			return false;
		var data={
			action	:	$("#action").val()				
		};
		
		data = $.extend(data, $("#addpurchaseorder").serializeFormJSON());
		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Saving purchase order...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#po_id").val(arr[2]);
					setTimeout(function(){Redirect('purchaseorder/'+arr[2])}, 2000);
				}
				message(arr[1],2000);
			}
		});
	}
}
function isItemAdded(){
	return $("#postitembox").find('tbody tr').length;
}
function autoAdjustRowNumber(){
	var i=1;
	$("#postitembox").find('tbody tr').each(function(){
		$(this).find("td:eq(0)").html(`<input type="hidden" name="po_item_sr[]" value="`+i+`">` + i++);
	});
	updateItemSummary();
}

function getPoItemRows(data){	
	var itemsHtml = '<select id="po_item_'+getRandomNumber()+'" name="po_item[]" size="1" class="po_item form-control"><option val="0">- Select Item -</option>';
	for(var i=0; i<items.length; i++){
		itemsHtml += '<option '+(data.poi_item_id == items[i]['wci_id'] ? 'selected':'')+' value="'+items[i]['wci_id']+'">'+items[i]['wci_name']+'</option>';
	}
	itemsHtml += '</select>';
	return `<tr>
				<td>#</td>
				<td><div class="form-group mb-0">`+itemsHtml+`</div></td>
				<td>
					<div class="form-group mb-0">
						<input type="text" name="po_item_description[]" class="form-control po_description" value="`+(data.poi_description || '')+`" placeholder="Item detail description"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" min="0" name="po_item_qty[]" class="form-control po_qty pr-0" value="`+(data['poi_quantity_total'] || 0)+`" placeholder="Quantity"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" min="0" step="0.01" name="po_item_rate[]" class="form-control po_rate pr-0" value="`+(data['poi_rate'] || 0)+`" placeholder="Rate"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" min="0" step="0.01" name="po_item_vat[]" class="form-control po_vat pr-0" value="`+(data['poi_vat'] || 0)+`" placeholder="Vat"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" readonly min="0" step="0.01" name="po_item_amount[]" class="form-control po_amount pr-0" value="`+(data['poi_amount_total'] ? (parseFloat(data['poi_amount_total'])+parseFloat(data['poi_amount_vat'])) : 0)+`" placeholder="Amount"/>
					</div>
				</td>
				<?php if(isset($po_id) && $po_id > 0){?>
				<td class="text-center">
					<span>`+(data['poi_quantity_received'] || 0)+`</span>
					
				</td>
				<td class="text-center">
					<div class="form-group mb-0">
						<label class="switch switch-icon switch-pill switch-danger switch-info">
							<input class="switch-input poi_is_closed" data-id="`+(data['poi_id'] || 0)+`|`+(data['poi_po_id'] || 0)+`|`+(data['poi_item_id'] || 0)+`" value="1" name="poi_is_closed" type="checkbox" `+(data['poi_is_closed'] == 1 ? 'checked' : '')+`>
							<span class="switch-label" data-on="Yes" data-off="No"/>
							<span class="switch-handle"/>
						</label>
					</div>
				</td>
				<?php }?>
				<?php if(!isset($po_is_approved) || $po_is_approved == 0){?>
				<td class="text-center">
					<a href="#" class="btn btn-danger po_item_remove"><i class="fa fa-trash"></i></a>
				</td>
				<?php }?>
			</tr>`;
}
			
</script>
<script>
  // This example displays an address form, using the autocomplete feature
  // of the Google Places API to help users fill in the information.

  // This example requires the Places library. Include the libraries=places
  // parameter when you first load the API. For example:
  // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

  var placeSearch, autocomplete;
  var componentForm = {
	street_number: 'short_name',
	route: 'long_name',
	locality: 'long_name',
	administrative_area_level_1: 'long_name',
	country: 'long_name',
	postal_code: 'long_name'
  };


  function initAutocomplete() {
	// Create the autocomplete object, restricting the search to geographical
	// location types.
	autocomplete = new google.maps.places.Autocomplete(
		/** @type {!HTMLInputElement} */(document.getElementById('po_shipping_address')),
		{types: ['geocode']});

	// When the user selects an address from the dropdown, populate the address
	// fields in the form.
	//autocomplete.addListener('place_changed', fillInAddress);
  }


  // Bias the autocomplete object to the user's geographical location,
  // as supplied by the browser's 'navigator.geolocation' object.
  function geolocate() {
	if (navigator.geolocation) {
	  navigator.geolocation.getCurrentPosition(function(position) {
		var geolocation = {
		  lat: position.coords.latitude,
		  lng: position.coords.longitude
		};
		var circle = new google.maps.Circle({
		  center: geolocation,
		  radius: position.coords.accuracy
		});
		autocomplete.setBounds(circle.getBounds());
	  });
	}
  }
</script> 
<?php echo GOOGLE_MAP_API_SCRIPT;?>