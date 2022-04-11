<?php

    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Supplier = new Supplier($idvalue);
        $status ? $Supplier->Activate() : $Supplier->Deactivate();
        Activity::add(status($status) . " Supplier <b>" . $Supplier->get('supplier_name') . "</b> staus");
        echo json_encode(array("200",  "success|Supplier " . status($status) . " Successfully"
        ));
    } else
        echo json_encode(array("300", "warning|Supplier not found."
        ));

?>