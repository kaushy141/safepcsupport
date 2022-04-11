<?php


    $info_app_bank_details = $info_app_address = $info_app_contact = $info_app_invoice_acknowledge = $info_app_invoice_happytext = $info_app_invoice_terms = $info_app_disclaimer = $wc_carrier_certificate_text = $wc_consigner_certificate_text = $wc_carrier_licence_number = $wc_hazardous_waste_licence_number = $wc_environment_permit = $wc_vehicle_registration = $wc_declaration_text = $wc_collection_declaration_text = $wc_authority_name = "";
    $data                  = sanitizePostData($_POST);
    extract($data);
    $appinfo     = new AppInfo();
    $info_app_id = $appinfo->add($info_app_bank_details, $info_app_address, $info_app_contact, $info_app_invoice_acknowledge, $info_app_invoice_happytext, $info_app_invoice_terms, $info_app_disclaimer, $wc_carrier_certificate_text, $wc_consigner_certificate_text, $wc_carrier_licence_number, $wc_hazardous_waste_licence_number, $wc_environment_permit, $wc_vehicle_registration, $wc_authority_name, $wc_declaration_text, $wc_collection_declaration_text);
    Activity::add("added Application Information");
    echo json_encode(array("200",  "success|Application Information added Successfully",
        $info_app_id
    ));
    $email = new Email($app->siteName . " : " . "Application Information Added");
    $email->send("You have successfully added application Detail information on " . $app->siteName);

?>