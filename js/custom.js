var backgroundColorData = ['#FF6384', '#36A2EB', '#FFCE56', '#E91E63', '#F44336', '#9C27B0', '#673AB7', '#3F51B5', '#00BCD4', '#009688', '#4CAF50', '#CDDC39', '#FFEB3B', '#FF9800', '#FF5722', '#795548', '#9E9E9E', '#607D8B'];

var retryingInterval;
var currentRequest;
var documentPageTitle;
var settimeoutdeclartion;
var original_file_data;
var call_back_upload_handler;
var is_interval_running = false;
var is_file_uploaded = false;
var dropdownDelay = 2;
var dropdowntimeoutCount = 0;
var dropdowntimeout = null;
var popsettimeoutdeclartion;
var enableChatGlobalCount = false;
var countComplaintLogGlobalCountIntervalTimeOut = 20700;
var countComplaintLogGlobalCountInterval = null;
var countComplaintLogGlobalCountFlag = false;
var selectedProductFullFillmentOpener = null;
var scheduleData;
var interval = null;
var max_idle_limit = (typeof(getCookie("TIMEOUT")) !== "undefined" && getCookie("TIMEOUT") != 0) ? parseInt(getCookie("TIMEOUT")) : 30 * 60;
var max_clock_run = max_idle_limit + (5 * 60);
var funCallBackConfirm = '';
var funCancelCallBack = null;
var parameterConfirm = null;
var original_file_data = "<i class=\"text-danger fa fa-file-pdf-o fa-lg\"></i>";
var processing_file_data = "Processing... <i class=\"fa fa-refresh fa-lg m-t-2 fa-spin\"></i>";
var call_back_file_upload_handler;
var is_interval_file_running = false;
var is_file_uploaded = false;
var logMediablob = null;
var speakTimeout = 0;
var ajaxRequestCount = 0;

$(document).ready(function(){
	$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
		if (options.type.toLowerCase() == 'post') {
			ajaxRequestCount++;
			if(ajaxRequestCount > 1)
			$(".server_potentail").html('<i class="fa fa-circle text-danger"></i>');
			else
			$(".server_potentail").html('<i class="fa fa-circle text-warning"></i>');
			options.data += '&app_url='+window.location.href;
			if (options.data.charAt(0) == '&') {
				options.data = options.data.substr(1);
			}
		}
	});
	$(document).ajaxComplete(function () {
	  ajaxRequestCount--;
	  ajaxRequestCount = Math.max(ajaxRequestCount, 0);
	  
	  if(ajaxRequestCount > 0)
		$(".server_potentail").html('<i class="fa fa-circle text-warning"></i>');
	  else
		$(".server_potentail").html('<i class="fa fa-circle text-success"></i>');
	});
	
	startShowingBottomNotification();
})

function waitforme(ms)  {
  return new Promise( resolve => { setTimeout(resolve, ms); });
}
async function startShowingBottomNotification(){
	if(bottomNotificationStack.length){
		for(var i=0; i<bottomNotificationStack.length; i++){
			showBottomNotification(bottomNotificationStack[i]);
			await waitforme(5000); 
		}		
		startShowingBottomNotification();
	}
}

function flipString(string, deliminator){
	return string.split(deliminator).reverse().join(deliminator);
}

function deskTopNotification(message, title){
	 if(window.Notification) {
		Notification.requestPermission(function(status) {
			console.log('Status: ', status); // show notification permission if permission granted then show otherwise message will not show
			var options = {
			  body: message, // body part of the notification
			  dir: 'ltr', // use for direction of message
			  image:'download.png' // use for show image
			};
			var n = new Notification(title, options);
		});
	}
	else {
		toastMessage('warning|Your browser doesn\'t support notifications.');
	}
}

function toastMessage(msg){
	var msg = msg.split("|");
    var icon = msg[0] == 'success' ? 'fa-check-circle-o' : (msg[0] == 'warning' ? 'fa-warning' : (msg[0] == 'danger' ? 'fa-shield' : (msg[0] == 'process' ? 'fa-refresh fa-spin' : 'fa-envelope-o')));
	if (msg[0] != 'success')
        msg[0] = 'error';
	toastr[msg[0]]("<i class='fa "+icon+"'></i> " + msg[1], msg[0].to);
}

function getColor(limit) {
    return backgroundColorData.slice(0, limit);
}
(function($) {
    $.fn.serializeFormJSON = function() {

        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
})(jQuery);

function validateHardwareCode(field) {
    if (!field.value.match(/^[A-Za-z]+$/)) {
        field.value = "";
        message("danger|Only A-Z Character allowed.");
        popmessage("warning|Only A-Z Character allowed.");
        return false;
    }
    return true;
}

function getRandomNumber(){
	var d = new Date();
	return d.getTime();
}

function validateFields(arr, popup) {
    var idsArray = arr.split(",");
    for (var i = 0; i < idsArray.length; i++) {
				
		var elementIds = idsArray[i].trim();
        if ($("." + elementIds).is('input[type="radio"]') || $("." + elementIds).is('input[type="checkbox"]')) {
			var field_name = ucword(elementIds.split("_").join(" "));         
           
            if ($("." + elementIds.trim() + ':checked').length == 0) {
                popup == undefined ? toastMessage("warning|" + field_name + " must be selected") : popmessage("warning|" + field_name + " must be selected", 0);
                return false;
            }
        } 
		else
		{			
			var field = $("#" + elementIds);
			var field_name = ucword(elementIds.split("_").join(" "));   
				field_name = typeof(field.attr('data-label')) != 'undefind' ? field.attr('data-label') : field_name;
			if(typeof(field_name) == 'undefined'){
				field_name = field.prev('label').text();
			}
            var elementValues = "";
            if (field.is("select")){
                elementValues = field.prop("selectedIndex");
			}
            else if (field.is("input") || field.is("textarea") || field.is("number")){
                elementValues = field.val().trim(); 
			}
            if (elementValues == "" || (elementValues == 0 && field.is("select"))) {
                field.focus();
                popup == undefined ? toastMessage("warning|" + field_name + " is required") : popmessage("warning|" + field_name + " is required", 0);
                return false;
            } else
                field.val(field.val().trim());
        }
    }
    return true;
}

function message(msg, isStable, showClose) {
    window.clearTimeout(settimeoutdeclartion); //cancel the previous timer.
    settimeoutdeclartion = null;
    var msg = msg.split("|");
    var icon = msg[0] == 'success' ? 'fa-check-circle-o' : (msg[0] == 'warning' ? 'fa-warning' : (msg[0] == 'danger' ? 'fa-shield' : (msg[0] == 'process' ? 'fa-refresh fa-spin' : 'fa-envelope-o')));

    alertMessage.Reset();

    if (msg[0] == 'warning')
        msg[0] = 'primary';
    if (msg[0] == 'process')
        msg[0] = 'default';

    if (msg[0] == "process")
        alertMessage.showFooter(false);
    else if (msg[0] == "danger" || showClose !== undefined) {
        alertMessage.showFooter(true);
    }

    $("#appModalMessage .modal-body").addClass("text-" + msg[0]);
    alertMessage.Body('<i class="fa ' + icon + ' fa-2x"></i> <br/><br/>' + msg[1]);
    alertMessage.Show();
    if (msg[0] != "danger") {
        if ((typeof isStable === 'undefined' || msg[0] == 'warning')) {
            if (msg[0] != 'process')
                settimeoutdeclartion = setTimeout(hideMessage, 2000);
        } else if (parseInt(isStable) == 0 || parseInt(isStable) === NaN) {} else {
            settimeoutdeclartion = setTimeout(hideMessage, isStable);
        }
    }
}

function hideMessage() {
    $(".msg").slideUp();
    alertMessage.Hide();
}

function popmessage(msg, isStable) {
    window.clearTimeout(popsettimeoutdeclartion); //cancel the previous timer.
    popsettimeoutdeclartion = null;
    $(".popmsg").show();
    var msg = msg.split("|");
    $(".popmsg div").removeClass("card-success card-warning card-danger ");
    $(".popmsg div").addClass("card-" + msg[0]);
    var icon = msg[0] == 'success' ? 'fa-check' : (msg[0] == 'warning' ? 'fa-warning' : (msg[0] == 'danger' ? 'fa-times-circle-o' : 'fa-refresh fa-spin'));
    $(".popmsg div").html('<i class="fa ' + icon + ' m-t-2"></i> ' + msg[1]);
    if (isStable === undefined) {
        popsettimeoutdeclartion = setTimeout(pophideMessage, 3000);
    } else if (parseInt(isStable) == 0 || parseInt(isStable) === NaN) {} else {
        popsettimeoutdeclartion = setTimeout(pophideMessage, isStable);
    }
}

function pophideMessage() {
    $(".popmsg").slideUp();
}

function ucword(str) {
    str = str.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function(replace_latter) {
        return replace_latter.toUpperCase();
    }); //Can use also /\b[a-z]/g
    return str; //First letter capital in each word
}

function _(id) {
	if(typeof(document.getElementById(id)) != 'undefined' && document.getElementById(id) != null){
		//console.log("Element ID found " + id);
		return document.getElementById(id);
	}
	else{
		if(typeof($("#"+id)) != 'undefined' && $("#"+id) != null){
			//console.log("Element ID found found Via jQuery " + id);
			return $("#"+id);
		}
		else{
			//console.log("Element ID not found in jQuery" + id);
			return null;
		}
	}
}

function uploadFile(field_name) {
    var file = _(field_name).files[0]; //alert(file.name+" | "+file.size+" | "+file.type); 
    var formdata = new FormData(); //alert(file.name+" | "+file.size+" | "+file.type); 
    var formdata = new FormData();
    formdata.append(field_name, file);
    formdata.append('field_handler', field_name);
    is_interval_running = false;
    is_file_uploaded = false;
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", sitePath + "fileupload.php");
    ajax.send(formdata);
}

function progressHandler(event) {
    var percent = (event.loaded / event.total) * 100;
    original_file_data = _("image_uploader").innerHTML;
    _("image_uploader").innerHTML = round(percent,2) + ' % ';

    if (is_file_uploaded == false && Math.round(percent) == 100) {
        is_file_uploaded = true;
        call_back_upload_handler_function();
    }
}

function call_back_upload_handler_function() {
    is_interval_running = true;
    _("image_uploader").innerHTML = "<i class=\"fa fa-refresh fa-lg m-t-2 fa-spin\"></i>";
}

function completeHandler(event) {
    var arr = JSON.parse(event.target.responseText);
    if (arr[1]) {
        message(arr[1]);
        if (arr[0] == 200)
            _("image_uploader").innerHTML = arr[2];
        else
            _("image_uploader").innerHTML = original_file_data;
    } else
        _("image_uploader").innerHTML = original_file_data;
}

function errorHandler(event) {
    _("image_uploader").innerHTML = "<i class=\"fa fa-camera fa-lg\"></i>";
    message("danger|File Upload error. Please try gaian.");
    _("image_uploader").innerHTML = original_file_data;
}

function abortHandler(event) {
    _("image_uploader").innerHTML = "<i class=\"fa fa-camera fa-lg\"></i>";
    message("danger|File Upload aborted by user. Please try gaian.");
    _("image_uploader").innerHTML = original_file_data;
}

function getDropdown(itm, dropdown, extra) {
    if (itm.value != '' && (dropdowntimeout == null || dropdowntimeoutCount < dropdownDelay)) {
        if ($("#json-datalist-" + itm.id).length > 0)
            $("#json-datalist-" + itm.id).remove()
        dropdowntimeoutCount = 0;
        dropdowntimeoutCountTimeout = setTimeout(dropdowntimeoutCount++, 1000);
        if ($("#json-datalist-" + itm.id).length == 0)
            $('<datalist id="json-datalist-' + itm.id + '"> </datalist>').insertAfter(itm);

        var dataList = document.getElementById("json-datalist-" + itm.id);

        var option = document.createElement('option');
        option.value = '';
        option.innerHTML = 'Loading...';
        dataList.appendChild(option);


        var data = {
            action: 'system/getdropdown',
            dropdown: dropdown,
            keyword: itm.value,
            extra: extra == undefined ? 0 : extra,
            condition: arguments[3] != undefined ? arguments[3] : ""
        }
        $("#" + itm.id).attr("autocomplete", "off");
        $("#json-datalist-" + itm.id).html("");
        dropdowntimeout = $.ajax({
            type: 'POST',
            data: data,
            url: sitePath + 'ajax.php',
            success: function(output) {
                dataList.innerHTML = "";
                var dropdowntimeout = null;
                clearTimeout(dropdowntimeoutCountTimeout);
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    var extraData = [];
                    var optionsValue = arr[1];

                    if (optionsValue.length > 0) {
                        $("#json-datalist-" + itm.id).show();
                        for (var i = 0; i < optionsValue.length; i++) {
                            var option = document.createElement('option');
                            option.value = optionsValue[i]['value'];
                            option.setAttribute('data-id', optionsValue[i]['data-id']);
                            option.innerHTML = optionsValue[i]['label'];
                            dataList.appendChild(option);
                            extra ? extraData[optionsValue[i]['data-id']] = optionsValue[i]['extra'] : '';
                        }
                    }
                    $("#json-datalist-" + itm.id + " option").click(function() {
                        itm.value = this.value;
                        $("#json-datalist-" + itm.id).hide();
                        $("#" + itm.id).focus();
                        if (extra)
                            callExtraModule(extraData[$(this).attr('data-id')], itm);
                    })
                } else
                    $("#json-datalist-" + itm.id).hide();
            }
        })
    }
}

function newWindow(path) {
    window.open(path, "_blank");
}

modal = {
    Title: function(html) {
        $("#appModal .modal-title").html(html);
    },
    Body: function(html) {
        $("#appModal .modal-body").html(html);
    },
    Footer: function(html) {
        $("#appModal .modal-footer").html(html);
    },
    History: function(html) {
        $("#appModal .modal-history").html(html);
    },
    AppendHistory: function(html) {
        $("#appModal .modal-history").append(html);
    },
    PrependHistory: function(html) {
        $("#appModal .modal-history").prepend(html);
    },
    Notice: function(html) {
        $("#appModal .modal-notice .noticebox").html(html);
        html == "" ? $("#appModal .modal-notice").hide() : $("#appModal .modal-notice").show();
    },
    Show: function() {
        $("#appModal").modal('show');
    },
    Hide: function() {
        $("#appModal").modal('hide');
    }
}

function setPopup(id, title) {
    modal.History("");
    modal.Footer("");
    modal.Notice("");
    $("#keyid").val(id);
    modal.Title(title);
}

function getComplaintLogGlobalCount() {
    if (countComplaintLogGlobalCountInterval != null && countComplaintLogGlobalCountFlag == false) {
        countComplaintChatAutoLoadFlag = true;
        var data = {
            action: "repair/getcomplaintlogglobalcount"
        }
        $.ajax({
            type: 'POST',
            data: data,
            url: sitePath + 'ajax.php',
            beforeSend: function() {

            },
            success: function(output) {
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    if (arr[2] > 0) {
                        $("#complaint_global_log_count_header").text(parseInt(arr[2]));
                        $("#complaint_global_log_count_dropdown").text(parseInt(arr[2]));
                    }
                }
            }

        })
    }
    countComplaintChatAutoLoadFlag = false;
}
if (enableChatGlobalCount)
    countComplaintLogGlobalCountInterval = setInterval(getComplaintLogGlobalCount, countComplaintLogGlobalCountIntervalTimeOut);
function resetPageLoading(){
	$(document).find("div.introtour").remove();
	$(document).find(".prevnextbox").each(function(){
		$(this).remove();
	});
	$("#system_crash_refresh .system_crash_error").text('');
}
function loadPage(path, isHistory) {
    var d = new Date();
	$(document).find(".popover").remove();
    var contentboxid = 'contentbox' + d.getTime();
    documentPageTitle = siteTitle;
    clearInterval(retryingInterval);
    var data = {
        path: path
    };
    var start_time = new Date().getTime();
    var stop_time;
    currentRequest = $.ajax({
        type: 'POST',
        data: data,
        timeout: AJAX_REQUEST_MAX_TIME,
        url: sitePath + 'page.php',
        beforeSend: function() {
            $("body").removeClass("sidebar-mobile-show");
            $(".contentbox").replaceWith("<div class='animated fadeIn contentbox' id='" + contentboxid + "'><div style=\"margin-top:"+($(window).height()/2 - 150)+"px;\">"+LOADING_HTML+"</div></div>").html(LOADING_HTML);
			resetPageLoading();
            if (sitePath + path == logoutUrl) {
                message("process|Shutting down... Please wait.", 0);
                document.title = "Shutting down...";
            } else {
                //message("process|Loading... Please wait.", 0);
                document.title = "Loading...";
            }
        },
        success: function(output) {
            MAX_AJAX_REQUEST_RETRING_ATTEMPT_COUNT = 0;
            clearInterval(retryingInterval);
            stop_time = new Date().getTime();
			resetPageLoading();
            try {
                var contextData = $.parseJSON(output);                
                $("#" + contentboxid).html(contextData['context']);
                if (contextData['title'] != null && contextData['title'].length > 0)
                    documentPageTitle = contextData['title'];
                document.title = documentPageTitle;
                checkSystemUpdate(contextData['version']);
            } catch (err) {
                $("#system_crash_refresh").show();
<<<<<<< HEAD
                $("#system_crash_refresh .system_crash_error").html(output.replace( /(<([^>]+)>)/ig, ''));
=======
                $("#system_crash_refresh .system_crash_error").text(output);
>>>>>>> 77a717f (Version 2)
            }
        },
        error: function(xmlhttprequest, textstatus, messagecontent) {
            MAX_AJAX_REQUEST_RETRING_ATTEMPT_COUNT++;
            clearInterval(retryingInterval);
            if (textstatus === "timeout") {
                currentRequest.abort();
				$("#" + contentboxid + " div").html('<div class="col-sm-12 col-md-12 text-center py-0"><img class="img" style="max-width:280px" src="' + sitePath + 'img/system-failed.png"><br/><span class="text-muted">Unable to load page content.<br/>Check Internet connection and try again !</span><br/><br/><a type=\'button\' href="javascript:Redirect(\'' + path + '\')" class=\'btn btn-outline-info\'>Reload</a></div>');
            }
        }
    });

    //window.history.pushState(path,documentPageTitle,sitePath+path);
}

function round(num, decimalPlaces = 0) {
    var p = Math.pow(10, decimalPlaces);
    var m = (num * p) * (1 + Number.EPSILON);
    return Math.round(m) / p;
}

function checkSystemUpdate(newVersion) {
    if (newVersion !== VERSION) {
        showVersionUpdate();
    }
}

function showVersionUpdate() {
    $(".version_update").html('<div class="version_box text-info" style="background-image: url(\'' + sitePath + 'img/upgrade.gif\');"><i class="fa fa-history"></i> Updates Available<br/> <a class="btn btn-outline-info btn-xs" href="' + sitePath + '">Update Now</a></div>');
}

function dissableSubmission() {
    $(".submission_handler_btn").each(function(index, element) {
        $(this).prop('disabled', true);
    });
}

function enableSubmission(output) {
    $("#system_crash_refresh .system_crash_error").text('');
    try {
        var json = $.parseJSON(output);
    } catch (err) {
        $("#system_crash_refresh").show();
        $("#system_crash_refresh .system_crash_error").text(output);
    }

    $(".submission_handler_btn").each(function(index, element) {
        $(this).prop('disabled', false);
    });
}

function requestFeedback(feedback_module_id, feedback_module_code, feedback_customer_id) {
    var data = {
        action: "feedback/checkrequestedfeedback",
        feedback_module_id: feedback_module_id,
        feedback_module_code: feedback_module_code,
        feedback_customer_id: feedback_customer_id
    };

    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        beforeSend: function() {
            modal.Title("Select Feedback request for..");
            modal.Body(LOADING_HTML);
            modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
            modal.Show();
            modal.History('');
        },
        success: function(output) { 
            enableSubmission(output);
            var arr = JSON.parse(output);
            if (arr[0] == 200) {

                var bodyHtml = '';
                if (arr[3].length > 0) {
                    for (var i = 0; i < arr[3].length; i++) {
                        var store = arr[3][i];
                        if (arr[2] == null || arr[2].indexOf(store['store_key']) == -1)
                            bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-4 mb-2"><a style="background-image:url(' + (sitePath + store['store_logo']) + '); background-size: contain; background-position: center; background-repeat: no-repeat;" data-id="' + store['store_key'] + '" type="button" class="p-2 btn-block btn-outline-success feedbackstorebtn">&nbsp;</a><a class="text-center btn-block mt-1">' + store['store_title'] + '</a></div>';
                    }
                }

                bodyHtml = bodyHtml == "" ? "<center>You have already requested feedback for all store </center>" : bodyHtml;
                modal.Body(bodyHtml);


                $(".feedbackstorebtn").click(function() {
                    $(this).addClass("btn-success");
                    callRequestFeedback(feedback_module_id, feedback_module_code, feedback_customer_id, $(this).attr("data-id"), $(this))
                });
            }
        }
    })
}

function callRequestFeedback(feedback_module_id, feedback_module_code, feedback_customer_id, feedback_store, buttonItem) {
    confirmMessage.Set('Are you sure to send feedback request...?', 'executeFeedbackRequest', [feedback_module_id, feedback_module_code, feedback_customer_id, feedback_store], function() {
        buttonItem.removeClass('btn-success')
    });
}

function executeFeedbackRequest(FeedbackParameter) {
    var data = {
        action: "feedback/requestfeedback",
        feedback_module_id: FeedbackParameter[0],
        feedback_module_code: FeedbackParameter[1],
        feedback_customer_id: FeedbackParameter[2],
        feedback_store: FeedbackParameter[3]
    };

    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        beforeSend: function() {
            popmessage("process|Connecting...");
            dissableSubmission();
        },
        success: function(output) {
            enableSubmission(output);
            var arr = JSON.parse(output);
            popmessage(arr[1]);
        }
    })
}


function appendResourceLogHistory(resource_id, resource_format, modal){
	var dataAjax = {
        action: 'employee/getloghistory',
        id: resource_id,
        complaint_format: resource_format
    };
    $.ajax({
        type: 'POST',
        data: dataAjax,
        url: sitePath + 'ajax.php',
        beforeSend: function() {
            modal.History("<center class=\"py-2 text-muted\"><i class=\"fa fa-circle-o-notch fa-spin\"></i> Loading previous log records...</center>");
        },
        success: function(output) {
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
				modal.History("");
				var timeLine = "";
                var logTextObj = arr[2];
                if (logTextObj.length > 0) {
                    for (var i = 0; i < logTextObj.length; i++) {
                        var logText = logTextObj[i];							
						if(logText.complaint_log_reply_parent != 0){
							$(".reply_comment_log_" + logText.complaint_log_reply_parent).append(getPopupMessageReplyBlock(logText));
						}
						else							
							modal.PrependHistory(getPopupMessageBlock(logText));
                    }
                }				
				else{
					modal.History("<center class=\"py-2 px-1 text-muted\">No prevous log history found. Be the first to log on this</center>");
				}
            }
            //popmessage(arr[1]);
        }
    });
}

function openChatLogForm(id, title) {
	logMediablob = null;
    setPopup(id, title);
	var idData = $("#keyid").val().split('|');
    var bodyHtml = `<div class="row">`;
    bodyHtml += `<div class="col-md-12">`;
	bodyHtml += `<div class="reply-box-identifier mb-1"></div>`;
    bodyHtml += `<div class="form-group">
					<label for="log_comment_text">Type your message<sup>*</sup></label>
					<textarea id="log_comment_text" data-label="Log comments" spellcheck="false" name="log_comment_text" rows="3" class="form-control log_comment_text mention mention-input-extra" placeholder="Write log message here... use @ to mention people"></textarea>
					<div class="iconholder">
						`+(userType == "E" ? `<div class="logmediabox chat_media_uploader_button"><img class="mediaicon" src="`+sitePath+`img/system/media.png"></div>` : '')+`
						<div class="logemojibox" data-placement="left" data-trigger="hover" data-toggle="popover-ajax" data-popover-action="emoji" data-popover-id="1" data-popover-state="saved">
							<img class="imojiicon" src="`+sitePath+`img/system/emoji/smile.png">
						</div>
					</div>
				</div>`;
	/*if(userType == "E")
	{
		bodyHtml += `<div class="form-group">
					<textarea id="log_comment_tag" name="log_comment_tag" spellcheck="false" rows="1" class="form-control mention mention-input-extra" placeholder="@Tag user here..."></textarea>
				</div>`;
	}*/
	if(userType == "E")
	{
		bodyHtml += `<div class="w-100"><div class="form-group logmediauploaderbox">
					<div contenteditable class="logmediauploader" data-id="`+idData[0]+`" data-media-section="logChatMediaUpload" data-media-counter="null" data-name="log_chat_media_upload_file">
						<span class="mediaplaceholder">
							<i class="fa fa-paperclip"></i> Drag&Drop, Paste file Or Click to Upload
						</span>
					</div>
					<input type="file" style="display:none;" class="logmediainputfile" name="logmediainputfile" onchange="handleLogDargFile(this.files)">
				</div></div>`;
	}
	
    if (userType == "E"){
        bodyHtml += `<div class="w-100"><div class="form-group mb-0">
						<input type="checkbox" value="1" name="mark_message_private" id="mark_message_private" />
						<label for="mark_message_private">Show this message to customer also.</label>
					</div>`;
		bodyHtml += `<div class="form-group">
						<input type="checkbox" value="1" name="notify_all_participants" id="notify_all_participants" /> 
						<label for="notify_all_participants">Notify all participants of this conversation including tagged.</label>
					</div></div>`;
	}
    bodyHtml += `</div>`;
    bodyHtml += `</div>`;
    modal.Body(bodyHtml);
    modal.Footer(`<button type="reset" class="btn btn-default">Reset</button>
				  <button type="button" id="popupsubmit" onclick="submitChatLogPopup();" class="btn btn-success" >Save</button>
				  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>`);
   
	//modal.Show();
    appendResourceLogHistory(idData[0], idData[1], modal);
	//loadGallery(true, 'a.logattachementfile');

	if(userType == 'E')
	{
		$(".logmediabox").on("click", function(){
			$(".logmediauploaderbox").slideToggle();
		});
		//----------------------------------------------------------------------------
		
		$('.logmediauploader').on("click", function(){
			$('.logmediainputfile').click();
		});
		
		$('.logmediauploader').pastableContenteditable();
		$('.logmediauploader').on('pasteImage', function(ev, data){ 
		
			data.blob.name = "PasteImage.png";
			//console.log(data.blob);
			logMediablob = data.blob;
			//console.log(logMediablob);
			var blobUrl = URL.createObjectURL(data.blob); 
			//handleLogDargFile(this.files)		
			$(".log_media_drop_list").remove();
			var html = '<div class="log_media_drop_list"><div class="mb-1"><ul class="list-group mb-0">';
			
			html += '<li class="mb-1 p-1 list-group-item d-flex list-group-item-action justify-content-between align-items-center"><label class="mb-0"><img data-name="'+data.blob.name+'" class="img img-responsive" src="' + data.dataURL +'" style="height:40px; display:inline-flex;" > &nbsp; Clipboard image</label><span class="badge badge-info badge-pill"></span> </li>';
			html += '</ul></div>';
			$(html).insertAfter($('.logmediauploader'));		
			
		  }).on('pasteImageError', function(ev, data){
			alert('Oops: ' + data.message);
			if(data.url){
			  alert('But we got its url anyway:' + data.url)
			}
		});

		var obj = $('.logmediauploader');
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
			 handleLogDargFile(e.originalEvent.dataTransfer.files);
		});
		//------------------------------------------------------------------------------------
		$('.mention').mentiony({
			applyInitialSize:   false,
			showMentionedItem: false,
			onDataRequest: function (mode, keyword, onDataRequestCompleteCallback) {
				if(mentionUserData == null){
					var dataAjax = {
						action: 'system/getmentionuser',
						keyword: keyword
					};
				   $.ajax({
							method: "POST",
							url: sitePath+"ajax.php",
							data:dataAjax,
							dataType: "json",
							success: function (response) {
								var data = response.data;
								mentionUserData = data;
								data = jQuery.grep(data, function( item ) {
									return item.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1;
								});
								onDataRequestCompleteCallback.call(this, data);
							}
						});
					onDataRequestCompleteCallback.call(this, data);
				}
				else{
					var data = mentionUserData;
					data = jQuery.grep(data, function( item ) {
						return item.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1;
					});
					onDataRequestCompleteCallback.call(this, data);
				}
			},
			timeOut: 0,
			debug: 1,
		});
	}
}
   

function handleLogDargFile(files)
{	
   $(".log_media_drop_list").remove();
   if(files.length > 0)
   { 
	   var html = '<div class="log_media_drop_list"><div class="mb-1"><ul class="list-group mb-0">';
	   for (var i = 0; i < 1; i++) 
	   {	if(allowedFilesType.indexOf(files[i]['type']) != -1)
			{
				var reader = new FileReader();
				reader.readAsArrayBuffer(files[i]);
				reader.fileType = files[i].type;;
				reader.fileName = files[i].name;
				reader.onload = function(e) {						
					logMediablob = new Blob([new Uint8Array(e.target.result)], {type: e.target.fileType});
					logMediablob.name = e.target.fileName;
				};
				
				var icon = files[i]['type'] == 'application/pdf' ? 'fa-file-pdf-o' : 'fa-image';
				html += '<li class="mb-1 p-1 list-group-item d-flex list-group-item-action justify-content-between align-items-center"><label class="mb-0"><i class="fa '+icon+'"></i> '+files[i]['name']+'</label><span class="badge badge-info badge-pill">'+humanFileSize(files[i]['size'])+'</span> </li>';
			}
	   }
	   html += '</ul></div>';
   }
   $(html).insertAfter($('.logmediauploader'));
}

function startUploadingDragFiles()
{
	var formdata = new FormData(); 	
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

function getPopupMessageBlock(logText) {
	var isReplyBox = logText.complaint_log_reply_parent != 0;
	
	return `<div id="chat_msg_id_` + logText.complaint_log_id + `" data-weight="`+logText.timestamp+`" class="chat-row-block callout callout-` + (logText.logger_type == 'E' ? "info" : "warning") + ` m-a-0 p-y-1">
				<div>
					<div class="avatar pull-xs-left l_r_b_l">
						<img src="` + logText.logger_image + `" class="img-avatar" alt="` + logText.logger_name + `">
					</div>
					<div class="l_r_b_r logtextbox">` + logText.complaint_log_text +`</div>
				</div>` + 				
				(logText.complaint_log_tag != null ? (`
				<div class="log_tag_user">
					<i class="icon icon-tag"></i> `+logText.complaint_log_tag+`
				</div>`) : ``) +
				`<div class="l_r_b_c px-1">
					<i class="fa ` + (logText.complaint_log_privacy == 0 ? `fa-lock text-success` : `fa-unlock text-danger`) + `">
					</i> &nbsp; 
					<small class="text-muted m-r-1">
							<i class="icon-clock"></i>&nbsp; ` + logText.log_time + `
					</small> &nbsp; 
					<small class="text-muted">
						<i class="icon-user"></i>&nbsp; ` + logText.logger_name + `
					</small>`+
					(logText.complaint_user_id == userId ? (`&nbsp; 
					<small class="text-muted updatecomment" data-complaint-log-id="` + logText.complaint_log_id + `" data-comment-logger-type="` + logText.logger_type + `">
						<i class="icon-pencil"></i> Edit
					</small>`):``)+
					(` &nbsp; 
					<small data-log-id="`+logText.complaint_log_id+`" data-replier="` + logText.logger_name + `"  class="text-muted btn_log_reply">
						<i class="fa fa-reply"></i> Reply
					</small>`)+
					(logText.complaint_log_media != null ? ` &nbsp; 
					<small class="text-muted log_attachment" data-media="`+logText.complaint_log_media+`">
						<a class="logattachementfile" target="_blank" href="`+(logText.complaint_log_media)+`" data-image-id="" data-toggleK="modal" data-titleK="Log Attachment" data-image="`+logText.complaint_log_media+`" data-target="#image-galleryK">
							<i class="fa fa-paperclip"></i> Attachement
						</a>
					</small>` : ``)+
					(logText.complaint_notified_all == 1 ? `&nbsp; <small class="text-muted"><i class="fa fa-fw fa-bell faa-ring animated text-warning"></i> Notified all</small>` : ``) +`
				</div>
				<div class="replier_box reply_comment_log_`+logText.complaint_log_id+`"></div>`+
				(!isReplyBox ? `<hr class="m-x-1 m-y-0">` : ``)+`
			</div>`;
}

function getPopupMessageReplyBlock(logText){
	return '<div class="replier_box_body">' + getPopupMessageBlock(logText) + '</div>';
}

$(document).on('click', '.sentpotosupplier', function(e){
		e.preventDefault();
		confirmMessage.Set('Are you sure to mail this purchase order to supplier..?', 'sentpotosupplier', $(this).attr('data-id'));
});

$(document).on('click', '.btn_log_reply', function(e){
		e.preventDefault();
		$(".reply-box-identifier").html('<div class="rbi d-flex align-items-center justify-content-between p-0"><div class="reply-content p-1 pl-0 justify-content-start"><i class="fa fa-reply"></i> <i>Replying To : <strong>'+$(this).attr('data-replier')+'</strong> on "'+$("#chat_msg_id_"+$(this).attr('data-log-id')).find('.logtextbox').text()+'" </i></div><div class="reply-closer p-1 justify-content-end text-right"><div><i class="fa fa-trash text-danger"></i></div></div></div><input class="complaint_log_reply_parent" type="hidden" name="complaint_log_reply_parent" value="'+$(this).attr('data-log-id')+'">');
		$(".reply-box-identifier").show();
		$("#log_comment_text").focus();
		$('#appModal').animate({
			scrollTop: 0
		},1000, 'easeOutExpo');
});

function closeLogReplierBody(){
	$('.reply-box-identifier').hide();
	$('.reply-box-identifier').html('');
}

$(document).on('click', '.reply-closer', function(e){
		e.preventDefault();
		closeLogReplierBody();
});

$(document).on('change', '.po_is_closed', function(e){		
		confirmMessage.Set('Are you sure to change close status ..?', 'changepoclosestatus', {po_id : $(this).attr('data-id'), po_status : $(this).is(":checked") ? 1 : 0});
});



function sentpotosupplier(po_id){
	var theme = $(this).attr("data-theme");
	var data={
				action		:	'po/sentpotosuplier',
				po_id		:	po_id
								
		};

	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);	
			if(arr[0] == 200){
				
			}				
			toastMessage(arr[1]);
		}
	});
}

function changepoclosestatus(poData){	
	var theme = $(this).attr("data-theme");
	var data={
				action		:	'po/updatepoclosestatus',
				po_id		:	poData.po_id,
				po_status	:	poData.po_status
								
		};

	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);	
			if(arr[0] == 200){
				
			}				
			toastMessage(arr[1]);
		}
	});
}


$(document).ready(function() {
	
	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "newestOnTop": true,
	  "progressBar": true,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "5000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	};

    $(document).on("click", ".updatecomment", function() {
        var data = {
            'complaint_log_id': $(this).attr('data-complaint-log-id'),
            'data_comment_logger_type': $(this).attr('data-comment-logger-type'),
            'data_comment_text': $("#chat_msg_id_" + $(this).attr('data-complaint-log-id')).find(".logtextbox").text()
        }

        if ($("#comment_updator_" + data.complaint_log_id).length == 0) {
            $("#chat_msg_id_" + $(this).attr('data-complaint-log-id')).find(".logtextbox").hide();
            $("#chat_msg_id_" + $(this).attr('data-complaint-log-id')).prepend("<div id=\"comment_updator_" + data.complaint_log_id + "\" class=\"w-100\"><form id=\"comment_update_form_" + data.complaint_log_id + "\"><div class=\"form-group\"><label for=\"poplogtext\">Update log comment<sup>*</sup></label><textarea id=\"log_comment_update_" + data.complaint_log_id + "\" name=\"log_comment_update\" rows=\"2\" class=\"form-control w-100\" placeholder=\"Enter updated text here...\">" + data.data_comment_text + "</textarea><input type=\"hidden\" name=\"complaint_log_id\" value=\"" + data.complaint_log_id + "\"></div><div id=\"modal-footer\" class=\"modal-footer\"><button type=\"button\" id=\"popupsubmit\" onclick=\"updateCommentLog(" + data.complaint_log_id + ");\" class=\"btn  btn-outline-success btn-sm\">Update</button><button type=\"button\" data-complaint-log-id=" + data.complaint_log_id + " class=\"btn  btn-outline-danger btn-sm updatelogcloser\">Close</button></div></form></div>");
        }
    });

    $(document).on("click", ".updatelogcloser", function() {
        $("#chat_msg_id_" + $(this).attr('data-complaint-log-id')).find(".logtextbox").show();
        $("#comment_updator_" + $(this).attr('data-complaint-log-id')).remove();
    })
	
	$(".logoffbtn").on("click", function(){
		var data={	
					action:	'system/signinlogoff'		
				};
		$.ajax({type:'POST', data:data, timeout: AJAX_REQUEST_MAX_TIME, url:sitePath+'ajax.php',	
			beforeSend: function(){
				message("process|Signing Off... Please wait.", 0);
			},	
			success:function(output){			
				var context = $.parseJSON(output);
				if(context[0] == 200)
				{
					logOffTimePassValue = 0;
					//setCookie('LOGOFF', true, parseInt(context[2]) + (86400 * 1));
					//setCookie('LOGOFFTIME', parseInt(context[2]), parseInt(context[2]) + (86400 * 1));
					$("#signinlogoffbox").show();
					signOffTimeInterval = setInterval(printLogOffTime, 1000);
					sendSocketMessage('Sign off <i class="fa fa-check-circle text-warning"></i>');
				}
				message(context[1] , 500);
			},
			error: function(xmlhttprequest, textstatus, messagecontent) {}
		});
		
	});	
	
	$(".systheme").on("click", function(){
		var theme = $(this).attr("data-theme");
		var data={
					action		:	'system/applysystemtheme',
					theme		:	theme
									
			};
	
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				//message("connecting|Connecting...",0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0] == 200){
					setTimeout(function(){
					$('link[href="'+arr[3]+'"]')[0].disabled=true;
					$('link[href="'+arr[3]+'"]').remove();
					$("head").append(arr[2]);
					}, 1000)
				}				
				toastMessage(arr[1]);
			}
		});
	});
	
    $("#signinlogoff").on("click", function(){
		
		var data={	
					action:	'system/signinlogon'		
				};
		$.ajax({type:'POST', data:data, timeout: AJAX_REQUEST_MAX_TIME, url:sitePath+'ajax.php',	
			beforeSend: function(){
				message("process|Signing in... Please wait.", 0);
			},	
			success:function(output){			
				var context = $.parseJSON(output);
				if(context[0] == 200)
				{
					logOffTimePassValue = 0;
					$("#signinlogoffbox").hide();
					clearInterval(signOffTimeInterval);
					signOffTimeInterval = null;
					sendSocketMessage('Sign in <i class="fa fa-check-circle text-success"></i>');
				}
				message(context[1] , 500);
			},
			error: function(xmlhttprequest, textstatus, messagecontent) {}
		});
		return false;		
	});
	
	if(getCookie("LOGOFF") == true)
	{
		$("#signinlogoffbox").show();		
		signOffTimeInterval = setInterval(printLogOffTime, 1500);
	}
	
	if(userType == "E")
	{
		if(LIVE_CHAT_ENABLE_STATUS == true){
			if($("#loadlivechatuserContainer").is(":visible")){
				loadlivechatuser();
				setInterval(loadlivechatuser, 3*60*1000);
			}
		}
		else{
			$(".live_user_container").html('<span class="text-danger">Live Chat temporarily closed.</span>');
		}
	}
	
	$(".radiodatalistfilterstate").on("click", function(e){
		var user_filter_state = $(this).attr('user-filter-state');
		var data={	
					action				:	'system/saveuserfilterstate',
					user_filter_state	: 	user_filter_state
				};
		$.ajax({type:'POST', data:data, timeout: AJAX_REQUEST_MAX_TIME, url:sitePath+'ajax.php',	
			beforeSend: function(){
				message("process|Updating filter saving state...", 0);
			},	
			success:function(output){			
				var context = $.parseJSON(output);
				if(context[0] == 200)
				{
					app_filter_state  = user_filter_state == 1 ? 0 : 1;
					$(".radiodatalistfilterstate").attr('user-filter-state', user_filter_state == 1 ? 0 : 1);
					$(".radiodatalistfilterstate i").toggleClass("text-success fa-toggle-on fa-toggle-off");
					$(".radiodatalistfilterstate .filter_state_text").text(user_filter_state == 1 ? "Enable filter state" : "Disable filter state");
				}
				message(context[1] , 500);
			},
			error: function(xmlhttprequest, textstatus, messagecontent) {}
		});
	});
	
});

function updateCommentLog(complaint_log_id) {
    if ($("#log_comment_update_" + complaint_log_id).val() != "") {
        var dataAjax = {
            action: 'system/updatecomplaintlog',
            complaint_log_text: $("#log_comment_update_" + complaint_log_id).val().trim(),
            complaint_log_id: complaint_log_id
        };
        $.ajax({
            type: 'POST',
            data: dataAjax,
            url: sitePath + 'ajax.php',
            beforeSend: function() {
                popmessage("connecting|Connecting...", 0);
            },
            success: function(output) {
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    $("#comment_updator_" + complaint_log_id).remove();
                    $("#chat_msg_id_" + complaint_log_id).replaceWith(getPopupMessageBlock(arr[2][0]));
                }
                popmessage(arr[1]);
            }
        })
    }
}

function resetMentiony(){
	$(".mentiony-content").html('');
}


function getMentionyItems(){
	return $(".mentiony-content").find('a.mentiony-link').map(function () { return { id : $(this).attr('data-item-id'),
	name : $(this).text()}; }).get();
}

function getToDoMentionyItems(){
	return $("div.todomentionbox").find('a.mentiony-link').map(function () { return { id : $(this).attr('data-item-id'),
	name : $(this).text()}; }).get();
}


function getMentionyText(){
   if( $(".mentiony-content div").length > 0){
       return $(".mentiony-content div").map(function() {
		return $(this).text();
	}).get().join('\n');
   }else{
       return $(".mentiony-content").text();
   }
}

function submitChatLogPopup() {
    var logtext = getMentionyText(); 
    if (1) {
        var idData = $("#keyid").val().split('|');
		var formdata = new FormData(); 
		if(logMediablob != null){
			formdata.append('logmedia', logMediablob, logMediablob.name);
			formdata.append('mediatype', logMediablob, logMediablob.type);
		}
		formdata.append('action', 'repair/insertcomplaintlog'); 
		formdata.append('logtext', logtext); 
		formdata.append('replier_parent', $(".complaint_log_reply_parent").length ? $(".complaint_log_reply_parent").val() : 0); 
		var mentionObject = getMentionyItems();
		//console.log(mentionObject);
		if(mentionObject != null)
		for ( var key in mentionObject ) {
			formdata.append('mentiony['+key+']', JSON.stringify(mentionObject[key]));
		}
		
		formdata.append('privacy', (userType == 'E' && $("#mark_message_private").is(":checked")) ? 1 : 0); 
		formdata.append('notifyall', (userType == 'E' && $("#notify_all_participants").is(":checked")) ? 1 : 0); 
		formdata.append('id', idData[0]); 
		formdata.append('complaint_format', idData[1]); 
        $("#popupsubmit").html("<i class='fa fa-spinner fa-spin'></i> Saving...");
		$("#popupsubmit").attr('disabled', true);
		var oReq = new XMLHttpRequest();
		oReq.addEventListener("load", transferCompleteChatLog);
		oReq.addEventListener("error", transferFailedChatLog);
		oReq.addEventListener("abort", transferCanceledChatLog);
		oReq.open("POST", sitePath +"fcm.php"); 
		oReq.send(formdata);
    }
	else{
		toastMessage('warning|Please enter comment text');
	}
}


function transferCompleteChatLog(oEvent){	
   $(".log_media_drop_list").remove();
   $("#popupsubmit").html("Save");
   $("#popupsubmit").attr('disabled', false);
   closeLogReplierBody();
   logMediablob = null;
	var arr = JSON.parse(oEvent.target.response);
	if (arr[0] == 200) {
		var dataAjax = arr[3][0];
		//console.log(dataAjax);
		sendSocketMessage('commented <i>"' + dataAjax['complaint_log_text'] + '"</i>');
		$("#log_comment_text").val('');
		var payload = JSON.parse(arr[2]);
		if (payload != null) {
			payload.data.complaint_log_privacy = dataAjax['complaint_log_privacy'] ? 1 : 0;
			appendMessagePopup(dataAjax);
			
		} else {
			arr[1] += " <span class='card-warning'>Reload Chat to see message.</span>";
			popmessage(arr[1]);
		}

	}
	if (arr[2] == false)
		arr[1] += " <span class='card-warning'>Notification Could not Send.</span>";
	popmessage(arr[1]);
}

function transferFailedChatLog(oEvent){
	logMediablob = null;	
	$("#popupsubmit").html("Save");
   $("#popupsubmit").attr('disabled', false);
	$(".log_media_drop_list").remove();
	$("#popupsubmit").html("Save");
	closeLogReplierBody();
}

function transferCanceledChatLog(oEvent){
	logMediablob = null;	
	$("#popupsubmit").html("Save");
   $("#popupsubmit").attr('disabled', false);
	$(".log_media_drop_list").remove();
	$("#popupsubmit").html("Save");
	closeLogReplierBody();
}

function calculaterandPay() {
    var basic_salary = $("#pay_slip_basic_salary").val();
    var total_sales = $("#pay_slip_total_sale").val();
    var sale_comosion = $("#pay_slip_commision").val();
    var grand_pay = parseInt(basic_salary) + round((total_sales * sale_comosion) / 100, 2);
    $("#pay_slip_grant_pay").val(grand_pay);
}

$("#modalform").submit(function(e) {
    e.preventDefault();

    var data = {
        action: "employee/saveemployeecontract"
    };

    data = $.extend(data, $("#modalform").serializeFormJSON());
    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        beforeSend: function() {
            message("process|Submitting employee mpr...");
            dissableSubmission();
        },
        success: function(output) {
            enableSubmission(output);
            var arr = JSON.parse(output);
            if (arr[0] == 200) {}
            message(arr[1], 2000);
            popmessage(arr[1], 2000);
        }
    })
});


// Add a message to the messages element.
function appendMessage(payload) {
    var logText = payload.data;
    var alignClass = logText.logger_type == 'E' ? "l" : "r";
    var alignClassRev = logText.logger_type == 'E' ? "r" : "l";
    var alignClassFull = logText.logger_type == 'E' ? "left" : "right";
    var alignClassFullRev = logText.logger_type == 'E' ? "right" : "left";
    html = '<div id="chat_msg_id_' + logText.complaint_log_id + '" class="cst_user_chat_box callout callout-' + (logText.logger_type == 'E' ? "info" : "warning") + ' m-a-0 p-y-1 callout-' + alignClassFull + '-position"><div class="avatar pull-xs-' + alignClassFull + ' l_r_b_' + alignClassRev + ' chat_img_content_box"><img src="' + logText.logger_image + '" class="img-avatar" alt="' + logText.logger_name + '"></div><div class="l_r_b_' + alignClass + ' chat_text_content_box">' + logText.complaint_log_text + '</div><div class="l_r_b_c text-' + alignClassFullRev + '"><small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; ' + logText.log_time + '</small>&nbsp; &nbsp;<small class="text-muted"><i class="icon-location-pin"></i>&nbsp; ' + logText.logger_name + '</small></div></div>';
    if ((logText.complaint_id + logText.complaint_format) == ($("#complaint_id").val() + $("#complaint_format").val())) {
        if ($("#chat_msg_id_" + logText.complaint_log_id).length == 0)
            $("#chat_user_message_box").append(html);
        $("#complaint_log_offset_count").val(logText.complaint_log_id);
    } else {

        var logCount = isNaN(parseInt($("#customer_unread_message_count_" + logText.logger_id).text())) ? 0 : parseInt($("#customer_unread_message_count_" + logText.logger_id).text());
        $("#customer_unread_message_count_" + logText.logger_id).text(logCount + 1)
    }
}

function appendMessagePopup(logText) {  
	if ($("#chat_msg_id_" + logText.complaint_log_id).length == 0){
		if(logText.complaint_log_reply_parent != 0){
			$(".reply_comment_log_" + logText.complaint_log_reply_parent).append(getPopupMessageReplyBlock(logText));
		}
		else{
			modal.PrependHistory(getPopupMessageBlock(logText));
		}
	}
    
}

function notifyMe() {
    // Let's check if the browser supports notifications
    if (!("Notification" in window)) {
        //console.log("This browser does not support desktop notification");
    }

    // Let's check whether notification permissions have alredy been granted
    else if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        var notification = new Notification("Notification Permission Granted!");
    }

    // Otherwise, we need to ask the user for permission
    else if (Notification.permission !== 'denied' || Notification.permission === "default") {
        Notification.requestPermission(function(permission) {
            // If the user accepts, let's create a notification
            if (permission === "granted") {
                var notification = new Notification("Notification Permission Granted!");
            }
        });
    }

    // At last, if the user has denied notifications, and you 
    // want to be respectful there is no need to bother them any more.
}



function sendUserTokentoServer(currentToken) {
    var data = {
        action: "employee/setuserfcmtoken",
        currentToken: currentToken
    }
    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        beforeSend: function() {

        },
        success: function(output) {
            var arr = JSON.parse(output);
            if (arr[0] != 200) {
                toastMessage(arr[1]);
            }
        }
    })
}


$("#appModalHtmlNotification").click(function() {
    setPopup(0, "Messages");
});

function addSchedule(action) {

    var formFields = "schedule_title, schedule_due_date, schedule_status";

    if (validateFields(formFields)) {
        var data = {
            action: action,
			mentiony : getToDoMentionyItems()
        };
        data = $.extend(data, $("#modalform").serializeFormJSON());

        $.ajax({
            type: 'POST',
            data: data,
            url: sitePath + 'ajax.php',
            beforeSend: function() {
                popmessage("process|Adding new Schedule...", 0);
                dissableSubmission();
            },
            success: function(output) {
                enableSubmission(output);
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    if (formFields == 'updateschedule')
                        $("#schedule_box_" + $("#keyid").val()).remove();
                    modal.Body("<p class='p-2 text-center'><i class='fa fa-check text-success'></i> Todo Reminder created successfully. Reference #"+ arr[2] +"</p>");
                    modal.Footer("");
                }
                popmessage(arr[1], 2000);
            }
        })
    }

}

function updateSchedule(schedule) {
    createSchedule();
	var footerHtml = '';
	if(schedule.schedule_user_id == userId && userType == 'E'){
		footerHtml += `<button type="reset" class="btn btn-default" >Reset</button><button type="button" id="popupsubmit" onclick="addSchedule(\'updateschedule\');" class="btn btn-success" >Update Schedule</button>`;
		setPopup(schedule.schedule_id, "Update Schedule");
	}
	else{
		setPopup(schedule.schedule_id, "ToDo for you");
		$(".removable_area").hide();
	}
		
    $("#schedule_title").val(schedule.schedule_title);
    $("#schedule_due_date").val(schedule.schedule_due_date);
    $("#schedule_status").val(schedule.schedule_status);
    $("#customer_email").val(schedule.customer_email);
    $(".schedule_scope").each(function(index, element) {
        if ($(this).val() == schedule.schedule_scope)
            $(this).attr("checked", true);
    });
	$(".todomentionbox").append(schedule.tags);
	$(".todomentionbox").append(schedule.comments);
	
	footerHtml += `<a class="btn btn-default comments_log_operner" data-id="`+(schedule.schedule_id + '|A')+`"  data-heading = "`+('ToDo Schedule '+schedule.schedule_id+' Log')+`"><i class="fa fa-comments"></i> Comments</a>`;
	footerHtml += `<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>`;
    modal.Footer(footerHtml);
    modal.Show();
}

function quickView(path, title) {
	$("#appModalQuick .modal-title").html(typeof(title) != 'undefined' ? title : "Quick View");    
    $("#appModalQuick .modal-body").html(LOADING_HTML);
    $("#appModalQuick .modal-footer").html('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
    
	var data = {
        path: path
    };
    $.ajax({
        type: 'POST',
        data: data,
        timeout: AJAX_REQUEST_MAX_TIME,
        url: sitePath + 'page.php',
        beforeSend: function() {
            resetPageLoading();
        },
        success: function(output) {
            stop_time = new Date().getTime();			
            try {
                var contextData = $.parseJSON(output);
                if (contextData['title'] != null && contextData['title'].length > 0)
                    $("#appModalQuick .modal-title").html(contextData['title']);
				$("#appModalQuick .modal-body").html(contextData['context']);
				$("#appModalQuick").on("hidden.bs.modal", function (e) {					
					$("#appModalQuick  .modal-body").html('');
				});
                checkSystemUpdate(contextData['version']);
            } catch (err) {
                $("#appModalQuick .modal-body").html("Unable to laod quick view");
            }
			resetPageLoading();
        },
        error: function(xmlhttprequest, textstatus, messagecontent) {
            MAX_AJAX_REQUEST_RETRING_ATTEMPT_COUNT++;
            clearInterval(retryingInterval);
            if (textstatus === "timeout") {
                currentRequest.abort();
                message("danger|<div class='card-action'>Request Timeout.<br/><a type='button' href=\"javascript:Redirect('" + path + "')\" class='btn btn-outline-success'>Reload</a></div>", 0);
                $("#" + contentboxid).html('<div class="col-sm-12 col-md-12 text-center pt-3 pb-3"><img class="img" src="' + sitePath + 'img/system-failed.png"><br/><span class="text-muted">System couldn\'t process request.<br/>Check Internet connection and Try again !</span></div>');
            }
        }
    });
}



$(document).on("click", "[data-quick-view='true']", function(e){
	e.preventDefault();
	var path  =$(this).attr('data-quick-url');
	if(path != ""){		
		quickView(path);
	}		
});	
$(document).on("click", ".formfield_entities_button", function(e){
	e.preventDefault();
	var ele = $(this).closest('div.entity_form_box').find('.field_entities_field'); 
	var entities_key  = ele.attr('data-key');
	var entities_values  = ele.val();
	var entities_php_allowed = $(this).closest('div.entity_form_box').find('.entities_php_allowed').is(":checked") ? 1 : 0;
	updateEntitiesValue(entities_key, entities_values, entities_php_allowed);	
});	
$(document).on("change", ".field_entities_button", function(e){
	e.preventDefault();
	var entities_key  = $(this).attr('data-key');
	var entities_values  = $(this).is(":checked") ? 1 : 0;
	var entities_php_allowed = $(this).closest('div.entity_form_box').find('.entities_php_allowed').is(":checked") ? 1 : 0;
	updateEntitiesValue(entities_key, entities_values, entities_php_allowed);	
});	

function updateEntitiesValue(entities_key, entities_values, entities_php_allowed){
	var dataAjax = {
		'action': 'system/saveentitiesvalue',
		'entities_key': entities_key,
		'entities_values': entities_values,
		'entities_php_allowed' : entities_php_allowed
	};
	$.ajax({
		method: "POST",
		url: sitePath+"ajax.php",
		data:dataAjax,
		dataType: "json",
		success: function (output){
			var arr = JSON.parse(output);
            toastMessage(arr[1]); 
		}
	});	
}
	
	$('.mention').mentiony({
	applyInitialSize:   false,
	showMentionedItem: false,
    onDataRequest: function (mode, keyword, onDataRequestCompleteCallback) {
		if(mentionUserData == null){
			var dataAjax = {
				action: 'system/getmentionuser',
				keyword: keyword
			};
		   $.ajax({
					method: "POST",
					url: sitePath+"ajax.php",
					data:dataAjax,
					dataType: "json",
					success: function (response) {
						var data = response.data;
						mentionUserData = data;
						data = jQuery.grep(data, function( item ) {
							return item.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1;
						});
						onDataRequestCompleteCallback.call(this, data);
					}
				});
			onDataRequestCompleteCallback.call(this, data);
		}
		else{
			var data = mentionUserData;
			data = jQuery.grep(data, function( item ) {
				return item.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1;
			});
			onDataRequestCompleteCallback.call(this, data);
		}
    },
    timeOut: 0,
    debug: 1,
});

function closeSchedueNotif(schedule_id) {
    $("#schedule_box_" + schedule_id).fadeOut(2000);
    var data = {
        action: 'schedule/markasread',
        schedule_id: schedule_id
    };
    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        success: function(output) {
            var arr = JSON.parse(output);
            if (arr[0] == 200)
                $("#schedule_box_" + schedule_id).remove();
            else
                $("#schedule_box_" + schedule_id).fadeIn(1000);
        }
    });
}

function fetchScheduleReminder(){
	var data = {
        action: 'schedule/schedulenotif'
    };
	$.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        success: function(output) {
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
                if (arr['data'] != null) {
                    scheduleData = arr['data'];
                    var notificationHtml = "";
                    $.each(scheduleData, function(index, notif) {
                        						
						notificationHtml += `<div class="card" id="schedule_box_` + notif.schedule_id + `">
                          <div class="card-body p-0">
							<div class="d-flex align-items-center justify-content-between p-1">
								<div class="px-0 justify-content-start">
								  <p class="text-value-sm text-muted text-xs mb-0">`+notif.schedule_title+`</p>
								</div>
							</div>							
							<div class="d-flex align-items-center justify-content-between p-1">
								<div class="px-0 justify-content-right">
								<span><i class="fa fa-circle" title="`+notif.schedule_status_name+`" style="color:` + notif.schedule_status_color + `;"></i></span> &nbsp; 
								<span type="button" class="text-muted text-xs"><img class="img-avator" src="`+notif.customer_image+`" width="14px"> `+notif.user_fname+`</span> &nbsp; 
								<a href="#" class="text-muted text-xs schedule_opener" data-id="`+notif.schedule_id+`">Open </a> &nbsp; 
								<a href="#" class="text-muted text-xs" onclick="closeSchedueNotif(` + notif.schedule_id + `)" >Close </a>
								</div>
							</div>
													
                          </div>
                        </div>`;
                    });
                    $("#schedule_notif_box_container").html(notificationHtml);
                }
				
				if(arr['customer_message'] != false){
					var isNewAppend = false;
					var DashboradNotification = arr['customer_message'];
					$("#appModalHtmlBody").html('');
					
					for(var i=0; i<DashboradNotification.length; i++)
					{
						if($("#customer_sent_message_"+DashboradNotification[i].complaint_log_id).length == 0)
						{
							isNewAppend = true;
							$("#appModalHtml").find(".modal-body").append('<div id="customer_sent_message_"'+DashboradNotification[i].complaint_log_id+' class="card card-block notif_box_content"><div class="text-justify font-italic">"'+DashboradNotification[i].complaint_log_text+'"</div><div class="text-right text-success"><b>'+DashboradNotification[i].logger_name+' </b> <img src="'+DashboradNotification[i].logger_image +'" class="img-avatar icon_customer_img" height="32px" /></div><div class="text-right notif_msg_time"><small class="text-muted"><i class="icon-clock"></i> '+DashboradNotification[i].complaint_log_time+'</small></div><div class="text-right"><a data-toggle="modal" data-target="#appModal" href="#" onclick="openChatLogForm(\''+DashboradNotification[i].complaint_id+'|'+DashboradNotification[i].complaint_format+'\', \'# Reply to '+DashboradNotification[i].logger_name+'\')"><i class="fa fa-reply"></i> Reply to '+DashboradNotification[i].logger_name+'</a> </div></div>');
						}
					}
					if(isNewAppend)
					$("#appModalHtmlNotification").click();
				}
            }
        }
    });
}

var scheduleFetchTimeOut = setInterval(function() {    
	fetchScheduleReminder();    
}, 2 * 60 * 1000);

var preTagFetched = false;
function scheduleTagFetch(){
    var data = {
        action: 'system/getmytag',
		user_tag_id : user_tag_id
    };
    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        success: function(output) {
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
                if (arr['data'] != null) {
                    var tagNotifHtml = "";
					var unreadCounter = $("#global_tag_count_header").text() == "" ? 0 : parseInt($("#global_tag_count_header").text());
					var tagCounter = 0;
                    $.each(arr['data'], function(index, tag) {
						if(tag['tag_readed'] == '0')
						tagCounter++;
						user_tag_id = tag['tag_id'];
                        $("#tabtagtimeline .notification_content").prepend(`
						<div class="card-tag`+(tag['tag_readed'] == '0' ? ' tag_unread':'')+`">
							<div class="card-body p-1">
								<div class="d-flex w-100 align-items-center justify-content-between">
									<div class="pl-0 justify-content-start"><img src="`+tag['user_image']+`"></div>
									<div class="px-1 justify-content-center">`+tag['tag_text']+(tag['tag_log_modal'] != null ? ' <a href="#" class="comments_log_operner" ' + tag['tag_log_modal'] + '><i class="fa fa-comments text-success"></i> Log</a>' : '')+`</div>
								</div>
								<div class="d-flex w-100 align-items-center justify-content-between">
									<div class="px-1 pb-1 small"><a href="#" class="`+(tag['tag_readed'] == 1 ? "tagreaded":"marktagread")+` text-muted" data-id="`+tag['tag_id']+`"><i class="fa fa-check-circle"></i> `+(tag['tag_readed'] == 1 ? "Seen":"Mark seen")+`</a><span class="pull-right">`+tag['tag_time']+`</span></div>
								</div>
							</div>
						</div>`);
						if(preTagFetched == true)
						bottomNotificationStack.push(`<img src="`+tag['user_image']+`" height="24px"> `+tag['tag_text']);
                    }); 
					if(tagCounter > 0){
						$("#global_tag_count_header").text(unreadCounter + tagCounter);
						$("a[aria-controls='tabtagtimeline']").find('sup').html(tagCounter ? tagCounter : '');
					}
                }
            }
			preTagFetched = true;
        }
    });
}
if(countUserTagAutoLoad == true){
	var tagFetchTimeout = setInterval(scheduleTagFetch, 60*1050);	
}


/*-----------------------------Payment Reminder Notification---------------------------*/
function schedulePaymentReminderFetch(){
    var data = {
        action: 'system/getmypaymentreminder',
    };
    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        success: function(output) {
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
				var existingPaymentReminder = $("#tabpaymentreminder .notification_content .card-tag").length;
				$("#tabpaymentreminder .notification_content").html('');
                if (arr['data'] != null) {
                    var tagNotifHtml = "";
					var unreadCounter = $("#global_tag_count_header").text() == "" ? 0 : parseInt($("#global_tag_count_header").text());
					var paymentReminder = 0;
                    $.each(arr['data'], function(index, tag) {
						paymentReminder++;
                        $("#tabpaymentreminder .notification_content").prepend(`
						<div class="card-tag`+(tag[' tag_readed'] == '0' ? ' tag_unread':'')+`">
							<div class="card-body p-1">
								<div class="d-flex align-items-center justify-content-between">
									<div class="pl-0 justify-content-start"><img src="`+tag['user_image']+`"></div>
									<div class="px-1 justify-content-center">`+tag['reminder_text']+(tag['tag_log_modal'] != null ? ' <a href="#" ' + tag['tag_log_modal'] + '><i class="fa fa-comments text-success"></i> View log</a>' : '')+` or <a class="redurect" href="`+tag['payment_reminder_link']+`">View Reminder</a> </div>
								</div>
								<div class="d-flex align-items-center justify-content-between">
									<div class="px-1 pb-1 small"><span class="pull-right">`+tag['tag_time']+`</span></div>
								</div>
							</div>
						</div>`);
                    }); 
					$("a[aria-controls='tabpaymentreminder']").find('sup').html(paymentReminder ? paymentReminder : '');
					if(paymentReminder - existingPaymentReminder)
					$("#global_tag_count_header").text(unreadCounter + paymentReminder - existingPaymentReminder);
					
                }
            }
        }
    });
}
schedulePaymentReminderFetch();
var schedulePaymentReminderFetchTimeout = setInterval(schedulePaymentReminderFetch, 60*1200);	

/*--------------------------------------------------------*/

$(document).on("click", ".markallreadanchor", function(e){
	e.preventDefault();
	var ele = $(this);
	var data = {
        action: 'system/'+ele.attr('data-action'),
		user_tag_id : ele.attr('data-id')
    };
    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        success: function(output) {
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
				ele.closest('div [aria-action-content="'+ele.attr('data-action')+'"].notification_content .tag_unread').each(function(el){
					var aele = el.find('.marktagread');
					aele.removeClass('marktagread');
					aele.addClass('tagreaded');
					aele.html(`<i class="fa fa-check-circle"></i> Seen`);
				});
				
				var unreadCounter = $("#global_tag_count_header").text() == "" ? 0 : parseInt($("#global_tag_count_header").text()) -arr[2];
				$("#global_tag_count_header").text(unreadCounter > 0 ? unreadCounter : '');
				$("a[aria-action-tag='"+ele.attr('data-action')+"']").find('sup').html('');
               toastMessage(arr[1]); 
            }
        }
    })	
})

$(document).on("click", ".marktagread", function(e){
	e.preventDefault();
	var ele = $(this);
	var data = {
        action: 'system/marktagreaded',
		user_tag_id : ele.attr('data-id')
    };
    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        success: function(output) {
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
				ele.closest('.card-tag').removeClass('tag_unread');
				ele.removeClass('marktagread');
				ele.addClass('tagreaded');
				ele.html(`<i class="fa fa-check-circle"></i> Seen`);
				var unreadCounter = $("#global_tag_count_header").text() == "" ? 0 : parseInt($("#global_tag_count_header").text()) -1;
				$("#global_tag_count_header").text(unreadCounter > 0 ? unreadCounter : '');
               toastMessage(arr[1]); 
            }
        }
    })	
})


window.onpopstate = function(e) {
    if (e.state) {
        Redirect(e.state, false);
    }
};



$("html").on('click', "a.redirect", function(event) {
    var hash = this.hash;
    Redirect(this.href.substring(sitePath.length, this.href.length), true);
    //event.stopImmediatePropagation();
    event.preventDefault();
});

$("html").on('change', ".wci_item_aged", function(event) {
    $(this).val($(this).is(":checked") ? 1 : 0);
});

$("html").on('keypress', "input[type=number]", function(e) { 
    return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        e.which == 46 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
})

var prevLiveUserData = null;

function loadlivechatuser() {
    var dataAjax = {
        'action': 'employee/loadlivechatuser'
    };
    $(".live_user_container").html('<li class="live_user"> <i class="fa fa-spinner fa-spin"></i> Connecting ... </li>');
    $.ajax({
        type: 'POST',
        data: dataAjax,
        url: sitePath + 'ajax.php',
        beforeSend: function() {},
        success: function(output) {
            $(".live_user_container").html('');
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
                notifyOnlineOfflineUsers(arr[2]);
                for (var i = 0; i < arr[2].length; i++) {
                    var record = arr[2][i];
                    $(".live_user_container").append('<li class="live_user px-1" data-user-id="' + record.user_id + '" data-user-image="' + record.user_image + '" data-user-name="' + record.user_name + '"><i class="fa fa-circle text-' + (record.user_is_live == 1 ? "success" : "danger") + '"></i> &nbsp;' + record.user_name + (record.msg_count > 0 ? "<span class=\"badge badge-success pull-right\">" + record.msg_count + "</span>" : "") + '</li>');
                    if ($("#user_live_burst_" + record.user_id) != "undefined") {
                        if (record.user_is_live == 1)
                            $("#user_live_burst_" + record.user_id).html('<i title="User is online" class="fa fa-circle-o faa-burst text-success animated"></i>');
                        else
                            $("#user_live_burst_" + record.user_id).html('');
                    }
                }
            }
        }
    });
}

function getArrayByColumnKey(arrayData, keyName) {
    var keyArray = [];
    for (var i = 0; i < arrayData.length; i++) {
        keyArray[arrayData[i][keyName]] = arrayData[i];
    }
    return keyArray;
}

function notifyOnlineOfflineUsers(newLiveUserData) {
    var slideUpTimeOut = 5000;
    var isNotify = false;
    if (prevLiveUserData != null) {
        var pData = getArrayByColumnKey(prevLiveUserData, 'user_id');

        for (var i = 0; i < newLiveUserData.length; i++) {
            if (newLiveUserData[i]['user_is_live'] != pData[newLiveUserData[i]['user_id']]['user_is_live']) {
                notifyUserStatus(newLiveUserData[i], slideUpTimeOut);
                slideUpTimeOut += 1000;
                isNotify = true;
            }
        }
        if (isNotify) {
            var audio = new Audio(USER_ONLINE_NOTIF_SOUND);
            audio.play();
        }
    }
    prevLiveUserData = newLiveUserData;
}

function notifyUserStatus(user, slideUpTime) {
    if (user['user_is_live'] == 1) {
        var color = '#0f0';
        var statusName = 'online';
        var className = 'success';
    } else {
        var color = '#f00';
        var statusName = 'offline';
        var className = 'danger';
    }
    var u = $('<div class="p-1 mt-2 user_notif_box user_notif_' + user['user_id'] + '"><img class="img-avatar" height="50" style="box-shadow:0px 0px 7px ' + color + '" title="' + user['user_name'] + '" alt="' + user['user_name'] + '" src="' + user['user_image'] + '"><div style="text-shadow:1px 2px 9px ' + color + '"><i class="fa fa-circle text-' + className + '"></i> <span class="text-' + className + '">' + statusName + '</span></div></div>');
    $(".dynamic_notif .user_notif_" + user['user_id']).remove();
    $(".dynamic_notif").append(u);
    $(document).on("mousemove", "body", function() {
        if ($(".user_notif_box")) {            
            setTimeout(function() {
                u.slideUpRemove(500)
            }, slideUpTime);
        }
    });
}

function userBirthDayCheck() {
    if (getCookie('birthdayCheck') == '') {
        var data = {
            action: 'employee/getbirthdayuser',
        };
        $.ajax({
            type: 'POST',
            data: data,
            url: sitePath + 'ajax.php',
            beforeSend: function() {},
            success: function(output) {
                enableSubmission(output);
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    setCookie('birthdayCheck', 1, 1);
                    notifyUserBirthDay(arr[2]);
                }
            }
        });
    }
}

function notifyUserBirthDay(userArray) {

    if (userArray.length > 0) {
        var user = userArray[0];
        var birthDayHtml = '<div class="flex-space-around"><img class="img-circle" style="height:180px; margin-top:150px;" src="' + user['user_image'] + '"></div><div class="flex-space-around"><h3 class="text-muted">' + (userId == user['user_id'] ? ('Happy Birthday ' + user['user_fname']) : ('It\'s ' + user['user_fname'] + ' Birthday Today')) + '</h3></div>';
        $("#general-modal").find('div.modal-body').html(birthDayHtml);
        $('#general-modal').modal({
            show: true
        });
    }
}

setTimeout(userBirthDayCheck, 3000);

$.fn.slideUpRemove = function(d, c) {
    var _this = this,
        duration = $.isNumeric(d) || $.isPlainObject(d) ? d : 400,
        callback = $.isFunction(c) ? c : d;

    return this.slideUp(duration).promise().done(function() {
        _this.remove();
        if ($.isFunction(callback)) callback.call();
    });
};

var preOrderFetched = false;
function scheduleFetchOrderInfo() {
    var web_order_id = 0;
    if (typeof(window.localStorage.getItem("user_last_order_seen")) !== 'undefined') {
        web_order_id = window.localStorage.getItem("user_last_order_seen");
    }

    var dataAjax = {
        'action': 'system/getnewweborderinfoforuser',
        'web_order_id': web_order_id
    };

    $.ajax({
        type: 'POST',
        data: dataAjax,
        url: sitePath + 'ajax.php',
        beforeSend: function() {},
        success: function(output) {
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
                if (arr[2].length > 0) {
                    for (var i = 0; i < arr[2].length; i++) {
                        var item = arr[2][i];
                        pushUsersNotification(item, 0);
                        window.localStorage.setItem("user_last_order_seen", item['limit']);
						if(preOrderFetched == true)
						bottomNotificationStack.push(`<img src="`+item.image+`" height="24px"> `+item.title);
                    }
                    var audio = new Audio(ORDER_NOTIFICATION_SOUND);
                    audio.play();
                }
            }
			preOrderFetched = true;
        }
    });
}

function scheduleFetchPaidInvoice() {
    
    var dataAjax = {
        'action': 'system/getschedulefetchpaidinvoice'
    };

    $.ajax({
        type: 'POST',
        data: dataAjax,
        url: sitePath + 'ajax.php',
        beforeSend: function() {},
        success: function(output) {
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
                if (arr[2].length > 0) {
                    for (var i = 0; i < arr[2].length; i++) {
                        var item = arr[2][i];
                        pushUsersNotification(item, 0);
                    }
                    var audio = new Audio(SALES_INVOICE_PAID_SUCCESS);
                    audio.play();
                }
            }
        }
    });
}

function scheduleFetchJivoChatEvent() {
    var event_id = 0;
    if (typeof(window.localStorage.getItem("jivo_chat_event_id")) !== 'undefined') {
        event_id = window.localStorage.getItem("jivo_chat_event_id");
    }

    var dataAjax = {
        'action': 'system/getnewjivochatevent',
        'event_id': event_id
    };

    $.ajax({
        type: 'POST',
        data: dataAjax,
        url: sitePath + 'ajax.php',
        beforeSend: function() {},
        success: function(output) {
			speakTimeout = 0;
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
                if (arr[2].length > 0) {
                    for (var i = 0; i < arr[2].length; i++) {
                        var item = arr[2][i];
                        pushUsersNotification(item, 3000);
                        window.localStorage.setItem("jivo_chat_event_id", item['event_id']);
                    }
                    var audio = new Audio(USER_NEW_CUSTOMER_SITE_SOUND);
                    audio.play();
                }
            }
        }
    });
}
				

function removeLoadingBox(id) {
    $("#" + id + " .loading_box").remove();
}

function openChatBox(user_id, user_name, user_image) {
    if ($("#chatboxcontainerid_" + user_id).length == 0) {
        var chatcount = $(".chatboxcontainer").length;
        marginleft = 200 + (chatcount * 30);
        var html = '<div id="chatboxcontainerid_' + user_id + '" class="chatboxcontainer"><div class="chatboxheader" id="chatboxcontainerid_' + user_id + 'header"><img class="user_chat_image" src="' + user_image + '"/><span class="chatboxusername">' + user_name + '</span> <i class="userchatdivclose fa fa-close pull-right" style="cursor:pointer;"></i></div><div class="chatboxarea"><div id="chatboxareatextid_' + user_id + '" class="chatboxareatext"></div><div class="chatboxareainput"><div><div class="colsm9"><input type="text" placeholder="write message here..." id="chatwindowtextarea_' + user_id + '" data-user-id="' + user_id + '" class="chatwindowtextarea"/></div><div class="colsm3 text-right mr-1"><i class="fa fa-send iconsendmsg text-success mt-2 fa-2x" onclick="sendChatMessage(' + user_id + ');"></i></div></div></div></div></div></div>';
        $("body").append(html);
        dragElement(document.getElementById(("chatboxcontainerid_" + user_id)));
        //$("#chatboxcontainerid_"+user_id).css("margin-left", marginleft+'px');
        $("#chatwindowtextarea_" + user_id).on("keypress", function(e) {
            if (e.which == 13 || e.keyCode == 13) {
                sendChatMessage($(this).attr("data-user-id"));
            }
        });
        loadUserLiveChatHistory(user_id, 0);
    }
}

function loadUserLiveChatHistory(user_id, offsetId) {
    removeLoadmoreChat("chatboxareatextid_" + user_id);
    var dataAjax = {
        'action': 'employee/loaduserlivechathistory',
        'user_id': user_id,
        'offsetId': offsetId == 'undefined' ? 0 : offsetId
    };
    $("#chatboxareatextid_" + user_id).prepend(LOADING_HTML);
    $.ajax({
        type: 'POST',
        data: dataAjax,
        url: sitePath + 'ajax.php',
        beforeSend: function() {},
        success: function(output) {
            removeLoadingBox("chatboxareatextid_" + user_id);
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
                if (offsetId == 'undefined' || offsetId == 0)
                    $("#chatboxareatextid_" + user_id).html('');
                if (arr[2].length > 0) {
                    for (var i = 0; i < arr[2].length; i++) {
                        var payload = {
                            'data': arr[2][i],
                        };
                        chatMessagePayload(payload, 'prepend');
                    }
                    loadmorechat(user_id);
                } else {
                    if (offsetId == 'undefined' || offsetId == 0)
                        $("#chatboxareatextid_" + user_id).html(EMPTY_TEXT_BOX);
                }
            }
        }
    });
}



function chatMessagePayload(payload, format) {
    var data = payload.data;
    if ($("#chat_msg_" + data.msg_id).length == 0) {
        if (data.sender_id == userId) {
            openChatBox(data.receiver_id, data.receiver_name, data.receiver_image);
            var user_id = data.receiver_id;
            var message = '<div id="chat_msg_' + data.msg_id + '" class="textput textsender userchatcounter"><div class="row chatrow"><div class=" col-sm-2"><div class="row"><div class="avatar"><a class="redirect"><img class="img-avatar" src="' + data.sender_image + '" height="34px"></a></div></div></div><div class="col-sm-10 textmsgput">' + data.msg_text + '<div class="col-sm-12 text-right msgtimestamp">' + data.msg_time + '</div></div></div></div>';
        } else {
            openChatBox(data.sender_id, data.sender_name, data.sender_image);
            var user_id = data.sender_id;
            var message = '<div id="chat_msg_' + data.msg_id + '" class="textput textreceiver userchatcounter"><div class="row chatrow"><div class="col-sm-10 textmsgput">' + data.msg_text + '<div class="col-sm-12 text-left msgtimestamp">' + data.msg_time + '</div></div><div class=" col-sm-2"><div class="row"><div class="avatar"><a class="redirect"><img class="img-avatar" src="' + data.sender_image + '" height="34px"></a></div></div></div></div></div>';
        }
        $("#chatboxareatextid_" + user_id).children(".empty_text_box").remove();
        if (format == 'prepend')
            $("#chatboxareatextid_" + user_id).prepend(message);
        else {
            $("#chatboxareatextid_" + user_id).append(message);
            $("#chatboxareatextid_" + user_id).scrollTop($("#chatboxareatextid_" + user_id).prop("scrollHeight"));
        }
    }
}

function removeLoadmoreChat(id) {
    $("#" + id + " .loadmorechat").remove();
}

function loadmorechat(user_id) {
    var message = '<div data-user-id="' + user_id + '" class="loadmorechat"><span>Load more previous chat</span></div>';
    $("#chatboxareatextid_" + user_id).prepend(message);
}


function sendChatMessage(user_id) {
    var msg_text = $("#chatwindowtextarea_" + user_id).val().trim();
    if (msg_text != "") {
        $("#chatwindowtextarea_" + user_id).val('');
        var dataAjax = {
            'chatsection': 1,
            'msg_receiver': user_id,
            'msg_text': msg_text
        };

        $.ajax({
            type: 'POST',
            data: dataAjax,
            url: sitePath + 'fcm.php',
            beforeSend: function() {},
            success: function(output) {
                var arr = JSON.parse(output);
                if (arr[0] != 200) {
                    message(arr[1], 2000);
                    $("#chatwindowtextarea_" + user_id).val(msg_text);
                } else {
                    chatMessagePayload(arr[2], 'prepend');
                }
            }
        });
    }
}


//Make the DIV element draggagle:


function dragElement(elmnt) {
    var pos1 = 0,
        pos2 = 0,
        pos3 = 0,
        pos4 = 0;
    if (document.getElementById(elmnt.id + "header")) {
        /* if present, the header is where you move the DIV from:*/
        document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
    } else {
        /* otherwise, move the DIV from anywhere inside the DIV:*/
        elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        /* stop moving when mouse button is released:*/
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

alertMessage = {
    Body: function(html) {
        $("#appModalMessage .modal-body").html(html);
    },
    Reset: function() {
        $("#appModalMessage .modal-body").html('');
        $("#appModalMessage .modal-body").removeClass("text-default text-primary text-success text-warning text-danger");
        $("#appModalMessage").modal('hide');
        $("#appModalMessage .modal-footer").hide();
    },
    Show: function() {
        $("#appModalMessage").modal('show');
        confirmMessage.Hide();
    },
    Hide: function() {
        $("#appModalMessage").modal('hide');
    },
    showFooter: function(isVisible) {
        isVisible ? $("#appModalMessage .modal-footer").show() : $("#appModalMessage .modal-footer").hide();
    }
}

confirmMessage = {
    Set: function(html, funCallBack, parameter, cancelCallBack) {
        $("#appModalConfirm .modal-body").html(html);
        funCallBackConfirm = funCallBack !== 'undefined' ? funCallBack : null;
        parameterConfirm = parameter !== 'undefined' ? parameter : '';
        funCancelCallBack = cancelCallBack !== 'undefined' ? cancelCallBack : null;
        $("#appModalConfirm").modal('show');
    },
    Show: function() {
        $("#appModalConfirm").modal('show');
    },
    Hide: function() {
        $("#appModalConfirm").modal('hide');
		funCallBackConfirm = null;
		funCancelCallBack = null;
		parameterConfirm = '';
    }
}

$("#appModalConfirmYes").on("click", function() {
    if (funCallBackConfirm != null){
        window[funCallBackConfirm](parameterConfirm);
	}
    confirmMessage.Hide();
});

$("#appModalConfirmNo").on("click", function() {
    if (funCancelCallBack !== null){
		 window[funCancelCallBack](parameterConfirm);
	}
    confirmMessage.Hide();
});


function filesUpload(field_name, extra_field) {
    var file = _(field_name).files[0];
    var formdata = new FormData();
    formdata.append(field_name, file);
    formdata.append('field_handler', field_name);

    if (extra_field !== undefined) {
        $.each(extra_field, function(key, value) {
            formdata.append(key, value);
        });
    }
    is_interval_file_running = false;
    is_file_uploaded = false;

    var uploadHandler = $("#" + field_name).parent('div').find('.file_uploader');    

    var ajax = new XMLHttpRequest();
    ajax.filed_handler = field_name;
    ajax.upload.addEventListener("progress", function(event) {
        progressFileHandler(event, uploadHandler);
    }, false);
    ajax.addEventListener("load", function(event) {
        completeFileHandler(event, uploadHandler);
    }, false);
    ajax.addEventListener("error", function(event) {
        errorFileHandler(event, uploadHandler);
    }, false);
    ajax.addEventListener("abort", function(event) {
        abortFileHandler(event, uploadHandler);
    }, false);
    ajax.open("POST", sitePath + "csvupload.php");
    ajax.send(formdata);
}

function progressFileHandler(event, uploadHandler) {
    dissableSubmission();
    var percent = (event.loaded / event.total) * 100;
    uploadHandler.html(round(percent,2) + ' % ');

    if (is_file_uploaded == false && round(percent,2) == 100) {
        is_file_uploaded = true;
        call_back_file_upload_handler(uploadHandler);
    }

    if (round(percent) == 100)
        uploadHandler.html(processing_file_data);
}

function call_back_file_upload_handler(uploadHandler) {
    is_interval_file_running = true;
    uploadHandler.html(processing_file_data);
}

function completeFileHandler(event, uploadHandler) {
    enableSubmission(event.target.responseText);
    var arr = JSON.parse(event.target.responseText);
    if (arr[1]) {
        message(arr[1]);
        if (arr[0] == 200) {
            uploadHandler.html(arr[2]);
            if (arr[3] !== 'undefined')
                window[arr[3]](arr[4]);
        } else
            uploadHandler.html(original_file_data);
    } else
        uploadHandler.html(original_file_data);
}

function errorFileHandler(event, uploadHandler) {
    enableSubmission(event.target.responseText);
    uploadHandler.html(original_file_data);
    message("danger|File Upload error. Please try gaian.");
}

function abortFileHandler(event, uploadHandler) {
    enableSubmission(event.target.responseText);
    uploadHandler.html(original_file_data);
    message("danger|File Upload aborted by user. Please try gaian.");
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function delCookie(cname) {
    var expires = "expires=Thu, 01 Jan 1970 00:00:00 UTC";
    document.cookie = cname + "=" + ";" + expires + ";path=/";
}


function fancyTimeFormat(time) {
    // Hours, minutes and seconds
    var hrs = ~~(time / 3600);
    var mins = ~~((time % 3600) / 60);
    var secs = ~~time % 60;

    // Output like "1:01" or "4:03:59" or "123:03:59"
    var ret = "";

    if (hrs > 0) {
        ret += "" + hrs + ":" + (mins < 10 ? "0" : "");
    }

    ret += "" + mins + ":" + (secs < 10 ? "0" : "");
    ret += "" + secs;
    return ret;
}

function getDayName(dateVal) {
    var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    return days[dateVal];
}

function padDigits(number, digits) {
    return Array(Math.max(digits - String(number).length + 1, 0)).join(0) + number;
}

function seconds2time(seconds) {
    var hours = Math.floor(seconds / 3600);
    var minutes = Math.floor((seconds - (hours * 3600)) / 60);
    var seconds = seconds - (hours * 3600) - (minutes * 60);
    var time = "";

    if (hours != 0) {
        time = hours + " hour ";
    }
    if (minutes != 0 || time !== "") {
        minutes = (minutes < 10 && time !== "") ? "0" + minutes : String(minutes);
        time += minutes + " min ";
    }
    return time;
}


function pushUsersNotification(item, autoClose) {
    var title = typeof(item.title) === 'undefined' ? null : item.title;
    var image = typeof(item.image) === 'undefined' ? null : item.image;
    var user = typeof(item.user) === 'undefined' ? null : item.user;
    var link = typeof(item.link) === 'undefined' ? '#' : item.link;
	var time = typeof(item.time) === 'undefined' ? null : item.time;
	var type = typeof(item.type) === 'undefined' ? null : item.type;

    if (title != null && image != null && user != null) {
		var ele = $('<div class="notifbox"><div class="notif_img avatar pull-left"><img src="' + image + '" class="img-avatar mt-1" alt="Img"></div><div class="notif_text pull-left">' + title + (time != null ? ('<br/><i class="icon-clock text-success"></i>&nbsp; ' + time):'') + '</div><div class="clear clearer"></div><div class="notif_detail"><small class="text-muted"><i class="icon-user text-success"></i>&nbsp; ' + user + '</small> &nbsp; <small class="text-muted updatecomment" data-complaint-log-id="2362" data-comment-logger-type="E">'+((typeof(item.link) == 'undefined' || item.link == null || item.link == '') ? '':'<a '+(type == 'JIVO-CHAT' ? 'target="_blank"' :'class="redirect"')+' href="' + link + '"><i class="icon-cursor"></i> Open</a>&nbsp; ')+'<span class="notif_close text-danger"><i class="icon-trash"></i> Close</span></small></div></div>');
        $("#popupnotification").append(ele);
		if(autoClose > 0)
		ele.delay(autoClose).fadeOut('slow');
    }
	if(!typeof(item.speak) === 'undefined'){
		setTimeout(function(){say(item.speak)}, speakTimeout + 3000);
		speakTimeout = speakTimeout + 3000;
	}
}

function showMeIntro(name){
	if (0 && typeof(getCookie(name)) !== "undefined" && getCookie(name) == 0){
		javascript:introJs().start();
		setCookie(name, 1);
	}
	else{
		$("#main").append("<div class='flip-card introtour text-center py-1 px-1' onclick=\"recallIntro('"+name+"');\"><div class='flip-card-inner'><div class='flip-card-front'><i class='fa fa-question-circle text-white fa-2x fa-w'></i></div><div class='flip-card-back'><i class='fa fa-question-circle text-white fa-2x fa-w'></i></div></div></div>");
	}
}

function appendPrevNext(values){
	if(typeof(values.PREV) != 'undefined' && values.PREV != null)
	$(".container-fluid").append("<div title=\"Prev Item\" class=\"prevnextbox prevnextbox-prev text-center py-1 px-1\"><a class=\"redirect\" href=\""+(values.PREV)+"\"><i class='fa fa-backward text-white fa-w'></i></a></div>");
	if(typeof(values.NEXT) != 'undefined' && values.NEXT != null)
	$(".container-fluid").append("<div title=\"Next Item\" class=\"prevnextbox prevnextbox-next text-center py-1 px-1\"><a class=\"redirect\" href=\""+(values.NEXT)+"\"><i class=\"fa fa-forward text-white fa-w\"></i></a></div>");
	
	$(".prevnextbox").css("top", $(window).height()/2 - 50);
}

function recallIntro(name){
	javascript:introJs().start();
	setCookie(name, 0);
	showMeIntro(name);
}

function getFilterLabel(item) {
    if ($('#tag_' + item.attr('id')).length == 0)
        return '<span id="tag_' + item.attr('id') + '" class="tagspan">' + (typeof(item.attr('data-label')) == 'undefined' ? item.val() : item.attr('data-label')) + ' <span><i data-id="' + item.attr('id') + '" class="filtercloser fa fa-close"></i></span></span>';
    else
        return '';
}

function addfiltersearch() {
    window.localStorage.setItem(window.location.href.split('#')[0], JSON.stringify(formdata));
}

function resetfiltersearch() {
    window.localStorage.removeItem(window.location.href.split('#')[0]);
}

function hideFestiveModal() {
    setCookie('hideFestiveModal', true, 1);
	window.location.reload();
}


function resetLot() {
    $(".lot_product_count").text('');
    $(".createnewlot").removeClass('hide');
    $(".viewcurrentlot, .clearcurrentlot").addClass('hide');
}

function updateLot(count, updateRecord) {
    if (updateRecord === undefined)
        $(".lot_product_count").text(count);
    $(".createnewlot").addClass('hide');
    $(".viewcurrentlot, .clearcurrentlot").removeClass('hide');
}

function confirmOrderUpload(field_name, id, label_type)
{
	confirmMessage.Set('Are you sure to upload Shipment '+label_type+'...?', 'proceedUploadPopUpLabel', {field_name : field_name, id : id, label_type: label_type});
}
function openLabelUploader(name, id, label_type){
	modal.Title("Select Label file to upload");
	modal.Body(LOADING_HTML);
	var data={
					action		:	'shipment/getshipmentoptions'				
			};	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);		
			if(arr[0] == 200){
				var bodyHtml = '<div class="col-md-12"><div class="row">';
	
				bodyHtml += '<div class="col-xs-12 col-sm-6"><div class="form-group"> <label for="customer_type_id">Shipment service<sup>*</sup></label><select id="label_shipment_service_type" name="label_shipment_service_type" class="form-control label_shipment_service_type label_shipment_required" size="1"><option value=""> - Shipment service - </option>'+arr[2]+'</select></div></div>';
				
				bodyHtml += '<div class="col-xs-12 col-sm-6"><div class="form-group"><label>Tracking code<sup>*</sup></label><input class="form-control input_text_upper label_shipment_service_code label_shipment_required" type="text" maxlength="50" name="label_shipment_service_code" value=""></div></div>';
				
				bodyHtml += '<div class="col-xs-12 col-sm-12"><label>Uploading label for -</label><div class="form-group"><div class="col-xs-12"><div class="row"><label class="switch switch-icon switch-pill switch-success"><input class="switch-input shiplment_label_for_type" checked="" id="shiplment_label_for_type_dispatch" value="Dispatch" name="shiplment_label_for_type" type="radio"><span class="switch-label"  data-on="" data-off=""></span> <span class="switch-handle"></span> </label> &nbsp; <strong>Dispatch</strong> &nbsp; &nbsp; &nbsp; <label class="switch switch-icon switch-pill switch-info"><input class="switch-input shiplment_label_for_type" id="shiplment_label_for_type_return" value="Return" name="shiplment_label_for_type" type="radio"><span class="switch-label"  data-on="" data-off=""></span> <span class="switch-handle"></span> </label> &nbsp; <strong>Return</strong></div></div></div></div>';
				
				bodyHtml += '<div class="col-xs-12 col-sm-12"><div class="form-group"><label>Other Information<sup></sup></label><input class="form-control label_details_field" type="text" maxlength="500" name="label_details_field" value=""></div></div>';
				
				bodyHtml += '<div class="col-xs-12"><div class="form-group text-center"><a onClick="$(\'#'+name+'\').click()" disabled class="btn btn-info common_label_uploader_btn text-white"><i class="fa fa-paperclip fa-fw"></i> Select Shipment '+label_type+' File </a><input onchange="confirmOrderUpload(this.name, \''+id+'\', \''+label_type+'\');" type="file" id="'+name+'" class="d-none" name="'+name+'"></div></div>';
				
				bodyHtml += '</div></div>';
				
				modal.Body(bodyHtml);
			}
		}
	});
	modal.History('');
	modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
}

function proceedUploadPopUpLabel(uploadData)
{	
	var files = _(uploadData.field_name).files;
	var formdata = new FormData(); 
	formdata.append('field_name', uploadData.field_name);
	if(files.length == 1)
	{
		for (var index = 0; index < files.length; index++) 
		{
			formdata.append('webcam[]', files[index]); 
		}
		formdata.append('mediasection', mediaSection);
		formdata.append('id', uploadData.id); 
		if($(".label_shipment_service_type").length){
			formdata.append('label_shipment_service_type', $(".label_shipment_service_type").val());
		}
		if($(".label_shipment_service_code").length){
			formdata.append('label_shipment_service_code', $(".label_shipment_service_code").val());	
		}
		if($(".label_details_field").length){
			formdata.append('label_details_field', $(".label_details_field").val());	
		}
		formdata.append('label_type', $(".shiplment_label_for_type:checked").val());	 
		is_interval_running = false;
		is_file_uploaded 	= false;
		$("."+uploadData.field_name+"_processing").html('<div class="col-xs-12 col-lg-3 p-3"  id="item-collection-media-processing"><div id="image_uploader"></div></div>');

		var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", progressHandler, false); 
		ajax.addEventListener("load", completeLabelHandler, false);	
		ajax.addEventListener("error", errorHandler, false); 
		ajax.addEventListener("abort", abortHandler, false); 
		ajax.open("POST", sitePath +"saveimage.php"); 
		ajax.send(formdata);
	}
}

$(document).ready(function(e) {
	
	fetchScheduleReminder();
	
	$(document).on('keyup, change', '.label_shipment_required', function(e){
		e.preventDefault();
		var allowAttachement = true;
		$(".label_shipment_required").each(function(){
			if($(this).val() == '')
			allowAttachement = false;
		});
		$(".common_label_uploader_btn").attr('disabled', !allowAttachement);
	});
	
	$(document).on("click", ".comments_log_operner", function(evt){
		evt.preventDefault();
		openChatLogForm($(this).attr('data-id'), $(this).attr('data-heading'));
	});
	
	$(document).on("click", ".btn-update-order-priority", function(evt){
		evt.preventDefault();
		var dataAjax = {
			'action': 'weborder/updateorderpriority',
			'id': $(this).attr('data-id'),
			'priority' : $(this).prev('input.drop-down-input').val()
		};
		$.ajax({
			type: 'POST',
			data: dataAjax,
			url: sitePath + 'ajax.php',
			beforeSend: function() {},
			success: function(output) {
				var arr = JSON.parse(output);
				toastMessage(arr[1]);
			}
		});
	});
	
	$(document).on("click", ".schedule_opener", function(evt){
		evt.preventDefault();
		var ele = $(this);
		var btnContent = ele.html();
		$(this).html('<i class="fa fa-refresh fa-spin"></i> Opening...');
		var id = $(this).attr('data-id');
		var dataAjax = {
			'action': 'schedule/getscheduledetails',
			'id': id
		};
		$.ajax({
			type: 'POST',
			data: dataAjax,
			url: sitePath + 'ajax.php',
			beforeSend: function() {},
			success: function(output) {
				ele.html(btnContent);
				var arr = JSON.parse(output);
				if (arr[0] == 200) {					
					updateSchedule(arr[2]);
				}
				else
				toastMessage(arr[1]);
			}
		});
	});
	
    $(document).on("click", "#resetfilteration", function() {
        resetfiltersearch();
        Redirect(window.location.pathname.slice(1));
    });

    if ($(".snowflakes").length > 0) {
        setTimeout(function() {
                confirmMessage.Set($('.snowflakes').attr('data-title'), 'hideFestiveModal')
            }, 5000);
    }

    if (USER_CAN_SEE_ORDER_NOTIFICATION == 1) {
        scheduleFetchOrderInfo();
        setInterval(scheduleFetchOrderInfo, 60 * 1300);
    }
	
	if(JIVO_CHAT_EVENT_ENABLED){
		scheduleFetchJivoChatEvent();
		setInterval(scheduleFetchJivoChatEvent, 60 * 1070);		
	}
	
	if(FETCH_PAID_INVOICE_ENABLED){
		scheduleFetchPaidInvoice();
		setInterval(scheduleFetchPaidInvoice, 60 * 2780);		
	}
	
	if(countUserTagAutoLoad == true){
		scheduleTagFetch();		
	}

    $(document).on("click", ".notif_close", function() {
        $(this).parents(".notifbox").fadeOut(500, function() {
            $(this).remove();
        });
    });

    $("html").on("click", "a.salaryoptionbtn", function() {
        var user_id = $(this).attr("data-id");
        var month_id = $(this).attr("data-month-id");

        var data = {
            action: "employee/empmonthsalary",
            user_id: user_id,
            month_id: month_id
        };

        $.ajax({
            type: 'POST',
            data: data,
            url: sitePath + 'ajax.php',
            beforeSend: function() {
                setPopup(user_id, "Loading... Employee MPR");
                modal.Body(LOADING_HTML);
                modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
                modal.Show();
            },
            success: function(output) {
                enableSubmission(output);
                var arr = JSON.parse(output);
                var msg = arr[1].split("|");
                if (arr[0] == 200) {
                    var info = arr[2];
                    var total_sales = typeof(info.pay_slip_total_sale) === 'undefined' ? 0 : info.pay_slip_total_sale;
                    var sale_comosion = typeof(info.pay_slip_commision) === 'undefined' ? info.user_pay_sales_commision : info.pay_slip_commision;
                    var grant_pay = typeof(info.pay_slip_grant_pay) === 'undefined' ? (info.user_pay_salary + round((total_sales * sale_comosion) / 100,2)) : info.pay_slip_grant_pay;
                    modal.Title("\"" + info.user_name + "\" " + msg[1] + " (" + info.month_name + ")");
                    var bodyHtml = '<div class="col-md-12"><div class="row">';
                    bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="pay_slip_basic_salary">Basic salary <sup>*</sup></label><input type="text" id="pay_slip_basic_salary" name="pay_slip_basic_salary" required  class="form-control" placeholder="Enter basic salary" value="' + info.user_pay_salary + '" onblur="calculaterandPay();" /></div></div>';
                    bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="pay_slip_total_sale">Total Sale</label><input type="text" id="pay_slip_total_sale" name="pay_slip_total_sale" class="form-control" placeholder="Enter Total Sale" value="' + (typeof(info.pay_slip_total_sale) === 'undefined' ? 0 : info.pay_slip_total_sale) + '" onblur="calculaterandPay();" /></div></div>';
                    bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="pay_slip_commision">Sale Commision %</label><input type="text" id="pay_slip_commision" name="pay_slip_commision" class="form-control" placeholder="Enter basic salary" value="' + (typeof(info.pay_slip_commision) === 'undefined' ? info.user_pay_sales_commision : info.pay_slip_commision) + '"  onblur="calculaterandPay();" /></div></div>';
                    bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="pay_slip_leave_taken">Leave Taken</label><input type="number" min="0" max="31" id="pay_slip_leave_taken" name="pay_slip_leave_taken " class="form-control" placeholder="Leaves taken" value="' + (typeof(info.pay_slip_leave_taken) === 'undefined' ? 0 : info.pay_slip_leave_taken) + '" /></div></div>';
                    bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="pay_slip_paid_status">Pay Status<sup>*</sup></label><select id="pay_slip_paid_status" required name="pay_slip_paid_status" size="1" class="form-control">' + info.paid_option + '</select></div></div>';
                    bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="pay_slip_pay_mode">Payment mode<sup>*</sup></label><select id="pay_slip_pay_mode" required name="pay_slip_pay_mode" size="1" class="form-control">' + info.paid_paymode + '</select></div></div>';

                    bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="pay_slip_grant_pay">Grand pay</label><input type="text" id="pay_slip_grant_pay" name="pay_slip_grant_pay" class="form-control" min="0" required placeholder="Total Grand pay" value="' + grant_pay + '" /></div></div>';

                    bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="pay_slip_payment_reference">Payment reference</label><input type="text" id="pay_slip_payment_reference" name="pay_slip_payment_reference" class="form-control" maxlength="50" placeholder="Payment Reference" value="' + (typeof(info.pay_slip_payment_reference) === 'undefined' ? "" : info.pay_slip_payment_reference) + '" /></div></div>';

                    bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="complaint_is_disk_provided">Send Slip to Employee &nbsp; <i class="fa fa-file-pdf-o"></i></label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="send_pay_slip_to_employee" value="1" name="send_pay_slip_to_employee" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div>';

                    if (typeof(info.pay_slip_link) !== 'undefined')
                        bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="complaint_is_disk_provided">Download Payslip &nbsp; <i class="fa fa-pdf-o fa-lg m-t-2"></i></label> <a target="new" href="' + info.pay_slip_link + '" class="btn btn-sm btn-danger"><i class="fa fa-file-pdf-o"></i> Download</a></div></div>';

                    bodyHtml += '<input type="hidden" name="user_id" value="' + user_id + '">';
                    bodyHtml += '<input type="hidden" name="month_id" value="' + month_id + '">';
                    bodyHtml += '</div></div></form>';
                    modal.Body(bodyHtml);
                    modal.Footer('<button type="reset" class="btn btn-default" >Reset</button><button type="submit" class="btn btn-success" >Save</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
                    if (typeof(info.pay_slip_grant_pay) === 'undefined')
                        calculaterandPay();
                } else {
                    modal.Body(msg[1]);
                    popmessage(arr[1]);
                }
            }
        }); // Ajax Close		
    }); // Function Close

    $(document).on("keyup", ".input_text_upper", function() {
        $(this).val($(this).val().toUpperCase());
    });

<<<<<<< HEAD
=======
    var isTimeOutPlaying = false;
    var audioTimeOutPlaying = new Audio(USER_SESSION_EXPIRE_SOUND);
    audioTimeOutPlaying.addEventListener('ended', function(){
        audioTimeOutPlaying.play();
    });
>>>>>>> 77a717f (Version 2)
    if (typeof(window.localStorage.getItem('user_sign_in')) !== "undefined" && window.localStorage.getItem('user_sign_in') == "true") {
        if (typeof(window.Storage) !== "undefined") {
            var di = new Date();
            window.localStorage.setItem("live_session", di.getTime());
            $("html").on("mousemove", function() {
                if ($("#session_live_popup").is(":hidden")) {
                    var dm = new Date();
                    window.localStorage.setItem("live_session", dm.getTime());
                }
            })
            setTimeout(function() {
                interval = setInterval(function() {
                    var dc = new Date();
                    var seconds = Math.floor((dc.getTime() - window.localStorage.getItem("live_session")) / 1000);
                    if (seconds > max_idle_limit && getCookie("LOGOFF") != true) {
<<<<<<< HEAD
=======
                        if(!isTimeOutPlaying){
                            audioTimeOutPlaying.play();
                            isTimeOutPlaying = true;
                        }
>>>>>>> 77a717f (Version 2)
                        var remaingTime = Math.floor(parseInt(max_clock_run - seconds)) > 0 ? Math.floor(parseInt(max_clock_run - seconds)) : 0;
                        $("#session_live_popup").show();
                        $("#session_live_count").html(remaingTime);
                        document.title = 'Warning: ' + remaingTime + ' second to logout.';
                        if (max_clock_run - seconds <= 0) {
                            clearInterval(interval);
                            window.location = logoutUrl;
                        }
                    } else {
                        $("#session_live_popup").hide();
                        document.title = documentPageTitle;
<<<<<<< HEAD
=======
                        isTimeOutPlaying = false;
                        audioTimeOutPlaying.pause();
>>>>>>> 77a717f (Version 2)
                    }
                }, 1700);
            }, 5000);
        } else
            console.log("Browser not supported storage");
    } else
        console.log("Browser not supported storage for login " + window.localStorage.getItem('user_sign_in'));


    $(document).on("click", "#keppmelivesession", function() {
        var du = new Date();
        window.localStorage.setItem("live_session", du.getTime());
        $("#session_live_popup").hide();
<<<<<<< HEAD
    });

    $(document).on("click", "#logedmeoutsession", function() {
=======
        isTimeOutPlaying = false;
        audioTimeOutPlaying.pause();
    });

    $(document).on("click", "#logedmeoutsession", function() {
        audioTimeOutPlaying.pause();
        isTimeOutPlaying = false;
>>>>>>> 77a717f (Version 2)
        window.location = logoutUrl;
    });

/*
    $(".nav-link").click(function() {
        $(".nav-item a.nav-link").each(function(index, element) {
            $(this).removeClass("active");
        });
        $(this).addClass("active");
    })
*/
    $("html").on("click", ".live_user", function() {
        var user_id = $(this).attr("data-user-id");
        var user_image = $(this).attr("data-user-image");
        var user_name = $(this).attr("data-user-name");
        openChatBox(user_id, user_name, user_image);
    });

    $("#changeLiveUserWindow").on("click", function() {
        if ($(this).hasClass("fa-angle-down")) {
            $(".live_user_container").slideUp();
        } else {
            $(".live_user_container").slideDown();
        }
        $(this).toggleClass("fa-angle-down ");
        $(this).toggleClass("fa-angle-up");
    });

    $("html").on("click", "i.userchatdivclose", function() {
        $(this).parents(".chatboxheader").parents(".chatboxcontainer").remove();
    });


    $("html").on("click", ".loadmorechat", function() {
        loadUserLiveChatHistory($(this).attr("data-user-id"), $("#chatboxareatextid_" + $(this).attr("data-user-id") + " .userchatcounter").length);
    })

    $("html").on("focus", "input.chatwindowtextarea", function() {
        $(this).parents("div").parents("div").parents("div.chatboxareainput").parents("div.chatboxarea").parents("div.chatboxcontainer").children("div.chatboxheader").addClass("chatboxheaderactive");
    });

    $("html").on("focusout", "input.chatwindowtextarea", function() {
        $(this).parents("div").parents("div").parents("div.chatboxareainput").parents("div.chatboxarea").parents("div.chatboxcontainer").children("div.chatboxheader").removeClass("chatboxheaderactive");
    });

    $('body').on('hidden.bs.modal', function() {
        if ($('.modal.in').length > 0) {
            $('body').addClass('modal-open');
        }
    });

    setTimeout(function() {
        $.ajax({
            type: 'POST',
            data: {
                'action': 'system/checkexistinglot'
            },
            url: sitePath + 'ajax.php',
            beforeSend: function() {},
            success: function(output) {
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    $(".lot_product_count").text(arr[2]);
                    if (arr[2] === '') {
                        $(".viewcurrentlot, .clearcurrentlot").addClass('hide');
                    } else {
                        $(".createnewlot").addClass('hide');
                        $(".viewcurrentlot, .clearcurrentlot").removeClass('hide');
                    }
                }
                //message(arr[1],2000);
            }
        });
    }, 5000);


    $(document).on('click', '.addtolot', function(e) {
        e.preventDefault();
        var dataAjax = {
            'action': 'system/addproductonlot',
            'product': $(this).attr('data-lot')
        };

        $.ajax({
            type: 'POST',
            data: dataAjax,
            url: sitePath + 'ajax.php',
            beforeSend: function() {},
            success: function(output) {
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    updateLot(arr[2]);
                } else if (arr[0] == 300) {
                    updateLot(arr[2], true);
                }
                message(arr[1], 1000);
            }
        });
    });

    $('.createnewlot').on('click', function() {
        var dataAjax = {
            'action': 'system/createnewlot'
        };

        $.ajax({
            type: 'POST',
            data: dataAjax,
            url: sitePath + 'ajax.php',
            beforeSend: function() {},
            success: function(output) {
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    updateLot(0);
                } else if (arr[0] == 300) {
                    updateLot(0, false);
                }
                message(arr[1], 2000);
            }
        });
    });

    $('.clearcurrentlot').on('click', function() {
        var dataAjax = {
            'action': 'system/clearcurrentlot'
        };

        $.ajax({
            type: 'POST',
            data: dataAjax,
            url: sitePath + 'ajax.php',
            beforeSend: function() {},
            success: function(output) {
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    resetLot();
                    $(".viewcurrentlot").attr("href", sitePath + "viewlotitems");
                }
                message(arr[1], 2000);
            }
        });
    });

    $(document).on("click", ".removelotproduct", function(e) {
        e.preventDefault();
        $('#tablelotproduct').DataTable().row("#row_" + $(this).attr('data-id')).remove().draw();
    });

    $(document).on("click", ".mark_weborder_processed", function(e) {
        e.preventDefault();
        var pData = {
            order_id: $(this).attr("data_order_id"),
            label_id: $(this).attr("data_label_id"),
            group_action: $(this).attr("data-group-action")
        };
        if ($(this).attr("data_is_pack_user") != 1) {
            confirmMessage.Set("This " + $(this).attr('data-name') + " is assigned to " + $(this).attr('data_user') + "<br/>Do you still want to mark it processed... ?", 'proceedtoMarkProcess', pData);
        } else
            proceedtoMarkProcess(pData);
    });
	
    $(document).on("click", ".download_weborder_label", function(e) {
        e.preventDefault();
        var pData = {
            'label_id': $(this).attr("data_label_id"),
            'group_action': $(this).attr("data-group-action")
        };
        if ($(this).attr("data_is_pack_user") != 1) {
            confirmMessage.Set("This Label is assigned to " + $(this).attr('data_user') + "<br/>Do you still want to download this label... ?", 'proceedtoDownloadLable', pData);
        } else
            proceedtoDownloadLable(pData);
    });
	
	
	
		$(document).on('click', '.fullfillmentopener', function(e){
		e.preventDefault();
		selectedProductFullFillmentOpener = $(this);
		var data_code = $(this).attr('data-code');
		var data_id = $(this).attr('data-id');
		var data_sku = $(this).attr('data-sku');
		var data_item_id = $(this).attr('data-item-id');
		var data_reference = $(this).attr('data-reference');
		var data_sale_price = $(this).attr('data-sale-price');
		var data_sale_currency = $(this).attr('data-sale-currency');
		
		$("#appModalQuick .modal-title").html("Product fulfill availbility");    
		$("#appModalQuick .modal-body").html(LOADING_HTML);
		$("#appModalQuick .modal-footer").html('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
		
		var data = {
			action: 'system/fulfillcheckforopener',
			data_code 		: data_code,
			data_id 		: data_id,
			data_sku 		: data_sku,
			data_item_id 	: data_item_id
		};
		$.ajax({
			type: 'POST',
			data: data,
			timeout: AJAX_REQUEST_MAX_TIME,
			url: sitePath + 'ajax.php',
			beforeSend: function() {
				resetPageLoading();
			},
			success: function(output) {
				stop_time = new Date().getTime();			
				try {
					var contextData = $.parseJSON(output);
					if(contextData[0] == 200){
						var html = '<table class="table table-bordered table-striped">';
						for(var i=0; i<contextData['data'].length; i++){
							var pro = contextData['data'][i];
							var pro_type_code = pro['lot_code'].split('|')[0];
							html += '<tr><td>';
								html += '<div class="row"><div class="col-sm-12">';
									html += '<div class="card "><div class="p-1">'+''+pro['name']+'</div><div class="card-body" data-code="'+data_code+'" data-product-id="'+pro['id']+'" data-type-code="'+pro_type_code+'"  data-product-code="'+pro['code']+'" data-serial="'+pro['srno']+'" data-id="'+data_id+'" data-item-id="'+data_item_id+'" data-sku="'+data_sku+'"><div class="px-1 pt-0 d-flex flex-wrap align-items-center justify-content-between"><div class="justify-content-start pl-1"><span class="badge badge-info">Origin : '+pro['origin']+'</span></div><div class="justify-content-start pl-1"><span class="badge badge-primary">SKU : '+pro['sku']+'</span></div><div class="justify-content-start pl-1"><span class="badge badge-primary">Location : '+pro['location']+'</span></div><div class="justify-content-start pl-1"><span class="badge badge-primary">Code : '+pro['code']+'</span></div><div class="justify-content-start pl-1"><span class="badge badge-primary">Serial : '+pro['srno']+'</span></div></div><div class="px-1 pt-0 pb-1 d-flex align-items-left justify-content-start"><div class="mb-1 pt-1 d-flex align-items-center justify-content-center"><div class="pl-0 justify-content-center"><a class="btn btn-success" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm(\''+flipString(pro['lot_code'], '|')+'\', \'#'+pro['code']+' Log Report\')"><i class="fa fa-comments-o" aria-hidden="true"></i></a> <a target="_blank" class="btn btn-default" href="'+pro['label']+'"><i class="fa fa-barcode" aria-hidden="true"></i> Label</a> <a class="btn btn-warning redirect" onclick="Redirect(\''+pro['link']+'\')" data-dismiss="modal" href="'+pro['url']+'"><i class="fa fa-eye" aria-hidden="true" ></i> View Product</a></div></div></div><div class="px-1 pt-0 pb-1 row"><div class="col-xs-12 col-sm-6 col-md-4"><div class="form-group"><label>Selling Price ('+data_sale_currency+')<sup>*</sup></label><input class="form-control" name="bpca_sell_price" placeholder="Enter Selling Product price" type="number" step="0.01" value="'+data_sale_price+'"></div></div><div class="col-xs-12 col-sm-6 col-md-4"><div class="form-group"><label>Store Reference<sup>*</sup></label><div class="input-group"><input class="form-control"  name="bpca_store_reference" maxlength="30" placeholder="Enter Reference like order id" type="text" value="'+data_reference+'"><span class="input-group-addon">ORDER ID</span></div></div></div><div class="col-xs-12 col-sm-6 col-md-4"><div class="form-group"><label>Selling Date/Time<sup>*</sup></label><div class="input-group date"><input type="text" class="form-control datetimepicker" name="bpca_sell_date" placeholder="YYYY-MM-DD" value=""> <span class="input-group-addon"> <label style="margin-bottom:0px;"><i class="fa fa-calendar fa-lg m-t-2" aria-hidden="true"></i></label></span> </div>  </div></div>  <div class="col-sm-12">  <div class="form-group">  <label>Selling Remark<sup>*</sup></label>  <textarea name="bpca_remark" rows="2" class="form-control" placeholder="Enter selling remark like extra charge or shipping charges etc"></textarea> </div></div><div class="col-sm-12"><a type="button" onclick="confirmMessage.Set(\'Are you sure to use this product and create sale history for it...?<br/>Important : This product will mark as out of stock and a sale history record will be added for it\', \'assignproductandsavesalehistory\', {btn : $(this), lot_code:\''+pro['lot_code']+'\'});" class="btn btn-success mt-0"><i class="fa fa-check" aria-hidden="true"></i> Assign product and create Sale History</a></div></div></div></div>';
								html += '</div></div>';
							html += '</td></tr>';
						}
						html += '</table>';
					}
					else{
						var html = '<center>No fulfill available for this product</center>'
					}
					
					$("#appModalQuick .modal-body").html(html);
					$(function () {
					$('.datetimepicker').datetimepicker({
						format: 'yyyy-mm-dd HH:ii P',
						autoclose:true,
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true,
						fontAwesome : true,
						showMeridian: true,
					});
				});
					$("#appModalQuick").on("hidden.bs.modal", function (e) {					
						$("#appModalQuick  .modal-body").html('');
					});
				} catch (err) {
					$("#appModalQuick .modal-body").html("Unable to laod fulfill options");
				}
				resetPageLoading();
			},
			error: function(xmlhttprequest, textstatus, messagecontent) {
				MAX_AJAX_REQUEST_RETRING_ATTEMPT_COUNT++;
				clearInterval(retryingInterval);
				if (textstatus === "timeout") {
					currentRequest.abort();
					message("danger|<div class='card-action'>Request Timeout.<br/><a type='button' href=\"javascript:Redirect('" + path + "')\" class='btn btn-outline-success'>Reload</a></div>", 0);
					$("#" + contentboxid).html('<div class="col-sm-12 col-md-12 text-center pt-3 pb-3"><img class="img" src="' + sitePath + 'img/system-failed.png"><br/><span class="text-muted">System couldn\'t process request.<br/>Check Internet connection and Try again !</span></div>');
				}
			}
		});
	});

}); // Document ready Close


function assignproductandsavesalehistory(productData){
	var openrPro = selectedProductFullFillmentOpener.parents('.card-block');
	
	var ele = productData.btn;
	var parentBody = ele.parents('.card-body');
	var data_code = parentBody.attr('data-code');
	var data_id = parentBody.attr('data-id');
	var data_sku = parentBody.attr('data-sku');
	var data_item_id = parentBody.attr('data-item-id');
	var data_serial = parentBody.attr('data-serial');
	var data_product_id = parentBody.attr('data-product-id');	
	var data_type_code = parentBody.attr('data-type-code');
	var data_product_code = parentBody.attr('data-product-code');
	
	
	var sell_price = parentBody.find('input[name="bpca_sell_price"]').val();
	var store_reference = parentBody.find('input[name="bpca_store_reference"]').val();
	var sell_date = parentBody.find('input[name="bpca_sell_date"]').val();
	var remark = parentBody.find('textarea[name="bpca_remark"]').val();
	
	if(sell_price == ""){
		toastMessage('warning|Please enter sell price');
		parentBody.find('input[name="bpca_sell_price"]').focus();
		return;
	}
	if(store_reference == ""){
		toastMessage('warning|Please enter reference');
		parentBody.find('input[name="bpca_store_reference"]').focus();
		return;
	}
	if(sell_date == ""){
		toastMessage('warning|Please enter sale date');
		parentBody.find('input[name="bpca_sell_date"]').focus();
		return;
	}
	if(remark == ""){
		toastMessage('warning|Please enter remark');
		parentBody.find('textarea[name="bpca_remark"]').focus();
		return;
	}
	
	var data = {
		action			:	'system/assignproductandsavesalehistory',
		data_code : data_code,
		data_id : data_id,
		data_sku : data_sku,
		data_item_id : data_item_id,
		data_product_id: data_product_id,
		data_type_code : data_type_code,
		data_product_code : data_product_code,
		sell_price : sell_price,
		store_reference : store_reference,
		sell_date : sell_date,
		remark : remark
	};
	
	
	
	$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
			beforeSend: function(){
			message("process|Assigning product...", 0);
			dissableSubmission();
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				openrPro.find('input[name="wo_product_srno['+data_item_id+']"]').val(data_serial);
				openrPro.find('input[name="wo_process_code['+data_item_id+']"]').val(data_product_code);
			}
			message(arr[1],2000);															   
		}
	});
}
	

function proceedtoMarkProcess(pData) {
    var data = {
        action: pData.group_action,
        order_action: 'process',
        web_order_id: pData.order_id,
        sales_invoice_id: pData.order_id,
        complaint_id: pData.order_id,
        user_id: 0

    };
    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        beforeSend: function() {
            message("connecting|Connecting...", 0);
            dissableSubmission();
        },
        success: function(output) {
            enableSubmission(output);
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
                setTimeout(function() {
                    var item = $((pData.group_action == "Order" ? "#dashboard_order_label_" : "#dashboard_sales_label_") + pData.label_id)
                    item.toggle("slow");
                    item.remove();
                }, 4000);
            }
            message(arr[1]);
        }
    });
}


$(document).on("click", ".product_availbility_check", function(e){
	e.preventDefault();
	var section_code = $(this).attr('data-section');
	var product_id = $(this).attr('data-id');
	var order_id = $(this).attr('data-order-id');
	
	var data={
			action			:	'company_resource/getavailablesupplierlist'
		};		
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			setPopup(section_code+'|'+order_id+'|'+product_id, "Product Availbility Check");
			modal.Body(LOADING_HTML);
			modal.Footer('');
			modal.Show();
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				openProductAvailabilityCheckForm(section_code, product_id, arr[2], arr[3]);
			}
		}
	});	
});

function openProductAvailabilityCheckForm(section_code, product_id, suppliers_list, time){
		
	var bodyHtml = '<div class="col-md-12">';	
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="pro_avail_supplier_id">Supplier<sup>*</sup></label><select id="pro_avail_supplier_id" name="pro_avail_supplier_id" class="form-control" size="1">'+suppliers_list+'</select></div></div>';
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="pro_avail_checked_time">Checked On</label><input class="form-control" id="pro_avail_checked_time" name="pro_avail_checked_time" maxlength="50" placeholder="Availbility Checked time" type="text" onfocus="blur()" value="'+time+'"></div></div>';
	
	
	bodyHtml +='</div>';	
	
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-12"><div class="form-group">\
              <label> Product Stock availability ?</label>\
              <br/>\
                  <div class="col-sm-12 col-md-4">\
                    <label class="switch switch-icon switch-pill switch-success">\
                    <input class="switch-input pro_avail_stock_status" value="Available" name="pro_avail_stock_status" type="radio">\
                    <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label> <strong>Available</strong>\
                  </div>\
              	  <div class="col-sm-12 col-md-4">\
                    <label class="switch switch-icon switch-pill switch-warning">\
                    <input class="switch-input pro_avail_stock_status" value="Waiting to confirm" name="pro_avail_stock_status" type="radio">\
                    <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label> <strong>Waiting to confirm</strong>\
                  </div>\
                  <div class="col-sm-12 col-md-4">\
                    <label class="switch switch-icon switch-pill switch-danger">\
                    <input class="switch-input pro_avail_stock_status" value="Not available" name="pro_avail_stock_status" type="radio">\
                    <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label> <strong>Not available</strong>\
                  </div>\
            </div></div>';	
	
	bodyHtml +='<div class="col-md-12"><div class="form-group"><label for="pro_avail_remark">Reamrk</label><textarea class="form-control" id="pro_avail_remark" name="pro_avail_remark" maxlength="250" rows="2" data-label="Note about availability check" placeholder="Note about availability check"></textarea></div></div>';
	bodyHtml +='</div>';
	
	modal.Body(bodyHtml);
	modal.Footer('<button type="reset" class="btn btn-default" >Reset</button><button type="button" onclick="addProductAvailabilityCheck();" class="btn btn-success" >Save</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
	$("#pro_avail_checked_time").datetimepicker({ format: "yyyy-mm-dd hh:ii", autoclose: true, todayBtn: true, fontAwesome : true  });
}

function addProductAvailabilityCheck(){	
	if($("#pro_avail_supplier_id").val() == 0){
		toastMessage('warning|Please select supplier');
		return false;
	}
	if($("#pro_avail_checked_time").val() == ""){
		toastMessage('warning|Please select stock availability checked time');
		return false;
	}
	if($(".pro_avail_stock_status:checked").length == 0){
		toastMessage('warning|Please select stock availability status');
		return false;
	}
	var data={
				action		:	"system/addproductavailabilitycheck",
				key_id	:	$("#keyid").val(),
				pro_avail_supplier_id : $("#pro_avail_supplier_id").val(),
				pro_avail_checked_time : $("#pro_avail_checked_time").val(),
				pro_avail_stock_status : $(".pro_avail_stock_status:checked").val(),
				pro_avail_remark	   : $("#pro_avail_remark").val()
		};
	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
		},		
		success:function(output){ 		
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)	
			{
				modal.Hide();
			}
			toastMessage(arr[1]);
		}
	});	
}

function proceedtoDownloadLable(pData) {

    var data = {
        action: pData.group_action,
        label_id: pData.label_id
    };
    $.ajax({
        type: 'POST',
        data: data,
        url: sitePath + 'ajax.php',
        beforeSend: function() {
            message("connecting|Connecting...", 0);
            dissableSubmission();
        },
        success: function(output) {
            enableSubmission(output);
            var arr = JSON.parse(output);
            if (arr[0] == 200) {
                var url = arr[2];
                $("#label_download_count_" + pData.label_id).text(arr[3]);
                let a = document.createElement('a');
                a.href = url;
                a.download = url.split('/').pop().split('#')[0].split('?')[0];
                document.body.appendChild(a);
                a.click();
                setTimeout(function() {
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                }, 100);
            }
            message(arr[1]);
        }
    });
}

/*------------Image Galley Viewer Modal Code-----------*/

var modalId = $('#image-gallery');

$(document).ready(function() {
	
    loadGallery(true, 'a.thumbnail');

    $.fn.dataTable.pipeline = function(opts) {
        // Configuration options
        var conf = $.extend({
            pages: 5, // number of pages to cache
            url: '', // script url
            data: null, // function or object with parameters to send to the server
            // matching how `ajax.data` works in DataTables
            method: 'GET' // Ajax HTTP method
        }, opts);

        // Private variables for storing the cache
        var cacheLower = -1;
        var cacheUpper = null;
        var cacheLastRequest = null;
        var cacheLastJson = null;

        return function(request, drawCallback, settings) {
            var ajax = false;
            var requestStart = request.start;
            var drawStart = request.start;
            var requestLength = request.length;
            var requestEnd = requestStart + requestLength;

            if (settings.clearCache) {
                // API requested that the cache be cleared
                ajax = true;
                settings.clearCache = false;
            } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                // outside cached data - need to make a request
                ajax = true;
            } else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
            ) {
                // properties changed (ordering, columns, searching)
                ajax = true;
            }

            // Store the request for checking next time around
            cacheLastRequest = $.extend(true, {}, request);

            if (ajax) {
                // Need data from the server
                if (requestStart < cacheLower) {
                    requestStart = requestStart - (requestLength * (conf.pages - 1));

                    if (requestStart < 0) {
                        requestStart = 0;
                    }
                }

                cacheLower = requestStart;
                cacheUpper = requestStart + (requestLength * conf.pages);

                request.start = requestStart;
                request.length = requestLength * conf.pages;

                // Provide the same `data` options as DataTables.
                if (typeof conf.data === 'function') {
                    // As a function it is executed with the data object as an arg
                    // for manipulation. If an object is returned, it is used as the
                    // data object to submit
                    var d = conf.data(request);
                    if (d) {
                        $.extend(request, d);
                    }
                } else if ($.isPlainObject(conf.data)) {
                    // As an object, the data given extends the default
                    $.extend(request, conf.data);
                }

                return $.ajax({
                    "type": conf.method,
                    "url": conf.url,
                    "data": request,
                    "dataType": "json",
                    "cache": false,
                    "success": function(json) {
                        cacheLastJson = $.extend(true, {}, json);

                        if (cacheLower != drawStart) {
                            json.data.splice(0, drawStart - cacheLower);
                        }
                        if (requestLength >= -1) {
                            json.data.splice(requestLength, json.data.length);
                        }

                        drawCallback(json);
                    }
                });
            } else {
                json = $.extend(true, {}, cacheLastJson);
                json.draw = request.draw; // Update the echo for each response
                json.data.splice(0, requestStart - cacheLower);
                json.data.splice(requestLength, json.data.length);

                drawCallback(json);
            }
        }
    };

    // Register an API method that will empty the pipelined data, forcing an Ajax
    // fetch on the next draw (i.e. `table.clearPipeline().draw()`)
    $.fn.dataTable.Api.register('clearPipeline()', function() {
        return this.iterator('table', function(settings) {
            settings.clearCache = true;
        });
    });	
});
//This function disables buttons when needed
function disableButtons(counter_max, counter_current) {
    $('#show-previous-image, #show-next-image')
        .show();
    if (counter_max === counter_current) {
        $('#show-next-image')
            .hide();
    } else if (counter_current === 1) {
        $('#show-previous-image')
            .hide();
    }
}

$(document).on("click", ".data-download", function(e) {
    var url = $(this).attr('data-href');
    let a = document.createElement('a');

    // initialize 
    a.href = url;
    a.download = url.split('/').pop().split('#')[0].split('?')[0];

    // append element to the body, 
    // a must, due to Firefox
    document.body.appendChild(a);

    // trigger download
    a.click();

    // delay a bit deletion of the element
    setTimeout(function() {
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    }, 100);

});
var isPopoverOnProgress = false;
var popoverState = [];
$(document).on("mouseenter", '[data-toggle="popover-ajax"]', function() {	
	if(!isPopoverOnProgress && typeof($(this).attr('data-popover-action')) != 'undefined'){		
		var e = $(this);
		isPopoverOnProgress = true;
		e.popover('dispose');
		var div_id =  "tmp-id-" + $.now();		
		var ajaxData = {action : e.attr('data-popover-action'), id: e.attr('data-popover-id')};
		var isStateSave = typeof($(this).attr('data-popover-state')) != 'undefined' && $(this).attr('data-popover-state') == 'saved';
		if(isStateSave && typeof(popoverState['popover-state-'+ajaxData.action+'-'+ajaxData.id]) != 'undefined'){
			showPopOverBox(e, div_id, popoverState['popover-state-'+ajaxData.action+'-'+ajaxData.id]);		
			isPopoverOnProgress = false;
		}
		else{
			$.ajax({
				type: 'POST',
				data: ajaxData,
				url: sitePath + 'popover.php',
				beforeSend: function() {
					showPopOverBox(e, div_id, '<center><i class="fa fa-circle-o-notch fa-spin fa-fw"></i><span> Loading...</span></center>');
				},
				success: function(response) { 
					isPopoverOnProgress = false;
					if(isStateSave){
						popoverState['popover-state-'+ajaxData.action+'-'+ajaxData.id] = response;
					}
					$(document).find('.'+div_id).html(response);
				}
			}); 
		}
	}	
});
<<<<<<< HEAD

=======
/*
$(document).on("mouseenter", '[data-toggle="popover"]', function() {		
	var e = $(this);
	e.popover('dispose');
	var div_id =  "tmp-id-" + $.now();	
	//showPopOverBox(e, div_id, popoverState['popover-state-'+div_id]);
	showPopOverBox(e, div_id, e.attr('data-content'));
});
*/
>>>>>>> 77a717f (Version 2)
function openReturnForm(title, module_code, module_id, module_reference){
	modal.Title(title);
	modal.Body(LOADING_HTML);
	var data={
					action		:	'return/checkreturn',
					module_code	:	module_code,
					module_id	:	module_id,
					module_reference : module_reference
			};	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			$(".return_details_after").html('');	
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);		
			if(arr[0] == 200){				
				
				var bodyHtml = '<div class="col-md-12"><div class="row">';
				
				bodyHtml +='<div class="col-md-12 return_details_before"></div>';
				
				bodyHtml += '<div class="col-xs-12 col-sm-6"><div class="form-group"><label>Return date<sup>*</sup></label><input class="form-control return_date" type="text" maxlength="50" name="return_date" value=""></div></div>';
	
				bodyHtml += '<div class="col-xs-12 col-sm-6"><div class="form-group"> <label for="return_status">Return Status<sup>*</sup></label><br/><span style="line-height: 2;">is this '+module_reference+' returned ?</span><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input return_status" id="return_status" value="1" name="return_status" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div>';
								
				bodyHtml +='<div class="col-md-12"><div class="form-group"><label for="return_remark">Remark</label><textarea class="form-control return_remark" id="return_remark" name="return_remark" maxlength="250" rows="2" data-label="Note about this return" placeholder="Note about this return"></textarea></div></div>';
				
				bodyHtml +='<div class="col-md-12 return_details_after"></div>';
				
				bodyHtml += '</div></div>';
				bodyHtml += '<input class="return_id" type="hidden" name="return_id" value="'+arr[3]+'"><input class="return_module_code" type="hidden" name="return_module_code" value="'+module_code+'"><input class="return_module_id" type="hidden" name="return_module_id" value="'+module_id+'"><input class="return_module_reference" type="hidden" name="return_module_reference" value="'+module_reference+'">';
				modal.Body(bodyHtml);
				$(".return_date").datetimepicker({ format: "yyyy-mm-dd hh:ii", autoclose: true, todayBtn: true, fontAwesome : true  });
				if(arr[2] != 0){
					$(".return_date").val(arr[4].return_date);
					$(".return_remark").val(arr[4].return_remark);	
					$(".return_details_after").html(arr['return_details_after']);
					$(".return_status").prop("checked", arr[4].return_status == "0" ? false : true);			
				}
			}
		}
	});
	modal.History('');
	modal.Footer('<button type="button" class="btn btn-success btn-save-return">Save</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
}

function saveReturnDetails(){
	if ($(".return_date").val()!="" && $(".return_sttaus").val()!="" && $(".return_remark").val()!= "") {
        var dataAjax={
			action	:	'return/savereturn',
			return_status : $(".return_status").is(":checked") ? 1:0,
		};		
		dataAjax = $.extend(dataAjax, $("#modalform").serializeFormJSON());
        $.ajax({
            type: 'POST',
            data: dataAjax,
            url: sitePath + 'ajax.php',
            beforeSend: function() {
                popmessage("connecting|Connecting...", 0);
            },
            success: function(output) {
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    $(".modal.open.in .return_id").val(arr[2]);
                }
                popmessage(arr[1]);
            }
        })
    }
	else{
		toastMessage("warning|All fields required.");
	}
}

$(document).on('click', '.btn-save-return', function(e){
		e.preventDefault();
		confirmMessage.Set('Are you sure save return details..?', 'saveReturnDetails');
});

$(document).on('click', '.returnable', function (e) {
	e.preventDefault();
    openReturnForm($(this).attr('data-module-title'), $(this).attr('data-module-code'), $(this).attr('data-module-id'), $(this).attr('data-module-reference'));
});

$(document).on('click', '.emojiinput', function(e){
	e.preventDefault();
	$(".mentiony-content").html($(".mentiony-content").html() + $(this).attr('alt'));
});

function showPopOverBox(e, div_id, content){
	e.popover({
			animate : false,
			trigger: 'manual',
			container: e,
			viewport: '.container',
			content: '<div class="'+ div_id +'" style="min-width:240px">'+content+'</div>', 
			html : true, 
			placement: 'bottom'
		}).popover('show');
}

<<<<<<< HEAD
$(document).on('mouseenter', '[data-toggle="popover-ajax"]', function () {
=======
$(document).on('mouseenter', '[data-toggle="popover-ajax"], [data-toggle="popover"]', function () {
>>>>>>> 77a717f (Version 2)
    var self = this;
    jQuery(this).popover("show");
    jQuery(".popover").on('mouseleave', function () {
        jQuery(self).popover('hide');
    });
});
$(document).on('mouseleave', '[data-toggle="popover-ajax"],  .clockpicker-popover', function () {
    var self = this;
    setTimeout(function () {
        if (!jQuery('.popover:hover').length) {
            jQuery(self).popover('hide');
        }
    }, 600);
});
// $(document).on("mouseleave", '[data-toggle="popover-ajax"]', function() {
	// $(this).popover('dispose');
// });

function loadGallery(setIDs, setClickAttr) {

    var current_image,
        selector,
        counter = 0;
    $(document).on("click", '#show-next-image, #show-previous-image', function() {
        if ($(this)
            .attr('id') === 'show-previous-image') {
            current_image--;
        } else {
            current_image++;
        }

        selector = $('[data-image-id="' + current_image + '"]');
        updateGallery(selector);
    });


    function updateGallery(selector) {
        var $sel = selector;
        current_image = $sel.data('image-id');
        $('#image-gallery-title')
            .text($sel.data('title'));

        var d = new Date();
        var n = d.getTime();
        var image_id = 'image-gallery-image-' + n;
        $('#image-gallery').find('div.modal-body').html('');
        var modalBodyWidth = $('#image-gallery').find('div.modal-body').innerWidth();
        var img = $('<img id="' + image_id + '">'); //Equivalent: $(document.createElement('img'))
        img.attr('src', $sel.data('image'));
        img.addClass('img-responsive col-md-12');
        img.attr('width', modalBodyWidth);
        $('#image-gallery').find("div.modal-body").html(img);
        $('#image-gallery').find("div.modal-footer .data-download").attr('data-href', $sel.data('image'));

        $('#' + image_id).attr('data-image', $sel.data('image'));
        $('#' + image_id).attr('data-zoom-image', $sel.data('image'));
        disableButtons(counter, $sel.data('image-id'));

        //----------------Zoom Code----------------------------

        $('#' + image_id).ezPlus({
            zoomType: 'lens',
            lensShape: 'round',
            lensSize: 200,
            zIndex: 1055
        });

        //-----------------------------------------------------

    }

    if (setIDs == true) {
        $('[data-image-id]')
            .each(function() {
                counter++;
                $(this)
                    .attr('data-image-id', counter);
            });
    }
    $(setClickAttr)
        .on('click', function() {
            updateGallery($(this));
        });
}

// build key actions
$(document)
    .keydown(function(e) {
        var x = e.which || e.keyCode;
        switch (x) {
            case 37: // left
                if ((modalId.data('bs.modal') || {})._isShown && $('#show-previous-image').is(":visible")) {
                    $('#show-previous-image')
                        .click();
                }
                return;
                break;

            case 39: // right
                if ((modalId.data('bs.modal') || {})._isShown && $('#show-next-image').is(":visible")) {
                    $('#show-next-image')
                        .click();
                }
                return;
                break;

            default:
                return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    });

/*-------------------Image Gallery modal viewer code end*/

var matched, browser;

jQuery.uaMatch = function(ua) {
    ua = ua.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
        /(webkit)[ \/]([\w.]+)/.exec(ua) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
        /(msie) ([\w.]+)/.exec(ua) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) || [];

    return {
        browser: match[1] || "",
        version: match[2] || "0"
    };
};

matched = jQuery.uaMatch(navigator.userAgent);
browser = {};

if (matched.browser) {
    browser[matched.browser] = true;
    browser.version = matched.version;
}

// Chrome is Webkit, but Webkit is also Safari.
if (browser.chrome) {
    browser.webkit = true;
} else if (browser.webkit) {
    browser.safari = true;
}

jQuery.browser = browser;