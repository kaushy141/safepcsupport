<?php if(isset($wc_id) && $wc_id !=0) echo drawCollectionProcedure($wc_id);?>
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
</style>
<div class="row" id="collection_form_container">
  <div class="col-sm-12">
    <form id="managecollectionroute" name="managecollectionroute">
      <div class="card" id="card-detail-print">
        <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i> Collection Route Info <b><?php echo $wc_code; ?></b>
          <div class="card-actions"> <a data-title="Reload" title="Reload" href="<?php echo $app->siteUrl("collectionroute/$wc_id");?>"><i class="fa fa-history  fa-lg m-t-2"></i></a> </div>
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="wc_collection_source">Source<sup>*</sup></label>
                <input class="form-control" id="wc_collection_source" name="wc_collection_source" placeholder="Enter Collection source" type="text" value="<?=isset($wc_collection_source)?$wc_collection_source:"";?>">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="wc_collection_destination">Destination<sup>*</sup></label>
                <input class="form-control" id="wc_collection_destination" name="wc_collection_destination" placeholder="Enter Collection destination" type="text" value="<?=isset($wc_collection_destination)?$wc_collection_destination:"";?>">
              </div>
            </div>
          </div>
          
          <!--/row-->
          
          <div class="row">
            <div class="col-xs-8 col-md-8 col-sm-8 col-lg-8">
              <div class="form-group">
                <label for="wc_collection_via">Via<sup></sup></label>
                <input class="form-control" id="wc_collection_via" name="wc_collection_via" placeholder="Enter collection via" type="text" value="<?=isset($wc_collection_via)?$wc_collection_via:"";?>">
              </div>
            </div>
            <div class="col-xs-4 col-md-4 col-sm-4 col-lg-4">
              <div class="form-group" style="margin-top:24px;">
                <button type="button"  id="getmapsubmitbtn" class="btn btn-success btn-block"><i class="fa fa-check-circle fa-lg m-t-2 submission_handler_btn"></i> <span id="btn_action_name">
                <?=$btnText?>
                </span> </button>
              </div>
            </div>
          </div>
          
          <!--/row-->
          
          <div class="row">
            <div class="col-sm-8">
              <label for="wc_collection_map_image">Map<sup></sup></label>
              <div class="form-group" id="rout_map_view" style="min-height:400px;background:#4F8DEC;"> </div>
            </div>
            <div class="col-sm-4">
              <div class="row">
              <div class="col-sm-12">
              <div class="form-group" id="rout_map_option">
                <label for="rout_map_option_val">Route Options<sup>*</sup></label>
              	<select id="rout_map_option_val" onchange="getRoutMapSteps(this.value)" name="rout_map_option_val" class="form-control" size="1">
              </select>
              </div>
            </div>
              <div class="col-sm-12">
                <label for="wc_collection_dir_text">Route<sup></sup></label>
                <div class="form-group" id="rout_dir_view"  style="min-height:400px;background:#FFF; padding:5px;"> </div>
              </div>
              
              </div>
            </div>
          </div>
          
          <div class="row">
          		<div class="col-sm-12" id="rout_map_steps">
                </div>
          </div>
        </div>
      </div>
      <div class="card-footer"></div>
      <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"";?>"  />
      <input type="hidden" id="wc_id" name="wc_id" value="<?=isset($wc_id)?$wc_id:"0";?>"  />
    </form>
  </div>
</div>
<script>
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
	$("#getmapsubmitbtn").click();
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
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('rout_map_view'), {
          zoom: 6,
          center: {lat: 41.85, lng: -87.65}
        });
        directionsDisplay.setMap(map);

        document.getElementById('getmapsubmitbtn').addEventListener('click', function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        });
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        var waypts = [];
        var checkboxArray = document.getElementById('wc_collection_via').value;
		if(checkboxArray!="")
       
          if (checkboxArray!="") {
            waypts.push({
              location: checkboxArray,
              stopover: true
            });
          }

        directionsService.route({
          origin: document.getElementById('wc_collection_source').value,
          destination: document.getElementById('wc_collection_destination').value,
          waypoints: waypts,
          optimizeWaypoints: true,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
            var route = response.routes[0];
			DirectionRoute = route;
            var summaryPanel = document.getElementById('rout_dir_view');
            summaryPanel.innerHTML = '';
			$("#rout_map_option_val").html('<option value=""> -- Get Route Step -- </option>');
            // For each route, display summary information.
			var optimumRoute;
			var optimumRouteDistance=99999999999;
            for (var i = 0; i < route.legs.length; i++) {
			  if(route.legs[i].distance.value<optimumRouteDistance){
				  optimumRoute = i;
				  optimumRouteDistance = route.legs[i].distance.value;
			  }
              var routeSegment = i + 1;
              summaryPanel.innerHTML += '<div data-value="'+i+'" class="rout_map_steps_list" id="rout_map_steps_id_'+i+'"><b>Route : ' + routeSegment +'</b> - &nbsp;'+ route.legs[i].start_address + ' to '+ route.legs[i].end_address + '<br>Distance : '+route.legs[i].distance.text + ' &nbsp;Time : '+route.legs[i].duration.text + '</div>';
			  $("#rout_map_option_val").append('<option value="'+i+'">'+route.legs[i].distance.text + ' &nbsp;'+route.legs[i].duration.text + ' &nbsp;'+route.legs[i].start_address +'</option>');
            }
			
			$("#rout_map_steps_id_"+optimumRoute).addClass('card-success text-white');
			
          } else {
            window.alert('Directions request failed due to ' + status);
          }
		  
		  $(".rout_map_steps_list").click(function(){
			  $("#rout_map_option_val").val($(this).attr('data-value'));
			  $(".rout_map_steps_list").each(function(index, element) {
                $(this).removeClass('card-success text-white');
              });
			  $(this).addClass('card-success text-white');
			  getRoutMapSteps($(this).attr('data-value'));
		  })
        });
      }
    </script>