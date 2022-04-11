<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?php echo $formHeading; ?></strong> <small>Form</small> </div>
      <form id="addempnoticeform" name="addempnoticeform">
        <div class="card-block">
          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="notice_emp_id">Employee Name<sup>*</sup></label>
                <select id="notice_emp_id" name="notice_emp_id" class="form-control" size="1">
				  <?php
				 $user = new Employee(0);
				 echo $user->getUserOptionSelect(0, isset($notice_emp_id)?$notice_emp_id:0);
				 ?>
				  </select>
              </div>
            </div>			  
			
            
			<div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="notice_date">Notice date time<sup>*</sup></label>
                <input class="form-control" id="notice_date" name="notice_date" maxlength="20" placeholder="YYYY-MM-DD HH:mm AA" type="text" value="<?=isset($notice_date)?$notice_date:"";?>">
              </div>
				<script type="text/javascript">
				$(function () {
					$('#notice_date').datetimepicker({
						format: 'yyyy-mm-dd HH:ii P',
						autoclose:true,
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true,
						fontAwesome : true,
						showMeridian: true,
					});
				});
            </script> 
            </div> 
			  
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<label for="notice_attachement_file">Notice Attachement<sup></sup></label>
				  <input type="file" name="notice_attachement_file" id="notice_attachement_file" class="btn btn-primary btn-block save" data-action="save-png" onchange="filesUpload('notice_attachement_file');" />
				  <input type="hidden" name="notice_attachement" id="notice_attachement" value="" />
				  <span class="file_uploader"></span>
				</div>
			</div>
			 
            <div class="col-sm-12">
              <div class="form-group">
                <label for="notice_reason">Notice Reason<sup>*</sup></label>
                <textarea id="notice_reason" name="notice_reason" rows="2" class="form-control" placeholder="Notice reason..."><?=isset($notice_reason)?$notice_reason:"";?>
</textarea>
              </div>
            </div> 
			  
			<div class="col-xs-12 col-sm-6 col-md-4">
			  <div class="form-group">
				<label for="notice_status"><i class="fa fa-envelope-o fa-lg m-t-2"></i> &nbsp;Notice status ? </label>
				<label class="switch switch-icon switch-pill switch-success pull-right">
				  <input class="switch-input" id="notice_status" value="1" name="notice_status" type="checkbox" <?=(isset($notice_status) && $notice_status == 1)?"checked":"";?>>
				  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
			  </div>
			</div>
          </div>
			
		 
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to save employee notice...?', 'saveEmpNotice')" class="btn btn-sm btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i>
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=$action;?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="notice_id" name="notice_id" value="<?=isset($notice_id)?$notice_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">
function notice_attachement_file_callback(path){
	$("#notice_attachement").val(path);
}
function saveEmpNotice()
{
	if(validateFields("notice_emp_id, notice_date, notice_reason"))
	{
		var data={
					action	:	$("#action").val()						
				};
		data = $.extend(data, $("#addempnoticeform").serializeFormJSON());
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
					$("#notice_id").val(arr[2]);				
				}
				message(arr[1]);
			}
		})	
	}
}
</script> 