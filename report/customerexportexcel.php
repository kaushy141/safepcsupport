<?php
if(isset($_SESSION['REPORT']['CUSTOMER-EXPORT']))
{
	$filename = "customer_list_" . date('Y-m-d-H-i-s') . ".csv";
	$sql = $_SESSION['REPORT']['CUSTOMER-EXPORT'];
	unset($_SESSION['REPORT']);
	$f = fopen('php://memory', 'w');
	$delimiter = ",";
	$dbc 	= 	new DB();
	$result	=	$dbc->db_query($sql);
	if($dbc->db_num_rows() > 0)
	{
		$HeaderFields = array(
			'Sr.No.',
			'Name',
			'Type',
			'Email',
			'Phone',
			'Company',
			'Status',
			'Create Date'
		);
		fputcsv($f, $HeaderFields, $delimiter);
		$counter = 1;
		while($_customer = $dbc->db_fetch_assoc(true))
		{
			fputcsv($f, array(
							$counter++,
							$_customer['customer_fname']. ' ' .$_customer['customer_lname'],
							$_customer['customer_type_name'],
							$_customer['customer_email'],
							$_customer['customer_phone'],
							$_customer['customer_company'],
							$_customer['customer_status'] ? 'Active':'Deactive',
							date('d/M/Y', strtotime($_customer['customer_created_date'])),
							), 
							$delimiter);
		}
		
	}
	fseek($f, 0);
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');
	fpassthru($f);
}
else
	echo 'No Data';
?>