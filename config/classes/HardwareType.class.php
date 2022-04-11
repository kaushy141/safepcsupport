<?php
class HardwareType extends DB{
	public $id;
	public $key_id	=	"hardware_id";
	public $table_name	=	"app_hardware_type";
	public $status	=	"hardware_status";	
	function __construct($hardware_id=0){
				
		$this->id	=	$hardware_id;
	}
	
	function IsCodeAvailable($hardware_code,$hardware_id=NULL)
	{
		$condition="";
		$hardware_code = checkData($hardware_code);
		if($hardware_id!=NULL)
		$condition=" AND `hardware_id`!='$hardware_id'";
		$sql="SELECT * FROM `app_hardware_type` WHERE LOWER(`hardware_code`) = LOWER('".$hardware_code."') $condition AND UPPER(`hardware_code`)!='RMA'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return ($dbc->db_num_rows()==1)?false:true;			
	}
	function IsNameAvailable($hardware_name,$hardware_id=NULL)
	{
		$condition="";
		$hardware_name = checkData($hardware_name);
		if($hardware_id!=NULL)
		$condition=" AND `hardware_id`!='$hardware_id'";
		$sql="SELECT * FROM `app_hardware_type` WHERE LOWER(`hardware_name`) = LOWER('".$hardware_name."') $condition";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return ($dbc->db_num_rows()==1)?false:true;			
	}
	
	function add($hardware_name, $hardware_code, $hardware_status)
	{
		$sql = "INSERT INTO `app_hardware_type`(`hardware_code`, `hardware_name`, `hardware_created_date`, `hardware_status`) VALUES ('$hardware_code', '$hardware_name', NOW(), '$hardware_status')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();	
		return $this->id;
	}
		
	function getDetails()
	{
		//$hardware_id!=NULL?$this->hardware_id=$hardware_id:"";
		$sql="SELECT `hardware_id`, `hardware_code`, `hardware_name`, `hardware_created_date`, `hardware_status` FROM `app_hardware_type` WHERE `hardware_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function getRecords()
	{
		$sql="SELECT `hardware_id`, `hardware_code`, `hardware_name`, `hardware_created_date`, `hardware_status` FROM `app_hardware_type` WHERE 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<tr>";
				$html.="<td>".$row['hardware_name']."</td>";
				$html.="<td>".$row['hardware_code']."</td>";
				$html.="<td>".$row['hardware_created_date']."</td>";
				$html.="<td>".statusView($row['hardware_id'], $row['hardware_status'])."</td>";
			$html.="</tr>";		
		}
		return $html;	
	}
	
	function getCheckbox($checkbox_name, $hardware_type_id_array=array())
	{
		$sql="SELECT `hardware_id`, `hardware_name` FROM `app_hardware_type` WHERE `hardware_status`=1 ORDER BY `hardware_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<div class=\"col-xs-12 col-sm-6 col-md-4 col-lg-3\" style=\"margin-bottom:5px;\"><div class=\"row\"><label for=\"".$checkbox_name."_".$row['hardware_id']."\" class=\"switch switch-icon switch-primary\">  
                                        <input  id=\"".$checkbox_name."_".$row['hardware_id']."\" name=\"".$checkbox_name."[]\" value=\"$row[hardware_id]\" class=\"switch-input $checkbox_name\" ".((in_array($row['hardware_id'], $hardware_type_id_array)?"checked=\"\"":""))." type=\"checkbox\">
                                        <span class=\"switch-label\" data-on=\"\" data-off=\"\"></span>
                                        <span class=\"switch-handle\"></span>
                                    </label> <label  for=\"".$checkbox_name."_".$row['hardware_id']."\"> &nbsp; $row[hardware_name]</label></div></div>";					
		}
		return $html;	
	}
	
	function listName($hardware_type_id_array=array())
	{
		$sql="SELECT `hardware_id`, `hardware_name` FROM `app_hardware_type` WHERE `hardware_status`=1 ORDER BY `hardware_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){
			if(in_array($row['hardware_id'], $hardware_type_id_array))					
			$html.="<div class=\"col-sm-4 col-md-3 col-xs-6\" style=\"margin-bottom:5px;\"><div class=\"row\"><label> &nbsp; ".icon(1, $row['hardware_name'])."</label></div></div>";					
		}
		return $html;
	}
	
	function getOptions($hardware_id=NULL)
	{
		$sql="SELECT `hardware_id`, `hardware_name` FROM `app_hardware_type` WHERE `hardware_status`=1 ORDER BY hardware_name";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Hardware Type - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[hardware_id]\" ".(($hardware_id==$row['hardware_id'])?"selected":"")." >".$row['hardware_name']."</option>";					
		}
		return $html;	
	}
	
	function simplelistName($hardware_type_id_array=array(), $separator = ",")
	{
		if(count(array_filter($hardware_type_id_array))>0 && !empty(array_filter($hardware_type_id_array)))
		{
			$sql="SELECT GROUP_CONCAT(`hardware_name` separator '$separator ') as name FROM `app_hardware_type` WHERE `hardware_id` IN (".implode(",",array_filter($hardware_type_id_array)).") ORDER BY `hardware_name`";	
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			if($dbc->db_num_rows())	
			{
				$row = $dbc->db_fetch_assoc();
				return $row['name'];
			}
			else
				return "";				
		}
		else
			return "";
	}
	
}
?>