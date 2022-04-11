<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-header"> <strong><?=$formHeading?></strong> <small>Form</small> </div>
      <form id="addlicence" name="addlicence" enctype="multipart/form-data">
      <div class="card-block">
        
        
        <div class="row">
		  <div class="col-sm-12">
            <div class="form-group">
                <label for="licence_folder">Folder name <sup>*</sup></label>
                <input class="form-control" id="licence_folder" name="licence_folder" maxlength="50" placeholder="Enter folder name" type="text" value="General">
              </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label for="user_image">Select Licence File<sup>*</sup></label>
              <div class="input-group">
					<span class="input-group-addon" style="padding:0px" id="image_uploader"><i class="fa fa-file-pdf-o fa-lg"></i></span>
					<input class="form-control" id="licence_file" name="licence_file" style="padding-bottom: 4px; padding-top: 4px;" maxlength="100" value="" type="file" >
				</div>
            </div>
          </div>
          
        </div>
        <!--/row-->
        
          
        
             
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
      <button type="button" onClick="confirmMessage.Set('Are you sure to save licence Information...?', 'uploadLicence')" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name"><?=$btnText?></span></button>
      
    </div>    	
      </form>
    </div>
    
  </div>
  <div class="col-sm-6">
  	<div id="file_uploader" style="text-align:center; margin:50px auto; font-size:24px;"><i class="text-danger fa fa-file-pdf-o fa-lg"></i></div>
  </div>
</div>
<!--/col--> 

<!--/col-->
</div>
<script type="text/javascript">
function uploadLicence(){
	var data = {'licence_folder' : $("#licence_folder").val()};
	filesUpload('licence_file', data);
}
function removeLicence(id)
{
	if(confirm("Are you sure to remove this record... ?"))
	{
		var data={
			action	:	"company_resource/removelicence",
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

$(document).ready(function(e){
	$("#licence_folder").on("blur", function(){
		if($(this).val().trim() == ''){
			$(this).val('General');
		}
	})
})
</script> 


<div class="row">
<div class="col-lg-12">

    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Uploaded Licences List </div>
       <div class="block-fluid table-sorting clearfix">	   
		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-12 pt-1">
					<div class="row">
					<?php 
					$licences = new Licences();
					if($folderArray = $licences->getFolderName())
					{
						foreach($folderArray as $_folder){
							?>
							<div class="col-sm-6 col-md-4 col-lg-3">
								<div class="d-flex align-items-center  justify-content-between p-1 folder_box" data-label="<?php echo $_folder['licence_folder'];?>">
									<div class="pl-0 justify-content-start" style="min-width:60px;"> <img width="50px" class="img img-responsive" src="<?php echo $app->basePath('img/folder_icon.png')?>"><span class=" text-primary"><?php echo $_folder['record'];?> items</span></div>
									<div class="pr-1 justify-content-start">
									  <div class="text-value-sm"><?php echo $_folder['licence_folder'] ?: 'New Folder'?></div>
									  <div class="text-value-sm text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($_folder['folder_created_date']))?></div>
									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
					</div>	
<hr/>					
				</div>
				<div class="col-sm-12" id="tbldatatablebox">
					<table id="tbldatatable" class="table table-striped">
					  <thead>
						<tr>
						  <th>Folder</th>
						  <th class="hidden-sm hidden-md hidden-xs hidden-md hidden-sm visible-lg">Name</th>
						  <th class="hidden-sm hidden-md hidden-xs hidden-md hidden-sm visible-lg">Modified</th>
						  <th class="text-center">DownLoad</th>
						  <th class="text-center">Action</th>
						</tr>
					  </thead>
					  <tbody></tbody>
					</table>
				</div>
			</div>
	   </div>
        
      </div>
    </div>
    </div>
  </div>
  
<script type="text/javascript">
var tableHtml;
$(document).off('click', '.folder_box');
$(document).ready(function(){
	
	$(document).on('click', '.folder_box', function(){
		var ele = $(this);
		$(".folder_box").each(function(e){
			$(this).removeClass('folder_active');
		});
		getFolderContent(ele.attr('data-label'));
		ele.addClass('folder_active');
	});	
	
	tableHtml = $("#tbldatatablebox").html();
	$("#tbldatatablebox").html('');
});

function getFolderContent(licence_folder){
	var data={
					action			:	'company_resource/getlicencefoldercontent',
					licence_folder	:	licence_folder
			};
	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){	
			$("#tbldatatablebox").html('<center>Loading...</center>');
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);		
			toastMessage(arr[1]);
			$("#tbldatatablebox").html(tableHtml);
			$("#tbldatatable tbody").html(arr[2]);
			$("#tbldatatable").dataTable();
		}
	});
}

</script>