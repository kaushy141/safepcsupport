<?php if (isset($wc_id) && $wc_id != 0) echo drawCollectionProcedure($wc_id); ?>
<?php
$longitude = $latitude = null;
$nearByCustomerList = [];
if ($customer_address_geo_location == "") {
  $sql = "SELECT DISTINCT * FROM `app_customer_address` WHERE (`customer_address_geo_location` = '') AND `customer_address_id` ='$wc_customer_address_id'";
  //echo $sql;
  $dbc   =   new DB();
  $locations = "";
  $result  =  $dbc->db_query($sql);
  $records = $dbc->db_num_rows($sql);

  if ($records > 0) {
    $address = $dbc->db_fetch_assoc(true);
    //print_r($address);
    $postcode = $address['customer_address_postcode'];
    $request = "https://api.postcodes.io/postcodes/" . $postcode;

    $options = array(
      CURLOPT_RETURNTRANSFER => true,   // return web page
      CURLOPT_HEADER         => false,  // don't return headers
      CURLOPT_FOLLOWLOCATION => true,   // follow redirects
      CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
      CURLOPT_ENCODING       => "",     // handle compressed
      CURLOPT_USERAGENT      => "test", // name of client
      CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
      CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
      CURLOPT_TIMEOUT        => 120,    // time-out on response
    );
    $ch = curl_init($request);
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    //echo $result;
    curl_close($ch);
    $data = json_decode($result, true);
    //var_dump($data);
    if (
      $data['status'] == 200
    ) {
      $latitude =  $data['result']['latitude'];
      $longitude = $data['result']['longitude'];
      $customer_address_geo_location = "(" . $latitude . ", " . $longitude . ")";
      $sqlupdate = "UPDATE `app_customer_address` SET `customer_address_geo_location` = '$customer_address_geo_location'  WHERE `customer_address_postcode` = '$postcode'";
      $dbc->db_query($sqlupdate);
    } else { ?>
      <div class="alert alert-warning"><?= $data['error'] ?> - <b><?= $postcode ?></b></div>
<?php }
  }
} else {
  $latlng = str_replace(")", "", str_replace("(", "", $customer_address_geo_location));
  //echo $latlng;
  $latlngArr = explode(",", $latlng);
  //print_r($latlngArr);
  $latitude =  $latlngArr[0];
  $longitude = $latlngArr[1];
}

if ($latitude != null && $longitude != null) {
  $radius = 5 * 1000; //Meter
  $nearByApiUrl =  "https://api.postcodes.io/postcodes/lon/{$longitude}/lat/{$latitude}?radius={$radius}";
  $options = array(
    CURLOPT_RETURNTRANSFER => true,   // return web page
    CURLOPT_HEADER         => false,  // don't return headers
    CURLOPT_FOLLOWLOCATION => true,   // follow redirects
    CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
    CURLOPT_ENCODING       => "",     // handle compressed
    CURLOPT_USERAGENT      => "test", // name of client
    CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
    CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
    CURLOPT_TIMEOUT        => 120,    // time-out on response
  );
  $ch = curl_init($nearByApiUrl);
  curl_setopt_array($ch, $options);
  $result = curl_exec($ch);
  //echo $result;
  curl_close($ch);
  $dataNearBy = json_decode($result, true);


  if (
    $dataNearBy['status'] == 200
  ) {
    $nearByPostcodes = [];
    if (count($dataNearBy['result'])) {
      foreach ($dataNearBy['result'] as $loc) {
        array_push($nearByPostcodes, str_replace(" ", "", $loc['postcode']));
      }


      $sql = "SELECT `app_customer_address`.*, `app_customer`.* FROM `app_customer_address` INNER JOIN `app_customer` ON `app_customer_address`.`customer_id`= `app_customer`.`customer_id` WHERE REPLACE(`customer_address_postcode`, ' ','') IN ('" . implode("', '", $nearByPostcodes) . "')  AND `customer_address_id` !='$wc_customer_address_id'";
      // echo $sql; 
      $dbc   =   new DB();
      $result  =  $dbc->db_query($sql);
      $records = $dbc->db_num_rows($sql);

      if ($records > 0) {
        while ($customer = $dbc->db_fetch_assoc(true)) {
          $nearByCustomerList[] = $customer;
        }
      }
    }
  }
}

// echo "<pre>";
// print_r($nearByCustomerList);
// echo "</pre>";
?>
<style>
  .rout_map_steps_list {
    box-shadow: 1px 2px 5px #aaa;
    cursor: pointer;
    display: block;
    margin-bottom: 8px;
    padding: 8px;
    -webkit-transition-property: background;
    /* Safari */
    -webkit-transition-duration: .5s;
    /* Safari */
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
    <form id="managecollectionnearby" name="managecollectionroute">
      <div class="card" id="card-detail-print">
        <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i> Near by customers <b><?php echo $wc_code; ?></b>

        </div>
        <div class="card-block">
          <div class="row">

          </div>

          <!--/row-->



          <!--/row-->

          <div class="row">
            <div class="col-sm-8">
              <div class="form-group" id="rout_map_view" style="min-height:400px;background:#4F8DEC;"> </div>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">

                </div>
                <div class="col-sm-12">
                  <label for="wc_collection_dir_text">Nearby available customer<sup></sup></label>
                  <div class="form-group" id="near_by_customer_box" style="min-height:400px;background:#FFF; padding:5px;">
                    <ul class="list-group">

                      <?php
                      if (count($nearByCustomerList) > 0) {
                        foreach ($nearByCustomerList as $customer) {
                      ?>
                          <li class="list-group-item rounded">
                            <p class="mb-1">Name: <?php echo $customer['customer_fname'] ?> <?php echo $customer['customer_lname'] ?></p>
                            <p class="mb-1">Email: <?php echo $customer['customer_email'] ?></p>
                            <p class="mb-1">Phone: <?php echo $customer['customer_phone'] ?></p>

                            <p>Address: <?php echo $customer['customer_address_street_number'] ?>, <?php echo $customer['customer_address_route'] ?> <?php echo $customer['customer_address_locality'] ?> <?php echo $customer['customer_address_administrative_area'] ?> <?php echo $customer['customer_address_country'] ?> - <?php echo $customer['customer_address_postcode'] ?></p>
                            <p><a target="_blank" href="http://maps.google.com/?q=<?php echo $customer['customer_address_street_number'] ?>, <?php echo $customer['customer_address_route'] ?> <?php echo $customer['customer_address_locality'] ?> <?php echo $customer['customer_address_administrative_area'] ?> <?php echo $customer['customer_address_country'] ?> - <?php echo $customer['customer_address_postcode'] ?>" class="btn btn-primary"><i class="fa fa-directions"></i> Direction</a></p>
                          </li>
                        <?php
                        }
                      } else {
                        ?>
                        <div class="text-center text-muted">No nearby customer available.</div>
                      <?php
                      }
                      ?>
                    </ul>
                  </div>
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
      <input type="hidden" id="action" name="action" value="<?= isset($action) ? $action : ""; ?>" />
      <input type="hidden" id="wc_id" name="wc_id" value="<?= isset($wc_id) ? $wc_id : "0"; ?>" />
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
      /** @type {!HTMLInputElement} */
      (document.getElementById('wc_collection_via')), {
        types: ['geocode']
      });

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

  function getRoutMapSteps(stepID) {
    if (stepID != '') {
      var mapStep = DirectionRoute.legs[stepID].steps;
      var mapStepPanel = document.getElementById('rout_map_steps');
      mapStepPanel.innerHTML = '';

      // For each route, display summary information.
      for (var i = 0; i < mapStep.length; i++) {
        var routeSegment = i + 1;
        var stepData = mapStep[i];
        mapStepPanel.innerHTML += '<div><b>Route Step: ' + routeSegment + '</b> ' + stepData.instructions + '&nbsp; <b>' + stepData.maneuver + '</b>  <i class="fa fa-road" aria-hidden="true"></i> ' + stepData.distance.text + ' &nbsp; <i class="fa fa-clock-o" aria-hidden="true"></i> : ' + stepData.duration.text + ' &nbsp;</div>';
      }
    } else
      mapStepPanel.innerHTML = '';
  }
</script>
<?php echo GOOGLE_MAP_API_SCRIPT; ?>
<script>
  var DirectionRoute;

  function initMap() {
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;
    var infoWindow = new google.maps.InfoWindow();
    const pos = {
      lat: <?php echo $latitude ? $latitude : 41.85 ?>,
      lng: <?php echo $longitude ? $longitude : -87.65 ?>
    };
    var map = new google.maps.Map(document.getElementById('rout_map_view'), {
      zoom: 6,
      center: pos,
      zoom: 14,

    });

    infoWindow.setPosition(pos);
    infoWindow.setHeaderContent("Collection location");
    infoWindow.setContent("Customer: <?php echo $customer_fname ?> <?php echo $customer_lname ?>");
    infoWindow.open(map);
    map.setCenter(pos);


    new google.maps.Marker({
      position: pos,
      map,
      title: "Collection Point"
    });


    directionsDisplay.setMap(map);

    document.getElementById('getmapsubmitbtn').addEventListener('click', function() {
      calculateAndDisplayRoute(directionsService, directionsDisplay);
    });
  }

  function calculateAndDisplayRoute(directionsService, directionsDisplay) {
    var waypts = [];
    var checkboxArray = document.getElementById('wc_collection_via').value;
    if (checkboxArray != "")

      if (checkboxArray != "") {
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
        var optimumRouteDistance = 99999999999;
        for (var i = 0; i < route.legs.length; i++) {
          if (route.legs[i].distance.value < optimumRouteDistance) {
            optimumRoute = i;
            optimumRouteDistance = route.legs[i].distance.value;
          }
          var routeSegment = i + 1;
          summaryPanel.innerHTML += '<div data-value="' + i + '" class="rout_map_steps_list" id="rout_map_steps_id_' + i + '"><b>Route : ' + routeSegment + '</b> - &nbsp;' + route.legs[i].start_address + ' to ' + route.legs[i].end_address + '<br>Distance : ' + route.legs[i].distance.text + ' &nbsp;Time : ' + route.legs[i].duration.text + '</div>';
          $("#rout_map_option_val").append('<option value="' + i + '">' + route.legs[i].distance.text + ' &nbsp;' + route.legs[i].duration.text + ' &nbsp;' + route.legs[i].start_address + '</option>');
        }

        $("#rout_map_steps_id_" + optimumRoute).addClass('card-success text-white');

      } else {
        window.alert('Directions request failed due to ' + status);
      }

      $(".rout_map_steps_list").click(function() {
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