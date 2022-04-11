<?php
class Licences extends DB{
	public $id;
	public $key_id	=	"licence_id";
	public $table_name	=	"app_licence";
	public $status	=	"licence_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}	
	
	function add($licence_name, $licence_folder, $licence_file_path, $licence_status)
	{
		$sql="INSERT INTO `app_licence`(`licence_name`, `licence_folder`, `licence_file_path`, `licence_created_date`, `licence_status`) VALUES ('$licence_name', '$licence_folder', '$licence_file_path', NOW(), '$licence_status')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();
		return $this->id ;
	}
	
	function getFolderName(){
		$sql="SELECT COUNT(`licence_id`) AS record, `licence_folder`, MIN(`licence_created_date`) as folder_created_date FROM `app_licence` WHERE `licence_status` = 1 GROUP BY `licence_folder` ORDER BY `licence_folder`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc()){								
				$record_array[] = $row;
		}
		return $record_array;
	}
	
	function getItemsByFolder($licence_folder){
		global $app;
		$condition = $licence_folder ? "`licence_folder` = '$licence_folder'" : "(`licence_folder` = '' OR `licence_folder` is null)";
		$sql="SELECT `licence_id`, `licence_folder`, `licence_name`, `licence_file_path`, `licence_created_date`, `licence_status` FROM `app_licence` WHERE `licence_status` = 1 AND $condition ORDER BY `licence_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){								
				$html.="<tr id=\"row_$row[licence_id]\">";
				$html.="<td>".$row['licence_folder']."</td>";
				$html.="<td>".$row['licence_name']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['licence_created_date'])."</td>";
				$html.="<td class=\"text-center\"><a target=\"new\" href=\"".$app->basePath($row['licence_file_path'])."\"><i class=\"text-primary fa fa-cloud-download fa-2x\"></i></a></td>";
				$html.="<td><a href=\"#\" onclick=\"removeLicence($row[licence_id])\"><i class=\"text-center icon-close icons font-4xl d-block mt-4 text-danger\"></i></a></td>";
								
				$html.="</tr>";	
		}
		return $html;
	}
	
	function getRecords()
	{		
		global $app;		
		$sql="SELECT `licence_id`, `licence_folder`, `licence_name`, `licence_file_path`, `licence_created_date`, `licence_status` FROM `app_licence` WHERE `licence_status` = 1 ORDER BY `licence_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){								
				$html.="<tr id=\"row_$row[licence_id]\">";
				$html.="<td>".$row['licence_folder']."</td>";
				$html.="<td>".$row['licence_name']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['licence_created_date'])."</td>";
				$html.="<td class=\"text-center\"><a target=\"new\" href=\"".$app->basePath($row['licence_file_path'])."\"><i class=\"text-primary fa fa-cloud-download fa-2x\"></i></a></td>";
				$html.="<td><a href=\"#\" onclick=\"removeLicence($row[licence_id])\"><i class=\"text-center icon-close icons font-4xl d-block mt-4 text-danger\"></i></a></td>";
								
				$html.="</tr>";	
		}
		return $html;	
	}
	
}
?>