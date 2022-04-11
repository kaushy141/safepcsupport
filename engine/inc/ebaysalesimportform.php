<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-header"> <strong><?=$formHeading?></strong> <small>Form</small> </div>
      <form id="addebaysalesimport" name="addebaysalesimport" enctype="multipart/form-data">
      <div class="card-block">
        
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="user_image">Select eBay CSV File<sup>*</sup></label>
              <div class="input-group">
                                    <span class="input-group-addon" style="padding:0px" id="image_uploader"><i class="fa fa-file-excel-o fa-lg"></i></span>
                                    <input class="form-control" id="ebay_csv_file" name="ebay_csv_file" style="padding-bottom: 4px; padding-top: 4px;" maxlength="100" value="" type="file" >
                                </div>
            </div>
          </div>
          
        </div>
        <!--/row-->
        
          
        
             
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
      <button type="button" onClick="importEbayRecords('ebay_csv_file');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name"><?=$btnText?></span> eBay Records </button>
      
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
var original_csv_file_data = "<i class=\"fa fa-file-excel-o fa-lg\"></i>";
var call_back_csv_upload_handler;
var is_interval_csv_running=false;
var is_csv_file_uploaded=false;

function importEbayRecords(field_name)
{
	var file = _(field_name).files[0]; 
	var formdata = new FormData(); 
	var formdata = new FormData(); 
	formdata.append(field_name, file); 
	formdata.append('field_handler', field_name); 
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

function progressCSVHandler(event)
{
	dissableSubmission();
	var percent = (event.loaded / event.total) * 100;
	_("csv_uploader").innerHTML = Math.round(percent)+' % ';
		
	if(is_csv_file_uploaded == false && Math.round(percent)==100)
	{
		is_csv_file_uploaded = true;
		call_back_csv_upload_handler();
		_('ebay_csv_file').values = "";		
	}
	
	if(Math.round(percent)==100)
	_("csv_uploader").innerHTML = 'Processing... <i class="fa fa-refresh fa-lg m-t-2 fa-spin"></i>';
} 

function call_back_upload_handler_function()
{
	 is_interval_csv_running = true;
	_("csv_uploader").innerHTML = "Processing... <i class=\"fa fa-refresh fa-lg m-t-2 fa-spin\"></i>";
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


<div class="row">
<div class="col-lg-12">

    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Wastage Collection Item List </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>File Name</th>
              <th>Records</th>
              <th>Uploader</th>
              <th>Upload date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			$EbayCsv = new EbayCsv(0);
			echo $EbayCsv->getRecords();
			?>
          </tbody>
        </table>
        
      </div>
    </div>
    </div>
  </div>
  
<script type="text/javascript">
$("#tbldatatable").dataTable({"iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100], "sPaginationType": "full_numbers", "aoColumns": [ { "bSortable": false }, null, null, null, null], "order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [0], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,3,4,5 ] }]});
</script>