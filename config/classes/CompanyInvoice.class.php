<?php
class CompanyInvoice extends DB{
	public $id;
	public $key_id	=	"company_invoice_id";
	public $table_name	=	"app_company_invoice";
	public $status	=	"company_invoice_status";	
	function __construct($company_invoice_id=0){
				
		$this->id	=	$company_invoice_id;
	}
	
	
	function add($company_invoice_name, $company_invoice_file_path, $company_invoice_status)
	{
		$sql="INSERT INTO `app_company_invoice`(`company_invoice_name`, `company_invoice_file_path`, `company_invoice_created_date`, `company_invoice_status`) VALUES ('$company_invoice_name', '$company_invoice_file_path', NOW(), '$company_invoice_status')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();
		return $this->id ;
	}
	
	function getRecords()
	{		
		global $app;		
		$sql="SELECT `company_invoice_id`, `company_invoice_name`, `company_invoice_file_path`, `company_invoice_created_date`, `company_invoice_status` FROM `app_company_invoice` WHERE `company_invoice_status` = 1 ORDER BY `company_invoice_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){								
				$html.="<tr id=\"row_$row[company_invoice_id]\">";
				$html.="<td>".$row['company_invoice_name']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['company_invoice_created_date'])."</td>";
				$html.="<td class=\"text-center\"><a target=\"new\" href=\"".$app->basePath($row['company_invoice_file_path'])."\"><i class=\"text-primary fa fa-cloud-download fa-2x\"></i></a></td>";
				$html.="<td><a href=\"#\" onclick=\"removePolicies($row[company_invoice_id])\"><i class=\"text-center icon-close icons font-4xl d-block mt-4 text-danger\"></i></a></td>";
								
				$html.="</tr>";	
		}
		return $html;	
	}
	
}
?>