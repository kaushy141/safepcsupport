<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small> </div>
      <form id="addcontarctreg" name="addcontarctreg" enctype="multipart/form-data">
        <div class="card-block">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="cont_ins_description">Description<sup>*</sup></label>
                <textarea class="form-control" id="cont_ins_description" name="cont_ins_description" rows="2" maxlength="5000" placeholder="Enter contract description" ><?=isset($cont_ins_description)?$cont_ins_description:"";?></textarea>
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="cont_ins_owner">Owner<sup>*</sup></label>
                <input class="form-control" id="cont_ins_owner" name="cont_ins_owner" placeholder="Enter Owner name" maxlength="100" type="text" value="<?=isset($cont_ins_owner)?$cont_ins_owner:"";?>" >
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="cont_ins_risk">Risk<sup>*</sup></label>
                <select id="cont_ins_risk" name="cont_ins_risk" class="form-control" size="1">
                <?php echo getRisk(isset($cont_ins_risk)?$cont_ins_risk:"");
				?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="cont_ins_risk_prob">Prob. Risk Occurance<sup>*</sup></label>
                <select id="cont_ins_risk_prob" name="cont_ins_risk_prob" class="form-control" size="1">
                <?php echo getProbabilityOfRiskOccurance(isset($cont_ins_risk_prob)?$cont_ins_risk_prob:"");
				?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="cont_ins_risk_rating">Overall Risk Rating<sup>*</sup></label>
                <select id="cont_ins_risk_rating" name="cont_ins_risk_rating" class="form-control" size="1">
                <?php echo getOvarallRisk(isset($cont_ins_risk_rating)?$cont_ins_risk_rating:"");
				?>
                </select>
              </div>
            </div>          
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="cont_ins_risk_treatment">Risk Treatment<sup>*</sup></label>
                <input class="form-control" id="cont_ins_risk_treatment" name="cont_ins_risk_treatment" placeholder="Enter Risk Treatment" maxlength="500" type="text" value="<?=isset($cont_ins_risk_treatment)?$cont_ins_risk_treatment:"";?>">
              </div>
            </div> 
            
            <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <input type="file" name="cont_ins_file" id="cont_ins_file" class="btn btn-primary btn-block save" data-action="save-png" onchange="filesUpload('cont_ins_file');" />
              <input type="hidden" name="cont_ins_file_path" id="cont_ins_file_path" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>
            
            
          </div>
          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to add this Contract...?', 'addContractInsurance')" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"company_resource/addcontractinsurance";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="cont_ins_id" name="cont_ins_id" value="<?=isset($cont_ins_id)?$cont_ins_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">

function addContractInsurance()
{
	
	var formFields	=	"cont_ins_description, cont_ins_owner, cont_ins_risk, cont_ins_risk_prob, cont_ins_risk_rating, cont_ins_risk_treatment, cont_ins_file_path";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()		
		};
		
		data = $.extend(data, $("#addcontarctreg").serializeFormJSON());
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
					if($("#cont_ins_id").val() == 0)
					$('#addcontarctreg').trigger("reset");					
					$('#tblSortable').DataTable().ajax.reload(); 
					$("#file_uploader").html('');
				}
				message(arr[1]);
			}
		})	
	}
}

function cont_ins_file_path_callback(path){
	$("#cont_ins_file_path").val(path);
}


</script> 

<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i>contracts/insurances/agreements List </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Contact</th>
              <th>Modified</th>
              <th>Owner</th>
              <th>Risk</th>
              <th width="110px" class="text-center">Option</th>
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
				"action"	:	"viewcontractinsurance",				
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
			$(nRow).attr("id",'row_' + aData[5]);
			
			return nRow;
		},
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [3], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 1 ] }]
    } );
} );

function removeContract(id)
{
	if(confirm("Are you sure to remove this record... ?"))
	{
		var data={
			action	:	"company_resource/removecontract",
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
</script> 
