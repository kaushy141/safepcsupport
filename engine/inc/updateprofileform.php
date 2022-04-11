<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>#
        <?=$user_name?>
        Profile </strong> <small>Form</small> </div>
      <form id="updatePassword" name="updatePassword">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="old_password">Old Password<sup>*</sup></label>
                <div class="input-group">
                  <input id="old_password" name="old_password" class="form-control" placeholder="Type Old Password" type="password" autocomplete="off"  maxlength="32" value="">
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="new_password">New Password<sup>*</sup></label>
                <div class="input-group">
                  <input id="new_password" name="new_password" class="form-control" placeholder="Type New Password" type="password" autocomplete="off" maxlength="32" value="">
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="conf_password">Confirm Password<sup>*</sup></label>
                <div class="input-group">
                  <input id="conf_password" name="conf_password" class="form-control" placeholder="Confirm Password" type="password" autocomplete="off" maxlength="32" value="">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="updatepassword();" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span> Password </button>
        </div>
        <input type="hidden" id="action" name="action" value="updateuserpassword"  />
      </form>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>#
        <?=$user_name?>
        Profile </strong> <small>Information</small>
        <div class="card-actions"> <a class="btn-minimize" data-toggle="collapse" href="#collapseDetail" aria-expanded="true" aria-controls="collapseDetail"><i class="icon-arrow-up"></i></a> <a class="btn-close" href="#"><i class="icon-close"></i></a> </div>
      </div>
      <div class="card-block collapse-in" id="collapseDetail" aria-expanded="true">
        <div class="row">
          <div class="col-sm-4"> <img src="<?=$app->imagePath($user_image)?>" class="img-avatar" height="200px" alt="<?=$user_name?>"> </div>
          <div class="col-sm-8">
            <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label><b>First Name</b></label>
                    <div class="input-group">
                      <?=$user_fname?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label><b>Last Name</b></label>
                    <div class="input-group">
                      <?=$user_lname?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label><b>Email</b></label>
                    <div class="input-group">
                      <?=$user_email?>
                      <?=icon($user_is_email_verified)?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label><b>Phone</b></label>
                    <div class="input-group">
                      <?=$user_phone?>
                      <?=icon($user_is_mobile_verified)?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label><b>Designation</b></label>
                    <div class="input-group">
                      <?=$user_type_name?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label><b>Address</b></label>
                    <div class="input-group">
                      <?=$user_address?>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row">
              <div class="col-sm-12">
                  <div class="form-group">
                    <label><b>Map</b></label>
                    <div class="input-group">
                    <iframe  width="100%"  height="350"  frameborder="0" style="border:0"
  src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCHuGg8PZcw3Cb7U_KPpbsmWCbpEYrabmc 
    &q=<?=urlencode($user_address)?>" allowfullscreen></iframe>
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
</div>
<script type="text/javascript">

function updatepassword()
{
	if(validateFields("old_password, new_password, conf_password"))
	{
		if($("#new_password").val() == $("#conf_password").val())
		{
			var data={
						action	:	$("#action").val()							
					};
			data = $.extend(data, $("#updatePassword").serializeFormJSON());
			$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
					message("process|Connecting...");
					dissableSubmission();
				},		
				success:function(output){ 
					enableSubmission(output);
					var arr	=	JSON.parse(output);	
					message(arr[1]);
				}
			})
		}
		else
			message("danger|New Password should matched with Confirm Password");
	}
}
</script> 
