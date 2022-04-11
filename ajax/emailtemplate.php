<?php

function addemailtemplate()
{
    global $app;
    $crm_email_template_id     = 0;
    $crm_email_template_name   = $crm_email_template_html = "";
    $crm_email_template_status = 1;
    $data                      = sanitizePostData($_POST);
    extract($data);
    $CrmEmailTemplate      = new CrmEmailTemplate();
    $crm_email_template_id = $CrmEmailTemplate->add($crm_email_template_name, $crm_email_template_html, $crm_email_template_status);
    Activity::add("added New Email Template \"$crm_email_template_name\"");
    echo json_encode(array("200",  "success|Email Template added Successfully",
        $crm_email_template_id
    ));
    $email = new Email($app->siteName . " : " . "New Template added");
    $email->send("You have successfully added Email Template \"$crm_email_template_name\" on " . $app->siteName);
}
function updateemailtemplate()
{
    global $app;
    $crm_email_template_id     = 0;
    $crm_email_template_name   = $crm_email_template_html = "";
    $crm_email_template_status = 1;
    $data                      = sanitizePostData($_POST);
    extract($data);
    $CrmEmailTemplate = new CrmEmailTemplate($crm_email_template_id);
    if ($record = $CrmEmailTemplate->getDetails()) {
        $CrmEmailTemplate->update(array("crm_email_template_name" => $crm_email_template_name,
            "crm_email_template_html" => $crm_email_template_html,
            "crm_email_template_status" => $crm_email_template_status
        ));
        Activity::add("Updated New Email Template \"$record[crm_email_template_name]\"");
        echo json_encode(array("200",  "success|Email Template updated Successfully",
            $crm_email_template_id
        ));
        $email = new Email($app->siteName . " : " . " Email Template Updated");
        $email->send("You have successfully updated Email Template \"$record[crm_email_template_name]\" to \"$crm_email_template_name\" on " . $app->siteName);
    } else
        echo json_encode(array("400",
            "Warning|No Email Template found"
        ));
}
function updateemailtemplatestatus()
{
    global $app;
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $CrmEmailTemplate = new CrmEmailTemplate($idvalue);
        $status ? $CrmEmailTemplate->Activate() : $CrmEmailTemplate->Deactivate();
        Activity::add(status($status) . " Email Template <b>" . $CrmEmailTemplate->get('crm_email_template_name') . "</b> staus");
        echo json_encode(array("200",  "success|Email Template " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Email Template " . status($status));
        $email->send("Email Template <b>\"" . $CrmEmailTemplate->get('crm_email_template_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Email Template Status not found."
        ));
}
function addcustomergroup()
{
    global $app;
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
    $CustomerGroup = new CustomerGroup();
    $crm_group_id  = $CustomerGroup->add($crm_group_name, $crm_group_customers_all, $crm_group_customers_except, $crm_group_customers_not_all, $crm_group_customers_included, $crm_group_creator, $crm_group_status);
    Activity::add("added New Customer Group \"$crm_group_name\"");
    echo json_encode(array("200",  "success|Customer Group added Successfully",
        $crm_group_id
    ));
    $email = new Email($app->siteName . " : " . "New Customer Group added");
    $email->send("You have successfully added Customer Group \"$crm_group_name\" on " . $app->siteName);
}
function updatecustomergroup()
{
    global $app;
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
}
function updatecustomergroupstatus()
{
    global $app;
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $CustomerGroup = new CustomerGroup($idvalue);
        $status ? $CustomerGroup->Activate() : $CustomerGroup->Deactivate();
        Activity::add(status($status) . " Crm Group <b>" . $CustomerGroup->get('crm_group_name') . "</b> staus");
        echo json_encode(array("200",  "success|Crm Group " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Crm Group " . status($status));
        $email->send("Crm Group <b>\"" . $CustomerGroup->get('crm_group_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Crm Group Status not found."
        ));
}
function addcrmtask()
{
    global $app;
    $crm_task_id     = $crm_task_template_id = $crm_task_customer_group_id = 0;
    $crm_task_name   = $crm_task_subject = $crm_task_execution_time = "";
    $crm_task_status = 1;
    $data            = sanitizePostData($_POST);
    extract($data);
    $CrmTask     = new CrmTask();
    $crm_task_id = $CrmTask->add($crm_task_name, $crm_task_subject, $crm_task_template_id, $crm_task_customer_group_id, $crm_task_execution_time, $crm_task_status);
    Activity::add("added New Crm Task \"$crm_task_name\"");
    echo json_encode(array("200",  "success|Crm Task added Successfully",
        $crm_task_id
    ));
    $email = new Email($app->siteName . " : " . "New Crm Task added");
    $email->send("You have successfully added Crm Task \"$crm_task_name\" on " . $app->siteName);
}
function updatecrmtask()
{
    global $app;
    $crm_task_id     = $crm_task_template_id = $crm_task_customer_group_id = 0;
    $crm_task_name   = $crm_task_subject = $crm_task_execution_time = "";
    $crm_task_status = 1;
    $data            = sanitizePostData($_POST);
    extract($data);
    $CrmTask = new CrmTask($crm_task_id);
    $CrmTask->update(array("crm_task_name" => $crm_task_name,
        "crm_task_subject" => $crm_task_subject,
        "crm_task_template_id" => $crm_task_template_id,
        "crm_task_customer_group_id" => $crm_task_customer_group_id,
        "crm_task_execution_time" => $crm_task_execution_time,
        "crm_task_status" => $crm_task_status
    ));
    Activity::add("updated Crm Task \"$crm_task_name\"");
    echo json_encode(array("200",  "success|Crm Task updated Successfully",
        $crm_task_id
    ));
    $email = new Email($app->siteName . " : " . "Crm Task Updated");
    $email->send("You have successfully updated Crm Task \"$crm_task_name\" on " . $app->siteName);
}
function updatecrmtaskstatus()
{
    global $app;
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $CrmTask = new CrmTask($idvalue);
        $status ? $CrmTask->Activate() : $CrmTask->Deactivate();
        Activity::add(status($status) . " Crm Task <b>" . $CrmTask->get('crm_task_name') . "</b> staus");
        echo json_encode(array("200",  "success|Crm Task " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Crm Task " . status($status));
        $email->send("Crm Task <b>\"" . $CrmTask->get('crm_task_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Crm Task Status not found."
        ));
}
function executetask()
{
    global $app;
    $crm_task_id = 0;
    $data        = sanitizePostData($_POST);
    extract($data);
    if ($crm_task_id) {
        $CrmTask = new CrmTask($crm_task_id);
        $CrmTask->execute();
        echo json_encode(array("200",  "success|Crm Task executed Successfully"
        ));
    } else
        echo json_encode(array("300", "warning|Crm Task not found."
        ));
}
?>