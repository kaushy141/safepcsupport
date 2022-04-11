<?php if(isset($wc_id) && $wc_id !=0) echo drawCollectionProcedure($wc_id);?>
<div class="row" id="collection_form_container">
  <div class="col-sm-12">
    <form id="managecollection" name="managecollection">
      
      <div class="card">
        <div class="card-header card-primary"><i class="fa fa-file-text"></i> Collection Media Files 
		<div class="card-actions">         
		   <a data-id="<?=isset($wc_id)?$wc_id:"0";?>" data-image-type="0" data-media-section="<?=MediaSection::$collectionMedia?>" class="dropdown-item process_image_camera" href="#" title="Take image for Collection"><i class="fa fa-camera text-danger"></i></a>
			</div>	
		</div>
        <div class="card-block"> 
          <!--/row-->
          <div class="row" id="media_uploaded_image_box">
          <?php 
			$iCounter = 0;
		  $WcRecordMedia = new WcRecordMedia(0);
		  $recorded_snapshot_list = $WcRecordMedia->getRecordList($wc_id);
		  if($recorded_snapshot_list):
		  foreach($recorded_snapshot_list as $itemCapt):
			  $iCounter++;
		  ?>
              <div class="col-xs-4 col-sm-3 col-lg-2 thumb" data-record-id="<?php echo $itemCapt['record_id']; ?>" id="item-collection-media-container-<?php echo $itemCapt['record_id']; ?>"><div class="card"><div class="card-header card-default"><?php echo trim($itemCapt['record_file_name'])==""?"Image $iCounter":$itemCapt['record_file_name']; ?><div class="card-actions"><a class="btn-close" href="javascript:removeCollectionMedia('<?php echo $itemCapt['record_id']; ?>');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix">
				  <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="Image <?php echo $iCounter;?>" data-image="<?php echo $app->basePath($itemCapt['record_file_path']); ?>" data-target="#image-gallery">
                    <img class="img-thumbnail" src="<?php echo getResizeImage($itemCapt['record_file_path'], 100); ?>" alt="Image <?php echo $iCounter;?>">
                </a>
				  <div class="font-weight-normal  text-center"><?php echo $itemCapt['record_remark']; ?></div></div></div></div>			  		  
            
            <?php
            endforeach;
					
			endif;
			?>
          </div>
          <!--/row--> 
        </div>
      </div>
      <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"managecollection";?>"  />
      <input type="hidden" id="wc_id" name="wc_id" value="<?=isset($wc_id)?$wc_id:"0";?>"  />
    </form>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(e) {
	loadGallery(true, 'a.thumbnail');
    if($("#media_uploaded_image_box div").length ==0)
		$("#media_uploaded_image_box").html(EMPTY_IMAGE_BOX);
	
	$(".process_image_camera").on("click", function(){
		openMediaUploader($(this).attr('data-media-section'), $(this).attr('data-id'), "collection_record_image", "Seelct Collection product's image source", null);	
	});
});
function uploadMediaFile(param)
{
	processUploadMediaFile(param.name, '', '<?=MediaSection::$collectionMedia?>');
	// if(confirm("Are you sure to upload this media file... ?"))
	// {
		// var files = _(field_name).files;
		// var formdata = new FormData(); 
		// if(files.length > 0)
		// {
			// for (var index = 0; index < files.length; index++) 
			// {
				// formdata.append('webcam[]', files[index]); 
			// }
			// formdata.append('wc_id', $("#wc_id").val()); 
			// is_interval_running = false;
			// is_file_uploaded 	= false;
			// $("#media_uploaded_image_box").append('<div class="col-xs-12 col-lg-3"  id="item-collection-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading Snapshot<br/><br/>Please wait</center></div>');

			// var ajax = new XMLHttpRequest();
			// ajax.upload.addEventListener("progress", progressHandler, false); 
			// ajax.addEventListener("load", completeHandler, false);	
			// ajax.addEventListener("load", completeMediaUpload, false);  
			// ajax.addEventListener("error", errorHandler, false); 
			// ajax.addEventListener("abort", abortHandler, false); 
			// ajax.open("POST", sitePath +"saveimage.php"); 
			// ajax.send(formdata);
		// }
	// }
	
}

function completeMediaUpload(event)
{
	var rc	=	JSON.parse(event.target.responseText);
	if(rc['image_id_array'].length > 0)
	{
		for(var i=0; i < rc['image_id_array'].length; i++){
			$("#media_uploaded_image_box").append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['image_id_array'][i]+'" id="item-collection-media-container-'+rc['image_id_array'][i]+'"><div class="card"><div class="card-header card-success">Image<div class="card-actions"><a class="btn-close" href="javascript:removeCollectionMedia(\''+rc['image_id_array'][i]+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['image_path_array'][i]+'" /></div></div></div>');
		}
	}
	message('success|File uploaded', 1000);
	$("#item-collection-media-processing").remove();	
	$("#media_uploaded_image_box .empty_image_box").remove();
}


function removeCollectionMedia(id)
{
	if(confirm("Are you sure to remove this media file... ?"))
	{
		var data={
			action		:	'removecollectionmedia',
			wc_id		:	$("#wc_id").val(),
			record_id	: id			
		};
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Removing Collection media file...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{					
					$("#item-collection-media-container-"+id).remove();
					if($("#media_uploaded_image_box div").length ==0)
					$("#media_uploaded_image_box").html(EMPTY_IMAGE_BOX);
				}
				message(arr[1],2000);
			}
		})	
	}
}
</script> 
<script language="JavaScript" type="text/javascript">
function take_snapshot() {
	if(!cametaStatus){
		message("danger|Please start camera first...", 2000);
		return false;
	}
	
	// take snapshot and get image data
	Webcam.snap( function(data_uri) {
		// display results in page
		var count = parseInt($(".snapshot_image_count_row").length)+1;
		var parameter={
			wc_id	:	$("#wc_id").val()		
		};
		if(confirm("Are you sure to upload this media file... ?"))
		{
			$("#media_uploaded_image_box").append('<div class="col-xs-12 col-lg-3"  id="item-collection-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading Snapshot<br/><br/>Please wait</center></div>');
			Webcam.upload( data_uri, parameter, sitePath+'saveimage.php', function(code, text) {
				var rc	=	JSON.parse(text);	
				$("#media_uploaded_image_box"+rc['wc_id']).append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['record_id']+'" id="item-collection-media-container-'+rc['record_id']+'"><div class="card"><div class="card-header card-success">Cam Image<div class="card-actions"><a class="btn-close" href="javascript:removeCollectionMedia(\''+rc['record_id']+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['record_file_path']+'" /></div></div></div>');
				$("#item-collection-media-processing").remove();
			} );
		}
	} );
	
}
</script>



