<?php
if(isset($_SESSION['WEB-ORDER']['WEBORDER']))
{
	$filename = "weborder_order_" . date('Y-m-d-H-i-s') . ".csv";
	$sql = $_SESSION['WEB-ORDER']['WEBORDER'];
	
	unset($_SESSION['WEB-ORDER']);
	$f = fopen('php://memory', 'w');
	$delimiter = ",";
	$dbc 	= 	new DB();
	$result	=	$dbc->db_query($sql);
	if($dbc->db_num_rows() > 0)
	{
		$HeaderFields = array(
			'Order Date',
			'Order Number',
			'Order Status',
			'Store',
			'Product name',
			'Product SKU',
<<<<<<< HEAD
=======
			'Product SRNO',
			'Product Purchase Price',
			'Product Sell Price',
			'Product Shipping Charges',
>>>>>>> 77a717f (Version 2)
			'Product Qty',
			'Product URL',
			'Comments'
		);
		fputcsv($f, $HeaderFields, $delimiter);
		$counter = 0;
		while($_wo = $dbc->db_fetch_assoc(true))
		{
			$wp = new WebsiteOrderProduct();
			$productlist = $wp->getList($_wo['web_order_id']);
			$proCounter = 0;
			foreach($productlist as $_pro){
				if($proCounter++ == 0){
					$cl = new ComplaintLog();
					$commentsList = $cl->getLog($_wo['web_order_id'], 'O');
					$comment = '';
					if($commentsList)
					foreach($commentsList as $_comment){
						$comment.= $_comment['complaint_log_text'].' - '.$_comment['logger_name'].' Time - '.date('d/M/Y h:iA',strtotime($_comment['complaint_log_time']));
					}
					fputcsv($f, array(
										date('d/M/Y', strtotime($_wo['web_order_created_date'])),
										$_wo['web_order_number'],
										$_wo['wc_status_name'],
										$_wo['store_name'],
										$_pro['wo_product_name'],
										$_pro['wo_product_sku'],
<<<<<<< HEAD
=======
										$_pro['wo_product_srno'],
										$_pro['wo_product_purchase_base_amount'],
										$_pro['wo_product_sell_base_price'],
										$_pro['wo_product_shipping_base_price'],
>>>>>>> 77a717f (Version 2)
										$_pro['wo_product_quantity'],
										$_pro['wo_product_url'],
										$comment
										), 
										$delimiter);
				}
				else
					fputcsv($f, array(
<<<<<<< HEAD
										'-',
										'-',
										'-',
										'-',
										$_pro['wo_product_name'],
										$_pro['wo_product_sku'],
=======
										date('d/M/Y', strtotime($_wo['web_order_created_date'])),
										$_wo['web_order_number'],
										$_wo['wc_status_name'],
										$_wo['store_name'],
										$_pro['wo_product_name'],
										$_pro['wo_product_sku'],
										$_pro['wo_product_srno'],
										$_pro['wo_product_purchase_base_amount'],
										$_pro['wo_product_sell_base_price'],
										$_pro['wo_product_shipping_base_price'],
>>>>>>> 77a717f (Version 2)
										$_pro['wo_product_quantity'],
										$_pro['wo_product_url'],
										''
										), $delimiter);
			}
		}
	}
	fseek($f, 0);
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');
	fpassthru($f);
}
?>