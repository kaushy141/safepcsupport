<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>Customer Group</strong> <small>Form</small> </div>
      <form id="addcrmgroup" name="addcrmgroup">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="crm_group_name">Group Name</label>
                <input class="form-control" id="crm_group_name" name="crm_group_name" maxlength="50" required="required" value="<?=isset($crm_group_name)?$crm_group_name:"";?>" placeholder="Enter Template name" type="text">
              </div>
            </div>
            
            <div class="col-sm-6">
              <div class="form-group">
                <label class="form-control-label">Group Status</label>
                <div class="col-md-12">
                  <div class="row">
                    <label class="radio-inline" for="inline-radio1">
                      <input id="inline-radio1" name="crm_group_status" checked class="hardware_name" value="1" type="radio" <?=isset($crm_group_status) && $crm_group_status == 1 ? "checked=\"checked\"":"";?> >
                      Active </label>
                    &nbsp;  &nbsp;
                    <label class="radio-inline" for="inline-radio2">
                      <input id="inline-radio2" name="crm_group_status" class="hardware_name" value="0" type="radio" <?=isset($crm_group_status) && $crm_group_status == 0 ? "checked=\"checked\"":"";?>>
                      Deactive </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header"> <i class="fa fa-align-justify"></i> Customer List
                <div class="card-actions" style="padding-top:5px;">Check 
            	<label class="switch switch-text switch-success-outline-alt">
                    <input class="switch-input" <?=isset($crm_group_customers_all) && $crm_group_customers_all == 1 ? "checked=\"checked\"":"";?> value="1" type="checkbox" id="check_all_customer" name="check_all_customer">
                    <span class="switch-label" data-on="All" data-off="Off"></span>
                	<span class="switch-handle"></span>
                </label> &nbsp;
                Uncheck 
                <label class="switch switch-text switch-danger-outline-alt">
                    <input class="switch-input" <?=isset($crm_group_customers_except) && $crm_group_customers_except == 1 ? "checked=\"checked\"":"";?> value="1" type="checkbox" id="uncheck_all_customer" name="uncheck_all_customer">
                    <span class="switch-label" data-on="All" data-off="Off"></span>
                	<span class="switch-handle"></span>
                </label>
        		</div>
                </div>
                <div class="block-fluid table-sorting clearfix">
                  <table id="tblSortable" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Pic</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Status</th>
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
        </div>
        <div class="card-footer">
          <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addemailtemplate";?>"  />
          <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
          <input type="hidden" id="crm_group_id" name="crm_group_id" value="<?=isset($crm_group_id)?$crm_group_id:"";?>" />
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="addCustomerGroup();" class="btn btn-sm btn-primary submission_handler_btn"><i class="fa fa-dot-circle-o"></i>
          <?=isset($btnText)?$btnText:"Add";?>
          </button>
        </div>
      </form>
    </div>
  </div>
  <!--/col--> 
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Customer Group List </div>
      <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>Group Name</th>
              <th>Added date</th>
              <th>User</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
			$CustomerGroup = new CustomerGroup(0);
			echo $CustomerGroup->getRecords();
			?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">
function addCustomerGroup()
{
	if(validateFields("crm_group_name"))
	{
		var data={
			action	:	$("#action").val()	,
			customerArray	:	customerArray,
			customerCheckArray	:	customerCheckArray,
			customerUnCheckArray	:	customerUnCheckArray			
		};
		data = $.extend(data, $("#addcrmgroup").serializeFormJSON());	
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				message(arr[1]);
				if(arr[0] == 200)
				$("#addemailtemplate").reset();
			}
		})	
	}
}

function validateHardwareCode(code)
{
	if (code.value.match(/^[A-Za-z]+$/))
	{
		$("#hardware_code").val("");
		message("danger|Only A-Z Character allowed.");
		return false;
	}
	return true;
}

$("#tbldatatable").dataTable({"iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100], "sPaginationType": "full_numbers", "aoColumns": [ { "bSortable": false }, null, null, null, null]});

var dataCustomer = {
				"action"	:	"viewcustomerlistcheckbox",	
				"group_id"	:	0				
		   };
var customerArray = [<?php echo json_encode(CustomerGroup::getGroupCustomerArray($crm_group_id))?>];
var customerCheckArray = [<?=isset($crm_group_customers_included)?$crm_group_customers_included:"";?>];
var customerUnCheckArray = [<?=isset($crm_group_customers_except)?$crm_group_customers_except:"";?>];
$(document).ready(function() {
    $('#tblSortable').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax":  {
            "url": "<?=$app->basePath("server_processing.php")?>",
            "type": "POST",
			"data": dataCustomer
        },
		"order": [[ 2, 'asc' ]],
		columnDefs: [{ targets: [1], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,3 ] }],
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			  var ht = "<div><label class=\"switch switch-icon switch-success\"><input value=\""+aData[5]+"\" onchange=\"updateCustomerGroupArray(this);\" name=\"radio_check_btn_"+aData[5]+"\" id=\"radio_check_btn_"+aData[5]+"\" class=\"switch-input customer_check_box\" "+((customerArray.indexOf(aData[5])>-1 || ($("#check_all_customer").prop("checked") && customerUnCheckArray.indexOf(aData[5])==-1))?"checked":"")+" type=\"checkbox\"><span class=\"switch-label\" data-on=\"On\" data-off=\"Off\"></span><span class=\"switch-handle\"></span></label></div>";
			$('td:eq(4)', nRow).html( ht );
		}
    } );
} );

function updateCustomerGroupArray(field)
{
	if(Number(field.checked))
		customerArray.push(field.value);
	else
		customerArray = customerArray.filter(item => item !== field.value)
		
	if($("#check_all_customer").prop("checked"))
	customerUnCheckArray.push(field.value);
	
	if($("#uncheck_all_customer").prop("checked"))
	customerCheckArray.push(field.value);	
}

$(document).ready(function(e) {
    $("#check_all_customer").on("change", function(){ 
		if($(this).prop("checked"))
		{
			$(".customer_check_box").each(function(index, element) {
				$(this).prop("checked", true);
			});
			$("#uncheck_all_customer").prop("checked", false);
			customerUnCheckArray =[];
		}
		else
			$("#uncheck_all_customer").prop("checked", true);
	});
	
	$("#uncheck_all_customer").on("change", function(){
		if($(this).prop("checked"))
		{
			$(".customer_check_box").each(function(index, element) {
				$(this).prop("checked", false);
			});
			$("#check_all_customer").prop("checked", false);
			customerCheckArray =[];
			customerArray = [];
		}
		else
			$("#check_all_customer").prop("checked", true);
	});
	
});

function statusAction(field)
{
	var data={
			action	:	"emailtemplate/updatecustomergroupstatus",
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
