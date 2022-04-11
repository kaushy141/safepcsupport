<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?php echo $formHeading; ?></strong> <small>Form</small> </div>
      <form id="addempleaveform" name="addempleaveform">
        <div class="card-block">
          <div class="row">			  
			
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="leave_employee_id">Select Employee<sup>*</sup></label>
                <select id="leave_employee_id" name="leave_employee_id" class="form-control" size="1">
                  <?php
                $Employee = new Employee(0);
				echo $Employee->getUserOption(0, isset($leave_employee_id)?$leave_employee_id:"0");
				?>
                </select>
              </div>
            </div>
			
			<div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="leave_from_time">From<sup>*</sup></label>
                <input class="form-control" id="leave_from_time" name="leave_from_time" maxlength="20" placeholder="YYYY-MM-DD HH:mm AA" type="text" value="<?=isset($leave_from_time)?$leave_from_time:"";?>">
              </div>
				<script type="text/javascript">
				$(function () {
					$('#leave_from_time').datetimepicker({
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
                <label for="leave_end_time">Till<sup>*</sup></label>
                <input class="form-control" id="leave_end_time" name="leave_end_time" maxlength="20" placeholder="YYYY-MM-DD HH:mm AA" type="text" value="<?=isset($leave_end_time)?$leave_end_time:"";?>">
              </div>
				<script type="text/javascript">
				$(function () {
					$('#leave_end_time').datetimepicker({
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
            <div class="col-sm-12">
              <div class="form-group">
                <label for="leave_reason">Reason<sup>*</sup></label>
                <textarea id="leave_reason" name="leave_reason" rows="2" class="form-control" placeholder="Leave reason..."><?=isset($leave_reason)?$leave_reason:"";?>
</textarea>
              </div>
            </div> 
			  
			<div class="col-xs-12 col-sm-6 col-md-4">
			  <div class="form-group">
				<label for="leave_is_extra_hours"><i class="fa fa-clock-o fa-lg m-t-2"></i> &nbsp;Mark as Extra hours ? </label>
				<label class="switch switch-icon switch-pill switch-success pull-right">
				  <input class="switch-input" id="leave_is_extra_hours" value="1" name="leave_is_extra_hours" type="checkbox" <?=(isset($leave_is_extra_hours) && $leave_is_extra_hours == 1)?"checked":"";?>>
				  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
			  </div>
			</div>
			
			<div class="col-xs-12 col-sm-6 col-md-4">
			  <div class="form-group">
				<label for="leave_is_approved"><i class="fa fa-envelope-o fa-lg m-t-2"></i> &nbsp;is this approoved ? </label>
				<label class="switch switch-icon switch-pill switch-success pull-right">
				  <input class="switch-input" id="leave_is_approved" value="1" name="leave_is_approved" type="checkbox" <?=(isset($leave_is_approved) && $leave_is_approved == 1)?"checked":"";?>>
				  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
			  </div>
			</div>
          </div>
			
		 
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to save employee Information...?', 'saveEmpLeave')" class="btn btn-sm btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i>
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=$action;?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="leave_id" name="leave_id" value="<?=isset($leave_id)?$leave_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">

function saveEmpLeave()
{
	if(validateFields("leave_employee_id, leave_from_time, leave_end_time, leave_reason"))
	{
		var data={
					action	:	$("#action").val()						
				};
		data = $.extend(data, $("#addempleaveform").serializeFormJSON());
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
					$("#leave_id").val(arr[2]);				
				}
				message(arr[1]);
			}
		})	
	}
}
</script> 