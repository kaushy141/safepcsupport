<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?php echo $formHeading; ?></strong> <small>Form</small> </div>
      <form id="addcompanyrecordform" name="addcompanyrecordform">
        <div class="card-block">
          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="training_name">Trainee name<sup>*</sup></label>
                <input class="form-control" id="training_trainee_name" name="training_trainee_name" maxlength="50" placeholder="Enter trainee name" type="text" value="<?=isset($training_trainee_name)?$training_trainee_name:"";?>">
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="training_type">Training type<sup>*</sup></label>
                <input class="form-control" id="training_type" name="training_type" maxlength="50" placeholder="Enter training type" type="text" value="<?=isset($training_type)?$training_type:"";?>">
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="training_date">Training date<sup>*</sup></label>
                <input class="form-control" id="training_date" name="training_date" maxlength="50" placeholder="yyyy-mm-dd" readonly type="text" value="<?=isset($training_date)?$training_date:"";?>">
              </div>
				<script type="text/javascript">
                    $('#training_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,					
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
            </div>
			 <div class="col-sm-12">
              <div class="form-group">
                <label for="training_description">Training description<sup>*</sup></label>
                <textarea id="training_description" name="training_description" rows="2" class="form-control" placeholder="Enter training description ..."><?=isset($training_description)?$training_description:"";?></textarea>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="training_job_title">Trainee Job Title <sup>*</sup></label>
                <input class="form-control" id="training_job_title" name="training_job_title" maxlength="50" placeholder="Enter training job title" type="text" value="<?=isset($training_job_title )?$training_job_title :"";?>">
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="training_trainer_name">Training trainer name<sup>*</sup></label>
                <input class="form-control" id="training_trainer_name" name="training_trainer_name" maxlength="50" placeholder="Enter trainer name" name="text" value="<?=isset($training_trainer_name)?$training_trainer_name:"";?>">
              </div>
            </div> 
			  
			<div class="col-sm-12">
              <div class="form-group">
                <label for="training_learning_detail">Training learning detail<sup>*</sup></label>
                <textarea id="training_learning_detail" name="training_learning_detail" rows="2" class="form-control" placeholder="Enter training learning detail ..."><?=isset($training_learning_detail)?$training_learning_detail:"";?></textarea>
              </div>
            </div>
          </div>
			
		  <div class="row">
			<div class="col-md-6 col-sm-6 bg-warning" style="padding-top: 12px;">
				<div id="signature_saved_image_trainee" style="display: <?php echo (isset($training_trainee_signature) && $training_trainee_signature!="") ? "block" : "none"; ?>">				
				  <img id="" alt="" src="<?php echo $app->basePath($training_trainee_signature);?>" class="img-responsive" /><br/>
					<button type="button" class="btn btn-default" onClick="changeTraineeSignature()">Change Signature</button>
				</div>
				<div id="signature_saved_image_trainee_form" style="display: <?php echo (isset($training_trainee_signature) && $training_trainee_signature!="") ? "none" : "block"; ?>">
					<div class="form-group">
					  <div id="signature-pad-trainee" class="m-signature-pad">
						<div class="m-signature-pad-body">
						  <canvas class="trainee"></canvas>
						</div>                
					  </div>
					</div>
					<div class="form-group">
					  <button type="button" class="btn btn-default save" data-action="save-png" onclick="saveTraineeSignature(event);">Upload Trainee Signature</button>              
					  <button type="button" class="btn btn-danger clear" onclick="clearTraineeSignature(event);" data-action="clear">Clear</button>
					  <input type="hidden" value="" name="training_trainee_signature" id="training_trainee_signature" />
					</div>
				</div>
				<p><center>Trainee Signature</center></p>
          	</div>
			  
			 <div class="col-md-6 col-sm-6 bg-info" style="padding-top: 12px;">
				<div id="signature_saved_image_trainer" style="display: <?php echo (isset($training_trainer_signature) && $training_trainer_signature!="") ? "block" : "none"; ?>">				
				  <img id="" alt="" src="<?php echo $app->basePath($training_trainer_signature);?>" class="img-responsive" /><br/>
					<button type="button" class="btn btn-default" onClick="changeTrainerSignature()">Change Signature</button>
				</div>
				<div id="signature_saved_image_trainer_form" style="display: <?php echo (isset($training_trainer_signature) && $training_trainer_signature!="") ? "none" : "block"; ?>">
					<div class="form-group">
					  <div id="signature-pad-trainer" class="m-signature-pad">
						<div class="m-signature-pad-body">
						  <canvas class="trainer"></canvas>
						</div>                
					  </div>
					</div>
					<div class="form-group">
					  <button type="button" class="btn btn-default save" data-action="save-png" onclick="saveTrainerSignature(event);">Upload Trainer Signature</button>              
					  <button type="button" class="btn btn-danger clear" onclick="clearTrainerSignature(event);" data-action="clear">Clear</button>
					  <input type="hidden" value="" name="training_trainer_signature" id="training_trainer_signature" />
					</div>
				</div>
				<p><center>Trainer Signature</center></p>
          	</div>
		 </div>
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to save training Information...?', 'addTrainingInfo')" class="btn btn-sm btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i>
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=$action;?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="training_id" name="training_id" value="<?=isset($training_id)?$training_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">

function addTrainingInfo()
{
	if(validateFields("training_trainee_name, training_type, training_date, training_description, training_job_title, training_trainer_name, training_learning_detail"))
	{
		var data={
					action	:	$("#action").val(),
					field_handler : "training_trademark"						
				};
		data = $.extend(data, $("#addcompanyrecordform").serializeFormJSON());
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
					$("#training_id").val(arr[2]);
					$("#action").val("company_resource/updatecompanyrecord");					
					$('#tblSortable').DataTable().ajax.reload();
				}
				message(arr[1]);
			}
		})	
	}
}

function statusAction(field)
{
	var data={
			action	:	"updatecompanyrecordstatus",
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


<script type="text/javascript">
var traineeWrapper = document.getElementById("signature-pad-trainee");
var traineeCanvas = traineeWrapper.querySelector("canvas.trainee");
var traineeSignaturePad = new SignaturePad(traineeCanvas);

function changeTraineeSignature(){
	$("#signature_saved_image_trainee").hide();
	$("#signature_saved_image_trainee_form").show();
}

function clearTraineeSignature(event)
{
	traineeSignaturePad.clear();
	$("#employee_signature_trainee").val('');
}

function saveTraineeSignature(event)
{
	if (traineeSignaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
		var data={
			action	:	'collection/savesignature',
			signature:traineeSignaturePad.toDataURL()				
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
					$("#training_trainee_signature").val(arr[2]);
					$("#signature_saved_image_trainee").html('<img src="'+arr[3]+'" class="img-responsive" />');
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
	
	
var trainerWrapper = document.getElementById("signature-pad-trainer");
var trainerCanvas = trainerWrapper.querySelector("canvas.trainer");
var trainerSignaturePad = new SignaturePad(trainerCanvas);

function changeTrainerSignature(){
	$("#signature_saved_image_trainer").hide();
	$("#signature_saved_image_trainer_form").show();
}

	
function clearTrainerSignature(event)
{
	trainerSignaturePad.clear();
	$("#employee_signature_trainer").val('');
}

function saveTrainerSignature(event)
{
	if (trainerSignaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
		var data={
			action	:	'collection/savesignature',
			signature:trainerSignaturePad.toDataURL()				
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
					$("#training_trainer_signature").val(arr[2]);
					$("#signature_saved_image_trainer").html('<img src="'+arr[3]+'" class="img-responsive" />');
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