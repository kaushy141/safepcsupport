<?php

    $complaint_vat_tax = $complaint_e_tax = $complaint_z_tax = $complaint_n_tax = $complaint_r_tax = $complaint_s_tax = "";
    $data              = sanitizePostData($_POST);
    extract($data);
    $ComplaintTax = new ComplaintTax();
    $ComplaintTax->deactivateAll();
    $complaint_tax_id = $ComplaintTax->add(number_format($complaint_vat_tax, 2), number_format($complaint_e_tax, 2), number_format($complaint_z_tax, 2), number_format($complaint_n_tax, 2), number_format($complaint_r_tax, 2), number_format($complaint_s_tax, 2));
    Activity::add("added new Tax Information");
    echo json_encode(array("200",  "success|Complaint Tax Information added Successfully",
        $complaint_tax_id
    ));
    $email = new Email($app->siteName . " : " . "Application Tax Information Added");
    $email->send("You have successfully added application Tax Information Detail on " . $app->siteName . " w.e.f. " . date('d M Y h:i:s A'));

?>