<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?php echo $formHeading; ?></strong> <small>Form</small> </div>
      <form id="addempleaverform" name="addempleaverform">
        <div class="card-block">
         <div class="row">
			<div class="col-md-4 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="eil_employee_name">Employee Name<sup>*</sup></label>
                <input class="form-control" id="eil_employee_name" name="eil_employee_name" maxlength="50" placeholder="Enter employee name" type="text" onkeyup="getDropdown(this, 'ContractEmployee<=>customer_email',false)"  value="<?=isset($eil_employee_name)?$eil_employee_name:"";?>">
              </div>
            </div>		
		 </div>
		 <div class="row">
			 <div class="col-sm-12">
			 <table id="tblSortable" class="table table-bordered table-responsive">
			 	<tr>
					<th>Check</th> 
					<th width="30%">Item</th> 
					<th>Comments</th>
					<th>Date</th>
					<th>Completed</th>
				</tr>
			 <?php 
			 $itemCategory = array();
			 if($itemList){
				 foreach($itemList as $item){
					 if(!in_array($item['checklist_item_category'], $itemCategory)){
						 array_push($itemCategory, $item['checklist_item_category']);
						 ?>
				 <tr><td colspan="5"><b><?php echo $item['checklist_item_category']?></b></td></tr>
				 <?php
					 }
			?>
			 	<tr>
			 		<td><input type="checkbox" name="checkbox_item[<?php echo $item['checklist_item_id']?>]" value="<?php echo $item['checklist_item_id']?>" <?php if(isset($item['eili_checklist_item_id']) && $item['eili_checklist_item_id'] != 0) echo "checked";?>></td>
					<td><?php echo $item['checklist_item_name']?></td>
					<td><div><textarea class="form-control" name="checkbox_comment[<?php echo $item['checklist_item_id']?>]"><?php if(isset($item['eili_comment']) && $item['eili_comment'] != "") echo $item['eili_comment'];?></textarea></div></td>
					<td><div>
					<input class="form-control" id="checkbox_date_<?php echo $item['checklist_item_id']?>" name="checkbox_date[<?php echo $item['checklist_item_id']?>]" placeholder="yyyy-mm-dd" readonly type="text" value="<?php if(isset($item['eili_completed_date']) && $item['eili_completed_date'] != "0000-00-00") echo $item['eili_completed_date'];?>">
				  </div>
					<script type="text/javascript">
						$('#checkbox_date_<?php echo $item['checklist_item_id']?>').datepicker({
							format: "yyyy-mm-dd",
							autoclose:true,					
							daysOfWeekHighlighted: '0,6',
							todayHighlight:true
						});
					</script></td>
					<td><label class="switch switch-icon switch-pill switch-success pull-right">
				  <input class="switch-input" id="checkbox_completed_<?php echo $item['checklist_item_id']?>" value="1" name="checkbox_completed[<?php echo $item['checklist_item_id']?>]" type="checkbox" <?php if(isset($item['eili_completed']) && $item['eili_completed'] != 0) echo "checked";?>>
				  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></td>
			 	</tr>
			<?php
				 }
			 }
			 ?>
				 
			 </table>
		 </div>
		</div>
		<div class="row">      
            
			<div class="col-sm-12">
              <div class="form-group">
                <label for="eil_relevant_issue">Relevent issues to department <sup>*</sup></label>
                <textarea id="eil_relevant_issue" name="eil_relevant_issue" rows="2" class="form-control" placeholder="Enter Relevent issues to department ..."><?=isset($eil_relevant_issue)?$eil_relevant_issue:"";?></textarea>
              </div>
            </div>
          </div>
			<div class="row"><div class="col-md-12">&nbsp;</div></div>
		  <div class="row">
			<div class="col-md-6 col-sm-6 bg-warning" style="padding-top: 12px;">
				<div id="block_eil_employee_signature" style="display: <?php echo (isset($eil_employee_signature) && $eil_employee_signature!="") ? "block" : "none"; ?>">				
				  <img id="" alt="" src="<?php echo $app->basePath($eil_employee_signature);?>" class="img-responsive" /><br/>
					<button type="button" class="btn btn-default" onClick="changeSignature('eil_employee_signature')">Change Signature</button>
				</div>
				<div id="form_eil_employee_signature" style="display: <?php echo (isset($eil_employee_signature) && $eil_employee_signature!="") ? "none" : "block"; ?>">
					<div class="form-group">
					  <div id="signature-pad-block1" class="m-signature-pad">
						<div class="m-signature-pad-body">
						  <canvas class="eil_employee_signature"></canvas>
						</div>                
					  </div>
					</div>
					<div class="form-group">
					  <button type="button" class="btn btn-default save" data-action="save-png" onclick="saveSignature(event, 'eil_employee_signature');">Upload Employee Signature</button>              
					  <button type="button" class="btn btn-danger clear" onclick="clearSignature('eil_employee_signature');" data-action="clear">Clear</button>
					  <input type="hidden" value="" name="eil_employee_signature" id="eil_employee_signature" />
					</div>
				</div>
				<p><center>Employee Signature</center></p>
				<div class="col-xs-12">
				  <div class="form-group">
					<label for="eil_employee_sign_date">Employee Signing date<sup>*</sup></label>
					<input class="form-control" id="eil_employee_sign_date" name="eil_employee_sign_date" maxlength="50" placeholder="yyyy-mm-dd" readonly type="text" value="<?=isset($eil_employee_sign_date)?$eil_employee_sign_date:"";?>">
				  </div>
					<script type="text/javascript">
						$('#eil_employee_sign_date').datepicker({
							format: "yyyy-mm-dd",
							autoclose:true,					
							daysOfWeekHighlighted: '0,6',
							todayHighlight:true
						});
					</script> 
            	</div>
          	</div>
			  
			<div class="col-md-6 col-sm-6 bg-info" style="padding-top: 12px;">
				<div id="block_eil_hr_signature" style="display: <?php echo (isset($eil_hr_signature) && $eil_hr_signature!="") ? "block" : "none"; ?>">				
				  <img id="" alt="" src="<?php echo $app->basePath($eil_hr_signature);?>" class="img-responsive" /><br/>
					<button type="button" class="btn btn-default" onClick="changeSignature('eil_hr_signature')">Change Signature</button>
				</div>
				<div id="form_eil_hr_signature" style="display: <?php echo (isset($eil_hr_signature) && $eil_hr_signature!="") ? "none" : "block"; ?>">
					<div class="form-group">
					  <div id="signature-pad-block2" class="m-signature-pad">
						<div class="m-signature-pad-body">
						  <canvas class="eil_hr_signature"></canvas>
						</div>                
					  </div>
					</div>
					<div class="form-group">
					  <button type="button" class="btn btn-default save" data-action="save-png" onclick="saveSignature(event, 'eil_hr_signature');">Upload HR Signature</button>              
					  <button type="button" class="btn btn-danger clear" onclick="clearSignature('eil_hr_signature');" data-action="clear">Clear</button>
					  <input type="hidden" value="" name="eil_hr_signature" id="eil_hr_signature" />
					</div>
				</div>
				<p><center>HR Mgr Signature</center></p>
				<div class="col-xs-12">
				  <div class="form-group">
					<label for="eil_hr_signature_date">Employee Signing date<sup>*</sup></label>
					<input class="form-control" id="eil_hr_signature_date" name="eil_hr_signature_date" maxlength="50" placeholder="yyyy-mm-dd" readonly type="text" value="<?=isset($eil_hr_signature_date)?$eil_hr_signature_date:"";?>">
				  </div>
					<script type="text/javascript">
						$('#eil_hr_signature_date').datepicker({
							format: "yyyy-mm-dd",
							autoclose:true,					
							daysOfWeekHighlighted: '0,6',
							todayHighlight:true
						});
					</script> 
            	</div>
          	</div> 
		 </div>
         <div class="row" style="margin-top: 30px;">			 
			<?php if(isset($eil_submitted) && $eil_submitted == 1 && isAdmin()):?>
			 <div class="col-xs-12 col-sm-6 col-md-4">
			  <div class="form-group">
				<label for="eil_review_status"><i class="fa fa-envelope-o fa-lg m-t-2"></i> &nbsp;Mark Leaver review success</label>
				<label class="switch switch-icon switch-pill switch-success pull-right">
				  <input class="switch-input" id="eil_review_status" value="1" name="eil_review_status" type="checkbox" <?php if(isset($eil_review_status) && $eil_review_status == 1) echo "checked"; ?>>
				  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
			  </div>
			</div>
			<?php endif; ?>
		 </div>
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to save leaver Information...?', 'addLeaverInfo')" class="btn btn-sm btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i>
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=$action;?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="eil_id" name="eil_id" value="<?=isset($eil_id)?$eil_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">

function addLeaverInfo()
{
	if(validateFields("eil_employee_name, eil_relevant_issue"))
	{
		var data={
					action	:	$("#action").val()						
				};
		data = $.extend(data, $("#addempleaverform").serializeFormJSON());
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
					$("#eil_id").val(arr[2]);
				}
				message(arr[1]);
			}
		})	
	}
}

</script> 


<script type="text/javascript">
var block1Wrapper = document.getElementById("signature-pad-block1");
var block1Canvas = block1Wrapper.querySelector("canvas.eil_employee_signature");
var block1SignaturePad = new SignaturePad(block1Canvas);
	
var block2Wrapper = document.getElementById("signature-pad-block2");
var block2Canvas = block2Wrapper.querySelector("canvas.eil_hr_signature");
var block2SignaturePad = new SignaturePad(block2Canvas);	
	

function changeSignature(type){
	$("#block_"+type).hide();
	$("#form_"+type).show();
}

function clearSignature(type)
{
	if(type == 'eil_employee_signature')
		block1SignaturePad.clear();
	else
		block2SignaturePad.clear();
		
	$("#"+type).val('');
}

function saveSignature(event, type)
{
	if(type == 'eil_employee_signature'){
		var SignaturePad = block1SignaturePad;
	}else{
		var SignaturePad = block2SignaturePad;
	}
	if (SignaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
		var data={
			action	:	'collection/savesignature',
			signature:SignaturePad.toDataURL()				
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
					$("#"+type).val(arr[2]);
				}
				else
				{
					$("#"+type).val('');
				}
				message(arr[1]);
			}
		})
    }
}	
$(document).ready(function() {
    $('#tblSortable').DataTable( {
        
    } );
} );
</script>