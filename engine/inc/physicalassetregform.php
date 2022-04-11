<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small> </div>
      <form id="addassetreg" name="addassetreg" enctype="multipart/form-data">
        <div class="card-block">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="asset_desciption">Description<sup>*</sup></label>
                <textarea class="form-control" id="asset_desciption" name="asset_desciption" rows="2" maxlength="500" placeholder="Enter assets description" ><?=isset($asset_desciption)?$asset_desciption:"";?></textarea>
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_manufaturer">Manufacturer<sup>*</sup></label>
                <input class="form-control" id="asset_manufaturer" name="asset_manufaturer" placeholder="Enter manufaturer" maxlength="100" type="text" value="<?=isset($asset_manufaturer)?$asset_manufaturer:"";?>" >
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_model">Model<sup>*</sup></label>
                <input class="form-control" id="asset_model" name="asset_model" placeholder="Enter model" maxlength="100" type="text" value="<?=isset($asset_model)?$asset_model:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_serial_number">Serial Number<sup>*</sup></label>
                <input class="form-control" id="asset_serial_number" name="asset_serial_number" placeholder="Enter serial number" maxlength="50" type="text" value="<?=isset($asset_serial_number)?$asset_serial_number:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_tag">TAG<sup>*</sup></label>
                <input class="form-control" readonly="readonly" id="asset_tag" name="asset_tag" placeholder="Enter Tag" maxlength="10" type="text" value="<?=isset($asset_tag)?$asset_tag:PhysicalAsset::getAssetTag();?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_processor">Processor<sup></sup></label>
                <input class="form-control" id="asset_processor" name="asset_processor" placeholder="Enter Processor" maxlength="100" type="text" value="<?=isset($asset_processor)?$asset_processor:"";?>">
              </div>
            </div>            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_ram">RAM<sup></sup></label>
                <input class="form-control" id="asset_ram" name="asset_ram" placeholder="Enter RAM" maxlength="50" type="text" value="<?=isset($asset_ram)?$asset_ram:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_size">Size<sup></sup></label>
                <input class="form-control" id="asset_size" name="asset_size" placeholder="Enter Size" maxlength="50" type="text" value="<?=isset($asset_size)?$asset_size:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_owner">Owner<sup>*</sup></label>
                <input class="form-control" id="asset_owner" name="asset_owner" placeholder="Enter Owner" maxlength="100" type="text" value="<?=isset($asset_owner)?$asset_owner:"";?>">
              </div>
            </div>            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_managed_by">Managed By<sup>*</sup></label>
                <input class="form-control" id="asset_managed_by" name="asset_managed_by" placeholder="Enter managed by" maxlength="100" type="text" value="<?=isset($asset_managed_by)?$asset_managed_by:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_managed_by">Used By<sup>*</sup></label>
                <input class="form-control" id="asset_used_by" name="asset_used_by" placeholder="Enter used by" maxlength="100" type="text" value="<?=isset($asset_used_by)?$asset_used_by:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_location">Location<sup>*</sup></label>
                <input class="form-control" id="asset_location" name="asset_location" placeholder="Enter location" maxlength="100" type="text" value="<?=isset($asset_location)?$asset_location:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="asset_desciption">Overview<sup>*</sup></label>
                <textarea class="form-control" id="asset_overview" name="asset_overview" rows="2" maxlength="500" placeholder="Enter assets overview" ><?=isset($asset_overview)?$asset_overview:"";?></textarea>
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_overview">Risk<sup>*</sup></label>
                <input class="form-control" id="asset_risk" name="asset_risk" placeholder="Enter risk" maxlength="100" type="text" value="<?=isset($asset_risk)?$asset_risk:"";?>">
              </div>
            </div>
            <!--SELECT `asset_id`  `asset_cia`, `asset_impact`, `asset_risk_rating`, `asset_suspect_vulnerabilities`, `asset_action plan`, `asset_created_by`, `asset_created_date`, `asset_status` FROM `app_physical_asset_info` WHERE 1-->
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_cia">CIA<sup>*</sup></label>
                <select id="asset_cia" name="asset_cia" class="form-control" size="1">
                <?php echo getCiaOptions(isset($asset_cia)?$asset_cia:"");
				?>
                </select>
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_impact">Impact if Loss or Disclosed<sup>*</sup></label>
                <select id="asset_impact" name="asset_impact" class="form-control" size="1">
                <?php echo getImpactOptions(isset($asset_impact)?$asset_impact:"");
				?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_risk_rating">Risk Rating<sup>*</sup></label>
                <select id="asset_risk_rating" name="asset_risk_rating" class="form-control" size="1">
                <?php echo getRiskRatingOptions(isset($asset_risk_rating)?$asset_risk_rating:"");
				?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="asset_action_plan">Action Plan<sup>*</sup></label>
                <input class="form-control" id="asset_action_plan" name="asset_action_plan" placeholder="Enter action plan" maxlength="100" type="text" value="<?=isset($asset_action_plan)?$asset_action_plan:"";?>">
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="asset_suspect_vulnerabilities">Known or Suspect vulnerabilities<sup></sup></label>
                <textarea class="form-control" id="asset_suspect_vulnerabilities" name="asset_suspect_vulnerabilities" rows="2" maxlength="500" placeholder="Enter known or suspect vulnerabilities" ><?=isset($asset_suspect_vulnerabilities)?$asset_suspect_vulnerabilities:"";?></textarea>
              </div>
            </div>
            
            
            
          </div>
          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to add Physical Assets Information...?', 'addPhysicalAssetReg');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addphysicalassetreg";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="asset_id" name="asset_id" value="<?=isset($asset_id)?$asset_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
function addPhysicalAssetReg()
{
	var formFields	=	"asset_desciption, asset_manufaturer, asset_model, asset_serial_number, asset_tag, asset_owner, asset_managed_by, asset_used_by, asset_location, asset_overview, asset_risk, asset_cia, asset_impact, asset_risk_rating, asset_suspect_vulnerabilities, asset_action_plan";
	
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
					if($("#asset_id").val() == 0)
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
      <div class="card-header"> <i class="fa fa-align-justify"></i>Physical Assets List </div>
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
function removePhysicalAsset(id)
{
	if(confirm("Are you sure to remove this record... ?"))
	{
		var data={
			action	:	"company_resource/removephysicalasset",
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
				"action"	:	"viewphysicalasset",				
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
