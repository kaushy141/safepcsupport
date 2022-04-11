<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small> </div>
      <form id="addutilityreg" name="addutilityreg" enctype="multipart/form-data">
        <div class="card-block">
          <div class="row">                        
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_description">Description<sup>*</sup></label>
                <input class="form-control" id="utility_description" name="utility_description" placeholder="Enter description" maxlength="100" type="text" value="<?=isset($utility_description)?$utility_description:"";?>" >
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_used_by">Used By <sup>*</sup></label>
                <input class="form-control" id="utility_used_by" name="utility_used_by" placeholder="Enter assets used by" maxlength="100" type="text" value="<?=isset($utility_used_by)?$utility_used_by:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_managed_by">Managed By<sup>*</sup></label>
                <input class="form-control" id="utility_managed_by" name="utility_managed_by" placeholder="Enter assets managed by" maxlength="100" type="text" value="<?=isset($utility_managed_by)?$utility_managed_by:"";?>">
              </div>
            </div>  
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_owner">Owner<sup>*</sup></label>
                <input class="form-control" id="utility_owner" name="utility_owner" placeholder="Enter asset owner name" maxlength="100" type="text" value="<?=isset($utility_owner)?$utility_owner:"";?>">
              </div>
            </div>            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_location">Location<sup>*</sup></label>
                <input class="form-control" id="utility_location" name="utility_location" placeholder="Enter assets location" maxlength="100" type="text" value="<?=isset($utility_location)?$utility_location:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_risk">Risk<sup>*</sup></label>
                <input class="form-control" id="utility_risk" name="utility_risk" placeholder="Enter risk" maxlength="100" type="text" value="<?=isset($utility_risk)?$utility_risk:"";?>">
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_cia">CIA<sup>*</sup></label>
                <select id="utility_cia" name="utility_cia" class="form-control" size="1">
                <?php echo getCiaOptions(isset($utility_cia)?$utility_cia:"");
				?>
                </select>
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_impact">Impact if Loss or Disclosed<sup>*</sup></label>
                <select id="utility_impact" name="utility_impact" class="form-control" size="1">
                <?php echo getImpactOptions(isset($utility_impact)?$utility_impact:"");
				?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_risk_rating">Risk Rating<sup>*</sup></label>
                <select id="utility_risk_rating" name="utility_risk_rating" class="form-control" size="1">
                <?php echo getRiskRatingOptions(isset($utility_risk_rating)?$utility_risk_rating:"");
				?>
                </select>
              </div>
            </div>
            
            
            
            
            
            
            
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_contract_number">Contract Number<sup>*</sup></label>
                <input class="form-control" id="utility_contract_number" name="utility_contract_number" placeholder="Enter contract number" maxlength="100" type="text" value="<?=isset($utility_contract_number)?$utility_contract_number:"";?>">
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_person_to_contact">Person to Contact<sup>*</sup></label>
                <input class="form-control" id="utility_person_to_contact" name="utility_person_to_contact" placeholder="Enter person to contact" maxlength="100" type="text" value="<?=isset($utility_person_to_contact)?$utility_person_to_contact:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_contact_number">Contact Number<sup>*</sup></label>
                <input class="form-control" id="utility_contact_number" name="utility_contact_number" placeholder="Enter contact number" maxlength="100" type="text" value="<?=isset($utility_contact_number)?$utility_contact_number:"";?>">
              </div>
            </div>
            
                       
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="utility_overview">Utility Overview<sup>*</sup></label>
                <input class="form-control" id="utility_overview" name="utility_overview" placeholder="Enter Overview" maxlength="5000" type="text" value="<?=isset($utility_overview)?$utility_overview:"";?>">
              </div>
            </div> 
            
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_key_security_tool">Key Security Controll<sup>*</sup></label>
                <input class="form-control" id="utility_key_security_tool" name="utility_key_security_tool" placeholder="Enter security tool" maxlength="5000" type="text" value="<?=isset($utility_key_security_tool)?$utility_key_security_tool:"";?>">
              </div>
            </div>                        
              
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_potential_action">Potential Action<sup>*</sup></label>
                <input class="form-control" id="utility_potential_action" name="utility_potential_action" placeholder="Enter potential action" maxlength="5000" type="text" value="<?=isset($utility_potential_action)?$utility_potential_action:"";?>">
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="utility_action_plan">Action Plan<sup>*</sup></label>
                <input class="form-control" id="utility_action_plan" name="utility_action_plan" placeholder="Enter action plan" maxlength="500" type="text" value="<?=isset($utility_action_plan)?$utility_action_plan:"";?>">
              </div>
            </div>  
            
            <div class="col-md-4">
            <div class="form-group">
            <label for="user_cont_file"><i class="fa fa-file-pdf-o fa-lg m-t-2 text-danger"></i> &nbsp; Upload Utility Document</label>
              <input type="file" name="utility_doc_file" id="utility_doc_file" class="btn btn-default btn-block save" data-action="save-png" onchange="filesUpload('utility_doc_file');" />
              <input type="hidden" name="utility_doc_file_path" id="utility_doc_file_path" value="" />
              <span class="file_uploader" id="file_uploader"></span>
            </div>
          </div>          
             
          </div>
          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to add Utility Information...?', 'addUtility');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addutilities";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="utility_id" name="utility_id" value="<?=isset($utility_id)?$utility_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
function addUtility()
{
	var formFields	=	"utility_description, utility_used_by, utility_managed_by, utility_owner, utility_location, utility_risk, utility_cia, utility_impact, utility_risk_rating, utility_contract_number, utility_person_to_contact, utility_contact_number, utility_overview, utility_key_security_tool, utility_potential_action, utility_action_plan";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()		
		};
		
		data = $.extend(data, $("#addutilityreg").serializeFormJSON());
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
					if($("#utility_id").val() == 0)
					$('#addutilityreg').trigger("reset");
					
					$('#tblSortable').DataTable().ajax.reload();
				}
				message(arr[1]);
			}
		})	
	}
}

function utility_doc_file_path_callback(path){
	$("#utility_doc_file_path").val(path);
}
</script> 

<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i>Sales Invoice List </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Date</th>
              <th>Assets</th>
              <th>Owner</th>
              <th>UsedBy</th>
              <th>Location</th>
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
var data = {
				"action"	:	"viewutility",				
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
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			$(nRow).attr("id",'row_' + aData[6]);
			return nRow;
		},
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [5], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 5 ] }]
    } );
} );


</script> 
