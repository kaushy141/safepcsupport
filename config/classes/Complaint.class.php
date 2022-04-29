<?php

class Complaint extends DB{

	public $id;

	public $key_id			=	"complaint_id";

	public $table_name		=	"app_complaint_record";

	public $status			=	"complaint_active_status";	

	public $customer		=	0;

	public $technician		=	0;	

	function __construct($user_id=0){

				

		$this->id	=	$user_id;

		return $this;

	}
	

	function Count($type=NULL)

	{

		$condition	=	"";

		if($type!=NULL)

		$condition.= $type=="completed"?" AND `complaint_status` = '2'":"";

		

		if($this->customer!=0)

		$condition.= " AND `complaint_customer_id` = '".$this->customer."'";

		

		$sql="SELECT COUNT(`complaint_id`) as num FROM `app_complaint_record` WHERE 1 $condition";

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);		

		$data	=	$dbc->db_fetch_assoc();	

		return $data[0]	;

	}
	
	function getTodaysComplaint($date){
		global $app;

		$sql="SELECT a.`complaint_id`, a.`complaint_ticket_number`, a.`complaint_due_date`, a.`complaint_technician_id`, a.`complaint_created_date`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_image`, b.`customer_phone`, c.`customer_type_name`, d.`complaint_status_name`, d.`complaint_status_clolor_code`, CONCAT(e.`user_fname`, ' ', e.`user_lname`) as technician_name, e.`user_image` as technician_image
		FROM `app_complaint_record` as a 
		LEFT JOIN `app_customer` as b ON  a.`complaint_customer_id`= b.`customer_id`
		LEFT JOIN `app_customer_type` as c ON b.`customer_type_id`= c.`customer_type_id`
		LEFT JOIN `app_complaint_status_master` as d ON a.`complaint_status`= d.`complaint_status_id`
		LEFT JOIN `app_system_user` as e ON a.`complaint_technician_id`= e.`user_id`
		WHERE DATE_FORMAT(a.`complaint_created_date`, '%Y-%m-%d') = '$date'";	

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);
		$result_array = array();

		while($row = $dbc->db_fetch_assoc(true)){	
			$row['technician_image'] =	getResizeImage($row['technician_image'],50);							
			$result_array[] = $row;
		}
		return $result_array;
	}
	
	function getTodaysDueComplaint($date){
		global $app;

		$sql="SELECT a.`complaint_id`, a.`complaint_ticket_number`, a.`complaint_due_date`, a.`complaint_technician_id`, a.`complaint_created_date`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_image`, b.`customer_phone`, c.`customer_type_name`, d.`complaint_status_name`, d.`complaint_status_clolor_code`, CONCAT(e.`user_fname`, ' ', e.`user_lname`) as technician_name, e.`user_image` as technician_image
		FROM `app_complaint_record` as a 
		LEFT JOIN `app_customer` as b ON  a.`complaint_customer_id`= b.`customer_id`
		LEFT JOIN `app_customer_type` as c ON b.`customer_type_id`= c.`customer_type_id`
		LEFT JOIN `app_complaint_status_master` as d ON a.`complaint_status`= d.`complaint_status_id`
		LEFT JOIN `app_system_user` as e ON a.`complaint_technician_id`= e.`user_id`
		WHERE DATE_FORMAT(a.`complaint_due_date`, '%Y-%m-%d') = '$date'";	

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);
		$result_array = array();

		while($row = $dbc->db_fetch_assoc(true)){	
			$row['technician_image'] =	getResizeImage($row['technician_image'],50);		
			$result_array[] = $row;
		}
		return $result_array;
	}

	function getTechnicianFilteration($filter_name = null){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`complaint_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`complaint_technician_id` = b.`user_id` GROUP BY a.`complaint_technician_id` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	
	function getCreatorFilteration($filter_name = null){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`complaint_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`complaint_created_by` = b.`user_id` GROUP BY a.`complaint_created_by` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	
	function getStatusFilteration($filter_name = null){
		$sql = "SELECT b.`complaint_status_id`, b.`complaint_status_name`, COUNT(a.`complaint_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_complaint_status_master` AS b on a.`complaint_status` = b.`complaint_status_id` GROUP BY a.`complaint_status` ORDER BY b.`complaint_status_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$data[] = $row;
		}
		return $data;
	}

	function getComplaintTicketNumber($hardware_code=NULL)

	{

		if($hardware_code==NULL)

		$hardware_code = "RMA";

		$sql = "SELECT COUNT(`complaint_id`) as num FROM `app_complaint_record` WHERE UPPER(SUBSTR(`complaint_ticket_number`,3,3)) = UPPER('$hardware_code') AND YEAR(`complaint_created_date`) = YEAR(NOW())";

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$numcountrow = $dbc->db_fetch_assoc();

		$numcount = $numcountrow['num']+1;

		$ticketNumber = strtoupper(date("y").$hardware_code.sprintf("%05d",$numcount));

		return $ticketNumber;

	}

	

	function getCustomerComplaint($customer_id)

	{

		$log_view_user_type = getUserType();

		$sql="SELECT a.`complaint_id` as id, 'C' as complaint_format, a.`complaint_ticket_number` as code, a.`complaint_status` as status_id, DATE_FORMAT(a.`complaint_created_date`, '%d %b-%Y') as created_date, b.`complaint_status_name` as status_name, count(c.`complaint_log_id`) as new_message

FROM `app_complaint_record` as a 

INNER JOIN `app_complaint_status_master` as b ON a.`complaint_status`=b.`complaint_status_id`

LEFT JOIN `app_complaint_log` as c ON (a.`complaint_id` = c.`complaint_id` AND c.`complaint_format` = 'C' AND c.`complaint_customer_id`!=0 AND c.`is_readed`='0')

WHERE a.`complaint_customer_id` = '$customer_id'

GROUP BY a.`complaint_id`

UNION



SELECT a.`wc_id` as id, 'W' as complaint_format, a.`wc_code` as code, a.`wc_status_id` as status_id, DATE_FORMAT(a.`wc_created_date`, '%d %b-%Y') as created_date, b.`wc_status_name` as status_name, count(c.`complaint_log_id`) as new_message FROM `app_wc_record` as a INNER JOIN `app_wc_status` as b ON  a.`wc_status_id`= b.`wc_status_id` LEFT JOIN `app_complaint_log` as c ON (a.`wc_id` = c.`complaint_id` AND c.`complaint_format` = 'W' AND c.`complaint_customer_id`!=0 AND c.`is_readed`='0') WHERE a.`wc_customer_id` = '$customer_id'

GROUP BY a.`wc_id`


UNION



SELECT a.`sales_invoice_id` as id, 'S' as complaint_format, a.`sales_invoice_number` as code, a.`sales_invoice_is_cancelled` as status_id, DATE_FORMAT(a.`sales_invoice_created_date`, '%d %b-%Y') as created_date, CASE WHEN a.`sales_invoice_is_cancelled` = 1 THEN 'Cancelled' ELSE 'Active' END AS status_name, count(c.`complaint_log_id`) as new_message FROM `app_sales_invoice` as a LEFT JOIN `app_complaint_log` as c ON (a.`sales_invoice_id` = c.`complaint_id` AND c.`complaint_format` = 'S' AND c.`complaint_customer_id`!=0 AND c.`is_readed`='0') WHERE a.`sales_invoice_customer_id` = '$customer_id'

GROUP BY a.`sales_invoice_id`


UNION



SELECT a.`web_order_id` as id, 'O' as complaint_format, a.`web_order_number` as code, a.`web_order_status` as status_id, DATE_FORMAT(a.`web_order_created_date`, '%d %b-%Y') as created_date, b.`wc_status_name` AS status_name, count(c.`complaint_log_id`) as new_message FROM `app_website_order` as a INNER JOIN `app_wc_status` as b ON  a.`web_order_status`= b.`wc_status_id` LEFT JOIN `app_complaint_log` as c ON (a.`web_order_id` = c.`complaint_id` AND c.`complaint_format` = 'O' AND c.`complaint_customer_id`!=0 AND c.`is_readed`='0') WHERE a.`web_order_customer_id` = '$customer_id'

GROUP BY a.`web_order_id`

ORDER BY `created_date` DESC

";

		//echo $sql;

		$dbc 	= 	new DB();

		$dbc->db_query($sql);

		$result_array = array();

		if($dbc->db_num_rows()){		

			while($row = $dbc->db_fetch_assoc()){	

			$result_array[]=$row;

			}

		}

		return $result_array;

	}

	

	function getRecords()

	{

		global $app;

		$sql="SELECT a.`complaint_id`, a.`complaint_customer_id`, a.`customer_address_id`, a.`complaint_ticket_number`, a.`complaint_product_serial`, a.`complaint_product_model`, a.`complaint_is_backup`, a.`complaint_product_password`, a.`complaint_is_disk_provided`, a.`complaint_product_is_under_waranty`, a.`complaint_product_operating_system`, a.`complaint_product_antivirus`, a.`complaint_description`, a.`complaint_problem_details`, a.`complaint_product_hardware_not_working`, a.`complaint_due_date`, a.`complaint_priority`, a.`complaint_sales_record_number`, a.`complaint_estimated_cost`, a.`complaint_product_condition_at_receiving`, a.`complaint_technician_id`, a.`complaint_tax_id`,  a.`complaint_created_date`, a.`complaint_created_by`, a.`complaint_status`, a.`complaint_active_status`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_email`, b.`customer_phone`, c.`customer_type_name`, d.`complaint_status_name`, d.`complaint_status_clolor_code`, CONCAT(e.`user_fname`, ' ', e.`user_lname`) as technician_name

		FROM `app_complaint_record` as a 

		LEFT JOIN `app_customer` as b ON  a.`complaint_customer_id`= b.`customer_id`

		LEFT JOIN `app_customer_type` as c ON b.`customer_type_id`= c.`customer_type_id`

		LEFT JOIN `app_complaint_status_master` as d ON a.`complaint_status`= d.`complaint_status_id`

		LEFT JOIN `app_system_user` as e ON a.`complaint_technician_id`= e.`user_id`

		WHERE 1";	

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$html="";	

		while($row = $dbc->db_fetch_assoc()){					

			$html.="<tr style=\"color:".$row['complaint_status_clolor_code'].";".(!$row['complaint_active_status']?"background-color:#ffebee;":""). "\">";

				$html.="<td>".dateView($row['complaint_created_date'])."</td>";

				$html.="<td>".$row['complaint_ticket_number']."</td>";

				$html.="<td>".$row['customer_name']."</td>";

				$html.="<td>".$row['complaint_description']."</td>";

				$html.="<td>".$row['complaint_problem_details']."</td>";

				$html.="<td>".$row['technician_name']."</td>";				

				$html.="<td>".$row['complaint_status_name']."</td>";

				$html.="<td>"."

				<a data-title=\"Update #".$row['complaint_ticket_number']." Request\" href=\"updatecomplaintrequest/".$row['complaint_id']."\" type=\"button\" class=\"btn btn-secondary redirect\"><i class=\"fa fa-lightbulb-o\"></i>&nbsp; View</a>

				<a data-title=\"Print Detail #".$row['complaint_ticket_number']." Request\" onclick=\"newWindow('".DOC::CDTL($row['complaint_id'])."')\" href=\"#\" type=\"button\" class=\"btn btn-secondary\"><i class=\"fa fa-print\"></i>&nbsp; Print</a>

				"."</td>";

			$html.="</tr>";		

		}

		return $html;	

	}

	

	function load()

	{

		$sql="SELECT a.*, b.`customer_id`, b.`customer_fname`, b.`customer_lname`, b.`customer_email`, b.`customer_company`, b.`customer_phone`, b.`customer_type_id`, c.`customer_address_id`, c.`customer_address_street_number`, c.`customer_address_route`, c.`customer_address_locality`, c.`customer_address_administrative_area`, c.`customer_address_country`, c.`customer_address_postcode`, c.`customer_address_geo_location`, GROUP_CONCAT(d.`problem_id`) as app_not_working_problem_mark, CONCAT(e.`user_fname`, ' ', e.`user_lname`) as technician_name, f.`customer_type_name`

		FROM `app_complaint_record` as a 

		LEFT JOIN `app_customer` as b ON  a.`complaint_customer_id`= b.`customer_id`

		LEFT JOIN `app_customer_address` as c ON a.`customer_address_id`= c.`customer_address_id`

		LEFT JOIN `app_complaint_record_problems` as d ON a.`complaint_id` = d.`complaint_id`

		LEFT JOIN `app_system_user` as e ON a.`complaint_technician_id`= e.`user_id`

		LEFT JOIN `app_customer_type` as f ON b.`customer_type_id`= f.`customer_type_id`

		WHERE a.`complaint_id` = '".intval($this->id)."'  GROUP BY a.`complaint_id`";

		//echo $sql;

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		if($dbc->db_num_rows()==1)

			return $dbc->db_fetch_assoc();

		else

			return false;	

	}

	

	function ComplaintAddedchart()

	{

		$sql="SELECT COUNT(`complaint_id`) as count, DATE_FORMAT(`complaint_created_date`, '\'%b-%y\'') as month FROM `app_complaint_record` WHERE 1 GROUP BY month ORDER BY `complaint_created_date`";

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$data = array();		

		while($row = $dbc->db_fetch_assoc())	

		$data[$row['month']]=$row['count'];

		return $this->getChartData($data);

	}

	

	function ComplaintOriginchart()

	{

		$sql="SELECT COUNT(a.`complaint_id`) as item1, CONCAT('''',b.`customer_type_name`,'''') as item2 FROM `app_complaint_record` as a RIGHT JOIN `app_customer_type` as b ON a.`complaint_origin_type_id`=b.`customer_type_id` WHERE 1 GROUP BY item2 ORDER BY item2";

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$data = array();		

		while($row = $dbc->db_fetch_assoc())	

		$data[$row['item2']]=$row['item1'];

		return $this->getChartData($data);

	}

	

	

	function complaintRecordProblemCountChart()

	{

		$sql="SELECT COUNT(a.`complaint_problem_id`) as item1, CONCAT('''',b.`problem_name`,'''') as item2  FROM `app_complaint_record_problems` as a INNER JOIN `app_hardware_problems` as b ON a.`problem_id`=b.`problem_id` WHERE 1 GROUP BY b.`problem_name` order by b.`problem_name`";

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$data = array();		

		while($row = $dbc->db_fetch_assoc())	

		$data[$row['item2']]=$row['item1'];

		return $this->getChartData($data);

	}

	

	function ComplaintDueDate()

	{

		$sql="SELECT count(a.`complaint_id`) as total, (SELECT count(b.`complaint_id`) FROM `app_complaint_record` as b WHERE b.`complaint_status` = '2' AND DATE_FORMAT(b.`complaint_created_date`, '\'%d-%b-%y\'')=DATE_FORMAT(a.`complaint_created_date`, '\'%d-%b-%y\'')) as completed, (SELECT count(b.`complaint_id`) FROM `app_complaint_record` as b WHERE b.`complaint_status` = '1' AND DATE_FORMAT(b.`complaint_created_date`, '\'%d-%b-%y\'')=DATE_FORMAT(a.`complaint_created_date`, '\'%d-%b-%y\'')) as toberepaired ,(SELECT count(b.`complaint_id`) FROM `app_complaint_record` as b WHERE b.`complaint_status` = '1' AND DATE_FORMAT(b.`complaint_created_date`, '\'%d-%b-%y\'')=DATE_FORMAT(a.`complaint_created_date`, '\'%d-%b-%y\'')) as toberepaired ,(SELECT count(b.`complaint_id`) FROM `app_complaint_record` as b WHERE b.`complaint_status` = '5' AND DATE_FORMAT(b.`complaint_created_date`, '\'%d-%b-%y\'')=DATE_FORMAT(a.`complaint_created_date`, '\'%d-%b-%y\'')) as wrapproval , DATE_FORMAT(a.`complaint_created_date`, '\'%b %d\'') as perday FROM `app_complaint_record` as a WHERE 1 GROUP BY DATE_FORMAT(a.`complaint_created_date`, '\'%b %d %y\'') ORDER BY a.`complaint_created_date` DESC LIMIT 30";

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$data = array();		

		while($row = $dbc->db_fetch_assoc())

		{	

			$data['total'][]		=	$row['total'];

			$data['completed'][]	=	$row['completed'];

			$data['toberepaired'][]	=	$row['toberepaired'];

			$data['wrapproval'][]	=	$row['wrapproval'];

			$data['other'][]		=	$row['total']-($row['completed']+$row['toberepaired']+$row['wrapproval']);

			$data['perday'][]		=	$row['perday'];			

		}		

		foreach($data as $key => $value)

			$return[$key]		= 	implode(" , " , array_reverse($value));			

		return $return;

	}

	function setReturn($return_id){
		$this->update(array("complaint_is_returned" => $return_id));
	}

	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL){

				

		global $app;

		$this->start 	= $start;

		$this->length	= $length;

		$this->aColumn = array(

					"TABLES"=>array(

								"`app_complaint_record`" => array(

															"column"=> array("`complaint_id`", "`complaint_ticket_number`", "`complaint_description`", "`complaint_problem_details`", "`complaint_created_date`", "`complaint_order_number`", "`complaint_product_serial`", "`complaint_is_returned`"),

															"reference" => "a",

															"join" => NULL

															),

								"`app_customer`" => array(

															"column"=> array("CONCAT(`customer_fname`, ' ', `customer_lname`) as customer_name", "`customer_fname`", "`customer_lname`", "`customer_id`", "`customer_email`", "`customer_phone`", "`customer_is_mobile_verified`", "`customer_is_email_verified`"),

															"reference" => "b",

															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_complaint_record`", "on"=>array("`customer_id`" => "`complaint_customer_id`"))

															),

								"`app_system_user`" => array(

															"column"=> array("CONCAT(e.`user_fname`, ' ', e.`user_lname`) as technician_name"),

															"reference" => "e",

															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_complaint_record`", "on"=>array("`user_id`" => "`complaint_technician_id`"))

															),

								"`app_complaint_status_master`" => array(

															"column"=> array("`complaint_status_name`", "`complaint_status_clolor_code`", "`complaint_status_light_clolor_code`"),

															"reference" => "f",

															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_complaint_record`", "on"=>array("`complaint_status_id`" => "`complaint_status`"))

															)

								),

					"ORDER"	=>array("complaint_created_date","`complaint_ticket_number`", "CONCAT(`customer_fname`,  `customer_fname`)", "`customer_email`", "CONCAT(e.`user_fname`, ' ', e.`user_lname`)", "`complaint_status_name`")						

							  );

		

		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		if($filter != NULL && count($filter)){
			foreach($filter as $filed=>$values){
				$filedCondArray = array();
				if(is_array($values)){
					foreach($values as $_val)
						$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($_val));
				}
				else
					$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($values));
				$conditionArray[] = $filedCondArray;	
			}
		}
		$this->condition = $conditionArray;

		if($this->customer!=0)

		$this->condition = array(

									array("b.`customer_id`","=",$this->customer)									

								);	

		$this->orderPosition = $orderPosition; 

		$this->orderDirection= $orderDirection;

		//$this->groupby = array("a.`complaint_id`");

			

		

		$sql = $this->getSql();

		//echo $sql; die;

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$num_rows_filtered= $dbc->db_num_rows($result);

		

		$dbcTotal 	= 	new DB();

		$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);

		$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	

			

		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());

		while($row = $dbc->db_fetch_assoc()){					

			$output["data"][] = array(								

								dateView($row["complaint_created_date"],"DATE"),

								$row["complaint_ticket_number"]." ".$row["complaint_order_number"],

								viewText($row["customer_name"]),

								"<i class=\"fa fa-phone-square fa-fw text-".($row["customer_is_mobile_verified"]?"success":"danger")."\"></i> ".viewText($row["customer_phone"]),

								$row["technician_name"],

								"<span class='badge badge-label' style=\"background-color:{$row["complaint_status_light_clolor_code"]}; color:{$row["complaint_status_clolor_code"]}\">".$row["complaint_status_name"]."</span>",

								"<div class=\"btn-group\">

  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">

    <i class=\"fa ".($row['complaint_is_returned'] ? "fa-history text-danger" : "fa-navicon")." fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>

  </button>

  <div class=\"dropdown-menu dropdown-menu-right\">

    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[complaint_id]|C', '$row[complaint_ticket_number] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>

    <a class=\"dropdown-item redirect\" href=\"".$app->siteUrl("viewcomplaint/".$row['complaint_id'])."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
	
		<a class=\"dropdown-item returnable\" data-module-title=\"RMA $row[complaint_ticket_number] return\" data-module-code=\"C\" data-module-id=\"{$row['complaint_id']}\" data-module-reference=\"{$row['complaint_ticket_number']}\" href=\"#\"><i class=\"fa fa-undo fa-fw\"></i> Return</a>".(isAdmin()?
	

    ("<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::CDTL($row['complaint_id'])."');\"><i class=\"fa fa-bookmark-o fa-fw\"></i> Label</a>

	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::CPRT($row['complaint_id'])."');\" ><i class=\"fa fa-print fa-fw\"></i> Print</a>

	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::CDTL($row['complaint_id'], 1)."');\"><i class=\"fa fa-clone fa-fw\"></i> Multi Label</a>	

    <a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::CINV($row['complaint_id'])."');\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Invoice</a>

	<a class=\"dropdown-item\" href=\"javascript:requestFeedback('".$row['complaint_id']."', 'C', '".$row['customer_id']."');\"><i class=\"fa fa-comments-o fa-fw\"></i> Request Feedback</a>"):"")."

  </div>

</div>",

								$_SESSION['app_theme'] != 'dark' ? null : null,
								"updatecomplaintrequest/".$row['complaint_id']

			);

		}

		return json_encode($output);		

	}

	

	function getReportComplaintDetials()

	{

		return "SELECT a.`complaint_ticket_number`, a.`complaint_product_serial`, a.`complaint_product_model`, a.`complaint_is_backup`, a.`complaint_problem_details`,  a.`complaint_due_date`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_email`, b.`customer_phone`, CONCAT(c.`user_fname`, ' ', c.`user_lname`) as technician, a.`complaint_tax_id`, CONCAT(d.`user_fname`, ' ', d.`user_lname`) as bookedby, e.`complaint_status_name`

		FROM `app_complaint_record` as a 

		LEFT JOIN `app_customer` as b ON  a.`complaint_customer_id`= b.`customer_id`

		LEFT JOIN `app_system_user` as c ON a.`complaint_technician_id` = c.`user_id`

		LEFT JOIN `app_system_user` as d ON a.`complaint_created_by` = d.`user_id`

		LEFT JOIN `app_complaint_status_master` as e ON a.`complaint_status`=e.`complaint_status_id`

		WHERE a.`complaint_id` = '".intval($this->id)."'";

	}

	static function todaysRmaActivity(){
		$sql = "
		SELECT COUNT(`complaint_id`) AS records, 'Picked' AS label, '#FF9900' AS color  FROM `app_complaint_record` WHERE DATE_FORMAT(`complaint_picking_time`,'%Y-%m-%d') = CURRENT_DATE UNION 
		SELECT COUNT(`complaint_id`) AS records, 'Packed' AS label, '#3399FF' AS color FROM `app_complaint_record` WHERE DATE_FORMAT(`complaint_packing_time`,'%Y-%m-%d') = CURRENT_DATE  UNION 
		SELECT COUNT(`complaint_id`) AS records, 'Processed' AS label, '#8BC34A' AS color FROM `app_complaint_record` WHERE DATE_FORMAT(`complaint_process_time`,'%Y-%m-%d') = CURRENT_DATE UNION 
		SELECT COUNT(`complaint_id`) AS records, 'Canceled' AS label, '#FF3300' AS color FROM `app_complaint_record` WHERE DATE_FORMAT(`complaint_cancel_time`,'%Y-%m-%d') = CURRENT_DATE";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$results_array = array();
		if($dbc->db_num_rows()>0)
		{					
			while($row = $dbc->db_fetch_assoc(true))
			{
				$results_array[] = $row;	
			}
		}
		return $results_array;
	}

	function getReportComplaintListSql()

	{

		return "SELECT a.`complaint_ticket_number`, a.`complaint_due_date`, a.`complaint_estimated_cost`, DATE_FORMAT(a.`complaint_created_date`, '%d-%b %y') as complaint_created_date, a.`complaint_problem_details`, a.`complaint_created_by`, a.`complaint_status`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_email`, b.`customer_phone`, c.`customer_type_name`, d.`complaint_status_name`, d.`complaint_status_clolor_code`, CONCAT(e.`user_fname`, ' ', e.`user_lname`) as technician_name

		FROM `app_complaint_record` as a 

		LEFT JOIN `app_customer` as b ON  a.`complaint_customer_id`= b.`customer_id`

		LEFT JOIN `app_customer_type` as c ON b.`customer_type_id`= c.`customer_type_id`

		LEFT JOIN `app_complaint_status_master` as d ON a.`complaint_status`= d.`complaint_status_id`

		LEFT JOIN `app_system_user` as e ON a.`complaint_technician_id`= e.`user_id`

		WHERE 1 ORDER BY a.`complaint_created_date`";

	}

	

	function loadInvoice()

	{

		$sql="SELECT LPAD(a.`complaint_id`,8,0) as invoice_number, a.`complaint_store_id`, a.`complaint_ticket_number` as invoice_ticket,  a.`complaint_ticket_number`, a.`complaint_tax_id`, CONCAT(a.`complaint_description`, '<br/>', a.`complaint_problem_details`) as description, DATE_FORMAT(a.`complaint_due_date`, '%d-%m-%Y') as tax_date,  a.`complaint_estimated_cost` as amount, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`, '<br/>Email: ', b.`customer_email`, ' Ph: ', b.`customer_phone`) as invoice_to, CONCAT(c.`customer_address_street_number`,', ', c.`customer_address_route`,', ', c.`customer_address_locality`,', ', c.`customer_address_administrative_area`,', ', c.`customer_address_country`,' Post Code - ', c.`customer_address_postcode`) as ship_to, GROUP_CONCAT(e.`problem_name` SEPARATOR ', ') as problem_list, COUNT(e.`problem_id`) as quantity

		FROM `app_complaint_record` as a 

		LEFT JOIN `app_customer` as b ON  a.`complaint_customer_id`= b.`customer_id`

		LEFT JOIN `app_customer_address` as c ON a.`customer_address_id`= c.`customer_address_id`

		LEFT JOIN `app_complaint_record_problems` as d ON a.`complaint_id` = d.`complaint_id`

		LEFT JOIN `app_hardware_problems` as e ON d.`problem_id` = e.`problem_id`

		WHERE a.`complaint_id` = '".intval($this->id)."'  GROUP BY a.`complaint_id`";

		//echo $sql;

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		if($dbc->db_num_rows()==1)

			return $dbc->db_fetch_assoc(true);

		else

			return false;	

	}

	

	function loadPrint()

	{

		$sql="SELECT LPAD(a.`complaint_id`,8,0) as invoice_number, a.`complaint_store_id`, a.`complaint_ticket_number` as invoice_ticket,  a.`complaint_ticket_number`, a.`complaint_description`, a.`complaint_problem_details`,  a.`complaint_product_hardware_not_working`, DATE_FORMAT(a.`complaint_due_date`, '%d-%m-%Y') as complaint_due_date, DATE_FORMAT(a.`complaint_created_date`, '%d-%m-%Y %h:%i%p') as request_date,  a.`complaint_product_serial` as serial_no, a.`complaint_product_model` as model_no, a.`complaint_is_backup`, a.`complaint_product_password` as password, a.`complaint_is_disk_provided` as disk_provided, a.`complaint_product_is_under_waranty` as under_waranty, a.`complaint_product_operating_system` as os, a.`complaint_product_antivirus` as antivirus, a.`complaint_product_condition_at_receiving` as product_condition, a.`complaint_estimated_cost` as estimated_cost, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as cuctomer_name, c.`complaint_status_name` as complaint_status, CONCAT(e.`user_fname`, ' ', e.`user_lname`) as technician_name, GROUP_CONCAT(f.`problem_name` separator ', ') as problem_list,  CONCAT(g.`user_fname`, ' ', g.`user_lname`) as bookedby

		FROM `app_complaint_record` as a 

		LEFT JOIN `app_customer` as b ON  a.`complaint_customer_id`= b.`customer_id`

		LEFT JOIN `app_complaint_status_master` as c ON a.`complaint_status`= c.`complaint_status_id`

		LEFT JOIN `app_complaint_record_problems` as d ON a.`complaint_id` = d.`complaint_id`

		LEFT JOIN `app_system_user` as e ON a.`complaint_technician_id`= e.`user_id`

		LEFT JOIN `app_hardware_problems` as f ON d.`problem_id` = f.`problem_id`

		LEFT JOIN `app_system_user` as g ON a.`complaint_created_by`= g.`user_id`

		WHERE a.`complaint_id` = '".intval($this->id)."'  GROUP BY a.`complaint_id` LIMIT 1";

		//echo $sql;

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		if($dbc->db_num_rows()==1)

			return $dbc->db_fetch_assoc(true);

		else

			return false;	

	}

	

	function getUnreadMessage()

	{

		global $app;

		$sql="SELECT count(`complaint_log_id`) as chat_count, `complaint_customer_id` FROM `app_complaint_log` WHERE `is_readed` = '0' AND `complaint_customer_id` != 0 GROUP BY `complaint_customer_id`";

		//echo $sql;	

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$data = array();

		if($result)

		if($dbc->db_num_rows()>0)

		{	

			while($row = $dbc->db_fetch_assoc())	

				$data[] = $row;	

		}

		return $data;

	}

	function getGlobalUnreadMessage()

	{

		global $app;

		$sql="SELECT count(`complaint_log_id`) as chat_count, `complaint_customer_id` as customer_id FROM `app_complaint_log` WHERE `is_readed` = '0' AND `complaint_customer_id` != 0 GROUP BY `complaint_customer_id` ";

		//echo $sql;	

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$row = $dbc->db_fetch_assoc();			

		return $row['chat_count'];

	}

	

	function complaintCollectionCountChart()

	{

		$sql="SELECT COUNT(`complaint_id`) as item1, 'repair' as item2 , DATE_FORMAT(`complaint_created_date`,'%b-%y') as month, DATE_FORMAT(`complaint_created_date`,'%y-%m') as ordercolumn FROM `app_complaint_record` WHERE 1 GROUP BY DATE_FORMAT(`complaint_created_date`,'%b-%y')

UNION

SELECT COUNT(`wc_id`) as item1, 'collection' as item2 , DATE_FORMAT(`wc_created_date`,'%b-%y') as month, DATE_FORMAT(`wc_created_date`,'%y-%m') as ordercolumn FROM  `app_wc_record` WHERE 1 GROUP BY DATE_FORMAT(`wc_created_date`,'%b-%y') ORDER BY ordercolumn desc, item2 desc LIMIT 12";

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$data = array();		
		$recordArray = array();
		while($row = $dbc->db_fetch_assoc())
		{
			if($row['item2']=='repair')
			{
				$recordArray[$row['month']]['complaint'] = $row['item1'];
				$recordArray[$row['month']]['collection'] = 0;
			}
			if($row['item2']=='collection')
			{
				$recordArray[$row['month']]['collection'] = $row['item1'];
			}
		}
		{	

			$data['MONTH']=array_keys($recordArray);

			$data['COMPLAINT']= array_column($recordArray, 'complaint');

			$data['COLLECTION']= array_column($recordArray, 'collection');

		}
		
		//print_r($recordArray);die;

		return array("MONTH"=>json_encode(array_reverse($data['MONTH'])), "COMPLAINT"=>json_encode(array_reverse($data['COMPLAINT'])), "COLLECTION"=>json_encode(array_reverse($data['COLLECTION'])));

	}

	

	function getListView($offset=0, $searchkeyword = "")

	{

		global $app;

		$condition = "";

		if($searchkeyword!="")

		{

			$searchkeyword = trim(strtolower($searchkeyword));

			$condition = " AND (LOWER(a.`complaint_ticket_number`) LIKE '%$searchkeyword%' OR a.`complaint_due_date` LIKE '%$searchkeyword%' OR a.`complaint_created_date` LIKE '%$searchkeyword%' OR LOWER(b.`customer_fname`) LIKE '%$searchkeyword%' OR LOWER(b.`customer_lname`) LIKE '%$searchkeyword%'  OR LOWER(e.`user_fname`) LIKE '%$searchkeyword%' OR LOWER(e.`user_lname`) LIKE '%$searchkeyword%' OR LOWER(c.`customer_type_name`) LIKE '%$searchkeyword%' OR LOWER(CONCAT(b.`customer_fname`, ' ', b.`customer_lname`)) LIKE '%$searchkeyword%' OR LOWER(CONCAT(e.`user_fname`, ' ', e.`user_lname`)) LIKE '%$searchkeyword%') ";

		}

		

		$sql="SELECT a.`complaint_id`, a.`complaint_ticket_number`, a.`complaint_due_date`,  a.`complaint_sales_record_number`, a.`complaint_created_date`, a.`complaint_status`, a.`complaint_active_status`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_email`, b.`customer_phone`, b.`customer_image` , c.`customer_type_name`, d.`complaint_status_name`, d.`complaint_status_clolor_code`, CONCAT(e.`user_fname`, ' ', e.`user_lname`) as technician_name, e.`user_image` as technician_image

		FROM `app_complaint_record` as a 

		LEFT JOIN `app_customer` as b ON  a.`complaint_customer_id`= b.`customer_id`

		LEFT JOIN `app_customer_type` as c ON b.`customer_type_id`= c.`customer_type_id`

		LEFT JOIN `app_complaint_status_master` as d ON a.`complaint_status`= d.`complaint_status_id`

		LEFT JOIN `app_system_user` as e ON a.`complaint_technician_id`= e.`user_id`

		WHERE 1 $condition ORDER BY a.`complaint_id` DESC Limit 10 OFFSET $offset";	

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$complaint_record_array = array();	

		while($row = $dbc->db_fetch_assoc(true)){					

			$row['customer_image']			= $app->basePath($row['customer_image'], false);

			$row['technician_image']		= $app->basePath($row['technician_image'], false);

			$row['complaint_created_date']	= dateView($row['complaint_created_date'], "DATE");

			$row['complaint_due_date']		= dateView($row['complaint_due_date'], "DATE");

			$complaint_record_array[] = $row;		

		}

		return $complaint_record_array;	

	}

	

	function getDetailsByCode($complaint_ticket_number)

	{

		$sql="SELECT a.`complaint_id`, a.`complaint_customer_id`, a.`customer_address_id`, a.`complaint_ticket_number`, a.`complaint_product_serial`, a.`complaint_product_model`, a.`complaint_is_backup`, a.`complaint_product_password`, a.`complaint_is_disk_provided`, a.`complaint_product_is_under_waranty`, a.`complaint_product_operating_system`, a.`complaint_product_antivirus`, a.`complaint_description`, a.`complaint_problem_details`, a.`complaint_product_hardware_not_working`, a.`complaint_due_date`, a.`complaint_priority`, a.`complaint_sales_record_number`, a.`complaint_estimated_cost`, a.`complaint_product_condition_at_receiving`, a.`complaint_technician_id`, a.`complaint_tax_id`, a.`complaint_status`, a.`complaint_created_date`, b.`customer_id`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_email`, b.`customer_image`, b.`customer_phone`, b.`customer_type_id`, c.`customer_address_id`, c.`customer_address_street_number`, c.`customer_address_route`, c.`customer_address_locality`, c.`customer_address_administrative_area`, c.`customer_address_country`, c.`customer_address_postcode`, c.`customer_address_geo_location`, CONCAT(c.`customer_address_street_number`,  ', ', c.`customer_address_route`, ', ', c.`customer_address_locality`, ', ', c.`customer_address_administrative_area`, ', ', c.`customer_address_country`, ' ', c.`customer_address_postcode`) as customer_full_address,  GROUP_CONCAT(d1.`problem_name`) as app_not_working_problem_mark, CONCAT(e.`user_fname`, ' ', e.`user_lname`) as technician_name,  e.user_image as technician_image, e.user_email as technician_email, e.user_phone as technician_phone, e.user_address as technician_address, f.`customer_type_name`, g.`complaint_status_name`, g.`complaint_status_clolor_code`

		FROM `app_complaint_record` as a 

		LEFT JOIN `app_customer` as b ON  a.`complaint_customer_id`= b.`customer_id`

		LEFT JOIN `app_customer_address` as c ON a.`customer_address_id`= c.`customer_address_id`

		LEFT JOIN `app_complaint_record_problems` as d ON a.`complaint_id` = d.`complaint_id`

		LEFT JOIN `app_hardware_problems` as d1 ON d.`problem_id` = d1.`problem_id`

		LEFT JOIN `app_system_user` as e ON a.`complaint_technician_id`= e.`user_id`

		LEFT JOIN `app_customer_type` as f ON b.`customer_type_id`= f.`customer_type_id`

		LEFT JOIN `app_complaint_status_master` as g ON a.`complaint_status`= g.`complaint_status_id`

		WHERE a.`complaint_ticket_number` = '$complaint_ticket_number'  GROUP BY a.`complaint_id`";

		//echo $sql;

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		if($dbc->db_num_rows()==1)

			return $dbc->db_fetch_assoc(true);

		else

			return false;	

	}

	

	static function getAndroidCustomerComplaint($customer_id)

	{

		$sql = "SELECT a.`complaint_id`, a.`complaint_ticket_number`, a.`complaint_status`,  a.`complaint_created_date`, a.`complaint_due_date`, b.`complaint_status_name`

FROM `app_complaint_record` as a 

INNER JOIN `app_complaint_status_master` as b ON a.`complaint_status`=b.`complaint_status_id`

WHERE a.`complaint_customer_id` = '$customer_id' GROUP BY a.`complaint_id`";

		$dbc 	= 	new DB();

		$result	=	$dbc->db_query($sql);

		$data = array();		

		while($row = $dbc->db_fetch_assoc())

		$data[] = $row;

		return $data;

	}

	function getRevenueReport(){
		$sql = "SELECT DATE_FORMAT(`complaint_created_date`, '%b-%y')as month, SUM(`complaint_estimated_cost`) as sale FROM `app_complaint_record` group by month order by `complaint_created_date` desc LIMIT 6";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		$recordArray = array();
		while($row = $dbc->db_fetch_assoc())
		{
			$recordArray['label'][]= $row['month'];
			$recordArray['value'][]= $row['sale'];
		}
		return $recordArray;

	}
	
	function getHardwareNotWorkingCount(){
	$sql = "SELECT a.`hardware_name`, (SELECT count(*) FROM `app_complaint_record` WHERE find_in_set(a.`hardware_id` , `complaint_product_hardware_not_working`)) as num FROM `app_hardware_type` as a where 1 having num > 0 ORDER BY a.`hardware_name` ";
	$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		$recordArray = array();
		while($row = $dbc->db_fetch_assoc())
		{
			$recordArray['label'][]= $row['hardware_name'];
			$recordArray['value'][]= $row['num'];
		}
		return $recordArray;	
	}

}


class ComplaintMedia extends DB{
	public $id;
	public $key_id		=	"repair_image_id";
	public $table_name	=	"app_repair_record_images";
	public $status		=	"repair_image_status";	
	public $repair_image_complaint_id	=	"repair_image_complaint_id";
	function __construct($repair_image_id=0){
				
		$this->id	=	$repair_image_id;
	}
	
	function getImageList($repair_image_complaint_id, $repair_image_media_type = 0){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `".$this->repair_image_complaint_id."` = '$repair_image_complaint_id' AND `".$this->status."` = '1' AND `repair_image_media_type` = '$repair_image_media_type' ";
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