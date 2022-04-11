<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small> </div>
      <form id="addassetreg" name="addassetreg" enctype="multipart/form-data">
        <div class="card-block">
          <div class="row">                        
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_type">Assets Type<sup>*</sup></label>
                <input class="form-control" id="soft_asset_type" name="soft_asset_type" placeholder="Enter software ssset type" maxlength="100" type="text" value="<?=isset($soft_asset_type)?$soft_asset_type:"";?>" >
              </div>
            </div>
           
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_version">Version<sup>*</sup></label>
                <input class="form-control" id="soft_asset_version" name="soft_asset_version" placeholder="Enter software asset version" maxlength="100" type="text" value="<?=isset($soft_asset_version)?$soft_asset_version:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_reg_code">Asset Reg. Code<sup>*</sup></label>
                <input class="form-control" id="soft_asset_reg_code" name="soft_asset_reg_code" placeholder="Enter software asset reg code" maxlength="50" type="text" value="<?=isset($soft_asset_reg_code)?$soft_asset_reg_code:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_allocated_install_to">Allocated or Installed to<sup>*</sup></label>
                <input class="form-control" id="soft_asset_allocated_install_to" name="soft_asset_allocated_install_to" placeholder="Enter Loocator name" maxlength="100" type="text" value="<?=isset($soft_asset_allocated_install_to)?$soft_asset_allocated_install_to:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_licence_number">Licence number<sup>*</sup></label>
                <input class="form-control" id="soft_asset_licence_number" name="soft_asset_licence_number" placeholder="Enter licence number" maxlength="100" type="text" value="<?=isset($soft_asset_licence_number)?$soft_asset_licence_number:"";?>">
              </div>
            </div>            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_owner">Assets Owner<sup>*</sup></label>
                <input class="form-control" id="soft_asset_owner" name="soft_asset_owner" placeholder="Enter asset owner name" maxlength="100" type="text" value="<?=isset($soft_asset_owner)?$soft_asset_owner:"";?>">
              </div>
            </div>            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_used_by">AssetsUsed By <sup>*</sup></label>
                <input class="form-control" id="soft_asset_used_by" name="soft_asset_used_by" placeholder="Enter assets used by" maxlength="100" type="text" value="<?=isset($soft_asset_used_by)?$soft_asset_used_by:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_managed_by">Assets Managed By<sup>*</sup></label>
                <input class="form-control" id="soft_asset_managed_by" name="soft_asset_managed_by" placeholder="Enter assets managed by" maxlength="100" type="text" value="<?=isset($soft_asset_managed_by)?$soft_asset_managed_by:"";?>">
              </div>
            </div>            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_location">Assets Location<sup>*</sup></label>
                <input class="form-control" id="soft_asset_location" name="soft_asset_location" placeholder="Enter assets location" maxlength="100" type="text" value="<?=isset($soft_asset_location)?$soft_asset_location:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="soft_asset_overview">Assets Overview<sup>*</sup></label>
                <input class="form-control" id="soft_asset_overview" name="soft_asset_overview" placeholder="Enter assets over view" maxlength="500" type="text" value="<?=isset($soft_asset_overview)?$soft_asset_overview:"";?>">
              </div>
            </div>                        
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_risk">Risk<sup>*</sup></label>
                <input class="form-control" id="soft_asset_risk" name="soft_asset_risk" placeholder="Enter risk" maxlength="100" type="text" value="<?=isset($soft_asset_risk)?$soft_asset_risk:"";?>">
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_cia">CIA<sup>*</sup></label>
                <select id="soft_asset_cia" name="soft_asset_cia" class="form-control" size="1">
                <?php echo getCiaOptions(isset($soft_asset_cia)?$soft_asset_cia:"");
				?>
                </select>
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_impact">Impact if Loss or Disclosed<sup>*</sup></label>
                <select id="soft_asset_impact" name="soft_asset_impact" class="form-control" size="1">
                <?php echo getImpactOptions(isset($soft_asset_impact)?$soft_asset_impact:"");
				?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_risk_rating">Risk Rating<sup>*</sup></label>
                <select id="soft_asset_risk_rating" name="soft_asset_risk_rating" class="form-control" size="1">
                <?php echo getRiskRatingOptions(isset($soft_asset_risk_rating)?$soft_asset_risk_rating:"");
				?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_action_plan">Action Plan<sup>*</sup></label>
                <input class="form-control" id="soft_asset_action_plan" name="soft_asset_action_plan" placeholder="Enter action plan" maxlength="100" type="text" value="<?=isset($soft_asset_action_plan)?$soft_asset_action_plan:"";?>">
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_suspect_vulnerabilities">Known or Suspect vulnerabilities<sup>*</sup></label>
                <input class="form-control" id="soft_asset_suspect_vulnerabilities" name="soft_asset_suspect_vulnerabilities" placeholder="Enter action plan" maxlength="500" type="text" value="<?=isset($soft_asset_suspect_vulnerabilities)?$soft_asset_suspect_vulnerabilities:"";?>">
              </div>
            </div>            
           
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_key_security_control">Key Security Controll<sup>*</sup></label>
                <input class="form-control" id="soft_asset_key_security_control" name="soft_asset_key_security_control" placeholder="Enter Key Security controll" maxlength="200" type="text" value="<?=isset($soft_asset_key_security_control)?$soft_asset_key_security_control:"";?>">
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="soft_asset_potential_action">Potential Action<sup>*</sup></label>
                <input class="form-control" id="soft_asset_potential_action" name="soft_asset_potential_action" placeholder="Enter potential action" maxlength="200" type="text" value="<?=isset($soft_asset_potential_action)?$soft_asset_potential_action:"";?>">
              </div>
            </div>
            
            
            
          </div>
          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to add Sofware Assets Information...?', 'addSoftwareAssetReg');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addsoftwareassetreg";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="soft_asset_id" name="soft_asset_id" value="<?=isset($soft_asset_id)?$soft_asset_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
function addSoftwareAssetReg()
{
	var formFields	=	"soft_asset_type, soft_asset_version, soft_asset_reg_code, soft_asset_allocated_install_to, soft_asset_licence_number, soft_asset_owner, soft_asset_used_by, soft_asset_managed_by, soft_asset_location, soft_asset_overview, soft_asset_risk, soft_asset_cia, soft_asset_impact, soft_asset_risk_rating, soft_asset_suspect_vulnerabilities, soft_asset_action_plan, soft_asset_key_security_control, soft_asset_potential_action";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()		
		};
		
		data = $.extend(data, $("#addassetreg").serializeFormJSON());
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
					if($("#soft_asset_id").val() == 0)
					$('#addassetreg').trigger("reset");
					
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
function removeSoftwareAsset(id)
{
	if(confirm("Are you sure to remove this record... ?"))
	{
		var data={
			action	:	"company_resource/removesoftwareasset",
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
				"action"	:	"viewsoftwareasset",				
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
