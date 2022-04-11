<?php

	$po_id = "";
	Modal::load(array('Po'));
    
    $data           = sanitizePostData($_POST);
	extract($data);	
	
	$po = new Po($po_id);
	if($po->isExist()){
		$poData = $po->getDetails();
		$po->update(array('po_is_sent_to_supplier'=>1));
		
		$supplier = new Supplier($poData['po_suplier_id']);
		$supplierData = $supplier->getDetails();
		
		$store = new Store($poData['po_store_id']);
		$storeData = $store->getDetails();
		
		$dataArray = array(
			"supplier_name" => $supplierData['supplier_name'],
			"po_code" => $poData['po_code'],
			"po_date"	=> dateView($poData['po_code'], 'DATE'),
			"store_name"	=> $storeData['store_title'].' ('.$storeData['store_name'].')'
		);
   
        
        $email     = new Email("#{$poData['po_code']} Purchase Order from {$storeData['store_title']}");
        $email->to($supplierData['supplier_email'], $supplierData['supplier_name'], $app->imagePath($supplierData['supplier_image']));
		$email->addFile(DOC::PO($poData['po_id']), $app->siteName . " - {$storeData['store_title']} Purchase order - {$poData['po_code']}.pdf");
		
		$email->template('po_to_supplier', $dataArray);
		$email->send();
    
		echo json_encode(array("200",  "success|Purchase order sent to supplier successfully"));
	}
	else
		echo json_encode(array("300",  "warning|Purchase order not Found"));

?>