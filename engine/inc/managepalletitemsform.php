<style type="text/css">
.ticket_box {
	padding: 8px 10px;
	padding-right: 0px;
	display: inline-block;
	margin: 2px;
	margin-right: 6px;
	font-size: 14px;
	color: #555;
	border: 1px dashed #bbb;
}

.ticket_box:hover {
	box-shadow: 1px 1px 3px #bbb;
	border-color: #F77A77;
}
.ticket_text {
	color:#646464;
	font-weight:600;
	float: left;
	display: inline-block;
	position: relative;
}
.ticket_text:hover {
	color: #000;
}
.ticket_remove {
	float: right;
	display: inline-block;
	position: relative;
	cursor: pointer;
	font-weight: bold;
	padding: 8px 8px;
	color: #ccc;
	margin-top: -8px;
	margin-bottom: -8px;
	background: #f1f1f1;
	margin-left: 1px;
}
.ticket_remove:hover {
	background-color: #FF3737;
	color: #FFF;
}
.ticket_edit{
	float: right;
	display: inline-block;
	position: relative;
	cursor: pointer;
	font-weight: bold;
	padding: 8px 8px;
	color: #ccc;
	margin-top: -8px;
	margin-bottom: -8px;
	background: #f1f1f1;
	margin-left: 1px;
}
.ticket_edit:hover {
	background-color: #2BAE70;
	color: #FFF;
} 
.drag{
	float: left;
	display: inline-block;
	position: relative;
	cursor: move;
	padding: 8px 8px;
	color: #ccc;
	margin-top: -8px;
	margin-bottom: -8px;
	background: #f1f1f1;
	margin-left: -8px;
	margin-right: 5px;
}
.drag:hover {
	background-color: #7A7A7A;
	color: #FFF;
} 
.ticket_box_list {
	padding: 8px 8px;
	padding-right: 0px;
	display: inline-block;
	margin: 0px 2px;
	margin-right: 6px;
	font-size: 14px;
	color: #555;
	border: 1px solid #ddd;
	width: 100%;
	background: #f3f3f3;
	min-height: 38px;
}
.ticket_box_list:hover {
	box-shadow: 1px 1px 3px #bbb;
	border-color: #bbb;
}
.process_item_container{ padding-left:0px;}
.data-text{ color:#AAA;}
</style>
<div class="row" id="collection_form_container">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-block">
        <div class="row" id="collection_item_id_boxb">
          <div class="col-md-12">
            <div class="row">
              <div class="col-sm-6 col-xs-12 col-md-4">
                <form name="addbarcode" id="addbarcode">
                  <div class="form-group">
                    <div class="input-group"> <span class="input-group-addon">
                      <label style="margin-bottom:0px;" for="item_bar_code"><i class="fa fa-barcode fa-lg m-t-2" style="font-size:3em;"></i></label>
                      </span>
                      <input class="form-control input_text_upper" id="item_bar_code" name="item_bar_code" maxlength="20" placeholder="write/scan bar code" type="text" value="" style="padding-top:28px; padding-bottom:29px; font-size:20px; text-align:center;">
                      <span class="input-group-addon">
                      <button class="btn btn-success" type="submit" id="add-pallet-items" style="margin-bottom:0px; cursor:pointer;" for="item_bar_code"><i class="fa fa-plus fa-lg" style="font-size:3em;"></i></button>
                      </span> </div>
                  </div>
                </form>
              </div>
              <div class="col-sm-6 col-xs-12 col-md-4">
                <button class="btn btn-lg btn-primary btnopenitemform" style="display:none; margin-top:5px;">Open items details form</button>
              </div>
              <div class="col-sm-6 col-xs-12 col-md-4"><a target="new" href="<?php echo DOC::PALLETITEMLIST("$pallet_id");?>" class="btn btn-lg btn-success" style="margin-top:5px;">Download Pallet Sheet</a></div>
            </div>
            <div class="row">
              <div class="col-md-12" id="bar_code_container"> </div>
            </div>
          </div>
        </div>
        <!--/row--> 
      </div>
    </div>
    <form id="managepalletitems" name="managepalletitems">
      <div class="card">
        <div class="card-header card-defualt"><i class="fa fa-newspaper-o"></i> <?php echo $formHeading;?></div>
        <div class="card-block">
          <div class="row">
            <div class="col-md-12">            
              <ul class="process_item_container" id="process_item_id_boxb">             
              </ul>
            </div>
          </div>
          <!--/row--> 
        </div>
      </div>
      <div class="card">
        <div class="card-footer">
          <div class="row">
            <div class="col-sm-12">
              <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg"></i> Reset</button>
              &nbsp;
              <button type="button" onClick="confirmMessage.Set('Are you sure to saev this pallet items...?', 'managepalletitems');" class="btn btn-success mt-0"><i class="fa fa-check-circle fa-lg"></i>
              <?=$btnText?>
              </button>
            </div>
          </div>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"managepalletitems";?>"  />
        <input type="hidden" id="pallet_id" name="pallet_id" value="<?=isset($pallet_id)?$pallet_id:"0";?>"  />
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">

$(document).ready(function(e) {
	
	$("#addbarcode").on("submit", function(){
		addBarcodeLabel();
		return false;
	});
    $("#add-pallet-items").on("click", function(){
		addBarcodeLabel();
		return false;
	});	
	
	$(".btnopenitemform").on("click", function(){
		openItemDetailsForm(SINGLE_ITEM_DATA);
	});
	$(document).on("click", ".ticket_remove", function(e){
		$ele = $(this).parent();
		$ele.hide(500, function(){
			$ele.remove();
			managebtnopenitemform();
		});
	});
	
	$(document).on("click", ".ticket_edit", function(e){
		var text = $(this).attr('data-id');
		openItemDetailsForm($("#LIST-"+text).val());
	});	
	

  $('li.ticket_box_list').arrangeable({dragSelector: '.drag'});
  $(function() {
          $('.draggable-element').arrangeable();
          $('li.ticket_box_list').arrangeable({dragSelector: '.drag'});
   });
});
var SINGLE_ITEM_DATA;
function addBarcodeLabel(){
	var barcode = $("#item_bar_code").val().trim().toUpperCase();
	if(barcode != ""){
		var valueArray = $('.processcode').map(function() {
			return this.value;
		}).get();
		if(valueArray.indexOf(barcode) == -1)
		{
			var data={
				action					:	'collection/loadcollectionitemdetail',
				wc_process_asset_code	: 	barcode
			};
			data = $.extend(data, $("#processcollection").serializeFormJSON());
			$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
					beforeSend: function(){
					message("process|Connecting...");
				},		
				success:function(output){
					var arr	=	JSON.parse(output);	
					if(arr[0] == 200){			
						$("#bar_code_container").prepend(getBarcodeLebel(barcode));
						$("#item_bar_code").val('')
						managebtnopenitemform();
						SINGLE_ITEM_DATA = JSON.stringify(arr[2]);
					}
					message(arr[1],500);
				}
			})				
			
		}
		else
		{
			message("warning|item allready in list");
		}
	}
	else
		$("#item_bar_code").focus();
}

function managebtnopenitemform(){
	var btn = $(".btnopenitemform");
	$('#bar_code_container .processcode').length == 0 ? btn.hide() : btn.show();
}

function getFormElement(ele){
	var boxClassData 	= ele.boxClass;
	var nameData 		= ele.name;
	var idData 			= ele.id;
	var typeData 		= ele.type;
	var classData 		= ele.class;
	var valueData 		= ele.value;
	var labelData 		= ele.label;
	var maxlengthData 	= ele.maxlength;
	
	typeData = typeData=='varchar' ? 'text':typeData;
	typeData = (typeData=='int' || typeData=='float') ? 'number':typeData;
	if(typeData == 'text' || typeData == 'number')
	{
		return '<div class="'+boxClassData+'"><div class="form-group"><label for="'+nameData+'">'+labelData+'<sup></sup></label><input class="form-control '+classData+'" id="'+idData+'" name="'+nameData+'" maxlength="'+maxlengthData+'" placeholder="Enter '+labelData+'" type="'+typeData+'" value="'+valueData+'"></div></div>';	
	}
	else if(typeData == 'radio')
	{
		var textCheckData = ele.textCheck;
		var textUncheckData = ele.textUncheck;
		return '<div class="'+boxClassData+'"><div class="form-group"><label for="'+nameData+'">'+labelData+'<sup></sup></label> &nbsp; <label class="switch switch-icon switch-pill switch-success"><input id="'+idData+'" class="'+classData+' switch-input" value="1"  name="'+nameData+'" type="checkbox"><span class="switch-label" data-on="'+textCheckData+'" data-off="'+textUncheckData+'"></span> <span class="switch-handle"></span></label></div></div>';
	}
}
/*var elementArray = <?php //echo json_encode(CollectionProcess::getProcessColumSchema());?>;*/

var elementArray = 
[
  {
    "name": "wc_process_item_make",
    "id": "wc_process_item_make",
    "class": "wc_process_item_make",
    "type": "varchar",
    "value": "",
    "label": "Item make",
    "maxlength": 50
  },
  {
    "name": "wc_process_item_model",
    "id": "wc_process_item_model",
    "class": "wc_process_item_model",
    "type": "varchar",
    "value": "",
    "label": "Item model",
    "maxlength": 50
  },
  {
    "name": "wc_process_item_name",
    "id": "wc_process_item_name",
    "class": "wc_process_item_name",
    "type": "varchar",
    "value": "",
    "label": "Item name",
    "maxlength": 200
  },
  {
    "name": "wc_process_item_sku",
    "id": "wc_process_item_sku",
    "class": "wc_process_item_sku",
    "type": "varchar",
    "value": "",
    "label": "Item sku",
    "maxlength": 50
  },
  {
    "name": "wc_process_item_sr_no",
    "id": "wc_process_item_sr_no",
    "class": "wc_process_item_sr_no input_text_upper",
    "type": "varchar",
    "value": "",
    "label": "Item sr no",
    "maxlength": 50
  },
  {
    "name": "wc_process_item_location",
    "id": "wc_process_item_location",
    "class": "wc_process_item_location",
    "type": "varchar",
    "value": "",
    "label": "Item location",
    "maxlength": 200
  },
  {
    "name": "wc_process_item_weight",
    "id": "wc_process_item_weight",
    "class": "wc_process_item_weight",
    "type": "float",
    "value": "",
    "label": "Item weight",
    "maxlength": 0
  },
  {
    "name": "wc_process_item_qty",
    "id": "wc_process_item_qty",
    "class": "wc_process_item_qty",
    "type": "int",
    "value": "",
    "label": "Item qty",
    "maxlength": 0
  },
  {
    "name": "wc_process_item_damage_status",
    "id": "wc_process_item_damage_status",
    "class": "wc_process_item_damage_status",
    "type": "radio",
    "value": "",
    "label": "Item damage status",
    "maxlength": 10,
	"textCheck" : "Pass",
	"textUncheck" : "Fail"
  },
  {
    "name": "wc_process_item_inext_phase",
    "id": "wc_process_item_inext_phase",
    "class": "wc_process_item_inext_phase",
    "type": "radio",
    "value": "",
    "label": "Item inext phase",
    "maxlength": 50,
	"textCheck" : "Yes",
	"textUncheck" : "No"
  },
  {
    "name": "wc_process_item_stock",
    "id": "wc_process_item_stock",
    "class": "wc_process_item_stock",
    "type": "radio",
    "value": "",
    "label": "Item Stock",
    "maxlength": 10,
	"textCheck" : "Yes",
	"textUncheck" : "No"
  }
];
function openItemDetailsForm(formData){
	//console.log("formData : " + formData);
	setPopup(0, "Add Item details form");	
	var bodyHtml = '<div class="col-md-12">';
	bodyHtml +='<div class="row">';
	
	for(var i=0; i<elementArray.length; i++)
	{	
		var ele  = elementArray[i];
		ele.boxClass = 'col-md-4';
		bodyHtml +=getFormElement(ele);
	}	
	
	bodyHtml +='</div>';
	
	bodyHtml +='</div>';
	modal.Body(bodyHtml);
	modal.Footer('<button type="reset" class="btn btn-default" >Reset</button><button type="button" id="popupsubmit" onclick="addItemsOnPallet(\''+(typeof formData !== 'undefined' ? (typeof JSON.parse(formData).palletCode !== 'undefined' ? JSON.parse(formData).palletCode:''):'')+'\');" class="btn btn-success" >Add Items</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
		
	if(typeof formData !== 'undefined')
	{
		var formDataObject = JSON.parse(formData);
		for(var i=0; i<elementArray.length; i++)
		{	
			var ele  = elementArray[i];
			if(ele.type == 'radio')
				$("#"+ele.id).attr("checked", formDataObject[ele.id] == 1 ? true : false);
			else
				$("#"+ele.id).val(formDataObject[ele.id]);
		}		
	}
}

function addItemsOnPallet(palletCode){
	var data = $.extend(data, $("#modalform").serializeFormJSON());
	
	if(palletCode == "")
	{	 
		var palletCodeArray = $('#bar_code_container .processcode').map(function() {
			return this.value;
		}).get();
		if(palletCodeArray.length > 0)
		{
			for(var i=0; i<palletCodeArray.length; i++)
			{
				var text = palletCodeArray[i];
				var palletRowHtml = getAppendInlineList(text, data);
				$("#process_item_id_boxb").append(palletRowHtml);
			}
		}	
		$('#bar_code_container').html('');
		managebtnopenitemform();
	}
	else
	{		
		$("#ticket_box_list_"+palletCode).replaceWith(getAppendInlineList(palletCode, data));
	}
	modal.Hide();
	managebtnopenitemform();
	$('li.ticket_box_list').arrangeable({dragSelector: '.drag'});
}

function getAppendInlineList(text, data){
	data.palletCode = text;
	return "<li class='ticket_box_list draggable-element' id='ticket_box_list_"+text+"'><div title='Drag to rearrange' class='drag'><i class='fa fa-arrows-alt'></i></div><div class='ticket_text'>"+text+"<span class='data-text'> : {name:"+data.wc_process_item_name +" Make:"+data.wc_process_item_make +" Model:"+data.wc_process_item_model +" Sr.No.:"+data.wc_process_item_sr_no +" }</span></div><div title='Remove item' class='ticket_remove'><i class='fa fa-close'></i></div><div title='Edit Details' class='ticket_edit' data-id='"+text+"'><i class='fa fa-edit'></i></div><div class='clear'></div><input type='hidden' class='listprocesscode' name='listprocesscode["+text+"]' id='LIST-"+text+"' value='"+JSON.stringify(data)+"' name='listprocesscode[]'><input type='hidden' class='processcode' value='"+text+"' name='processcode[]'></li>";
}

function getBarcodeLebel(text){
	return "<div class='ticket_box'><div class='ticket_text'>"+text+"</div><div title='Remove item' class='ticket_remove'>X</div><div class='clear'></div><input type='hidden' class='processcode' value='"+text+"' name='processcode[]'></div>";
}

function managepalletitems(){
	
	var data = $("#managepalletitems").serializeFormJSON();	
	console.log(data);
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Connecting...");
		},		
		success:function(output){
			var arr	=	JSON.parse(output);	
			if(arr[0] == 200){			
				
			}
			message(arr[1],3000);
		}
	})		
}


var storedPalletItems = [<?php $PalletItems = new PalletItems($pallet_id); echo json_encode($PalletItems->getItemsRecords());?>];

if(storedPalletItems.length)
{
	$.each(storedPalletItems, function( index, value ) {
		$.each(value, function (key, val) {
			$("#process_item_id_boxb").append(getAppendInlineList(val.wpi_process_asset_code, val));
		});  		
	});
}
else
alert(storedPalletItems.length);
</script> 