<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?php echo $formHeading; ?></strong> <small>Form</small> </div>
      <form id="addempcontractform" name="addempcontractform">
        <div class="card-block">
          <div class="row">
			<div class="col-sm-4">
            <div class="form-group">
			<label for="employee_contract_store">Select Contract Org.<sup>*</sup></label>
			<select id="employee_contract_store" name="employee_contract_store" class="form-control" size="1">
			  <?php
			$store = new Store(0);
			echo $store->getOptions(isset($employee_contract_store)?$employee_contract_store:"0", 'store_allow_appointment');
			?>
			</select>
            </div>
          </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_name">Employee Name<sup>*</sup></label>
                <input class="form-control" id="employee_name" name="employee_name" maxlength="50" placeholder="Enter employee name" type="text" value="<?=isset($employee_name)?$employee_name:"";?>">
              </div>
            </div>
			  
			<div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_email">Email Id<sup>*</sup></label>
                <input class="form-control" id="employee_email" name="employee_email" maxlength="50" placeholder="Enter email id" type="text" value="<?=isset($employee_email)?$employee_email:"";?>">
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_phone">Phone number (10 Digit)<sup>*</sup></label>
                <input class="form-control" id="employee_phone" name="employee_phone" maxlength="10" placeholder="Enter phone number" type="text" value="<?=isset($employee_phone)?$employee_phone:"";?>">
              </div>
            </div>
			<div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_contarct_date">Employee DOB<sup>*</sup></label>
                <input class="form-control" id="employee_dob" name="employee_dob" maxlength="10" placeholder="YYYY-MM-DD" type="text" value="<?=isset($employee_dob)?$employee_dob:"";?>">
              </div>
				<script type="text/javascript">
                    $('#employee_dob').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,					
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
            </div> 
            <div class="col-sm-12">
              <div class="form-group">
                <label for="employee_address">Employee address<sup>*</sup></label>
                <textarea id="employee_address" name="employee_address" rows="2" class="form-control" placeholder="Enter employee address ..."><?=isset($employee_address)?$employee_address:"";?>
</textarea>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_country">Country name<sup>*</sup></label>
                <input class="form-control" id="employee_country" name="employee_country" maxlength="50" placeholder="Enter Country name" type="text" value="<?=isset($employee_country)?$employee_country:"";?>">
              </div>
            </div>
            
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_salary_rate_mode">Salary rate mode<sup>*</sup></label>
                <input class="form-control" id="employee_salary_rate_mode" name="employee_salary_rate_mode" maxlength="50" placeholder="Example: 'Monthly', 'Per hour', 'Per day'" type="text" value="<?=isset($employee_salary_rate_mode)?$employee_salary_rate_mode:"";?>">
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_salary_rate_price">Salary rate price<sup>*</sup></label>
                <input class="form-control" id="employee_salary_rate_price" name="employee_salary_rate_price" maxlength="50" placeholder="Enter mode base salary rate" step="0.01" type="number" value="<?=isset($employee_salary_rate_price)?$employee_salary_rate_price:"";?>">
              </div>
            </div>
			  <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_salary_rate_currency">Salary Currency<sup>*</sup></label>
                <input class="form-control" id="employee_salary_rate_currency" name="employee_salary_rate_currency" maxlength="10" placeholder="Ex. 'Pound', 'Doller', 'GBP', 'INR', 'PKR', '$', '£'" type="text" value="<?=isset($employee_salary_rate_currency)?$employee_salary_rate_currency:"";?>">
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_job_title">JOb Title<sup>*</sup></label>
                <input class="form-control" id="employee_job_title" name="employee_job_title" maxlength="50" placeholder="Enter job title" type="text" value="<?=isset($employee_job_title)?$employee_job_title:"";?>">
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_contract_date">Contract date<sup>*</sup></label>
                <input class="form-control" id="employee_contract_date" name="employee_contract_date" maxlength="10" placeholder="YYYY-MM-DD" type="text" value="<?=isset($employee_contract_date)?$employee_contract_date:"";?>">
              </div>
				<script type="text/javascript">
                    $('#employee_contract_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,					
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
            </div>
			 <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="employee_employment_date">Employment date<sup>*</sup></label>
                <input class="form-control" id="employee_employment_date" name="employee_employment_date" maxlength="10" placeholder="YYYY-MM-DD" type="text" value="<?=isset($employee_employment_date)?$employee_employment_date:"";?>">
              </div>
				<script type="text/javascript">
                    $('#employee_employment_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,						
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
            </div>
			<div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group">
            	<label for="employee_id_card_file">Employee ID Card File(PDF)<sup></sup></label>
              <input type="file" name="employee_id_card_file" id="employee_id_card_file" class="btn btn-primary btn-block save" data-action="save-png" onchange="filesUpload('employee_id_card_file');" />
              <input type="hidden" name="employee_id_card" id="employee_id_card" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>
		  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
			  <div class="form-group">
				<label for="employee_is_zero_hour_contract">IS this Zero Hour Contract</label>
				<br/>
				<label for="employee_is_zero_hour_contract"><i class="fa fa-copy fa-lg m-t-2"></i> &nbsp;Zero Hour contract ?</label>
				<label class="switch switch-icon switch-pill switch-success pull-right">
				  <input class="switch-input" <?php if(isset($employee_is_zero_hour_contract) && $employee_is_zero_hour_contract == 1) echo "checked"?> id="employee_is_zero_hour_contract" value="1" name="employee_is_zero_hour_contract" type="checkbox">
				  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
			  </div>
			</div>
          </div>
			
		 <div class="row">
			<div class="col-sm-6">
            <div class="form-group">
              <div id="signature-pad" class="m-signature-pad">
                <div class="m-signature-pad-body">
                  <canvas></canvas>
                </div>                
              </div>
            </div>
			<div class="form-group">
              <button type="button" class="btn btn-default save" data-action="save-png" onclick="saveSignature(event);">Upload Signature</button>              
              <button type="button" class="btn btn-outline clear" onclick="clearSignature(event);" data-action="clear">Clear</button>
              <input type="hidden" value="" name="employee_signature" id="employee_signature" />
            </div>
          </div>
			
          <div class="col-sm-6">
            <div class="form-group" id="signature_saved_image">
              <?php if(isset($employee_signature) && $employee_signature!=""):?>
              <img src="<?php echo $app->basePath($employee_signature);?>" class="img-responsive" />
              <?php endif; ?>
            </div>
          </div>	
		 </div>
         <div class="row">
		 <?php if(isset($employee_id) && $employee_id > 0){?>
		 <div class="col-xs-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label for="employee_upgrade_version"><i class="fa fa-user-plus fa-lg m-t-2"></i> &nbsp;Upgrade Contract version</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="employee_upgrade_version" value="1" name="employee_upgrade_version" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
		 <?php }?>
		 </div>
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to save employee Information...?', 'saveEmpContract')" class="btn btn-sm btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i>
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"employee/saveempcontract";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="employee_id" name="employee_id" value="<?=isset($employee_id)?$employee_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">
function employee_id_card_file_callback(path){
	$("#employee_id_card").val(path);
}

function saveEmpContract()
{
	if(validateFields("employee_contract_store, employee_name, employee_address, employee_country, employee_email, employee_phone, employee_dob,  employee_salary_rate_mode, employee_salary_rate_price, employee_job_title, employee_contract_date, employee_employment_date"))
	{
		var data={
					action	:	$("#action").val()						
				};
		data = $.extend(data, $("#addempcontractform").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#employee_id").val(arr[2]);
					if($("#employee_upgrade_version").is(":checked"))
						redirect(sitePath + 'editempcontract/'+arr[2]);
				}
				message(arr[1]);
			}
		})	
	}
}
</script> 

<script type="text/javascript">
var wrapper = document.getElementById("signature-pad");
var canvas = wrapper.querySelector("canvas");
var signaturePad = new SignaturePad(canvas);


function clearSignature(event)
{
	signaturePad.clear();
	$("#employee_signature").val('');
	$("#signature_saved_image").html('');
}

function saveSignature(event)
{
	if (signaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
		var data={
			action	:	'collection/savesignature',
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
					$("#employee_signature").val(arr[2]);
					$("#signature_saved_image").html('<img src="'+arr[3]+'" class="img-responsive" />');
				}
				else
				{
					$("#employee_signature").val('');
					$("#signature_saved_image").html('');
				}
				message(arr[1]);
			}
		})
    }
}
</script> 

