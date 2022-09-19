<?php
class Store extends DB{
	public $id;
	public $key_id	=	"store_id";
	public $table_name	=	"app_store_master";
	public $status	=	"store_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}	
						
	function getDetails()
	{
		$sql="SELECT * FROM `".$this->table_name."` WHERE  `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	
	function getBankAccounts()
	{
		$sql="SELECT * FROM `app_store_bank_accounts` WHERE  `account_store_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true)){
				$result_array[] = $row;
			}
		}
		return $result_array;
	}
	
	function getBankDetails($account_id)
	{
		$sql="SELECT * FROM `app_store_bank_accounts` WHERE  `account_id` = '".$account_id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){
			return $dbc->db_fetch_assoc(true);
		}
		else
			return false;
	}
	
	
	function getDetailsByKey($store_key)
	{
		$sql="SELECT * FROM `".$this->table_name."` WHERE  `store_key` = '".$store_key."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function getAll($column_array = NULL)
	{
		$sql="SELECT ".(($column_array== NULL ? "*" : implode(", ", $column_array)))." FROM `".$this->table_name."` WHERE  1 AND `store_is_feedback_requestable` = '1'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true)){
				$result_array[] = $row;
			}
		}
		return $result_array;
	}
		
	function getOptions($store_id, $filter_type = null, $outputAsArray = false)
	{
		global $app;
		$condition = $filter_type != null ? "AND `$filter_type` = '1'" : "";
		$sql="SELECT `store_id`, `store_name`, `store_title`, `store_link`, `store_official_name`, `store_is_vat_disabled`, `store_vat_percent` FROM `".$this->table_name."` WHERE (`store_status` = '1' $condition) OR `store_id` = '$store_id' ORDER BY `store_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$resultArray = array();
		$html="<option value=\"0\"> - Select Store - </option>";			
		while($row = $dbc->db_fetch_assoc()){	
			$resultArray[] = $row;		
			$html.="<option data-vat-dissable=\"".$row['store_is_vat_disabled']."\" data-vat-percent=\"".$row['store_vat_percent']."\" value=\"$row[store_id]\" ".(($store_id==$row['store_id'])?"selected":"")." >".$row['store_title'].' - '.$row['store_name']."</option>";					
		}
		return $outputAsArray ? $resultArray : $html;
	}
	
	/*static function reviewLink($store_key)
	{
		global $app;
		$sql="SELECT `store_google_feedback_url` FROM `app_store_master` WHERE `store_google_feedback_url` = '$store_key'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){		
			$data = $dbc->db_fetch_assoc(true);
			return $data['store_google_feedback_url'];
		}
		else
			return NULL;
	}	*/

}
?>