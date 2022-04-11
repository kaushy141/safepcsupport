<?php
class Isms extends DB{
	public $id;
	public $key_id	=	"isms_id";
	public $table_name	=	"app_isms_register";
	public $status	=	"isms_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}
	
	function add($isms_name, $isms_file_path, $isms_status){
		$sql = "INSERT INTO `app_isms_register` (`isms_name`, `isms_file_path`, `isms_created_date`, `isms_status`) 
		VALUES ('$isms_name', '$isms_file_path', now(), '$isms_status')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();
		return $this->id ;
	}
	function getRecords()
	{		
		global $app;		
		$sql="SELECT `isms_id`, `isms_name`, `isms_file_path`, `isms_created_date`, `isms_status` FROM `app_isms_register` WHERE `isms_status` = '1' ORDER BY `isms_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){								
				$html.="<tr id=\"row_$row[isms_id]\">";
				$html.="<td>".$row['isms_name']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['isms_created_date'])."</td>";
				$html.="<td class=\"text-center\"><a target=\"new\" href=\"".$app->basePath($row['isms_file_path'])."\"><i class=\"text-primary fa fa-cloud-download fa-2x\"></i></a></td>";
				$html.="<td><a href=\"#\" onclick=\"removeIsms($row[isms_id])\"><i class=\"text-center icon-close icons font-4xl d-block mt-4 text-danger\"></i></a></td>";
								
				$html.="</tr>";	
		}
		return $html;	
	}
	
}
?>