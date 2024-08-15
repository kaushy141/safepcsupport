<?php include("setup.php");

if(isset($_REQUEST['data']))
{
    ob_end_clean();
	$text = $_GET['data'];
	QRcode::png($text, false, $level = QR_ECLEVEL_H, 5, $margin = 4, false);
}
?>