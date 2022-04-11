<?php
$wcItem = new WcItem($wc_process_item_id);
$wciData = $wcItem->getDetails();
$attributes = $wcItem->getCollectionItemAttributesValues($wc_process_id);

$allItems = CollectionProcess::getAllProcessCodeOfItem($wc_process_wc_id, $wc_process_item_id);
$formHeading = "$wciData[wci_name] #$wc_process_asset_code (".(array_search($wc_process_asset_code, $allItems)+1)."/".count($allItems).")";
$prevItem = isset($allItems[array_search($wc_process_asset_code, $allItems)-1]) ? $allItems[array_search($wc_process_asset_code, $allItems)-1] : NULL;
$nextItem = isset($allItems[array_search($wc_process_asset_code, $allItems)+1]) ? $allItems[array_search($wc_process_asset_code, $allItems)+1] : NULL;
?>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"><strong><i class="fa fa-lg <?php echo $wciData['wci_item_icon']?>"></i> <?=$formHeading?></strong>
	  
	   <div class="card-actions">
	   <?php  if($prevItem != NULL){ ?>
            <a class="redirect" data-title="Prev" title="Prev" href="<?php echo $app->basePath('updatewcprocessitem/'.$prevItem);?>"><i class="icon-arrow-left icons font-2xl d-block m-t-2"></i></a>
	   <?php }?>
	  <?php  if($nextItem != NULL){ ?>
            <a class="redirect" data-title="Next" title="Next" href="<?php echo $app->basePath('updatewcprocessitem/'.$nextItem);?>"><i class="icon-arrow-right icons font-2xl d-block m-t-2"></i></a>
	  <?php }?>
        </div>
	  </div>
      <form id="addwcprocessitem" name="addwcprocessitem" onSubmit="return false;" >
      <div class="card-block">
	  <div class="row">
		<div class="col-md-12col-sm-12 col-xs-12">
			<div class="progress-group-bars">
				<div class="progress progress-xs" style="height:4px; margin-top:-2px;">
					<div class="progress-bar bg-success" role="progressbar" style="width: <?php echo min(floor((array_search($wc_process_asset_code, $allItems)+1)*100/count($allItems)),100)?>%" aria-valuenow="<?php echo array_search($wc_process_asset_code, $allItems)+1;?>" aria-valuemin="0" aria-valuemax="<?php echo count($allItems); ?>"></div>
				</div>
			</div>
		</div>
	  </div>
	  <div class="row">
		<div class="col-md-3 col-sm-4 col-xs-6">
			<label>
				<input class="chkhandle handleall" data-chk="chkattribute" type="checkbox" value="" /> Check All
			</label>
		</div>
		<div class="col-md-3 col-sm-4 col-xs-6">
			<label>
				<input class="chkhandle handletext" data-chk="chktext" type="checkbox" value="" /> Check all Text
			</label>
		</div>
		<div class="col-md-3 col-sm-4 col-xs-6">
			<label>
				<input class="chkhandle handledropdown" data-chk="chkdropdown" type="checkbox" value="" /> Check All Combo
			</label>
		</div>
		<div class="col-md-3 col-sm-4 col-xs-6">
			<label>
				<input class="chkhandle handleradio" data-chk="chkboolean" type="checkbox" value="" /> Check all Radio
			</label>
		</div>
		
	  </div>
	  <hr/>
        <div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_item_make">Make<sup>*</sup></label>
				<div class="input-group">
				<span class="input-group-addon">
					<input class="chkattribute chktext" type="checkbox" name="chkattribute[]" value="wc_process_item_make" />
				</span>
                <input class="form-control" id="wc_process_item_make" name="wc_process_item_make" maxlength="50" placeholder="Enter item make name" type="text" value="<?=isset($wc_process_item_make)?$wc_process_item_make:"";?>">
              </div>
			  </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_item_model">Model<sup>*</sup></label>
				<div class="input-group">
				<span class="input-group-addon">
					<input class="chkattribute chktext" type="checkbox" name="chkattribute[]" value="wc_process_item_model" />
				</span>
                <input class="form-control" id="wc_process_item_model" name="wc_process_item_model" maxlength="100" placeholder="Enter item model number" type="text" value="<?=isset($wc_process_item_model)?$wc_process_item_model:"";?>">
              </div>
			  </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_item_name">Name<sup>*</sup></label>
				<div class="input-group">
				<span class="input-group-addon">
					<input class="chkattribute chktext" type="checkbox" name="chkattribute[]" value="wc_process_item_name" />
				</span>
                <input class="form-control" id="wc_process_item_name" name="wc_process_item_name" maxlength="200" placeholder="Enter item full name" type="text" value="<?=isset($wc_process_item_name)?$wc_process_item_name:"";?>">
              </div>
			  </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_item_sku">Sku<sup>*</sup></label>
				<div class="input-group">
				<span class="input-group-addon">
					<input class="chkattribute chktext" type="checkbox" name="chkattribute[]" value="wc_process_item_sku" />
				</span>
                <input class="form-control" id="wc_process_item_sku" name="wc_process_item_sku" maxlength="50" placeholder="Enter item sku" type="text" value="<?=isset($wc_process_item_sku)?$wc_process_item_sku:"";?>">
				<span class="input-group-addon">
				<span id="fetchskudetail" title="Click to fetch this sku detail" class="fa fa-tasks fa-lg" style="cursor:pointer;"></span>
			  </span>
              </div>
			  </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_item_sr_no">Serial number<sup></sup> <small>N/A or Left Blank (if not available)</small></label>
                <input class="form-control input_text_upper" id="wc_process_item_sr_no" name="wc_process_item_sr_no" maxlength="50" placeholder="Enter item serial number" type="text" value="<?=isset($wc_process_item_sr_no)?$wc_process_item_sr_no:"";?>">
              </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_item_weight">Weight<sup></sup></label>
				<div class="input-group">
				<span class="input-group-addon">
					<input class="chkattribute chktext" type="checkbox" name="chkattribute[]" value="wc_process_item_weight" />
				</span>
                <input class="form-control" id="wc_process_item_weight" name="wc_process_item_weight" maxlength="50" placeholder="Enter item weight"  type="number" step="0.01" value="<?=isset($wc_process_item_weight)?$wc_process_item_weight:"";?>">
              </div>
			  </div>
            </div>
			
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_item_pallet">Assign Pallet<sup></sup></label>
				<div class="input-group">
				<span class="input-group-addon">
					<input class="" disabled type="checkbox" name="chkattribute[]" value="wc_process_item_pallet" />
				</span>
                <select id="wc_process_item_pallet" name="wc_process_item_pallet" class="form-control" size="1">
                  <?php
                $pallet = new Pallet(0);
				$wc_process_item_pallet = PalletItems::getPalletIdByCode($wc_process_asset_code);
				echo $pallet->getOptions(isset($wc_process_item_pallet)?$wc_process_item_pallet:"0");
				?>
                </select>
              </div>
			  </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_age_date">Product Age Date<sup>*</sup></label>
                <div class="input-group date">
                	<span class="input-group-addon">
					<input class="" type="checkbox" name="chkattribute[]" value="wc_process_age_date" />
				</span>
                  <input type='text' class="form-control" id="wc_process_age_date" name="wc_process_age_date" placeholder="YYYY-MM-DD" value="<?=(isset($wc_process_age_date) && $wc_process_age_date!= '')?date("Y-m-d", strtotime($wc_process_age_date)):'';?>" />
                  <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="wc_process_age_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                  </span> </div>
                <script type="text/javascript">
						$('#wc_process_age_date').datepicker({
							format: "yyyy-mm-dd",
							autoclose:true,
							startDate  : '<?=date('Y-m-d')?>',
							daysOfWeekHighlighted: '0,6',
							todayHighlight:true
						});
				</script> 
              </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_item_location">Store location<sup></sup></label>
				<div class="input-group">
				<span class="input-group-addon">
					<input class="chkattribute chktext" type="checkbox" name="chkattribute[]" value="wc_process_item_location" />
				</span>
                <input class="form-control input_text_upper" id="wc_process_item_location" name="wc_process_item_location" maxlength="50" placeholder="Enter store location Ex. UK, UAE"  type="text" value="<?=isset($wc_process_item_location)?$wc_process_item_location:"";?>">
              </div>
			  </div>
            </div>
		     <?php
			 if($attributes){
				 $isRadio = false;
				 foreach($attributes as $item){
					 if($item['attribute_element_type'] == 'cboolean' && $isRadio == false){
						 $isRadio = true;
						 echo "</div><div class='row'>";
					 }
					echo getAttributeFieldForm($item);
				 }
			 }
			 ?>
             </div>
             
          <div class="row"> 			
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<input class="chkattribute chkboolean" type="checkbox" name="chkattribute[]" value="wc_process_item_stock">
						</span>
						<label for="wc_process_item_stock"><i class="fa fa-stop-circle-o fa-lg m-t-2"></i> &nbsp;Product in Stock ? </label>
						<label class="switch switch-icon switch-pill switch-success pull-right">
						<input class="switch-input" id="wc_process_item_stock" value="1" name="wc_process_item_stock" type="checkbox" <?=(isset($wc_process_item_stock) && $wc_process_item_stock == 1)?"checked":"";?>>
						<span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_is_on_way"><i class="fa fa-truck fa-fw"></i> &nbsp;Product is on the way</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" <?php echo (isset($wc_process_is_on_way) && $wc_process_is_on_way) ? "checked":"" ?> id="wc_process_is_on_way" value="1" name="wc_process_is_on_way" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
			 <?php if(count($attributes) == 0){?>
			 <div class="col-sm-12"><p class="alert alert-warning text-center"><a style="cursor:pointer"><?=$wciData['wci_name']?> don't have attributes</a></p></div>
			<?php
			 }
			 ?> 
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_under_technician_chk">Assign Technician</label>
                <br/>
                <label for="wc_process_under_technician_chk"><i class="fa fa-wrench fa-lg m-t-2"></i> &nbsp;Under Technician ?</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="wc_process_under_technician_chk" <?php if(isset($wc_process_under_technician) && $wc_process_under_technician > 0 ) echo "checked";?> value="1" name="wc_process_under_technician_chk" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8" id="wc_process_under_technician_box" style="display:<?php echo (isset($wc_process_under_technician) && $wc_process_under_technician > 0 ) ? "box":"none";?>;">
              <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="no_of_copy">Select Tehnician<sup>*</sup></label>
                    <select id="wc_process_under_technician" name="wc_process_under_technician" class="form-control" size="1">
                      <?php
					$Employee = new Employee(0);
					echo $Employee->getTechnicianOption(isset($wc_process_under_technician)?$wc_process_under_technician:"0");
				?>
                    </select>
                  </div>
                </div>
                
              </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_process_verified"><i class="fa fa-check fa-lg m-t-2"></i> &nbsp;Product Verified</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" <?php echo (isset($wc_process_verified) && $wc_process_verified) ? "checked":"" ?> id="wc_process_verified" value="1" name="wc_process_verified" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
          </div>
		<hr/>
		 <div class="row">
			  <div class="col-md-12" style="margin-top: 1px;">
				<div class="card">
					<div class="card-header card-default"><i class="fa fa-file-text"></i> Item Media Files						
					<div class="card-actions">         
					   <a data-id="<?php echo $wc_process_asset_code?>" data-image-type="0" data-media-section="<?=MediaSection::$collectionProductMedia?>" class="dropdown-item process_image_camera" href="#" title="Take image for <?php echo "$wciData[wci_name] #$wc_process_asset_code"?>"><i class="fa fa-camera text-danger"></i></a>

						</div>					
					</div>
					<div class="card-block"> 

					  <div class="row media_uploaded_image_box" id="media_uploaded_image_box_<?php echo $wc_process_asset_code;?>">
					  <?php 
					  $iCounter = 0;
					  $cm = new CollectionItemMedia(0);
					  $recorded_snapshot_list = $cm->getImageList($wc_process_asset_code);
					  if($recorded_snapshot_list):
					  foreach($recorded_snapshot_list as $itemCapt):
						  $iCounter++;
					  ?>
						  <div class="col-xs-4 col-sm-3 col-lg-2 thumb" data-record-id="<?php echo $itemCapt['image_id']; ?>" id="item-process-media-container-<?php echo $itemCapt['image_id']; ?>"><div class="card"><div class="card-header card-default">Image <?php echo $iCounter;?><div class="card-actions"><a href="javascript:removeCollectionProcesstMedia('<?php echo $itemCapt['image_id']; ?>', '<?php echo $wc_process_asset_code; ?>');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix">
							  <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="Image <?php echo $iCounter;?>" data-image="<?php echo $app->basePath($itemCapt['image_path']); ?>" data-target="#image-gallery">
                    						<img class="img-thumbnail" src="<?php echo getResizeImage($itemCapt['image_path'], 100); ?>" alt="Image <?php echo $iCounter;?>">
                </a>
							  <div class="font-weight-normal  text-center"><?php echo $itemCapt['image_label']; ?></div></div></div></div>

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
		<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-6">
          <div class="form-group">
            <label for="apply_on_all_items_of_this_collection"><i class="fa-lg fa <?php echo $wciData['wci_item_icon']?>"></i> Apply to next <b><input class="text-center" style="width:54px; display:none;" id="apply_data_count" name="apply_data_count" min="0" max="<?php echo count($allItems)-1;?>" placeholder="x" type="number" value="0" autocomplete="off"> <?=$wciData['wci_name']?></b></label>
            <label class="switch switch-icon switch-pill switch-success pull-right">			  
              <input class="switch-input" id="apply_on_all_items_of_this_collection" value="1" name="apply_on_all_items_of_this_collection" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
			
          </div>
        </div>
		
				
		<div class="col-xs-12 col-sm-12 col-md-6">
			<div class="form-group">
            <label><i class="fa-lg fa fa-download"></i> Download Barcode for <b><?=$wciData['wci_name']?></b></label>
            
              <a class="pull-right" target="_blank" href="<?=DOC::CASSETCODE($wc_process_wc_id, $wc_process_asset_code)?>"><i class="fa fa-2x fa-barcode"></i></a> &nbsp;
			
          </div>
			
          </div>
        </div>
		
		</div>
                
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
      <button type="submit" onClick="confirmMessage.Set('Are you sure to save Details...?', 'saveProcessItemDetails');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <?=$btnText?></button>
	  
	  
	  <?php if(isset($wc_process_asset_code) && $wc_process_asset_code):?>		
	  <div class="btn-group dropup">
		<button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action<span class="sr-only"></span></button>
		<div class="dropdown-menu dropdown-menu-right"> 
			<a class="dropdown-item redirect" href="<?php echo $app->basePath("updatewcprocessitem/$wc_process_asset_code"); ?>"><i class="fa fa-refresh fa-lg m-t-2"></i> Reload</a>
			<a class="dropdown-item" href="#"  data-toggle="modal" data-target="#appModal"  onClick="openChatLogForm('<?=$wc_process_id?>|P', '<?=$wc_process_asset_code?> Log Report')"><i class="fa fa-refresh fa-lg m-t-2"></i> Comments</a>
			<?php if(isAdmin()){?>
			<a class="dropdown-item redirect" href="<?php echo $app->basePath("processproductsalehistory/$wc_process_asset_code"); ?>"><i class="fa fa-shopping-cart fa-lg m-t-2"></i> Sale history</a> 
			<a class="dropdown-item" href="javascript:newWindow('<?php echo DOC::CASSETCODE($wc_process_wc_id, $wc_process_asset_code)?>')"><i class="fa fa-barcode fa-lg m-t-2"></i> Print Barcode</a>
			<?php 
			}?>
		</div>
	  </div>
	  <?php endif;?>
      
    </div>
    	<input type="hidden" id="action" name="action" value="<?=$action;?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="wc_process_asset_code" name="wc_process_asset_code" value="<?=isset($wc_process_asset_code)?$wc_process_asset_code:"0";?>"  />
		<input type="hidden" id="wc_process_item_id" name="wc_process_item_id" value="<?=isset($wc_process_item_id)?$wc_process_item_id:"0";?>"  />
	  </div>
      </form>
    </div>
</div>
<!--/col--> 

<!--/col-->
<script type="text/javascript">
$(document).ready(function(){
	$("#wc_process_under_technician_chk").on("change", function(){
		if($(this).is(":checked")){
			$("#wc_process_under_technician_box").show();
		}else{
			$("#wc_process_under_technician_box").hide();
			$("#wc_process_under_technician").val(0);
		}			
	}); 
	
	$("#apply_on_all_items_of_this_collection").on("change", function(){
		if($(this).is(":checked"))
			$("#apply_data_count").show();
		else
			$("#apply_data_count").hide();
	});
	$(".process_image_camera").on("click", function(){
	openMediaUploader($(this).attr('data-media-section'), $(this).attr('data-id'), "complaint_record_image", "Seelct Collection product's image source", null);	
	
	});
});
var allitems = <?php echo json_encode($allItems); ?>;
$(document).ready(function(){
	$(".chkhandle").on("click", function(){
		var ctlchk = $(this);
		$("."+$(this).attr('data-chk')).each(function(){
			$(this).prop('checked', ctlchk.is(':checked') ? true : false);
		});		
	});
});

function saveProcessItemDetails()
{		
	var data={
		action	:	$("#action").val()		
	};
	
	data = $.extend(data, $("#addwcprocessitem").serializeFormJSON());
	$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
			beforeSend: function(){
			message("process|Connecting...", 0);
			dissableSubmission();
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				//setTimeout(openNewItem, 2000, $("#wc_process_asset_code").val());
			}
			message(arr[1], 2000);
		}
	});
}

function openNewItem(item_code){
	if(allitems.indexOf(item_code) != -1){
		if(typeof (allitems[allitems.indexOf(item_code)+1]) != 'undefined'){
			var newCode = allitems[allitems.indexOf(item_code)+1];
			Redirect('updatewcprocessitem/' + newCode);
		}
		message('success|All <?=count($allItems)?> <?=$wciData['wci_name']?> updated');
	}
}

$("#fetchskudetail").on("click", function(){ 
	fetchskudetail();
})

function fetchskudetail()
{		
	var data={
		action	:	'collection/fetchprocessitemskudetail',
		wc_process_item_id : $("#wc_process_item_id").val(),
		wc_process_item_sku : $("#wc_process_item_sku").val()
	};
	if($("#wc_process_item_sku").val().trim() != ""){
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				$("#fetchskudetail").toggleClass('fa-task fa-spin fa-spinner');
				dissableSubmission();
			},		
			success:function(output){ 
				$("#fetchskudetail").toggleClass('fa-task fa-spin fa-spinner');
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$.each(arr[2], function(key, value){
						if(key == 'wc_process_item_stock'){
							$("#wc_process_item_stock").attr("checked", value ? true:false);
						}
						else
						$("#"+key).val(value);
						if(key == 'attribute'){
							$.each(value, function(attribute_id, attribute_value){								
								if($("#attribute_" + attribute_id).is('input:checkbox') || $("#attribute_" + attribute_id).is('input:radio')){									
									$('#attribute_'+attribute_id).prop('checked', attribute_value == "Yes" ? true : false);	
								}									
								else{													
									$("#attribute_" + attribute_id).val(attribute_value);									
								}
							});
						}
					});
				}
				message(arr[1]);
			}
		});
	}
	else
		message('warning|Sku must be filled');
}
</script> 

<script>
function uploadMediaFile(param)
{
	processUploadMediaFile(param.name, '_'+$("#keyid").val(), '<?=MediaSection::$collectionProductMedia?>');
	// if(confirm("Are you sure to upload this media file... ?"))
	// {
		// var files = _(field_name).files;
		// var formdata = new FormData(); 
		// if(files.length > 0){
			// for (var index = 0; index < files.length; index++) 
			// {
				// formdata.append('webcam[]', files[index]); 
			// }
			
			// formdata.append('wc_process_asset_code', $("#keyid").val()); 
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


function removeCollectionProcesstMedia(id, wc_process_asset_code)
{
	if(confirm("Are you sure to remove this media file... ?"))
	{
		var data={
			action		:	'collection/removecollectionitemmedia',
			record_id	: id			
		};
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Removing web order media file...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{					
					$("#item-process-media-container-"+id).remove();
					if($("#media_uploaded_image_box_"+wc_process_asset_code+" div").length ==0)
					$("#media_uploaded_image_box_"+wc_process_asset_code).html(EMPTY_IMAGE_BOX);
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
			wc_process_asset_code	: $("#keyid").val()
		};
		if(confirm("Are you sure to upload this media file... ?"))
		{
			$("#media_uploaded_image_box_"+$("#keyid").val()).append('<div class="col-xs-12 col-lg-3"  id="item-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading Snapshot<br/><br/>Please wait</center></div>');
			Webcam.upload( data_uri, parameter, sitePath+'saveimage.php', function(code, text) {
				var rc	=	JSON.parse(text);	
				$("#media_uploaded_image_box_"+rc['wc_process_asset_code']).append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['repair_image_id']+'" id="item-complaint-media-container-'+rc['repair_image_id']+'"><div class="card"><div class="card-header card-success">Image<div class="card-actions"><a href="javascript:removeCollectionProcesstMedia(\''+rc['repair_image_id']+'\', \''+rc['wc_process_asset_code']+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['repair_image_path']+'" /></div></div></div>');
	$("#item-media-processing").remove();	
	$("#media_uploaded_image_box_"+rc['wc_process_asset_code']+" .empty_image_box").remove();
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