<?php
class Templates extends DB{
	public $id;
	public $key_id	=	"templates_id";
	public $table_name	=	"app_templates";
	public $status	=	"templates_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}	
	
	function add($templates_name, $templates_file_path, $templates_status)
	{
		$sql="INSERT INTO `app_templates`(`templates_name`, `templates_file_path`, `templates_created_date`, `templates_status`) VALUES ('$templates_name', '$templates_file_path', NOW(), '$templates_status')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();
		return $this->id ;
	}
	
	function getRecords()
	{		
		global $app;		
		$sql="SELECT `templates_id`, `templates_name`, `templates_file_path`, `templates_created_date`, `templates_status` FROM `app_templates` WHERE `templates_status` = 1 ORDER BY `templates_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc(true)){								
				$html.="<tr id=\"row_$row[templates_id]\">";
				$html.="<td>".$row['templates_name']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['templates_created_date'])."</td>";
				$html.="<td class=\"text-center\"><a target=\"new\" href=\"".$app->basePath($row['templates_file_path'])."\"><i class=\"text-primary fa fa-cloud-download fa-2x\"></i></a></td>";
				$html.="<td><a href=\"#\" onclick=\"removetemplates($row[templates_id])\"><i class=\"text-center icon-close icons font-4xl d-block mt-4 text-danger\"></i></a></td>";
								
				$html.="</tr>";	
		}
		return $html;	
	}
	
}
?>