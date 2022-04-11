<div id="loginPopup" class="modal open" role="dialog">
    <form name="modalform" id="modalform">
        <div class="modal-dialog">
            <div class="modal-content">                
                <div id="modal-header" class="modal-header">
                	<button type="button" class="close" data-dismiss="modal">&times;</button>
                	<h4 class="modal-title">Forgot Password</h4>
                </div>
                <div id="modal-notice" class="modal-notice popmsg" style="display:none;">
                	<div class="card-inverse text-xs-center" style="padding:8px 5px;">
                    </div>
                </div>
                <div id="modal-body" class="modal-body">
                	<div class="input-group">
                        <input id="forgot_email" name="forgot_email" class="form-control" placeholder="Enter your registered Email" type="email" required>
                        <span class="input-group-btn">
                            <button type="button" onClick="submitform()" class="btn btn-primary">Submit</button>
                        </span>
                    </div>
                  <div class="input-group">  
                    <div class="col-sm-12">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                <a href="javascript:refreshFPGCaptcha();">Refresh</a> <br/>
                <img id="customer_captcha_code_file_fpg" src="<?php echo $app->basePath("captcha.php?mode=CUSTOMER-FORGOT-PASSWORD")?>" style="margin:6px 0px;" /> </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <div class="form-group">
                  
                    <label for="customer_captcha_code">Captcha</label>
                    <input class="form-control" id="customer_captcha_code_file_fpg_val" name="customer_captcha_code_file_fpg_val" maxlength="4" placeholder="Code" type="text" value="">
                  </div>
                </div>
              </div>
            </div>
              	</div>
                </div>
                <div class="modal-footer">
                <div class="modal-footer-btn-block" style="float:left">
                </div>
                <input type="hidden" id="keyid" name="keyid" value="<?=$login_page_id?>" />
                <button type="reset" class="btn btn-default" >Reset</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
                
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
function refreshFPGCaptcha()
{
	$("#customer_captcha_code_file_fpg").attr('src','<?php echo $app->basePath("captcha.php?mode=CUSTOMER-FORGOT-PASSWORD&id=");?>'+Math.random());
}
function submitform()
{	
	var forgot_email	=	$("#forgot_email").val().trim();
	var keyid			= 	$("#keyid").val().trim();
	var customer_captcha_code_file_fpg_val = $("#customer_captcha_code_file_fpg_val").val();
	if(customer_captcha_code_file_fpg_val !="")
	{
		if(forgot_email==""){
			$("#forgot_email").focus();
			popmessage("danger|Please fill Registered Email.");
			return false;
		}
		var data={
					action		:	'forgotpassword',
					forgot_email:	forgot_email,
					customer_captcha_code_file_fpg_val:customer_captcha_code_file_fpg_val,
					keyid		:	keyid,							
				}
		$.ajax({type:'POST', data:data, url:'aouth.php', 
			
			beforeSend: function(){
				popmessage("warning|Connecting...");
			},		
			success:function(output){
				var arr	=	JSON.parse(output);
				popmessage(arr[1]);
			}
		})
	}
	else
	popmessage("warning|Captcha must be filled...");
}
</script>
