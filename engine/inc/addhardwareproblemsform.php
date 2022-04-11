<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-header"> <strong>Hardware Problem </strong> <small> Form</small>
          <div class="card-actions">
                <a data-title="Add New Hardware Problem" title="Add New Hardware Problem" onclick="addNewHardwareProblemRecord()" href="#"><i class="icon-plus icons font-2xl d-block m-t-2"></i></a>
          </div>       	
      </div>
      <form id="addhardwareproblem" name="addhardwareproblem">
      <div class="card-block">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="problem_name">Problem Name</label>
              <input class="form-control" id="problem_name" name="problem_name" maxlength="200" placeholder="Write Hardware Problem Type Name" type="text">
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                  <label for="problem_status">Active Status &nbsp; <i class="fa fa-check-circle fa-lg m-t-2"></i></label>
                  <label class="switch switch-icon switch-pill switch-success pull-right">
                    <input class="switch-input" id="problem_status" value="1" checked="" name="problem_status" type="checkbox" <?=(isset($complaint_is_disk_provided) && $complaint_is_disk_provided)?"checked":"";?>>
                    <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                </div>
            </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
        <button type="button" onClick="addHardwareProblem();" class="btn btn-sm btn-primary submission_handler_btn"><i class="fa fa-dot-circle-o"></i> &nbsp; <span id="data_submit_value">Add Hardware Problem</span></button>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addhardwareproblem";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="problem_id" name="problem_id" value="0"  />
      </div>
      </form>
    </div>
  </div>
  <!--/col-->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Hardware Problems List </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>Problem Name</th>
              <th>Add Date</th>
              <th>Status</th>
              <th>Option</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			$HardwareProblem = new HardwareProblem(0);
			echo $HardwareProblem->getRecords();
			?>
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">
function updateHardwareProblemRecord(i)
{
	$("#problem_id").val(i);
	$("#problem_name").val($("#data_value_record_row_"+i).children(".problem_name_text").attr("data-value"));
	if($("#data_value_record_row_"+i).children(".problem_name_status").attr("data-value")==0)
		$("#problem_status").removeAttr('checked');
	else
		$("#problem_status").attr('checked','checked');
	$("#data_submit_value").text("Update Hardware Problem");
}

function addNewHardwareProblemRecord()
{
	$("#problem_id").val(0);
	$("#problem_status").attr('checked','checked');
	$("#problem_name").val('');
	$("#data_submit_value").text("Add Hardware Problem");
}

function addHardwareProblem()
{
	if(validateFields("problem_name"))
	{
		var data={
			action	:	$("#action").val()				
		};
		
		data = $.extend(data, $("#addhardwareproblem").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==300)
				$("#hardware_code").val('');
				message(arr[1]);
			}
		})	
	}
}

function statusAction(field)
{
	var data={
			action	:	"system/updatehardwareproblemstatus",
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

function checkHardwarecode()
{
	if(validateFields("hardware_code"))
	{
		var data={
					action	:	'system/iscodeavailable',
					hardware_code	:	$("#hardware_code").val()							
				};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);			
				message(arr[1]);
			}
		})	
	}
}

$("#tbldatatable").dataTable({"iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100], "sPaginationType": "full_numbers", "aoColumns": [ { "bSortable": false }, null, null, null, null]});

</script> 
