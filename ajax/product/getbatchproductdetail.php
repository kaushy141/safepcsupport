<?php

    $product_id  = "";
    $data           = sanitizePostData($_POST);
    extract($data);
	if($product_id != ""){
		$batchProduct = new BatchProduct($product_id);
		if($batchProduct->isExist()){		
			
			echo json_encode(array("200", "success|Product loaded successfully", $batchProduct->getDetails()));
		}
		else
			echo json_encode(array("300",  "danger|Products not available."));
	}
	else
		echo json_encode(array("300",  "danger|Security key missmatch."));

?>