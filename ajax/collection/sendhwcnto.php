<?php

    $usertype = $wc_id = 0;
    $data     = sanitizePostData($_POST);
    extract($data);
    $collection     = new Collection($wc_id);
    $collectionData = $collection->load();
    $email          = new Email("New Hazard waste Collection Report #$collectionData[wc_code]");
    if ($usertype = 1)
        $email->to($collectionData['collection_manager_email'], $collectionData['collection_manager'], $collectionData['collection_manager_image']);
    elseif ($usertype = 2)
        $email->to($collectionData['carrier_email'], $collectionData['carrier_name'], $collectionData['carrier_logo_image']);
    $email->addFile(DOC::HWCN($wc_id), $app->siteName . " HWC Collection - $collectionData[wc_code].pdf")->template('send_document')->send();
    Activity::add("send Hazard waste Consignment note to Collection Manager|^|$collectionData[wc_code]", "W", $wc_id);
    echo json_encode(array("200",  "success|Hazard waste Collection Report send Successfully"
    ));
?>