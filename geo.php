<?php
include("setup.php");

$sql = "SELECT DISTINCT `customer_address_postcode` FROM `app_customer_address` WHERE `customer_address_geo_location` = '' AND `customer_address_postcode` !=''";
$dbc 	= 	new DB();
$locations = "";
$result	=	$dbc->db_query($sql);
$records = $dbc->db_num_rows($sql);
$total_position = $dbc->db_num_rows($sql);
echo "$total_position Position require to update<br/>";
if ($total_position > 0) {
	while ($row = $dbc->db_fetch_assoc(true)) {
		//$postcode = "DH1 2UD";
		$postcode = $row['customer_address_postcode'];
		$request = "https://api.postcodes.io/postcodes/" . $postcode;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $request);
		$result = curl_exec($ch);
		curl_close($ch);
		//$result = @file_get_contents($request);
		echo "Request $request completed with result " . json_encode($result) . "<br/>";
		$data = json_decode($result);
		if ($data && $data->status == 200) {
			$updata = "(" . $data->result->latitude . ", " . $data->result->longitude . ")";
			$sqlupdate = "UPDATE `app_customer_address` SET `customer_address_geo_location` = '$updata'  WHERE `customer_address_postcode` = '$row[customer_address_postcode]'";
			$dbc->db_query($sqlupdate);
			$counter++;
			echo "Position updated for $row[customer_address_postcode]<br/>";
		}
		usleep(10000);
	}
}
echo $counter . " Geo Position Updated.<br/>";
