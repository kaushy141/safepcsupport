<?php include("setup.php"); ?>
<?php $app = new App();?>
<?php
if(isset($_REQUEST['data']))
{
	$text = $_REQUEST['data'];
	QRcode::png($text, false, $level = QR_ECLEVEL_H, 5, $margin = 4, false);
	
}
?>