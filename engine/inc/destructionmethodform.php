<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small> </div>
      <form id="adddispoalform" name="adddispoalform" enctype="multipart/form-data">
        <div class="card-block">
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="eqipment_disposal_hardware_id">Type of Equipment Media<sup>*</sup></label>
                <select id="eqipment_disposal_hardware_id" name="eqipment_disposal_hardware_id" class="form-control" size="1">
                  <?php
                $HardwareType = new HardwareType(0);
				echo $HardwareType->getOptions(isset($eqipment_disposal_hardware_id)?$eqipment_disposal_hardware_id:"0");
				?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="eqipment_disposal_manufacturer">Manufacturer<sup>*</sup></label>
                <input class="form-control" id="eqipment_disposal_manufacturer" name="eqipment_disposal_manufacturer" placeholder="Enter manufaturer" maxlength="100" type="text" value="<?=isset($eqipment_disposal_manufacturer)?$eqipment_disposal_manufacturer:"";?>" >
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="eqipment_disposal_model">Model<sup>*</sup></label>
                <input class="form-control" id="eqipment_disposal_model" name="eqipment_disposal_model" placeholder="Enter model" maxlength="100" type="text" value="<?=isset($eqipment_disposal_model)?$eqipment_disposal_model:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="eqipment_disposal_serial_number">Serial Number<sup>*</sup></label>
                <input class="form-control" id="eqipment_disposal_serial_number" name="eqipment_disposal_serial_number" placeholder="Enter serial number" maxlength="50" type="text" value="<?=isset($eqipment_disposal_serial_number)?$eqipment_disposal_serial_number:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="eqipment_disposal_log_no">Log Number<sup>*</sup></label>
                <input class="form-control" readonly="readonly" id="eqipment_disposal_log_no" name="eqipment_disposal_log_no" placeholder="Enter Log number" maxlength="9" type="text" value="<?=isset($eqipment_disposal_log_no)?$eqipment_disposal_log_no:DestructionMethod::getDestructionLogNumber();?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="eqipment_disposal_disposed_to">Disposed To<sup>*</sup></label>
                <input class="form-control" id="eqipment_disposal_disposed_to" name="eqipment_disposal_disposed_to" placeholder="Enter Disposed To" maxlength="100" type="text" value="<?=isset($eqipment_disposal_disposed_to)?$eqipment_disposal_disposed_to:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="eqipment_disposal_reason">Reason for disposal<sup></sup></label>
                <textarea class="form-control" id="eqipment_disposal_reason" name="eqipment_disposal_reason" rows="2" maxlength="1000" placeholder="Enter eqipment disposal reason" ><?=isset($eqipment_disposal_reason)?$eqipment_disposal_reason:"";?>
</textarea>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="eqipment_disposal_destruction_method">Destruction Method<sup>*</sup></label>
                <select id="eqipment_disposal_destruction_method" name="eqipment_disposal_destruction_method" class="form-control" size="1">
                  <?php
				echo getDestructionMethod(isset($eqipment_disposal_destruction_method)?$eqipment_disposal_destruction_method:"");
				?>
                </select>
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="eqipment_disposal_destroyed_date">Date Destroyed<sup>*</sup></label>
                <div class="input-group date">
                  <input type='text' class="form-control" id="eqipment_disposal_destroyed_date" name="eqipment_disposal_destroyed_date" placeholder="YYYY-MM-DD" value="<?=isset($eqipment_disposal_destroyed_date)?date("Y-m-d h:i A", strtotime($eqipment_disposal_destroyed_date)):date('Y-m-d h:i A');?>" />
                  <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="eqipment_disposal_destroyed_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                  </span> </div>
                <script type="text/javascript">
				$(function () {
					$('#eqipment_disposal_destroyed_date').datetimepicker({
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
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="eqipment_disposal_destroyed_by">Destroyed By<sup>*</sup></label>
                <input class="form-control" id="eqipment_disposal_destroyed_by" name="eqipment_disposal_destroyed_by" placeholder="Enter Destroyed by name" maxlength="100" type="text" value="<?=isset($eqipment_disposal_destroyed_by)?$eqipment_disposal_destroyed_by:"";?>">
              </div>
            </div>
            
          </div>
          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to add Disposal/Destruction log...?', 'addDisposalDestructionLog');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"company_resource/addequipmentdisposal";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="eqipment_disposal_id" name="eqipment_disposal_id" value="<?=isset($eqipment_disposal_id)?$eqipment_disposal_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
function addDisposalDestructionLog()
{
	var formFields	= "eqipment_disposal_hardware_id, eqipment_disposal_manufacturer, eqipment_disposal_model, eqipment_disposal_serial_number, eqipment_disposal_log_no, eqipment_disposal_disposed_to, eqipment_disposal_reason, eqipment_disposal_destroyed_date, eqipment_disposal_destroyed_by";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()		
		};
		
		data = $.extend(data, $("#adddispoalform").serializeFormJSON());
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
					$('#adddispoalform').trigger("reset");
					
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
              <th>LogNo.</th>
              <th>Equipment</th>
              <th>Model</th>
              <th>DisposedBY</th>
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
function removeDisposalDestructionLog(id)
{
	if(confirm("Are you sure to remove this record... ?"))
	{
		var data={
			action	:	"company_resource/removedisposaldestructionlog",
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
				"action"	:	"viewdisposaldestructionlog",				
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
		columnDefs: [{ targets: [5], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 5 ] }]
    } );
} );


</script> 
