<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>Add Application Information</strong> <small>Form</small> </div>
      <form id="addappinfo" name="addappinfo">
      <div class="card-block">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="info_app_bank_details">App bank details<sup>*</sup></label>
              <textarea id="info_app_bank_details" name="info_app_bank_details" rows="5" class="form-control" placeholder="Enter app bank details"><?=isset($info_app_bank_details)?$info_app_bank_details:"";?></textarea>
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="info_app_address">App address<sup>*</sup></label>
              <textarea id="info_app_address" name="info_app_address" rows="1" class="form-control" placeholder="Enter app address"><?=isset($info_app_address)?$info_app_address:"";?></textarea>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="info_app_contact">App contact<sup>*</sup></label>
              <textarea id="info_app_contact" name="info_app_contact" rows="1" class="form-control" placeholder="Enter app contact"><?=isset($info_app_contact)?$info_app_contact:"";?></textarea>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="info_app_invoice_acknowledge">App invoice acknowledge<sup>*</sup></label>
              <textarea id="info_app_invoice_acknowledge" name="info_app_invoice_acknowledge" rows="1" class="form-control" placeholder="Enter invoice acknowledge"><?=isset($info_app_invoice_acknowledge)?$info_app_invoice_acknowledge:"";?></textarea>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="info_app_disclaimer">App Disclaimer<sup>*</sup></label>
              <textarea id="info_app_disclaimer" name="info_app_disclaimer" rows="2" class="form-control" placeholder="Enter app address"><?=isset($info_app_disclaimer)?$info_app_disclaimer:"";?></textarea>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="info_app_invoice_happytext">App invoice happytext<sup>*</sup></label>
              <textarea id="info_app_invoice_happytext" name="info_app_invoice_happytext" rows="3" class="form-control" placeholder="Enter invoice happytext"><?=isset($info_app_invoice_happytext)?$info_app_invoice_happytext:"";?></textarea>
            </div>
          </div>
        </div>
        
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="info_app_invoice_terms">App app invoice terms<sup>*</sup></label>
              <textarea id="info_app_invoice_terms" name="info_app_invoice_terms" rows="2" class="form-control" placeholder="Enter app invoice terms"><?=isset($info_app_invoice_terms)?$info_app_invoice_terms:"";?></textarea>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="wc_carrier_certificate_text">Carrier Certificate text<sup>*</sup></label>
              <textarea id="wc_carrier_certificate_text" name="wc_carrier_certificate_text" rows="2" class="form-control" placeholder="Enter Carrier Certificate text"><?=isset($info_app_invoice_terms)?$wc_carrier_certificate_text:"";?></textarea>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="wc_consigner_certificate_text">Consigner certificate text<sup>*</sup></label>
              <textarea id="wc_consigner_certificate_text" name="wc_consigner_certificate_text" rows="2" class="form-control" placeholder="Enter Consigner certificate text"><?=isset($wc_consigner_certificate_text)?$wc_consigner_certificate_text:"";?></textarea>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wc_carrier_licence_number">Carrier licence number<sup>*</sup></label>
              <input class="form-control" id="wc_carrier_licence_number" name="wc_carrier_licence_number" maxlength="50" placeholder="Enter Carrier licence number" type="text" value="<?=isset($wc_carrier_licence_number)?$wc_carrier_licence_number:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wc_hazardous_waste_licence_number">Enter Hazardous waste licence number<sup>*</sup></label>
              <input class="form-control" id="wc_hazardous_waste_licence_number" name="wc_hazardous_waste_licence_number" maxlength="50" placeholder="Enter Hazardous waste licence number" type="text" value="<?=isset($wc_hazardous_waste_licence_number)?$wc_hazardous_waste_licence_number:"";?>">
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wc_environment_permit">Environment permit number<sup>*</sup></label>
              <input class="form-control" id="wc_environment_permit" name="wc_environment_permit" maxlength="50" placeholder="Enter Environment permit number" type="text" value="<?=isset($wc_environment_permit)?$wc_environment_permit:"";?>">
            </div>
          </div>
        </div>
        <div class="row">  
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wc_vehicle_registration">Vehicle registration number<sup>*</sup></label>
              <input class="form-control" id="wc_vehicle_registration" name="wc_vehicle_registration" maxlength="50" placeholder="Enter vehicle registration number" type="text" value="<?=isset($wc_vehicle_registration)?$wc_vehicle_registration:"";?>">
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wc_authority_name">Collection Authority Name<sup>*</sup></label>
              <input class="form-control" id="wc_authority_name" name="wc_authority_name" maxlength="50" placeholder="Enter Collection Authority Name" type="text" value="<?=isset($wc_authority_name)?$wc_authority_name:"";?>">
            </div>
          </div>
        </div>
       <!--/row-->
       
       <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="wc_declaration_text">HWC Declaration text<sup>*</sup></label>
              <textarea id="wc_declaration_text" name="wc_declaration_text" rows="2" class="form-control" placeholder="Enter Hazardous Waste Consignment Declaration text"><?=isset($wc_declaration_text)?$wc_declaration_text:"";?></textarea>
            </div>
          </div>
        </div>
        
       <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="wc_collection_declaration_text">Wastage Collection declaration<sup>*</sup></label>
              <textarea id="wc_collection_declaration_text" name="wc_collection_declaration_text" rows="2" class="form-control" placeholder="Enter Wastage Collection declaration"><?=isset($wc_collection_declaration_text)?$wc_collection_declaration_text:"";?></textarea>
            </div>
          </div>
        </div>
        
        
        
      </div>
      <div class="card-footer">
        <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
        <button type="button" onClick="confirmMessage.Set('Are you sure to update application Information...?', 'addAppInfo')" class="btn btn-sm btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name"><?=$btnText?></span> Application Info </button>
      </div>
      <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"";?>"  />
      <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
      <input type="hidden" id="info_app_id" name="info_app_id" value="<?=isset($info_app_id)?$info_app_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col-->
  
  <!--/col--> 
</div>
<script type="text/javascript">

function addAppInfo()
{
	if(validateFields("info_app_bank_details, info_app_address, info_app_contact, info_app_invoice_acknowledge, info_app_invoice_happytext, info_app_invoice_terms, info_app_disclaimer, wc_carrier_certificate_text, wc_consigner_certificate_text, wc_carrier_licence_number, wc_hazardous_waste_licence_number, wc_environment_permit, wc_vehicle_registration, wc_authority_name,  wc_declaration_text, wc_collection_declaration_text"))
	{
		var data={
					action	:	$("#action").val()							
				};
		data = $.extend(data, $("#addappinfo").serializeFormJSON());
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
					$("#info_app_id").val(arr[2]);
					$("#action").val("system/updateappinfo");
					$("#btn_action_name").text("UPDATE");
				}
				message(arr[1]);
			}
		})	
	}
}
</script> 
