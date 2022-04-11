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
								<select data-label="Supplier" id="po_suplier_id" name="po_suplier_id" class="form-control" size="1">
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
									<select data-label="Store" id="po_store_id" name="po_store_id" class="form-control" size="1">
										<?php $store = new Store(0);echo $store->getOptions(isset($po_store_id)?$po_store_id:"0");
?>
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label for="po_currency">Currency<sup>*</sup>
									</label>
									<select data-label="Order currency" id="po_currency" name="po_currency" class="form-control" size="1">
										<?php echo getCurrencyType(isset($po_currency)?$po_currency:null); ?>
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label for="po_shipping_via">Shipping via<sup></sup>
									</label>
									<input type="text" id="po_shipping_via" name="po_shipping_via" class="form-control" value="<?php echo isset($po_shipping_via)?$po_shipping_via:''?>" placeholder="Enter PO shipping via info">
									</input>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label for="po_crew">Crew</label>
									<input type="text" id="po_crew" name="po_crew" class="form-control" value="<?php echo isset($po_crew)?$po_crew:''?>" placeholder="Enter PO crew">
									</input>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label for="po_description">Description</label>
									<textarea data-label="Description" id="po_description" rows="2" name="po_description" class="form-control" placeholder="Enter PO description"><?php echo isset($po_title)?$po_title:''?></textarea>
								</input>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="customer_email">Shipping address</label>
								<input data-label="Shipping address" id="po_shipping_address" name="po_shipping_address" class="inputbox form-control" placeholder="Enter shipping address" onFocus="geolocate()" type="text">
								</input>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group">
								<label>
									<i class="fa fa-check-circle"/> is Approved <a data-html="true" data-trigger="hover" data-toggle="popover" title="About PO Approved" data-content="Check will ensure deal confirmation with supplier. <br/>Once checked you can't revert. <br/>Item's quantity, price and vat details can't modify">
										<i class="fa fa-question-circle text-warning"/>
									</a>
								</label>
								<label class="switch switch-icon switch-pill switch-success pull-right">
									<input class="switch-input" id="po_is_approved" value="1" name="po_is_approved" type="checkbox" <?php echo (isset($po_is_approved) && $po_is_approved == 1) ? "checked=\" checked\"" :""?> >
										<span class="switch-label" data-on="Yes" data-off="No"/>
										<span class="switch-handle"/>
									</label>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label>
										<i class="fa fa-square"/> is Closed <i class="fa fa-question-circle text-warning"/>
									</label>
									<label class="switch switch-icon switch-pill switch-success pull-right">
										<input class="switch-input" id="po_is_closed" value="1" name="po_is_closed" type="checkbox" <?php echo (isset($po_is_closed) && $po_is_closed == 1) ? "checked=\" checked\"" :""?> >
											<span class="switch-label" data-on="Yes" data-off="No"/>
											<span class="switch-handle"/>
										</label>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4">
									<div class="form-group">
										<label>
											<i class="fa fa-circle"/> Active Status <i class="fa fa-question-circle text-warning"/>
										</label>
										<label class="switch switch-icon switch-pill switch-success pull-right">
											<input class="switch-input" id="po_status" value="1" name="po_status" type="checkbox" <?php echo (isset($po_status) && $po_status == 1) ? "checked=\" checked\"" :""?> >
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
												<a class="addpositem" data-title="Add Item" title="Add Item" ><i class="fa fa-plus fa-lg m-t-2"></i></a> 
											</div>
										</div>
										<div class="card-block">
											<div class="row">
												<div class="col-md-12">
													<table id="postitembox" class="table tbale-bordered tbale-striped">
														<thead>
															<tr>
																<td width="3%">#</td>
																<td width="5%">Item</td>
																<td>Description</td>
																<td width="10%">Qty</td>
																<td width="10%">Rate</td>
																<td width="8%">Vat</td>
																<td width="12%">Amount</td>
																<td width="5%">Remove</td>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
							<div class="card">
							<div class="card-footer">
								<div class="row">
									<div class="col-sm-12">
										<button type="reset" class="btn btn-outline-danger">
											<i class="fa fa-refresh m-t-2"/> Reset</button>
										&nbsp;
										<button type="button" id="btn_complaint_submit" onClick="confirmMessage.Set('Are you sure to save purchase order...?', 'addPurchaseOrder');" class="btn btn-success mt-0 submission_handler_btn">
											<i class="fa fa-check"/> Save Purchase Order</button>
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
var maxLevel = 3;
var selectedRows = null;
$(document).off('click', '.addpositem');
$(document).off('click', '.po_category_selection');
$(document).off('click', '.po_item_remove');
$(document).off('click', '.add_new_category_btn');
$(document).off('click', '.categorylilevel');
$(document).off('click', '.popupselectcategory');
function getCategoryLevelBox(level){
	return '<div class="form-group"><ul class="list-group categoryulgroup" data-level="'+level+'"><li class="list-group-item d-flex text-center"><i class="fa fa-circle-notch fa-spin"></i> &nbsp; Loading...</li></ul></div>'
}
$(document).ready(function(){
	
	$(document).on('click', '.addpositem', function(e){
		e.preventDefault();		
		$("#postitembox tbody").append(getPoItemRows());
		autoAdjustRowNumber();
	});
	
	$(document).on('click', '.po_item_remove', function(e){
		e.preventDefault();
		$(this).closest('tr').remove();
		autoAdjustRowNumber();
	});	
	
	$(document).on('click', '.po_category_selection', function(e){
		e.preventDefault();
		selectedRows = $(this);
		setPopup(0, 'Select Category');
		var bodyHtml = `<div class="row">`;
		
		bodyHtml += '<div class="col-xs-12 col-sm-12 categorybox">';
		bodyHtml += getCategoryLevelBox(1);		
		bodyHtml += '</div>';		
		
		
		bodyHtml += '</div>';
		modal.Body(bodyHtml);
		modal.Footer('<button type="button" class="btn btn-success popupselectcategory disabled" >Select Category</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
		modal.Show();
		$("button.popupselectcategory").addClass('disabled');
		loadCategory(0,1);
	});	
	
	$(document).on("change", ".po_qty, .po_rate", function(){
		var row = $(this).closest('tr');
		var qty = parseFloat(row.find('.po_qty').val());
		var rate = parseFloat(row.find('.po_rate').val());		
		row.find('.po_amount').val(qty*rate);
	})
});

function loadCategory(id, level){
	var data={
			action	:	'po/loadpocategory',
			id		:	id,
			level	:	level
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("ul.categoryulgroup[data-level='"+level+"']").html(getCategoryListHtml(arr[2], id, level));
				}
				toastMessage(arr[1]);
			}
		});
}

function getCategoryListHtml(category, id, level){
	var html = '<li class="list-group-item d-flex text-center"><div class="input-group"><input class="form-control add_new_category" name="add_new_category" placeholder="Add new category" value="" type="text"><span class="input-group-addon bg-info text-white p-0"><a href="#" data-id="'+id+'"  data-level="'+level+'" class="btn btn-info add_new_category_btn" style="margin-bottom:0px;"><i class="fa fa-plus fa-lg"></i> ADD</a> </span> </div></li>'
	if(category.length > 0){
		for(var i=0; i < category.length; i++){
			html += '<li class="list-group-item d-flex text-center categorylilevel"><label><input data-label="'+category[i]['category_name']+'" data-level="'+level+'" data-id="'+category[i]['category_id']+'" type="radio" class="categoryliradio" value="'+category[i]['category_id']+'" name="category_radio_'+level+'"> &nbsp; '+category[i]['category_name']+'</label></li>';
		}
	}
	return html;
}

function addPurchaseOrder(){
	var formFields	=	"po_suplier_id, po_order_date, po_store_id, po_currency, po_description, po_shipping_address";
	
	if(validateFields(formFields))
	{
		
		if(!isItemAdded())
		{
			toastMessage("danger|Please add product on purchase order");
			return false;
		}
		var isFormFilled = true;
		$(".cataegoryids").each(function(){
			if($(this).text() == ""){
				toastMessage('error|All Category item should be selected');
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
					setTimeout(function(){Redirect(sitePath + 'salesinvoice/'+arr[2])}, 2000);
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
	})
}

$(document).on('click', '.popupselectcategory', function(e){
	var dataCategory = '';
	$("input.categoryliradio:checked").each(function() {
        //dataCategory = (dataCategory != '' ? ('<span class="badge badge-success">' + dataCategory + '</span> > ') : dataCategory) + $(this).attr('data-label');
		dataCategory = dataCategory + (dataCategory != '' ? ' <i class="fa fa-arrow-right"></i> ':'') +'<span class="badge badge-success">' + $(this).attr('data-label') + '</span>';
		
    });
	var lastselectedRadio = $("input.categoryliradio:checked").last().val();
	dataCategory+="<input type=\"hidden\" name=\"po_item_categorry[]\" value=\""+lastselectedRadio+"\">";
	selectedRows.closest('tr').find('.cataegoryids').html(dataCategory);
	modal.Hide();
});

$(document).on('change', '.categoryliradio', function(){
	$(this).toggleClass('selected');
	var category_parent = $(this).attr('data-id');
	var category_level = parseInt($(this).attr('data-level'))+1;
	if(category_level-1 == maxLevel){
			console.log(category_level);
		$("button.popupselectcategory").removeClass('disabled');
	}
	else{
		$("button.popupselectcategory").addClass('disabled');
	}
	
	if(category_level-1 < maxLevel){
		$("ul.categoryulgroup[data-level='"+(category_level)+"']").parent('.form-group').remove();
		$("ul.categoryulgroup[data-level='"+(category_level+1)+"']").parent('.form-group').remove();
		$("ul.categoryulgroup[data-level='"+(category_level+2)+"']").parent('.form-group').remove();
		$("ul.categoryulgroup[data-level='"+(category_level+3)+"']").parent('.form-group').remove();
		$('.categorybox').append(getCategoryLevelBox(category_level));
		loadCategory(category_parent,category_level);
	}
});
$(document).on('click', '.add_new_category_btn', function(e){
	e.preventDefault();
	var category_name = $(this).closest('.input-group').find('input.add_new_category').val();
	console.log(category_name);
	var category_parent = $(this).attr('data-id');
	var category_level = $(this).attr('data-level');
	if(category_name != ''){
		var data={
			'action'			:	'po/addpocategory',
			'category_name' 	: 	category_name,
			'category_level'	:	category_level,
			'category_parent' 	:	category_parent
		};
		console.log(data);
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					loadCategory(category_level-1,category_level);
				}
				message(arr[1],2000);
			}
		});
	}
	else{
		toastMessage('danger|Please enter category name');
	}
});

function getPoItemRows(){
	
	return `<tr>
				<td>#</td>
				<td><a href="#" class="btn btn-info po_category_selection"><i class="fa fa-list"></i></a></td>
				<td>
					<div class="form-group mb-0">
						<input type="text" name="po_item_description[]" class="form-control po_description" value="" placeholder="Item description"/>
						<span class="cataegoryids"></span>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" min="0" name="po_item_qty[]" class="form-control po_qty pr-0" value="0" placeholder="Quantity"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" min="0" step="0.01" name="po_item_rate[]" class="form-control po_rate pr-0" value="0" placeholder="Rate"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" min="0" step="0.01" name="po_item_vat[]" class="form-control po_vat pr-0" value="0" placeholder="Vat"/>
					</div>
				</td>
				<td>
					<div class="form-group mb-0">
						<input type="number" readonly min="0" step="0.01" name="po_item_amount[]" class="form-control po_amount pr-0" value="0" placeholder="Amount"/>
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