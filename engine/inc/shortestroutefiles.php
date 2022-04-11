<style>
.rout_map_steps_list {
	box-shadow: 1px 2px 5px #aaa;
	cursor: pointer;
	display: block;
	margin-bottom: 8px;
	padding: 8px;
	-webkit-transition-property: background; /* Safari */
	-webkit-transition-duration: .5s; /* Safari */
	transition-property: background;
	transition-duration: .5s;
}
.rout_map_steps_list:hover {
	background: #1B66E4;
	color: #FFF;
}
.way_point_html_box {
	padding: 20px 0px;
}
.way_point_html {
	padding: 10px 8px;
	border: 1px solid #eaeaea;
	margin-bottom: 3px;
}
.span_count{
border-radius: 50%;
padding: 3px 6px;}
.span_count_active{ 
background: #EA4335;
color: #FFF;
}.span_count_inactive{ 
background: #DDDDDD;
color: #CACACA;
}
#rout_map_steps{ margin-top:20px;}
</style>
<div class="row" id="collection_form_container">
  <div class="col-sm-12">
    <form id="managecollectionroute" name="managecollectionroute">
      <div class="card" id="card-detail-print">
        <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i>
          <?=$formHeading;?>
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="route_origin_point"><span class="span_count span_count_active" id="span_count_source">A</span> Source<sup>*</sup></label>
                <input class="form-control" id="route_origin_point" name="route_origin_point" placeholder="Enter Collection source" type="text" value="<?=isset($route_origin_point)?$route_origin_point:"";?>">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="route_destination_point"><span class="span_count" id="span_count_destination"></span> Final Destination<sup>*</sup></label>
                <input class="form-control" id="route_destination_point" name="route_destination_point" placeholder="Enter Collection destination" type="text" value="<?=isset($route_destination_point)?$route_destination_point:"";?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="" id=""> 
              <table class="table table-striped tbale-bordered">
              <?php
			  $i=0;
              foreach($wayPointsName as $wayName){
			  ?>
              	<tr>
                	<td><span class="span_count span_count_list">-</span></td>
                    <td>
                    <div>
                    	
                        <label class="switch switch-text switch-pill switch-success">
                            <input value="<?php echo htmlspecialchars($wayName);?>" value="<?php echo $i;?>" name="short_routh_path" class="switch-input short_routh_path" checked="checked" type="checkbox">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
    				</td>
                	<td><?php echo $wayName;?></td>
                </tr>
              <?php
			  $i++;
			  }
			  ?>
              </table>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="way_point_html_box" id="optimised_way_point">
              	<div class="way_point_html"><b><center>Initializing... Google Map</center></b></div>
              </div>
            </div>
          </div>
          <div class="row" id="route_info_box" style="display:none;">
            <div class="col-sm-12">
              <div class="form-group" id="rout_map_view" style="min-height:400px;background:#4F8DEC;"> </div>
            </div>
            
            <div class="col-sm-12" id="rout_map_steps"> <div class="way_point_html"></div> </div>
          </div>
          
        </div>
      </div>
      
    </form>
  </div>
</div>
<script>
var alphbetArray = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
  // This example displays an address form, using the autocomplete feature
  // of the Google Places API to help users fill in the information.

  // This example requires the Places library. Include the libraries=places
  // parameter when you first load the API. For example:
  // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

  var placeSearch, autocomplete;
 
  function initAutocomplete() {
	// Create the autocomplete object, restricting the search to geographical
	// location types.
	autocomplete = new google.maps.places.Autocomplete(
		/** @type {!HTMLInputElement} */(document.getElementById('wc_collection_via')),
		{types: ['geocode']});

	// When the user selects an address from the dropdown, populate the address
	// fields in the form.
	autocomplete.addListener('place_changed', fillInAddress);
	
	initMap();
	//$("#getmapsubmitbtn").click();
  }

  function fillInAddress() {
	// Get the place details from the autocomplete object.
	var place = autocomplete.getPlace();
	document.getElementById("wc_collection_via").value = place.formatted_address;
  }

  // Bias the autocomplete object to the user's geographical location,
  // as supplied by the browser's 'navigator.geolocation' object.
  function geolocate() {
	if (navigator.geolocation) {
	  navigator.geolocation.getCurrentPosition(function(position) {
		var geolocation = {
		  lat: position.coords.latitude,
		  lng: position.coords.longitude
		};
		var circle = new google.maps.Circle({
		  center: geolocation,
		  radius: position.coords.accuracy
		});
		autocomplete.setBounds(circle.getBounds());
	  });
	}
  }
  
function getRoutMapSteps(stepID)
{
	if(stepID!='')
	{
		var mapStep = DirectionRoute.legs[stepID].steps;
		var mapStepPanel = document.getElementById('rout_map_steps');
		mapStepPanel.innerHTML = '';
		
		// For each route, display summary information.
		for (var i = 0; i < mapStep.length; i++) {
		  var routeSegment = i + 1;
		  var stepData = mapStep[i];
		  mapStepPanel.innerHTML += '<div><b>Route Step: ' + routeSegment +'</b> '+stepData.instructions+'&nbsp; <b>'+stepData.maneuver+'</b>  <i class="fa fa-road" aria-hidden="true"></i> '+stepData.distance.text + ' &nbsp; <i class="fa fa-clock-o" aria-hidden="true"></i> : '+stepData.duration.text + ' &nbsp;</div>';
		}
	}
	else
	mapStepPanel.innerHTML='';
}


</script> 
<?php echo GOOGLE_MAP_API_SCRIPT;?> 
<script>
	var DirectionRoute;
      function initMap() {
		  $("#route_info_box").hide();
		var c = 1; 
		$(".short_routh_path:checked").parents("label").parents("div").parents("td").parents("tr").find(".span_count_list").each(function(index, element) {
			
            $(this).text(alphbetArray[c++]).addClass("span_count_active").removeClass("span_count_inactive");
        });
		$("#span_count_destination").text(alphbetArray[c++]).addClass("span_count_active");
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('rout_map_view'), {
          zoom: 13,
          center: {lat: 41.85, lng: -87.65}
        });
		//var marker = new google.maps.Marker({position: document.getElementById('route_origin_point').value, map: map});

        directionsDisplay.setMap(map);
		calculateAndDisplayRoute(directionsService, directionsDisplay);
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
		//var locationData =  [<?php //echo implode(",",$wayPoints)?>];
		var locationData =  [];
		$(".short_routh_path:checked").each(function(index, element) {
			locationData.push($(this).val());
        });
        var waypts = [];
        /*var checkboxArray = document.getElementById('wc_collection_via').value;*/
		if(locationData.length >0)
       {
          for(var i=0; i<locationData.length; i++) {
            waypts.push({
              location: locationData[i],
              stopover: true
            });
          }
	  }
	  $("#optimised_way_point").html('<div class="way_point_html"><b><center>Processing... Please wait</center></b></div>');

        directionsService.route({
          origin: document.getElementById('route_origin_point').value,
          destination: document.getElementById('route_destination_point').value,
          waypoints: waypts,
          optimizeWaypoints: true,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
			  //console.log(response);
			  $("#route_info_box").slideDown(100);
            directionsDisplay.setDirections(response);
            var route = response.routes[0];
			DirectionRoute = route;
			
			var waypoint_order = DirectionRoute.waypoint_order
			console.log(response);
			
			var wayPointHtml = "";
			
			
			
            
			//$("#rout_map_option_val").html('<option value=""> -- Select route segment -- </option>');
            // For each route, display summary information.
			var optimumRoute;
			var optimumRouteDistance=99999999999;
            for (var i = 0; i < route.legs.length; i++) {
			  if(route.legs[i].distance.value<optimumRouteDistance){
				  optimumRoute = i;
				  optimumRouteDistance = route.legs[i].distance.value;
			  }
              var routeSegment = i + 1;
			  
			  wayPointHtml += '<div id="rout_map_steps_id_'+i+'" data-value="'+i+'"  class="way_point_html rout_map_steps_list card-success text-white"><b> 0'+ (i+1)+ ' : </b>&nbsp;<i class="fa fa-road"></i> : '+route.legs[i].distance.text + ' &nbsp;<i class="fa fa-clock-o"></i> : '+route.legs[i].duration.text + '<br/><i class="fa fa-circle text-warning"></i> '+ route.legs[i].start_address + '<br/><i class="fa fa-circle text-danger"></i> '+ route.legs[i].end_address + '</div>';
            }
			$("#optimised_way_point").html(wayPointHtml);
			
			//$("#rout_map_steps_id_"+optimumRoute).addClass('card-success text-white');
			
          } else {
            window.alert('Directions request failed due to ' + status);
          }
		  
		  $(".rout_map_steps_list").click(function(){
			  //$("#rout_map_option_val").val($(this).attr('data-value'));
			  $(".rout_map_steps_list").each(function(index, element) {
                $(this).removeClass('card-success text-white');
              });
			  $(this).addClass('card-success text-white');
			  getRoutMapSteps($(this).attr('data-value'));
		  })
        });
      }
	  
	  $(document).ready(function(e) {
        $(".short_routh_path").on("click", function(){
			$(this).parents("label").parents("div").parents("td").parents("tr").find(".span_count_list").text("-").addClass("span_count_inactive").removeClass("span_count_active")
			initMap();
		});
		
    });
    </script>