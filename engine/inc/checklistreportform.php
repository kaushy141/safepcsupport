<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small> </div>
      <form id="addchecklistform" name="addchecklistform" enctype="multipart/form-data">
        <div class="card-block">
          <div class="row">            
            
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="checklist_employee_name">Employee Name<sup>*</sup></label>
                <input class="form-control" id="checklist_employee_name" name="checklist_employee_name" placeholder="Enter employee name" maxlength="50" type="text" value="<?=isset($checklist_employee_name)?$checklist_employee_name:"";?>" >
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="checklist_name">Checklist<sup>*</sup></label>
                <select <?php echo isset($checklist_name) ? "disabled" : "";?> id="checklist_name" name="checklist_name" class="form-control" size="1">
                <?php echo getCompanyCheckListTypes(isset($checklist_name)?$checklist_name:"");
				?>
                </select>
              </div>
            </div> 
            
            <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
               <label for="checklist_file">Checklist file<sup>*</sup></label>
              <input type="file" name="checklist_file" id="checklist_file" class="btn btn-default btn-block save" data-action="save-png" onchange="filesUpload('checklist_file');" />
              <input type="hidden" name="checklist_file_path" id="checklist_file_path" value="" />
              <span class="file_uploader"></span>
            </div>
          </div>          
            
          </div>
          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to <?=(isset($checklist_id) && $checklist_id == 0 )?"add":"update";?>  this checklist report...?', 'addChecklistReport')" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i>    <?=$btnText?></span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addchecklistreport";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="checklist_id" name="checklist_id" value="<?=isset($checklist_id)?$checklist_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
function checklist_file_path_callback(path){
	$("#checklist_file_path").val(path);
}

function addChecklistReport()
{
	
	var formFields	=	"checklist_employee_name, checklist_name";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()		
		};
		
		data = $.extend(data, $("#addchecklistform").serializeFormJSON());
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
					if($("#checklist_id").val() == 0)
					$('#addchecklistform').trigger("reset");					
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
      <div class="card-header"> <i class="fa fa-align-justify"></i>Contract Insurance List </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Date</th>
              <th>Checklist</th>
              <th>Completed By</th>
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
				"action"	:	"viewchecklistreport",				
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
			$(nRow).attr("id",'row_' + aData[4]);
			if(aData[5]==0){
			$('td', nRow).css('background-color', "#666");
			$('td', nRow).css('color', "#fff");
			}
			return nRow;
		},
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [3], orderable: false }]
    } );
} );

function removeChecklistReport(id)
{
	if(confirm("Are you sure to remove this record... ?"))
	{
		var data={
			action	:	"company_resource/removechecklistreport",
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
