<?php
class CustomerGroup extends DB{
	public $id;
	public $key_id		= "crm_group_id";
	public $table_name	= "app_customer_crm_group";
	public $status		= "crm_group_status";
	function __construct($id=0){				
		$this->id	=	$id;
	}
	
	function add($crm_group_name, $crm_group_customers_all, $crm_group_customers_except, $crm_group_customers_not_all, $crm_group_customers_included, $crm_group_creator, $crm_group_status)
	{		
		$sql="INSERT INTO `app_customer_crm_group`(`crm_group_name`, `crm_group_customers_all`, `crm_group_customers_except`, `crm_group_customers_not_all`, `crm_group_customers_included`, `crm_group_creator`, `crm_group_created_date`, `crm_group_status`) VALUES ('$crm_group_name', '$crm_group_customers_all', '$crm_group_customers_except', '$crm_group_customers_not_all', '$crm_group_customers_included', '$crm_group_creator', NOW(), '$crm_group_status')";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return $dbc->db_insert_id();		
	}
	
	function getDetails()
	{
		global $app;
		$sql="SELECT `crm_group_id`, `crm_group_name`, `crm_group_customers_all`, `crm_group_customers_except`, `crm_group_customers_not_all`, `crm_group_customers_included`, `crm_group_creator`, `crm_group_created_date`, `crm_group_status` FROM `app_customer_crm_group` WHERE MD5(`crm_group_id`) = MD5('".$this->id."')";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if($dbc->db_num_rows())
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
			
	function getList($offset_id=0)
	{
		global $app;
		$sql="SELECT `crm_group_id`, `crm_group_name`, `crm_group_customers_all`, `crm_group_customers_except`, `crm_group_customers_not_all`, `crm_group_customers_included`, `crm_group_creator`, `crm_group_created_date`, `crm_group_status` FROM `app_customer_crm_group` WHERE 1 ORDER BY `crm_group_created_date` DESC";	
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){		
			while($row = $dbc->db_fetch_assoc()){
				$row['crm_group_created_date'] = dateView($row['crm_group_created_date'],'NOW');
				$result_array[] = $row;
			}
			return array_reverse($result_array);
		}
		else
			return false;
	}
	
	function getRecords()
	{
		global $app;
		$sql="SELECT a.`crm_group_id`, a.`crm_group_name`, a.`crm_group_creator`, a.`crm_group_created_date`, a.`crm_group_status`, CONCAT(b.`user_fname`, ' ', b.`user_lname`) as crm_group_creator_name FROM `app_customer_crm_group` as a INNER JOIN `app_system_user` as b ON a.`crm_group_creator` = b.`user_id` WHERE 1 ORDER BY `crm_group_created_date` DESC";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<tr>";
				$html.="<td>".$row['crm_group_name']."</td>";
				$html.="<td>".dateView($row['crm_group_created_date'],'NOW')."</td>";
				$html.="<td>".$row['crm_group_creator_name']."</td>";
				$html.="<td>".statusView($row['crm_group_id'], $row['crm_group_status'])."</td>";
				$html.="<td><a class=\"dropdown-item redirect\" href=\"".$app->siteUrl("updatecustomergroup/".$row['crm_group_id'])."\"><i class=\"fa fa-share-square-o fa-fw\"></i> Edit</a></td>";
			$html.="</tr>";		
		}
		return $html;	
	}
	
	static function getGroupCustomerArray($group_id, $withDetails = false)
	{
		//, `crm_group_customers_all`, `crm_group_customers_except`, `crm_group_customers_not_all`, `crm_group_customers_included` 
		$sql="SELECT `crm_group_id`, `crm_group_customers_all`, `crm_group_customers_except`, `crm_group_customers_not_all`, `crm_group_customers_included` FROM `app_customer_crm_group` WHERE  `crm_group_id` = '$group_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()==1){	
			$data = $dbc->db_fetch_assoc();
			$data['crm_group_customers_except'] = trim(trim($data['crm_group_customers_except']), ",");
			$data['crm_group_customers_included'] = trim(trim($data['crm_group_customers_included']), ",");
			$condition = "";
			if($data['crm_group_customers_all'] == 1 && trim($data['crm_group_customers_except']) != "")
				$condition = " AND `customer_id` NOT IN ($data[crm_group_customers_except]) ";
			elseif($data['crm_group_customers_not_all'] == 1 && trim($data['crm_group_customers_included']) != "")
				$condition = " AND `customer_id` IN ($data[crm_group_customers_included]) ";
			elseif(trim($data['crm_group_customers_included']) != "")
				$condition = " AND `customer_id` IN ($data[crm_group_customers_included]) ";
			else
				$condition = " AND 1=0";
			if($withDetails == false)
			{	
				$SQLCUSTOMER = "SELECT GROUP_CONCAT(`customer_id`) as customer_ids FROM `app_customer` WHERE 1 $condition";
				$dbc 	= 	new DB();
				$result	=	$dbc->db_query($SQLCUSTOMER);
				if($dbc->db_num_rows()==1){	
					$data = $dbc->db_fetch_assoc();
					return $data['customer_ids'];
				}
				else
					return NULL;
			}
			else
			{
				$record_array = array();
				$SQLCUSTOMER = "SELECT `customer_id`, `customer_fname`, `customer_lname` , CONCAT(`customer_fname`, ' ', `customer_lname`) as customer_name, `customer_email`, `customer_image` , `customer_phone`, `customer_password`  FROM `app_customer` WHERE $condition";				
				$dbc 	= 	new DB();
				$result	=	$dbc->db_query($SQLCUSTOMER);
				if($dbc->db_num_rows()>0)
				{	
					while($data = $dbc->db_fetch_assoc(true))
					{
						array_push($record_array, $data);
					}
					return $record_array;
				}
				else
					return NULL;
			}
		}
	}
	
	function getOptions($crm_group_id=NULL)
	{
		$sql="SELECT `crm_group_id`, `crm_group_name` FROM `app_customer_crm_group` WHERE `crm_group_status` = '1' OR `crm_group_id` = '$crm_group_id' ORDER BY `crm_group_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Customer Group - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[crm_group_id]\" ".(($crm_group_id==$row['crm_group_id'])?"selected":"")." >".$row['crm_group_name']."</option>";					
		}
		return $html;	
	}
}
?>