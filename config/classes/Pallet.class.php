<?php
class Pallet extends DB{
	public $id;
	public $key_id	=	"pallet_id";
	public $table_name	=	"app_wc_pallet";
	public $status	=	"pallet_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function isFull()
	{
		$sql="SELECT `pallet_id` FROM `".$this->table_name."` WHERE `pallet_is_full` = '1' AND `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return $dbc->db_num_rows() ? true : false;		
	}
	
	function markFull()
	{
		$sql="UPDATE `".$this->table_name."` SET `pallet_is_full` = '1' WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		
	}
	function markUnFull()
	{
		$sql="UPDATE `".$this->table_name."` SET `pallet_is_full` = '0' WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);				
	}
	
	function getAvaialableSpace()
	{
		$sql="SELECT a.`pallet_capacity`, a.`pallet_is_full`, COUNT(b.`wpi_id`) as stored FROM `".$this->table_name."` AS a INNER JOIN `app_wc_pallet_items` AS b ON a.`pallet_id` = b.`wpi_pallet_id` WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if($dbc->db_num_rows() == 1)
		{
			$record = $dbc->db_fetch_assoc(true);
			if($record['pallet_is_full'] == 0)
				return max($record['pallet_capacity'] - $record['stored'], 0);
			else
				return 0;
		}
	}
		
	function getOptions($pallet_id=0)
	{
		global $app;
		$sql="SELECT `pallet_id`, `pallet_code`, `pallet_name`, `pallet_type`, `pallet_status`, `pallet_is_full`, `pallet_created_date`, `pallet_created_by` FROM `".$this->table_name."` WHERE (`pallet_is_full`!=1 AND `pallet_status` = 1) OR `pallet_id` = '$pallet_id'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Pallet - </option>";			
		while($row = $dbc->db_fetch_assoc(true)){				
			$html.="<option value=\"$row[pallet_id]\" ".($pallet_id==$row['pallet_id']?"selected":"").">".$row['pallet_type']."-".$row['pallet_code']."-".$row['pallet_name']."</option>";					
		}
		return $html;
	}	
	
	function getOptionsArray($pallet_id=0)
	{
		global $app;
		$sql="SELECT `pallet_id`, `pallet_code`, `pallet_name`, `pallet_type`, `pallet_status`, `pallet_is_full`, `pallet_created_date`, `pallet_created_by` FROM `".$this->table_name."` WHERE 1 ORDER BY `pallet_type` , `pallet_code`";	
		//`pallet_id` = '$pallet_id'  OR (`pallet_is_full`!=1 AND `pallet_status` = 1)
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data_array = array();			
		while($row = $dbc->db_fetch_assoc()){
			$data_array[] = array("pallet_id"=>$row['pallet_id'], "pallet_name"=>$row['pallet_type']."-".$row['pallet_code']."-".$row['pallet_name']);				
		}
		return $data_array;
	}
	
	
	function getPalletCode()
	{
		$sql="SELECT `pallet_code`, CURRENT_DATE as currentdate FROM `app_wc_pallet` WHERE YEAR(`pallet_created_date`) = YEAR(NOW()) AND MONTH(`pallet_created_date`) = MONTH(NOW()) ORDER BY `pallet_id` DESC LIMIT 1 ";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows())
		$data = $dbc->db_fetch_assoc();
		else
		{
			$data['pallet_code'] = date("ym")."00";
			$data['currentdate'] = date("Y-m-d");
		}
		$numcount = intval(substr($data['pallet_code'],4,2))+1;
		return date("ym",strtotime($data['currentdate'])).sprintf("%02d",$numcount);	
	}
	
	function getRecords()
	{		
		global $app;		
		$sql="SELECT a.`pallet_id`, a.`pallet_code`, a.`pallet_name`, a.`pallet_type`, a.`pallet_status`, a.`pallet_is_full`, COUNT(b.`wpi_pallet_id`) as pallet_totl_item FROM `app_wc_pallet` as a LEFT JOIN app_wc_pallet_items as b ON a.pallet_id = b.wpi_pallet_id WHERE 1 GROUP BY a.`pallet_id` ORDER BY a.`pallet_id` DESC";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){								
				$html.="<tr class=\"".($row['pallet_is_full']?"danger":""). "\">";
				$html.="<td>".$row['pallet_code']."</td>";
				$html.="<td class=\"hidden-xs hidden-sm hidden-md\">".$row['pallet_name']."</td>";			
				$html.="<td class=\"text-center\">".intval($row['pallet_totl_item'])."</td>";
				$html.="<td class=\"hidden-xs hidden-sm hidden-md\">".($row['pallet_is_full']?"Yes":"No")."</td>";
				$html.="<td>".statusView($row['pallet_id'], $row['pallet_status'])."</td>";
				$html.="<td>"."<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
    <a class=\"dropdown-item redirect\" data-title=\"Update #".$row['pallet_code']."\" href=\"".$app->siteUrl("updatepallet/".$row['pallet_id'])."\"><i class=\"fa fa-edit fa-fw\"></i> Update</a>
	<a class=\"dropdown-item redirect\" data-title=\"Manage #".$row['pallet_code']." items\" href=\"".$app->siteUrl("managepalletitems/".$row['pallet_id'])."\"><i class=\"fa fa-list-alt fa-fw\"></i> Manage Items</a>
	<a class=\"dropdown-item\" target=\"new\" data-title=\"Download pallet label\" href=\"".DOC::PALLETCODE($row['pallet_id'])."\"><i class=\"fa fa-barcode fa-fw\"></i> Download Label</a>
	<a class=\"dropdown-item\" target=\"new\" data-title=\"Download pallet sheet\" href=\"".DOC::PALLETITEMLIST($row['pallet_id'])."\"><i class=\"fa fa-file-excel-o fa-fw text-warning\"></i> Download sheet</a>
	<a class=\"dropdown-item\" target=\"new\" data-title=\"Download Detail sheet\" href=\"".DOC::PALLETDETAILSHEET($row['pallet_id'])."\"><i class=\"fa fa-file-excel-o fa-fw text-success\"></i> Pallet Items CSV</a>	
	"."</td>";
				$html.="</tr>";	
		}
		return $html;	
	}
	
}
?>