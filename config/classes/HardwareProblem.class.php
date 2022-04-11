<?php
class HardwareProblem extends DB{
	public $id;	
	public $key_id	=	"problem_id";
	public $table_name	=	"app_hardware_problems";
	public $status	=	"problem_status";	
	function __construct($problem_id=0){
				
		$this->id	=	$problem_id;
	}	
	
	function getDetails()
	{
		//$hardware_id!=NULL?$this->hardware_id=$hardware_id:"";
		$sql="SELECT `problem_id`, `problem_name`, `problem_created_date`, `problem_status` FROM `app_hardware_problems` WHERE 1 `problem_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function getOptions($problem_id=NULL)
	{
		$sql="SELECT `problem_id`, `problem_name`, `problem_created_date`, `problem_status` FROM `app_hardware_problems` WHERE 1 ORDER BY `problem_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Hardware Problem - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[problem_id]\" ".(($problem_id==$row['problem_id'])?"selected":"")." >".$row['problem_name']."</option>";					
		}
		return $html;	
	}
	
	function getCheckbox($checkbox_name, $problem_checked_id_array=array())
	{
		$sql="SELECT `problem_id`, `problem_name`, `problem_created_date`, `problem_status` FROM `app_hardware_problems` WHERE `problem_status`=1 ORDER BY `problem_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<div class=\"col-xs-12 col-sm-6 col-md-6 col-lg-4\" style=\"margin-bottom:5px;\"><div class=\"row\"><label class=\"switch switch-icon switch-danger\"  for=\"".$checkbox_name."_".$row['problem_id']."\">  
                                        <input  id=\"".$checkbox_name."_".$row['problem_id']."\" name=\"".$checkbox_name."[]\" value=\"$row[problem_id]\" class=\"switch-input $checkbox_name\" ".((in_array($row['problem_id'], $problem_checked_id_array)?"checked=\"\"":""))." type=\"checkbox\">
                                        <span class=\"switch-label\" data-on=\"\" data-off=\"\"></span>
                                        <span class=\"switch-handle\"></span>
                                    </label> <label  for=\"".$checkbox_name."_".$row['problem_id']."\"> &nbsp; $row[problem_name]</label></div></div>";					
		}
		return $html;	
	}
	
	function listName($problem_checked_id_array=array())
	{
		$sql="SELECT `problem_id`, `problem_name`, `problem_created_date`, `problem_status` FROM `app_hardware_problems` WHERE `problem_status`=1 ORDER BY `problem_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){
			if(in_array($row['problem_id'], $problem_checked_id_array))					
			$html.="<div class=\"col-sm-6 col-md-3 col-xs-12\" style=\"margin-bottom:5px;\"><div class=\"row\"><label> &nbsp; ".icon(1,$row['problem_name'])."</label></div></div>";					
		}
		return $html;
	}
	
	function getRecords()
	{
		$sql="SELECT `problem_id`, `problem_name`, `problem_created_date`, `problem_status` FROM `app_hardware_problems` WHERE 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<tr id=\"data_value_record_row_".$row['problem_id']."\">";
				$html.="<td class=\"problem_name_text\" data-value=\"$row[problem_name]\">".$row['problem_name']."</td>";
				$html.="<td>".$row['problem_created_date']."</td>";
				$html.="<td class=\"problem_name_status\" data-value=\"$row[problem_status]\">".statusView($row['problem_id'], $row['problem_status'])."</td>";
			$html.="<td><a class=\"dropdown-item\" href=\"javascript:updateHardwareProblemRecord(".$row['problem_id'].");\"><i class=\"fa fa-share-square-o fa-fw\"></i>Edit</a></td>";			
			$html.="</tr>";		
		}
		return $html;	
	}
	
	function add($problem_name, $problem_status)
	{
		$sql="INSERT INTO `app_hardware_problems`(`problem_name`, `problem_created_date`, `problem_status`) VALUES ('$problem_name', NOW(), '$problem_status')";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
}
?>