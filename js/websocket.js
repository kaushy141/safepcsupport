var websocket = null;
var retryWait = 0;
var retryWiatInterval;
function sendSocketMessage(title, link){
	//var messageJSON.title;
    //var messageJSON.image;
    //var messageJSON.user;
    //var messageJSON.link;
	var messageJSON = {
					messageType : 'SUPPORT_SYSTEM_NOTIFICATION',
					user : userName,
					image : userImage,
					title : title,
					link : typeof(link) == 'undefined' ? null : link
					}	
	if(websocket)
	websocket.send(JSON.stringify(messageJSON));
}

function socketStatusMessage(statusMessage){
	$(".socket_connection_block").html(statusMessage);
}

function suspendRetryInterval(){
	clearInterval(retryWiatInterval);
	retryWiatInterval = null;
}

function retrySocketConnectionWait(){
	retryWait += 5;
	var waitCount = 0;
	retryWiatInterval = setInterval(function(){
		if(retryWait <= ++waitCount){		
			retrySocketConnection();
			suspendRetryInterval();
		}
		else{
			socketStatusMessage("<span class='text-info'><i class='fa fa-warning'></i> Retrying in "+(retryWait - waitCount)+"s.</span>");
		}
	}, 1000);
}


function retrySocketConnection(){
	socketStatusMessage("<span class='text-warning'><i class='fa fa-circle-o-notch fa-spin'></i> Connecting...</span>");
	websocket = new WebSocket("wss://live.safepcsupport.co.uk:8080/php-socket.php"); 
	websocket.onopen = function(event) { 
		retryWait = 0;
		socketStatusMessage("<span class='text-success'><i class='fa fa-check'></i> Connected</span>");		
	}
	websocket.onmessage = function(event) {
		var Data = JSON.parse(event.data);
		if(Data.message_type == 'SUPPORT_SYSTEM_NOTIFICATION'){
			pushUsersNotification(Data.message, 5000);
		}
	};

	websocket.onerror = function(event){
		socketStatusMessage("<span class='text-danger'><i class='fa fa-warning'></i> Socket closed.</span>");
		$.get( sitePath + 'php-socket.php', function( data ) {
		  //alert( data );
		});
	};
	websocket.onclose = function(event){
		socketStatusMessage("<span class='text-danger'><i class='fa fa-warning'></i> Connection Closed.</span>");		
		$.get('http://live.safepcsupport.co.uk/php-socket.php');
		retrySocketConnectionWait()
	};
}

$(document).ready(function(){
	//retrySocketConnection();	
});
