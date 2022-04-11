<div class="row">
  <div class="col-sm-12 col-md-6">
    <div class="card">
      <div class="card-header"> <strong><?=$formHeading?></strong></div>
      <form id="addemployeereleaving" name="addemployeereleaving" enctype="multipart/form-data">
      <div class="card-block">
        <div class="row">
			<div class="col-sm-12">
				<div class="card">
				  <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
					<div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($user_image,50)?>"> </div>
					<div class="px-2 justify-content-center">
					  <div class="text-value-sm text-primary text-center"><?php echo $user_fname?> <?php echo $user_lname?></div>
					</div>
				  </div>
				</div>
			</div>
						
          <div class="col-sm-12">
              <div class="form-group">
                <label for="releaving_date">Releaving Date<sup>*</sup></label>
                <input class="form-control" id="releaving_date" name="releaving_date" maxlength="10" placeholder="YYYY-MM-DD" type="text" value="<?=isset($releaving_date)?$releaving_date:"";?>">
              </div>
				<script type="text/javascript">
                    $('#releaving_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,					
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
          </div> 
         <div class="col-sm-12">
            <div class="form-group">
              <label for="releaving_start_position">Employee start work position<sup></sup></label>
              <input class="form-control" id="releaving_start_position" name="releaving_start_position" maxlength="50" placeholder="Enter employee start work position" type="text" value="<?=isset($releaving_start_position)?$releaving_start_position:"";?>">
            </div>
          </div> 
         <div class="col-sm-12">
            <div class="form-group">
              <label for="releaving_last_position">Employee last work position<sup></sup></label>
              <input class="form-control" id="releaving_last_position" name="releaving_last_position" maxlength="50" placeholder="Enter employee last work position" type="text" value="<?=isset($releaving_last_position)?$releaving_last_position:"";?>">
            </div>
          </div>
		<?php if($releaving_id > 0){?>
		<div class="col-sm-12 text-center">
		<a type="button" target="new" href="<?php echo DOC::EMPEXPLETTER(md5($releaving_id))?>" class="btn btn-success mt-1"><i class="fa fa-download"></i> Downlaod experience letter</a><br/>
          <button type="button" id="sent_exp_letetr_to_employee" onClick="sendExpLetter();" class="btn btn-info mt-1"><i class="fa fa-check"></i> Click to send Experience letter to <?php echo $user_fname?></button><br/><span class="text-muted">
		  <?php if(isset($releaving_is_exp_letter_sent) && $releaving_is_exp_letter_sent == 1){?>
		  Experience letter was sent on <?php echo dateView($releaving_exp_letter_sent_date,'FULL')?>
		 <?php  
		  }else echo "Experience letter not sent yet."?></span>
        </div>		  
		<?php }?>            
        </div>     
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh"></i> Reset</button>
      <button type="button" onClick="confirmMessage.Set('Are you sure to add employee releaving information...?', 'addEmployeeReleaving');" class="btn btn-success submission_handler_btn"><i class="fa fa-check"></i> Save</button>
      
    </div>
    	<input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="releaving_id" name="releaving_id" value="<?=isset($releaving_id)?$releaving_id:"0";?>"  />
		<input type="hidden" id="releaving_user_id" name="releaving_user_id" value="<?=isset($user_id)?$user_id:"0";?>"  />
      </form>
    </div>
    
  </div>
</div>
<!--/col--> 

<!--/col-->
</div>
<script type="text/javascript">
function addEmployeeReleaving()
{
	var formFields	=	"releaving_date,releaving_last_position";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val(),
			field_handler:"user_image"				
		};
		
		data = $.extend(data, $("#addemployeereleaving").serializeFormJSON());
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
					$("#releaving_user_id").val(arr[2]);
				}
				message(arr[1]);
			}
		})	
	}
}
function sendExpLetter()
{	
	var dataAjax={
				action			:	'employee/sendexperienceletter',
				releaving_id	:	$("#releaving_id").val()					
			};
	$.ajax({type:'POST', data:dataAjax, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			dissableSubmission();
		},		
		success:function(output){
			enableSubmission(output);
			var arr	=	JSON.parse(output);		
			toastMessage(arr[1]);
		}
	})	
}
</script>
