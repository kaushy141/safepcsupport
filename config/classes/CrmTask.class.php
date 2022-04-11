<?php
class CrmTask extends DB{
	public $id;
	public $key_id		= "crm_task_id";
	public $table_name	= "app_customer_crm_task";
	public $status		= "crm_task_status";
	function __construct($id=0){				
		$this->id	=	$id;
	}
	
	function add($crm_task_name, $crm_task_subject, $crm_task_template_id, $crm_task_customer_group_id, $crm_task_execution_time, $crm_task_status)
	{		
		$sql="INSERT INTO `app_customer_crm_task`(`crm_task_name`, `crm_task_subject`, `crm_task_template_id`, `crm_task_customer_group_id`, `crm_task_execution_time`, `crm_task_created_date`, `crm_task_status`) VALUES ('$crm_task_name', '$crm_task_subject', '$crm_task_template_id', '$crm_task_customer_group_id', '$crm_task_execution_time', NOW(), $crm_task_status)";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return $dbc->db_insert_id();		
	}
	
	function getDetails()
	{
		global $app;
		$sql="SELECT `crm_task_id`, `crm_task_name`, `crm_task_subject`, `crm_task_template_id`, `crm_task_customer_group_id`, `crm_task_execution_time`, `crm_task_created_date`, `crm_task_status` FROM `app_customer_crm_task` WHERE MD5(`crm_task_id`) = MD5('".$this->id."')";
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
		$sql="SELECT a.`crm_task_id`, a.`crm_task_name`, a.`crm_task_subject`, a.`crm_task_template_id`, a.`crm_task_customer_group_id`, a.`crm_task_execution_time`, a.`crm_task_created_date`, a.`crm_task_status`, b.`crm_email_template_name`, c.`crm_group_name` FROM `app_customer_crm_task` as a INNER JOIN `app_customer_crm_email_template` as b on a.`crm_task_template_id` = b.`crm_email_template_id` INNER JOIN `app_customer_crm_group` as c ON a.`crm_task_customer_group_id` = c.`crm_group_id` WHERE 1 ORDER BY a.`crm_task_id` DESC";	
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){		
			while($row = $dbc->db_fetch_assoc()){
				$row['crm_task_created_date'] = dateView($row['crm_task_created_date'],'FULL');
				$result_array[] = $row;
			}
			return array_reverse($result_array);
		}
		else
			return false;
	}
	
	function getRecords()
	{
		$sql="SELECT a.`crm_task_id`, a.`crm_task_name`, a.`crm_task_subject`, a.`crm_task_template_id`, a.`crm_task_customer_group_id`, a.`crm_task_execution_time`, a.`crm_task_created_date`, a.`crm_task_status`, b.`crm_email_template_name`, c.`crm_group_name` FROM `app_customer_crm_task` as a INNER JOIN `app_customer_crm_email_template` as b on a.`crm_task_template_id` = b.`crm_email_template_id` INNER JOIN `app_customer_crm_group` as c ON a.`crm_task_customer_group_id` = c.`crm_group_id` WHERE 1 ORDER BY a.`crm_task_id` DESC";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<tr>";
				$html.="<td><button title=\"Execute\" type=\"button\" class=\"btn fa fa-play-circle \" style=\"margin-bottom: 4px\" onclick=\"executeTask($row[crm_task_id])\">&nbsp;</button></td>";
				$html.="<td>".$row['crm_task_name']."</td>";
				$html.="<td>".$row['crm_task_subject']."</td>";
				$html.="<td>".$row['crm_email_template_name']."</td>";
				$html.="<td>".$row['crm_group_name']."</td>";				
				$html.="<td>".dateView($row['crm_task_execution_time'],'DATE')."</td>";
				$html.="<td>".statusView($row['crm_task_id'], $row['crm_task_status'])."</td>";
				$html.="<td><a class=\"dropdown-item redirect\" href=\"updatecrmtask/".$row['crm_task_id']."\"><i class=\"fa fa-share-square-o fa-fw\"></i> Edit</a></td>";
			$html.="</tr>";		
		}
		return $html;	
	}
	
	function execute()
	{
		global $app;
		$Task = $this->getDetails();
		$customerListArray = CustomerGroup::getGroupCustomerArray($Task['crm_task_customer_group_id'], true);
		$CrmEmailTemplate = new CrmEmailTemplate($Task['crm_task_template_id']);
		$Template = $CrmEmailTemplate->getDetails();
		if(count($customerListArray))
		{
			foreach($customerListArray as $customer)
			{
				$customer["login_page"]=$app->basePath("customer-login.php");
				$email = new Email($Task['crm_task_subject']);
				$email->to($customer['customer_email'], $customer['customer_name'], image($app->imagePath($customer['customer_image']),80,true));

				$email->setCrmTemplate($Template['crm_email_template_html'], $customer);
				$email->send();
			}
		}
	}
}
?>