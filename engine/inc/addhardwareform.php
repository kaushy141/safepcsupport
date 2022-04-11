<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-header"> <strong>Add New Hardware Type</strong> <small>Form</small> </div>
      <div class="card-block">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">Hardware Name</label>
              <input class="form-control" id="hardware_name" name="hardware_name" maxlength="100" placeholder="Enter Hardware name" type="text">
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="ccnumber">Hardware Code (3 Character Only)</label>
              <input class="form-control" id="hardware_code" name="hardware_code" onBlur="checkHardwarecode(this.value);" maxlength="3" onKeyUp="this.value=this.value.toUpperCase();" placeholder="ABC" type="text">
            </div>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label">Hardware Status</label>
          <div class="col-md-9">
            <label class="radio-inline" for="inline-radio1">
              <input id="inline-radio1" name="hardware_name" checked class="hardware_name" value="1" type="radio">
              Active </label>
            &nbsp;  &nbsp;
            <label class="radio-inline" for="inline-radio2">
              <input id="inline-radio2" name="hardware_name" class="hardware_name" value="0" type="radio">
              Deactive </label>
          </div>
        </div>
      </div>
      <div class="card-footer">
      	<input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
        <button type="button" onClick="addHardware();" class="btn btn-sm btn-primary submission_handler_btn"><i class="fa fa-dot-circle-o"></i> Submit</button>
      </div>
    </div>
  </div>
  <!--/col-->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Hardware Type List </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>Hardware Name</th>
              <th>Hardware Code</th>
              <th>Add Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			$hardwaretype = new HardwareType(0);
			echo $hardwaretype->getRecords();
			?>
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">

function addHardware()
{
	if(validateFields("hardware_name, hardware_code"))
	{
		var data={
					action	:	'system/addhardware',
					hardware_name	:	$("#hardware_name").val(),
					hardware_code	:	$("#hardware_code").val(),
					hardware_status	:	$(".hardware_status:checked").val(),							
				};
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
			action	:	"system/updatehardwaretypestatus",
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

function checkHardwarecode(field)
{
	if(validateFields("hardware_code") && validateHardwareCode(field))
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

$("#tbldatatable").dataTable({"iDisplayLength": 10, "sPaginationType": "full_numbers","bLengthChange": false,"bFilter": false,"bInfo": false,"bPaginate": true, "aoColumns": [ { "bSortable": false }, null, null, null, null]});

</script> 
