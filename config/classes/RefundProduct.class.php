<?php
class RefundProduct extends DB{
	public $id;
	public $key_id	=	"refund_pro_id";
	public $table_name	=	"app_refund_record_products";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	static function getRefundProducts($refund_pro_refund_id){
		$sql = "SELECT * FROM `app_refund_record_products` WHERE `refund_pro_refund_id` = '$refund_pro_refund_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){
			while($data = $dbc->db_fetch_assoc(true))
			$result_array[$data['refund_pro_ref_id']] = $data;
		}
		return $result_array;
	}	
}
?>