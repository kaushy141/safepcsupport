<?php
class Supplier extends DB{
	public $id;
	public $key_id	=	"supplier_id";
	public $table_name	=	"app_supplier_record";
	public $status	=	"supplier_status";	
	function __construct($supplier_id=0){
				
		$this->id	=	$supplier_id;
	}
	
	static function getProcessArray(){
		return array(
			'Wholesale',
			'Retail'
		);
	}
	
	function getOptions($supplier_id=NULL)
	{
		$sql="SELECT `supplier_id`, `supplier_name`, `supplier_address`, `supplier_company_name` FROM `app_supplier_record` WHERE 1 ORDER BY `supplier_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select suplier - </option>";	
		while($row = $dbc->db_fetch_assoc(true)){					
			$html.="<option data-address=\"".htmlentities($row['supplier_address'])."\" value=\"$row[supplier_id]\" ".(($supplier_id==$row['supplier_id'])?"selected":"")." >".$row['supplier_name'].' - '.$row['supplier_company_name']."</option>";					
		}
		return $html;	
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_supplier_record`" => array(
															"column"=> array("`supplier_id`", "`supplier_name`", "`supplier_email`", "`supplier_contact`", "`supplier_address`", "`supplier_description`", "`supplier_doc_file`", "`supplier_status`", "`supplier_supply_process`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_supplier_type`" => array(
															"column"=> array("`supplier_type_name`"),
															"reference" => "b",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_supplier_record`", "on"=>array("`supplier_type_id`" => "`supplier_type_id`"))
															)								
								),
					"ORDER"	=>array("`supplier_name`", "`supplier_type_name`", "`supplier_email`", "`supplier_address`", "`supplier_status`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		if($filter != NULL && count($filter)){
			foreach($filter as $filed=>$values){
				$filedCondArray = array();
				if(is_array($values)){
					foreach($values as $_val)
						$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($_val));
				}
				else
					$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($values));
				$conditionArray[] = $filedCondArray;	
			}
		}
		$this->condition = $conditionArray;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`supplier_id`");
				
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		
		$dbcTotal 	= 	new DB();
		$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(								
								viewText($row["supplier_name"])."<br/>(".viewText($row["supplier_supply_process"]).")",
								viewText($row["supplier_type_name"]),
								viewText($row["supplier_contact"])."<br/>".viewText($row["supplier_email"]),
								viewText($row["supplier_address"]),
								viewText($row["supplier_description"]),
								statusView($row["supplier_id"],$row["supplier_status"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    <a class=\"dropdown-item redirect\" href=\"".$app->basePath("updatesupplier/".$row['supplier_id'])."\"><i class=\"fa fa-edit fa-fw\"></i> Update</a>
	
	<a class=\"dropdown-item\" target=\"new\" href=\"".DOC::SUPPLIER($row['supplier_id'])."\"><i class=\"fa fa-print fa-fw text-primary\"></i> Download</a>
	<a class=\"dropdown-item\" target=\"_blank\" href=\"".$app->basePath($row['supplier_doc_file'])."\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Download File</a>
  </div>
</div>",
								$row["supplier_id"]

			);
		}
		return json_encode($output);
	}
	
	function getSupplierTypeFilter(){
		$sql = "SELECT COUNT(`supplier_id`) as record, a.supplier_type_id, b.`supplier_type_name` FROM `".$this->table_name."` AS a INNER JOIN `app_supplier_type` AS b ON a.`supplier_type_id` = b.`supplier_type_id` GROUP BY a.`supplier_type_id` ORDER BY b.`supplier_type_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$record_array[] = $row;				
			}
		}
		return $record_array;
	}
	
}

class SupplierType extends DB{
	public $id;
	public $key_id	=	"supplier_type_id";
	public $table_name	=	"app_supplier_type";
	public $status	=	"supplier_type_status";	
	function __construct($supplier_type_id=0){				
		$this->id	=	$supplier_type_id;
	}
	
	function getOptions($supplier_type_id=0)
	{
		$sql="SELECT `supplier_type_id`, `supplier_type_name` FROM `app_supplier_type` WHERE 1 ORDER BY `supplier_type_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Supplier Type - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[supplier_type_id]\" ".(($supplier_type_id==$row['supplier_type_id'])?"selected":"")." >".$row['supplier_type_name']."</option>";					
		}
		return $html;	
	}
}

?>