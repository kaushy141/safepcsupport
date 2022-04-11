<?php

	$code = "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if($code !="")
	{
		$code = strtoupper($code);
		$format = $path = "";
		if(preg_match("/[0-9]{4}[W]{1}[0-9]{3}/", $code)==1 && strlen($code)==8){ //1806W025
			$format = "Collection";
			$class = new Collection();
			$record = $class->getDetailsByCode($code);
			$path = "updatecollection/$record[wc_id]";
		}
		elseif(preg_match("/[0-9]{2}[A-Z]{3}[0-9]{5}/", $code)==1 && strlen($code)==10){ //18CPC00050
			$format = "Complaint";
			$class = new Complaint();
			$record = $class->getDetailsByCode($code);
			$path = "viewcomplaint/$record[complaint_id]";
		}
		elseif(preg_match("/[0-9]{8}/", $code)==1 && strlen($code)==8){ //18060033
			$format = "SalesInvoice";
			$class = new SalesInvoice();
			$record = $class->load($code);
			$path = "salesinvoice/$record[sales_invoice_id]";
		}
		elseif(preg_match("/[0-9]{4}[W]{1}[0-9]{7}/", $code)==1 && strlen($code)==12){ //1806W0250005
			$format = "CollectionProcess";
			$record = CollectionProcess::getProcessByCode($code);
			$path = "processcollection/$record[wc_process_wc_id]/$code";
		}
		elseif(preg_match("/[0-9]{4}[B]{1}[0-9]{5}/", $code)==1 && strlen($code)==10){ //2001B00001
			$format = "BatchProduct";
			$record = BatchProduct::getBatchProductByCode($code);
			$path = "updatebatchproduct/$record[product_id]";
		}
		elseif(preg_match("/[0-9]{2}[R]{1}[A-Z]{2}[0-9]{2}/", $code)==1 && strlen($code)==7){ //20RAA43
			Modal::load(array('Refund'));
			$format = "Refund";
			$record = Refund::getRefundByCode($code);
			$path = "viewrefund/".md5($record['refund_id']);
		}
		if($path != "")
		{
			echo json_encode(array("200",  "danger|Loading $format <b>$code</b> details...", $path));		
		}
		else
			echo json_encode(array("300",  "danger|No record found with <b>$code</b> code"));
	}
	else
		echo json_encode(array("300",  "danger|Input code <b>$code</b> doesn't match"));

?>