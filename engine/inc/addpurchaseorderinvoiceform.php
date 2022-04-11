<div class="row" id="purchase_order_form_container">
	<div class="col-sm-12 pt-1">
		<div class="row">
			<div class="col-sm-12">
				<div class="card" style="background-color:$wc_status_color_code;">
					<div class="card-block">
<div class="row">					
						<div class="col-sm-6 col-md-4 pt-1">
							<dt class="col-xs-5">Purchase Order :</dt>
							<dd class="col-xs-7">#<?php echo $po_code?></dd>
						</div>
						<div class="col-sm-6 col-md-4 pt-1">
							<dt class="col-xs-5">Store :</dt>
							<dd class="col-xs-7"><img alt="<?php echo $store_name?>" style="width: 80px;" src="<?php echo $app->imagePath($store_logo)?>" /></dd>
						</div>
						<div class="col-sm-6 col-md-4 pt-1">
							<dt class="col-xs-5">Supplier :</dt>
							<dd class="col-xs-7"><?php echo $supplier_name?></dd>
						</div>
						<div class="col-sm-6 col-md-4 pt-1">
							<dt class="col-xs-5">Order date :</dt>
							<dd class="col-xs-7"><?php echo dateView($po_order_date, 'DATE')?></dd>
						</div>
						<div class="col-sm-6 col-md-4 pt-1">
							<dt class="col-xs-5">Crew :</dt>
							<dd class="col-xs-7"><?php echo $po_crew?></dd>
						</div>
						<div class="col-sm-6 col-md-4 pt-1">
							<dt class="col-xs-5">Close status :</dt>
							<dd class="col-xs-7"><?php echo $po_is_closed ? "<span class='badge badge-danger'>Closed</span>":"<span class='badge badge-success'>Open</span>"?></dd>
						</div>
						<div class="col-sm-6 col-md-4 pt-1">
							<dt class="col-xs-5">Total Amount :</dt>
							<dd class="col-xs-7"> <?php echo $po_item_amount_total?> <?php echo $po_currency?></dd> 
						</div>
						<div class="col-sm-6 col-md-4 pt-1">
							<dt class="col-xs-5">Discount :</dt>
							<dd class="col-xs-7"><?php echo $po_amount_discount?> <?php echo $po_currency?></dd>
						</div>
						<div class="col-sm-6 col-md-4 pt-1">
							<dt class="col-xs-5">Balance amount :</dt>
							<dd class="col-xs-7"><?php echo $po_amount_discount?> <?php echo $po_currency?></dd>
						</div>
						
						</div>
					</div>
				</div>		
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<form id="addpurchaseorderinvoice" name="addpurchaseorderinvoice">
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
								<label for="pob_invoice_date">Invoice date<sup>*</sup>
								</label>
								<div class="input-group date">
									<input data-label="Invoice date" class="form-control" id="pob_invoice_date" name="pob_invoice_date" placeholder="YYYY-MM-DD" value="<?php echo isset($pob_invoice_date)?$pob_invoice_date:date('Y-m-d')?>" type="text">
										<span class="input-group-addon">
											<label style="margin-bottom:0px;" for="pob_invoice_date">
												<i class="fa fa-calendar fa-lg m-t-2"/>
											</label>
										</span>
									</div>
									<script type="text/javascript">
										$('#pob_invoice_date').datepicker({
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
								<label for="pob_due_date">Due date<sup>*</sup>
								</label>
								<div class="input-group date">
									<input data-label="Order date" class="form-control" id="pob_due_date" name="pob_due_date" placeholder="YYYY-MM-DD" value="<?php echo isset($pob_due_date)?$pob_due_date:date('Y-m-d')?>" type="text">
										<span class="input-group-addon">
											<label style="margin-bottom:0px;" for="pob_due_date">
												<i class="fa fa-calendar fa-lg m-t-2"/>
											</label>
										</span>
									</div>
									<script type="text/javascript">
										$('#pob_due_date').datepicker({
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
									<label for="pob_terms">Terms<sup></sup>
									</label>
									<input type="text" id="pob_terms" name="pob_terms" class="form-control" value="<?php echo isset($pob_terms)?$pob_terms:''?>" placeholder="Enter purchase order terms">
								</div>
							</div>				
							<div class="col-sm-12">
								<div class="form-group">
									<label for="pob_description">Description</label>
									<textarea data-label="Description" id="pob_description" rows="4" name="pob_description" class="form-control" placeholder="Enter purchase order invoice description"></textarea>
								</input>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="pob_mailing_address">Shipping address</label>
								<input data-label="mailing address" id="pob_mailing_address" name="pob_mailing_address" class="inputbox form-control" value="<?php echo isset($po_shipping_address)?$po_shipping_address:''?>" placeholder="Enter mailing address" onFocus="geolocate()" type="text">
								</input>
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
				</div>
				<div class="card-block">
					<div class="row">
						<div class="col-md-12">
							<table id="postitembox" class="table tbale-bordered tbale-striped mb-1">
								<thead>
									<tr>
										<th width="3%">#</th>
										<th width="12%">Item</th>
										<th width="12%">Make</th>
										<th width="12%">Model</th>
										<th>Description</th>
										<th width="5%">Received</th>
										<th width="7%">Balance</th>
										<th width="10%">Rate</th>
										<th width="6%">Vat</th>
										<th width="10%">Amount</th>
										<th width="3%">Remove</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
								<tfoot class="mt-2">
									<tr class="added_item_summary"></tr>
									<tr><td colspan="9" class=""><b><i class="fa fa-fw fa-info-circle text-info"></i> Information : </b><i class="fa fa-fw fa-square text-danger"></i> Item are Closed and Can't add on invoice. If you will add these item(s) System will remove from invoice automatically. <a class="btn_remove_closed_item btn btn-sm btn-info text-white">Remove all Completed or Closed items</a></td></tr>
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
								<label for="pob_amount_discount">Discount amount<sup></sup>
								</label>
								<input type="number" step="0.01" id="pob_amount_discount" name="pob_amount_discount" class="form-control pr-0" min="0" value="" placeholder="Enter purchase order discount amount">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group mt-3">
								<label>
									<i class="fa fa-square"/> Add items in collection stock <i class="fa fa-question-circle text-warning" data-placement="top" data-html="true" data-trigger="hover" data-toggle="popover-ajax" title="Information" data-content="If Checked, a new Collection for <strong><?php echo $supplier_name?></strong> will automatically created."></i>
								</label>
								<label class="switch switch-icon switch-pill switch-success pull-right" >
									<input class="switch-input add_items_in_collection_stock" id="add_items_in_collection_stock" value="1" name="add_items_in_collection_stock" type="checkbox">
									<span class="switch-label" data-on="Yes" data-off="No"/>
									<span class="switch-handle"/>
								</label>
							</div>
						</div>
					</div>
					
					<div class="row" id="collection_stock_container" style="display:none;">
					  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<div class="form-group">
						  <label for="wc_consignment_note_code">Consignment Note Code<sup></sup></label>
						  <input class="form-control" id="wc_consignment_note_code" name="wc_consignment_note_code" maxlength="50" placeholder="Enter Consignment Note Code" type="text" value="<?=isset($wc_consignment_note_code)?$wc_consignment_note_code:"";?>">
						</div>
					  </div>
					  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<div class="form-group">
						  <label for="wc_on_behalf_of_user">On Behalf of(if Any)<sup></sup></label>
						  <input class="form-control" id="wc_on_behalf_of_user" name="wc_on_behalf_of_user" maxlength="50" placeholder="Enter On Behalf of(if Any)" type="text" value="<?=isset($wc_on_behalf_of_user)?$wc_on_behalf_of_user:"";?>">
						</div>
					  </div>
					  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<div class="form-group">
						  <label for="wc_carrier_id">Carrier Service<sup>*</sup></label>
						  <select id="wc_carrier_id" name="wc_carrier_id" onchange="loadOptions('wc_vehicle_id', 'CarrierVehicle', this.value, 0)" class="form-control" size="1">
							<?php
							$Carrier = new Carrier(0);
							echo $Carrier->getOptions(isset($wc_carrier_id)?$wc_carrier_id:"0");
							?>
						  </select>
						</div>
					  </div>
					  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<div class="form-group">
						  <label for="wc_loading_time">Loading Time<sup></sup></label>
						  <div class="input-group clockpicker" data-placement="left" data-align="top">
							<input type="text" class="form-control" id="wc_loading_time" name="wc_loading_time" maxlength="9"  value="<?=isset($wc_loading_time)?$wc_loading_time:"";?>" placeholder="Loading time Hour : Minute">
							<span class="input-group-addon"> <span class="fa fa-clock-o fa-lg"></span> </span> </div>
						  <script type="text/javascript">
							$(document).ready(function(e) {
							   $('.clockpicker').clockpicker({placement: 'top', autoclose:true, donetext: 'Done'}); 
							});
							</script> 
						</div>
					  </div>
					  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<div class="form-group">
						  <label for="wc_help_member">Help member from Customer<sup></sup></label>
						  <input class="form-control" id="wc_help_member" name="wc_help_member" maxlength="50" placeholder="Members count" type="number" min="0" value="<?=isset($wc_help_member)?intval($wc_help_member):"0";?>">
						</div>
					  </div>
					  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<div class="form-group">
						  <label for="wc_manager_id">Assign Collection Manager<sup>*</sup></label>
						  <select id="wc_manager_id" name="wc_manager_id" class="form-control" size="1">
							<?php
							$wcm = new Employee(0);
							echo $wcm->getUserOption(5, isset($wc_manager_id)?$wc_manager_id:"0");
							?>
						  </select>
						</div>
					  </div>
					  
					  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="row">
						 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 data_col_toggle" id="data_toggle_vehicle_select_box">
							<div class="form-group">
							  <label for="wc_vehicle_id">Select Carrier Vehicle<sup>*</sup></label>
							  <a class="pull-right togle_option text-primary" style="cursor: pointer;" data-togle-id="data_toggle_vehicle_text_box">New Vehicle</a>
							  <select id="wc_vehicle_id" name="wc_vehicle_id" class="form-control" size="1">
								<?php
								$CarrierVehicle = new CarrierVehicle(0);
								echo $CarrierVehicle->getOptions(isset($wc_vehicle_id)?$wc_vehicle_id:"0");
								?>
							  </select>
							</div>
						  </div>  
						  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 data_col_toggle" id="data_toggle_vehicle_text_box" style="display:none;">
							<div class="form-group">
							  <label for="wc_drop_off_vehicle">Write Vehicle Number<sup>*</sup></label>
							  <a class="pull-right togle_option text-success" style="cursor: pointer;" data-togle-id="data_toggle_vehicle_select_box">Select Vehicle</a>
							  <input class="form-control" id="wc_drop_off_vehicle" onkeyup="getDropdown(this, 'Collection<=>wc_drop_off_vehicle')"  name="wc_drop_off_vehicle" maxlength="50" placeholder="Drop off Vehicle Number" type="text" value="<?=isset($wc_drop_off_vehicle)?$wc_drop_off_vehicle:"";?>">
							</div>
						  </div>             
						  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 data_col_toggle" id="data_toggle_driver_select_box">
							<div class="form-group">
							  <label for="wc_driver_id">Select Driver<sup>*</sup></label>
							  <a class="pull-right togle_option text-primary" style="cursor: pointer;" data-togle-id="data_toggle_driver_text_box">New Driver</a>
							  <select id="wc_driver_id" name="wc_driver_id" class="form-control" size="1">
								<?php
								$wcm = new Employee(0);
								echo $wcm->getUserOption(6, isset($wc_driver_id)?$wc_driver_id:"0");
								?>
							  </select>
							</div>
						  </div>
						  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 data_col_toggle" id="data_toggle_driver_text_box" style="display:none;">
							<div class="form-group">
							  <label for="wc_drop_off_driver">Write Driver name<sup>*</sup></label>
							  <a class="pull-right togle_option text-success" style="cursor: pointer;"  data-togle-id="data_toggle_driver_select_box">Select Driver</a>
							  <input class="form-control" id="wc_drop_off_driver" onkeyup="getDropdown(this, 'Collection<=>wc_drop_off_driver')"  name="wc_drop_off_driver" maxlength="100" placeholder="Drop off driver name" type="text" value="<?=isset($wc_drop_off_driver)?$wc_drop_off_driver:"";?>">
							</div>
						  </div>
						</div>
					  </div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-footer">
					<div class="row">
						<div class="col-sm-12">
							<button type="reset" class="btn btn-outline-danger btnformreset">
								<i class="fa fa-refresh m-t-2"/> Reset</button>
							&nbsp;
							<button type="button" id="btn_complaint_submit" onClick="confirmMessage.Set('Are you sure to save purchase order invoice...?', 'addPurchaseOrderInvoice');" class="btn btn-success mt-0 submission_handler_btn"><i class="fa fa-check"/> Save PO Invoice</button>
								
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
var no_item_addedd = '<tr class="no_item_added"><td class="text-center text-muted" colspan="'+($("#postitembox thead tr th").length)+'">No item added</td></tr>';
var items = <?php echo json_encode($wcItem->getList());?>;
var poItemsRecord = <?php echo json_encode($poItemsRecord);?>;
$(document).off('click', '.po_item_remove');
$(document).off('click', '.btnformreset');
$(document).off('click', '.btn_remove_closed_item');
$(document).off('click', '.togle_option');
$(document).off('click', '.add_items_in_collection_stock');
$(document).ready(function(){
	$(".togle_option").on("click", function(){
		$(this).parents(".data_col_toggle").hide();		
		$("#"+$(this).attr('data-togle-id')).show();
		if($(this).next(".form-control").is('input'))
			$(this).next(".form-control").val('');
		else
			$(this).next(".form-control").val(0);
	});
	
	$(".add_items_in_collection_stock").on("change", function(){
		if($(this).is(':checked')){
			$("#collection_stock_container").show();
		}
		else{
			$("#collection_stock_container").hide();
		}
	});
	
	$(document).on("change", ".pobi_item_quantity, .pobi_item_rate, .pobi_item_vat", function(){
		var row = $(this).closest('tr');
		var qty = parseFloat(row.find('.pobi_item_quantity').val());
		var rate = parseFloat(row.find('.pobi_item_rate').val());		
		var vat = parseFloat(row.find('.pobi_item_vat').val());		
		row.find('.pobi_item_amount').val(round((qty*rate) + ((qty*(rate*vat))/100), 2));
		updateItemSummary();
	});
	
	$(document).on('click', '.btn_remove_closed_item', function(e){
		e.preventDefault();		
		if($("#postitembox tbody tr.row_filled").length > 0){
			$("#postitembox tbody tr.row_filled").each(function(){
				$(this).remove();
			});
			autoAdjustRowNumber();
			updateItemSummary();
		}
		
	});	
	$(document).on('click', '.po_item_remove', function(e){
		e.preventDefault();
		$(this).closest('tr').remove();
		autoAdjustRowNumber();
		if($("#postitembox tbody tr").length == 0)
		$("#postitembox tbody").html(no_item_addedd);
	});	
	$(document).on('click', '.btnformreset', function(e){
		if(poItemsRecord != null){
			$("#postitembox tbody").html('');
			for(var i=0; i < poItemsRecord.length; i++){
				addItemRows(poItemsRecord[i]);
			}
		}
	});	
		
	if(poItemsRecord != null){
		for(var i=0; i < poItemsRecord.length; i++){
			addItemRows(poItemsRecord[i]);
		}
	}
});

function addItemRows(roecord){
	$("#postitembox tbody").append(getPoItemRows(roecord));
	autoAdjustRowNumber();
}

function updateItemSummary(){
	var items = $("#postitembox tbody tr:not(.po_item_remove)").length;
	var amount = 0.00;
	$('.pobi_item_amount').each(function() {
        amount += Number($(this).val());
    });
	$(".added_item_summary").html('<td class="text-center text-muted" colspan="'+($("#postitembox thead tr th").length)+'">Purchase order invoice has <b>'+items+'</b> item with sum amount <b>'+round(amount,2)+'<b></td>');	
}

function addPurchaseOrderInvoice(){
	var formFields	=	"pob_invoice_date,pob_due_date,pob_description,pob_mailing_address";
	
	if(validateFields(formFields))
	{
		
		if(!isItemAdded())
		{
			toastMessage("danger|Please add product on purchase order");
			return false;
		}
		var isFormFilled = true;
		
		$(".pobi_item").each(function(){
			if($(this).val() == 0){
				toastMessage('error|Item should be selected');
				$(this).focus();
				isFormFilled = false;
				return false;
			}
		});
		$(".pobi_description").each(function(){
			if($(this).val() == ""){
				toastMessage('error|All Description should be filled');
				$(this).focus();
				isFormFilled = false;
				return false;
			}
		});
		$(".pobi_qty").each(function(){
			if($(this).val() == 0){
				toastMessage('error|All Description should be filled');
				$(this).focus();
				isFormFilled = false;
				return false;
			}
		});
		$(".pobi_rate").each(function(){
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
		
		data = $.extend(data, $("#addpurchaseorderinvoice").serializeFormJSON());
		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Saving purchase order invoice...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					setTimeout(function(){Redirect('purchaseorderinvoiceist')}, 2000);
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

	var itemsHtml = '<select id="pobi_item_'+getRandomNumber()+'" name="pobi_item[]" size="1" class="pobi_item form-control">';
	for(var i=0; i<items.length; i++){
		if(data.poi_item_id == items[i]['wci_id'])
		itemsHtml += '<option '+(data.poi_item_id == items[i]['wci_id'] ? 'selected':'')+' value="'+items[i]['wci_id']+'">'+items[i]['wci_name']+'</option>';
	}
	
	itemsHtml += '<input type="hidden" name="pobi_poi[]" value="'+(data['poi_id'] || 0)+'"></select>';
	var rowInfo = 'class="'+((data['poi_is_closed'] == '1' || data['poi_quantity_received'] == data['poi_quantity_total']) ? 'bg-danger row_filled' : '')+'" title="'+((data['poi_is_closed'] == '1' || data['poi_quantity_received'] == data['poi_quantity_total']) ? 'Item is Closed. Can\'t add on invoice. If you will add this item System will remove this item from invoice' : 'Item is Open')+'"';
	return `<tr `+rowInfo+`>
				<td>#</td>
				<td><div class="form-group mb-0">`+itemsHtml+`</div></td>
				<td>
					<div class="form-group mb-0">
						<input type="text" name="pobi_item_make[]" class="form-control pobi_item_description" value="" placeholder="Item make"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="text" name="pobi_item_model[]" class="form-control pobi_item_model" value="" placeholder="Item model"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="text" name="pobi_item_description[]" class="form-control pobi_item_description" value="`+(data.poi_description || '')+`" placeholder="Item detail description"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0 text-center">
						`+(data['poi_quantity_received'] || 0)+` / `+(data['poi_quantity_total'] || 0)+`
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" min="0" name="pobi_item_quantity[]" class="form-control pobi_item_quantity pr-0" value="`+(data['poi_quantity_total'] - data['poi_quantity_received'] || 0)+`" max="`+(data['poi_quantity_total'] - data['poi_quantity_received'] || 0)+`" placeholder="Quantity"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" min="0" step="0.01" name="pobi_item_rate[]" class="form-control pobi_item_rate pr-0" value="`+(data['poi_rate'] || 0)+`" placeholder="Rate"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" min="0" step="0.01" name="pobi_item_vat[]" class="form-control pobi_item_vat pr-0" value="`+(data['poi_vat'] || 0)+`" placeholder="Vat"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" readonly min="0" step="0.01" name="pobi_item_amount[]" class="form-control pobi_item_amount pr-0" value="`+(data['poi_amount_total'] || 0)+`" placeholder="Amount"/>
					</div>
				</td>
				<td class="text-center">
					<a href="#" class="btn btn-danger po_item_remove"><i class="fa fa-trash"></i></a>
				</td>
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