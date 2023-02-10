<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?=$formHeading?></strong> <small>Form</small> </div>
      <form id="addcontract" name="addcontract" onsubmit="return saveContract();" enctype="multipart/form-data">
      <div class="card-block">
        <div class="row">
		   <div class="col-sm-4">
            <div class="form-group">
			<label for="user_contract_store">Select Contract Org.<sup>*</sup></label>
			<select id="user_contract_store" name="user_contract_store" class="form-control" size="1">
			  <?php
			$store = new Store(0);
			echo $store->getOptions(isset($user_contract_store)?$user_contract_store:"0", 'store_allow_appointment');
			?>
			</select>
            </div>
          </div>
           <div class="col-sm-4">
            <div class="form-group">
              <label for="user_cnic_number">CNIC Number<sup>*</sup></label>
              <input class="form-control" id="user_cnic_number" name="user_cnic_number" placeholder="Enter CNIC number" maxlength="50" required="required" type="text" value="<?=isset($user_cnic_number)?$user_cnic_number:"";?>">
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_department_name">Department Name<sup>*</sup></label>
              <input class="form-control" id="user_department_name" name="user_department_name" placeholder="Enter department name" maxlength="100" required="required" type="text" value="<?=isset($user_department_name)?$user_department_name:"";?>">
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_appointment_issue_date">Appointment issue date<sup>*</sup></label>
              <div class="input-group date">
              <input class="form-control" id="user_appointment_issue_date" name="user_appointment_issue_date" maxlength="10" placeholder="Enter appointment issue date" required="required" type="text" value="<?=isset($user_appointment_issue_date)?$user_appointment_issue_date:date("Y-m-d", strtotime($data['user_created_date']));?>">
              <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="user_appointment_issue_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                  </span>
              </div>
              <script type="text/javascript">
				$(function () {
					$('#user_appointment_issue_date').datepicker({
						format: 'yyyy-mm-dd',
						autoclose:true,
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true,
						fontAwesome : true,
					});
				});
            </script>
            </div>
          </div>
          
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_father_name">Surname<sup>*</sup></label>
              <input class="form-control" id="user_father_name" name="user_father_name" placeholder="Enter Employee father name" maxlength="50" required="required" type="text" value="<?=isset($user_father_name)?$user_father_name:"";?>">
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_pay_working_hours">Working hours<sup>*</sup></label>
              <div class="input-group clockpicker" data-placement="right" data-align="bottom">
              <input class="form-control" id="user_pay_working_hours" name="user_pay_working_hours" maxlength="10" placeholder="Enter Employee working hours" type="text" value="<?=isset($user_pay_working_hours)?$user_pay_working_hours:"";?>">
              <span class="input-group-addon">
                    <span class="fa fa-clock-o"></span>
                </span>
                </div>
                <script type="text/javascript">
				$(document).ready(function(e) {
                   $('.clockpicker').clockpicker({placement: 'bottom', autoclose:true, donetext: 'Done'}); 
                });
            	</script> 
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_pay_sales_commision">Sales commision %<sup>*</sup></label>
              <input class="form-control" id="user_pay_sales_commision" name="user_pay_sales_commision" maxlength="10" placeholder="Enter Employee sales commision" min="0" max="100" required="required" type="number" value="<?=isset($user_pay_sales_commision)?$user_pay_sales_commision:"0";?>">
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_pay_joining_date">Joining date (yyyy-mm-dd)<sup>*</sup></label>
              <div class="input-group date">
              <input class="form-control" id="user_pay_joining_date" name="user_pay_joining_date" maxlength="10" placeholder="Enter joining date" required="required" type="text" value="<?=isset($user_pay_joining_date)?$user_pay_joining_date:date("Y-m-d", strtotime($data['user_created_date']));?>">
              <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="user_pay_joining_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                  </span>
              </div>
              <script type="text/javascript">
				$(function () {
					$('#user_pay_joining_date').datepicker({
						format: 'yyyy-mm-dd',
						autoclose:true,
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true,
						fontAwesome : true,
					});
				});
            </script>
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_pay_salary">Basic Salary<sup>*</sup></label>
              <input class="form-control" id="user_pay_salary" name="user_pay_salary" maxlength="10" placeholder="Enter employee salary" required="required" min="0" type="number" step="any" value="<?=isset($user_pay_salary)?$user_pay_salary:"";?>">              
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_payment_currency">Salary currency<sup>*</sup></label>
              <select id="user_payment_currency" name="user_payment_currency" class="form-control" size="1">
              	<?php echo getCurrencyType(isset($user_payment_currency)?$user_payment_currency:"");
				?>
              </select>
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_working_time">Working time <sup>*</sup></label>
              <input class="form-control" id="user_working_time" name="user_working_time" maxlength="50" placeholder="Enter working time Ex.9AM-5PM" required="required" type="text" value="<?=isset($user_working_time)?$user_working_time:"";?>">              
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_phone">Salary pay format<sup>*</sup></label>
              <select id="user_pay_salary_invoicing" name="user_pay_salary_invoicing" class="form-control" size="1">
              	<option> -- Seelct Pay Format -- </option>
                <option <?php echo(isset($user_pay_salary_invoicing) && $user_pay_salary_invoicing == PAY_FORMAT_HOURLY) ? "selected":""?> value="<?=PAY_FORMAT_HOURLY?>"><?=PAY_FORMAT_HOURLY?></option>
                <option <?php echo(isset($user_pay_salary_invoicing) && $user_pay_salary_invoicing == PAY_FORMAT_WEEK) ? "selected":""?> value="<?=PAY_FORMAT_WEEK?>"><?=PAY_FORMAT_WEEK?></option>
                <option <?php echo(isset($user_pay_salary_invoicing) && $user_pay_salary_invoicing == PAY_FORMAT_15DAY) ? "selected":""?> value="<?=PAY_FORMAT_15DAY?>"><?=PAY_FORMAT_15DAY?></option>
                <option <?php echo(isset($user_pay_salary_invoicing) && $user_pay_salary_invoicing == PAY_FORMAT_MONTH) ? "selected":""?> value="<?=PAY_FORMAT_MONTH?>"><?=PAY_FORMAT_MONTH?></option>
                <option <?php echo(isset($user_pay_salary_invoicing) && $user_pay_salary_invoicing == PAY_FORMAT_QUATR) ? "selected":""?> value="<?=PAY_FORMAT_QUATR?>"><?=PAY_FORMAT_QUATR?></option>
                <option <?php echo(isset($user_pay_salary_invoicing) && $user_pay_salary_invoicing == PAY_FORMAT_HALFY) ? "selected":""?> value="<?=PAY_FORMAT_HALFY?>"><?=PAY_FORMAT_HALFY?></option>
                <option <?php echo(isset($user_pay_salary_invoicing) && $user_pay_salary_invoicing == PAY_FORMAT_YEAR) ? "selected":""?> value="<?=PAY_FORMAT_YEAR?>"><?=PAY_FORMAT_YEAR?></option>
              </select>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_pay_previous_balance">Previous Balance<sup>*</sup></label>
              <input class="form-control" id="user_pay_previous_balance" name="user_pay_previous_balance" maxlength="10" placeholder="Enter Employee previous balance" min="0" step="any" type="number" value="<?=isset($user_pay_previous_balance)?$user_pay_previous_balance:"0";?>">
            </div>
          </div>   
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_pay_working_days">Working days</label>
              <input class="form-control" id="user_pay_working_days" name="user_pay_working_days" maxlength="1" placeholder="Enter Employee working days" required="required" type="number" min="0" step=".5" max="7" value="<?=isset($user_pay_working_days)?$user_pay_working_days:"5";?>">
            </div>
          </div>
		  
		  <div class="col-sm-4">
            <div class="form-group">
              <label for="user_pay_working_days_name">Working days name </label>
              <input class="form-control" id="user_pay_working_days_name" name="user_pay_working_days_name" maxlength="50" placeholder="Working days name Ex. 'Sat (if Mon-Fri)'" required="required" type="text"  value="<?=isset($user_pay_working_days_name)?$user_pay_working_days_name:"Sat (if Mon-Fri)";?>">
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="user_reporting_person">Reporting person<sup>*</sup></label>
              <input class="form-control" id="user_reporting_person" name="user_reporting_person" placeholder="Enter reporting person" maxlength="50" type="text" value="<?=isset($user_reporting_person)?$user_reporting_person:"";?>">
            </div>
          </div>
          
          <div class="col-sm-4">
              <div class="form-group">
              <label><i class="fa fa-envelope-o fa-lg m-t-2"></i> &nbsp; Contract Send</label><br />
                <label for="wc_mail_wcnn_to_customer">Send Contract Document Email</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="send_email_to_employee" value="1" name="send_email_to_employee" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
              <label><i class="fa fa-file-pdf-o fa-lg m-t-2 text-danger"></i> &nbsp; Attach Appointment letter</label><br />
                <label for="wc_attach_appointment_letter">Attach Appointment Document</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="wc_attach_appointment_letter" value="1" name="wc_attach_appointment_letter" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
              <label><i class="fa fa-hashtag fa-lg m-t-2"></i> &nbsp; Contract Status</label><br />
                <label for="user_pay_status">Continiuing with Employee</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="user_pay_status" <?=(isset($user_pay_status) && $user_pay_status ) ? "checked":"";?> value="1" name="user_pay_status" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            
            <div class="col-md-4">
            <div class="form-group">
            <label for="user_cont_file"><i class="fa fa-file-pdf-o fa-lg m-t-2 text-danger"></i> &nbsp; Upload Contract Document</label>
              <input type="file" name="user_cont_file" id="user_cont_file" class="btn btn-default btn-block save" data-action="save-png" onchange="filesUpload('user_cont_file');" />
              <input type="hidden" name="user_cont_file_path" id="user_cont_file_path" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>
              <div class="col-md-4">
                <div class="form-group">
                <label for="user_char_exp_file"><i class="fa fa-file-pdf-o fa-lg m-t-2 text-danger"></i> &nbsp;  Character certificate/experience</label>
                  <input type="file" name="user_char_exp_file" id="user_char_exp_file" class="btn btn-default btn-block save" data-action="save-png" onchange="filesUpload('user_char_exp_file');" />
                  <input type="hidden" name="user_char_exp_file_path" id="user_char_exp_file_path" value="" />
                  <span class="file_uploader"></span>
                </div>
              </div>
                 
        </div>
        <!--/row-->
		<div class="row">
		 <?php if(isset($user_pay_id) && $user_pay_id > 0){?>
		 <div class="col-xs-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label for="user_appointment_upgrade_version"><i class="fa fa-user-plus fa-lg m-t-2"></i> &nbsp;Upgrade Appointment version</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="user_appointment_upgrade_version" value="1" name="user_appointment_upgrade_version" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
		 <?php }?>
		 </div>
        
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
      <button type="submit" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name"><?=$btnText?></span></button>
      
    </div>
    	<input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addcontract";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="user_pay_user_id" name="user_pay_user_id" value="<?=isset($user_id)?$user_id:"0";?>"  />
        <input type="hidden" id="user_pay_id" name="user_pay_id" value="<?=isset($user_pay_id)?$user_pay_id:"0";?>"  />
      </form>
    </div>
    
  </div>
</div>
<!--/col--> 

<!--/col-->
</div>
<script type="text/javascript">
function saveContract()
{
	var formFields	=	"user_contract_store, user_cnic_number, user_department_name, user_appointment_issue_date, user_father_name, user_pay_working_hours, user_pay_joining_date, user_pay_salary, user_pay_salary_invoicing, user_working_time";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()		
		};
		
		data = $.extend(data, $("#addcontract").serializeFormJSON());
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
					$("#pay_id").val(arr[2]);
				}
				message(arr[1]);
			}
		})	
	}
	return false;
}

function user_cont_file_path_callback(path){
	$("#user_cont_file_path").val(path);
}
function user_char_exp_file_path_callback(path){
	$("#user_char_exp_file_path").val(path);
}

</script> 