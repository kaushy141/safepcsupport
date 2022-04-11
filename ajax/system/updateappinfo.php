<?php

    $info_app_bank_details = $info_app_address = $info_app_contact = $info_app_invoice_acknowledge = $info_app_invoice_happytext = $info_app_invoice_terms = $info_app_disclaimer = "";
    $data                  = sanitizePostData($_POST);
    extract($data);
    $appinfo = new AppInfo($info_app_id);
    $appinfo->update(array("info_app_bank_details" => $info_app_bank_details,
        "info_app_address" => $info_app_address,
        "info_app_contact" => $info_app_contact,
        "info_app_invoice_acknowledge" => $info_app_invoice_acknowledge,
        "info_app_invoice_happytext" => $info_app_invoice_happytext,
        "info_app_invoice_terms" => $info_app_invoice_terms,
        "info_app_disclaimer" => $info_app_disclaimer,
        "wc_carrier_certificate_text" => $wc_carrier_certificate_text,
        "wc_consigner_certificate_text" => $wc_consigner_certificate_text,
        "wc_carrier_licence_number" => $wc_carrier_licence_number,
        "wc_hazardous_waste_licence_number" => $wc_hazardous_waste_licence_number,
        "wc_environment_permit" => $wc_environment_permit,
        "wc_vehicle_registration" => $wc_vehicle_registration,
        "wc_declaration_text" => $wc_declaration_text,
        "wc_collection_declaration_text" => $wc_collection_declaration_text,
        "wc_authority_name" => $wc_authority_name
    ));
    Activity::add("updated Application Information");
    echo json_encode(array("200",  "success|Application Information Updated Successfully",
        $info_app_id
    ));
    $email = new Email($app->siteName . " : " . "Application Information Updated");
    $email->send("You have successfully updated application Detail information on " . $app->siteName);

?>