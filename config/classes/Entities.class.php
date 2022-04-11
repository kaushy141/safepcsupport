<?php
class Entities extends DB{
	public $id;
	public $key_id	=	"entities_key";
	public $table_name	=	"app_core_entities_value";
	public $status	=	"entities_status";
	
	function __construct($entities_key=0){
				
		$this->id	=	$entities_key;
	}
	
	static function getEntitiesConstantsFilename(){
		return 'app_system_entity_constants.php';
	}

	static function getAllowedConstantsEntity(){
		$sql = "SELECT `entities_key`, `entities_values` FROM `app_core_entities_value` WHERE `entities_php_allowed` = '1'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$resultArray = array();
		if($dbc->db_num_rows() > 0 ){
			while($row = $dbc->db_fetch_assoc(true))
			$resultArray[] = $row;
		}		
		return $resultArray;
	}	
		
	static function isKeyExist($entities_key){
		$sql = "SELECT `entities_values` FROM `app_core_entities_value` WHERE `entities_key` = '$entities_key'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() > 0  ? true : false;
	}
	
	
	static function saveKeyValues($entities_key, $entities_values, $entities_js_allowed, $entities_php_allowed){	
		if(!Entities::isKeyExist($entities_key)){
			return Entities::setKeyValues($entities_key, $entities_values, $entities_js_allowed, $entities_php_allowed);
		}
		else{
			return Entities::updateKeyValues($entities_key, $entities_values, $entities_js_allowed, $entities_php_allowed);
		}
	}
	
	static function setKeyValues($entities_key, $entities_values, $entities_js_allowed, $entities_php_allowed){		
		$keyData = array(
			'entities_key' => $entities_key,
			'entities_values' => $entities_values,
			'entities_updated_date' => 'NOW()',
			'entities_updated_by' => getLoginId(),
			'entities_js_allowed' => $entities_js_allowed,
			'entities_php_allowed' => $entities_php_allowed,
			'entities_status' => 1
		);
		
		$entities 	= 	new Entities();
		return $entities->insert($keyData);
	}
	static function updateKeyValues($entities_key, $entities_values, $entities_js_allowed, $entities_php_allowed){		
		$keyData = array(
			'entities_values' => $entities_values,
			'entities_updated_date' => 'NOW()',
			'entities_updated_by' => getLoginId(),
			'entities_js_allowed' => $entities_js_allowed,
			'entities_php_allowed' => $entities_php_allowed,
			'entities_status' => 1
		);
		
		$entities = new Entities($entities_key);
		return $entities->update($keyData);
	}
		
	static function getKeyValues($entities_key){
		$sql = "SELECT `entities_values` FROM `app_core_entities_value` WHERE `entities_key` = '$entities_key'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows() > 0 ){
			$row = $dbc->db_fetch_assoc(true);
			return $row['entities_values'];
		}
		else
			return null;
	}
	
	static function isJsAllowed($entities_key){
		$sql = "SELECT `entities_key` FROM `app_core_entities_value` WHERE `entities_key` = '$entities_key' AND `entities_js_allowed` = '1'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() > 0  ? true : false;
	}
	
	static function isPhpAllowed($entities_key){
		$sql = "SELECT `entities_key` FROM `app_core_entities_value` WHERE `entities_key` = '$entities_key' AND `entities_php_allowed` = '1'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() > 0  ? true : false;
	}
}
?>