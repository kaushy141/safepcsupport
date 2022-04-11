<?php
include("setup.php");
if (isset($_SESSION['app_log_type'])) {
    include($_SESSION['app_log_type'] == "E" ? "config/session.php" : "config/customer-session.php");
}
else{
	echo json_encode(array( 404, "danger|Session timeout. Relogin required."));
    die;
}
$app = new App();

if (isset($_POST['action'])) {
    if (isset($_POST['formcode']) && trim($_POST['formcode']) != "" && strlen($_POST['formcode']) == FORMCODE_STRING_LENGTH) {
        if (!App::checkFormcode($_POST['formcode'])) {
            echo json_encode(array( 404, "danger|Loaded form expired please reload this form."
            ));
            die;
        }
    }
	$action = !is_array($_POST['action'])? $_POST['action'] : null; 
	if($action != null)
	{
		$actionFile = "ajax/$action.php";
		$actionController = explode("/", trim($action));
		if(file_exists($actionFile)){
			require_once($actionFile);
		}
		else{
			echo json_encode(array( 404, "danger|Invalid Action found. Please Refresh and try again.", $action));
		}
	}
	else
		echo json_encode(array( 404, "danger|Action controller not found. Please Refresh and try again."));
} 
else
    echo json_encode(array( 404, "danger|No Action found. Please Refresh and try again."
    ));


?> 