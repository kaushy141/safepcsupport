<?php if(isset($wc_id) && $wc_id !=0) echo drawCollectionProcedure($wc_id);?>
<style>
.extrarow {
	background: #f44941;
	cursor: help;
}
.searchassets {
	background: #333 !important
}
</style>
<div class="row" id="collection_item_container">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i>
        <?=$formHeading;?>
        <div class="card-actions"><a id="data-collapse-abtn" class="btn-minimize collapsed" style="cursor:pointer;" data-toggle="collapse" data-target="#collection_item_id_boxb" aria-expanded="true"><i class="icon-arrow-up"></i></a></div>
      </div>
      <div class="card-block">
        <div class="row collapse in" aria-expanded="true" id="collection_item_id_boxb">
          <div class="col-md-12">
            <div class="row" style="margin-left:-5px; margin-right:-5px;">
              <?php 
				$WcItem = new WcItem();
				$collectionItemList = $WcItem->getItemsList((isset($wc_id) && $wc_id != 0)? $wc_id : 0);
				if($collectionItemList){
					foreach($collectionItemList as $item){
				?>
              <div class="col-xs-12 col-sm-6  col-md-4 col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="p-2 d-flex align-items-center"> <i class="<?php echo $item['wci_item_icon']?> p-2 fa-2x mr-2"></i>
                      <div>
                        <div class="text-value-sm text-primary"><?php echo $item['process'] ? "<i class='fa fa-check-circle text-success'></i> ":""?><?php echo $item['wci_name']?></div>
                        <div class="text-muted text-uppercase font-weight-bold text-center"><?php echo $item['wcr_item_qty']?></div>
                      </div>
                    </div>
                    <div class="px-2 pb-2"> <a data-wc-id = "<?php echo $wc_id?>" data-wc-item-id = "<?php echo $item['wci_id']?>" data-wc-item-name="<?php echo $item['wci_name']?>" class="data_item_btn data_item_btn_<?php echo $wc_id?>_<?php echo $item['wci_id']?> btn btn-outline-info btn-block">Process Items</a> </div>
                  </div>
                </div>
              </div>
              <?php		
					}	
				}
				?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
var datatable = null;

$(document).off('click', 'a.data_item_btn');
$(document).off('change', '.process_sr_no_file');
$(document).ready(function(){
	$(".process_sr_no_file").on('change', function(){
		confirmMessage.Set('Are you sure to upload serial number for '+$(".anchoruploadsrno").attr('data-wc-item-name')+'...?', 'uploadSerialNumberFile');
	});
	
	$("a.data_item_btn").on('click', function(){
		$(".anchoruploadsrno").attr('data-wc-item-name', $(this).attr('data-wc-item-name'));
		var wc_id = $(this).attr('data-wc-id');
		var item_id = $(this).attr('data-wc-item-id');
		
		var data={
			action	:	'collection/processcollectionitem',
			wc_id	: 	wc_id,
			item_id	: 	item_id
		};
	
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...",0);
				dissableSubmission();
				},		
				success:function(output){
					var arr	=	JSON.parse(output);	
					enableSubmission(output);
					if(arr[0] == 200){			
						$("#data_element_body_row").html(arr[2]);
						$("#process_form_container").slideDown(500);
						$("#data-collapse-abtn").trigger('click');
						$("#item_id").val(item_id);	
						$(".anchoruploadsrno").attr('data-id', item_id);
					}
					message(arr[1],1000);
				}
			});
	});
	
	$("a.loadmorerow").on('click', function(){
		var wc_id = $('#wc_id').val();
		var item_id = $("#item_id").val();
		
		var data={
			action	:	'collection/addnewprocessitem',
			wc_id	: 	wc_id,
			item_id	: 	item_id
		};

	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Connecting...",0);
			dissableSubmission();
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				enableSubmission(output);
				if(arr[0] == 200){			
					$("#data_element_body_row").append(arr[2]);
				}
				message(arr[1],2000);
			}
		});
	});
	
	$(document).on("click", "a.remove_item_row", function(){ 
		var wc_id = $('#wc_id').val();
		var item_id = $("#item_id").val();
		var process_code = $(this).attr('data-process-code');
		
		var data={
			action	:	'collection/removecollectionprocesscode',
			wc_id	: 	wc_id,
			item_id	: 	item_id,
			process_code:process_code
		};

	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Connecting...",0);
			dissableSubmission();
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				enableSubmission(output);
				if(arr[0] == 200){			
					$("#wc_item_row_tr_"+process_code).remove();
				}
				message(arr[1],2000);
			}
		});
	});	
	
	$(".anchoruploadsrno").on('click', function(){
		$(".process_sr_no_file").click();
	});
});

function uploadSerialNumberFile(){
	var params = {
					'wc_id' 	: $('#wc_id').val(),
					'item_id'	: $("#item_id").val()
				};
	filesUpload('process_sr_no_file', params);
}

function processCollection()
{
	var data={
		action				:	$("#action").val()
	};
	data = $.extend(data, $("#processcollection").serializeFormJSON());	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Connecting...",0);
			dissableSubmission();
		},		
		success:function(output){
			var arr	=	JSON.parse(output);	
			enableSubmission(output);
			if(arr[0] == 200){	
			}
			message(arr[1],2000);
		}
	})	
	return false;
}

</script>
<div class="row" id="process_form_container" style="display:none;">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i>
        <?=$formHeading;?>
      </div>
      <form id="processcollection" action="#" onsubmit="return false;" name="processcollection">
        <div class="card-block">
          <div class="row" id="collection_item_id_boxb">
			<div class="col-md-12">
			<p class="alert alert-warning">You can upload an .xslx or .csv file for all available items. Serial number will apply on all items. For best case make sure number of rows in file should be equal to no of items. in case of less rows in uploaded file system will apply serial number on those items which don't have serial number.
			<br/><b class="text-danger">Serial</b> Column name must be exist on file for serial numbers <sup class="text-danger">*</sup>
			<br/><b>Make</b> Column name may be exist on file for Make
			<br/><b>Model</b> Column name may be exist on file for Model
			<br/><b>Name</b> Column name may be exist on file for Name
			<br/><br/>
			<a href="#" data-wc-item-name="" data-id="0" class="btn btn-info text-white anchoruploadsrno">Import Serial numbers from file</a> <span class="file_uploader"></span>
			<input type="file" id="process_sr_no_file" class="process_sr_no_file" name="process_sr_no_file" style="display:none;">
			</p>
			</div>
            <div class="col-md-12">
              <table class="table table-responsive-sm table-bordered table-striped" id="data_item_table" style="width:100%;">
                <thead>
                  <tr>
                    <th>CODE</th>
                    <th class="text-primary">NAME*</th>
                    <th>Make*</th>
                    <th>Model*</th>
                    <th title="Important" class="text-danger">SR.NO*</th>
                    <th class="text-center" width="30px"><a class="loadmorerow" style="cursor:pointer;"><i class="icon-plus icons font-4xl d-block mt-4 text-success"></i></a></th>
                  </tr>
                <thead>
                <tbody id="data_element_body_row">
                </tbody>
              </table>
            </div>
            <!--/row--> 
            
          </div>
        </div>
        <div class="card">
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-12">
                <button type="button" onclick="confirmMessage.Set('Are you sure to save Collection process...?', 'processCollection');" class="btn btn-success mt-0"><i class="fa fa-check-circle"></i> Save Process</button>
              </div>
            </div>
          </div>
          <input type="hidden" id="action" name="action" value="collection/processcollectionv2"  />
          <input type="hidden" id="wc_id" name="wc_id" value="<?=isset($wc_id)?$wc_id:"0";?>"  />
          <input type="hidden" id="item_id" name="item_id" value=""  />
        </div>
      </form>
    </div>
  </div>
</div>
