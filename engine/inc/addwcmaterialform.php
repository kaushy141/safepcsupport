<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?=$formHeading?></strong> <small>Form</small> </div>
      <form id="addemployee" name="addemployee" enctype="multipart/form-data">
      <div class="card-block">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_fname">Employee First Name<sup>*</sup></label>
              <input class="form-control" id="user_fname" name="user_fname" maxlength="50" placeholder="Enter employee first name" type="text" value="<?=isset($user_fname)?$user_fname:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_lname">Employee Last Name<sup>*</sup></label>
              <input class="form-control" id="user_lname" name="user_lname" maxlength="50" placeholder="Enter employee Last name" type="text" value="<?=isset($user_lname)?$user_lname:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_type_id">Employee Type<sup>*</sup></label>
              <select id="user_type_id" name="user_type_id" class="form-control" size="1">
                <?php
                $UserType = new UserType(0);
				echo $UserType->getOptions(isset($user_type_id)?$user_type_id:"0");
				?>
              </select>
            </div>
          </div>
        </div>
        <!--/row-->
        
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_email">Employee Email<sup>*</sup></label>
              <input class="form-control" id="user_email" name="user_email" maxlength="50" placeholder="Enter employee Email id" type="email" onblur="checkExistingEmail();" value="<?=isset($user_email)?$user_email:"";?>">              
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_phone">Employee Phone Number<sup>*</sup></label>
              <input class="form-control" id="user_phone" name="user_phone" maxlength="20" placeholder="Enter employee phone number" type="tel" value="<?=isset($user_phone)?$user_phone:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_image">Employee Photo <sup>*</sup></label>
              <div class="input-group">
                                    <span class="input-group-addon" style="padding:0px" id="image_uploader"><?=isset($user_image)?$user_image:'<i class="fa fa-camera fa-lg"></i>'?></span>
                                    <input class="form-control" id="user_image" name="user_image" style="padding-bottom: 4px; padding-top: 4px;" maxlength="100" value="" type="file" onchange="uploadFile(this.name);">
                                </div>
            </div>
          </div>
          
        </div>
        <!--/row-->
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="user_address">Employee Address<sup>*</sup></label>
              <div id="locationField">
              <textarea id="user_address" name="user_address" onFocus="geolocate()" rows="4" class="form-control" placeholder="Enter Employee Address"><?=isset($user_address)?$user_address:"";?></textarea>
              </div>
            </div>
          </div>
        </div>       
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
      <button type="button" onClick="addEmployee();" class="btn btn-success"><i class="fa fa-check-circle fa-lg m-t-2"></i> Save</button>
      
    </div>
    	<input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="user_id" name="user_id" value="<?=isset($user_id)?$user_id:"0";?>"  />
      </form>
    </div>
    
  </div>
</div>
<!--/col--> 

<!--/col-->
</div>
<script type="text/javascript">
function addEmployee()
{
	var formFields	=	"user_fname, user_lname, user_type_id, user_email, user_phone, user_address";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val(),
			field_handler:"user_image"				
		};
		
		data = $.extend(data, $("#addemployee").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#user_id").val(arr[2]);
					$("#action").val("employee/updateemployee");
				}
				message(arr[1]);
			}
		})	
	}
}

function checkExistingEmail()
{
	if(validateFields("user_email"))
	{
		var data={
			action	:	'isuseremailavailable',
			user_email	:	$("#user_email").val()							
		};	 
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
					message("process|Connecting...", 0);
				},		
				success:function(output){
					var arr	=	JSON.parse(output);			
				message(arr[1], 0);
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

  function initAutocomplete() {
	// Create the autocomplete object, restricting the search to geographical
	// location types.
	autocomplete = new google.maps.places.Autocomplete(
		/** @type {!HTMLInputElement} */(document.getElementById('user_address')),
		{types: ['geocode']});

	// When the user selects an address from the dropdown, populate the address
	// fields in the form.
	autocomplete.addListener('place_changed', fillInAddress);
  }

  function fillInAddress() {
	// Get the place details from the autocomplete object.
	var place = autocomplete.getPlace();
	document.getElementById("user_address").value = place.formatted_address;
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
