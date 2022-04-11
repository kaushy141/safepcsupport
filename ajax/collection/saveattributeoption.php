<?php
	$attribute_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($attribute_id){
			$attribute = new Attribute($attribute_id);
			if($attribute->isExist())
			{
				$wcitem = new WcItem();
				$savedOptions = $wcitem->getAttributesOptions($attribute_id);
				$savedOptions = array_values($savedOptions);
				$newOptions = count($savedOptions) ? array_diff($options, $savedOptions) : $options;
				$delOptions = count($savedOptions) ? array_diff($savedOptions, $options) : $savedOptions;
				if(count($newOptions)){
					foreach($newOptions as $values_data)
					$wcitem->addAttributeOption($attribute_id, $values_data);
				}
				if(count($delOptions)){
					foreach($delOptions as $values_data)
					$wcitem->removeAttributeOption($attribute_id, $values_data);
				}
				echo json_encode(array("200",  "success|Attribute Option Saved", $record));			
			}
			else
				echo json_encode(array("300",  "warning|No Attribute found."));
		}
		else
			echo json_encode(array("300",  "warning|Invalid Attribute."));	

?>