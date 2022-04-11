<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        </strong></div>
      <div class="card-block" style="height:100%; min-height:700px;">
        <div id="map" style="height:100%;"></div>
      </div>
    </div>
  </div>
</div>
</div>
 <script src="https://cdn.rawgit.com/googlemaps/js-marker-clusterer/gh-pages/src/markerclusterer.js">
</script>
<script>

      function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: {lat: 51.752022, lng: -1.257677}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: labels[i % labels.length]
          });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
          {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
		/*   
		var mcOptions = {
			gridSize: 50,
			maxZoom: 15
			};
		var markerCluster = new MarkerClusterer(map, [], mcOptions);
		*/
      }
	  
      var locations = [<?php echo CustomerAddress::getAddressGeoTracking()?>];
    </script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY; ?>&callback=initMap">
    </script> 
