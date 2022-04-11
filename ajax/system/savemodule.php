<?php

	$module_name = $module_key = $module_icon = $module_color = "";
	$module_sort_order = $module_parent = $module_is_customer_access = $module_is_navbar = $module_is_topbar = $module_id = $module_status = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($module_name != "" && $module_key != "" && $module_icon != "")
	{
		$modulePostData = array(
									"module_name" => $module_name,
									"module_key" => $module_key,
									"module_icon" => $module_icon,
									"module_color" => $module_color,
									"module_sort_order" => $module_sort_order,
									"module_parent" => $module_parent,
									"module_is_customer_access" => $module_is_customer_access,
									"module_is_navbar" => $module_is_navbar,
									"module_is_topbar" => $module_is_topbar,
									"module_id" => $module_id,
									"module_status" => $module_status
								);
		$navbar = new Navbar($module_id);
		if($navbar->isExist())
		{
			$navbar->update($modulePostData);
			echo json_encode(array("200",  "success|Module '$module_name' updated"));
		}
		else
		{
			$module_id = $navbar->insert($modulePostData);
			echo json_encode(array("200",  "success|Module '$module_name' added", $navbar->load()));
		}
		
	}
	else
		echo json_encode(array("300",  "danger|All mendate field required"));


?>