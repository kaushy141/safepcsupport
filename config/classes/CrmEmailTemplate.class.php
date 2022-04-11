<?php
class CrmEmailTemplate extends DB{
	public $id;
	public $key_id		= "crm_email_template_id";
	public $table_name	= "app_customer_crm_email_template";
	public $status		= "crm_email_template_status";
	function __construct($id=0){				
		$this->id	=	$id;
	}
	
	function add($crm_email_template_name, $crm_email_template_html, $crm_email_template_status)
	{		
		$sql="INSERT INTO `app_customer_crm_email_template`(`crm_email_template_name`, `crm_email_template_html`, `crm_email_template_modified_date`, `crm_email_template_status`) VALUES ('$crm_email_template_name', '$crm_email_template_html', NOW(), '$crm_email_template_status')";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return $dbc->db_insert_id();		
	}
	
	function getDetails()
	{
		global $app;
		$sql="SELECT `crm_email_template_id`, `crm_email_template_name`, `crm_email_template_html`, `crm_email_template_modified_date`, `crm_email_template_status` FROM `app_customer_crm_email_template` WHERE MD5(`crm_email_template_id`) = MD5('".$this->id."')";
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
		$sql="SELECT `crm_email_template_id`, `crm_email_template_name`, `crm_email_template_html`, `crm_email_template_modified_date`, `crm_email_template_status` FROM `app_customer_crm_email_template` WHERE 1 ORDER BY `crm_email_template_modified_date` DESC";	
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){		
			while($row = $dbc->db_fetch_assoc()){
				$row['crm_email_template_modified_date'] = dateView($row['crm_email_template_modified_date'],'NOW');
				$result_array[] = $row;
			}
			return array_reverse($result_array);
		}
		else
			return false;
	}
	
	function getRecords()
	{
		$sql="SELECT `crm_email_template_id`, `crm_email_template_name`, `crm_email_template_html`, `crm_email_template_modified_date`, `crm_email_template_status` FROM `app_customer_crm_email_template` WHERE 1 ORDER BY `crm_email_template_modified_date` DESC";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<tr>";
				$html.="<td>".$row['crm_email_template_name']."</td>";
				$html.="<td>".dateView($row['crm_email_template_modified_date'],'NOW')."</td>";
				$html.="<td>".statusView($row['crm_email_template_id'], $row['crm_email_template_status'])."</td>";
				$html.="<td><a class=\"dropdown-item redirect\" href=\"updateemailtemplate/".$row['crm_email_template_id']."\"><i class=\"fa fa-share-square-o fa-fw\"></i> Edit</a></td>";
			$html.="</tr>";		
		}
		return $html;	
	}
	
	function getOptions($crm_email_template_id=NULL)
	{
		$sql="SELECT `crm_email_template_id`, `crm_email_template_name` FROM `app_customer_crm_email_template` WHERE `crm_email_template_status` = '1'  OR `crm_email_template_id` = '$crm_email_template_id' ORDER BY `crm_email_template_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select CRM Template - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[crm_email_template_id]\" ".(($crm_email_template_id==$row['crm_email_template_id'])?"selected":"")." >".$row['crm_email_template_name']."</option>";					
		}
		return $html;	
	}
}
?>