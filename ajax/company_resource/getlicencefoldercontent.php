<?php

	$licence_folder = '';
	$data           = sanitizePostData($_POST);
    extract($data);
	$licences = new Licences();
	echo json_encode(array("200",  "success|Licence folder conent loaded", $licences->getItemsByFolder($licence_folder)));

?>