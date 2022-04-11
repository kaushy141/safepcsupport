<?php
class BatchProductSaleHistory extends DB{
	public $id;
	public $key_id	=	"bpca_id";
	public $table_name	=	"app_batch_product_customer_allocation";
	public $status	=	"bpca_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function getDetails(){
		$sql="SELECT * FROM `".$this->table_name."` as a 
		LEFT JOIN `app_customer` as b ON a.`bpca_customer_id` = b.`customer_id` 
		LEFT JOIN `app_customer_address` as c ON a.`bpca_customer_address_id`= c.`customer_address_id` WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows() == 1){
			return $dbc->db_fetch_assoc(true);
		}
		else
			return false;
	}
	function getRecords($bpca_product_id = 0, $json = false)
	{
		$condition = $bpca_product_id ? " AND a.`bpca_product_id` = '$bpca_product_id'":"";
		$sql="SELECT a.*, b.customer_fname, b.customer_lname, b.customer_image, c.store_name, c.store_icon FROM `".$this->table_name."` as a 
		LEFT JOIN `app_customer` as b ON a.`bpca_customer_id` = b.`customer_id` 
		LEFT JOIN `app_store_master` as c ON a.`bpca_store_id` = c.`store_id` WHERE `".$this->status."` = '1' $condition";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$recordArray = array();	
		while($row = $dbc->db_fetch_assoc()){					
			$recordArray[] = array("<img class=\"img-avatar\" src=\"".getResizeImage($row["customer_image"], 50)."\" height=\"40px\">",
				$row["customer_fname"]." ".$row["customer_lname"],
				"<img class=\"img-avatar\" src=\"".getResizeImage($row["store_icon"], 50)."\" height=\"40px\">".$row["store_name"],
				dateView($row['bpca_sell_date'], "FULL"),
				($row['bpca_is_returned'] ? dateView($row['bpca_returned_date'], "DATE") : "Not Returned"),
				$row['bpca_sell_price']." GBP",
				"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-ellipsis-v fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    <a class=\"dropdown-item\" href=\"javascript:viewSellDetail(".$row['bpca_id'].")\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
	<a class=\"dropdown-item\" href=\"javascript:confirmMessage.Set('Are you sure to delete product Sale Information...?', 'removeProductSalesHistory', $row[bpca_id])\"><i class=\"fa fa-trash fa-fw text-danger \"></i> Remove</a>
  </div>
</div>"
				);			
		}
		if($json)			
		return $recordArray;	
		else{
			$html = "";
			foreach($recordArray as $row)
			$html .= "<tr><td>".implode("</td><td>", $row)."</td></tr>";
			return $html;
		}
	}
}
?>