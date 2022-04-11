<?php
class ComplaintTax extends DB{
	public $id;
	public $key_id	=	"complaint_tax_id";
	public $table_name	=	"aap_complaint_tax";
	public $status	=	"complaint_tax_status";	
	function __construct($complaint_tax_id=0){
				
		$this->id	=	$complaint_tax_id;
	}
	
	
	function getDetails()
	{
		$sql="SELECT `complaint_tax_id`, `complaint_vat_tax`, `complaint_e_tax`, `complaint_z_tax`, `complaint_n_tax`, `complaint_r_tax`, `complaint_s_tax`, `complaint_tax_created_date`, `complaint_tax_status` FROM `aap_complaint_tax` WHERE `".$this->key_id."` = '".$this->id."' ORDER BY `complaint_tax_created_date` DESC LIMIT 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
	
	function getCurrentId()
	{
		$sql="SELECT `complaint_tax_id` FROM `aap_complaint_tax` WHERE `complaint_tax_status` = '1' ORDER BY `complaint_tax_id` DESC LIMIT 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			$data = $dbc->db_fetch_assoc();
			return $data['complaint_tax_id'];
		}
		else
			return false;
	}	
	
	function add($complaint_vat_tax, $complaint_e_tax, $complaint_z_tax, $complaint_n_tax, $complaint_r_tax, $complaint_s_tax){
	
		$sql= "INSERT INTO `aap_complaint_tax`(`complaint_vat_tax`, `complaint_e_tax`, `complaint_z_tax`, `complaint_n_tax`, `complaint_r_tax`, `complaint_s_tax`, `complaint_tax_created_date`, `complaint_tax_status`) VALUES ('$complaint_vat_tax', '$complaint_e_tax', '$complaint_z_tax', '$complaint_n_tax', '$complaint_r_tax', '$complaint_s_tax', NOW(), 1)";		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
	function deactivateAll()
	{
		$sql= "UPDATE `aap_complaint_tax` SET `complaint_tax_status` = 0 ";		
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length)
	{	
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`aap_complaint_tax`" => array(
															"column"=> array("`complaint_tax_id`", "`complaint_vat_tax`", "`complaint_e_tax`", "`complaint_z_tax`", "`complaint_n_tax`", "`complaint_r_tax`", "`complaint_s_tax`", "`complaint_tax_created_date`", "`complaint_tax_status`"),
															"reference" => "a",
															"join" => NULL,
															"extracolumn"=>array("(select concat(a.complaint_tax_created_date, '/', b.complaint_tax_created_date) FROM `aap_complaint_tax` as b WHERE b.`complaint_tax_id` < a.`complaint_tax_id` LIMIT 1) as duration")
															)
								),
					"ORDER"	=>array("`complaint_vat_tax`", "`complaint_e_tax`", "`complaint_z_tax`", "`complaint_n_tax`", "`complaint_r_tax`", "`complaint_s_tax`", "`complaint_tax_status`",  "`complaint_tax_created_date`")						
							  );
		
		$this->searchKeyword = $searchKeyword;			
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		//$this->groupby = array("a.`complaint_id`");
			
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		$num_rows_total= $this->Count();	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){	
			$prev_tax_dutation = explode("/",$row["duration"]);		
			$output["data"][] = array(	
								number_format($row["complaint_vat_tax"],2),
								number_format($row["complaint_e_tax"],2),
								number_format($row["complaint_z_tax"],2),
								number_format($row["complaint_n_tax"],2),
								number_format($row["complaint_r_tax"],2),
								number_format($row["complaint_s_tax"],2),
								status($row["complaint_tax_status"]),
								$row["complaint_tax_status"]?"<a type=\"button\" class=\"btn btn-small btn-success redirect\" href=\"updatecomplainttaxinfo/".$row['complaint_tax_id']."\">Update</a>":($row["duration"]==NULL?"Begining":(dateView($prev_tax_dutation[0],"DATE")." - ".dateView($prev_tax_dutation[1],"DATE")))
																
								
			);
		}
		return json_encode($output);	
	}
}
?>