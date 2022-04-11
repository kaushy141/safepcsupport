<?php

    $wci_name    = $wci_ewc_code = $wci_chemical_component = $wci_concentration = $wci_physical_form = $wci_hazard_codes = $wci_container_type = $wci_description = $wci_serialize_type = "";
    $wci_status  = 1;
    $wci_type_id = $wci_id = 0;
    $data        = sanitizePostData($_POST);
    extract($data);
    $WcItem = new WcItem($wci_id);
    $WcItem->update(array("wci_type_id" => $wci_type_id,
        "wci_name" => $wci_name,
        "wci_ewc_code" => $wci_ewc_code,
        "wci_chemical_component" => $wci_chemical_component,
        "wci_concentration" => $wci_concentration,
        "wci_physical_form" => $wci_physical_form,
        "wci_hazard_codes" => $wci_hazard_codes,
        "wci_container_type" => $wci_container_type,
        "wci_description" => $wci_description,
		"wci_serialize_type" => $wci_serialize_type,
        "wci_status" => $wci_status
    ));
    Activity::add("updated Collection Item <b>$wci_name</b>");
    echo json_encode(array("200",  "success|Collection Item Updated Successfully",
        $wci_id
    ));
    $email = new Email($app->siteName . " : " . "Collection Item Updated");
    $email->send("You have successfully Updated Collection Item \"$wci_name\" on " . $app->siteName);

?>