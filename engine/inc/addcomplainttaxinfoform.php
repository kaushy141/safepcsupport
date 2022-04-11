<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>Add Complaint Tax Info</strong> <small>Form</small> </div>
      <form id="addcomplainttaxinfo" name="addcomplainttaxinfo">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_vat_tax">Complaint vat tax(%)<sup>*</sup></label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-gbp"></i></span>
                  <input id="complaint_vat_tax" name="complaint_vat_tax" class="form-control" placeholder=".." type="number" min="0" max="100" value="<?=isset($complaint_vat_tax)?$complaint_vat_tax:"";?>">
                  <span class="input-group-addon">%</span> </div>
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_e_tax">Complaint E tax(%)<sup>*</sup></label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-gbp"></i></span>
                  <input id="complaint_e_tax" name="complaint_e_tax" class="form-control" placeholder=".." type="number" min="0" max="100" value="<?=isset($complaint_e_tax)?$complaint_e_tax:"";?>">
                  <span class="input-group-addon">%</span> </div>
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_z_tax">Complaint Z tax(%)<sup>*</sup></label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-gbp"></i></span>
                  <input id="complaint_z_tax" name="complaint_z_tax" class="form-control" placeholder=".." type="number" min="0" max="100" value="<?=isset($complaint_z_tax)?$complaint_z_tax:"";?>">
                  <span class="input-group-addon">%</span> </div>
              </div>
            </div>
          </div>
          
          
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_vat_tax">Complaint N tax(%)<sup>*</sup></label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-gbp"></i></span>
                  <input id="complaint_n_tax" name="complaint_n_tax" class="form-control" placeholder=".." type="number" min="0" max="100" value="<?=isset($complaint_n_tax)?$complaint_n_tax:"";?>">
                  <span class="input-group-addon">%</span> </div>
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_r_tax">Complaint R tax(%)<sup>*</sup></label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-gbp"></i></span>
                  <input id="complaint_r_tax" name="complaint_r_tax" class="form-control" placeholder=".." type="number" min="0" max="100" value="<?=isset($complaint_r_tax)?$complaint_r_tax:"";?>">
                  <span class="input-group-addon">%</span> </div>
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
                <label for="complaint_s_tax">Complaint S tax(%)<sup>*</sup></label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-gbp"></i></span>
                  <input id="complaint_s_tax" name="complaint_s_tax" class="form-control" placeholder=".." type="number" min="0" max="100" value="<?=isset($complaint_s_tax)?$complaint_s_tax:"";?>">
                  <span class="input-group-addon">%</span> </div>
              </div>
            </div>
          </div>
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to <?=$complaint_tax_id==0?"add":"updated"?> Tax Information...?', 'addAppInfo');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span> Application Tax Info </button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addcomplainttaxinfo";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="complaint_tax_id" name="complaint_tax_id" value="<?=isset($complaint_tax_id)?$complaint_tax_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">

function addAppInfo()
{
	if(validateFields("complaint_vat_tax"))
	{
		var data={
					action	:	$("#action").val()							
				};
		data = $.extend(data, $("#addcomplainttaxinfo").serializeFormJSON());
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
					$("#complaint_tax_id").val(arr[2]);
					$("#action").val("system/updatecomplainttaxinfo");
					$("#btn_action_name").text("UPDATE");
				}
				message(arr[1]);
			}
		})	
	}
}
</script> 