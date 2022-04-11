<?php

    $crm_group_id      = $check_all_customer = $uncheck_all_customer = 0;
    $crm_group_status  = 1;
    $crm_group_name    = "";
    $customerArray     = $customerCheckArray = $customerUnCheckArray = array();
    $crm_group_creator = $_SESSION['user_id'];
    $data              = sanitizePostData($_POST);
    extract($data);
    $crm_group_customers_all      = 0;
    $crm_group_customers_except   = "";
    $crm_group_customers_not_all  = 0;
    $crm_group_customers_included = "";
    if ($check_all_customer == 1) {
        $crm_group_customers_all    = 1;
        $crm_group_customers_except = trim(implode(",", array_filter($customerUnCheckArray)), ",");
    } elseif ($uncheck_all_customer == 1) {
        $crm_group_customers_not_all  = 1;
        $crm_group_customers_included = trim(implode(",", array_filter($customerCheckArray)), ",");
    } else {
        $crm_group_customers_included = trim(implode(",", array_filter($customerArray)), ",");
    }
    $CustomerGroup = new CustomerGroup($crm_group_id);
    $CustomerGroup->update(array("crm_group_name" => $crm_group_name,
        "crm_group_customers_all" => $crm_group_customers_all,
        "crm_group_customers_except" => $crm_group_customers_except,
        "crm_group_customers_not_all" => $crm_group_customers_not_all,
        "crm_group_customers_included" => $crm_group_customers_included,
        "crm_group_creator" => $crm_group_creator,
        "crm_group_status" => $crm_group_status
    ));
    Activity::add("updated Customer Group \"$crm_group_name\"");
    echo json_encode(array("200",  "success|Customer Group updated Successfully",
        $crm_email_template_id
    ));
    $email = new Email($app->siteName . " : " . "New Customer Group updated");
    $email->send("You have successfully updated Customer Group \"$crm_group_name\" on " . $app->siteName);

?>