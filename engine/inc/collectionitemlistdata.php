<div class="row">
  <div class="col-lg-3">
    <div class="card">
      <form id="collectionroductfilter" name="collectionroductfilter">
        <div class="card-header"> <i class="fa fa-align-justify"></i> Filter 
		<a id="resetfilteration" href="#" class="pull-right">Reset</a></div>
        <div class="block-fluid table-sorting clearfix">
          <div class="row">
            <div class="col-xs-12" id="appliedfilter"></div>
          </div>
        </div>
		<?php		
		$technician = CollectionProcess::getTechnicianFilteration(isset($parameter1) ? $parameter1 : 0);
		$otherfilter = array();
		array_push($otherfilter, $technician);
		$wcItem = new WcItem(isset($parameter1) ? $parameter1 : 0);
		$record = $wcItem->getFilterAttributes();
		
		?>
        <div style="padding: 5px">
          <div class="block-fluid table-sorting clearfix">
            <div class="row">
              <div id="label_wc_process_item_id" class="col-md-12">
                <div class="form-group">
                  <select id="wc_process_item_id" name="wc_process_item_id" class="form-control" size="1">
					<option value=""> -- Select Item -- </option>
					<?php
					$wcItem = new WcItem();
					$records = $wcItem->getOptionList();
					if(count($records)){
						foreach($records as $item){
						echo "<option ".((isset($parameter1) && $parameter1 == $item['wci_id']) ? "selected" : "")." value=\"".$item['wci_id']."\">".htmlspecialchars($item['wci_name'])."</option>";
						}
					}
					?>
                  </select>
                </div>
              </div>
            </div>
          </div>
		  <div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$field = 'wc_process_item_stock';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Stock Status <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_wc_process_item_stock" class="col-md-12 col-form-label">
			
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="wc_process_item_stock" id="wc_process_item_stock_1" type="checkbox" value="1" data-label="In Stock">
				<label class="form-check-label" for="wc_process_item_stock_1">In Stock</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="wc_process_item_stock" id="wc_process_item_stock_0" type="checkbox" value="0" data-label="Out of Stock">
				<label class="form-check-label" for="wc_process_item_stock_0">Out of Stock</label>
				</div>
				
			</div>
			</div>
		  </div>
		  <div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$field = 'wc_process_item_location';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Item Location <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_wc_process_item_location" class="col-md-12 col-form-label">
			
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Store-UK" name="<?php echo $field?>" id="<?php echo $field.'_uk'?>" type="checkbox" value="UK">
				<label class="form-check-label" for="<?php echo $field.'_uk'?>"><img class="img-avatar" src="<?php echo $app->basePath('img/system/flag/flag_uk.png')?>" height="24px"/> UK</label>
				</div>
				
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Store-UAE" name="<?php echo $field?>" id="<?php echo $field.'_uae'?>" type="checkbox" value="UAE">
				<label class="form-check-label" for="<?php echo $field.'_uae'?>"><img class="img-avatar" src="<?php echo $app->basePath('img/system/flag/flag_uae.png')?>" height="24px"/> UAE</label>
				</div>
				
			</div>
			</div>
		  </div>
          <div id="dynamic_filter">
		  <?php
		  if(count($otherfilter))
		  {
			  foreach($otherfilter as $_filter)
			  {				 
				$html = '<div class="block-fluid table-sorting clearfix"><div class="row"><label class="col-md-12 col-form-label"><h5 class="filterheading">'.$_filter['title'].' <i data-id="'.$_filter['key'].'" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label><div id="label_'.$_filter['key'].'" class="col-md-12 col-form-label">';
				for($j=0; $j< count($_filter['items']); $j++)
				{
					$items = $_filter['items'][$j];
					$html .= '<div class="form-check checkbox filtercheckbox"><input class="form-check-input ml-0" data-label="'.$items['label'].'" name="'.$_filter['key'].'" id="web_order_assign_technician_'.$items['id'].'" type="checkbox" value="'.$items['id'].'"><label class="form-check-label" for="web_order_assign_technician_'.$items['id'].'"><img class="img-avatar" src="'.$items['user_image'].'" height="24px"> '.$items['value'].'</label></div>';
				}
				$html .= '</div></div></div>';
				echo $html;
			  }
		  }
		 ?>
		 
		 
		<?php 
		
		 if(count($record))
		 {
			for($i=0; $i< count($record); $i++)
			{
				$html = '';
				$p = $record[$i];
				$option = explode('|^|', $p['options']);
				if(count($option) > 0)
				{		
					$html .= '<div class="block-fluid table-sorting clearfix"><div class="row"><label class="col-md-12 col-form-label"><h5 class="filterheading">'.$p['attribute_name'].'<i data-id="attribute_'.$p['attribute_id'].'" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label><div id="label_attribute_'.$p['attribute_id'].'" class="col-md-12 col-form-label">';
					$j=0;	
					for($j; $j < count($option); $j++)
					{
						if($option[$j] != "")				
						$html .= '<div class="form-check checkbox filtercheckbox"><input class="form-check-input ml-0" data-label="'.$p['attribute_name'].' : '.$option[$j].'" name="attribute]['.$p['attribute_id'].']['.$j.']" id="attribute_'.$p['attribute_id'].'_'.$j.'" type="checkbox" value="'.$option[$j].'"><label class="form-check-label" for="attribute_'.$p['attribute_id'].'_'.$j.'">'.$option[$j].'</label></div>';		
					}
			
					$html .= '</div></div></div>';
					echo $html;
				}
			}
		 }			
		?> 
		 
		 
		 
		 
		 
		  </div>
		  <div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$field = 'wc_process_verified';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Data Filter <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Verified" name="wc_process_verified" id="wc_process_verified" type="checkbox" value="null|||is not">
				<label class="form-check-label" for="wc_process_verified"> Verified</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Unverified" name="wc_process_verified" id="wc_process_verified_2" type="checkbox" value="null|||is">
				<label class="form-check-label" for="wc_process_verified_2"> Unverified</label>
				</div>
			</div>
			</div>
		</div>
        </div>
      </form>
    </div>
  </div>
  <div class="col-lg-9">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Product List
        <div class="card-actions">
		<a id="printbarcode" class="card-header-action" title="Print Barcode" href="#"> <i class="fa fa-barcode"></i> </a>
		<a id="exporttocsv" class="card-header-action" title="Export to CSV" href="#"> <i class="fa fa-file-excel-o"></i> </a>		</div>
      </div>
      <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Code</th>
              <th>Item</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">Make</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">Model</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">Name</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">#SrNo</th>
			  <th class="hidden-xs hidden-md hidden-sm visible-lg">Stock</th>
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
<script type="text/javascript">
$(".form-check-input, #wc_process_item_id, .filtercloser, #printbarcode").off();

var data = {
								
		   };
	var coumnter = 0;
	var datatable;
	var formdata = {};
	if(app_filter_state == 1 && window.localStorage.getItem(window.location.href)){
		formdata = JSON.parse(window.localStorage.getItem(window.location.href));
		$('.form-check-input').each(function(){
			if(formdata.hasOwnProperty($(this).attr('name'))){
				var filterVal = formdata[$(this).attr('name')];
				//console.log(filterVal);
				if(filterVal.indexOf($(this).val()) !== -1){
					$(this).attr("checked", true);					
					$("#appliedfilter").append(getFilterLabel($(this)));
				}
			}
		})
	}
	
	var postdata = {
			action	:	"viewcollectionitemlist",
			filter  :   formdata
		}
$(document).ready(function() { 
	
	$("#wc_process_item_id").on("change", function(){
		Redirect('collectionitemlist/'+ $(this).find('option:selected').val());
		return;	
	});

	$('.form-check-input').on('change', function(){
		formdata = $("#collectionroductfilter").serializeFormJSON();		
		
		postdata = {
			action	:	"viewcollectionitemlist",
			filter  :   formdata
		}
		//console.log(postdata);
		
		if($(this).is(":checked")){
			if($(this).is("[type=radio]")){
				var name = $(this).attr('name');
				console.log(name);
				$('input[name="'+name+'"]').each(function(){
					$("#tag_"+$(this).attr('id')).remove();
				})
			}
			$("#appliedfilter").append(getFilterLabel($(this)));
		}
		else
			$("#tag_"+$(this).attr('id')).remove();
		if(typeof datatable != 'undefined'){
			datatable.clear().draw();
		}
		addfiltersearch();
	});
	
	$(".form-check-input-independent").on('change', function(){
		formdata = $("#collectionroductfilter").serializeFormJSON();		
		
		postdata = {
			action	:	"viewcollectionitemlist",
			filter  :   formdata
		}
		//console.log(postdata);
		addfiltersearch();
	});
	
	
	$('.filtercontroller').on('click', function(){
		var id = $(this).attr('data-id');
		if($(this).hasClass('fa-chevron-down')){
			$("#label_"+id).show();
		}
		else
			$("#label_"+id).hide();
		$(this).toggleClass('fa-chevron-down fa-chevron-up');
	})
	$("div.col-form-label").each(function(){
		$(this).hide();
	})
	$(document).on("click", ".filtercloser", function(e){
		var id = $(this).attr('data-id');
		$("#"+id).prop("checked", false);
		$("#tag_"+id).remove();
		$("#"+id).trigger("change");
		if(typeof datatable != 'undefined'){
			datatable.clear().draw();
		}
		addfiltersearch();
	})
} );





	
$(document).ready(function() {
	$("#printbarcode").on("click", function(e){
		var formdata = $("#collectionroductfilter").serializeFormJSON();		
		var data = {
			action	:	"collection/printcollectionproductbarcode",
			filter  :   formdata,
			searchKeyword  :   $('#tblSortable_filter label input[type="search"]').val()
		}
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);
				if(arr[0] == 200){
					window.open(sitePath+'report/collectionproductbarcode/'+arr[2], true);
				}
				message(arr[1]);
			}
		});
	});
	
	$("#exporttocsv").on("click", function(e){
		var formdata = $("#collectionroductfilter").serializeFormJSON();		
		var data = {
			action	:	"collection/exportcollectionproductcsv",
			filter  :   formdata,
			searchKeyword  :   $('#tblSortable_filter label input[type="search"]').val()
		}
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);
				if(arr[0] == 200){
					window.open(sitePath+'report/collectionprocessexport/'+arr[2], true);
				}
				message(arr[1]);
			}
		});
	});
});

datatable = $('#tblSortable').DataTable( {
	"processing": true,
	"serverSide": true,
	"bStateSave": true,
	"ajax":  {
		"url": "<?=$app->basePath("server_processing.php")?>",
		"type": "POST",
		"data": function ( d ) {
			$.extend(d, postdata);
		},
		"complete": function (data) {
            isDataTableRunning = false;
        }
	},
	'fnCreatedRow': function (nRow, aData, iDataIndex) {
        $(nRow).find('td').not(":last").attr('onclick', "Redirect('"+aData[8]+"')"); // or whatever you choose to set as the id
    },
	"order": [[ 0, 'desc' ]],
	columnDefs: [{ targets: [7], orderable: false },
				 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,3,4,5,6 ] }],
} );
	
function trigNewFilter(wci_id){
	var data = { 
					action : "collection/getitemfilterattributes", //system
					wci_id : wci_id 
			   };
	$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
			beforeSend: function(){
			//message("process|Connecting...", 0);
			dissableSubmission();
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);
			if(arr[0] == 200){
				applyImageDataFilter(arr[3]);
				loadFiltration(arr[2]);
				reApplyFilter();
				//activateFilterDropdown();
				$(".filtercontroller").bind("click", filterOpenCloseController);
			}
			else
			message(arr[1], 500);
		}
	});	
}

function applyImageDataFilter(data){
	var html = '';
	for(var i=0; i<data.length; i++)
	{
		var record = data[i];
		html += '<div class="block-fluid table-sorting clearfix"><div class="row"><label class="col-md-12 col-form-label"><h5 class="filterheading">'+record['title']+' <i data-id="'+record['key']+'" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label><div id="label_'+record['key']+'" class="col-md-12 col-form-label" style="display: none;">';
		for(var j=0; j<record['items'].length; j++)
		{
			var items = record['items'][j];
			html += '<div class="form-check checkbox filtercheckbox"><input class="form-check-input ml-0" data-label="'+items['label']+'" name="'+record['key']+'" id="web_order_assign_technician_'+items['id']+'" type="checkbox" value="'+items['id']+'"><label class="form-check-label" for="web_order_assign_technician_'+items['id']+'"><img class="img-avatar" src="'+items['user_image']+'" height="24px"> '+items['value']+'</label></div>';
		}
		html += '</div></div></div>';
	}
	$("#dynamic_filter").append(html);
}

function loadFiltration(arr){
	for(var i=0; i<arr.length; i++)
	{
		var html = '';
		var p = arr[i];
		var option = p['options'].split('|^|');
		if(option.length > 0)
		{		
			html += '<div class="block-fluid table-sorting clearfix"><div class="row"><label class="col-md-12 col-form-label"><h5 class="filterheading">'+p['attribute_name']+'<i data-id="attribute_'+p['attribute_id']+'" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label><div id="label_attribute_'+p['attribute_id']+'" class="col-md-12 col-form-label">';
			var j=0;	
			for(j; j<option.length; j++)
			{
				if(option[j] != "")				
				html += '<div class="form-check checkbox filtercheckbox"><input class="form-check-input ml-0" data-label="'+p['attribute_name']+' : '+option[j]+'" name="attribute]['+p['attribute_id']+']['+j+']" id="attribute_'+p['attribute_id']+'_'+j+'" type="checkbox" value="'+option[j]+'"><label class="form-check-label" for="attribute_'+p['attribute_id']+'_'+j+'">'+option[j]+'</label></div>';		
			}
	
			html += '</div></div></div>';
			$("#dynamic_filter").append(html);
		}
	}	
}
function verifyProcessProduct(product_code, verify){
		if(confirm('Are you sure to verify this record .. ?'))
		{
			var data = { 
							action			:	"collection/verifyprocessproduct", 
							product_code	: product_code ,
							verify 			: verify 
					   };
			$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
					beforeSend: function(){
					message("process|Connecting...", 0);
					dissableSubmission();
				},		
				success:function(output){ 
					enableSubmission(output);
					var arr	=	JSON.parse(output);
					if(arr[0] == 200){
						datatable.clear().draw();
					}
					message(arr[1]);
				}
			});
		}		
	}
	
	
</script> 
