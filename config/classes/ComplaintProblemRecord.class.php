<?php
class ComplaintProblemRecord extends DB{
	public $id;
	public $key_id	=	"complaint_problem_id";
	public $table_name	=	"app_complaint_record_problems";
	public $status	=	"is_problem_fixed";	
	function __construct($complaint_problem_id=0){
				
		$this->id	=	$complaint_problem_id;
	}
			
	function add($complaint_id, $problem_id, $is_problem_fixed){	
		$sql= "INSERT INTO `app_complaint_record_problems`(`complaint_id`, `problem_id`, `is_problem_fixed`, `problem_created_date`) VALUES ('$complaint_id', '$problem_id', '$is_problem_fixed', NOW())";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
	function delete($complaint_id, $problem_id){	
		$sql= "DELETE FROM `app_complaint_record_problems` WHERE `complaint_id`='$complaint_id' AND `problem_id`='$problem_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
}
?>