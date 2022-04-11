<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?php echo $formHeading; ?></strong> <small>Form</small> </div>
      <form id="addcompanyrecordform" name="addcompanyrecordform">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_name">Company Name<sup>*</sup></label>
                <input class="form-control" id="company_name" name="company_name" maxlength="100" placeholder="Enter company name" type="text" value="<?=isset($company_name)?$company_name:"";?>">
              </div>
            </div>
			<div class="col-sm-4">
              <div class="form-group">
                <label for="company_number">Company Number<sup>*</sup></label>
                <input class="form-control" id="company_number" name="company_number" maxlength="50" placeholder="Enter company number" type="text" value="<?=isset($company_number)?$company_number:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_carrier_licence_number">Company carrier licence number<sup>*</sup></label>
                <input class="form-control" id="company_carrier_licence_number" name="company_carrier_licence_number" maxlength="50" placeholder="Enter company carrier licence number" type="text" value="<?=isset($company_carrier_licence_number)?$company_carrier_licence_number:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_environment_permit_number">Environment permit number<sup>*</sup></label>
                <input class="form-control" id="company_environment_permit_number" name="company_environment_permit_number" maxlength="50" placeholder="Enter environment permit number" type="text" value="<?=isset($company_environment_permit_number)?$company_environment_permit_number:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_hazardous_waste_licence_number">Hazardous waste licence number<sup>*</sup></label>
                <input class="form-control" id="company_hazardous_waste_licence_number" name="company_hazardous_waste_licence_number" maxlength="50" placeholder="Enter company hazardous waste licence number" type="text" value="<?=isset($company_hazardous_waste_licence_number)?$company_hazardous_waste_licence_number:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_vehicle_reg_number">Company vehicle reg number<sup>*</sup></label>
                <input class="form-control" id="company_vehicle_reg_number" name="company_vehicle_reg_number" maxlength="50" placeholder="Enter company vehicle reg number" type="text" value="<?=isset($company_vehicle_reg_number)?$company_vehicle_reg_number:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_ico_registration_number">ICO registration number<sup>*</sup></label>
                <input class="form-control" id="company_ico_registration_number" name="company_ico_registration_number" maxlength="50" placeholder="Enter company ico registration number" type="text" value="<?=isset($company_ico_registration_number)?$company_ico_registration_number:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_iso_9001">Company iso 9001<sup>*</sup></label>
                <input class="form-control" id="company_iso_9001" name="company_iso_9001" maxlength="50" placeholder="Enter company iso 9001" type="text" value="<?=isset($company_iso_9001)?$company_iso_9001:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_iso_14001">Company iso 14001<sup>*</sup></label>
                <input class="form-control" id="company_iso_14001" name="company_iso_14001" maxlength="50" placeholder="Enter company iso 14001" type="text" value="<?=isset($company_iso_14001)?$company_iso_14001:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_vat_registration_number">Company vat registration number<sup>*</sup></label>
                <input class="form-control" id="company_vat_registration_number" name="company_vat_registration_number" maxlength="50" placeholder="Enter company vat registration number" type="text" value="<?=isset($company_vat_registration_number)?$company_vat_registration_number:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_registered_in_england_and_wales">Company registered in england and wales<sup>*</sup></label>
                <input class="form-control" id="company_registered_in_england_and_wales" name="company_registered_in_england_and_wales" maxlength="100" placeholder="Enter company registered in england and wales" type="text" value="<?=isset($company_registered_in_england_and_wales)?$company_registered_in_england_and_wales:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_registered_trademark">Company registered trademark<sup>*</sup></label>
                <input class="form-control" id="company_registered_trademark" name="company_registered_trademark" maxlength="50" placeholder="Enter company registered trademark" type="text" value="<?=isset($company_registered_trademark)?$company_registered_trademark:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="company_trademark">Company trademark (Square min 250px)<sup>*</sup></label>
                <div class="input-group"> <span class="input-group-addon" style="padding:0px" id="image_uploader">
                  <i class="fa fa-camera fa-lg"></i>
                  </span>
                  <input class="form-control" id="company_trademark" name="company_trademark" style="padding-bottom: 4px; padding-top: 4px;" maxlength="100" value="" type="file" onchange="uploadFile(this.name);">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="company_bank_details">Company bank details<sup>*</sup></label>
                <textarea id="company_bank_details" name="company_bank_details" rows="3" class="form-control" placeholder="Enter company bank details..."><?=isset($company_bank_details)?$company_bank_details:"";?>
</textarea>
              </div>
            </div>
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="company_address">Company address<sup>*</sup></label>
                <textarea id="company_address" name="company_address" rows="2" class="form-control" placeholder="Enter company address details..."><?=isset($company_address)?$company_address:"";?>
</textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="company_contact">Company contact<sup>*</sup></label>
                <textarea id="company_contact" name="company_contact" rows="2" class="form-control" placeholder="Enter company contact information..."><?=isset($company_contact)?$company_contact:"";?>
</textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to save compnay Information...?', 'addCompanyInfo')" class="btn btn-sm btn-success submission_handler_btn"><i class="fa fa-check fa-fw"></i>
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addcompanyrecord";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="company_id" name="company_id" value="<?=isset($company_id)?$company_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">

function addCompanyInfo()
{
	if(validateFields("company_name, company_address, company_contact, company_carrier_licence_number, company_environment_permit_number, company_hazardous_waste_licence_number, company_bank_details, company_vehicle_reg_number, company_registered_trademark, company_ico_registration_number, company_iso_9001, company_iso_14001, company_registered_in_england_and_wales, company_vat_registration_number"))
	{
		var data={
					action	:	$("#action").val(),
					field_handler : "company_trademark"						
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
					$("#company_id").val(arr[2]);
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
			action	:	"company_resource/updatecompanyrecordstatus",
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


<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i>Company List </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Trademark</th>
              <th>Name</th>
              <th>Address</th>
              <th>Created</th>
              <th>Status</th>
              <th width="110px" class="text-center">Action</th>
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
var data = {
				"action"	:	"viewcompnayrecord",				
		   };
$(document).ready(function() {
    $('#tblSortable').DataTable( {
		"language": {
					  "emptyTable": "No Company record available"
					},
        "processing": true,
        "serverSide": true,
        "ajax":  {
            "url": "<?=$app->basePath("server_processing.php")?>",
            "type": "POST",
			"data": data
        },
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			$(nRow).attr("id",'row_' + aData[5]);			
			return nRow;
		},
		"order": [[ 1, 'asc' ]],
		columnDefs: [{ targets: [0,3,4], orderable: false }]
    } );
} );

</script> 

