<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?php echo $formHeading; ?></strong>
        <?php if(isset($product_id) && $product_id!=0):?>
        <div class="card-actions"> <a class="card-header-action redirect" title="Previous" href="<?php echo $app->basePath('updatebatchproduct/'.($product_id-1))?>"> <i class="fa fa-chevron-left"></i> </a> <a class="card-header-action" title="Next" href="<?php echo $app->basePath('updatebatchproduct/'.($product_id+1))?>"> <i class="fa fa-chevron-right"></i> </a> </div>
        <?php endif; ?>
      </div>
      <form id="addbachproductform" name="addbachproductform">
        <div class="card-block">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label for="product_name">Product Name<sup>*</sup></label>
                <input class="form-control" id="product_name" name="product_name" maxlength="250" placeholder="Enter product name" type="text" value="<?=isset($product_name)? htmlspecialchars($product_name):"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_type">Product Type<sup>*</sup></label>
                <input class="form-control" id="product_type" name="product_type" maxlength="50" placeholder="Enter product type" type="text" value="<?=isset($product_type)?$product_type:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_order_number ">Order number [Optional]<sup></sup></label>
                <input class="form-control" id="product_order_number" name="product_order_number" maxlength="50" placeholder="Enter product order number" type="text" value="<?=isset($product_order_number )?$product_order_number :"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_reg_id">Registartion ID [Optional]<sup></sup></label>
                <input class="form-control" id="product_reg_id" name="product_reg_id" maxlength="20" placeholder="Enter registration id" type="text" value="<?=isset($product_reg_id)?$product_reg_id:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_serial_number">Serial number<sup></sup> <small>N/A or Left Blank (if not available)</small></label>
                <input class="form-control input_text_upper" id="product_serial_number" name="product_serial_number" maxlength="50" placeholder="Enter company vehicle reg number" type="text" value="<?=isset($product_serial_number)?$product_serial_number:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_sku">SKU<sup></sup></label>
                <input class="form-control input_text_upper" id="product_sku" name="product_sku" maxlength="50" placeholder="Enter product SKU code" type="text" value="<?=isset($product_sku)?$product_sku:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_model">Model name<sup></sup></label>
                <input class="form-control" id="product_model" name="product_model" maxlength="50" placeholder="Enter product model name" type="text" value="<?=isset($product_model)?$product_model:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_condition">Condition<sup></sup></label>
                <input class="form-control" id="product_condition" name="product_condition" maxlength="50" placeholder="Enter product condition" type="text" value="<?=isset($product_condition)?$product_condition:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_processor">Processor name<sup></sup></label>
                <input class="form-control" id="product_processor" name="product_processor" maxlength="50" placeholder="Enter proessor name" type="text" value="<?=isset($product_processor)?$product_processor:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_processor_speed">Processor speed<sup></sup></label>
                <div class="input-group">
                  <input class="form-control" id="product_processor_speed" name="product_processor_speed" maxlength="10" placeholder="Enter processor speed" type="number" step="0.01" value="<?=isset($product_processor_speed)?$product_processor_speed:"";?>">
                  <span class="input-group-addon">GHz</span></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_operating_system">Operating system<sup></sup></label>
                <input class="form-control" id="product_operating_system" name="product_operating_system" maxlength="50" placeholder="Enter processor speed" type="text" value="<?=isset($product_operating_system)?$product_operating_system:"N/A";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_screen_size">Screen size<sup></sup></label>
                <div class="input-group">
                  <input class="form-control" id="product_screen_size" name="product_screen_size" maxlength="2" placeholder="Enter screen size in inches" type="number" step="0.1" value="<?=isset($product_screen_size)?$product_screen_size:"";?>">
                  <span class="input-group-addon">" Inch</span></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_ram">RAM<sup></sup></label>
                <input class="form-control" id="product_ram" name="product_ram" maxlength="10" placeholder="Enter product RAM Ex. 4GB, 8GB" type="text" value="<?=isset($product_ram)?$product_ram:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_hdd">HDD<sup></sup></label>
                <input class="form-control" id="product_hdd" name="product_hdd" maxlength="10" placeholder="Enter product HDD" type="text" value="<?=isset($product_hdd)?$product_hdd:"0";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_ssd">SSD<sup></sup></label>
                <input class="form-control" id="product_ssd" name="product_ssd" maxlength="10" placeholder="Enter product SSD" type="text" value="<?=isset($product_ssd)?$product_ssd:"0";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_fusion_drive">Fusion Drive<sup></sup></label>
                <input class="form-control" id="product_fusion_drive" name="product_fusion_drive" maxlength="10" placeholder="Enter product fusion drive" type="text" value="<?=isset($product_fusion_drive)?$product_fusion_drive:"0";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_release">Release Year<sup></sup></label>
                <input class="form-control" id="product_release" name="product_release" maxlength="50" placeholder="Enter product release detail" type="text" value="<?=isset($product_release)?$product_release:"N/A";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_reason">Product Issues<sup></sup></label>
                <input class="form-control" id="product_reason" name="product_reason" maxlength="250" placeholder="Enter product reason detail" type="text" value="<?=isset($product_reason)?$product_reason:"N/A";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_battery_cycle">Battery cycle<sup></sup></label>
                <input class="form-control" id="product_battery_cycle" name="product_battery_cycle" maxlength="10" placeholder="Enter battery cycle detail" type="number" value="<?=isset($product_battery_cycle)?$product_battery_cycle:"N/A";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_batch_type">Batch type<sup></sup></label>
                <input class="form-control" id="product_batch_type" name="product_batch_type" maxlength="50" placeholder="Enter batch type" type="text" value="<?=isset($product_batch_type)?$product_batch_type:"N/A";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_batch_code">Batch Code<sup></sup></label>
                <input class="form-control" id="product_batch_code" name="product_batch_code" maxlength="50" placeholder="Enter batch type" type="text" value="<?=isset($product_batch_code)?$product_batch_code:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_grade">Product Grade<sup></sup></label>
                <input class="form-control input_text_upper" id="product_grade" name="product_grade" maxlength="10" placeholder="Enter product grade like A,B,C, A+" type="text" value="<?=isset($product_grade) ? $product_grade:"";?>">
              </div>
            </div> 
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_part_number">Product Part Number<sup></sup></label>
                <input class="form-control input_text_upper" id="product_part_number" name="product_part_number" maxlength="10" placeholder="Enter product part number" type="text" value="<?=isset($product_part_number) ? $product_part_number:"";?>">
              </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_price">Product Cost<sup></sup></label>
                <input class="form-control input_text_upper" id="product_price" name="product_price" max="999999" placeholder="Enter product cost" type="number" step="0.01" value="<?=isset($product_price) ? $product_price:"";?>">
              </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_store_location">Store Location<sup></sup></label>
                <input class="form-control input_text_upper" id="product_store_location" name="product_store_location" placeholder="Product location Ex. UK, UAE" type="text" value="<?=isset($product_store_location) ? $product_store_location:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_age_date">Product Age Date<sup>*</sup></label>
                <div class="input-group date">
                  <input type='text' class="form-control" id="product_age_date" name="product_age_date" placeholder="YYYY-MM-DD" value="<?=(isset($product_age_date) && $product_age_date!= '')?date("Y-m-d", strtotime($product_age_date)):'';?>" />
                  <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="product_age_date"><i class="fa fa-calendar fa-fw"></i></label>
                  </span> </div>
                <script type="text/javascript">
						$('#product_age_date').datepicker({
							format: "yyyy-mm-dd",
							autoclose:true,
							startDate  : '<?=date('Y-m-d')?>',
							daysOfWeekHighlighted: '0,6',
							todayHighlight:true
						});
				</script> 
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_in_stock"><i class="fa fa-cubes fa-fw"></i> &nbsp;Product in Stock</label>
                <label class="switch switch-icon switch-pill switch-info pull-right">
                  <input class="switch-input" <?php echo (isset($product_in_stock) && $product_in_stock) ? "checked":"" ?> id="product_in_stock" value="1" name="product_in_stock" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_verified"><i class="fa fa-check fa-fw"></i> &nbsp;Product is verified</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" <?php echo (isset($product_verified) && $product_verified) ? "checked":"" ?> id="product_verified" value="1" name="product_verified" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
			
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_status"><i class="fa fa-bullhorn fa-fw"></i> &nbsp;Product is enabled</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" <?php echo (isset($product_status) && $product_status) ? "checked":"" ?> id="product_status" value="1" name="product_status" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
				  <!--<br/>
				  <span class=" text-warning"><i class="fa fa-warning text-danger"></i> Disabled Product will not available in search <i>(Not Recommended)</i></span>-->
              </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_is_on_way"><i class="fa fa-truck fa-fw"></i> &nbsp;Product is on the way</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" <?php echo (isset($product_is_on_way) && $product_is_on_way) ? "checked":"" ?> id="product_is_on_way" value="1" name="product_is_on_way" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
			
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="product_under_technician">Product under Technician</label>
                <br/>
                <label for="product_under_technician"><i class="fa fa-wrench fa-fw"></i> &nbsp;Under repair ?</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="product_under_technician" <?php if(isset($product_under_technician) && $product_under_technician ==1 ) echo "checked";?> value="1" name="product_under_technician" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" id="product_under_technician_box" style="display:<?php echo (isset($product_under_technician) && $product_under_technician ==1 ) ? "box":"none";?>;">
              <div class="form-group">
                <label for="no_of_copy">Select Tehnician<sup>*</sup></label>
                <select id="product_under_technician_id" name="product_under_technician_id" class="form-control" size="1">
                  <?php
					$Employee = new Employee(0);
					echo $Employee->getTechnicianOption(isset($product_under_technician_id)?$product_under_technician_id:"0");
				?>
                </select>
              </div>
            </div>
          </div>
          <?php if(isset($product_id) && $product_id != 0){?>
          <hr/>
          <div class="row">
            <div class="col-md-12" style="margin-top: 1px;">
              <div class="card">
                <div class="card-header card-default"><i class="fa fa-file-text"></i> Item Media Files
                  <div class="card-actions"> <a data-id="<?php echo $product_id?>" data-image-type="0" data-media-section="<?=MediaSection::$batchProductMedia?>" class="dropdown-item process_image_camera" href="#" title="Take image for <?php echo $product_code?>"><i class="fa fa-camera text-danger"></i></a> </div>
                </div>
                <div class="card-block">
                  <div class="row media_uploaded_image_box" id="media_uploaded_image_box_<?php echo $product_id;?>">
                    <?php 
					  $iCounter = 0;
					  $cm = new BatchProductMedia(0);
					  $recorded_snapshot_list = $cm->getImageList($product_id);
					  if($recorded_snapshot_list):
					  foreach($recorded_snapshot_list as $itemCapt):
						  $iCounter++;
					  ?>
                    <div class="col-xs-6 col-sm-4 col-lg-3 thumb" data-record-id="<?php echo $itemCapt['image_id']; ?>" id="item-process-media-container-<?php echo $itemCapt['image_id']; ?>">
                      <div class="card">
                        <div class="card-header card-default">Image <?php echo $iCounter;?>
                          <div class="card-actions"><a href="javascript:removeBatchProcessMedia('<?php echo $itemCapt['image_id']; ?>', '<?php echo $product_id; ?>');"><i class="icon-close"></i></a></div>
                        </div>
                        <div class="card-block p-a-1 clearfix"> <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="Image <?php echo $iCounter;?>" data-image="<?php echo $app->basePath($itemCapt['image_path']); ?>" data-target="#image-gallery"> <img class="img-thumbnail" src="<?php echo getResizeImage($itemCapt['image_path'], 100); ?>" alt="Image <?php echo $iCounter;?>"> </a>
                          <div class="font-weight-normal  text-center"><?php echo $itemCapt['image_label']; ?></div>
                        </div>
                      </div>
                    </div>
                    <?php
						endforeach;

						endif;
						?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr/>
          <?php }?>
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="create_product_copy">Create a copy of this product</label>
                <br/>
                <label for="create_product_copy"><i class="fa fa-copy fa-fw"></i> &nbsp;Enter No of copy ?</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="create_product_copy" value="1" name="create_product_copy" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" id="create_product_copy_box" style="display:none;">
              <div class="form-group">
                <label for="no_of_copy">No of Copy<sup>*</sup></label>
                <div class="input-group date">
                  <input class="form-control" id="no_of_copy" name="no_of_copy" min="1" max="10" value="0" type="number">
                  <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="no_of_copy"><i class="fa fa-copy fa-fw"></i></label>
                  </span> </div>
              </div>
            </div>
          </div>
          <?php if(isAdmin()):?>
          <div class="row">
            <div class="col-md-12 mt-1 text-right">
              <?php 
			$creator = new Employee((isset($product_created_by) && $product_created_by != 0 ) ? $product_created_by : getLoginId());
			$creatorData = $creator->getDetails();
			$product_created_date = isset($product_created_date) ? $product_created_date : date('Y-m-d H:i:s');
			?>
              <div class="pull-right pl-1"><img class="img img-circle" style="margin-top:0px; margin-bottom:0px; height:40px;" src="<?php echo getResizeImage($creatorData["user_image"],50)?>"/></div>
              <div class="pull-right">Originally created by <?php echo $creatorData['user_name']?> <i class="fa fa-check-circle text-success"></i><br/>
                <span class="text-muted" style="font-size: 0.9em;"><?php echo dateView($product_created_date, 'NOW')?> <?php echo dateView($product_created_date, 'FULL')?></span> </div>
            </div>
          </div>
          <?php endif;?>
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to save product Information...?', 'addBatchProduct')" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-fw"></i>
          <?=$btnText?>
          </span></button>
          <?php if(isset($product_id) && $product_id!=0):?>		
		  <div class="btn-group dropup">
			<button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action<span class="sr-only"></span></button>
			<div class="dropdown-menu dropdown-menu-right"> 
				<a class="dropdown-item redirect" href="<?php echo $app->basePath("updatebatchproduct/$product_id"); ?>"><i class="fa fa-refresh fa-fw"></i> Reload</a>
				<a class="dropdown-item" href="#"  data-toggle="modal" data-target="#appModal"  onClick="openChatLogForm('<?=$product_id?>|B', '<?=$product_type?> Log Report')"><i class="fa fa-refresh fa-fw"></i> Comments</a>
				<?php if(isAdmin()){?>
				<a class="dropdown-item redirect" href="<?php echo $app->basePath("productsalehistory/$product_id"); ?>"><i class="fa fa-shopping-cart fa-fw"></i> Sale history</a> 
				<a class="dropdown-item" href="javascript:newWindow('<?php echo DOC::BATCHPROCODE($product_id)?>')"><i class="fa fa-barcode fa-fw"></i> Print Barcode</a>
				<?php 
				}?>
			</div>
		  </div>
          <?php endif;?>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"product/savebatchproductrecord";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="product_id" name="product_id" value="<?=isset($product_id)?$product_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">
$(document).ready(function(){
	$(".process_image_camera").on("click", function(){
	openMediaUploader($(this).attr('data-media-section'), $(this).attr('data-id'), "data_record_image", "Seelct Batch product's image source", null);	
	});
});
$("#create_product_copy").on("change", function(){
	if($(this).is(":checked")){
		$("#create_product_copy_box").show();
	}else{
		$("#create_product_copy_box").hide();
	}			
});  
$("#product_under_technician").on("change", function(){
	if($(this).is(":checked")){
		$("#product_under_technician_box").show();
	}else{
		$("#product_under_technician_box").hide();
	}			
});  
function addBatchProduct()
{
	if(validateFields("product_type, product_name, product_batch_code"))
	{
		var data={
					action	:	$("#action").val(),
					create_product_coopy : $("#create_product_copy:checked").val()
				};
		data = $.extend(data, $("#addbachproductform").serializeFormJSON());
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
					$("#product_id").val(arr[2]);
					if(arr[3] == 1){
						message(arr[1], 0, true);
						return;
					}
				}
				message(arr[1]);
			}
		})	
	}
}

</script> 
<script>
function uploadMediaFile(param)
{
	processUploadMediaFile(param.name, '_'+$("#keyid").val(), '<?=MediaSection::$batchProductMedia?>');
	// if(confirm("Are you sure to upload this media file... ?"))
	// {
		// var files = _(field_name).files;
		// var formdata = new FormData(); 
		// if(files.length > 0){
			// for (var index = 0; index < files.length; index++) 
			// {
				// formdata.append('webcam[]', files[index]); 
			// }
			
			// formdata.append('batch_product_id', $("#keyid").val()); 
			// is_interval_running = false;
			// is_file_uploaded 	= false;
			// $("#media_uploaded_image_box_"+$("#keyid").val()).append('<div class="col-xs-12 col-lg-3"  id="item-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading image<br/><br/>Please wait</center></div>');

			// var ajax = new XMLHttpRequest();	
			// ajax.addEventListener("progress", progressMediaUpload, false); 
			// ajax.addEventListener("load", completeMediaUpload, false); 
			// ajax.open("POST", sitePath +"saveimage.php"); 
			// ajax.send(formdata);
		// }
	// }	
}


function completeMediaUpload(event)
{
	var rc	=	JSON.parse(event.target.responseText);
	message(rc[1],2000);
	
	if(rc['image_id_array'].length > 0)
	{
		for(var i=0; i < rc['image_id_array'].length; i++){
			$("#media_uploaded_image_box_"+rc['id']).append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['image_id_array'][i]+'" id="item-process-media-container-'+rc['image_id_array'][i]+'"><div class="card"><div class="card-header card-success">Image<div class="card-actions"><a href="javascript:removeCollectionProcesstMedia(\''+rc['image_id_array'][i]+'\', \''+rc['id']+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['image_path_array'][i]+'" /></div></div></div>');
		}
	}
	
	message('success|File uploaded', 1000);
	$("#item-media-processing").remove();	
	$("#media_uploaded_image_box_"+rc['id']+" .empty_image_box").remove();
}

function removeBatchProcessMedia(id, batch_product_id)
{
	if(confirm("Are you sure to remove this media file... ?"))
	{
		var data={
			action		:	'product/removebatchproductmedia',
			record_id	: id			
		};
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Removing product media file...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{					
					$("#item-process-media-container-"+id).remove();
					if($("#media_uploaded_image_box_"+batch_product_id+" div").length ==0)
					$("#media_uploaded_image_box_"+batch_product_id).html(EMPTY_IMAGE_BOX);
				}
				message(arr[1],2000);
			}
		})	
	}
	else
		$("#item-process-media-container-"+id+" .card").show();
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
			batch_product_id	: $("#keyid").val()
		};
		if(confirm("Are you sure to upload this media file... ?"))
		{
			$("#media_uploaded_image_box_"+$("#keyid").val()).append('<div class="col-xs-12 col-lg-3"  id="item-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading Snapshot<br/><br/>Please wait</center></div>');
			Webcam.upload( data_uri, parameter, sitePath+'saveimage.php', function(code, text) {
				var rc	=	JSON.parse(text);	
				$("#media_uploaded_image_box_"+rc['batch_product_id']).append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['repair_image_id']+'" id="item-complaint-media-container-'+rc['repair_image_id']+'"><div class="card"><div class="card-header card-success">Image<div class="card-actions"><a href="javascript:removeCollectionProcesstMedia(\''+rc['repair_image_id']+'\', \''+rc['batch_product_id']+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['repair_image_path']+'" /></div></div></div>');
	$("#item-media-processing").remove();	
	$("#media_uploaded_image_box_"+rc['batch_product_id']+" .empty_image_box").remove();
			} );
		}
	} );
	
}
	
$(document).ready(function(e) {
	loadGallery(true, 'a.thumbnail');
	$(".media_uploaded_image_box").each(function(){
		if($(this).children().length == 0)
			$(this).html(EMPTY_IMAGE_BOX);
	});
});

</script>