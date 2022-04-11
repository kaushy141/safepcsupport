<?php 
$action	=	"importbatchrecords";
$formHeading	=	"Import Batch Products";
if(isset($_SESSION['BATCH-PROD']))
	unset($_SESSION['BATCH-PROD']);
?>
<?php include("engine/inc/batchimportform.php"); ?>