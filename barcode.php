<?php include("setup.php"); ?>
<?php
header('Content-type: image/png');
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
echo $generatorSVG->getBarcode($_REQUEST['code'], $generatorSVG::TYPE_CODE_128);
?>