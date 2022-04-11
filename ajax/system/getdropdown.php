<?php
	$condition = "";
    $data      = sanitizePostData($_POST);
    if (isset($data['condition']))
        $condition = $data['condition'];
    if (isset($data['dropdown'])) {
        $val = explode("<=>", $data['dropdown']);
        if (count($val) == 2) {
            $className = $val[0];
            $fieldName = $val[1];
            $keyword   = $data['keyword'];
            $extra     = isset($data['extra']) ? $data['extra'] : 0;
			if(!class_exists($className)){
				Modal::load(array($className));
			}
            if (class_exists($className) && trim($keyword) != "") {
                $extraData = array();				
                $d         = new $className(0);
                $options   = $d->getDropdownList($fieldName, trim($keyword), $extra, $condition);
                echo json_encode(array("200",
                    $options
                ));
            } else
                echo json_encode(array("300", "warning|No Option Available"
                ));
        } else
            echo json_encode(array("300", "warning|Invalid Request Data"
            ));
    } else
        echo json_encode(array("300", "warning|Invalid Method Calling"
        ));

?>