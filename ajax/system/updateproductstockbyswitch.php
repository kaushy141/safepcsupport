<?php

	$origin = "";
	$id	 = $value = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($id != 0){
		if($origin == "BatchProduct"){
			$product = new BatchProduct($id);
			if($product->isExist()){
				$product->update(array("product_in_stock" => $value ? 1 : 0));
				echo json_encode(array("200", "success|Product Stock status updated"));
			}
			else
				echo json_encode(array("300",  "danger|No product matched."));
		}
		elseif($origin == "CollectionProcess"){
			$product = new CollectionProcess();
			if($product->loadById($id)){
				$product->update(array("wc_process_item_stock" => $value ? 1 : 0));
				echo json_encode(array("200", "success|Product Stock status updated"));
			}
			else
				echo json_encode(array("300",  "danger|Input format is not correct."));
		}
		else
			echo json_encode(array("300",  "danger|Input format is not correct."));
	}
	else
		echo json_encode(array("300",  "danger|No product matched."));

?>