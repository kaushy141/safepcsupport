<?php
class Policies extends DB{
	public $id;
	public $key_id	=	"policies_id";
	public $table_name	=	"app_policies";
	public $status	=	"policies_status";	
	function __construct($policies_id=0){
				
		$this->id	=	$policies_id;
	}
	
	
	function add($policies_name, $policies_file_path, $policies_status)
	{
		$sql="INSERT INTO `app_policies`(`policies_name`, `policies_file_path`, `policies_created_date`, `policies_status`) VALUES ('$policies_name', '$policies_file_path', NOW(), '$policies_status')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();
		return $this->id ;
	}
	
	function getRecords()
	{		
		global $app;		
		$sql="SELECT `policies_id`, `policies_name`, `policies_file_path`, `policies_created_date`, `policies_status` FROM `app_policies` WHERE `policies_status` = 1 ORDER BY `policies_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){								
				$html.="<tr id=\"row_$row[policies_id]\">";
				$html.="<td>".$row['policies_name']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['policies_created_date'])."</td>";
				$html.="<td class=\"text-center\"><a target=\"new\" href=\"".$app->basePath($row['policies_file_path'])."\"><i class=\"text-primary fa fa-cloud-download fa-2x\"></i></a></td>";
				$html.="<td><a href=\"#\" onclick=\"removePolicies($row[policies_id])\"><i class=\"text-center icon-close icons font-4xl d-block mt-4 text-danger\"></i></a></td>";
								
				$html.="</tr>";	
		}
		return $html;	
	}
	
}
?>