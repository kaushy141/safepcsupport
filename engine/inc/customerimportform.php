<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-header"> <strong><?=$formHeading?></strong> <small>Form</small> </div>
      <form id="addcustomerimport" name="addcustomerimport" enctype="multipart/form-data">
      <div class="card-block">
        
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="user_image">Select Customer CSV File<sup>*</sup></label>
              <div class="input-group">
                                    <span class="input-group-addon" style="padding:0px" id="image_uploader"><i class="fa fa-file-excel-o fa-lg"></i></span>
                                    <input class="form-control" id="customer_csv_file" name="customer_csv_file" style="padding-bottom: 4px; padding-top: 4px;" maxlength="100" value="" type="file" >
                                </div>
            </div>
          </div>
          <div class="col-sm-12">
              <div class="form-group">
                <label for="auto_customer_feedback"><i class="fa fa-envelope-o fa-lg m-t-2"></i> &nbsp;Send Feedback Request automatically</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="auto_customer_feedback" value="1" name="auto_customer_feedback" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-sm-12" id="store_container_box" style="display:none;">
              <div class="form-group">
                <label for="store_id">Select Request Store<sup>*</sup></label>
                <select id="store_id" name="store_id" class="form-control" size="1">
                  <?php
                $store = new Store(0);
				echo $store->getOptions(isset($complaint_store_id)?$complaint_store_id:"0");
				?>
                </select>
              </div>
            </div>
          
        </div>
        <!--/row-->
        
          
        
             
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
      <button type="button" onClick="importCustomerRecords('customer_csv_file');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name"><?=$btnText?></span> Customer Records </button>
      
    </div>    	
      </form>
    </div>
    
  </div>
  <div class="col-sm-6">
  	<div id="csv_uploader" style="text-align:center; margin:50px auto; font-size:24px;"><i class="fa fa-file-excel-o fa-lg"></i></div>
  </div>
</div>
<!--/col--> 

<!--/col-->
</div>
<script type="text/javascript">
$("#auto_customer_feedback").on("change", function(){
	if($(this).is(":checked"))
		$("#store_container_box").show(); 
	else
		$("#store_container_box").hide(); 
});


var original_csv_file_data = "<i class=\"fa fa-file-excel-o fa-lg\"></i>";
var call_back_csv_upload_handler;
var is_interval_csv_running=false;
var is_csv_file_uploaded=false;

function importCustomerRecords(field_name)
{
	var success = true;
	
	if($("#customer_csv_file").val() == "")
	{
		message("danger|Please select csv file.");
		success = false;
		return false;
	}
	if($("#auto_customer_feedback").is(":checked"))
	{
		if($("#store_id").val() == 0)
		{
			message("danger|Please select store.");
			success = false;
			return false;
		}
	}
	if(success){
		var file = _(field_name).files[0]; 
		var formdata = new FormData(); 
		var formdata = new FormData(); 
		formdata.append(field_name, file); 
		formdata.append('field_handler', field_name); 
		formdata.append('auto_customer_feedback', $("#auto_customer_feedback").is(":checked") ? 1 : 0); 
		formdata.append('store_id', $("#store_id").val()); 
		is_interval_csv_running = false;
		is_csv_file_uploaded 	= false;
		var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", progressCSVHandler, false); 
		ajax.addEventListener("load", completeCSVHandler, false);
		ajax.addEventListener("error", errorCSVHandler, false); 
		ajax.addEventListener("abort", abortCSVHandler, false); 
		ajax.open("POST", sitePath +"csvupload.php"); 
		ajax.send(formdata); 
	}
} 

function progressCSVHandler(event)
{
	dissableSubmission();
	var percent = (event.loaded / event.total) * 100;
	_("csv_uploader").innerHTML = Math.round(percent)+' % ';
	
	if(percent == 100)
	_("csv_uploader").innerHTML = "File Uploaded<br/>Processing... <i class=\"fa fa-refresh fa-lg m-t-2 fa-spin\"></i><br/>This may take a while. Please wait.<br/> [Don't refresh or Reload Page]";
		
	if(is_csv_file_uploaded == false && Math.round(percent)==100)
	{
		is_csv_file_uploaded = true;
		call_back_csv_upload_handler();
		_('ebay_csv_file').values = "";		
	}
	
	if(Math.round(percent)==100)
	{
	_("csv_uploader").innerHTML = 'Processing... <i class="fa fa-refresh fa-lg m-t-2 fa-spin"></i>';
	}
} 

function call_back_upload_handler_function()
{
	 is_interval_csv_running = true;
	_("csv_uploader").innerHTML = "Processing... <i class=\"fa fa-refresh fa-lg m-t-2 fa-spin\"></i><br/>This may take a while. Please wait.";
}

function completeCSVHandler(event)
{	
	enableSubmission(event.target.responseText);
	var arr	=	JSON.parse(event.target.responseText);
	if(arr[1])
	{
		message(arr[1]);
		if(arr[0]==200)
		_("csv_uploader").innerHTML = arr[2];
		else
		_("csv_uploader").innerHTML = original_csv_file_data;
	}
	else
	_("csv_uploader").innerHTML = original_csv_file_data;
} 

function errorCSVHandler(event)
{
	enableSubmission(event.target.responseText);
	_("csv_uploader").innerHTML = original_csv_file_data;
	message("danger|File Upload error. Please try gaian.");
} 

function abortCSVHandler(event)
{ 
	enableSubmission(event.target.responseText);
	_("csv_uploader").innerHTML = original_csv_file_data;
	message("danger|File Upload aborted by user. Please try gaian.");
}

</script> 