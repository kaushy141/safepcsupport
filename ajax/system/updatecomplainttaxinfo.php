<?php

    $complaint_vat_tax = $complaint_e_tax = $complaint_z_tax = $complaint_n_tax = $complaint_r_tax = $complaint_s_tax = "";
    $data              = sanitizePostData($_POST);
    extract($data);
    $ComplaintTax = new ComplaintTax($complaint_tax_id);
    $ComplaintTax->update(array("complaint_vat_tax" => number_format($complaint_vat_tax, 2),
        "complaint_e_tax" => number_format($complaint_e_tax, 2),
        "complaint_z_tax" => number_format($complaint_z_tax, 2),
        "complaint_n_tax" => number_format($complaint_n_tax, 2),
        "complaint_r_tax" => number_format($complaint_r_tax, 2),
        "complaint_s_tax" => number_format($complaint_s_tax, 2)
    ));
    Activity::add("updated Tax Information");
    echo json_encode(array("200",  "success|Application Complaint Tax Information Updated Successfully",
        $complaint_tax_id
    ));
    $email = new Email($app->siteName . " : " . "Application Tax Information Updated");
    $email->send("You have successfully updaetd application Tax Information Detail on " . $app->siteName);

?>