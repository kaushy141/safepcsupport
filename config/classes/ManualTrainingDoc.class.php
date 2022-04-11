<?php
class ManualTrainingDoc extends DB{
	public $id;
	public $key_id	=	"manual_id";
	public $table_name	=	"app_manual_training_doc";
	public $status	=	"manual_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}
	
	
	function add($manual_name, $manual_file_path, $manual_status)
	{
		$sql="INSERT INTO `app_manual_training_doc`(`manual_name`, `manual_file_path`, `manual_created_date`, `manual_status`) VALUES ('$manual_name', '$manual_file_path', NOW(), '$manual_status')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();
		return $this->id ;
	}
	
	function getRecords()
	{		
		global $app;		
		$sql="SELECT `manual_id`, `manual_name`, `manual_file_path`, `manual_created_date`, `manual_status` FROM `app_manual_training_doc` WHERE `manual_status` = 1 ORDER BY `manual_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){								
				$html.="<tr id=\"row_$row[manual_id]\">";
				$html.="<td>".$row['manual_name']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['manual_created_date'])."</td>";
				$html.="<td class=\"text-center\"><a target=\"new\" href=\"".$app->basePath($row['manual_file_path'])."\"><i class=\"text-primary fa fa-cloud-download fa-2x\"></i></a></td>";
				$html.="<td><a href=\"#\" onclick=\"confirmMessage.Set('Are you sure to delete document ...?', 'removeManual', $row[manual_id]);\"><i class=\"text-center icon-close icons font-4xl d-block mt-4 text-danger\"></i></a></td>";
								
				$html.="</tr>";	
		}
		return $html;	
	}
	
}
?>