<?php
class Country extends DB{
	public $id;
	public $key_id	=	"id";
	public $table_name	=	"app_countries_list";
	public $status	=	"flag";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function setDefault()
	{
		$sql="UPDATE `".$this->table_name."` SET `is_default` = '0' WHERE 1";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		
		$sql="UPDATE `".$this->table_name."` SET `is_default` = '1' WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);				
	}
	
	
	function getOptions($carrier_selected = null, $field_value = null)
	{
		global $app;
		$sql="SELECT * FROM `".$this->table_name."` ORDER BY `name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"\"> - Select Country - </option>";			
		while($row = $dbc->db_fetch_assoc()){				
			$html.="<option value=\"".$row[$field_value == null ? 'id':$field_value]."\" ".(($carrier_selected!=null?(($carrier_selected==$row['id'] || $carrier_selected==$row['name'] || $carrier_selected==$row['iso2'] || $carrier_selected==$row['iso3'])?"selected":""):( $row['is_default'] == 1 ? "selected":"")))." >".$row['name']."</option>";					
		}
		return $html;
	}	
	
	static function getValueByParameter($value, $from, $oldValue)
	{
		$sql="SELECT `$value` FROM `app_countries_list` WHERE `$from` = '$oldValue'";
		//echo $sql;die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows() > 0){
			$record = $dbc->db_fetch_assoc(true);
			return $record[$value];
		}
		else
			return null;
	}
}
?>