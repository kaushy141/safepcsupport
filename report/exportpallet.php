<?php
$pallet_id = intval($_REQUEST['id']);
$PalletItems	= new PalletItems($pallet_id);
$PalletItems->getItemsExcelExport();
?>