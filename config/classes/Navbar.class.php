<?php
class Navbar extends DB{
	public $id;
	public $key_id	=	"module_id";
	public $table_name	=	"app_modules";
	public $status	=	"module_status";
	public $condition = "";
		
	function __construct($id=0){		
		$this->id	=	$id;
		$this->condition = isAdmin() ? "FIND_IN_SET (`module_id`, (SELECT `user_type_module_permission` FROM `app_user_type` WHERE `user_type_id` = '$_SESSION[user_type_id]'))>0 OR FIND_IN_SET (`module_id`, (SELECT `user_module_access` FROM `app_system_user` WHERE `user_id` = '$_SESSION[user_id]'))" : "`module_is_customer_access` = 1";
	}
	
	function getNavBarList()
	{
		$sql = "SELECT `module_id`, `module_name`, `module_key`, `module_icon`, `module_parent`, `module_sort_order`, `module_is_customer_access`, `module_is_navbar`, `module_status` FROM `app_modules` WHERE `module_is_navbar` = '1' AND `module_status` = '1' AND (".$this->condition.") ORDER BY `module_parent`, `module_sort_order`";
		//echo $sql;
		$dbc 	= 	new DB();		
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		
		while($row = $dbc->db_fetch_assoc(true)){
			if(isset($record_array[$row['module_parent']]))	
				$record_array[$row['module_parent']]['child'][] = $row;
			else
				$record_array[$row['module_id']]['info'] = $row;
		}
		return $record_array;
	}
	
	function isModulePemissionAllow($module = 0){
		$sql = "SELECT `module_id` FROM `app_modules` WHERE `module_is_navbar` = '1' AND `module_status` = '1' AND (".$this->condition.") AND (`module_id` = '$module' OR LOWER(`module_key`) = LOWER('$module'))";
		
		$dbc 	= 	new DB();		
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() ? true : false;
	}
	
	function isUserCanAccess($module = 0){
		$sql = "SELECT `module_id` FROM `app_modules` WHERE `module_status` = '1' AND (".$this->condition.") AND (`module_id` = '$module' OR LOWER(`module_key`) = LOWER('$module'))";
		//echo $sql;
		$dbc 	= 	new DB();		
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() ? true : false;
	}
	
	function getFullModuleList(){
		$sql = "SELECT `module_id`, `module_name`, `module_key`, `module_icon`, `module_parent`, `module_sort_order`, `module_is_customer_access`, `module_is_navbar`, `module_status` FROM `app_modules` WHERE 1 ORDER BY `module_parent`, `module_sort_order`";
		//echo $sql;
		$dbc 	= 	new DB();		
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		
		while($row = $dbc->db_fetch_assoc(true)){
			if(isset($record_array[$row['module_parent']]))	
				$record_array[$row['module_parent']]['child'][] = $row;
			else
				$record_array[$row['module_id']]['info'] = $row;
		}
		return $record_array;
	}
	
	function getTopBarList()
	{
		$sql = "SELECT `module_id`, `module_name`, `module_topbar_name`, `module_key`, `module_icon`, `module_parent`, `module_sort_order`, `module_is_customer_access`, `module_is_navbar`, `module_status` FROM `app_modules` WHERE `module_is_topbar` = '1' AND `module_status` = '1' AND (".$this->condition.") ORDER BY `module_parent`, `module_sort_order`";
		$dbc 	= 	new DB();		
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		
		while($row = $dbc->db_fetch_assoc(true)){
			$record_array[] = $row;
		}
		return $record_array;
	}
	
	function getParentOptions($module_id=NULL)
	{
		$sql="SELECT `module_id`, `module_name`, `module_parent`, `module_sort_order` FROM `app_modules` WHERE `module_status` = '1' ORDER by `module_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		while($row = $dbc->db_fetch_assoc(true)){
			if($row['module_parent'] == 0)	{	
				$row['is_child'] = 0;		
				$record_array[$row['module_id']][0] = $row;	
			}
			else{
				$row['is_child'] = 1;		
				$record_array[$row['module_parent']][$row['module_sort_order']] = $row;	
			}
		}
		foreach($record_array as $key=>$array)
		{
			ksort($array);
			$record_array[$key] = $array;
		}
		ksort($record_array);
		//echo "<pre>";
		//print_r($record_array);
		//echo "</pre>";
		
		$html="<option value=\"0\"> - Select parent module - </option>";
		
		foreach($record_array as $moduleArray){
			foreach($moduleArray as $row){
				$html.="<option class=\"".($row['is_child']?"child":"parent")."_option\" value=\"$row[module_id]\" ".(($module_id==$row['module_id'])?"selected":"")." >".$row['module_name']."</option>";		
			}
		}	
		return $html;	
	}
}
?>