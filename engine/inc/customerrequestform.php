<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"><i class="fa fa-align-justify"></i> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small>        
      </div>
      <form id="addcomplaintcustomer" name="addcomplaintcustomer">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_email">Customer Email<sup>*</sup></label>
                <input class="form-control" id="customer_email" name="customer_email" maxlength="50" placeholder="Enter customer Email id"  onkeyup="getDropdown(this, 'Customer<=>customer_email',true)" type="email" value="<?=isset($customer_email)?$customer_email:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_phone">Customer Phone Number<sup>*</sup></label>
                <input class="form-control" id="customer_phone" name="customer_phone" maxlength="20" placeholder="Enter customer phone number" type="tel" value="<?=isset($customer_phone)?$customer_phone:"";?>">
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
                <input class="form-control" id="customer_fname" name="customer_fname" maxlength="50" placeholder="Enter customer first name" type="text" value="<?=isset($customer_fname)?$customer_fname:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_lname">Customer Last Name<sup>*</sup></label>
                <input class="form-control" id="customer_lname" name="customer_lname" maxlength="50" placeholder="Enter customer Last name" type="text" value="<?=isset($customer_lname)?$customer_lname:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_company">Company Name </label>
                <input class="form-control" id="customer_company" name="customer_company" maxlength="10" placeholder="Enter Company name" type="text" value="<?=isset($customer_company)?$customer_company:"";?>">
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
                <input class="form-control" id="customer_address_street_number" name="customer_address_street_number" maxlength="100" placeholder="Enter Address street number" type="text" value="<?=isset($customer_address_street_number)?$customer_address_street_number:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_route">Address Route<sup>*</sup></label>
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
                <label for="customer_address_administrative_area">State (Administrative Area)<sup>*</sup></label>
                <input class="form-control" id="customer_address_administrative_area" name="customer_address_administrative_area" maxlength="100" placeholder="Enter State name" type="text" value="<?=isset($customer_address_administrative_area)?$customer_address_administrative_area:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_country">Country Name<sup>*</sup></label>
                <input class="form-control" id="customer_address_country" name="customer_address_country" maxlength="100" placeholder="Enter Country name" type="text" value="<?=isset($customer_address_country)?$customer_address_country:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_postcode">PostCode <sup>*</sup></label>
                <input class="form-control" id="customer_address_postcode" name="customer_address_postcode" maxlength="10" placeholder="Enter postcode" type="text" value="<?=isset($customer_address_postcode)?$customer_address_postcode:"";?>">
              </div>
            </div>
            <input class="form-control" id="customer_address_geo_location" name="customer_address_geo_location" readonly="readonly" maxlength="100" type="hidden" value="<?=isset($customer_address_geo_location)?$customer_address_geo_location:"";?>">
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="complaint_description">Complaint Description<sup>*</sup></label>
                <textarea id="complaint_description" name="complaint_description" rows="4" class="form-control" placeholder="Enter Complaint description"><?=isset($complaint_description)?$complaint_description:"";?>
</textarea>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="complaint_problem_details">Complaint Problem Details<sup>*</sup></label>
                <textarea id="complaint_problem_details" name="complaint_problem_details" rows="4" class="form-control" placeholder="Enter Complaint Problem Details"><?=isset($complaint_problem_details)?$complaint_problem_details:"";?>
</textarea>
              </div>
            </div>
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="">Mark Product Problem(s)</label>
                <div class="col-sm-12">
                  <div class="row" id="app_not_working_problem_mark_box">
                    <?php
                $HardwareProblem = new HardwareProblem(0);
				$app_not_working_problem_mark_array = array();
				if(isset($app_not_working_problem_mark))
				$app_not_working_problem_mark_array = explode(",",$app_not_working_problem_mark);
				echo $HardwareProblem->getCheckbox("app_not_working_problem_mark",$app_not_working_problem_mark_array);
				?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_product_serial">Product Serial Number<sup>*</sup></label>
                <input class="form-control" id="complaint_product_serial" name="complaint_product_serial" maxlength="50" placeholder="Enter Product Serial Number" type="text" value="<?=isset($complaint_product_serial)?$complaint_product_serial:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_product_model">Product Model Number</label>
                <input class="form-control" id="complaint_product_model" name="complaint_product_model" maxlength="50" onkeyup="getDropdown(this, 'Complaint<=>complaint_product_model')" placeholder="Enter Product Model Number" type="text" value="<?=isset($complaint_product_model)?$complaint_product_model:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_product_password">Product Password</label>
                <input class="form-control" id="complaint_product_password" name="complaint_product_password" maxlength="50" placeholder="Enter Product Password" type="text" value="<?=isset($complaint_product_password)?$complaint_product_password:"";?>">
              </div>
            </div>
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_product_operating_system">Operating System</label>
                <input class="form-control" id="complaint_product_operating_system" name="complaint_product_operating_system"  onkeyup="getDropdown(this, 'Complaint<=>complaint_product_operating_system')" maxlength="100" placeholder="Enter Operating System name" type="text" value="<?=isset($complaint_product_operating_system)?$complaint_product_operating_system:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_product_antivirus">Product Antivirus</label>
                <input class="form-control" id="complaint_product_antivirus" name="complaint_product_antivirus" onkeyup="getDropdown(this, 'Complaint<=>complaint_product_antivirus')" maxlength="100" placeholder="Enter Product Antivirus" type="text" value="<?=isset($complaint_product_antivirus)?$complaint_product_antivirus:"";?>">
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_store_id">Select Purchase Store<sup>*</sup></label>
                <select id="complaint_store_id" name="complaint_store_id" class="form-control" size="1">
                  <?php
                $store = new Store(0);
				echo $store->getOptions(isset($complaint_store_id)?$complaint_store_id:"0");
				?>
                </select>
              </div>
            </div>
            
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="complaint_description">Mark Hardware not working</label>
                <div class="col-sm-12">
                  <div class="row">
                    <?php
                $HardwareType = new HardwareType(0);
				$complaint_product_hardware_not_working_array = array();
				if(isset($complaint_product_hardware_not_working))
				$complaint_product_hardware_not_working_array = explode(",",$complaint_product_hardware_not_working);
				echo $HardwareType->getCheckbox("complaint_product_hardware_not_working",$complaint_product_hardware_not_working_array);
				?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--/row-->
          
          <!--/row-->
          
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_is_backup">Product Backuped &nbsp; <i class="fa fa-history fa-lg m-t-2"></i></label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="complaint_is_backup" value="1" name="complaint_is_backup" type="checkbox" <?=(isset($complaint_is_backup) && $complaint_is_backup)?"checked":"";?> >
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_is_disk_provided">Disk Provided &nbsp; <i class="fa fa-hdd-o fa-lg m-t-2"></i></label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="complaint_is_disk_provided" value="1" name="complaint_is_disk_provided" type="checkbox" <?=(isset($complaint_is_disk_provided) && $complaint_is_disk_provided)?"checked":"";?>>
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_product_is_under_waranty">Under Waranty &nbsp; <i class="fa fa-umbrella fa-lg m-t-2"></i></label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="complaint_product_is_under_waranty" value="1" name="complaint_product_is_under_waranty" type="checkbox" <?=(isset($complaint_product_is_under_waranty) && $complaint_product_is_under_waranty)?"checked":"";?>>
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
          </div>
          <!--/row-->
          
          

          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
          <button type="button" id="btn_complaint_submit" onClick="addComplaintCustomer();" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span> COMPLAINT </button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"repair/addcomplaintcustomer";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="complaint_id" name="complaint_id" value="<?=isset($complaint_id)?$complaint_id:"0";?>"  />
        <input type="hidden" id="customer_id" name="customer_id" value="<?=isset($customer_id)?$customer_id:"0";?>"  />
        <input type="hidden" id="customer_address_id" name="customer_address_id" value="<?=isset($customer_address_id)?$customer_address_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript" language="javascript">

function addComplaintCustomer()
{
	var formFields	=	"customer_email, customer_phone, customer_type_id, customer_fname, customer_lname, customer_address_postcode, customer_address_street_number, customer_address_route, customer_address_administrative_area, customer_address_country, complaint_description, complaint_problem_details, complaint_product_serial";
	
	if(validateFields(formFields))
	{
		var app_not_working_problem_mark = [];
		$(".app_not_working_problem_mark").each(function(index, element) {
            if($(this).is(":checked"))
			app_not_working_problem_mark.push($(this).val());
			
        });
		
		var complaint_product_hardware_not_working = [];
		$(".complaint_product_hardware_not_working").each(function(index, element) {
            if($(this).is(":checked"))
			complaint_product_hardware_not_working.push($(this).val());
			
        });
		var data={
			action	:	$("#action").val()				
		};
		
		data = $.extend(data, $("#addcomplaintcustomer").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Submitting Complaint Request...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					Redirect('viewcomplaintrequest');
				}
				message(arr[1],2000);
			}
		})	
	}
	
}

function callExtraModule(eData)
{
	$("#customer_phone").val(eData.customer_phone);
	$("#customer_type_id").val(eData.customer_type_id);
	$("#customer_fname").val(eData.customer_fname);
	$("#customer_lname").val(eData.customer_lname);
	$("#customer_address_postcode").val(eData.customer_address_postcode);
	$("#customer_address_street_number").val(eData.customer_address_street_number);
	$("#customer_address_route").val(eData.customer_address_route);
	$("#customer_address_locality").val(eData.customer_address_locality);
	$("#customer_address_administrative_area").val(eData.customer_address_administrative_area);
	$("#customer_address_country").val(eData.customer_address_country);
	$("#customer_address_geo_location").val(eData.customer_address_geo_location);
}

function openLogForm(id, title)
{
	setPopup(id, title);
	var bodyHtml = '<div class="col-md-12"><div class="row">';
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><label for="poplogtext">New Problem Detail<sup>*</sup></label><input class="form-control" id="problem_name" name="problem_name" maxlength="200" placeholder="Write Hardware Problem Type Name" type="text"></div>';
	bodyHtml +='</div>';
	bodyHtml +='</div></div>';
	modal.Body(bodyHtml);
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