<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?=$formHeading?></strong> <small>Form</small> </div>
      <form id="addcrmtask" name="addcrmtask" enctype="multipart/form-data">
      <div class="card-block">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="crm_task_name">Task Name<sup>*</sup></label>
              <input class="form-control" id="crm_task_name" name="crm_task_name" maxlength="100" placeholder="Enter crm task name" type="text" value="<?=isset($crm_task_name)?$crm_task_name:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="crm_task_subject">Task Subject<sup>*</sup></label>
              <input class="form-control" id="crm_task_subject" name="crm_task_subject" maxlength="100" placeholder="Enter crm task subject" type="text" value="<?=isset($crm_task_subject)?$crm_task_subject:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="crm_task_template_id">Applied Template<sup>*</sup></label>
              <select id="crm_task_template_id" name="crm_task_template_id" class="form-control" size="1">
                <?php
                $CrmEmailTemplate = new CrmEmailTemplate(0);
				echo $CrmEmailTemplate->getOptions(isset($crm_task_template_id)?$crm_task_template_id:"0");
				?>
              </select>
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
        	<div class="col-sm-4">
                <div class="form-group">
                  <label for="crm_task_customer_group_id">Customer Group<sup>*</sup></label>
                  <select id="crm_task_customer_group_id" name="crm_task_customer_group_id" class="form-control" size="1">
                    <?php
                    $CustomerGroup = new CustomerGroup(0);
                    echo $CustomerGroup->getOptions(isset($crm_task_customer_group_id)?$crm_task_customer_group_id:"0");
                    ?>
                  </select>
                </div>
              </div>
            <div class="col-sm-4">
                <div class="form-group">
                  <label for="wc_due_date">Execution Time<sup>*</sup></label>
                  <div class="input-group date">
                    <input type='text' class="form-control" id="crm_task_execution_time" name="crm_task_execution_time" placeholder="YYYY-MM-DD HH:II" value="<?=isset($crm_task_execution_time)?date("Y-m-d H:i", strtotime($crm_task_execution_time)):date('Y-m-d H:i');?>" />
                    <span class="input-group-addon">
                    <label style="margin-bottom:0px;" for="crm_task_execution_time"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                    </span> </div>
                  <script type="text/javascript">
                        $('#crm_task_execution_time').datetimepicker({
                            format: 'yyyy-mm-dd hh:ii',
							autoclose: true,        
							todayBtn: true,
							fontAwesome : true
                        });
                </script> 
                </div>
              </div>
              
              <div class="col-sm-4">
              <div class="form-group">
                <label class="form-control-label">Task Status</label>
                <div class="col-md-12">
                  <div class="row">
                    <label class="radio-inline" for="inline-radio1">
                      <input id="inline-radio1" name="crm_task_status" checked class="crm_task_status" value="1" type="radio" <?=isset($crm_task_status) && $crm_task_status == 1 ? "checked=\"checked\"":"";?> >
                      Active </label>
                    &nbsp;  &nbsp;
                    <label class="radio-inline" for="inline-radio2">
                      <input id="inline-radio2" name="crm_task_status" class="crm_task_status" value="0" type="radio" <?=isset($crm_task_status) && $crm_task_status == 0 ? "checked=\"checked\"":"";?>>
                      Deactive </label>
                  </div>
                </div>
              </div>
            </div>
        </div>
        
        <!--/row-->
        
          
        
             
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
      <button type="button" onClick="addCrmTask();" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <?=$btnText?></button>
      
    </div>
    	<input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"emailtemplate/addcrmtask";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="crm_task_id" name="crm_task_id" value="<?=isset($crm_task_id)?$crm_task_id:"0";?>"  />
      </form>
    </div>
    
  </div>
</div>
<!--/col--> 
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> CRM Task List </div>
      <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>Run</th>
              <th>Name</th>
              <th>Subject</th>
              <th>Template</th>
              <th>Group</th>
              <th>Execution</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
			$CrmTask = new CrmTask(0);
			echo $CrmTask->getRecords();
			?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<!--/col-->
</div>
<script type="text/javascript">
function addCrmTask()
{
	var formFields	=	"crm_task_name, crm_task_subject, crm_task_template_id, crm_task_customer_group_id, crm_task_execution_time";
	
	if(validateFields(formFields))
	{
		var data = $("#addcrmtask").serializeFormJSON();
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
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

function executeTask(crm_task_id)
{
	if(confirm("Are sure to execute this Task...?"))
	{
		var data={
			action	:	"executetask",
			crm_task_id	:	crm_task_id			
		};
		$.ajax({
			xhr: function() {
					var xhr = new window.XMLHttpRequest();
					xhr.addEventListener("progress", function(evt){
						if (evt.lengthComputable) {
							console.log(evt.loaded);
						}
					}, false);
				return xhr;
			},
			type:'POST', data:data, url:sitePath +'ajax.php', 		
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
}

function statusAction(field)
{
	var data={
			action	:	"emailtemplate/updatecrmtaskstatus",
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

</script> 
