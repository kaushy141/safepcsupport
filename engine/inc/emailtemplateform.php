<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>Add/Edit Email Template</strong> <small>Form</small> </div>
      <form id="addemailtemplate" name="addemailtemplate">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="crm_email_template_name">Template Name</label>
                <input class="form-control" id="crm_email_template_name" name="crm_email_template_name" maxlength="50" required="required" value="<?=isset($crm_email_template_name)?$crm_email_template_name:"";?>" placeholder="Enter Template name" type="text">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="form-control-label">Template Status</label>
                <div class="col-md-12">
                  <div class="row">
                    <label class="radio-inline" for="inline-radio1">
                      <input id="inline-radio1" name="crm_email_template_status" checked class="hardware_name" value="1" type="radio" <?=isset($crm_email_template_status) && $crm_email_template_status == 1 ? "checked=\"checked\"":"";?> >
                      Active </label>
                    &nbsp;  &nbsp;
                    <label class="radio-inline" for="inline-radio2">
                      <input id="inline-radio2" name="crm_email_template_status" class="hardware_name" value="0" type="radio" <?=isset($crm_email_template_status) && $crm_email_template_status == 0 ? "checked=\"checked\"":"";?>>
                      Deactive </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header"> Template Variable Hint
                  <div class="card-actions"><a href="#" class="btn-minimize" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true"><i class="icon-arrow-down"></i></a></div>
                </div>
                <div class="card-body collapse" id="collapseExample" style=" padding:0px 8px; overflow:hidden;"> <code>{{customer_name}}</code> to Add Customer name<br/>
                  <code>{{customer_email}}</code> to Add Customer Email<br/>
                  <code>{{customer_phone}}</code> to Add Customer Phone<br/>
                  <code>{{customer_password}}</code> to Add Customer Password<br/>
                  <code>{{login_page}}</code> to Add Customer Login Page Link<br/>
                  <code>{{app->siteName}}</code> to Add Site Name </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="crm_email_template_html">Template Body</label>
                <textarea class="form-control" id="crm_email_template_html" name="crm_email_template_html" rows="12" placeholder="Enter Template Text"><?=isset($crm_email_template_html)?$crm_email_template_html:"";?>
</textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addemailtemplate";?>"  />
          <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
          <input type="hidden" id="crm_email_template_id" name="crm_email_template_id" value="<?=isset($crm_email_template_id)?$crm_email_template_id:"";?>" />
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="addEmailTemplate();" class="btn btn-sm btn-primary submission_handler_btn"><i class="fa fa-dot-circle-o"></i>
          <?=isset($btnText)?$btnText:"Add";?>
          </button>
        </div>
      </form>
    </div>
  </div>
  <!--/col--> 
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Email Template List </div>
      <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>Template Name</th>
              <th>Modified date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
			$CrmEmailTemplate = new CrmEmailTemplate(0);
			echo $CrmEmailTemplate->getRecords();
			?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">
function addEmailTemplate()
{
	if(validateFields("crm_email_template_name, crm_email_template_html"))
	{
		data = $("#addemailtemplate").serializeFormJSON();
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				message(arr[1]);
				if(arr[0] == 200)
				$("#addemailtemplate").reset();
			}
		})	
	}
}

function statusAction(field)
{
	var data={
			action	:	"emailtemplate/updateemailtemplatestatus",
			status	:	Number(field.checked),
			idvalue		:	field.value				
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...",0);
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output); 
				var arr	=	JSON.parse(output);
				message(arr[1],2000);
			}
		})	
}

function checkHardwarecode()
{
	if(validateFields("hardware_code") && validateHardwareCode($("#hardware_code").val()))
	{
		var data={
					action	:	'system/iscodeavailable',
					hardware_code	:	$("#hardware_code").val()							
				};
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
}

function validateHardwareCode(code)
{
	if (code.value.match(/^[A-Za-z]+$/))
	{
		$("#hardware_code").val("");
		message("danger|Only A-Z Character allowed.");
		return false;
	}
	return true;
}

$("#tbldatatable").dataTable({"iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100], "sPaginationType": "full_numbers", "aoColumns": [ { "bSortable": false }, null, null, null, null]});

$("#tbldatatable").dataTable({"iDisplayLength": 10, "sPaginationType": "full_numbers","bLengthChange": false,"bFilter": false,"bInfo": false,"bPaginate": true, "aoColumns": [ { "bSortable": false }, null, null, null, null]});

</script> 
