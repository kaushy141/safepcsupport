<footer class="app-footer"> 
<a target="_blank" href="https://www.safepcdisposal.co.uk"><span class="server_potentail"></span> SPD</a> © <?=date('Y')?> v-<?=VERSION?>
<span class="float-right">Powered by <a target="_blank" href="https://www.tecknosoft.com">TecknoSoft</a> </span> 
<div class="bottom_notification_box"></div>
</footer>
<?php 
Modal::load(array('Voting'));
	if(0 && ENABLE_JS_CSS_CACHE)
		echo loadJS($autoloadJS);
	else{
	foreach($autoloadJS as $jsFile)
	echo "<script type=\"text/javascript\" src=\"".$app->jsPath($jsFile)."\"></script> ";
	}?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="application/javascript">
function Redirect(path, isHistory)
{ 
	if(path!="#" && path!="")
	{
		if(typeof(isHistory) == 'undefined' || isHistory == true)
		window.history.pushState(path, '', sitePath+path);
		loadPage(path);
	}   
}
<?php 
if(isset($loadedFunction) && $loadedFunction!="")
{
	echo $loadedFunction;
}
?>

function showBottomNotification(html){
	$(".bottom_notification_box").fadeOut(1000).promise().done(function(){
		 $('.bottom_notification_box').html('<span class="botton_notification_span">'+html+'</span>').fadeIn(1000);
	});
	setTimeout(function(){$('.bottom_notification_box').fadeIn(2000);}, 10000);
}
var winnerHtml = "<?php echo Voting::getWinnerHtml()?>";
if(winnerHtml != '')
{
	bottomNotificationStack.push(winnerHtml);
	<?php if(date('d') <= Voting::$votingOpenDays ){?>
	if(getCookie('vottingResultSeen') != '1'){
		showLastMonthWinnerTable();
		setCookie('vottingResultSeen', 1, 1);
	}
	<?php }else{
		echo "delCookie('vottingResultSeen');";		
	}?> 
}

function showLastMonthWinnerTable(){
	var data={
			action			:	'system/getlastmonthwinnertable'
		};		
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			setPopup(0,"<i class='fa fa-crown'></i> Results of last month Winner");
			modal.Body(LOADING_HTML);
			modal.Footer('');
			modal.Show();
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				modal.Body(arr[2]);
			}
		}
	});	
}
function say(m) {
  var msg = new SpeechSynthesisUtterance();
  var voices = window.speechSynthesis.getVoices();
  msg.voice = voices[10];
  msg.voiceURI = "native";
  msg.volume = 1;
  msg.rate = 1;
  msg.pitch = 0.8;
  msg.text = m;
  msg.lang = 'en-US';
  speechSynthesis.speak(msg);
}
</script> 
<script>
  // Initialize Firebase
  var config = {
    apiKey: GOOGLE_FIREBASE_API_KEY,
    authDomain: GOOGLE_FIREBASE_API_AUTH_DOMAIN,
    databaseURL: GOOGLE_FIREBASE_API_DATABASE_URL,
    projectId: GOOGLE_FIREBASE_API_PROJECT_ID,
    storageBucket: GOOGLE_FIREBASE_API_STORAGE_BUCKET,
    messagingSenderId: GOOGLE_FIREBASE_API_MESSAGING_SENDER_ID,
	appId: GOOGLE_FIREBASE_API_APP_ID,
	measurementId: GOOGLE_FIREBASE_API_MEASUREMENT_ID
  };
  firebase.initializeApp(config);
</script> 
<script>
  // [START get_messaging_object]
  // Retrieve Firebase Messaging object.
  const messaging = firebase.messaging();
  // [END get_messaging_object]

  // IDs of divs that display Instance ID token UI or request permission UI.
  const tokenDivId = 'token_div';
  const permissionDivId = 'permission_div';

  // [START refresh_token]
  // Callback fired if Instance ID token is updated.
  messaging.onTokenRefresh(function() {
    messaging.getToken()
    .then(function(refreshedToken) {
      console.log('Token refreshed.');
      // Indicate that the new Instance ID token has not yet been sent to the
      // app server.
      setTokenSentToServer(false);
      // Send Instance ID token to app server.
      sendTokenToServer(refreshedToken);
      // [START_EXCLUDE]
      // Display new Instance ID token and clear UI of all previous messages.
      resetUI();
      // [END_EXCLUDE]
    })
    .catch(function(err) {
      console.log('Unable to retrieve refreshed token ', err);
      //showToken('Unable to retrieve refreshed token ', err);
    });
  });

  messaging.onMessage(function(payload) {
	  console.log(payload);
    if(payload.data.msg_format == 'module')
	{
		appendMessage(payload);
		if($("#keyid").val()!==undefined)
		appendMessagePopup(payload);
		showNotification(payload.data);
	}
	else if(payload.data.msg_format == 'chat'){
		chatMessagePayload(payload);
	}
	else if(payload.data.msg_format == 'location')
	{
		drawUserLocationOnMap(payload);
	}
	
  });
  

  function resetUI() {
    //message("process|Connecting to Server...");
    //showToken('Connecting to Server...');
    messaging.getToken()
    .then(function(currentToken) {
      if (currentToken) {
        sendTokenToServer(currentToken);
        updateUIForPushEnabled(currentToken);
      } else {
        // Show permission request.
        //console.log('No Instance ID token available. Request permission to generate one.');
        message("warning|No Instance ID token available. Request permission to generate one",0);
        updateUIForPushPermissionRequired();
        setTokenSentToServer(false);
      }
    })
    .catch(function(err) {
	  //message("warning|Unsupported Browser : Browser not supporting Live Chat.",2000);
      //console.log('An error occurred while retrieving token. ', err);
      setTokenSentToServer(false);
    });
  }
  // [END get_token]


  // Send the Instance ID token your application server, so that it can:
  // - send messages back to this app
  // - subscribe/unsubscribe the token from topics
  function sendTokenToServer(currentToken) {
    if (!isTokenSentToServer()) {	  
	  //message("success|Connected..!!! Sending token to server.");
	  sendUserTokentoServer(currentToken);
      setTokenSentToServer(true);
    } else {
	  sendUserTokentoServer(currentToken);
	  //message("danger|Token already sent to server so won\'t send it again  unless it changes");
      //console.log('Token already sent to server so won\'t send it again ' +'unless it changes');
    }
	console.log("Sending Token to Server : " + currentToken);
  }

  function isTokenSentToServer() {
    return window.localStorage.getItem('sentToServer') == 1;
  }

  function setTokenSentToServer(sent) {
    window.localStorage.setItem('sentToServer', sent ? 1 : 0);
  }

 
  function requestPermission() {
    //console.log('Requesting permission...');
	message("process|Requesting permission...");
    // [START request_permission]
    messaging.requestPermission()
    .then(function() {
      //console.log('Notification permission granted.');
	  message("success|Notification permission granted.");
      // TODO(developer): Retrieve an Instance ID token for use with FCM.
      // [START_EXCLUDE]
      // In many cases once an app has been granted notification permission, it
      // should update its UI reflecting this.
      resetUI();
      // [END_EXCLUDE]
    })
    .catch(function(err) {
      console.log('Unable to get permission to notify.', err);
    });
    // [END request_permission]
  }

  function deleteToken() {
    // Delete Instance ID token.
    // [START delete_token]
    messaging.getToken()
    .then(function(currentToken) {
      messaging.deleteToken(currentToken)
      .then(function() {
        //console.log('Token deleted.');
        setTokenSentToServer(false);
        // [START_EXCLUDE]
        // Once token is deleted update UI.
        resetUI();
        // [END_EXCLUDE]
      })
      .catch(function(err) {
        //console.log('Unable to delete token. ', err);
      });
      // [END delete_token]
    })
    .catch(function(err) {
      //console.log('Error retrieving Instance ID token. ', err);
      message("warning|Error retrieving Instance ID token.");
    });

  }


  function updateUIForPushEnabled(currentToken) {
    //showToken(currentToken);
  }

  function updateUIForPushPermissionRequired() {
	  requestPermission();
  }

  resetUI();
  


function showNotification(data)
{
	if(data.logger_type!="<?=getUserType()?>")
	{
		$("#complaint_global_log_count_header").text((parseInt($("#complaint_global_log_count_header").text().toString()) || 0) +1);
		if(data.logger_type=="E")
		$("#customer_unread_message_count_"+data.logger_id).text((parseInt($("#customer_unread_message_count_"+data.logger_id).text().toString()) || 0)+1);
	}
	
}

function createSchedule()
{
	setPopup(0, "ToDo Reminder");
	var bodyHtml = '<div class="col-md-12">';
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><label for="schedule_title">Reminder note<sup>*</sup></label><textarea class="form-control" id="schedule_title" name="schedule_title" maxlength="5000" rows="3" data-label="Reminder note" placeholder="Write reminder note here"></textarea></div></div>';
	bodyHtml +='</div>';
	
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="schedule_due_date">Time<sup>*</sup></label> <div class="input-group date"> <input type="text" class="form-control" id="schedule_due_date" name="schedule_due_date" data-label="Reminder time" placeholder="YYYY-MM-DD HH:II" value="" /> <span class="input-group-addon"> <label style="margin-bottom:0px;" for="schedule_due_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label> </span> </div></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"> <label for="schedule_status">Status<sup>*</sup></label> <select id="schedule_status" name="schedule_status" class="form-control" size="1"> <?php $Schedule = new Schedule(0); echo $Schedule->getOptions(1); ?> </select> </div></div>';
	bodyHtml +='</div>';	
	
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="customer_email">Related Customer email (if any)<sup></sup></label><input class="form-control" id="customer_email" name="customer_email" maxlength="50" placeholder="Enter customer Email id"  onkeyup="getDropdown(this, \'Customer<=>customer_email\',false)" type="email" value=""></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"> <label class="form-control-label">Access Scope</label> <div class="col-md-12"> <div class="row"> <label class="radio-inline" for="inline-radio1"> <input id="inline-radio1" name="schedule_scope" checked class="schedule_scope" value="Public" type="radio" > Public </label> &nbsp;  &nbsp; <label class="radio-inline" for="inline-radio2"> <input id="inline-radio2" name="schedule_scope" class="schedule_scope" value="Secure" type="radio" > Me & Tagged person </label> </div> </div> </div></div>';
	bodyHtml +='</div>';
	
	bodyHtml += '<div class="form-group todomentionbox"><textarea id="schedule_user_mention" name="schedule_user_mention" spellcheck="false" rows="1" class="form-control todomention mention-input-extra" placeholder="@Tag user here..."></textarea></div>';
	
	bodyHtml +='</div>';
	modal.Body(bodyHtml);
	modal.Footer('<button type="reset" class="btn btn-default" >Reset</button><button type="button" id="popupsubmit" onclick="addSchedule(\'schedule/addschedule\');" class="btn btn-success" >Save ToDo Reminder</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
	
	$("#schedule_due_date").datetimepicker({ format: "yyyy-mm-dd hh:ii", autoclose: true, todayBtn: true, fontAwesome : true  });
	
	$('.todomention').mentiony({
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

function openEmployeeVoting()
{
	var employee = <?php echo json_encode(Employee::getVotingList())?>;
	setPopup(0, "<i class='fa fa-user'></i> Employee of the month");
	var bodyHtml = `<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="row">										`;
	
								for(var i=0; i<employee.length; i++){
									bodyHtml +=`<div class="col-lg-2 col-md-4 col-sm-6"><div class="form-group"><input  type="radio" name="voteemployee" value="`+employee[i]['user_id']+`"  id="voteemployee`+employee[i]['user_id']+`" class="input-radio-hidden voting_user" /> <label for="voteemployee`+employee[i]['user_id']+`">
												<img class="img img-responsive" src="`+employee[i]['user_image']+`"  alt="`+employee[i]['user_fname']+`" /><center class="text-muted">`+employee[i]['user_fname']+`</center>
												</label></div></div>`;
								}
					bodyHtml +=`</div>					
							</div>`;
					bodyHtml +=`<div class="col-md-12">
									<label>Rating : <span class='rangevalue'>5</span> / 10</label>
									<div class="form-group todomentionbox">
										<input type="range" class="myrangeinput voting_points w-100" id="voting_points" value="10" name="voting_points" min="0" max="10">
									</div>
								</div>`;
			bodyHtml +=`</div>
					</div>`;
	modal.Body(bodyHtml);
	modal.Footer('<button type="button" id="popupsubmit" onclick="saveMonthVoting();" class="btn btn-success" >Save Voting</button>');
	modal.Show();
	
}
<?php 
echo "var votingStartDate = '".Voting::getCurrentMonthVotingStartDate()."';";
if(Voting::isCurrentMonthVotingStarted() && !Voting::isRated()){
	echo " openEmployeeVoting();";
}
?>

$(".myrangeinput").on('change', function(){
	$(this).parents().parents().find('span.rangevalue').html($(this).val());
});

function saveMonthVoting(){
	var voting_user = $(".voting_user:checked").val();
	if($(".voting_user:checked").length == 0){
		toastMessage("warning|Please select user");
		return false;
	}
	else{
		var data={	
				action		:	'system/savemonthvoting',
				voting_user_id 	: 	voting_user,
				voting_points :	$(".voting_points").val()
			};
		$.ajax({type:'POST', data:data, timeout: AJAX_REQUEST_MAX_TIME, url:sitePath+'ajax.php',	
			beforeSend: function(){
				//message("process|Saving your vote....", 0);
			},	
			success:function(output){			
				var context = $.parseJSON(output);
				if(context[0] == 200)
				{
					toastMessage(context[1]);
					modal.Body('<center><h3>Thank you for taking participation in <b>Employee of the month</b> </h3><br/><h5> Results will be announced on 1st day of next month midnight.</h5></center>');
					modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
				}
			},
			error: function(xmlhttprequest, textstatus, messagecontent) {
				toastMessage('danger|Somethig wents wrong. Please contact to developer');
			}
		});
	}
}
</script>
<div id="system_crash_refresh" style="position:fixed; z-index:99999999; top:0px; bottom:0px; left:0px; right:0px; background:rgba(0,0,0,0.95); display:none;">
  <div style="background:#FFF; border-radius:2px; max-width:500px; margin:10% auto; position:relative; padding:50px 50px;">
    <p style="text-align:center; color:#D83214; font-size:32px"><i class="fa fa fa-warning fa-md m-b-2"></i></p>
    <p style="text-align:center; font-size:18px">Ooops... Something happening bad.</p>
    <p style="text-align:center; font-size:16px">Please Refresh the page and start working again.</p>
    <p style="text-align:center; font-size:16px">
      <button type="button" onClick="location.reload()" class="btn btn-lg btn-success mt-1 mb-1"><i class="fa fa-refresh fa-md m-t-1"></i> Refresh Page</button>
    </p>
    <p style="text-align:center; color:#AF3E1D; font-size:12px">Note: If you are continue getting this error. Please contact to developer.</p>
	<p class="system_crash_error text-muted" style="font-size:11px">Note: If you are continue getting this error. Please contact to developer.</p>
  </div>
</div>
<div id="schedule_notif_box_container" style="position:fixed; z-index:2; right:0px; bottom:0px; max-width:320px; width:100%; margin-right:10px; margin-top:10px; margin-bottom:10px;"></div>
<?php if(isAdmin()){?>
<div id="loadlivechatuserContainer" class="loadlivechatuserContainer d-none">
  <div class="live_chat_header"> &nbsp;<i class="fa fa-circle-o faa-burst animated"></i> <b> &nbsp; Live Chat </b> <i id="changeLiveUserWindow"  class="pull-right fa fa-angle-down fa-2x"></i></div>
  <div style="scrollbar-width: thin; overflow-y:auto; max-height:100px; margin-right:-18px;display:inline-block; width:100%; ">
    <ul class="nav live_user_container" style="min-width:200px; display:inline;">
    </ul>
  </div>
</div>
<?php }?>
<div class="dynamic_notif"></div>
<div class="socket_connection_block"></div>
<script type="text/javascript">
function searchCode(){
	var code = $("#searchformbox").val();
	var data={	
				action:	'system/searchcode',
				code : code						
			};
	$.ajax({type:'POST', data:data, timeout: AJAX_REQUEST_MAX_TIME, url:sitePath+'ajax.php',	
		beforeSend: function(){
			message("process|Searching... Please wait.", 0);
		},	
		success:function(output){			
		    var context = $.parseJSON(output);
			if(context[0] == 200)
			{
				Redirect(context[2]);
			}
			else
			message(context[1] , 300);
		},
		error: function(xmlhttprequest, textstatus, messagecontent) {}
	});
	return false;
}

var last_position_update_time = Date.now();
var old_location_lat = null;
var old_location_lng = null;
function geo_success(position) {
	var new_location = {
				  lat: position.coords.latitude,
				  lng: position.coords.longitude
              };
	if(last_position_update_time + (10*60*1000) < Date.now())
	{
		last_position_update_time = Date.now();
		if(window.localStorage.getItem('old_location_lat') != new_location.lat || window.localStorage.getItem('old_location_lng') != new_location.lng)
		{
			old_location_lat = new_location.lat;
			old_location_lng = new_location.lng;
			window.localStorage.setItem('old_location_lat', new_location.lat);
			window.localStorage.setItem('old_location_lng', new_location.lng);
			var data={	
					action				:	'employee/updatemylocation',
					location_lat_lng 	: 	new_location						
				};
			$.ajax({type:'POST', data:data, timeout: AJAX_REQUEST_MAX_TIME, url:sitePath+'ajax.php',	
				beforeSend: function(){
					//message("process|Searching... Please wait.", 0);
				},	
				success:function(output){			
					var context = $.parseJSON(output);
					if(context[0] == 200)
					{
						toastMessage('success|System updated your current location');
					}
				},
				error: function(xmlhttprequest, textstatus, messagecontent) {}
			});
		} 
	}
}

function geo_error(error) {
	if(error.code == error.PERMISSION_DENIED){
  		//message("danger|Sorry, your location is not visible. Please enable location request to App");
		//navigator.permissions.revoke({name : 'geolocation'});
		//wpidCalback();
	}
}

var geo_options = {
  enableHighAccuracy: true, 
  maximumAge        : 0, 
  timeout           : 30000
};

function wpidCalback(){
	var wpid = navigator.geolocation.watchPosition(geo_success, geo_error, geo_options);
}
wpidCalback();
</script>
<div id="chase_customer_notification" class="chase_customer_notification d-none">
	<div class="ccn_header gradient_bg"><span class="ccn_text_heading">Customer schedule</span> <span class="schedule_chase_customer_count badge badge-info"></span> <a class="ccr_controll pull-right"><i class="fa fa-angle-up fa-fw"></i></a></div>
	<div class="ccn_body chase_customer_list_block" style="height:400px;"></div>
	<div class="ccn_footer p-1 text-center" style=" background-color:#fff"><a href="<?php echo $app->basePath('chasecustomer')?>" class="text-xs text-muted redirect">Click to manage all customer</a></div>
</div>

<div id="signinlogoffbox" class="" style="display:none; position:fixed; top:0px; bottom:0px; left:0px; right:0px; z-index:1049; background:#000;">
	<div class="" style="display:block; margin:10% auto; max-width:400px; ">
        
            <div class="col-sm-12" style="min-height:200px; ">
            	<div style="position:relative;padding:40px;">
                <div class="" style="margin-top:20px; margin-bottom:20px; text-align:center;">
                    <img style="" class="img-circle" src="<?=$app->imagePath($_SESSION['user_image'])?>" height="120px">
                    
                    
                </div>
                <div class="" style="margin-top:20px; margin-bottom:30px; font-size:20px; font-weight:600; color:#ccc; text-align:center;">
                <?=$_SESSION['user_fname']?> <?=$_SESSION['user_lname']?>
                </div>
                <div class="" style="margin-top:15px; margin-bottom:30px; color:#fff; text-align:center;">
                <a id="signinlogoff" class="btn btn-success btn-lg"><i class="fa fa-sign-in"></i> Clock in</a>
                </div>
                <div class="" style="margin-top:15px; margin-bottom:0px; color:#999; text-align:center;">
                Clock Out time <span id="log_off_time_passed_label"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="" style="position:fixed; bottom:0px; right:10px; z-index:1048; min-height:0px; width:310px;">
	<div class="" style="display:block; margin:0 auto;"> 
		<div class="row">
			<div  id="popupnotification" class="col-sm-12" style="min-height:00px;"></div>			
		</div>
    </div>
</div>

<script type="text/javascript">
var signOffTimeInterval = null;
function printLogOffTime(){
	$("#log_off_time_passed_label").text(fancyTimeFormat(logOffTimePassValue++));
}
if(window.history.forward(1) != null)
   window.history.forward(1);
   
window.addEventListener('focus', function (event) {
    //sendSocketMessage('Away from app window');
});

window.addEventListener('blur', function (event) {
    //sendSocketMessage('Joined app window');
});
</script>
<?php if(time() >= strtotime(date('Y')."-01-01") && strtotime(date('Y-m-d')) <= strtotime(date('Y')."-01-03")){ ?>
<script>bottomNotificationStack.push("🎉 Happy new year, <?php echo $_SESSION['user_fname']?> !");</script>
<script src="https://cdn.jsdelivr.net/npm/party-js@latest/bundle/party.min.js"></script>
<script>

$(document).ready(function(){
	party.settings.debug = false;
	document.body.addEventListener("click", function (e) {
		(Math.ceil(Math.random()*10)%2) ? party.confetti(e) : party.sparkles(e);
	});
	
});

</script>
<?php }?>