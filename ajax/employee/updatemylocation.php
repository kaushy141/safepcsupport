<?php
	$data = sanitizePostData($_POST);
	$location_id = 0 ;
	$location_user_id = $_SESSION['user_id'];
	$location_lat_lng = NULL;
	$loation_save_time = "";
	$loation_is_expired = 0;
	
	extract($data);	
	if($location_lat_lng != NULL && isset($_SESSION['user_id']) && $_SESSION['user_id'] != 0)
	{
		$location_lat_lng = json_encode($location_lat_lng);
		$location = new Location();
		$location->expireMyLocation($_SESSION['user_id']);
		$location_id = $location->insert(
				array(
						"location_user_id"=>$location_user_id,
						"location_lat_lng"=>$location_lat_lng,
						"loation_save_time"=>"NOW()",
						"loation_is_expired"=>$loation_is_expired
					)
				);
		echo json_encode(array("200",  "success|location updated", $location_id, $data));
	}
	else
		echo json_encode(array("300", "danger|Invalid location found."));

?>