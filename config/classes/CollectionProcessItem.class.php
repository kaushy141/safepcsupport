<?php
class CollectionProcessItem extends DB{
	public $id;
	public $key_id	=	"values_id";
	public $table_name	=	"app_wc_item_attributes_values";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	public function removeCollectionItemAttributeValues($values_wc_process_id){
		$sql = "DELETE FROM `app_wc_item_attributes_values` WHERE `values_wc_process_id` = '$values_wc_process_id'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
	}
	
	public function getDropdownList($fieldName, $keyword, $extra, $condition)
	{
		if($condition != "")
		$condition = "AND $condition";
		
		$sql="SELECT DISTINCT `values_data` FROM `app_wc_item_attributes_values` WHERE  `values_attribute_id` = '$fieldName' AND (LOWER(`values_data`) LIKE '$keyword%' OR LOWER(`values_data`) LIKE '$keyword%' OR LOWER(`values_data`) LIKE '$keyword%' ) $condition 
		ORDER BY LOWER(`values_data`) LIKE '$keyword', LOWER(`values_data`) LIKE '$keyword%', LOWER(`values_data`) LIKE '%$keyword%' LIMIT 10";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($result)
		if($dbc->db_num_rows()>0)
		{	
			while($row = $dbc->db_fetch_assoc(true))	
				$data[]	= array("label"=>$row['values_data'], "data-id"=>$row['values_data'], "value"=>$row['values_data'], "extra"=>$row);
		}
		return $data;
	}
	
	function getCollectionItemExport($param){
		$sql = "SELECT a.`wc_process_id`, a.`wc_process_item_id`, a.`wc_process_asset_code`, a.`wc_process_item_make`, a.`wc_process_item_model`, a.`wc_process_item_name`, a.`wc_process_item_sr_no`, b.`wci_name` FROM `app_wc_process` AS a LEFT JOIN `app_wc_item_master` AS b ON ( b.`wci_id` = a.`wc_process_item_id` ) $param[joins] WHERE 1 $param[conditions] GROUP BY a.`wc_process_asset_code` ORDER BY `wc_process_asset_code` ASC" ;
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))	
			{
				$data[] = $row;
			}
		}
		$outputArray = array();
		if(count($data))
		{
			foreach($data as $row)
			{				
				$outputArray[] = $this->getCollectionFullDetails($row);
			}
		}
		return $outputArray;
	}
	
	function getCollectionItemsByIds($ids){
		$products = "'".implode("', '", $ids)."'";		
		$sql = "SELECT a.`wc_process_id`, a.`wc_process_item_id`, a.`wc_process_asset_code`, a.`wc_process_item_make`, a.`wc_process_item_model`, a.`wc_process_item_name`, a.`wc_process_item_sr_no`, b.`wci_name` FROM `app_wc_process` AS a LEFT JOIN `app_wc_item_master` AS b ON ( b.`wci_id` = a.`wc_process_item_id` ) WHERE CONCAT('P|',a.`wc_process_asset_code`) IN ($products) GROUP BY b.`wci_name`, a.`wc_process_asset_code` ORDER BY `wc_process_asset_code` ASC" ;
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))	
			{
				$data[] = $row;
			}
		}
		$outputArray = array();
		if(count($data))
		{
			foreach($data as $row)
			{				
				$outputArray[] = $this->getCollectionFullDetails($row);
			}
		}
		return $outputArray;
	}
	
	function getCollectionFullDetails($row){
		$rowData = array();
		$rowData['Item'] = $row['wci_name'];
		$rowData['Name'] = $row['wc_process_item_name'];
		$rowData['Make'] = $row['wc_process_item_make'];
		$rowData['Model'] = $row['wc_process_item_model'];
		$rowData['Code'] = $row['wc_process_asset_code'];
		$rowData['Serial'] = $row['wc_process_item_sr_no'];
		$sql1="SELECT b.`attribute_name`, c.`values_data` FROM `app_wc_item_attributes_relation` AS a 
		INNER JOIN `app_wc_item_attributes` AS b ON a.`attribute_relation_attribute_id` = b.`attribute_id` 
		LEFT JOIN `app_wc_item_attributes_values` as c ON a.`attribute_relation_id` = c.`values_realtion_id` AND a.`attribute_relation_item_id` = c.`values_item_id` AND a.`attribute_relation_attribute_id` = c.`values_attribute_id` 
		WHERE a.`attribute_relation_exportable` = '1' AND a.`attribute_relation_item_id` = '".$row['wc_process_item_id']."' AND c.`values_wc_process_id` = '".$row['wc_process_id']."' AND a.`attribute_relation_status` = '1' ORDER BY b.`attribute_element_type`, b.`attribute_name`";
//echo $sql1;				
		$dbc1 	= 	new DB();
		$result1	=	$dbc1->db_query($sql1);
		if($dbc1->db_num_rows()>0)	
		{	
			while($row1 = $dbc1->db_fetch_assoc(true))
			{
				$rowData[$row1['attribute_name']] = $row1['values_data'];
			}			
		}
		return $rowData;
	}
}
?>