<?php

    $wc_id = 0;
	$usertype = 2;
    $data  = sanitizePostData($_POST);
    extract($data);
    $collection     = new Collection($wc_id);
    $collectionData = $collection->load();
    $email          = new Email("New Collection Certificate for #$collectionData[wc_code]");
    if ($usertype == 1)
        $email->to($collectionData['collection_manager_email'], $collectionData['collection_manager'], $collectionData['collection_manager_image']);
    elseif ($usertype == 2){
        $email->to($collectionData['customer_email'], $collectionData['customer_name'], $collectionData['customer_image']);
		$collection->update(array("wc_is_certificate_sended"=>1));
	}
    $email->addFile(DOC::CERT($wc_id), $app->siteName . " Collection Certificate - $collectionData[wc_code].pdf")->template('send_document', array(
        "message" => "We are happy to inform you that your Collection Certificate is ready against Collection Order number : #$collectionData[wc_code]"
    ))->send();
    Activity::add("send Hazard waste Consignment Certificate to Customer|^|$collectionData[wc_code]", "W", $wc_id);
    echo json_encode(array("200",  "success|Collection Certificate send to customer Successfully",
        $collectionData
    ));

?>