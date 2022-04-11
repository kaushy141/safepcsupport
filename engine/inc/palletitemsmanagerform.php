<style>
.pallet_container{ border: 1px dashed #999; min-height:400px;}
</style>
<!-- <script src="<?php echo $app->jsPath('fieldChooser.js')?>"></script>-->
<div class="row" id="collection_form_container">
  <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
    <div class="card">
    	<div class="card-header"><i class="fa fa-align-justify"></i> <strong> Pallet</strong></div>
		<div class="card-block">
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-6" id="pallet_capacity">
						</div>
						<div class="col-xs-6 text-right" id="pallet_avlspace">
						</div>
					</div>
				</div>
				<div class="col-xs-12">
				  <div class="form-group">
					<label for="collection_pallet_id">Select Pallet<sup>*</sup></label>
					<select id="collection_pallet_id" name="collection_pallet_id" class="form-control" size="1">
					<?php
					$pallet = new Pallet();
					$palletArray = $pallet->getOptionsArray();
					if($palletArray)
					{
						echo "<option value='0'> -- Select Pallet --</option>";
						foreach($palletArray as $_pallet){
							echo "<option value='$_pallet[pallet_id]'>$_pallet[pallet_name]</option>";
						}
					}
					?>
					</select>
				  </div>
				</div>
				<div class="col-xs-12">
					<div class="pallet_container p-1" id="pallet_container"></div>
				</div>
			</div>
		 </div>
	</div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="card">
    	<div class="card-header"><i class="fa fa-align-justify"></i> <strong> Items</strong></div>
          <div class="card-block">
            <div class="row">
				<div class="col-xs-12 col-sm-6 col-md-4">
				  <div class="form-group">
					<label for="collection_wc_code">Enter Collection Code<sup>*</sup></label>
					<input class="form-control input_text_upper" id="collection_wc_code" name="collection_wc_code" placeholder="Enter Collection Code" maxlength="8" type="text" value="">
				  </div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
				  <div class="form-group">
					<label for="collection_item_id">Select Collection Items<sup></sup></label>
					<select id="collection_item_id" name="collection_item_id" class="form-control" size="1"></select>
				  </div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
				  <div class="form-group">
					<label for="collection_item_id">Filter Results</label><br/>
					<a id="btnitemfilter" class="btn btn-outline-info">Search Items</a>
				  </div>
				</div>
            </div>
			<hr class="my-2"/>
			<div class="row">
				<div class="col-xs-12 action_container" id="action_container">
					<label><input class="pull-left ctrlcheckbox ctrlcheckbox1" type="checkbox" value="1" name="checkall">	&nbsp;Check All</label>	&nbsp; 
					<label><input class="pull-left ctrlcheckbox ctrlcheckbox0" type="checkbox" value="0" name="checkall">	&nbsp;Uncheck All</label>	&nbsp; 				
					<a id="moveitems" class="btn btn-info text-white">Add to Pallet</a><br/>
					<hr class="my-2"/>
				</div>
				
				<div class="col-xs-12 items_container" id="items_container"></div>								
			</div>
            <!--/row--> 
          </div>
    </div>
    
  </div>
</div>
<script type="text/javascript">
$(".contentbox").on("click", ".item_ticket_remove", function(e){
	e.preventDefault();
	var rremEle = $(this);
	$ele = $(this).parent();
	if($ele.parent().attr('id') == "pallet_container")	
	{
		var source = {
			pallet_id : $("#collection_pallet_id").val(),
			code : rremEle.attr('data-srno'),
			element : $ele
		};
		confirmRemoveItemsFromPallet(source);
	}
	else
		$ele.remove();
	
});

function confirmRemoveItemsFromPallet(source){
	confirmMessage.Set('Are you sure to remove this items from Pallet...?', 'removeItemsFromPallet', source);
}

function removeItemsFromPallet(source){
	var data={
			action		:	'collection/removeitemsfrompallet',
			pallet_id 	:   source.pallet_id,
			code		:	source.code 
	};
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Removing item "+source.code+" from Pallet...");
			dissableSubmission();
		},		
		success:function(output){
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				source.element.remove();
				$("#pallet_avlspace_count").text(parseInt($("#pallet_avlspace_count").text())+1);
			}
			message(arr[1],100);
		}
	});	
}

$("#moveitems").on("click", function(){
	var chkitems = $(".items_container").find(".chkprocesscode:checked");
	if( chkitems.length > 0)
	{
		confirmMessage.Set('Are you sure to add '+chkitems.length+' items on Pallet...?', 'moveselecteditems');
	}
});

function moveselecteditems(){
	var chkitems = $(".items_container").find(".chkprocesscode:checked");
	if( chkitems.length > 0)
	{
		var multiitems = [];
		chkitems.each(function(){
			multiitems.push($(this).val());
		});
		var data={
			action		:	'collection/addmultiitemstopallet',
			pallet_id 	:   $("#collection_pallet_id").val(),
			multiitems	:	multiitems 
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Analysing Pallet items...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					chkitems.each(function(){
						var item = $(this).parent('div.ticket_box').clone(true).find(".chkprocesscode").remove().end();
						$("#pallet_container").prepend(item);
						$(this).parent('div.ticket_box').remove();
						$("#pallet_avlspace_count").text(parseInt($("#pallet_avlspace_count").text())-1);
					});
				}
				message(arr[1],2000);
			}
		});		
	}
	else
		message('danger|No Item selected',1000);
}

$(".ctrlcheckbox").on('change', function(){
	var checked = $(this).is(":checked");
	if($(this).val() == 1){
		if(checked){
			$(".ctrlcheckbox0").prop('checked', false);
			$(".items_container").find(".chkprocesscode").each(function(){
				$(this).attr("checked", true);
			});
		}
	}
	else
	{
		if(checked){
			$(".ctrlcheckbox1").prop('checked', false);
			$(".items_container").find(".chkprocesscode").each(function(){
				$(this).attr("checked", false);
			});
		}
	}
});

$("#collection_pallet_id").on("change", function(){
	var data={
			action		:	'collection/getpalletitems',
			pallet_id 	: $(this).val()			
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Getting Pallet items...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					var itemsArray   = arr[2];
					for( var i=0; i<itemsArray.length; i++){
						var item = itemsArray[i];
						addItemsRow(item['wpi_process_asset_code'], $("#pallet_container"), 0);
					}
					$("#pallet_capacity").html("<label>Capacity : <span id=\"pallet_capacity_count\">"+arr[3]+"</span></label>");
					$("#pallet_avlspace").html("<label>Sapce Available : <span id=\"pallet_avlspace_count\">"+(arr[3] - arr[2].length)+"</span></label>");
				}
				message(arr[1],2000);
			}
		});
});

$("#collection_wc_code").on("change", function(){
	if($(this).val().length == 8)
	{
		var data={
				action		:	'collection/getcollectionitemsoption',
				wc_code 	: $(this).val()			
			};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Getting Collection items..");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					var itemsArray   = arr[2];
					var optionHtml = "<option value=''> -- Apply Item Filter -- </option>";
					for( var i=0; i<itemsArray.length; i++){
						var item = itemsArray[i];
						optionHtml += "<option value='"+item['wcr_item_id']+"'>"+item['wci_name']+"</option>";
					}
					$("#collection_item_id").html(optionHtml);
				}
				message(arr[1],500);
			}
		});
	}
});

$("#btnitemfilter").on("click", function(){
	var data={
			action		: 'collection/getcollectionprocessitemsnotinpallet',
			wc_code 	: $("#collection_wc_code").val(),
			item_id 	: $("#collection_item_id").val()				
		};
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Getting Collection items..");
			dissableSubmission();
		},		
		success:function(output){
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				$("#items_container").html('');
				var palletTickets = [];
				$("#pallet_container .ticket_box .ticket_text").each(function(){
					palletTickets.push($(this).text());
				});
				var itemsArray   = arr[2];
				var optionHtml = "<option value=''> -- Apply Item Filter -- </option>";
				for( var i=0; i<itemsArray.length; i++){
					if(palletTickets.indexOf(itemsArray[i]) == -1)
					addItemsRow(itemsArray[i], $("#items_container"), 1);					
				}
			}
			message(arr[1],500);
		}
	});
});



function addItemsRow(code, box, checkbox){
	box.append('<div class="ticket_box">'+(checkbox == 1 ? '<input class="pull-left chkprocesscode" type="checkbox" value="'+code+'" nam="processcode[]">':'')+'<div class="ticket_text pl-1">'+code+'</div><div title="Remove" class="item_ticket_remove ticket_close" data-sipd-id="'+code+'" data-srno="'+code+'">X</div><div class="clear"></div></div>');
}
</script> 
















