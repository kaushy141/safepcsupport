<?php
if(isset($_SESSION['BATCH-PROD']['EXPORT'][$_REQUEST['id']]))
{
	$filename = "batch_product_" . date('Y-m-d-H-i-s') . ".csv";
	$sql = $_SESSION['BATCH-PROD']['EXPORT'][$_REQUEST['id']]['sql'];
	$col = $_SESSION['BATCH-PROD']['EXPORT'][$_REQUEST['id']]['col'];
	unset($_SESSION['BATCH-PROD']);
	$f = fopen('php://memory', 'w');
	$delimiter = ",";
	$dbc 	= 	new DB();
	$result	=	$dbc->db_query($sql);
	
	if($dbc->db_num_rows() > 0)
	{
		$fields = array();
		foreach($col as $_col){					
			array_push($fields, BatchProduct::$exportData[$_col]);	
		}		
		
		fputcsv($f, array_values($fields), $delimiter);
	
		$complaintLog = new ComplaintLog();
		while($item = $dbc->db_fetch_assoc(true))
		{
			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;
			if(isset($item['product_id'])) 
				unset($item['product_id']);
			
			$comments = '';
			if(in_array("comments", $col) && $logs = $complaintLog->getLog($product_id, 'B')){
				foreach($logs as $_log){
					$comments .= "$_log[complaint_log_text]\n";
				}
			}
			$item['comments'] = EscapeForCSV(trim(str_replace("<br />
", "\n", $comments), "\n"));
			fputcsv($f, array_values($item), $delimiter);
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