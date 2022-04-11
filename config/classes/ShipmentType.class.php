<?php
class ShipmentType extends DB{
	public $id;
	public $key_id	=	"shipment_type_id";
	public $table_name	=	"app_shipment_types";
	public $status	=	"shipment_type_status";
	private $defaultService = array();
		
	function __construct($id=0){		
		$this->id	=	$id;
		$this->defaultService = array(
			'UPS' => '11',
			'DPD' => '1^12',
			'FDX' => 'FEDEX_NEXT_DAY_END_OF_DAY'
		);
	}
	
	function getServicesList($origin='UPS'){
		$serviceNames = array(
	'UPS'=>array(
        '01' => 'UPS Next Day Air',
        '02' => 'UPS 2nd Day Air',
        '03' => 'UPS Ground',
        '07' => 'UPS Worldwide Express',
        '08' => 'UPS Worldwide Expedited',
        '11' => 'UPS Standard',
        '12' => 'UPS 3 Day Select',
        '13' => 'UPS Next Day Air Saver',
        '14' => 'UPS Next Day Air Early',
        '54' => 'UPS Worldwide Express Plus',
        '59' => 'UPS 2nd Day Air A.M.',
        '65' => 'UPS Worldwide Saver',
        '70' => 'UPS Access Point Economy',
        '71' => 'UPS Worldwide Express Freight Midday',
        '74' => 'UPS Express 12:00',
        '82' => 'UPS Today Standard',
        '83' => 'UPS Today Dedicated Courrier',
        '85' => 'UPS Today Express',
        '86' => 'UPS Today Express Saver',
        '96' => 'UPS Worldwide Express Freight',
        '59' => 'UPS Second Day Air AM',
        '65' => 'UPS Saver',
        '70' => 'UPS Access Point Economy',
        '74' => 'UPS Express 12:00',
        '93' => 'UPS Sure Post',
		),
	'DPD' => array(
		"1^01"=>"PARCEL SUNDAY",
		"1^03"=>"SWAPIT SUNDAY",
		"1^04"=>"SWAPIT SUNDAY 12=>00",
		"1^06"=>"FREIGHT PARCEL SUNDAY",
		"1^08"=>"PALLET SUNDAY",
		"1^09"=>"EXPRESSPAK SUNDAY",
		"1^11"=>"DPD TWO DAY",
		"1^12"=>"DPD NEXT DAY",
		"1^13"=>"DPD 12=>00",
		"1^14"=>"DPD 10=>30",
		"1^15"=>"PARCEL TIMED",
		"1^16"=>"PARCEL SATURDAY",
		"1^17"=>"PARCEL SATURDAY 12=>00",
		"1^18"=>"PARCEL SATURDAY 10=>30",
		"1^21"=>"HOME DELIVERY EVENING",
		"1^22"=>"PARCEL RETURN TO SHOP",
		"1^25"=>"HOME DELIVERY AFTERNOON",
		"1^27"=>"CONTRACTPAK EVENING",
		"1^29"=>"PARCEL SUNDAY 12=>00",
		"1^31"=>"FREIGHT PARCEL SUNDAY 12=>00",
		"1^32"=>"EXPRESSPAK DPD NEXT DAY",
		"1^33"=>"EXPRESSPAK DPD 12=>00",
		"1^34"=>"EXPRESSPAK DPD 10=>30",
		"1^35"=>"EXPRESSPAK TIMED",
		"1^36"=>"EXPRESSPAK SATURDAY",
		"1^37"=>"EXPRESSPAK SATURDAY 12=>00",
		"1^38"=>"EXPRESSPAK SATURDAY 10=>30",
		"1^41"=>"SWAPIT DPD TWO DAY",
		"1^42"=>"SWAPIT DPD NEXT DAY",
		"1^43"=>"SWAP IT DPD 12=>00",
		"1^44"=>"SWAPIT DPD 10=>30",
		"1^45"=>"SWAP IT TIMED",
		"1^46"=>"SWAPIT SATURDAY",
		"1^47"=>"SWAPIT SATURDAY 12=>00",
		"1^48"=>"SWAPIT SATURDAY 10=>30",
		"1^49"=>"SWAPIT AFTERNOON",
		"1^51"=>"EXPRESSPAK SUNDAY 12=>00",
		"1^52"=>"RETURN TO CONSIGNOR",
		"1^53"=>"SWAPIT EVENING",
		"1^55"=>"REVERSE IT DPD NEXT DAY",
		"1^56"=>"REVERSE IT DPD TWO DAY",
		"1^62"=>"DESK TO DESK DPD NEXT DAY",
		"1^63"=>"DESK TO DESK DPD 12=>00",
		"1^64"=>"DESK TO DESK DPD 10=>30",
		"1^66"=>"DESK TO DESK SATURDAY",
		"1^67"=>"DESK TO DESK SAT 12=>00",
		"1^68"=>"DESK TO DESK SATURDAY 10=>30",
		"1^69"=>"PALLET SUNDAY 12=>00",
		"1^71"=>"PALLET DPD TWO DAY",
		"1^72"=>"PALLET DPD NEXT DAY",
		"1^73"=>"PALLET DPD 12=>00",
		"1^74"=>"PALLET DPD 10=>30",
		"1^75"=>"PALLET TIMED",
		"1^76"=>"PALLET SATURDAY",
		"1^77"=>"PALLET SATURDAY 12=>00",
		"1^78"=>"PALLET SATURDAY 10=>30",
		"1^81"=>"FREIGHT PARCEL DPD TWO DAY",
		"1^82"=>"FREIGHT PARCEL DPD NEXT DAY",
		"1^83"=>"FREIGHT PARCEL DPD 12=>00",
		"1^84"=>"FREIGHT DPD 10=>30",
		"1^85"=>"FREIGHT PARCEL TIMED",
		"1^86"=>"FREIGHT PARCEL SATURDAY",
		"1^87"=>"FREIGHT PARCEL SATURDAY 12=>00",
		"1^88"=>"FREIGHT PARCEL SATURDAY 10=>30",
		"1^91"=>"PARCEL SHIP TO SHOP",
		"1^92"=>"CONTRACTPAK DPD NEXT DAY",
		"1^98"=>"Expak - Pickup Classic"
		),
	'FDX'=>array(
		"EUROPE_FIRST_INTERNATIONAL_PRIORITY"=>"Europe First International Priority",
		"FEDEX_1_DAY_FREIGHT"=>"Fedex 1 Day Freight",
		"FEDEX_2_DAY"=>"Fedex 2 Day",
		"FEDEX_2_DAY_AM"=>"Fedex 2 Day Am",
		"FEDEX_2_DAY_FREIGHT"=>"Fedex 2 Day Freight",
		"FEDEX_3_DAY_FREIGHT"=>"Fedex 3 Day Freight",
		"FEDEX_CARGO_AIRPORT_TO_AIRPORT"=>"Fedex Cargo Airport To Airport",
		"FEDEX_CARGO_FREIGHT_FORWARDING"=>"Fedex Cargo Freight Forwarding",
		"FEDEX_CARGO_INTERNATIONAL_EXPRESS_FREIGHT"=>"Fedex Cargo International Express Freight",
		"FEDEX_CARGO_INTERNATIONAL_PREMIUM"=>"Fedex Cargo International Premium",
		"FEDEX_CARGO_MAIL"=>"Fedex Cargo Mail",
		"FEDEX_CARGO_REGISTERED_MAIL"=>"Fedex Cargo Registered Mail",
		"FEDEX_CARGO_SURFACE_MAIL"=>"Fedex Cargo Surface Mail",
		"FEDEX_CUSTOM_CRITICAL_AIR_EXPEDITE"=>"Fedex Custom Critical Air Expedite",
		"FEDEX_CUSTOM_CRITICAL_AIR_EXPEDITE_EXCLUSIVE_USE"=>"Fedex Custom Critical Air Expedite Exclusive Use",
		"FEDEX_CUSTOM_CRITICAL_AIR_EXPEDITE_NETWORK"=>"Fedex Custom Critical Air Expedite Network",
		"FEDEX_CUSTOM_CRITICAL_CHARTER_AIR"=>"Fedex Custom Critical Charter Air",
		"FEDEX_CUSTOM_CRITICAL_POINT_TO_POINT"=>"Fedex Custom Critical Point To Point",
		"FEDEX_CUSTOM_CRITICAL_SURFACE_EXPEDITE"=>"Fedex Custom Critical Surface Expedite",
		"FEDEX_CUSTOM_CRITICAL_SURFACE_EXPEDITE_EXCLUSIVE_USE"=>"Fedex Custom Critical Surface Expedite Exclusive Use",
		"FEDEX_CUSTOM_CRITICAL_TEMP_ASSURE_AIR"=>"Fedex Custom Critical Temp Assure Air",
		"FEDEX_CUSTOM_CRITICAL_TEMP_ASSURE_VALIDATED_AIR"=>"Fedex Custom Critical Temp Assure Validated Air",
		"FEDEX_CUSTOM_CRITICAL_WHITE_GLOVE_SERVICES"=>"Fedex Custom Critical White Glove Services",
		"FEDEX_DISTANCE_DEFERRED"=>"Fedex Distance Deferred",
		"FEDEX_EXPRESS_SAVER"=>"Fedex Express Saver",
		"FEDEX_FIRST_FREIGHT"=>"Fedex First Freight",
		"FEDEX_FREIGHT_ECONOMY"=>"Fedex Freight Economy",
		"FEDEX_FREIGHT_PRIORITY"=>"Fedex Freight Priority",
		"FEDEX_GROUND"=>"Fedex Ground",
		"FEDEX_INTERNATIONAL_PRIORITY_PLUS"=>"Fedex International Priority Plus",
		"FEDEX_INTERNATIONAL_CONNECT_PLUS"=>"Fedex International Connect Plus",
		"FEDEX_NEXT_DAY_AFTERNOON"=>"Fedex Next Day Afternoon",
		"FEDEX_NEXT_DAY_EARLY_MORNING"=>"Fedex Next Day Early Morning",
		"FEDEX_NEXT_DAY_END_OF_DAY"=>"Fedex Next Day End Of Day",
		"FEDEX_NEXT_DAY_FREIGHT"=>"Fedex Next Day Freight",
		"FEDEX_NEXT_DAY_MID_MORNING"=>"Fedex Next Day Mid Morning",
		"FIRST_OVERNIGHT"=>"First Overnight",
		"GROUND_HOME_DELIVERY"=>"Ground Home Delivery",
		"INTERNATIONAL_DISTRIBUTION_FREIGHT"=>"International Distribution Freight",
		"INTERNATIONAL_ECONOMY"=>"International Economy",
		"INTERNATIONAL_ECONOMY_DISTRIBUTION"=>"International Economy Distribution",
		"INTERNATIONAL_ECONOMY_FREIGHT"=>"International Economy Freight",
		"INTERNATIONAL_FIRST"=>"International First",
		"INTERNATIONAL_GROUND"=>"International Ground",
		"INTERNATIONAL_PRIORITY"=>"International Priority",
		"INTERNATIONAL_PRIORITY_DISTRIBUTION"=>"International Priority Distribution",
		"INTERNATIONAL_PRIORITY_EXPRESS"=>"International Priority Express",
		"INTERNATIONAL_PRIORITY_FREIGHT"=>"International Priority Freight",
		"PRIORITY_OVERNIGHT"=>"Priority Overnight",
		"SAME_DAY"=>"Same Day",
		"SAME_DAY_CITY"=>"Same Day City",
		"SAME_DAY_METRO_AFTERNOON"=>"Same Day Metro Afternoon",
		"SAME_DAY_METRO_MORNING"=>"Same Day Metro Morning",
		"SAME_DAY_METRO_RUSH"=>"Same Day Metro Rush",
		"SMART_POST"=>"Smart Post",
		"STANDARD_OVERNIGHT"=>"Standard Overnight",
		"TRANSBORDER_DISTRIBUTION_CONSOLIDATION"=>"Transborder Distribution Consolidation"
		)

	);
		
		return isset($serviceNames[$origin]) ? $serviceNames[$origin] : array();
	}
	
	static function getServiceNameByCode($origin, $code){
		$shipmentType = new ShipmentType();
		$services = $shipmentType->getServicesList($origin);
		return $services[$code];
	}
	
	
	function getOptions($shipment_type_id=0){
		global $app;
		$sql="SELECT `shipment_type_id`, `shipment_type_name`, `shipment_type_image`, `shipment_type_color` FROM `app_shipment_types` WHERE `shipment_type_status` = 1 ORDER BY `shipment_type_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html = '';		
		while($row = $dbc->db_fetch_assoc()){				
			$html.="<option data-image=\"".$app->basePath($row['shipment_type_image'])."\"  data-color=\"$row[shipment_type_color]\" value=\"$row[shipment_type_id]\" ".(($shipment_type_id==$row['shipment_type_id'])?"selected":"")." >".$row['shipment_type_name']."</option>";					
		}
		return $html;
	}
	
	
	function getShipmetCodeOptions($shipmentType){
		$services = $this->getServicesList($shipmentType);
		$html = "<option value=\"\"> -- Select -- </option>";
		if($services)
		{
			$defaultService = isset($this->defaultService[$shipmentType]) ? $this->defaultService[$shipmentType]:'';
			foreach($services as $code => $value){				
				$html .= "<option ".($code == $defaultService ? "selected":"")." value=\"$code\">$value</option>";
			}
		}
		return $html;
	}
}
?>