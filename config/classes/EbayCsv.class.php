<?php
class EbayCsv extends DB{
	public $id;
	public $key_id	=	"ebay_import_id";
	public $table_name	=	"app_ebay_csv_import";
	public $status	=	"ebay_import_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
						
	function getDetails()
	{
		$sql="SELECT `ebay_import_id`, `ebay_import_file_name`, `ebay_import_record_count`, `ebay_import_created_date`, `ebay_import_user_id`, `ebay_import_status`, `ebay_import_ip_address` FROM `".$this->table_name."` WHERE  `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
	
		
	function add($ebay_import_file_name, $ebay_import_record_count, $ebay_import_user_id, $ebay_import_status, $ebay_import_ip_address)
	{
		$sql="INSERT INTO `app_ebay_csv_import`(`ebay_import_file_name`, `ebay_import_record_count`, `ebay_import_created_date`, `ebay_import_user_id`, `ebay_import_status`, `ebay_import_ip_address`) VALUES ('$ebay_import_file_name', '$ebay_import_record_count', NOW(), '$ebay_import_user_id', '$ebay_import_status', '$ebay_import_ip_address')";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function getRecords($searchKeyword=NULL, $jsonFormat = false)
	{		
		global $app;		
		$sql="SELECT a.`ebay_import_id`, a.`ebay_import_file_name`, a.`ebay_import_record_count`, a.`ebay_import_created_date`, a.`ebay_import_user_id`, a.`ebay_import_status`, a.`ebay_import_ip_address`, CONCAT(b.`user_fname`, ' ', b.`user_lname`) as uploader_name FROM  `".$this->table_name."` as a INNER JOIN `app_system_user` as b ON a.`ebay_import_user_id`= b.`user_id` WHERE 1 ORDER BY a.`ebay_import_id` DESC";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){								
				$html.="<tr>";
				$html.="<td>".$row['ebay_import_file_name']."</td>";
				$html.="<td>".$row['ebay_import_record_count']."</td>";
				$html.="<td>".$row['uploader_name']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['ebay_import_created_date'])."</td>";
				$html.="<td>".statusView($row['ebay_import_id'], $row['ebay_import_status'])."</td>";				
				$html.="</tr>";	
		}
		return $html;	
	}
	
	
	function getReportSql($format = NULL)
	{
		global $app;
		$image_path = $app->sitePath();
		if($format == NULL)
		return "SELECT a.`ebay_import_id`, a.`ebay_import_file_name`, a.`ebay_import_record_count`, a.`ebay_import_created_date`, a.`ebay_import_user_id`, a.`ebay_import_status`, a.`ebay_import_ip_address`, CONCAT(b.`user_fname`, ' ', b.`user_lname`) as uploader_name FROM  `".$this->table_name."` as a INNER JOIN `app_system_user` as b ON a.`ebay_import_user_id`= b.`user_id` WHERE 1";
	}

}
?>