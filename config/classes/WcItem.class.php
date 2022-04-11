<?php
class WcItem extends DB{
	public $id;
	public $key_id	=	"wci_id";
	public $table_name	=	"app_wc_item_master";
	public $status	=	"wci_status";	
	function __construct($wci_id=0){
				
		$this->id	=	$wci_id;
	}
	
		
	static function getItemSerializeType($wci_id){
		$sql="SELECT `wci_serialize_type` FROM `app_wc_item_master` WHERE `wci_id` = '$wci_id' LIMIT 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))
		{	
			$data = $dbc->db_fetch_assoc(true);
			return $data['wci_serialize_type'];
		}
		else
			return SERIALIZED;
	}
	
	function getOptionList(){
		$sql="SELECT `wci_id`, `wci_name` FROM `app_wc_item_master` WHERE 1 ORDER BY `wci_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$array_record = array();
		if($dbc->db_num_rows($result))	{	
			while($row = $dbc->db_fetch_assoc(true))
				$array_record[] = $row;
		}
		return $array_record;
	}
	
	function getList($wc_id = 0)
	{
		$sql="SELECT a.`wci_id`, a.`wci_type_id`, a.`wci_name`, a.`wci_container_type`,  a.`wci_serialize_type`, a.`wci_item_icon`, b.`wci_type_name` , GROUP_CONCAT(c.`wc_process_asset_code`) as wc_process_asset_code FROM `app_wc_item_master` as a INNER JOIN `app_wci_type_master` as b ON a.`wci_type_id` = b.`wci_type_id` LEFT JOIN `app_wc_process` as c ON (a.`wci_id` = c.`wc_process_item_id` AND c.`wc_process_wc_id` = '$wc_id' ) WHERE a.`wci_status` = '1' GROUP BY a.`wci_id` ORDER BY  a.`wci_name`";		
		$dbc 	= 	new DB();
		$result_array = array();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			while($row = $dbc->db_fetch_assoc(true))
			{
				array_push($result_array, $row);
			}
			return $result_array;
		}
		else
			return false;
	}
	
	function getItemsList($wc_id = 0)
	{
		$sql="SELECT a.`wci_id`, a.`wci_type_id`, a.`wci_name`, a.`wci_item_icon`, a.`wci_container_type`,  a.`wci_serialize_type`, b.`wcr_item_qty`, c.`wci_type_name`, count(d.`wc_process_id`) as process FROM `app_wc_item_master` as a
INNER JOIN `app_wc_record_items` as b ON a.`wci_id`= b.`wcr_item_id` 
INNER JOIN `app_wci_type_master` as c ON a.`wci_type_id` = c.`wci_type_id` 
LEFT JOIN `app_wc_process` as d ON (a.`wci_id` = d.`wc_process_item_id` AND d.`wc_process_wc_id` = b.`wc_id` )
WHERE a.`wci_status` = '1' AND b.`wc_id` = '$wc_id' GROUP BY a.`wci_id` ORDER BY  a.`wci_name`";		
		$dbc 	= 	new DB();
		$result_array = array();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			while($row = $dbc->db_fetch_assoc(true))
			{
				array_push($result_array, $row);
			}
			return $result_array;
		}
		else
			return false;
	}
	
	
	function add($wci_type_id, $wci_name, $wci_ewc_code, $wci_chemical_component, $wci_concentration, $wci_physical_form, $wci_hazard_codes, $wci_container_type, $wci_description, $wci_serialize_type, $wci_status){
	
		$sql= "INSERT INTO `app_wc_item_master`(`wci_type_id`, `wci_name`, `wci_ewc_code`, `wci_chemical_component`, `wci_concentration`, `wci_physical_form`, `wci_hazard_codes`, `wci_container_type`, `wci_description`, `wci_serialize_type`, `wci_created_date`, `wci_status`) VALUES ('$wci_type_id', '$wci_name', '$wci_ewc_code', '$wci_chemical_component', '$wci_concentration', '$wci_physical_form', '$wci_hazard_codes', '$wci_container_type', '$wci_description', '$wci_serialize_type', NOW(), '$wci_status')";		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
	function getRecords()
	{
		$sql="SELECT a.`wci_id`, a.`wci_type_id`, a.`wci_name`, a.`wci_ewc_code`, a.`wci_chemical_component`, a.`wci_concentration`, a.`wci_physical_form`, a.`wci_hazard_codes`, a.`wci_container_type`, a.`wci_description`, a.`wci_serialize_type`,  a.`wci_created_date`, a.`wci_status`, b.`wci_type_name` FROM `app_wc_item_master` as a INNER JOIN `app_wci_type_master` as b ON a.`wci_type_id` =  b.`wci_type_id` WHERE 1 ORDER BY b.`wci_type_name`, a.`wci_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<tr id=\"data_value_record_row_".$row['wci_id']."\">";
				$html.="<td>".$row['wci_name']."</td>";
				$html.="<td>".$row['wci_type_name']."[".$row['wci_serialize_type']."]"."</td>";
				$html.="<td>".$row['wci_hazard_codes']."</td>";
				$html.="<td>".$row['wci_ewc_code']."</td>";
				$html.="<td>".dateView($row['wci_created_date'])."</td>";
				$html.="<td class=\"problem_name_status\" data-value=\"$row[wci_status]\">".statusView($row['wci_id'], $row['wci_status'])."</td>";
			$html.="<td>"."<a data-title=\"Update #".$row['wci_name']." Item\" href=\"updatewcitem/".$row['wci_id']."\" type=\"button\" class=\"btn btn-secondary redirect\"><i class=\"fa fa-lightbulb-o\"></i>&nbsp; View</a>"."</td>";			
			$html.="</tr>";		
		}
		return $html;	
	}
	
	
	function getItemAttributes(){
		$sql="SELECT * FROM `app_wc_item_attributes_relation` AS a INNER JOIN `app_wc_item_attributes` AS b ON a.`attribute_relation_attribute_id` = b.`attribute_id` WHERE a.`attribute_relation_item_id` = '".$this->id."' AND a.`attribute_relation_status` = '1' ORDER BY b.`attribute_name`";	
		//echo $sql;die;
		$dbc 	= 	new DB();
		$result_array = array();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			while($row = $dbc->db_fetch_assoc(true))
			{
				array_push($result_array, $row);
			}			
		}
		return $result_array;
	}
	
	static function getAttributesOptions($option_attribute_id){
		$sql = "SELECT `option_id`, `option_value` FROM `app_wc_item_attribute_option` WHERE `option_attribute_id` = '$option_attribute_id' AND `option_status` = '1' ORDER BY `option_value`";
		$dbc 	= 	new DB();
		$result_array = array();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			while($row = $dbc->db_fetch_assoc(true))
			{
				$result_array[$row['option_id']] = $row['option_value'];
			}			
		}
		return $result_array;
	}
	
	function addAttributeOption($option_attribute_id, $option_value, $option_status = 1){
		$sql = "INSERT INTO `app_wc_item_attribute_option`(`option_attribute_id`, `option_value`, `option_status`) VALUES ('$option_attribute_id', '$option_value', '$option_status')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	function removeAttributeOption($option_attribute_id, $option_value){
		$sql = "DELETE FROM `app_wc_item_attribute_option` WHERE `option_attribute_id` = '$option_attribute_id' AND `option_value` = '$option_value'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getFilterAttributes(){
		$sql="SELECT a.`attribute_id`, a.`attribute_name`, GROUP_CONCAT(DISTINCT c.`values_data` ORDER BY c.`values_data`='No' ASC, c.`values_data`+0 ASC SEPARATOR '|^|') as options FROM `app_wc_item_attributes` AS a 
		INNER JOIN `app_wc_item_attributes_relation` AS b ON a.`attribute_id` = b.`attribute_relation_attribute_id` 
		LEFT JOIN `app_wc_item_attributes_values` as c ON a.`attribute_id` = c.`values_attribute_id`
		WHERE b.`attribute_relation_item_id` = '".$this->id."' AND a.`attribute_is_filtrer` = '1' GROUP BY a.`attribute_id` ORDER BY a.`attribute_name`";	
		//echo $sql;die;
		$dbc 	= 	new DB();
		$result_array = array();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			while($row = $dbc->db_fetch_assoc(true))
			{
				array_push($result_array, $row);
			}			
		}
		return $result_array;
		
	}
	
	
	function getCollectionItemAttributesValues($wc_process_id){
		$sql="SELECT * FROM `app_wc_item_attributes_relation` AS a 
		INNER JOIN `app_wc_item_attributes` AS b ON a.`attribute_relation_attribute_id` = b.`attribute_id` 
		LEFT JOIN `app_wc_item_attributes_values` as c ON a.`attribute_relation_id` = c.`values_realtion_id` AND a.`attribute_relation_item_id` = c.`values_item_id` AND a.`attribute_relation_attribute_id` = c.`values_attribute_id` AND c.`values_wc_process_id` = '".$wc_process_id."'
		WHERE a.`attribute_relation_item_id` = '".$this->id."' AND a.`attribute_relation_status` = '1' ORDER BY b.`attribute_element_type`, b.`attribute_name`";		
		$dbc 	= 	new DB();
		$result_array = array();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			while($row = $dbc->db_fetch_assoc(true))
			{
				array_push($result_array, $row);
			}			
		}
		return $result_array;
	}
	
	function addItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data){		
		$sql = "INSERT INTO `app_wc_item_attributes_values`(`values_wc_id`, `values_wc_process_id`, `values_realtion_id`, `values_item_id`, `values_attribute_id`, `values_data`) VALUES ('$values_wc_id', '$values_wc_process_id', '$values_realtion_id', '$values_item_id', '$values_attribute_id', '$values_data')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();		
	}
	
	function isExistItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id){
		$sql = "SELECT * FROM `app_wc_item_attributes_values` WHERE `values_wc_id` = '$values_wc_id' AND `values_wc_process_id` = '$values_wc_process_id' AND `values_realtion_id` = '$values_realtion_id' AND `values_item_id` = '$values_item_id' AND `values_attribute_id` = '$values_attribute_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows();		
	}
	
	function updateItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data){
		$sql = "UPDATE `app_wc_item_attributes_values` SET `values_data` = '$values_data' WHERE `values_wc_id` = '$values_wc_id' AND `values_wc_process_id` = '$values_wc_process_id' AND `values_realtion_id` = '$values_realtion_id' AND `values_item_id` = '$values_item_id' AND `values_attribute_id` = '$values_attribute_id'";
		//echo $sql;
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);	
	}
	
	static function removeProcessItemValues($values_wc_process_id){
		$sql = "DELETE FROM `app_wc_item_attributes_values` WHERE `values_wc_process_id` = '$values_wc_process_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);	
	}
}

class CollectionItemMedia extends DB{
	public $id;
	public $key_id		=	"image_id";
	public $table_name	=	"app_wc_process_images";
	public $image_process_code	=	"image_process_code";
	function __construct($image_id=0){
				
		$this->id	=	$image_id;
	}
	
	function getImageList($image_process_code){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `".$this->image_process_code."` = '$image_process_code'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$image_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$image_array[] = $row;
			}
			return $image_array;
		}
		return NULL;
	}
	
}
?>