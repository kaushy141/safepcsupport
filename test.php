<?php
include("setup.php");
Modal::load(array('Firebase'));
error_reporting(E_ALL);
ini_set('display_errors', 1);
$to = 'fKwOWarpMvtuSpBW1Z2oO_:APA91bE6JrVkXaauhD8jIPvyVpVAnsgVYNA_mMJCjavsd35Okfkw7OZAfi_TIrTmtCNPlWG8s4V_SKankqYgwwtkX9k9jBd6SBPyOLOPspdXOQR_qZIB2y8aeR4tbNGbN2_lSp6RlbKT';

Firebase::sendPushMessage($to, "Title", "My Message");
/*$e = "web-cct5p@mail-tester.com";
if(isset($_REQUEST['e']))
$e = trim(strtolower($_REQUEST['e']));
$email     = new Email("Email Testing for SPF ".time());
$email->to($e, "WEb CCT", $app->basePath(DEFAULT_IMAGE_PATH));
$email->template('default', array())->send();*/
?> 