<style>
.map_user_pointer{float:left; cursor:pointer; padding:5px; background-color: #C0ECAE; margin: 8px -8px; }
.map_user_pointer_image{ float:left; padding-left:0px;}
.map_user_pointer_image img{ height:40px;}
.map_user_pointer_detail{float:right; padding: 0px 6px;}
.map_user_pointer_track{text-align:center;}
.user_tracking{ background:#2e7d32;}
</style>
<div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-2">
<p id="employee_location_block_viewer" class="alert alert-info text-center"><a style="cursor:pointer">Click to view Employee location track</p>
</div>
<div id="employee_location_block" class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-2 hide">
	<div class="form-group" id="rout_map_view" style="min-height:400px;background:#4F8DEC;"></div>
	<div class="col-md-12">
		<div class="row"  id="rout_map_view_user">
		</div>
	</div>
</div>
<script type="text/javascript">
var map;
var infowindow;    
var locationArray = null;
var isMapLoaded = false;
var isMapDrawed = false;
function initAutocomplete() 
{
	map = new google.maps.Map(document.getElementById('rout_map_view'), {
	  //center: {lat: -34.397, lng: 150.644},
	  zoom: 6
	});
	
	if(navigator.geolocation) 
	{
	  navigator.geolocation.getCurrentPosition(function(position) {
		console.log(position.timestamp);
		var pos = {
		  lat: position.coords.latitude,
		  lng: position.coords.longitude
		};
		infoWindow = new google.maps.InfoWindow;       
		infoWindow.setPosition(pos);
		infoWindow.setContent("<div style='float:left'><img style='height:30px; border-radious:50%' src='<?php echo getResizeImage($_SESSION['user_image'],50)?>'></div><div style='float:right; padding: 0px 6px;'><b><?php echo $_SESSION['user_fname']?></b><br/><?php echo date("h:mA T")?></div>");
		infoWindow.open(map);
		map.setCenter(pos);
		isMapLoaded = true;
		getUserLocation();
	  }, function() {
		infoWindow = new google.maps.InfoWindow;
		handleLocationError(true, infoWindow, map.getCenter());
	  });	  
	}
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
      }


function getUserLocation(){
	var d = new Date();
	var timezone = d.getTimezoneOffset();
	var data={
		action			:	"employee/getalluserlocation",
		timezone		:	timezone
	};	
	$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
			beforeSend: function(){
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				if(JSON.stringify(arr[2]) != JSON.stringify(locationArray) || !isMapDrawed)
				{
					drawAllUser(arr[2]);
					locationArray = arr[2];
				}
				
			}
		}
	});
}

function trackuser(user_id){
	var data={
		action			:	"employee/getusertrackingroot",
		user_id			:	user_id
	};	
	$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
			beforeSend: function(){
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				if(JSON.stringify(arr[2]) != JSON.stringify(locationArray) || !isMapDrawed)
				{
					drawUserRoot(arr[2]);
				}
				
			}
		}
	});
}
var flightPath;
var is_tracking = false;
function drawUserRoot(rootArray){
	var flightPlanCoordinates = [];
	if(rootArray.length > 0)
	{
		if(typeof flightPath != 'undefined')
		flightPath.setMap(null);
		for(var i=0; i<rootArray.length; i++)
		{
			var latLng = $.parseJSON(rootArray[i].location_lat_lng);			
			flightPlanCoordinates.push({ lat: parseFloat(latLng.lat), lng: parseFloat(latLng.lng) });
		}
		  flightPath = new google.maps.Polyline({
          path: flightPlanCoordinates,
          geodesic: true,
          strokeColor: '#FF0000',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        flightPath.setMap(map);
	}        
}


var dataMarkerArray = [];
function drawAllUser(locationArray){
	if(isMapLoaded && locationArray != null && locationArray.length > 0)
	{
		dataLatLngPosition = [];
		dataMarkerArray = [];
		isMapDrawed = true;
		map = new google.maps.Map(document.getElementById('rout_map_view'), {
			//center: {lat: -34.397, lng: 150.644},
			zoom: 9,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		var rout_map_view_user_html = '';
		var infowindow = new google.maps.InfoWindow();
	 	var bounds = new google.maps.LatLngBounds();
		$("#rout_map_view_user").html('');
		for( var i=0; i<locationArray.length; i++)
		{
			var posData = locationArray[i];		
			var latLng = $.parseJSON(posData.location_lat_lng);
			//console.log(latLng);
			var pos = {
				  lat: parseFloat(latLng.lat),
				  lng: parseFloat(latLng.lng)
				};
			
			//console.log(pos);
			/*infoWindow = new google.maps.InfoWindow;       
			infoWindow.setPosition(pos);
			infoWindow.setContent();*/
			
			var marker = new google.maps.Marker({
				position: pos,
				map: map
			  });
			var rout_map_view_user_html = "<div data-marker='"+(i)+"' id='map_user_pointer_"+locationArray[i].user_id+"'  class='col-md-2 col-sm-4 col-xs-6'><div class='row map_user_pointer'><div class='col-xs-4 map_user_pointer_image'><img src='"+locationArray[i].user_image+"'></div><div class='col-xs-8 map_user_pointer_detail'><b>"+locationArray[i].user_fname+"</b><br/>"+locationArray[i].loation_save_time_view+"</div><div style='clear:both;'></div><div class='col-xs-12'><div class='map_user_pointer_track' data-tracking='0' data-user-id='"+locationArray[i].user_id+"'>Track</div></div></div></div>";
			$("#rout_map_view_user").append(rout_map_view_user_html);
			bounds.extend(marker.position);
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {	
					infowindow.setContent("<div style='float:left'><img style='height:30px;' src='"+locationArray[i].user_image+"'></div><div style='float:right; padding: 0px 6px;'><b>"+locationArray[i].user_fname+"</b><br/>"+locationArray[i].loation_save_time_view+"</div>");
					map.panTo(this.getPosition());
					map.setZoom(18);
                	//map.setCenter(this.getPosition(), 18);
					infowindow.open(map, marker);
					//map.checkResize();
				}
			})(marker, i));
			dataMarkerArray.push(marker);
			
		}
	 
		map.fitBounds(bounds);
		//(optional) restore the zoom level after the map is done scaling
		/*var listener = google.maps.event.addListener(map, "idle", function () {
			map.setZoom(7);
			google.maps.event.removeListener(listener);
		});	*/
		$(".map_user_pointer").on("click", function(){
			var itm = $(this).parent("div").attr('data-marker');
			new google.maps.event.trigger( dataMarkerArray[itm], 'click' );			
		});
		
		$(".map_user_pointer_track").on("click", function(){
			
			if($(this).attr('data-tracking') == 0)
			{
				$(this).html('Stop Track');
				$(this).addClass('user_tracking');
				trackuser($(this).attr('data-user-id'));
				$(this).attr('data-tracking', 1);	
			}
			else
			{
				$(this).html('Track');
				$(this).removeClass('user_tracking');
				$(this).attr('data-tracking', 0);
				if(typeof flightPath != 'undefined')
				flightPath.setMap(null);
			}
					
		});
		//map.setZoom(9);
	}	
}
$(document).ready(function(e) {
    getUserLocation();
	setInterval(getUserLocation, 30000);
	
	$("#employee_location_block_viewer").on("click", function(){
		$("#employee_location_block").removeClass('hide');
		$(this).slideUp();
	})
});
</script>
<?php echo GOOGLE_MAP_API_SCRIPT;?> 