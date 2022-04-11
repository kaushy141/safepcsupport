<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small> </div>
      <form id="addpallet" name="addpallet" enctype="multipart/form-data">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="pallet_name">Pallet Name<sup>*</sup></label>
                <input class="form-control" id="pallet_name" name="pallet_name" maxlength="250" placeholder="Enter pallet name" type="text" value="<?=isset($pallet_name)?$pallet_name:"";?>">
              </div>
            </div>
			<div class="col-sm-4">
              <div class="form-group">
                <label for="pallet_location">Pallet Location<sup>*</sup></label>
                <input class="form-control" id="pallet_location" name="pallet_location" maxlength="50" placeholder="Enter pallet name" type="text" value="<?=isset($pallet_location)?$pallet_location:"";?>">
              </div>
            </div>
			<div class="col-sm-4">
              <div class="form-group">
                <label for="pallet_capacity">Pallet Item Capacity<sup>*</sup></label>
                <input class="form-control" id="pallet_capacity" name="pallet_capacity" maxlength="50" placeholder="Enter pallet name" type="number" min="0" value="<?=isset($pallet_capacity)?intval($pallet_capacity):"0";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="pallet_type">Pallet Type<sup>*</sup></label>
                <select id="pallet_type" data-value="<?=isset($pallet_type)?$pallet_type:"";?>" name="pallet_type" class="form-control" size="1">
                  <option value="PALLET" <?php echo (isset($pallet_type) && $pallet_type =='PALLET')?"selected":"";?>>Pallet</option>
                  <option value="BOX" <?php echo (isset($pallet_type) && $pallet_type =='BOX')?"selected":"";?>>Box</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="pallet_cert_customer">Pallet certificate customer name<sup></sup></label>
                <input class="form-control" id="pallet_cert_customer" name="pallet_cert_customer" maxlength="100" placeholder="Enter pallet certificate customer" type="text" value="<?=isset($pallet_cert_customer)?$wc_carrier_licence_number:"";?>">
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
                <label for="pallet_cert_telephone">Pallet certificate customer phone<sup></sup></label>
                <input class="form-control" id="pallet_cert_telephone" name="pallet_cert_telephone" maxlength="50" placeholder="Enter pallet certificate telephone" type="text" value="<?=isset($pallet_cert_telephone)?$pallet_cert_telephone:"";?>">
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
                <label for="pallet_cert_date">Pallet certificate date<sup></sup></label>
                <div class="input-group date">
                <input class="form-control" id="pallet_cert_date" name="pallet_cert_date" maxlength="50" placeholder="Select pallet certificate date" type="text" value="<?=isset($pallet_cert_date)?$pallet_cert_date:"";?>">
                <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="pallet_cert_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                  </span> 
                  </div>
              </div>
            </div>
            
            <script type="text/javascript">
                    $('#pallet_cert_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,					
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
            
            <div class="col-sm-4">
              <div class="form-group">
                <label for="pallet_serial_number">Pallet serial number<sup></sup></label>
                <input class="form-control" id="pallet_serial_number" name="pallet_serial_number" maxlength="50" placeholder="Enter pallet serial number" type="text" value="<?=isset($pallet_serial_number)?$pallet_serial_number:"";?>">
              </div>
            </div>
			</div>
			<div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <label for="pallet_cert_address">Pallet certificate address<sup></sup></label>
                <textarea id="pallet_cert_address" name="pallet_cert_address" rows="4" class="form-control" placeholder="Enter pallet certificate address"><?=isset($pallet_cert_address)?$pallet_cert_address:"";?>
</textarea>
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <label for="pallet_tester">Pallet tester<sup></sup></label>
                <textarea id="pallet_tester" name="pallet_tester" rows="4" class="form-control" placeholder="Enter pallet tester"><?=isset($pallet_tester)?$pallet_tester:"";?>
</textarea>
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
                <label for="pallet_status">Is pallet active</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="pallet_status" value="1" name="pallet_status"  <?=(isset($pallet_status) && $pallet_status)?"checked":"";?> <?=(!isset($pallet_status))?"checked":"";?> type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="pallet_is_full">Is pallet space full ?</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="pallet_is_full" value="1" name="pallet_is_full"  <?=(isset($pallet_is_full) && $pallet_is_full)?"checked":"";?> type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
          </div>
          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
          <button type="button" onClick="addPallet();" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span> Pallet </button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"collection/addpallet";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="pallet_id" name="pallet_id" value="<?=isset($pallet_id)?$pallet_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
function addPallet()
{
	var formFields	=	"pallet_name,pallet_capacity";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()		
		};
		
		data = $.extend(data, $("#addpallet").serializeFormJSON());
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
					Redirect('viewpallet');
				}
				message(arr[1]);
			}
		})	
	}
}
</script> 