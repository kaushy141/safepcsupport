/*****
* CONFIGURATION
*/
    //Main navigation
    $.navigation = $('nav > ul.nav');

  $.panelIconOpened = 'icon-arrow-up';
  $.panelIconClosed = 'icon-arrow-down';

  //Default colours
  $.brandPrimary =  '#20a8d8';
  $.brandSuccess =  '#4dbd74';
  $.brandInfo =     '#63c2de';
  $.brandWarning =  '#f8cb00';
  $.brandDanger =   '#f86c6b';

  $.grayDark =      '#2a2c36';
  $.gray =          '#55595c';
  $.grayLight =     '#818a91';
  $.grayLighter =   '#d1d4d7';
  $.grayLightest =  '#f8f9fa';

'use strict';

/****
* MAIN NAVIGATION
*/


var dragEventData = null;
var allowedFilesType = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png']; 

function humanFileSize(bytes, si=false) {
  let u, b=bytes, t= si ? 1000 : 1024;     
  ['', si?'k':'K', ...'MGTPEZY'].find(x=> (u=x, b/=t, b**2<1));
  return `${u ? (t*b).toFixed(1) : bytes} ${u}${!si && u ? 'i':''}B`;    
}
$(document).ready(function($){

  // Add class .active to current link
  $.navigation.find('a').each(function(){

    var cUrl = String(window.location).split('?')[0];

    if (cUrl.substr(cUrl.length - 1) == '#') {
      cUrl = cUrl.slice(0,-1);
    }

    if ($($(this))[0].href==cUrl) {
      $(this).addClass('active');

      $(this).parents('ul').add(this).each(function(){
        $(this).parent().addClass('open');
      });
    }
  });

  // Dropdown Menu
  $.navigation.on('click', 'a', function(e){

    if ($.ajaxLoad) {
      e.preventDefault();
    }

    if ($(this).hasClass('nav-dropdown-toggle')) {
      $(this).parent().toggleClass('open');
      resizeBroadcast();
    }

  });

  function resizeBroadcast() {

    var timesRun = 0;
    var interval = setInterval(function(){
      timesRun += 1;
      if(timesRun === 5){
        clearInterval(interval);
      }
      window.dispatchEvent(new Event('resize'));
    }, 62.5);
  }

  /* ---------- Main Menu Open/Close, Min/Full ---------- */
  $('.navbar-toggler').click(function(){

    if ($(this).hasClass('sidebar-toggler')) {
      $('body').toggleClass('sidebar-hidden');
      resizeBroadcast();
    }

    if ($(this).hasClass('aside-menu-toggler')) {
      $('body').toggleClass('aside-menu-hidden');
      resizeBroadcast();
    }

    if ($(this).hasClass('mobile-sidebar-toggler')) {
      $('body').toggleClass('sidebar-mobile-show');
      resizeBroadcast();
    }

  });

  $('.sidebar-close').click(function(){
    $('body').toggleClass('sidebar-opened').parent().toggleClass('sidebar-opened');
  });

  /* ---------- Disable moving to top ---------- */
  $('a[href="#"][data-top!=true]').click(function(e){
    e.preventDefault();
  });

});

/****
* CARDS ACTIONS
*/

$(document).on('click', '.card-actions a', function(e){
  //e.preventDefault();

  if ($(this).hasClass('btn-close')) {
    $(this).parent().parent().parent().fadeOut();
	if ($(this).hasClass('btn-remove'))
		$(this).parent().parent().parent().remove();
  } else if ($(this).hasClass('btn-minimize')) {
    var $target = $(this).parent().parent().next('.card-block');
    if (!$(this).hasClass('collapsed')) {
      $('i',$(this)).removeClass($.panelIconOpened).addClass($.panelIconClosed);
    } else {
      $('i',$(this)).removeClass($.panelIconClosed).addClass($.panelIconOpened);
    }

  } else if ($(this).hasClass('btn-setting')) {
    $('#myModal').modal('show');
  }

});

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function init(url) {

  /* ---------- Tooltip ---------- */
  $('[rel="tooltip"],[data-rel="tooltip"]').tooltip({"placement":"bottom",delay: { show: 400, hide: 200 }});

  /* ---------- Popover ---------- */
  $('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();

}

$(document).on("click", ".runfullfill", function(e){
	var formdata = $("#tablefilter").serializeFormJSON();		
	var data = {
		action	:	"weborder/runfulfillordercheck"
	}
	$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
			beforeSend: function(){
			message("process|Checking Order fulfill...", 0);
			dissableSubmission();
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);
			if(arr[0] == 200){
				table.clear().draw();
			}
			message(arr[1]);
		}
	});
});

$(document).on("change", ".productdetailstockswitch", function(e){
	e.preventDefault();
	var data={
		action		:	'system/updateproductstockbyswitch',
		id			: 	$(this).attr('data-id'),
		origin		: 	$(this).attr('data-origin'),
		value		: 	$(this).is(":checked") ? 1 : 0
	};
	$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
			beforeSend: function(){
			message("process|Updating Stock status...", 0);
			dissableSubmission();
			//$(this).append("<div class='updateproductstockbyswitchloading'>"+LOADING_HTML+"</div>");					
		},		
		success:function(output){ 
			//$(".updateproductstockbyswitchloading").remove();					
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			message(arr[1], 2000);					
		}
	});
});

var isCheckingProductDetails = false;
$(document).on("blur", ".metadataloader", function(e){
	e.preventDefault();
	if(!isCheckingProductDetails)
	{
		isCheckingProductDetails = true;
		if($(this).val() != ""){
			var data={
			action		:	'system/getproductdetailsearch',
			keyword		: 	$(this).val()
			};
			$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
					beforeSend: function(){
					message("process|Getting Details...", 0);
					dissableSubmission();
					setPopup(0, '<i class="icon-handbag"></i> Product Details');
					var bodyHtml = '<div class="col-md-12"><div class="row">';
					bodyHtml +='<div class="col-md-12">';
					bodyHtml +=LOADING_HTML;
					bodyHtml +='</div>';
					bodyHtml +='</div></div>';
					modal.Body(bodyHtml);
					modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
					modal.Show();
				},		
				success:function(output){ 
				isCheckingProductDetails = false;
					enableSubmission(output);
					var arr	=	JSON.parse(output);	
					if(arr[0]==200)
					{
						modal.Body(getOProductDetailBlock(arr[2]));							
					}
					else if(arr[0]==300){
						modal.Body('<center>No Product matched or Matched product not in Stock</center>');		
					}
					message("success|Product Check Completed", 1);					
				}
			});
		}
	}
});

function getOProductDetailBlock(itemArray){
	//console.log(item);	
	var html = '<table class="table table-bordered table-striped mb-0">';
	if(itemArray.length > 0)
	{
		for(var j=0; j<itemArray.length; j++){
			var item = itemArray[j];			
			html += '<tr><td><div class="card"><div class="card-header"> <i class="fa fa-align-justify"></i><a  data-dismiss="modal" class="redirect" href="'+item['url']+'">'+item['name']+'</a> </div><div class="card-body"><ul class="list-group mb-0"> <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center"><label>Origin -</label>  <span>'+item['origin']+' </span> </li> <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center"><label>Age -</label><span> '+item['age']+' <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center"><label>serial number -</label> <span>'+item['srno']+'</span></li><li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center"><label><i class="fa fa-shopping-bag fa-lg m-t-2"></i> &nbsp;In Stock</label> <span><div class="form-group"><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input productdetailstockswitch" data-origin="'+item['origin']+'" data-id="'+item['id']+'" value="1" name="stock_status" type="checkbox" '+(item['in_stock'] == "1" ? 'checked=""':'')+'><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div> </span></li></ul></div> </div></td></tr>';	
		}
	}
else
	html += '<tr><td>No Details available for this Product</td></tr>';
	html += '</table>';
	return html;
}
function getFulfillmentBlock(item){
	//console.log(item);
	var html = '<table class="table table-bordered">';
	html += '<tr><th colspan="4" class="card-header">Product</th></tr>';
	html += '<tr><td><img src="'+item['product']['wo_product_image']+'" height="60px"></td><td colspan="3"><a target="new" href="'+item['product']['wo_product_url']+'">'+item['product']['wo_product_name']+'</a></td></tr>';
	html += '<tr><td>SKU :</td><td>'+item['product']['wo_product_sku']+'</td><td>Qty :</td><td>'+item['product']['wo_product_quantity']+'</td></tr>';
	html += '<tr><th colspan="4">Fulfillment for this product</th></tr>';
	
	html += '<tr><td colspan="4" class="p-0 border-0">';
	html += '<table class="table table-bordered table-striped mb-0">';
	if(item['fulfill']){		
		for(var j=0; j<item['fulfill'].length; j++){
			var fulfill = item['fulfill'][j];			
			html += '<tr><td><b>SrNo:</b>'+fulfill['srno']+'<br/><b>From:</b>'+fulfill['origin']+'<br/><b>Age:</b>'+fulfill['age']+'</td><td class="hidden-xs hidden-md hidden-sm visible-lg" colspan="2">'+fulfill['name']+'</td><td class="text-right"><a class="btn btn-primary btn-sm redirect" href="'+fulfill['url']+'" target="new" onclick="modal.Hide()">View</a></td></tr>';
		}
	}
	else{
		html += '<tr><td colspan="4">No Fulfillment is available for this Product</td></tr>';
	}
	html += '</table>';
	html += '</td></tr>';	
	html += '</table>';
	return html;
}
function checkOrderFulfilment(web_order_id){
	var data = {
			action	:	"weborder/checkorderfulfillment",
			web_order_id  :   web_order_id
		}
		setPopup(web_order_id, '<i class="icon-handbag"></i> Order fulfillment Check');
		var bodyHtml = '<div class="col-md-12"><div class="row">';
		bodyHtml +='<div class="col-md-12">';
		bodyHtml +=LOADING_HTML;
		bodyHtml +='</div>';
		bodyHtml +='</div></div>';
		modal.Body(bodyHtml);
		modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				popmessage("connecting|Checking Order fulfillment Status...",0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);
				if(arr[0] == 200){
					var products	=	arr[2];
					if(products.length){
						var bodyHtml = '';
						for(var i=0; i<products.length;i++){
							bodyHtml += getFulfillmentBlock(products[i]);
						}
						modal.Body(bodyHtml);
					}
					
				}
				popmessage(arr[1]);
			}
		});
}

function markTracking(order_i){
	confirmMessage.Set('Are you sure to mark this order tracked...?', 'processMarkTracking', {'order_id':order_id})
}

function processMarkTracking(order){
	var data={
					action	:	'weborder/processmarktracking'						
				};
		data = $.extend(data, order);
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
					if($(".tracking_"+ order.order_id).length == 1)
					{
						$(".tracking_"+ order.order_id).remove();	
						$(".meta_detail_"+ order.order_id).append(arr[2]);
					}
				}
				message(arr[1]);
			}
		})	
}

function cancelInvoice(id){
	var data={
		action	:	"sales/cancelinvoice",
		id		:	id			
	};
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("process|Connecting...",0);
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);
			if(arr[0] == 200){
				if(typeof(datatable) != 'undefined')
				datatable.ajax.reload();				
			}
			message(arr[1],2000);
		}
	});	
}

function undoCancelInvoice(id){
	var data={
		action	:	"sales/undocancelinvoice",
		id		:	id			
	};
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("process|Connecting...",0);
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);
			if(arr[0] == 200){
				if(typeof(datatable) != 'undefined')
				datatable.ajax.reload();
			}
			message(arr[1],2000);
		}
	});	
}

var cametaStatus = false;

var cameras = new Array();
var device = new Array(); //create empty array to later insert available devices
navigator.mediaDevices.enumerateDevices() // get the available devices found in the machine
.then(function(devices) {
	var i = 0;
	devices.forEach(function(device) { 
		if(device.kind == "videoinput"){ //filter video devices only
			cameras[i]= device.deviceId;// save the camera id's in the camera array
			device[i] = device; 
				i++;
		}
	});
})
var cameraSwitch = 0;
function switchCamera(){
	if(cameraSwitch >= cameras.length)
	cameraSwitch = 0;
	startCamera(cameraSwitch);	
	cameraSwitch++;
	//alert(cameraSwitch + " == " + cameras.length);
}
function startCamera(cameraId)
{
	Webcam.set({
		width: Math.min($("#my_camera").width(),450),
		height: Math.min($("#my_camera").width(),450),
		image_format: 'jpeg',
		jpeg_quality: 90,
		constraints: cameraId == 0 ? {
					facingMode: {
								  exact: 'environment'
								  }
										
				}	: null
	});
	
	
	Webcam.attach( '#my_camera' );
	cametaStatus = true;
	message("success|Camera started", 500);
	$(".cam_controller_box").show();
	$("#btn_capture_camera").prop("disabled", false);
	$("#btn_switch_camera").prop("disabled", false);	
	$("#btn_close_camera").prop("disabled", false);
}

function closeCamera()
{
	Webcam.reset( '#my_camera' );
	cametaStatus = false;
	message("success|Camera Closed  :)", 500);
	$(".cam_controller_box").hide();
	$("#btn_capture_camera").prop("disabled", true);
	$("#btn_switch_camera").prop("disabled", true);	
	$("#btn_close_camera").prop("disabled", true);
}

function processUploadMediaFile(field_name, id, mediasection)
{
	var files = _(field_name).files;
	var formdata = new FormData(); 
	if(files.length > 0){
		for (var index = 0; index < files.length; index++) 
		{
			formdata.append('webcam[]', files[index]); 
		}
		
		formdata.append('field_name', field_name); 
		formdata.append('mediasection', mediasection); 
		formdata.append('id', $("#keyid").val()); 
		if($(".media_file_type:checked").length){
			formdata.append('mediasection_type', $(".media_file_type:checked").val()); 
		}
		is_interval_running = false;
		is_file_uploaded 	= false;
		$("#media_uploaded_image_box"+id).append('<div class="col-xs-12 col-lg-3"  id="item-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading image<br/><br/>Please wait</center></div>');

		var ajax = new XMLHttpRequest();	
		ajax.addEventListener("progress", progressMediaUpload, false); 
		ajax.addEventListener("load", completeMediaUpload, false); 
		ajax.open("POST", sitePath +"saveimage.php"); 
		ajax.send(formdata);
	}		
}

	
function progressMediaUpload(event){
	message("process|Uploading media file..." + Math.round((event.loaded / event.total) * 100)+"%", 0);
}


function openMediaUploader(mediasection, id, name, title, counter){
	setPopup(id, title);
	var bodyHtml = '<div class="col-xs-12">';
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-xs-6 text-center" style="border-right: 1px dashed #aaa;">';
	bodyHtml +='<label><i class="fa fa-folder-open-o" style="color:#FFC107; font-size:90px;"></i><input class="form-control" id="'+name+'" name="'+name+'" type="file" style="display:none;" onchange="confirmMessage.Set(\'Are you sure to upload this image\', \'uploadMediaFile\', {name : this.name, mediasection : \''+mediasection+'\'});" multiple><br/><span class="text-muted fa-2x">Local Storage</span></label>';
	bodyHtml +='</div>';
		
	bodyHtml +='<div class="col-xs-6 text-center">';
	bodyHtml +='<label><i class="fa fa-camera-retro" style="color:#FFC107; font-size:90px;"></i><button id="btn_start_camera" type="button" onClick="startCamera(0);" style="display:none;">Start Cam</button><br/><span class="text-muted fa-2x">Cam Device</span></label>';
	bodyHtml +='</div>';

	bodyHtml +='<div class="col-xs-12">';
	bodyHtml +='<div data-id="'+id+'" data-media-section="'+mediasection+'" data-media-counter="'+counter+'" data-name="'+name+'" class="paste_image_uploader text-center py-3 my-3" contenteditable style="border:1px dashed #aaa;"><span class="fa-2x text-muted">Drag & Drop Or Paste file here</span></div>';
	bodyHtml +='</div>';
		
	bodyHtml +='<div class="col-xs-12">';
	bodyHtml +='<div class="form-group my-2" style="background:#000;"><div id="my_camera" style="margin:0px auto;"></div></div>';
	bodyHtml +='<div class="cam_controller_box" style="display:none;"><div class="row"><div class="col-xs-4"><button id="btn_capture_camera" disabled="disabled" type="button" onClick="take_snapshot();" class="btn btn-block btn-outline-success">Capture</button></div><div class="col-xs-4"><button id="btn_switch_camera" disabled="disabled" type="button" onClick="switchCamera();" class="btn btn-block btn-outline-info">Rotate</button></div><div class="col-xs-4"><button id="btn_switch_camera" disabled="disabled" type="button" onClick="switchCamera();" class="btn btn-block btn-outline-danger">Close</button></div></div></div>';
	bodyHtml +='</div>';
			
	
	
	
	if(counter != null){
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><p><strong>Select Image uploding for</strong></p></div>';
	bodyHtml +='</div>';
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><div class="col-xs-4"><label class="switch switch-icon switch-pill switch-success"> <input class="switch-input media_file_type" value="0" id="media_file_type_0" name="media_file_type" type="radio" checked> <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label><br/><strong>Product</strong> </div> <div class="col-xs-4"> <label class="switch switch-icon switch-pill switch-info"> <input class="switch-input media_file_type" id="media_file_type_1" value="1" name="media_file_type" type="radio"> <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label><br/><strong>Returned</strong> </div> <div class="col-xs-4"> <label class="switch switch-icon switch-pill switch-warning"> <input class="switch-input media_file_type"  id="media_file_type_2" value="2" name="media_file_type" type="radio"> <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label><br/><strong>Replaced/repaired</strong> </div></div>';
	bodyHtml +='</div>';
	}

	
	bodyHtml +='</div>';
	
	bodyHtml +='</div>';
	modal.Body(bodyHtml);
	modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
	if(counter != null)
	$("#media_file_type_"+counter).attr("checked", true);
		
	$('.paste_image_uploader').pastableContenteditable();
	$('.paste_image_uploader').on('pasteImage', function(ev, data){ 
		data.blob.name = "imageFilename.png";
		pasteImageBlob = data.blob;
        var blobUrl = URL.createObjectURL(data.blob); 
		$(".paste_image_result_box").remove();		
        $('<div class="paste_image_result_box"><div class="col-xs-12 mb-2"><img data-name="'+name+'" class="img img-responsive" src="' + data.dataURL +'" ></div><div class="col-xs-6"><button type="button" onClick="confirmMessage.Set(\'Are you sure to upload this image\', \'processPasteMediaFile\');" class="btn btn-block btn-outline-success">Upload</button></div><div class="col-xs-6"><button type="button" class="pasteimagecancel btn btn-block btn-outline-danger">Cancel</button></div></div>').insertAfter(this);
      }).on('pasteImageError', function(ev, data){
        alert('Oops: ' + data.message);
        if(data.url){
          alert('But we got its url anyway:' + data.url)
        }
      });

	  var obj = $('.paste_image_uploader');
		obj.on('dragenter', function (e) 
		{
			e.stopPropagation();
			e.preventDefault();
			$(this).css('border', '1px solid #0B85A1');
		});
		obj.on('dragover', function (e) 
		{
			 e.stopPropagation();
			 e.preventDefault();
		});
		obj.on('drop', function (e) 
		{		 
			 $(this).css('border', '1px dotted #0B85A1');
			 e.preventDefault();			 
			 dragEventData = e.originalEvent.dataTransfer;
			 handleDargFile(e.originalEvent.dataTransfer.files);
		});
}
function handleDargFile(files)
{	
   $(".paste_image_result_box").remove();
   if(files.length > 0)
   { 
	   var html = '<div class="paste_image_result_box"><div class="col-xs-12 mb-2"><ul class="list-group">';
	   for (var i = 0; i < files.length; i++) 
	   {	if(allowedFilesType.indexOf(files[i]['type']) != -1)
			{
				var icon = files[i]['type'] == 'application/pdf' ? 'fa-file-pdf-o' : 'fa-image';
				html += '<li class="mb-1 list-group-item d-flex list-group-item-action justify-content-between align-items-center"><label><i class="fa '+icon+'"></i> '+files[i]['name']+'</label><span class="badge badge-primary badge-pill">'+humanFileSize(files[i]['size'])+'</span> </li>';
			}
	   }
	   html += '</ul></div>';
   }
   $(html+'<div class="col-xs-6"><button type="button" onClick="confirmMessage.Set(\'Are you sure to upload this image\', \'processDragMediaFile\');" class="btn btn-block btn-outline-success">Upload</button></div><div class="col-xs-6"><button type="button" class="pasteimagecancel btn btn-block btn-outline-danger">Cancel</button></div>'+'</div>').insertAfter($('.paste_image_uploader'));
}
$(document).on("change", ".media_file_type", function(){
	$(".paste_image_uploader").attr('data-media-counter', $(this).val());
});

$(document).on("click", ".pasteimagecancel", function(e){
	e.preventDefault();
	$(this).css('border', '1px dotted #aaa');
	$(".paste_image_result_box").remove();
});

function processPasteMediaFile()
{
	var id = $(".paste_image_uploader").attr('data-id');
	var field_name = $(".paste_image_uploader").attr('data-name');
	var mediasection = $(".paste_image_uploader").attr('data-media-section');
	var mediasection_type = $(".paste_image_uploader").attr('data-media-counter');
	
	var formdata = new FormData(); 
	formdata.append('webcam[]', pasteImageBlob, "imageFilename.png");
	formdata.append('id', $("#keyid").val()); 
	formdata.append('field_name', field_name); 
	formdata.append('mediasection', mediasection); 
	formdata.append('mediasection_type', mediasection_type); 
	is_interval_running = false;
	is_file_uploaded 	= false;
	$("#media_uploaded_image_box"+id).append('<div class="col-xs-12 col-lg-3"  id="item-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading image<br/><br/>Please wait</center></div>');

	var ajax = new XMLHttpRequest();	
	ajax.addEventListener("progress", progressMediaUpload, false); 
	ajax.addEventListener("load", completeMediaUpload, false); 
	ajax.open("POST", sitePath +"saveimage.php"); 
	ajax.send(formdata);
	$(".paste_image_result_box").remove();	
}

function processDragMediaFile(){
	var id = $(".paste_image_uploader").attr('data-id');
	var field_name = $(".paste_image_uploader").attr('data-name');
	var mediasection = $(".paste_image_uploader").attr('data-media-section');
	
	var files = dragEventData.files;
	var formdata = new FormData(); 
	var isData = false;
	if(files.length > 0){
		for (var index = 0; index < files.length; index++) 
		{
			if(allowedFilesType.indexOf(files[index]['type']) != -1){
				formdata.append('webcam[]', files[index]);
				isData = true;
			}
		}
		if(!isData){
			message('warning:No File available to save.');
			return;
		}
		
		formdata.append('field_name', field_name); 
		formdata.append('mediasection', mediasection); 
		formdata.append('id', $("#keyid").val()); 
		if($(".media_file_type:checked").length){
			formdata.append('mediasection_type', $(".media_file_type:checked").val()); 
		}
		is_interval_running = false;
		is_file_uploaded 	= false;
		$("#media_uploaded_image_box"+id).append('<div class="col-xs-12 col-lg-3"  id="item-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading image<br/><br/>Please wait</center></div>');

		var ajax = new XMLHttpRequest();	
		ajax.addEventListener("progress", progressMediaUpload, false); 
		ajax.addEventListener("load", completeMediaUpload, false); 
		ajax.open("POST", sitePath +"saveimage.php"); 
		ajax.send(formdata);
	}
	$(".paste_image_result_box").remove();	
	dragEventData = null;	
}


function isEmail(email) {
  var regex = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
  return regex.test(email);
}
