<style>
.pac-container {
    z-index: 1051 !important;
}
</style>
<div id="signupPopup" class="modal open" role="dialog">
  <form name="modalformsignup" id="modalformsignup">
    <div class="modal-dialog">
      <div class="modal-content">
        <div id="modal-header" class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Create Support Account</h4>
        </div>
        <div id="signup-modal-notice" class="modal-notice popmsg" style="display:none;">
          <div class="card-inverse text-xs-center" style="padding:8px 5px;"> </div>
        </div>
        <div id="modal-body" class="modal-body">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_email">Email<sup>*</sup></label>
                <input class="form-control" id="customer_email" name="customer_email" maxlength="50" placeholder="Enter Email id" required="required" type="email" value="<?=isset($customer_email)?$customer_email:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_phone">Phone Number<sup>*</sup></label>
                <input class="form-control" id="customer_phone" name="customer_phone" maxlength="20" placeholder="Enter phone number" required="required" type="tel" value="<?=isset($customer_phone)?$customer_phone:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_type_id">Type<sup>*</sup></label>
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
                <label for="customer_fname">First Name<sup>*</sup></label>
                <input class="form-control" id="customer_fname" name="customer_fname" maxlength="50" placeholder="Enter First name" type="text" value="<?=isset($customer_fname)?$customer_fname:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_lname">Last Name<sup>*</sup></label>
                <input class="form-control" id="customer_lname" name="customer_lname" maxlength="50" placeholder="Enter Last name" type="text" value="<?=isset($customer_lname)?$customer_lname:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_postcode">PostCode <sup>*</sup></label>
                <input class="form-control" id="customer_address_postcode" name="customer_address_postcode" maxlength="10" placeholder="Enter postcode" type="text" value="<?=isset($customer_address_postcode)?$customer_address_postcode:"";?>">
              </div>
            </div>
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="customer_email">Search Address Here</label>
                <div id="locationField">
                  <input id="autocomplete" class="inputbox form-control" placeholder="Enter address"
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
                <label for="customer_address_administrative_area">State<sup>*</sup></label>
                <input class="form-control" id="customer_address_administrative_area" name="customer_address_administrative_area" maxlength="100" placeholder="Enter State name" type="text" value="<?=isset($customer_address_administrative_area)?$customer_address_administrative_area:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="customer_address_country">Country Name<sup>*</sup></label>
                <input class="form-control" id="customer_address_country" name="customer_address_country" maxlength="100" placeholder="Enter Country name" type="text" value="<?=isset($customer_address_country)?$customer_address_country:"";?>">
                <input type="hidden" name="customer_address_geo_location" id="customer_address_geo_location" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                <a href="javascript:refreshCaptcha();">Refresh</a> <br/>
                <img id="customer_captcha_code_file" src="<?php echo $app->basePath("captcha.php?mode=CUSTOMER-SIGNUP")?>" style="margin:6px 0px;" /> </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <div class="form-group">
                  
                    <label for="customer_captcha_code">Captcha</label>
                    <input class="form-control" id="customer_captcha_code" name="customer_captcha_code" maxlength="4" placeholder="Code" type="text" value="">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="modal-footer-btn-block" style="float:left"> </div>
          <input type="hidden" id="signupkeyid" name="signupkeyid" value="<?=$signup_page_id?>" />
          <button type="button" onClick="submitSignupForm()" class="btn btn-success">Submit</button>
          <button type="reset" class="btn btn-default" >Reset</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </form>
</div> 
<script type="text/javascript">
function submitSignupForm()
{	
	var customer_email	=	$("#customer_email").val().trim();
	var customer_phone	=	$("#customer_phone").val().trim();
	var customer_type_id	=	$("#customer_type_id").val().trim();
	var customer_fname	=	$("#customer_fname").val().trim();
	var customer_lname	=	$("#customer_lname").val().trim();
	var customer_address_postcode	=	$("#customer_address_postcode").val().trim();
	var customer_address_street_number	=	$("#customer_address_street_number").val().trim();
	var customer_address_route	=	$("#customer_address_route").val().trim();
	var customer_address_locality	=	$("#customer_address_locality").val().trim();
	var customer_address_administrative_area	=	$("#customer_address_administrative_area").val().trim();
	var customer_address_country	=	$("#customer_address_country").val().trim();
	var customer_captcha_code			= 	$("#customer_captcha_code").val().trim();	
	var keyid			= 	$("#signupkeyid").val().trim();
	if(customer_email==""){
		$("#customer_email").focus();
		popmessage("danger|Please fill Email.");
		return false;
	}
	if(customer_phone==""){
		$("#customer_phone").focus();
		popmessage("danger|Please fill phone.");
		return false;
	}
	if(customer_type_id==0){
		$("#customer_type_id").focus();
		popmessage("danger|Please fill customer type.");
		return false;
	}
	if(customer_fname==""){
		$("#forgot_fname").focus();
		popmessage("danger|Please enter first name.");
		return false;
	}
	if(customer_lname==""){
		$("#customer_email").focus();
		popmessage("danger|Please enter last name.");
		return false;
	}
	if(customer_address_postcode==""){
		$("#customer_address_postcode").focus();
		popmessage("danger|Please fill post code.");
		return false;
	}
	if(customer_address_street_number==""){
		$("#customer_address_street_number").focus();
		popmessage("danger|Please fill street number.");
		return false;
	}
	if(customer_address_locality==""){
		$("#customer_address_locality").focus();
		popmessage("danger|Please fill locality.");
		return false;
	}
	if(customer_address_route==""){
		$("#customer_address_route").focus();
		popmessage("danger|Please fill route name.");
		return false;
	}
	
	if(customer_address_administrative_area==""){
		$("#customer_address_administrative_area").focus();
		popmessage("danger|Please fill state name.");
		return false;
	}
	if(customer_address_country==""){
		$("#customer_address_country").focus();
		popmessage("danger|Please fill country.");
		return false;
	}
	if(customer_captcha_code==""){
		$("#customer_address_country").focus();
		popmessage("danger|Please fill country.");
		return false;
	}
	
	var data={
				action		:	'customersignup',
				keyid		:	keyid,							
			}
	data = $.extend(data, $("#modalformsignup").serializeFormJSON());
	$.ajax({type:'POST', data:data, url:'aouth.php', 
		
		beforeSend: function(){
			popmessage("warning|Connecting...");
		},		
		success:function(output){
			var arr	=	JSON.parse(output);
			popmessage(arr[1]);
			if(arr[0]==200)
			{
				$("#modalformsignup").reset();
			}
		}
	})			
}

function refreshCaptcha()
{
	$("#customer_captcha_code_file").attr('src','<?php echo $app->basePath("captcha.php?mode=CUSTOMER-SIGNUP&id=");?>'+Math.random());
}

$(document).ready(function(e) {
    $('#signupPopup').on('shown', function () {
    initializeMap();
	});
});

function initializeMap() {
        var mapOptions = {
            center: new google.maps.LatLng(51.219987, 4.396237),
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.HYBRID
        };
        var map = new google.maps.Map(document.getElementById("locationField"),
          mapOptions);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(51.219987, 4.396237)
        });
        marker.setMap(map);
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
