<?php
class Values extends DB{
	public $id;
	public $key_id	=	"data_key";
	public $table_name	=	"app_miscellaneous_data";
	
	function __construct($data_key = ''){
				
		$this->id	=	$data_key;
	}
	
	static function isKeyExist($data_key){
		$sql = "SELECT `data_values` FROM `app_miscellaneous_data` WHERE `data_key` = '$data_key'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() > 0  ? true : false;
	}
	
	
	static function saveKeyValues($data_key, $data_values){	
		if(!Values::isKeyExist($data_key)){
			return Values::setKeyValues($data_key, $data_values);
		}
		else{
			return Values::updateKeyValues($data_key, $data_values);
		}
	}
	
	static function setKeyValues($data_key, $data_values){		
		$keyData = array(
			'data_key' => $data_key,
			'data_values' => $data_values
		);
		
		$Values 	= 	new Values();
		return $Values->insert($keyData);
	}
	static function updateKeyValues($data_key, $data_values){		
		$keyData = array(
			'data_values' => $data_values
		);
		
		$Values = new Values($data_key);
		return $Values->update($keyData);
	}
		
	static function getKeyValues($data_key){
		$sql = "SELECT `data_values` FROM `app_miscellaneous_data` WHERE `data_key` = '$data_key'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows() > 0 ){
			$row = $dbc->db_fetch_assoc(true);
			return $row['data_values'];
		}
		else
			return null;
	}
	
}
?>