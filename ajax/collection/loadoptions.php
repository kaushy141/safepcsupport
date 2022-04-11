<?php
	$class_name = "";
    $value      = $id = 0;
    $data       = sanitizePostData($_POST);
    extract($data);
    if ($class_name != "" && $value != 0 && class_exists($class_name)) {
        $class       = new $class_name();
        $optionsHtml = $class->getOptions($id, $value);
        echo json_encode(array("200",  "success|Options Loaded successfully",
            $optionsHtml
        ));
    } else
        echo json_encode(array("300", "warning|Could not load Options"
        ));
?>