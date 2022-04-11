<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?php echo $formHeading; ?></strong> <small>Form</small> 
		<div class="card-actions">
		<a id="addnewsellrecord" class="card-header-action" title="Add new sell record" href="#">
		<i class="fa fa-plus"></i>
		</a>
		</div>
	  </div>
      <form id="addprocessproductalerecordform" name="addprocessproductalerecordform" style="display:none;">
        <div class="card-block">
			<div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_email">Customer Email<sup>*</sup></label>
                <input class="form-control" id="customer_email" name="customer_email" maxlength="50" placeholder="Enter customer Email id"  onkeyup="getDropdown(this, 'Customer<=>customer_email',true)" type="email" value="">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_phone">Customer Phone Number<sup>*</sup></label>
                <input class="form-control" id="customer_phone" name="customer_phone" maxlength="20" placeholder="Enter customer phone number" type="tel" value="">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_type_id">Customer Type<sup>*</sup></label>
                <select id="customer_type_id" name="customer_type_id" class="form-control" size="1">
                  <?php
                $CustomerType = new CustomerType(0);
				echo $CustomerType->getOptions(isset($customer_type_id)?$customer_type_id:"0");
				?>
                </select>
              </div>
            </div>
          </div>
          <!--/row-->
          
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_fname">Customer First Name<sup>*</sup></label>
                <input class="form-control" id="customer_fname" name="customer_fname" maxlength="50" placeholder="Enter customer first name" type="text" value="">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_lname">Customer Last Name<sup>*</sup></label>
                <input class="form-control" id="customer_lname" name="customer_lname" maxlength="50" placeholder="Enter customer Last name" type="text" value="">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_company">Company Name </label>
                <input class="form-control" id="customer_company" name="customer_company" maxlength="150" placeholder="Enter Company name" type="text" value="">
              </div>
            </div>
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="customer_email">Search Address Here</label>
                <div id="locationField">
                  <input id="autocomplete" class="inputbox form-control" placeholder="Enter Customer address"
                                         onFocus="geolocate()" type="text">
                  </input>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_street_number">Street Number<sup>*</sup></label>
                <input class="form-control" id="customer_address_street_number" name="customer_address_street_number" maxlength="100" placeholder="Enter Address street number" type="text" value="">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_route">Address Route<sup>*</sup></label>
                <input class="form-control" id="customer_address_route" name="customer_address_route" maxlength="100" placeholder="Enter Address route" type="text" value="">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_locality">Address Locality<sup></sup></label>
                <input class="form-control" id="customer_address_locality" name="customer_address_locality" maxlength="100" placeholder="Enter Address locality" type="text" value="">
              </div>
            </div>
          </div>
          <!--/row-->
          
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_administrative_area">State (Administrative Area)<sup>*</sup></label>
                <input class="form-control" id="customer_address_administrative_area" name="customer_address_administrative_area" maxlength="100" placeholder="Enter State name" type="text" value="">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_country">Country Name<sup>*</sup></label>
                <input class="form-control" id="customer_address_country" name="customer_address_country" maxlength="100" placeholder="Enter Country name" type="text" value="">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_postcode">PostCode <sup>*</sup></label>
                <input class="form-control input_text_upper" id="customer_address_postcode" name="customer_address_postcode" maxlength="10" placeholder="Enter postcode" type="text" value="">
              </div>
            </div>
            <input class="form-control" id="customer_address_geo_location" name="customer_address_geo_location" readonly="readonly" maxlength="100" type="hidden" value="">
          </div>
          <div class="row py-2 bg-primary">
			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="wpca_store_id">Purchase Store<sup>*</sup></label>
                <select id="wpca_store_id" name="wpca_store_id" class="form-control" size="1">
                  <?php
                $store = new Store(0);
				echo $store->getOptions(0);
				?>
                </select>
              </div>
            </div>		  
			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">				  
                <label for="wpca_sell_price">Selling Price<sup>*</sup></label>
				 <div class="input-group">
                <input class="form-control" id="wpca_sell_price" name="wpca_sell_price" maxlength="10" placeholder="Enter Selling Product price only" type="number" step="0.01" value=""><span class="input-group-addon">GBP</span></div>		  
              </div>
            </div>
            
			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">				  
                <label for="wpca_store_reference">Store Reference<sup>*</sup></label>
				 <div class="input-group">
                <input class="form-control" id="wpca_store_reference" name="wpca_store_reference" maxlength="30" placeholder="Enter Reference like order id" type="text" value=""><span class="input-group-addon">ORDER ID</span></div>		  
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
            <label for="wpca_sell_date">Selling Date/Time<sup>*</sup></label>
            <div class="input-group date">
              <input type='text' class="form-control" id="wpca_sell_date" name="wpca_sell_date" placeholder="YYYY-MM-DD" value="" />
              <span class="input-group-addon">
              <label style="margin-bottom:0px;" for="wpca_sell_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
              </span> </div>
            <script type="text/javascript">
				$(function () {
					$('#wpca_sell_date').datetimepicker({
						format: 'yyyy-mm-dd HH:ii P',
						autoclose:true,
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true,
						fontAwesome : true,
						showMeridian: true,
					});
				});
            </script> 
          </div>
            </div>
            
            
			</div>
		<div class="row bg-primary">
			<div class="col-sm-12">
             <div class="form-group">
                <label for="wpca_remark">Selling Remark<sup>*</sup></label>
                <textarea id="wpca_remark" name="wpca_remark" rows="4" class="form-control" placeholder="Enter selling remark like extra charge or shipping charges etc"></textarea>
              </div>
            </div>
          </div>
		  
		<div class="row bg-primary">
        <div class="col-xs-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label for="wpca_is_returned">For product return only</label>
            <br/>
            <label for="wpca_is_returned"><i class="fa fa-history fa-lg m-t-2"></i> &nbsp;is this Product returned ?</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="wpca_is_returned" value="1" name="wpca_is_returned" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4" id="wpca_returned_date_box" style="display:none;">
          <div class="form-group">
            <label for="wpca_returned_date">Returned Date<sup>*</sup></label>
            <div class="input-group date">
              <input class="form-control" id="wpca_returned_date" name="wpca_returned_date" placeholder="YYYY-MM-DD" value="" type="text">
              <span class="input-group-addon">
              <label style="margin-bottom:0px;" for="wpca_returned_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
              </span> </div>
            <script type="text/javascript">
                    $('#wpca_returned_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,
						endDate  : '<?php echo date('Y-m-d')?>',						
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
          </div>
        </div>
      </div>
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to save product Sale Information...?', 'addProductSalesHistory')" class="btn btn-sm btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i>
          <?=$btnText?>
          </span></button>
		  <?php if(isset($wc_process_id) && $wc_process_id!=0):?> 
			<a href="#"  data-toggle="modal" data-target="#appModal"  class="btn btn-primary btn-sm" onClick="openChatLogForm('<?=$wc_process_id?>|P', '<?=$wpca_process_code?> Log Report')">Comments</a>
		<?php endif;?>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"collection/saveprocessproductsalehistory";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="wpca_process_code" name="wpca_process_code" value="<?=isset($wpca_process_code)?$wpca_process_code:"0";?>"  />
		<input type="hidden" id="wpca_id" name="wpca_id" value="0"  />
        <input type="hidden" id="wpca_customer_id" name="wpca_customer_id" value=""  />
        <input type="hidden" id="wpca_customer_address_id" name="wpca_customer_address_id" value=""  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">
$("#wpca_is_returned").on("change", function(){
	if($(this).is(":checked")){
		$("#wpca_returned_date_box").show();
	}else{
		$("#wpca_returned_date_box").hide();
	}			
});  
function callExtraModule(eData, element)
{
	console.log(eData);
	if(element.id == "customer_email")
	{
		$("#customer_phone").val(eData.customer_phone);
		$("#customer_type_id").val(eData.customer_type_id);
		$("#customer_fname").val(eData.customer_fname);
		$("#customer_lname").val(eData.customer_lname);
		$("#customer_company").val(eData.customer_company);
		$("#customer_address_postcode").val(eData.customer_address_postcode);
		$("#customer_address_street_number").val(eData.customer_address_street_number);
		$("#customer_address_route").val(eData.customer_address_route);
		$("#customer_address_locality").val(eData.customer_address_locality);
		$("#customer_address_administrative_area").val(eData.customer_address_administrative_area);
		$("#customer_address_country").val(eData.customer_address_country);
		$("#customer_address_geo_location").val(eData.customer_address_geo_location);
	}
	else if(element.id == "searched_product_name")
	{
		$("#searched_product_price").val(eData.product_price);
		$("#searched_product_quantity").val(1);
	}
}

</script> 

<div class="row">
<div class="col-lg-12">

    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Product sale history </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>Image</th>
              <th>Customer</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">Store</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">Sold On</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">Return</th>
			  <th class="hidden-xs hidden-md hidden-sm visible-lg">Sell Price</th>
			  <th>Action</th>
            </tr>
          </thead>
          <tbody id="sellingrecords">
          	<?php 
			$ppsh = new ProcessProductSaleHistory(0);
			echo $ppsh->getRecords($wpca_process_code);
			?>
          </tbody>
        </table>
        
      </div>
    </div>
    </div>
  </div>
  
<script type="text/javascript">
var dataTable;
function addProductSalesHistory()
{
	var formFields	=	"customer_email, customer_phone, customer_type_id, customer_fname, customer_lname, customer_address_postcode, customer_address_street_number, customer_address_route, customer_address_administrative_area, customer_address_country, wpca_store_id, wpca_sell_price, wpca_sell_date";
	
	if(validateFields(formFields))
	{
		var data={
			action	:	$("#action").val(),
			wpca_is_returned : $("#wpca_is_returned:checked").val()
		};
		
		data = $.extend(data, $("#addprocessproductalerecordform").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Saving product selling record...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#sellingrecords").html(arr[2]);
					dataTable.draw();
				}
				message(arr[1],2000);
			}
		})	
	}
}
function viewSellDetail(wpca_id){	
	var data={
		action	:	'collection/getprocessproducthistorydetail'	,
		wpca_id :	wpca_id
	};
	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Getting sell details...");
			dissableSubmission();
		},		
		success:function(output){
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				//$record = arr[2];
				openHistoryForm(arr[2]);
				
			}
			message(arr[1],2000);
		}
	});	
}

function removeProcessProductSalesHistory(wpca_id){
	var data={
		action	:	'collection/removeprocessproductsalehistory'	,
		wpca_id :	wpca_id
	};
	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Getting sell details...");
			dissableSubmission();
		},		
		success:function(output){
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				dataTable.draw();
			}
			message(arr[1],2000);
		}
	});	
}

function openHistoryForm(arr){
	$.each( arr, function( key, value ) {
	  if(typeof ($("#"+key)) != 'undefined'){
		  		  
		  if(key == 'wpca_is_returned'){
			  if(value == 1){
				  $("#"+key).prop('checked', true);
				  $("#"+key).trigger("change");
			  }
		  }
		  else
			  $("#"+key).val(value);
	  }
	});
	$("#addprocessproductalerecordform").slideDown();
}
					 
dataTable = $("#tbldatatable").dataTable( {
  "columnDefs": [
    { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,3,4,5 ] }
  ]
} );

$("#addnewsellrecord").on("click", function(){
	$("#wpca_id").val(0);
	$("#addprocessproductalerecordform").slideDown();
});
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
  
  var componentResolver = {
	  street_number: 'customer_address_street_number',
	  route	:	'customer_address_route',
	  locality: 'customer_address_locality',
	  administrative_area_level_1:	'customer_address_administrative_area',
	  country: 'customer_address_country',
	  postal_code:	'customer_address_postcode'
	};

  function initAutocomplete() {
	// Create the autocomplete object, restricting the search to geographical
	// location types.
	autocomplete = new google.maps.places.Autocomplete(
		/** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
		{types: ['geocode']});

	// When the user selects an address from the dropdown, populate the address
	// fields in the form.
	autocomplete.addListener('place_changed', fillInAddress);
  }

  function fillInAddress() {
	// Get the place details from the autocomplete object.
	var place = autocomplete.getPlace();

	for (var component in componentResolver) {
	  document.getElementById(componentResolver[component]).value = '';
	  document.getElementById(componentResolver[component]).disabled = false;
	}

	// Get each component of the address from the place details
	// and fill the corresponding field on the form.
	
	
	for (var i = 0; i < place.address_components.length; i++) {
	  var addressType = place.address_components[i].types[0];
	  if (componentResolver[addressType]) {
		var val = place.address_components[i][componentForm[addressType]];
		//document.getElementById(addressType).value = val;route
		//if(addressType=='street_number')
		//val += (" "+place.address_components[i+1][componentForm['route']]);
		document.getElementById(componentResolver[addressType]).value = val;
	  }
	  document.getElementById("customer_address_geo_location").value = place.geometry.location;
	}
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