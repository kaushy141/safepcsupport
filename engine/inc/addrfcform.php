<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small> </div>
      <form id="addrfcform" name="adddispoalform" enctype="multipart/form-data">
        <div class="card-block">
          <div class="row">
          	<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="rfc_code">Change Number<sup>*</sup></label>
                <input class="form-control" readonly="readonly" id="rfc_code" name="rfc_code" placeholder="RFC Number" maxlength="9" type="text" value="<?=isset($rfc_code)?$rfc_code:Rfc::getRfcNumber();?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="rfc_require_by_date">Required by date<sup>*</sup></label>
                <div class="input-group date">
                  <input type='text' class="form-control" id="rfc_require_by_date" name="rfc_require_by_date" placeholder="YYYY-MM-DD" value="<?=isset($rfc_require_by_date)?date("Y-m-d", strtotime($rfc_require_by_date)):date('Y-m-d');?>" />
                  <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="rfc_require_by_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                  </span> </div>
                <script type="text/javascript">
                    $('#rfc_require_by_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,
						<?php if(!isset($complaint_id))echo "startDate  : '".date('Y-m-d')."',";?>						
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="rfc_date_of_request">Date of request<sup>*</sup></label>
                <div class="input-group date">
                  <input type='text' class="form-control" id="rfc_date_of_request" name="rfc_date_of_request" placeholder="YYYY-MM-DD" value="<?=isset($rfc_date_of_request)?date("Y-m-d", strtotime($rfc_date_of_request)):date('Y-m-d');?>" />
                  <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="rfc_date_of_request"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                  </span> </div>
                <script type="text/javascript">
                    $('#rfc_date_of_request').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,
						<?php if(!isset($complaint_id))echo "startDate  : '".date('Y-m-d')."',";?>						
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="rfc_serial_number">Serial Number<sup>*</sup></label>
                <input class="form-control" id="rfc_serial_number" name="rfc_serial_number" placeholder="Enter serial number" maxlength="50" type="text" value="<?=isset($rfc_serial_number)?$rfc_serial_number:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="rfc_requester">Requester<sup>*</sup></label>
                <input class="form-control" id="rfc_requester" name="rfc_requester" placeholder="Enter Requester" maxlength="100" type="text" value="<?=isset($rfc_requester)?$rfc_requester:"";?>" >
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="rfc_circulation_list">Circulation list<sup>*</sup></label>
                <input class="form-control" id="rfc_circulation_list" name="rfc_circulation_list" placeholder="Enter  circulation list" maxlength="5000" type="text" value="<?=isset($rfc_circulation_list)?$rfc_circulation_list:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <label for="rfc_request_details">Change Request details<sup>*</sup></label>
                <textarea class="form-control" id="rfc_request_details" name="rfc_request_details" placeholder="Enter  request details" maxlength="5000" type="text" ><?=isset($rfc_request_details)?$rfc_request_details:"";?></textarea>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <label for="rfc_request_reason">Reason for request <sup>*</sup></label>
                <textarea class="form-control" id="rfc_request_reason" name="rfc_request_reason" placeholder="Enter   request reason" maxlength="5000" type="text" ><?=isset($rfc_request_reason)?$rfc_request_reason:"";?></textarea>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <label for="rfc_service_owner_approval">System or service owner approval <sup>*</sup></label>
                <textarea class="form-control" id="rfc_service_owner_approval" name="rfc_service_owner_approval" placeholder="Enter System or service owner approval" maxlength="5000" type="text" ><?=isset($rfc_service_owner_approval)?$rfc_service_owner_approval:"";?></textarea>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <label for="rfc_back_out_paln">Back out plan <sup>*</sup></label>
                <textarea class="form-control" id="rfc_back_out_paln" name="rfc_back_out_paln" placeholder="Enter out plan" maxlength="5000" type="text" ><?=isset($rfc_back_out_paln)?$rfc_back_out_paln:"";?></textarea>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <label for="rfc_not_approved_reason">If <b class="text-danger">NOT</b> Approved reason <sup></sup></label>
                <textarea class="form-control" id="rfc_not_approved_reason" name="rfc_not_approved_reason" placeholder="Enter not approved reason" maxlength="5000" type="text" ><?=isset($rfc_not_approved_reason)?$rfc_not_approved_reason:"";?></textarea>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <label for="rfc_completion_acceptance">If <b class="text-success">Approved</b> completion acceptance criteria <sup></sup></label>
                <textarea class="form-control" id="rfc_completion_acceptance" name="rfc_completion_acceptance" placeholder="Enter completion acceptance criteria" maxlength="5000" type="text" ><?=isset($rfc_completion_acceptance)?$rfc_completion_acceptance:"";?></textarea>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <div id="signature-pad" class="m-signature-pad">
                  <div class="m-signature-pad-body">
                    <canvas></canvas>
                  </div>
                </div>
              </div>
              <div class="form-group">
              	<button type="button" class="btn btn-default text-danger clear" onclick="clearSignature(event);" data-action="clear">Clear Signature</button>
                <button type="button" class="btn btn-default text-success save" data-action="save-png" onclick="saveSignature(event);">Upload Signature</button>
                
                <input type="hidden" name="rfc_signature" id="rfc_signature" value="" />
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group" id="signature_saved_image" style="border:dashed; border-color:#CCC; min-height:190px;">
                <?php if(isset($rfc_signature) && $rfc_signature!=""):?>
                <img src="<?php echo $app->basePath($rfc_signature);?>" class="img-responsive" />
                <?php endif; ?>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="rfc_name">Name<sup>*</sup></label>
                <input class="form-control" id="rfc_name" name="rfc_name" placeholder="Enter name here" maxlength="50" type="text" value="<?=isset($rfc_name)?$rfc_name:"";?>">
              </div>
            </div>
            		<div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="rfc_date">Date<sup>*</sup></label>
                <div class="input-group date">
                  <input type='text' class="form-control" id="rfc_date" name="rfc_date" placeholder="YYYY-MM-DD" value="<?=isset($rfc_date)?date("Y-m-d", strtotime($rfc_date)):date('Y-m-d');?>" />
                  <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="rfc_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                  </span> </div>
                <script type="text/javascript">
                    $('#rfc_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,
						<?php if(!isset($complaint_id))echo "startDate  : '".date('Y-m-d')."',";?>						
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
              </div>
            </div>
            	</div>
            </div>
          </div>
          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh m-t-0"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to add Change Management Request...?', 'addrfcrecord');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-0"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addrfcrecord";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="rfc_id" name="rfc_id" value="<?=isset($rfc_id)?$rfc_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
var wrapper = document.getElementById("signature-pad");
var canvas = wrapper.querySelector("canvas");
var signaturePad = new SignaturePad(canvas);

function clearSignature(event)
{
	signaturePad.clear();
	$("#signature_link").val('');
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
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#rfc_signature").val(arr[2]);
					$("#signature_saved_image").html('<img src="'+arr[3]+'" class="img-responsive" />');
				}
				else
				{
					$("#signature_link").val('');
					$("#signature_saved_image").html(EMPTY_IMAGE_BOX);
				}
				message(arr[1]);
			}
		})
    }
}
</script>
<script type="text/javascript">
function addrfcrecord()
{
	var formFields	= "rfc_code, rfc_require_by_date, rfc_date_of_request, rfc_serial_number, rfc_requester, rfc_circulation_list, rfc_request_details, rfc_request_reason, rfc_service_owner_approval, rfc_back_out_paln, rfc_name, rfc_date";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()		
		};
		
		data = $.extend(data, $("#addrfcform").serializeFormJSON());
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
					if($("#eqipment_disposal_id").val() == 0)
					$('#addrfcform').trigger("reset");
					
					$('#tblSortable').DataTable().ajax.reload();
				}
				message(arr[1]);
			}
		})	
	}
}


</script>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i>Log List </div>
      <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Date</th>
              <th>Request</th>
              <th>Requester</th>
              <th>RFC Code</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">
function removeRfc(id)
{
	if(confirm("Are you sure to remove this record... ?"))
	{
		var data={
			action	:	"company_resource/removerfcrecord",
			id		:	id			
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...",0);
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);
				if(arr[0] == 200)
				$("#row_"+id).remove();
				message(arr[1],2000);
			}
		});	
	}
}
var data = {
				"action"	:	"viewrfcrecord",				
		   };
$(document).ready(function() {
    $('#tblSortable').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax":  {
            "url": "<?=$app->basePath("server_processing.php")?>",
            "type": "POST",
			"data": data
        },
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [4], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 1 ] }]
    } );
} );


</script> 
