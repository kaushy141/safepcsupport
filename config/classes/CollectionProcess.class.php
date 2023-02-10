<?php
class CollectionProcess extends DB{
	public $id;
	public $key_id	=	"wc_process_asset_code";
	public $table_name	=	"app_wc_process";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	public function loadById($wc_process_id){
		$sql = "SELECT * FROM `app_wc_process` WHERE `wc_process_id` = '$wc_process_id'";
		$dbc 	= 	new DB();
		$result = $dbc->db_query($sql);
		if($dbc->db_num_rows($result)==1){
			$record = $dbc->db_fetch_assoc($result);
			$this->id = $record['wc_process_asset_code'];
			return $record;
		}
		else
			return false;
	}
	
	function isSerialNumberExist($wc_process_item_sr_no){
		$sql = "SELECT `wc_process_asset_code` FROM `app_wc_process` WHERE TRIM(UPPER(`wc_process_item_sr_no`)) = TRIM(UPPER('$wc_process_item_sr_no')) AND `wc_process_asset_code` != '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows();
	}
	static function addProcess($wc_process_asset_code, $wc_process_wc_id, $wc_process_item_id, $wc_process_item_make, $wc_process_item_model, $wc_process_item_name, $wc_process_item_sr_no, $wc_process_item_weight, $wc_process_item_qty, $wc_process_item_inext_phase){
		$wc_process_created_by = getLoginId();
		$sql = "INSERT INTO `app_wc_process`(`wc_process_asset_code`,`wc_process_wc_id`, `wc_process_item_id`, `wc_process_item_make`, `wc_process_item_model`, `wc_process_item_name`, `wc_process_item_sr_no`, `wc_process_item_weight`, `wc_process_item_qty`, `wc_process_item_inext_phase`, `wc_process_created_by`) VALUES ('$wc_process_asset_code', '$wc_process_wc_id', '$wc_process_item_id', '$wc_process_item_make', '$wc_process_item_model', '$wc_process_item_name', '$wc_process_item_sr_no', '$wc_process_item_weight', '$wc_process_item_qty', '$wc_process_item_inext_phase', '$wc_process_created_by')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
		
	static function removeItemFromCollectionProcess($wc_process_wc_id, $wc_process_item_id){
		$sql = "DELETE FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id' AND `wc_process_item_id` = '$wc_process_item_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	static function removeSingleItemFromCollectionProcess($wc_process_wc_id, $wc_process_item_id){
		$sql = "DELETE FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id' AND `wc_process_item_id` = '$wc_process_item_id' ORDER BY `wc_process_wc_id` DESC LIMIT 1";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	static function getAllProcessCode($wc_process_wc_id){
		$sql = "SELECT `wc_process_asset_code` as codes FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id'";
		$dbc 	= 	new DB();
		$result = $dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows($result) > 0){
			while($row = $dbc->db_fetch_assoc(true))
				$data[] = $row['codes'];
		}
		return $data;
	}
	static function getAllProcessCodeOfItem($wc_process_wc_id, $wc_process_item_id){
		$sql = "SELECT `wc_process_asset_code` as codes FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id' AND `wc_process_item_id` = '$wc_process_item_id' ORDER BY `wc_process_asset_code`";
		$dbc 	= 	new DB();
		$result = $dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows($result) > 0){
			while($row = $dbc->db_fetch_assoc(true))
				$data[] = $row['codes'];
			return $data;
		}
		else
			return false;
	}
	static function getAllProcessItemCount($wc_process_wc_id, $wc_process_item_id){
		$sql = "SELECT count(*) as total FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id' AND `wc_process_item_id` = '$wc_process_item_id'";
		$dbc 	= 	new DB();
		$result = $dbc->db_query($sql);
		$record = $dbc->db_fetch_assoc(true);
		return $record['total'];
	}
	static function getAllProcessProductWithSrno($wc_process_wc_id, $wc_process_item_id){
		$sql = "SELECT `wc_process_asset_code`, `wc_process_item_sr_no` FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id' AND `wc_process_item_id` = '$wc_process_item_id' ORDER BY `wc_process_asset_code`";
		$dbc 	= 	new DB();
		$result = $dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows($result) > 0){
			while($row = $dbc->db_fetch_assoc(true))
				$data[] = $row;
			return $data;
		}
		else
			return false;
	}
	
	static function getProcessByCode($wc_process_asset_code){
		$sql = "SELECT * FROM `app_wc_process` WHERE `wc_process_asset_code` = '$wc_process_asset_code'";
		$dbc 	= 	new DB();
		$result = $dbc->db_query($sql);
		if($dbc->db_num_rows($result)==1)
			return $dbc->db_fetch_assoc($result);
		else
			return false;
	}
		
	static function deleteProcessByCode($wc_process_asset_code){
		$sql = "DELETE FROM `app_wc_process` WHERE `wc_process_asset_code` = '$wc_process_asset_code'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	static function getColletionItemDetails($wc_id, $wcr_item_id){
		$sql ="SELECT * FROM `app_wc_record_items` WHERE `wc_id`='$wc_id' AND `wcr_item_id` = '$wcr_item_id' ";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);	
		if($dbc->db_num_rows()>0)
		{		
			return $dbc->db_fetch_assoc(true);
		}
		return false;
	}
	
	static function isCollectionProcessGenerated($wc_process_wc_id){
		$sql = "SELECT * FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id'";
		$dbc 	= 	new DB();
		$result = $dbc->db_query($sql);
		$dbc->db_num_rows($result) > 0 ? true : false;
	}
	
	static function updateProcess($wc_process_asset_code, $wc_process_wc_id, $wc_process_item_id, $wc_process_item_make, $wc_process_item_model, $wc_process_item_name, $wc_process_item_sr_no, $wc_process_item_weight, $wc_process_item_qty, $wc_process_item_inext_phase){
		$sql = "UPDATE `app_wc_process` SET
		`wc_process_item_id` = '$wc_process_item_id',
		`wc_process_item_make` = '$wc_process_item_make',
		`wc_process_item_model` = '$wc_process_item_model',
		`wc_process_item_name` =  '$wc_process_item_name',
		`wc_process_item_sr_no` =  '$wc_process_item_sr_no',
		`wc_process_item_weight` =  '$wc_process_item_weight',
		`wc_process_item_qty` =  '$wc_process_item_qty',
		`wc_process_item_inext_phase` = '$wc_process_item_inext_phase'
		WHERE `wc_process_asset_code` = '$wc_process_asset_code' AND `wc_process_wc_id` = '$wc_process_wc_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
	static function getProcessCode($wc_id, $wc_code)
	{	
		$sql = "SELECT MAX(`wc_process_asset_code`) as code FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_id' ORDER BY `wc_process_id` DESC  LIMIT 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result) == 1)
		{
			$data = $dbc->db_fetch_assoc();
			if($data['code'] == "")
			{
				return $wc_code.sprintf("%04d", 1);		
			}
			else
			{
				$number = intval(str_replace($wc_code, "", $data['code']));
				return $wc_code.sprintf("%04d", $number+1);					
			}
		}
	}
	
	static function getProcess($wc_process_wc_id, $wc_process_item_id = 0, $wc_process_asset_code = '')	{
		$condition = $wc_process_item_id !=0 ? "AND a.`wc_process_item_id` = '$wc_process_item_id'" : "";
		$condition .= $wc_process_asset_code !='' ? "AND a.`wc_process_asset_code` = '$wc_process_asset_code'" : "";
		$sql ="SELECT a.`wc_process_id`, a.`wc_process_asset_code`, a.`wc_process_wc_id`, a.`wc_process_item_id`, a.`wc_process_item_make`, a.`wc_process_item_model`, a.`wc_process_item_name`, a.`wc_process_item_sr_no`, a.`wc_process_item_weight`, a.`wc_process_item_qty`, a.`wc_process_item_inext_phase`, b.`wc_code`, c.`wci_name`, c.`wci_name`, c.`wci_serialize_type` FROM `app_wc_process` as a 
		INNER JOIN `app_wc_record` as b ON a.`wc_process_wc_id` = b.`wc_id`
		INNER JOIN `app_wc_item_master` as c ON a.`wc_process_item_id` = c.`wci_id` WHERE `wc_process_wc_id` = '$wc_process_wc_id' $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$collection_item_list_array = array();	
		if($dbc->db_num_rows()>0)
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$collection_item_list_array[] = $row; 
			}
		}
		return $collection_item_list_array;
	}
	
	static function getPrintProcessCode($wc_process_wc_id, $wc_process_asset_code = 0)	{
		$condition = $wc_process_asset_code !=0 ? "AND a.`wc_process_wc_id` = '$wc_process_wc_id'" : "";
		if($wc_process_asset_code)
		$condition = $wc_process_asset_code !=0 ? "AND a.`wc_process_asset_code` = '$wc_process_asset_code'" : "";
		$sql ="SELECT a.`wc_process_asset_code`, a.`wc_process_item_make`, a.`wc_process_item_name`, a.`wc_process_item_model`, c.`wci_name` FROM `app_wc_process` as a INNER JOIN `app_wc_item_master` as c ON a.`wc_process_item_id` = c.`wci_id` WHERE 1 $condition";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$collection_item_list_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$collection_item_list_array[] = array(
														"code"=>$row['wc_process_asset_code'],
														"title"=>$row['wc_process_item_name']. " ". $row['wc_process_item_model'],
														"subtitle"=>$row['wci_name']. " ". $row['wc_process_item_make'],
													  ); 
			}
			return $collection_item_list_array;
		}
		else
			return NULL;
	}
	
	static function getFilteredPrintProductCode($param){
		if(isset($_SESSION['QUERY']['COLLECTIONITEMLIST']))
		$sql = $_SESSION['QUERY']['COLLECTIONITEMLIST'];
		else
		$sql = "SELECT a.`wc_process_id`, a.`wc_process_item_id`, a.`wc_process_asset_code`, a.`wc_process_item_make`, a.`wc_process_item_model`, a.`wc_process_item_name`, a.`wc_process_item_sr_no`, b.`wci_name` FROM `app_wc_process` AS a LEFT JOIN `app_wc_item_master` AS b ON ( b.`wci_id` = a.`wc_process_item_id` ) $param[joins] WHERE 1 $param[conditions] GROUP BY a.`wc_process_asset_code` ORDER BY `wc_process_asset_code` ASC" ;
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))	
			{
				$data[] = array(
									"code"=>$row['wc_process_asset_code'],
									"title"=>$row['wc_process_item_name']. " ". $row['wc_process_item_model'],
									"subtitle"=>$row['wci_name']. " ". $row['wc_process_item_make'],
								  );
			}
		}
		return $data;
	}
	
	static function getStoredProcess($wc_process_wc_id, $wc_process_item_id = 0)	{
		$condition = $wc_process_item_id !=0 ? "AND `wc_process_item_id` = '$wc_process_item_id'" : "";
		$sql ="SELECT * FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id' $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$collection_item_list_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$collection_item_list_array[] = $row; 
			}
			return  $collection_item_list_array;
		}
		return NULL;
	}
	static function getProcessColumSchema(){
		$sql = "SELECT column_name, data_type, CHARACTER_MAXIMUM_LENGTH FROM information_schema.columns WHERE table_name='app_wc_process' AND column_name IN ('wc_process_item_make', 'wc_process_item_model', 'wc_process_item_name', 'wc_process_item_sku', 'wc_process_item_sr_no', 'wc_process_item_weight', 'wc_process_item_qty', 'wc_process_item_location', 'wc_process_item_damage_status', 'wc_process_item_inext_phase', 'wc_process_item_stock')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$collection_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$collection_array[] = array(
					"name" => $row['column_name'],
					"id" => $row['column_name'],
					"class" => $row['column_name'],
					"type" => $row['data_type'],
					"value" => '',
					"label" => ucfirst(str_replace("_", " ",str_replace("wc_process_","", $row['column_name']))),
					"maxlength" => intval($row['CHARACTER_MAXIMUM_LENGTH']),
				); 
			}
			return  $collection_array;
		}
		return NULL;	
	}
	
	function getSimilarItemFromCollection($wc_process_wc_id, $wc_process_item_id){
		$sql = "SELECT `wc_process_id`, `wc_process_asset_code` FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id' AND `wc_process_item_id` = '$wc_process_item_id' ORDER BY `wc_process_asset_code`";
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
	
	function getReportSqlBasicAuditReport($wc_process_wc_id){
		return "SELECT a.`wc_process_asset_code` as asset, a.`wc_process_item_make` as make, a.`wc_process_item_model` as model, a.`wc_process_item_name` as name, a.`wc_process_item_sr_no` as srno, a.`wc_process_item_qty` as qty, c.`wci_name` as class FROM `app_wc_process` as a INNER JOIN `app_wc_item_master` as c ON a.`wc_process_item_id` = c.`wci_id` WHERE a.`wc_process_wc_id` = '$wc_process_wc_id'";
	}
	
	static function get_process_code_details($process_code){
		$process_code = strtoupper($process_code);
		$sql = "SELECT *, CONCAT(d.`customer_address_street_number`,  ', ', d.`customer_address_route`, ', ', d.`customer_address_locality`, ', ', d.`customer_address_administrative_area`, ', ', d.`customer_address_country`, ' ', d.`customer_address_postcode`) as customer_full_address, e.`carrier_name`, e.`carrier_licence_number`, e.`carrier_vehicle_reg_number`, f.`vehicle_registration_number`, f.`vehicle_name`, f.`vehicle_company`, f.`vehicle_model`, f.`vehicle_color`, CONCAT(g.user_fname, ' ', g.user_lname) as driver_name, g.user_id as driver_id, g.user_image as driver_image FROM `app_wc_process` AS a 
		INNER JOIN `app_wc_record` 				AS b ON a.`wc_process_wc_id` = b.`wc_id`  
		INNER JOIN `app_wc_item_master`			AS i ON a.`wc_process_item_id` = i.`wci_id` 
		INNER JOIN `app_customer` 				AS c on b.`wc_customer_id` = c.`customer_id` 
		INNER JOIN `app_customer_address` 		AS d on b.`wc_customer_address_id` = d.`customer_address_id` 
		LEFT JOIN `app_wc_carrier` 				AS e on b.`wc_carrier_id` = e.`carrier_id` 
		LEFT JOIN `app_wc_carrier_vehicle` 		AS f on b.`wc_vehicle_id` = f.`vehicle_id` 
		LEFT JOIN `app_system_user` 			AS g on b.`wc_driver_id` = g.`user_id`
		WHERE UPPER(a.`wc_process_asset_code`) = '$process_code'
		";
		$dbc 	= 	new DB();
		$result = $dbc->db_query($sql);
		if($dbc->db_num_rows($result)==1)
			return $dbc->db_fetch_assoc($result);
		else
			return false;
	}
	
	static function getFilTerJoinCondition($filter=NULL, $searchKeyword=""){
		$alias = 'h';
		$joins = '';
		$conditions = '';
		$searchConditions = array();
		if($filter)
		foreach($filter as $field=>$values){
			if($field == 'attribute'){
				if(is_array($values))
				{					
					foreach($values as $attribute_id => $_val)
					{
						$dataOn = array();							
						
						if(is_array($_val))
						{
							$dataMultiOn = array();								
							foreach($_val as $_v)
							{						
								$dataMultiOn[] = "$alias.`values_data` = '".sanitizePostData($_v)."'";
								if($searchKeyword != "")
								{
									$searchConditions[]= " $alias.`values_data` LIKE '%$searchKeyword%' ";
								}
							}
							$dataOn[] = "(".implode(" OR ", $dataMultiOn).")";
						}else{
							$dataOn[] = "$alias.`values_data` = '".sanitizePostData($_val)."'";
							if($searchKeyword != "")
							{
								$searchConditions[]= " $alias.`values_data` LIKE '%$searchKeyword%' ";
							}
						}
						
						$joins .= " LEFT JOIN `app_wc_item_attributes_values` as $alias ON a.`wc_process_id` = $alias.`values_wc_process_id` AND $alias.`values_attribute_id` = '$attribute_id' ";	
						if(count($dataOn))
							$conditions .= " AND ". implode(' AND ', $dataOn) ;
						
						$alias++;
					}						
				}
				else{
					$joins .= " LEFT JOIN `app_wc_item_attributes_values` as $alias ON a.`wc_process_id` = $alias.`values_wc_process_id` AND $alias.`values_attribute_id` = '$attribute_id' ";
					$conditions.= " AND $alias.`values_data` = '$values' ";
					if($searchKeyword != "")
					{
						$searchConditions[]= " $alias.`values_data` LIKE '%$searchKeyword%' ";
					}
					$alias++;
				}
				//if(count($filedCondArray))
				//$conditionArray[] = $filedCondArray;	
			}
			else
			{
				if(is_array($values))
				{
					$conditions.= " AND (";
					$subcondition = array();
					foreach($values as $_values)
					{
						$_val_arr = explode("|||", $_values);
						$_val = $_val_arr[0];
						$operator = isset($_val_arr[1]) ? $_val_arr[1] : "=";
						//$searchConditions[] = array(is_array($field) ? $field[0] : $field, $operator, sanitizePostData($_val));
						$subcondition.= " (".(is_array($field) ? $field[0] : $field)." $operator ".(in_array($_val, array('null')) ? sanitizePostData($_val) : ("'".sanitizePostData($_val)."'")).")";
										
						//$conditions.= " AND a.`$field` = '$_values' ";
						if($searchKeyword != "")
						{
							$searchConditions[] = " (a.`".(is_array($field) ? $field[0] : $field)."` LIKE '%$searchKeyword%' OR a.`wc_process_asset_code` LIKE '%$searchKeyword%' OR  a.`wc_process_item_make` LIKE '%$searchKeyword%' OR  a.`wc_process_item_model` LIKE '%$searchKeyword%' OR  a.`wc_process_item_name` LIKE '%$searchKeyword%' OR a.`wc_process_item_sku` LIKE '%$searchKeyword%' OR  a.`wc_process_item_sr_no` LIKE '%$searchKeyword%' OR  b.`wci_name` LIKE '%$searchKeyword%')";
						}
					}
					$conditions .= count($subcondition) ? implode(" OR ", $subcondition) : "1=1";
					$conditions.= " )";
				}
				else
				{
					$_val_arr = explode("|||", $values);
					$_val = $_val_arr[0];
					$operator = isset($_val_arr[1]) ? $_val_arr[1] : "=";
					//$searchConditions[] = array(is_array($field) ? $field[0] : $field, $operator, sanitizePostData($_val));
					$conditions.= " AND(".(is_array($field) ? $field[0] : $field)." $operator ".(in_array($_val, array('null')) ? sanitizePostData($_val) : ("'".sanitizePostData($_val)."'")).")";
									
					//$conditions.= " AND a.`$field` = '$values' ";
					if($searchKeyword != "")
					{
						$searchConditions[] = " (a.`".(is_array($field) ? $field[0] : $field)."` LIKE '%$searchKeyword%' OR a.`wc_process_asset_code` LIKE '%$searchKeyword%' OR  a.`wc_process_item_make` LIKE '%$searchKeyword%' OR  a.`wc_process_item_model` LIKE '%$searchKeyword%' OR  a.`wc_process_item_name` LIKE '%$searchKeyword%' OR a.`wc_process_item_sku` LIKE '%$searchKeyword%' OR  a.`wc_process_item_sr_no` LIKE '%$searchKeyword%' OR  b.`wci_name` LIKE '%$searchKeyword%')";
					}
				}
			}			
		}
		if(count($searchConditions)){
			$alias++;
			$joins .= " LEFT JOIN `app_wc_item_attributes_values` as $alias ON a.`wc_process_id` = $alias.`values_wc_process_id`";
			$conditions.= "AND (".implode(' OR ', $searchConditions)." OR $alias.`values_data` LIKE '%$searchKeyword%'".")";
		}
		$joins .= " LEFT JOIN `app_wc_item_attributes_values` as fbc ON a.`wc_process_id` = fbc.`values_wc_process_id` AND fbc.`values_attribute_id` = '57' ";	
		return array('joins'=>$joins, 'conditions'=>$conditions);
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`wc_process_asset_code`");
		$conditionArray = array();
		/*
		if($filter != NULL && count($filter)){
			//echo "<pre>";
			//print_r($filter);die;
			$alias = 'h';
			$joins = '';
			$conditions = '';
			$searchConditions = array();
			foreach($filter as $field=>$values){
				if($field == 'attribute'){
					if(is_array($values))
					{					
						foreach($values as $attribute_id => $_val)
						{
							$dataOn = array();							
							
							if(is_array($_val))
							{
								$dataMultiOn = array();								
								foreach($_val as $_v)
								{
									$dataMultiOn[] = "$alias.`values_data` = '".sanitizePostData($_v)."'";
									if($searchKeyword != "")
									{
										$searchConditions[]= " $alias.`values_data` LIKE '%$searchKeyword%' ";
									}
								}
								$dataOn[] = "(".implode(" OR ", $dataMultiOn).")";
							}else{
								$dataOn[] = "$alias.`values_data` = '".sanitizePostData($_val)."'";
								if($searchKeyword != "")
								{
									$searchConditions[]= " $alias.`values_data` LIKE '%$searchKeyword%' ";
								}
							}
							
							$joins .= " LEFT JOIN `app_wc_item_attributes_values` as $alias ON a.`wc_process_id` = $alias.`values_wc_process_id` AND $alias.`values_attribute_id` = '$attribute_id' ";	
							if(count($dataOn))
								$conditions .= " AND ". implode(' AND ', $dataOn) ;
							
							$alias++;
						}						
					}
					else{
						$joins .= " LEFT JOIN `app_wc_item_attributes_values` as $alias ON a.`wc_process_id` = $alias.`values_wc_process_id` AND $alias.`values_attribute_id` = '$attribute_id' ";
						$conditions.= " AND $alias.`values_data` = '$values' ";
						if($searchKeyword != "")
						{
							$searchConditions[]= " $alias.`values_data` LIKE '%$searchKeyword%' ";
						}
						$alias++;
					}
					//if(count($filedCondArray))
					//$conditionArray[] = $filedCondArray;	
				}
				else
				{
					$conditions.= " AND a.`$field` = '$values' ";
					if($searchKeyword != "")
					{
						$searchConditions[] = " (a.`$field` LIKE '%$searchKeyword%' OR a.`wc_process_asset_code` LIKE '%$searchKeyword%' OR  a.`wc_process_item_make` LIKE '%$searchKeyword%' OR  a.`wc_process_item_model` LIKE '%$searchKeyword%' OR  a.`wc_process_item_name` LIKE '%$searchKeyword%' OR  a.`wc_process_item_sr_no` LIKE '%$searchKeyword%' OR  b.`wci_name` LIKE '%$searchKeyword%')";
					}
				}
				
			}
		}
		if(count($searchConditions)){
			$alias++;
			$joins .= " LEFT JOIN `app_wc_item_attributes_values` as $alias ON a.`wc_process_id` = $alias.`values_wc_process_id`";
			$conditions.= "AND (".implode(' OR ', $searchConditions)." OR $alias.`values_data` LIKE '%$searchKeyword%'".")";
		}
		*/
		//die;
		//array_filter($conditionArray);
		//$this->condition = $conditionArray;
		
		//$sql = $this->getSql();
		$orderAray = array("`wc_process_asset_code`", "`wci_name`", "`wc_process_item_make`", "`wc_process_item_model`",  "`wc_process_item_name`", "`wc_process_item_sr_no`", "`wc_process_item_stock`");
		$orderKey = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0; 
		$orderwise = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'desc';
		$orderby = $orderAray[$orderKey].' '.$orderwise;
		$sqlParam = CollectionProcess::getFilTerJoinCondition($filter, $searchKeyword);
		$joins = $sqlParam['joins'];
		$conditions = $sqlParam['conditions'];
		$sqlWithoutLimit = "SELECT a.`wc_process_id`, a.`wc_process_wc_id`, a.`wc_process_asset_code`, a.`wc_process_item_make`, a.`wc_process_item_model`, a.`wc_process_item_name`, a.`wc_process_item_sku`, a.`wc_process_item_stock`, a.`wc_process_item_sr_no`, a.`wc_process_verified`, a.`wc_process_item_location`, b.`wci_name`, fbc.values_data  as batch_code
		FROM  `app_wc_process` AS a  
		LEFT JOIN `app_wc_item_master` AS b ON ( b.`wci_id` = a.`wc_process_item_id`  )  
		$joins WHERE 1 $conditions GROUP BY a.`wc_process_id`";
		$_SESSION['QUERY']['COLLECTIONITEMLIST'] = $sqlWithoutLimit;
		$sql = $sqlWithoutLimit . " ORDER BY $orderby LIMIT ".$this->length." OFFSET ".$this->start."";
		$sqlCount = "SELECT COUNT(a.`wc_process_id`) as total FROM  `app_wc_process` AS a  
		LEFT JOIN `app_wc_item_master` AS b ON ( b.`wci_id` = a.`wc_process_item_id`  )  
		$joins WHERE 1 $conditions";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		$resultTotal = $dbcTotal->db_query($sqlCount);
		$num_rows_total= $dbcTotal->db_fetch_assoc($resultTotal)['total'];	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array(), "sql"=>$sql);
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								($row['wc_process_verified'] == "" ? ("<i class=\"fa fa-check text-muted\"></i> ") : ("<i title='".(dateView($row['wc_process_verified'], 'FULL'))."' class=\"fa fa-check text-success\"></i> "))."<span class=\"text-".($row["wc_process_item_stock"] ? "success":"danger")."\">$row[wc_process_asset_code]</span><br/><img class=\"img-avatar\" src=\"".$app->basePath('img/system/flag/flag_'.strtolower($row['wc_process_item_location']).'.png')."\" height=\"16px\"> <span>{$row['wc_process_item_location']}</span><span class=\"badge badge-warning\">$row[wc_process_item_sku]</span>",
								$row["wci_name"]."<br/><span class=\"badge badge-info\" style=\"word-break:break-all\">$row[batch_code]</span>",
								$row["wc_process_item_make"],
								$row["wc_process_item_model"],
								$row["wc_process_item_name"],
								$row["wc_process_item_sr_no"],
								$row["wc_process_item_stock"] ? "<span class=\"badge badge-success hidden-xs hidden-md hidden-sm visible-lg\">In Stock</span><i class=\"fa fa-check-circle text-success visible-xs visible-sm hidden-md hidden-lg\"></i>":"<span class=\"badge badge-danger hidden-xs hidden-md hidden-sm visible-lg\">Out of  Stock</span><i class=\"fa fa-check-circle text-muted visible-xs visible-sm hidden-md hidden-lg\"></i>",
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-ellipsis-v fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
	<a class=\"dropdown-item redirect\" href=\"".$app->basePath('updatewcprocessitem/'.$row['wc_process_asset_code'])."\"><i class=\"fa fa-share-square-o fa-fw\"></i> Update</a>
	<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[wc_process_id]|P', '$row[wc_process_asset_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Comment Log</a>
	<a class=\"dropdown-item\" href=\"javascript:verifyProcessProduct('".$row['wc_process_asset_code']."', ".($row['wc_process_verified'] == "" ? 1:0).")\"><i class=\"fa fa-check fa-fw text-success\"></i> ".($row['wc_process_verified'] == "" ? "Mark Verify":"Unverify")."</a>
	<a class=\"dropdown-item redirect\" href=\"".$app->basePath('processproductsalehistory/'.$row['wc_process_asset_code'])."\"><i class=\"fa fa-shopping-cart fa-fw\"></i> Sale History</a>
    <a class=\"dropdown-item addtolot\" data-lot=\"P|{$row['wc_process_asset_code']}\" href=\"#\"><i class=\"fa fa-plus fa-fw text-success\"></i> Add to lot</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::CASSETCODE($row["wc_process_wc_id"], $row["wc_process_asset_code"])."')\"><i class=\"fa fa-barcode fa-fw\"></i> Barcode </a>
  </div>
</div>",
							"updatewcprocessitem/".$row['wc_process_asset_code']
			);
		}
		return json_encode($output);		
	}
	
	function getItemSkuCopy($wc_process_item_id, $wc_process_item_sku){		
		$sql="SELECT d.`wc_process_item_make`, d.`wc_process_item_sku`, d.`wc_process_item_stock`, d.`wc_process_item_name`, d.`wc_process_item_weight`, b.`attribute_id`, c.`values_data` FROM `app_wc_item_attributes_relation` AS a INNER JOIN `app_wc_item_attributes` AS b ON a.`attribute_relation_attribute_id` = b.`attribute_id` INNER JOIN `app_wc_item_attributes_values` as c ON a.`attribute_relation_id` = c.`values_realtion_id` AND a.`attribute_relation_item_id` = c.`values_item_id` AND a.`attribute_relation_attribute_id` = c.`values_attribute_id` RIGHT OUTER JOIN `app_wc_process` as d ON d.`wc_process_id` = c.`values_wc_process_id` AND d.`wc_process_wc_id` = c.`values_wc_id` WHERE d.`wc_process_item_id` = '".$wc_process_item_id."' AND a.`attribute_relation_status` = '1' AND LOWER(d.`wc_process_item_sku`) = LOWER('$wc_process_item_sku') GROUP BY attribute_id ORDER BY b.`attribute_name`";
		
		/*SELECT a.`wc_process_item_make`, a.`wc_process_item_sku`, a.`wc_process_item_stock`, a.`wc_process_item_name`, a.`wc_process_item_weight`, b.`values_data`, d.`attribute_id` FROM `app_wc_process` AS a 
		LEFT JOIN `app_wc_item_attributes_values` as b ON a.`wc_process_id` = b.`values_wc_process_id` AND a.`wc_process_wc_id` = b.`values_wc_id` AND a.`wc_process_item_id` = b.`values_item_id`
		LEFT JOIN `app_wc_item_attributes_relation` as c ON b.`values_realtion_id` = c.`attribute_relation_id` AND b.`values_item_id` = c.`attribute_relation_item_id` AND b.`values_attribute_id` = c.`attribute_relation_attribute_id`
		LEFT JOIN `app_wc_item_attributes` as d ON c.`attribute_relation_attribute_id` = d.`attribute_id` 
		WHERE a.`wc_process_item_id` = '".$wc_process_item_id."' AND LOWER(a.`wc_process_item_sku`) = LOWER('$wc_process_item_sku')
		GROUP BY d.`attribute_id` ORDER BY d.`attribute_name`*/	
		$dbc 	= 	new DB();
		$result_array = array();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result) > 0)	
		{
			$rowData = array();
			while($row = $dbc->db_fetch_assoc(true)){
				$rowData = $row;
				$result_array['attribute'][$row['attribute_id']] = $row['values_data'];
			}
			$result_array['wc_process_item_make'] = $rowData['wc_process_item_make'];
			$result_array['wc_process_item_name'] = $rowData['wc_process_item_name'];
			$result_array['wc_process_item_weight'] = $rowData['wc_process_item_weight'];
			$result_array['wc_process_item_sku'] = $rowData['wc_process_item_sku'];
			$result_array['wc_process_item_stock'] = $rowData['wc_process_item_stock'];
		}
		return $result_array;
	}
	
	static function getTechnicianFilteration($wci_id = 0){
		$condition = $wci_id ? " AND a.`wc_process_item_id` = '$wci_id'":"";
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`wc_process_asset_code`) as record FROM `app_wc_process` AS a LEFT JOIN `app_system_user` AS b on a.`wc_process_under_technician` = b.`user_id` WHERE a.`wc_process_under_technician` != 0 $condition GROUP BY a.`wc_process_under_technician` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] 	=	getResizeImage($row['user_image'],50);
		$row['label'] 	=	$row['user_fname'] . ' ' .$row['user_lname'];
		$row['value'] 	=	$row['user_fname'] . ' ' .$row['user_lname'] . "(".$row['record'].")";
		$row['id'] 	=	$row['user_id'];
		$data[] = $row;
		}
		return array("title" => "Technician", "key"=> "wc_process_under_technician", "items" => $data);
	}
	
	static function getProcessItemTableRow($item){
		global $app;
				return "<tr id=\"wc_item_row_tr_$item[wc_process_asset_code]\">
				<td><input type=\"hidden\" name=\"wc_process_asset_code[]\" value=\"$item[wc_process_asset_code]\"><b class=\"form-control wcc_asset_code\">$item[wc_process_asset_code]</b></td>';
		<td><input class=\"form-control wcc_name\" required value=\"$item[wc_process_item_name]\" name=\"wci_item_name[$item[wc_process_asset_code]]\" type=\"text\"></td>
		<td><input class=\"form-control wcc_make\" required value=\"$item[wc_process_item_make]\" name=\"wci_item_make[$item[wc_process_asset_code]]\" type=\"text\"></td>
		<td><input class=\"form-control wcc_model\" required value=\"$item[wc_process_item_model]\" name=\"wci_item_model[$item[wc_process_asset_code]]\" type=\"text\"></td>
		<td><input class=\"form-control wcc_srno input_text_upper\" value=\"$item[wc_process_item_sr_no]\" name=\"wci_item_srno[$item[wc_process_asset_code]]\" type=\"text\"></td>
		<td>		
		<div class=\"btn-group\"><button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fa fa-ellipsis-v fa-fw\"></i> <span class=\"sr-only\">TD</span></button><div class=\"dropdown-menu dropdown-menu-right\"><a class=\"updateitem redirect dropdown-item\" href=\"".$app->basePath("updatewcprocessitem/$item[wc_process_asset_code]")."\"><i class=\"fa fa-pencil fa-fw\"></i> Update</a><a data-id=\"'+id+'\" class=\"dropdown-item remove_item_row\" data-process-code=\"$item[wc_process_asset_code]\" href=\"#\"><i class=\"fa fa-trash fa-fw text-danger\"></i> Remove</a></div></div></td>
		</tr>";
		
	}
	
	
	function getCollectionItemsNotInPallet($wc_process_wc_id, $wc_process_item_id = 0){
		global $app;
		$condition = $wc_process_item_id ? " AND `wc_process_item_id` = '$wc_process_item_id'" : "";
		//$sql = "SELECT `wc_process_asset_code` as codes FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id' $condition  EXCEPT  SELECT `wpi_process_asset_code` AS codes FROM `app_wc_pallet_items`";
		
		$sql = "SELECT `wc_process_asset_code` as codes FROM `app_wc_process` WHERE `wc_process_wc_id` = '$wc_process_wc_id' $condition  AND `wc_process_asset_code` NOT IN (SELECT `wpi_process_asset_code` FROM `app_wc_pallet_items`)";
		$dbc 	= 	new DB();
		$result = $dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows($result) > 0){
			while($row = $dbc->db_fetch_assoc(true))
				$data[] = $row['codes'];
		}
		return $data;
	}
}
?>