<?php
	$attribute_id	= 0;
	$attribute_name	= "";
	$attribute_element_type = 'atext';
	$attribute_is_filtrer = 0;
	$attribute_status = 0;	
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($attribute_name != "" && $attribute_element_type != ""){
		$attribute = new Attribute($attribute_id);
		if(!$attribute->isAvailable($attribute_name) && $attribute_id == 0){
			$attribute_id = $attribute->insert(compact('attribute_name', 'attribute_element_type', 'attribute_is_filtrer', 'attribute_status'));
			echo json_encode(array("200",  "success|Atribute Saved", $attribute_id));
		}
		elseif(!$attribute->isAvailableExceptThis($attribute_name, $attribute_id)){
			$attribute->update(compact('attribute_name', 'attribute_is_filtrer', 'attribute_status'));
			echo json_encode(array("200",  "success|Atribute Updated", $attribute_id));
		}
		else
			echo json_encode(array("300",  "warning|Attribute name already exist."));	
	}
	else
		echo json_encode(array("300",  "warning|Attribute name and type not found."));	

?>