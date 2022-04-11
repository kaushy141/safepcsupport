<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?=$formHeading?></strong></div>
      <form id="addcustomer" name="addcustomer" enctype="multipart/form-data">
      <div class="card-block">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_fname">Customer First Name<sup>*</sup></label>
              <input class="form-control" id="customer_fname" name="customer_fname" maxlength="50" placeholder="Enter employee first name" type="text" value="<?=isset($customer_fname)?$customer_fname:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_lname">Customer Last Name<sup>*</sup></label>
              <input class="form-control" id="customer_lname" name="customer_lname" maxlength="50" placeholder="Enter employee Last name" type="text" value="<?=isset($customer_lname)?$customer_lname:"";?>">
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
              <label for="customer_email">Customer Email<sup>*</sup></label>
              <input class="form-control" id="customer_email" name="customer_email" maxlength="50" placeholder="Enter employee Email id" type="email" onblur="checkExistingEmail();" value="<?=isset($customer_email)?$customer_email:"";?>">              
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_phone">Customer Phone Number<sup></sup></label>
              <input class="form-control" id="customer_phone" name="customer_phone" maxlength="20" placeholder="Enter employee phone number" type="tel" value="<?=isset($customer_phone)?$customer_phone:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_image">Customer Photo <sup></sup></label>
              <div class="input-group">
                                    <span class="input-group-addon" style="padding:0px" id="image_uploader"><?=isset($customer_image)?$customer_image:'<i class="fa fa-camera fa-lg"></i>'?></span>
                                    <input class="form-control" id="customer_image" name="customer_image" style="padding-bottom: 4px; padding-top: 4px;" maxlength="100" value="" type="file" onchange="uploadFile(this.name);">
                                </div>
            </div>
          </div>
          
          <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_company">Company Name </label>
                <input class="form-control" id="customer_company" name="customer_company" maxlength="150" placeholder="Enter Company name" type="text" value="<?=isset($customer_company)?$customer_company:"";?>">
              </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="customer_tax_number">Tax Id/Vat Number </label>
                <input class="form-control" id="customer_tax_number" name="customer_tax_number" maxlength="50" placeholder="Enter Tax Id/Vat Number" type="text" value="<?=isset($customer_tax_number)?$customer_tax_number:"";?>">
              </div>
            </div>
			
			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class="form-group">
					<label for="customer_trade_licence_file">Trade licence(PDF)<sup></sup></label>
						<div class="input-group">
							<span class="input-group-addon" style="padding:0px"><?php echo (isset($customer_trade_licence) && $customer_trade_licence !='') ? ("<a target=\"_blank\" download href=\"".$app->basePath($customer_trade_licence)."\"><i class=\"fa fa-download fa-lg\"></i></a>"):"<i class=\"fa fa-file-pdf fa-lg\"></i>"?></span>
						<input class="form-control" type="file" name="customer_trade_licence_file" id="customer_trade_licence_file" onchange="filesUpload('customer_trade_licence_file');" />
				  <input type="hidden" name="customer_trade_licence" id="customer_trade_licence" value="" />
				  <span class="file_uploader"></span>
				</div>
				</div>
			  </div>
          
        </div>
        <!--/row-->
                
        <!--/row-->
        <div class="row">
          <div class="col-sm-8">
            <div class="form-group">
              <label for="">Search Address Here</label>
              <div id="locationField">
                <input id="autocomplete" class="inputbox form-control" placeholder="Search Customer address"
                                         onFocus="geolocate()" type="text">
                </input>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_address_postcode">PostCode <sup></sup></label>
              <input class="form-control" id="customer_address_postcode" name="customer_address_postcode" maxlength="10" placeholder="Enter postcode" type="text" value="<?=isset($customer_address_postcode)?$customer_address_postcode:"";?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_address_street_number">Street Number<sup></sup></label>
              <input class="form-control" id="customer_address_street_number" name="customer_address_street_number" maxlength="100" placeholder="Enter Address street number" type="text" value="<?=isset($customer_address_street_number)?$customer_address_street_number:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_address_route">Address Route<sup></sup></label>
              <input class="form-control" id="customer_address_route" name="customer_address_route" maxlength="100" placeholder="Enter Address route" type="text" value="<?=isset($customer_address_route)?$customer_address_route:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_address_locality">Address Locality<sup></sup></label>
              <input class="form-control" id="customer_address_locality" name="customer_address_locality" maxlength="100" placeholder="Enter Address locality" type="text" value="<?=isset($customer_address_locality)?$customer_address_locality:"";?>">
            </div>
          </div>
        </div>
        <!--/row-->
        
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_address_administrative_area">State (Administrative Area)<sup></sup></label>
              <input class="form-control" id="customer_address_administrative_area" name="customer_address_administrative_area" maxlength="100" placeholder="Enter State name" type="text" value="<?=isset($customer_address_administrative_area)?$customer_address_administrative_area:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_address_country">Country Name<sup></sup></label>
              <input class="form-control" id="customer_address_country" name="customer_address_country" maxlength="100" placeholder="Enter Country name" type="text" value="<?=isset($customer_address_country)?$customer_address_country:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="customer_address_geo_location">GEO Location</label>
              <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-globe fa-lg m-t-2"></i></span>
                                    <input class="form-control" id="customer_address_geo_location" name="customer_address_geo_location" readonly="readonly" maxlength="100" type="text" value="<?=isset($customer_address_geo_location)?$customer_address_geo_location:"";?>">
                                </div>
            </div>
          </div>
        </div>
         
		<div class="row">
			<div class="col-sm-12">
			  <div class="form-group">
				<label for="customer_remark">Customer Reamrk<sup>*</sup></label>
				<textarea id="customer_remark" name="customer_remark" rows="4" class="form-control" placeholder="Enter customer remark"><?=isset($customer_remark)?$customer_remark:"";?></textarea>
			  </div>
			</div>
		</div>
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
      <button type="button" onClick="addComplaint();" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> Save Customer </button>
      
    </div>
    	<input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addcustomer";?>"  />
        <input type="hidden" id="customer_id" name="customer_id" value="<?=isset($customer_id)?$customer_id:"0";?>"  />
        <input type="hidden" id="customer_address_id" name="customer_address_id" value="<?=isset($customer_address_id)?$customer_address_id:"0";?>"  />
      </form>
    </div>
    
  </div>
</div>
<?php if($addresses){?>
<!--/col--> 
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i>
        Customer Address        
      </div>
      <div class="card-block">
        <div class="row collapse in" aria-expanded="true" id="collection_item_id_boxb">
          <div class="col-md-12">
            <div class="row" style="margin-left:-5px; margin-right:-5px;"> 
            
            <?php foreach($addresses as $_address){?>                        
              <div class="col-xs-12 col-sm-6  col-md-4 col-lg-3">
                <div class="card">
                  <div class="card-body p-1">
                    <div class="text-value"><?php echo $_address['customer_address_street_number']?></div>
                    <div class="text-value"><?php echo $_address['customer_address_route']?>, <?php echo $_address['customer_address_locality']?></div>		
                    <div class="text-value"><?php echo $_address['customer_address_administrative_area']?>, <?php echo $_address['customer_address_country']?></div>
                    <div class="text-value"><?php echo $_address['customer_address_postcode']?></div>			
                    <div class="text-value"><small class="text-muted"><?php echo dateView($_address['customer_address_created_date'], 'FULL')?></small></div>
                    <div class="text-value text-right">
                    	<a data-value='<?php echo json_encode($_address)?>' type="button" data-id="<?php echo $_address['customer_address_id']?>, <?php echo $_address['customer_address_locality']?>" class="btn btn-outline-success btn-sm btn_customer_address">Update</a>
                    </div>
                    
                  </div>
                </div>
              </div>
            <?php }?>               
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/col-->
<?php }?>
<?php if($consolidate){?>
<!--/col--> 
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i>
        Customer Consolidate        
      </div>
      <div class="card-block">
        <div class="row collapse in" aria-expanded="true" id="collection_item_id_boxb">
          <div class="col-md-12">
            <div class="row" style="margin-left:-5px; margin-right:-5px;">             
              <div class="col-xs-12 col-sm-6  col-md-4 col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="p-2 d-flex align-items-center"> <i class="icon-wrench bg-primary p-3 fa-2x mr-2"></i>
                      <div class="text-center">
                        <div class="fa-2x text-primary">RMA</div>
                        <div class="text-muted text-uppercase font-weight-bold fa-2x"><?php echo $consolidate['rma']?></div>
                      </div>
                    </div>
                    <!--<div class="px-3 pb-2"> <a href="#" class="data_item_btn btn btn-default btn-block">View</a> </div>-->
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-6  col-md-4 col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="p-2 d-flex align-items-center"> <i class="icon-screen-tablet bg-primary p-3 fa-2x mr-2"></i>
                      <div class="text-center">
                        <div class="fa-2x text-primary">Collection</div>
                        <div class="text-muted text-uppercase font-weight-bold  fa-2x"><?php echo $consolidate['collection']?></div>
                      </div>
                    </div>
                    <!--<div class="px-3 pb-2"> <a href="#" class="data_item_btn btn btn-default btn-block">View</a> </div>-->
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-6  col-md-4 col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="p-2 d-flex align-items-center"> <i class="icon-wallet bg-primary p-3 fa-2x mr-2"></i>
                      <div class="text-center">
                        <div class="fa-2x text-primary">Invoice</div>
                        <div class="text-muted text-uppercase font-weight-bold  fa-2x"><?php echo $consolidate['invoice']?></div>
                      </div>
                    </div>
                    <!--<div class="px-3 pb-2"> <a href="#" class="data_item_btn btn btn-default btn-block">View</a> </div>-->
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-6  col-md-4 col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="p-2 d-flex align-items-center"> <i class="fa fa-registered bg-primary p-3 fa-2x mr-2"></i>
                      <div class="text-center">
                        <div class="fa-2x text-primary">Refund</div>
                        <div class="text-muted text-uppercase font-weight-bold  fa-2x"><?php echo $consolidate['refund']?></div>
                      </div>
                    </div>
                    <!--<div class="px-3 pb-2"> <a href="#" class="data_item_btn btn btn-default btn-block">View</a> </div>-->
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-6  col-md-4 col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="p-2 d-flex align-items-center"> <i class="icon-handbag bg-primary p-3 fa-2x mr-2"></i>
                      <div class="text-center">
                        <div class="fa-2x text-primary">Orders</div>
                        <div class="text-muted text-uppercase font-weight-bold  fa-2x"><?php echo $consolidate['orders']?></div>
                      </div>
                    </div>
                    <!--<div class="px-3 pb-2"> <a href="#" class="data_item_btn btn btn-default btn-block">View</a> </div>-->
                  </div>
                </div>
              </div>
                            
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/col-->
<?php }?>

<script type="text/javascript">
$(document).ready(function(e) {
    $(".btn_customer_address").on("click", function(){
		$("#customer_address_id").val($(this).attr('data-id'));
		var address = JSON.parse($(this).attr('data-value'));	
		//console.log(address);
		$("#customer_address_id").val(address.customer_address_id);
		$("#customer_address_street_number").val(address.customer_address_street_number);
		$("#customer_address_route").val(address.customer_address_route);
		$("#customer_address_locality").val(address.customer_address_locality);
		$("#customer_address_administrative_area").val(address.customer_address_administrative_area);
		$("#customer_address_country").val(address.customer_address_country);
		$("#customer_address_postcode").val(address.customer_address_postcode);
		$("#customer_address_geo_location").val(address.customer_address_geo_location);
	});
});
function customer_trade_licence_file_path_callback(path){
	$("#customer_trade_licence").val(path);
}
function addComplaint()
{
	$("#complaint_product_condition_at_receiving")
	var formFields	=	"customer_fname, customer_lname, customer_type_id, customer_email";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val(),
			field_handler:"customer_image"				
		};
		
		data = $.extend(data, $("#addcustomer").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#customer_id").val(arr[2]);
					$("#customer_address_id").val(arr[3]);
				}
				message(arr[1]);
			}
		})	
	}
}

function checkExistingEmail()
{
	if(validateFields("customer_email"))
	{
		var data={
			action	:	'customer/iscustomeremailavailable',
			customer_id	:	$("#customer_id").val(),
			customer_email	:	$("#customer_email").val()
		};	 
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
					message("process|Connecting...", 0);
					dissableSubmission();
				},		
				success:function(output){
					enableSubmission(output);
					var arr	=	JSON.parse(output);	
					if(arr[0]==300)
					{
						$("#customer_email").val('');
					}
					message(arr[1], 2000);
			}
		})	
	}
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
	  //console.log(componentResolver);
	  //console.log(place);
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