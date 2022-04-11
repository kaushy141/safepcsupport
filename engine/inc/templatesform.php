<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-header"> <strong><?=$formHeading?></strong> <small>Form</small> </div>
      <form id="addtemplates" name="addtemplates" enctype="multipart/form-data">
      <div class="card-block">
        
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="user_image">Select Templates File<sup>*</sup></label>
              <div class="input-group">
                                    <span class="input-group-addon" style="padding:0px" id="image_uploader"><i class="fa fa-file-pdf-o fa-lg"></i></span>
                                    <input class="form-control" id="templates_file" name="templates_file" style="padding-bottom: 4px; padding-top: 4px;" maxlength="100" value="" type="file" >
                                </div>
            </div>
          </div>
          
        </div>
        <!--/row-->
        
          
        
             
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
      <button type="button" onClick="filesUpload('templates_file');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name"><?=$btnText?></span></button>
      
    </div>    	
      </form>
    </div>
    
  </div>
  <div class="col-sm-6">
  	<div class="file_uploader" id="file_uploader" style="text-align:center; margin:50px auto; font-size:24px;"><i class="text-danger fa fa-file-pdf-o fa-lg"></i></div>
  </div>
</div>
<!--/col--> 

<!--/col-->
</div>
<script type="text/javascript">

function removetemplates(id)
{
	if(confirm("Are you sure to remove this record... ?"))
	{
		var data={
			action	:	"removetemplates",
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


<div class="row">
<div class="col-lg-12">

    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Uploaded Templates List </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>Templates</th>
              <th class="hidden-sm hidden-md hidden-xs hidden-md hidden-sm visible-lg">Modified</th>
              <th class="text-center">DownLoad</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			$templates = new Templates(0);
			echo $templates->getRecords();
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
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 1 ] }]});
</script>