<?php

    $attribute_relation_item_id  = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($attribute_relation_item_id != 0) {
		$attribute = new Attribute(0);     
		$record = $attribute->getItemAttributesList($attribute_relation_item_id);		
		echo json_encode(array("200",  "success|Item Attribute Loaded Successfully", $record['attribute'], array_combine($record['attribute'],$record['exportable'])));
    } 
	else
        echo json_encode(array("300", "warning|Attribute Status not found."));

?>