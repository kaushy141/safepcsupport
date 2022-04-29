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
          
                    
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_qualification">Employee Qualification<sup></sup></label>
              <input class="form-control" id="user_qualification" name="user_qualification" maxlength="200" placeholder="Enter Qualification" type="text" value="<?=isset($user_qualification)?$user_qualification:"";?>">
            </div>
          </div>         
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_skills">Employee Skills<sup></sup></label>
              <input class="form-control" id="user_skills" name="user_skills" maxlength="200" placeholder="Enter skills" type="text" value="<?=isset($user_skills)?$user_skills:"";?>">
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_experience">Employee Experience<sup></sup></label>
              <input class="form-control" id="user_experience" name="user_experience" maxlength="200" placeholder="Enter experience" type="text" value="<?=isset($user_experience)?$user_experience:"";?>">
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_responsibility">Employee responsibility<sup></sup></label>
              <input class="form-control" id="user_responsibility" name="user_responsibility" maxlength="5000" placeholder="Enter responsibility" type="text" value="<?=isset($user_responsibility)?$user_responsibility:"";?>">
            </div>
          </div>
		  
		  <div class="col-sm-4">
            <div class="form-group">
              <label for="user_gender">Employee gender<sup></sup></label>
              <select id="user_gender" name="user_gender" class="form-control" size="1">
                <?php
				echo getUserGender(isset($user_gender)?$user_gender:"");
				?>
              </select>
            </div>
          </div>
		  
		  <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="user_joining_date">Joining Date<sup>*</sup></label>
                <input class="form-control" id="user_joining_date" name="user_joining_date" maxlength="10" placeholder="YYYY-MM-DD" type="text" value="<?=isset($user_joining_date)?$user_joining_date:"";?>">
              </div>
				<script type="text/javascript">
                    $('#user_joining_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,					
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
          </div> 
		  
		  <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="user_releaving_date">Releaving Date<sup>*</sup></label>
                <input class="form-control" id="user_releaving_date" name="user_releaving_date" maxlength="10" placeholder="YYYY-MM-DD" type="text" value="<?=isset($user_releaving_date)?$user_releaving_date:"";?>">
              </div>
				<script type="text/javascript">
                    $('#user_releaving_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,					
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
          </div> 
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_work_positions">Employee Work Positions<sup></sup></label>
              <input class="form-control" id="user_work_positions" name="user_work_positions" maxlength="500" placeholder="Enter work positions" type="text" value="<?=isset($user_work_positions)?$user_work_positions:"";?>">
            </div>
          </div>
		  
		  </div>
		  <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label for="user_file">Employee CV File(PDF)<sup></sup></label>
              <input type="file" name="user_file" id="user_file" class="btn btn-primary btn-block save" data-action="save-png" onchange="filesUpload('user_file');" />
              <input type="hidden" name="user_cv_file" id="user_cv_file" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>
                    
          <?php if(isset($user_cv_file) && $user_cv_file != ""):?>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label>Existing CV File<sup></sup></label><br/>
              	<a download target="new" href="<?php echo $app->basePath($user_cv_file)?>"><i class="fa fa-2x fa-file-pdf-o text-danger"></i> Download CV FIle </a>
            </div>
          </div> 
          <?php endif; ?>
		<!--ID Card-->	
		
		  </div>
		  <div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label for="user_id_card_file">Employee ID Card File(PDF)<sup></sup></label>
              <input type="file" name="user_id_card_file" id="user_id_card_file" class="btn btn-primary btn-block save" data-action="save-png" onchange="filesUpload('user_id_card_file');" />
              <input type="hidden" name="user_id_card" id="user_id_card" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>
                    
          <?php if(isset($user_id_card) && $user_id_card != ""):?>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label>Existing ID Card<sup></sup></label><br/>
              	<a download target="new" href="<?php echo $app->basePath($user_id_card)?>"><i class="fa fa-2x fa-file-pdf-o text-danger"></i> Download Id card </a>
            </div>
          </div> 
          <?php endif; ?>
		
		  </div>
		  <div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label for="user_experience_certificate_file">Experience Certificate (PDF)<sup></sup></label>
              <input type="file" name="user_experience_certificate_file" id="user_experience_certificate_file" class="btn btn-primary btn-block save" data-action="save-png" onchange="filesUpload('user_experience_certificate_file');" />
              <input type="hidden" name="user_experience_certificate" id="user_experience_certificate" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>
                    
          <?php if(isset($user_id_card) && $user_id_card != ""):?>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label>Existing Experience Certificate<sup></sup></label><br/>
              	<a download target="new" href="<?php echo $app->basePath($user_experience_certificate)?>"><i class="fa fa-2x fa-file-pdf-o text-danger"></i> Download Experience Certificate </a>
            </div>
          </div> 
          <?php endif; ?>
          
          	
		<!--Educational document-->	
		
		  </div>
		  <div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label for="user_education_doc_file">Educational doc File(PDF)<sup></sup></label>
              <input type="file" name="user_education_doc_file" id="user_education_doc_file" class="btn btn-primary btn-block save" data-action="save-png" onchange="filesUpload('user_education_doc_file');" />
              <input type="hidden" name="user_education_doc" id="user_education_doc" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>
                    
          <?php if(isset($user_education_doc) && $user_education_doc != ""):?>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label>Existing Educational doc<sup></sup></label><br/>
              	<a download target="new" href="<?php echo $app->basePath($user_education_doc)?>"><i class="fa fa-2x fa-file-pdf-o text-danger"></i> Download Education doc </a>
            </div>
          </div> 
          <?php endif; ?>
			
			
			
		<!--DL document-->	
		
		  </div>
		  <div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label for="user_driving_licence_file">Driving Licence File(PDF)<sup></sup></label>
              <input type="file" name="user_driving_licence_file" id="user_driving_licence_file" class="btn btn-primary btn-block save" data-action="save-png" onchange="filesUpload('user_driving_licence_file');" />
              <input type="hidden" name="user_driving_licence" id="user_driving_licence" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>
                    
          <?php if(isset($user_driving_licence) && $user_driving_licence != ""):?>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label>Existing Driving Licence<sup></sup></label><br/>
              	<a download target="new" href="<?php echo $app->basePath($user_driving_licence)?>"><i class="fa fa-2x fa-file-pdf-o text-danger"></i> Download Driving Licence </a>
            </div>
          </div> 
          <?php endif; ?>
		  
		  
		  </div>
		  <div class="row">
		  <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label for="user_labour_card_file">Labour Card<sup></sup></label>
              <input type="file" name="user_labour_card_file" id="user_labour_card_file" class="btn btn-primary btn-block save" data-action="save-png" onchange="filesUpload('user_labour_card_file');" />
              <input type="hidden" name="user_labour_card" id="user_labour_card" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>
                    
          <?php if(isset($user_labour_card) && $user_labour_card != ""):?>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label>Existing Labour Card<sup></sup></label><br/>
              	<a download target="new" href="<?php echo $app->basePath($user_labour_card)?>"><i class="fa fa-2x fa-file-pdf-o text-danger"></i> Download Labour Card</a>
            </div>
          </div> 
          <?php endif; ?>
		  
		  
		  </div>
		  <div class="row">
		  <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label for="user_passport_file">Passport<sup></sup></label>
              <input type="file" name="user_passport_file" id="user_passport_file" class="btn btn-primary btn-block save" data-action="save-png" onchange="filesUpload('user_passport_file');" />
              <input type="hidden" name="user_passport" id="user_passport" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>
                    
          <?php if(isset($user_passport) && $user_passport != ""):?>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            	<label>Existing Passport<sup></sup></label><br/>
              	<a download target="new" href="<?php echo $app->basePath($user_passport)?>"><i class="fa fa-2x fa-file-pdf-o text-danger"></i> Download Passport</a>
            </div>
          </div> 
          <?php endif; ?>
			
          
          
          
          
          
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
        
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <div id="signature-pad" class="m-signature-pad">
                <div class="m-signature-pad-body">
                  <canvas></canvas>
                </div>                
              </div>
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <button type="button" class="btn btn-success btn-block save" data-action="save-png" onclick="saveSignature(event);">Upload Signature</button>              
              <button type="button" class="btn btn-danger btn-block clear" onclick="clearSignature(event);" data-action="clear">Clear</button>
              <input type="hidden" value="" name="user_signature" id="user_signature" />
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group" id="signature_saved_image">
              <?php if(isset($user_signature) && $user_signature!=""):?>
              <img src="<?php echo $app->basePath($user_signature);?>" class="img-responsive" />
              <?php endif; ?>
            </div>
          </div>
        </div>     
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh"></i> Reset</button>
      <button type="button" onClick="confirmMessage.Set('Are you sure to <?=$user_id==0?"add":"updated"?> Employee Information...?', 'addEmployee');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle"></i> <span id="btn_action_name"><?=$btnText?></span> EMPLOYEE </button>
      
    </div>
    	<input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"employee/addemployee";?>"  />
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
	var formFields	=	"user_fname,user_lname,user_type_id,user_email,user_phone,user_gender,user_joining_date,user_address";
	
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
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#user_id").val(arr[2]);
					$("#action").val("employee/updateemployee");
					$("#btn_action_name").text("UPDATE");
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
					dissableSubmission();
				},		
				success:function(output){
					enableSubmission(output);
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

<script type="text/javascript">
var wrapper = document.getElementById("signature-pad");
var canvas = wrapper.querySelector("canvas");
var signaturePad = new SignaturePad(canvas);

function user_cv_file_callback(path){
	$("#user_cv_file").val(path);
}

function user_id_card_file_callback(path){
	$("#user_id_card").val(path);
}
	
function user_exp_file_path_callback(path){
	$("#user_experience_certificate").val(path);
}	
function user_education_doc_file_callback(path){
	$("#user_education_doc").val(path);
}
function user_driving_licence_file_path_callback(path){
	$("#user_driving_licence").val(path);
}
function user_labour_card_file_path_callback(path){
	$("#user_labour_card").val(path);
}
function user_passport_file_path_callback(path){
	$("#user_passport").val(path);
}	

function clearSignature(event)
{
	signaturePad.clear();
	$("#signature_link").val('');
	$("#signature_saved_image").html('');
}

function saveSignature(event)
{
	if (signaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
		var data={
			action	:	'savesignature',
			signature:signaturePad.toDataURL()				
		};		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Saving Signature...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
			enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#user_signature").val(arr[2]);
					$("#signature_saved_image").html('<img src="'+arr[3]+'" class="img-responsive" />');
				}
				else
				{
					$("#signature_link").val('');
					$("#signature_saved_image").html('');
				}
				message(arr[1]);
			}
		})
    }
}

</script>


<?php echo GOOGLE_MAP_API_SCRIPT;?>
