<?php
class StoreLocation extends DB{
	private static $storeLocation = array(
		array(
				"key" 	=>"UK" ,
				"name"	=> "United Kingdom",
			),
		array(	
				"key" 	=>"UAE",
				"name"	=> "United Arab Emirates",
			),
		array(	
				"key" 	=>"USA",
				"name"	=> "United State America",
			)
			
	);						
	public function getStoreLocationList()
	{
		return StoreLocation::$storeLocation;
	}
	
	public static function getStoreNameByKey($store_key)
	{
		$name = "N/A";
		foreach(StoreLocation::$storeLocation as $_location){
			if(trim(strtolower($store_key)) == trim(strtolower($_location['key']))){
				$name = $_location['name'];
			}
		}
		return $name;		
	}
	
	public function getStoreLocationOptions($store_key, $label = "Store")
	{
		$html = "<option value=\"\"> -- Select $label Location -- </option>";
		foreach(StoreLocation::$storeLocation as $_location)
			$html.= "<option ".($store_key == $_location['key'] ? "selected":"")." value=\"$_location[key]\">$_location[name]</option>";
		return $html;
	}
}
?>