<?php
class Feedback extends DB{
	public $id;
	public $key_id	=	"feedback_id";
	public $table_name	=	"app_customer_feedback";
	public $status	=	"feedback_status";	
	function __construct($id=0){
				
		$this->id	=	$id;
	}
	
	
	function getDetails()
	{
		$sql="SELECT a.`feedback_id`, a.`feedback_customer_id`, a.`feedback_module_id`, a.`feedback_module_code`, a.`feedback_star_rating`, a.`feedback_title`, a.`feedback_comments`, a.`feedback_request_date`, a.`feedback_completion_date`, a.`feedback_ip_address`, a.`feedback_status`,  a.`feedback_store`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_email`, b.`customer_image`, b.`customer_phone`, c.`customer_type_name` FROM `app_customer_feedback` as a 
		LEFT JOIN `app_customer` as b ON  a.`feedback_customer_id`= b.`customer_id`
		LEFT JOIN `app_customer_type` as c ON b.`customer_type_id`= c.`customer_type_id` WHERE a.`".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
	
	function getFeebackIdByPattern($feedback_hashcode)
	{
		$pattern = "/[FD][-][0-9A-Z]{32}[-][0-9A-Z]{32}[-][A-Z]{1}[-][0-9A-Z]{32}/";
		if(preg_match($pattern, $feedback_hashcode)==1)
		{	
			$feedback_hashcode_array 	= explode("-", $feedback_hashcode);
			$feedback_id 				= strtoupper($feedback_hashcode_array[1]);
			$feedback_customer_id 		= strtoupper($feedback_hashcode_array[2]);
			$feedback_module_code	 	= strtoupper($feedback_hashcode_array[3]);
			$feedback_module_id			= strtoupper($feedback_hashcode_array[4]);
			
			$sql="SELECT `feedback_id` FROM `app_customer_feedback` WHERE 
						UPPER(MD5(`feedback_id`)) 	= '$feedback_id'
				    AND UPPER(MD5(`feedback_customer_id`)) = '$feedback_customer_id'
					AND UPPER(`feedback_module_code`) = '$feedback_module_code'
				    AND UPPER(MD5(`feedback_module_id`))   = '$feedback_module_id'";	
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			if($dbc->db_num_rows($result) == 1)	
			{	
				$data = $dbc->db_fetch_assoc();
				return $data['feedback_id'];			
			}
			else
				return false;
		}
		else
			return false;
	}
	
	function isValidForSubmission($feedback_hashcode)
	{
		$pattern = "/[FD][-][0-9A-Z]{32}[-][0-9A-Z]{32}[-][A-Z]{1}[-][0-9A-Z]{32}/";
		if(preg_match($pattern, $feedback_hashcode)==1)
		{	
			$feedback_hashcode_array 	= explode("-", $feedback_hashcode);
			$feedback_id 				= strtoupper($feedback_hashcode_array[1]);
			$feedback_customer_id 		= strtoupper($feedback_hashcode_array[2]);
			$feedback_module_code	 	= strtoupper($feedback_hashcode_array[3]);
			$feedback_module_id			= strtoupper($feedback_hashcode_array[4]);
			
			$sql="SELECT `feedback_id` FROM `app_customer_feedback` WHERE 
						UPPER(MD5(`feedback_id`)) 	= '$feedback_id'
				    AND UPPER(MD5(`feedback_customer_id`)) = '$feedback_customer_id'
					AND UPPER(`feedback_module_code`) = '$feedback_module_code'
				    AND UPPER(MD5(`feedback_module_id`))   = '$feedback_module_id'
					AND `feedback_status`      = '1'";
					
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			if($dbc->db_num_rows($result) == 1)	
			{	
				$data = $dbc->db_fetch_assoc();
				return $data['feedback_id'];			
			}
			else
				return false;
		}
		else
			return false;
	}
	
	function add($feedback_customer_id, $feedback_module_id, $feedback_module_code, $feedback_status, $feedback_store){
	
		$sql= "INSERT INTO `app_customer_feedback`(`feedback_customer_id`, `feedback_module_id`, `feedback_module_code`, `feedback_request_date`, `feedback_status`, `feedback_order_date`, `feedback_store`) VALUES ('$feedback_customer_id', '$feedback_module_id', '$feedback_module_code', NOW(), '$feedback_status', NOW(), '$feedback_store')";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_customer_feedback`" => array(
															"column"=> array("`feedback_id`", "`feedback_title`", "`feedback_star_rating`", "`feedback_module_code`",  "`feedback_status`", "`feedback_comments`", "`feedback_request_date`", "`feedback_completion_date`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_customer`" => array(
															"column"=> array("CONCAT(`customer_fname`, ' ', `customer_lname`) as customer_name", "`customer_email`", "`customer_image`", "`customer_phone`"),
															"reference" => "b",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_customer_feedback`", "on"=>array("`customer_id`" => "`feedback_customer_id`"))
															)								
								),
					"ORDER"	=>array("feedback_order_date", "CONCAT(`customer_fname`, `customer_fname`)", "`feedback_title`", "`feedback_star_rating`", "feedback_request_date",  "`feedback_status`", "`feedback_comments`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`feedback_id`");
			
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	
			
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		if($dbc->db_num_rows()>0)
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								"<img class=\"img-avatar\" src=\"".$app->imagePath($row["customer_image"])."\" height=\"40px\">",																													
								$row["customer_name"],
								viewText($row["feedback_title"]),
								$row["feedback_star_rating"],	
								dateView($row["feedback_request_date"],"DATE"),
								$row["feedback_status"]? "Requested":"Submitted"
			);
		}
		return json_encode($output);		
	}
	
	function getSendedFeedback($feedback_customer_id, $feedback_module_id, $feedback_module_code)
	{
		$sql="SELECT GROUP_CONCAT(`feedback_store`) as stores FROM `app_customer_feedback` WHERE 
				    `feedback_customer_id` = '$feedback_customer_id'
					AND UPPER(`feedback_module_code`) = UPPER('$feedback_module_code')
				    AND `feedback_module_id`   = '$feedback_module_id'";
			//echo $sql;		
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			if($dbc->db_num_rows($result) == 1)	
			{	
				$data = $dbc->db_fetch_assoc();
				if($data['stores']!="")
				{
					return explode(",", $data['stores']);
				}
				else
					return NULL;			
			}
			else
				return NULL;
	}
}
?>