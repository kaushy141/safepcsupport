<?php
if(isset($_SESSION['REPORT']['SQL']))
{
	$filename = "sales_profit_" . date('Y-m-d-H-i-s') . ".csv";
	$f = fopen('php://memory', 'w');
	$delimiter = ",";
	$dbc 	= 	new DB();
	$result	=	$dbc->db_query($_SESSION['REPORT']['SQL']);
	//unset($_SESSION['REPORT']['SQL']);
	if($dbc->db_num_rows() > 0)
	{
		$fields = array('source' => 'Source', 'store' => 'Store', 'customer'=>'Customer', 'order_number'=>'OrderNo', 'purchase_price'=>'Purchase', 'sell_price'=>'Sell', 'shipping_paid'=>'Ship', 'payment'=>'Payment', 'base_profit'=>'Profit');
		
		fputcsv($f, array_values(array_values($fields)), $delimiter);
		$keyArray = array_keys(array_values($fields));
		$complaintLog = new ComplaintLog();
		while($row = $dbc->db_fetch_assoc(true))
		{	
			$row['base_profit'] =  round($row['sell_price'] - ($row['purchase_price'] + $row['shipping_paid']), 2);
			$filteredArray = array_intersect_key($row, $fields);
			fputcsv($f, array_values($filteredArray), $delimiter);
		}
	}
	fseek($f, 0);
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');
	fpassthru($f);
}
function EscapeForCSV($value)
{
  return '"' . str_replace('"', '""', $value) . '"';
}
?>