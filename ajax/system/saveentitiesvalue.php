<?php
	$entities_key = $entities_values = "";
	$entities_js_allowed = $entities_php_allowed = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	Entities::saveKeyValues($entities_key, $entities_values, $entities_js_allowed, $entities_php_allowed);
	unlinkCacheFile(Entities::getEntitiesConstantsFilename());
	echo json_encode(array("200", "success|Entity values saved"));

?>