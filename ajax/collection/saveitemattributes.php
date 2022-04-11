<?php

    $attribute_relation_item_id  = 0;
	$attributes = array();
	$attributesexp = array();
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($attribute_relation_item_id != 0) 
	{
		$wcItem = new WcItem($attribute_relation_item_id); 
		if($wcItem->isExist())
		{
			$attribute = new Attribute();
			$prevAttribiutes = $attribute->getItemAttributesArray($attribute_relation_item_id);
			/*
			$newAttributes = count($prevAttribiutes) ? array_diff($attributes, $prevAttribiutes) : $attributes;
			$removeAttributes = count($prevAttribiutes) ? array_diff($prevAttribiutes, $attributes) : $prevAttribiutes;		
			*/	
			$newAttributes = array_diff($attributes, $prevAttribiutes);
			$removeAttributes = array_diff($prevAttribiutes, $attributes);		
			
			if(count($attributes)){
				foreach($attributes as $attribute_relation_attribute_id)
				{
					$attribute_relation_exportable = in_array($attribute_relation_attribute_id, $attributesexp) ? 1 : 0;
					if($attribute->isItemAttributeRelationExist($attribute_relation_item_id, $attribute_relation_attribute_id))
						$attribute->updateItemAttributeRelation($attribute_relation_item_id, $attribute_relation_attribute_id, $attribute_relation_exportable, 1);
					else
						$attribute->addItemAttribute($attribute_relation_item_id, $attribute_relation_attribute_id, $attribute_relation_exportable);
				}
			}
			
			if(count($removeAttributes)){
				foreach($removeAttributes as $attribute_relation_attribute_id){
					$attribute_relation_exportable = 0;
					$attribute->updateItemAttributeRelation($attribute_relation_item_id, $attribute_relation_attribute_id, $attribute_relation_exportable, 0);
				}
			}
			
			Activity::add("Updated Items attributes");
			echo json_encode(array("200",  "success|Item Attribute saved Successfully", $attributes, $prevAttribiutes, $removeAttributes));
		} 
		else
			echo json_encode(array("300", "warning|No Attribute found."));
	}
	else
        echo json_encode(array("300", "warning|Invalid Attribute found."));

?>