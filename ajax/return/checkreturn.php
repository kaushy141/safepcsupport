<?php
	Modal::load(array('Returns', 'Section'));    
	$module_code = null;
	$module_id = 0;
    $data = sanitizePostData($_POST);    
    extract($data);
	$return = new Returns(0);
	$wcStatus = new WcStatus();
	$moduleDetails = Section::getSectionDetails($module_code, $module_id);
	if($return->isReturnExist($module_code, $module_id))
	{
		$returnData = $return->getReturnByModule($module_code, $module_id);
		$return_details_after = "<div class=\"col-sm-12 px-0 py-1\"><div class=\"pull-right pl-1\"><img class=\"img img-circle\" style=\"margin-top:0px; margin-bottom:0px; height:40px;\" src=\"".getResizeImage($returnData["user_image"],50)."\"/></div><div class=\"pull-right\">Originally created by ".$returnData['user_fname']." <i class=\"fa fa-check-circle text-success\"></i><br/><span class=\"text-muted\" style=\"font-size: 0.9em;\">".dateView($returnData['return_created_date'], 'NOW')." ". dateView($returnData['return_created_date'], 'FULL')."</span></div></div>";
		echo json_encode(array("200",  "success|Return form loaded", $wcStatus->getOptions($returnData['return_status']), $returnData['return_id'], 'viewreturn/'.md5($returnData['return_id']), $returnData, "return_details_after"=>$return_details_after));					
	}
	else
		echo json_encode(array("200",  "success|Return is not added yet.", $wcStatus->getOptions(0), 0));

?>