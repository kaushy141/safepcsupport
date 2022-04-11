<?php
class PalletItems extends DB{
	public $id;
	public $key_id	=	"wpi_pallet_id";
	public $table_name	=	"app_wc_pallet_items";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function add($wpi_process_asset_code, $wpi_pallet_id, $wpi_code_number, $wpi_label_number, $wpi_item_order)
	{
		$sql="INSERT INTO `".$this->table_name."` (`wpi_process_asset_code`, `wpi_pallet_id`, `wpi_code_number`, `wpi_label_number`, `wpi_created_date`) VALUES ('$wpi_process_asset_code', '$wpi_pallet_id', '$wpi_code_number', '$wpi_label_number', NOW())";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function updateSerial($wpi_id, $number){
		$sql = "UPDATE `".$this->table_name."` SET `wpi_code_number` = '$wpi_code_number', `wpi_label_number` = '$wpi_label_number' WHERE `wpi_id` = '$wpi_id'"	;
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function resetSerial(){
		$records = $this->getItemsRecords();
		if(count($records) > 0){
			$counter = 0;
			foreach($records as $item){
				updateSerial($item['wpi_id'], $item[++$counter]);
			}	
		}
	}
	
	function updateAssetCode($wpi_process_asset_code, $wpi_pallet_id, $wpi_code_number, $wpi_label_number, $wpi_item_order){
		$sql = "UPDATE `".$this->table_name."` SET `wpi_code_number` = '$wpi_code_number', `wpi_label_number` = '$wpi_label_number', `wpi_pallet_id` = '$wpi_pallet_id', `wpi_item_order` = '$wpi_item_order' WHERE LOWER(`wpi_process_asset_code`) = LOWER('".$wpi_process_asset_code."')"	;
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}

	function isItemExist($wpi_process_asset_code){
		$sql="SELECT `wpi_id` FROM `".$this->table_name."` WHERE LOWER(`wpi_process_asset_code`) = LOWER('".$wpi_process_asset_code."')";	
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			return $dbc->db_num_rows() > 0 ? true : false;
	}
	
	static function getPalletIdByCode($wpi_process_asset_code){
		$sql="SELECT `wpi_pallet_id` FROM `app_wc_pallet_items` WHERE LOWER(`wpi_process_asset_code`) = LOWER('".$wpi_process_asset_code."')";	
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			if($dbc->db_num_rows() > 0){
				$data = $dbc->db_fetch_assoc(true);
				return $data['wpi_pallet_id'];
			}
			else
				return 0;
	}

	
	
	function saveItem($wpi_process_asset_code, $wpi_pallet_id, $wpi_code_number, $wpi_label_number, $wpi_item_order = NULL){
		if($wpi_item_order == NULL){
			$wpi_item_order = $this->getPalletItemLabel();
		}
		if(!$this->isItemExist($wpi_process_asset_code)){
			 $this->add($wpi_process_asset_code, $wpi_pallet_id, $wpi_code_number, $wpi_label_number, $wpi_item_order);
		}
		else{
			$this->updateAssetCode($wpi_process_asset_code, $wpi_pallet_id, $wpi_code_number, $wpi_label_number, $wpi_item_order);
		}
	}
	
	function getStoredItems(){
		$sql = "SELECT GROUP_CONCAT(`wpi_process_asset_code`) as list FROM `".$this->table_name."` WHERE `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows())
		{
			$data = $dbc->db_fetch_assoc(true);
			return explode(",", $data['list']);
		}
		else
			return NULL;	
	}
		
	function getPalletItemNumber()
	{
		return "SPD-PDR".sprintf("%04d",$this->getPalletItemLabel());
	}
	
	function removeByCodeItemCode($wpi_process_asset_code){
		$sql = "DELETE FROM `".$this->table_name."` WHERE `wpi_process_asset_code` = '$wpi_process_asset_code' AND `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function removePalletItemByCode($wpi_pallet_id, $wpi_process_asset_code){
		$sql = "DELETE FROM `".$this->table_name."` WHERE `wpi_pallet_id` = '$wpi_pallet_id' AND `wpi_process_asset_code` = '$wpi_process_asset_code'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function updateItemOrderByCode($wpi_process_asset_code, $wpi_item_order){
		$sql = "UPDATE `".$this->table_name."` SET `wpi_item_order` = '$wpi_item_order' WHERE `wpi_process_asset_code` = '$wpi_process_asset_code' AND `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	
	
	function getPalletItemLabel()
	{
		$sql="SELECT MAX(`wpi_label_number`) as num FROM `".$this->table_name."` WHERE `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows())
		{
			$data = $dbc->db_fetch_assoc();
			return $data['num']+1;
		}
		else
			return 1;			
	}
	
	function getItemsRecords()
	{		
		global $app;		
		$sql="SELECT a.`wpi_id`, a.`wpi_process_asset_code`, a.`wpi_pallet_id`, a.`wpi_code_number`, a.`wpi_label_number`, b.* FROM `".$this->table_name."` as a INNER JOIN `app_wc_process` as b ON a.`wpi_process_asset_code` = b.`wc_process_asset_code` WHERE a.`".$this->key_id."` = '".$this->id."' ORDER BY a.`wpi_item_order`";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$resultArray = array();
		while($row = $dbc->db_fetch_assoc(true)){								
				$resultArray[] = $row;
		}
		return $resultArray;	
	}
	
	function getItemsExcelExport()
	{
		global $app;	
		
		$pallet = new Pallet($this->id);
		$palletData = $pallet->getDetails();					
		$columNameArray = array(
							"NUMBER"		=>"wpi_code_number",
							"LABEL NUMBER"	=>"wpi_label_number",
							"MAKE"		=>"wc_process_item_make",
							"MODEL"		=>"wc_process_item_model",
							"NAME"		=>"wc_process_item_name",
							"SKU"		=>"wc_process_item_sku",
							"STOCK"		=>"wc_process_item_stock",
							"LOCATION"		=>"wc_process_item_location",
							"SR. NUMBER"		=>"wc_process_item_sr_no",
							"DAMAGE Y/N ITEMS"		=>"wc_process_item_damage_status",
							"WEIGHT"		=>"wc_process_item_weight",
							"QUANTITY"		=>"wc_process_item_qty"
								);
		$recordItems = $this->getItemsRecords();						
		$objPHPExcel = new PHPExcel();


		$objPHPExcel->getProperties()->setCreator($app->siteName)
					 ->setLastModifiedBy($app->siteName)
					 ->setTitle("Pallet ".$palletData['pallet_code'])
					 ->setSubject("Pallet ".$palletData['pallet_code']." product items xslx list")
					 ->setDescription("Pallet : ".$palletData['pallet_code']." and Serial number : ".$palletData['pallet_serial_number']." with ".count($recordItems)." items")
					 ->setKeywords("office 2007 openxml php")
					 ->setCategory("Pallet ".$palletData['pallet_code']." file");
		$objPHPExcel->setActiveSheetIndex(0);
		
		
		
		$styleArray = array(
							'font'  => array(
								'bold'  => false,
								'color' => array('rgb' => 'FFFFFF')
							));
		$styleAllignment = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
			)
		);
		$objPHPExcel->getActiveSheet()->mergeCells("A1:B1");    
		$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('21618C');
		$objPHPExcel->getActiveSheet()->setCellValue("A1", "PAT CERTIFICATE");
		$objPHPExcel->getActiveSheet()->setCellValue("C1", "SERIAL NUMBER");
		$objPHPExcel->getActiveSheet()->getStyle("C1")->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle("C1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('21618C');
		
		$objPHPExcel->getActiveSheet()->mergeCells("D1:G1");		
		$objPHPExcel->getActiveSheet()->getStyle("D1")->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle("D1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('21618C');
		$objPHPExcel->getActiveSheet()->setCellValue("D1", "TESTER");
		
		$objPHPExcel->getActiveSheet()->mergeCells("A2:B2");
		$objPHPExcel->getActiveSheet()->setCellValue("A2", "Customer: $palletData[pallet_cert_customer]");
		$objPHPExcel->getActiveSheet()->mergeCells("A3:B6");
		$objPHPExcel->getActiveSheet()->setCellValue("A3", "Address: $palletData[pallet_cert_address]");
		$objPHPExcel->getActiveSheet()->mergeCells("A3:B3");
		$objPHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleAllignment);
		
		$objPHPExcel->getActiveSheet()->mergeCells("A7:B7");
		$objPHPExcel->getActiveSheet()->setCellValue("A7", "Tel: $palletData[pallet_cert_telephone]");
		
		$objPHPExcel->getActiveSheet()->mergeCells("A8:B8");
		$objPHPExcel->getActiveSheet()->setCellValue("A8", "Date: ".date("d/m/Y", strtotime($palletData['pallet_cert_date'])));
		
		$objPHPExcel->getActiveSheet()->mergeCells("C2:C10");
		$objPHPExcel->getActiveSheet()->setCellValue("C2", $palletData['pallet_serial_number']);
		$objPHPExcel->getActiveSheet()->getStyle("C2")->applyFromArray($styleAllignment);
		
		$objPHPExcel->getActiveSheet()->mergeCells("D2:G10");
		$objPHPExcel->getActiveSheet()->setCellValue("D2", "Tester: ".$palletData['pallet_tester']);
		$objPHPExcel->getActiveSheet()->getStyle("D2")->applyFromArray($styleAllignment);
		
		$idexCounter = 11;
		$cellCount = 'A';
		

		foreach($columNameArray as $columnHeading=>$columnName)
		{				
			$objPHPExcel->getActiveSheet()->setCellValue("$cellCount"."$idexCounter", $columnHeading);
			//cellColor("$cellCount"."$idexCounter", "21618C"); 
			$objPHPExcel->getActiveSheet()->getStyle("$cellCount"."$idexCounter")->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle("$cellCount"."$idexCounter")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('21618C');
			$cellCount++;
		}
		
		
		$idexCounter = 12;
		foreach($recordItems as $itemData)
		{
			$itemData = array_intersect_key( $itemData, array_flip( array_values($columNameArray)));
			$itemData['wpi_label_number'] = sprintf("%04d", $itemData['wpi_label_number']);
			$itemData['wc_process_item_stock'] = $itemData['wc_process_item_stock'] == 1 ? "In Stock" : "Out of stock";
			$itemData['wc_process_item_damage_status'] = $itemData['wc_process_item_damage_status'] == 1 ? "PASS" : "FAIL";			 
			$cellCount = 'A';
			foreach(array_values($columNameArray) as $columnName)
			{				
            	$objPHPExcel->getActiveSheet()->setCellValue("$cellCount"."$idexCounter", $itemData[$columnName]);				
				$cellCount++;
			}
			$idexCounter++;
		}

		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle("Pallet ".$palletData['pallet_code']);
		
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;filename=Pallet $palletData[pallet_code].xls");
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
	function cellColor($cells,$color){
		global $objPHPExcel;
	
		$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'startcolor' => array(
				 'rgb' => $color
			)
		));
	}
	function getPalletItemExport($pallet_id){
		$sql = "SELECT a.`wc_process_id`, a.`wc_process_item_id`, a.`wc_process_asset_code`, a.`wc_process_item_make`, a.`wc_process_item_model`, a.`wc_process_item_name`, a.`wc_process_item_sku`, a.`wc_process_item_stock`, a.`wc_process_item_sr_no`, b.`wci_name`, d.`values_wc_process_id` FROM `app_wc_process` AS a 
		INNER JOIN `app_wc_pallet_items` as p ON a.`wc_process_asset_code` = p.`wpi_process_asset_code`
		LEFT JOIN `app_wc_item_master` AS b ON ( b.`wci_id` = a.`wc_process_item_id` ) 
		LEFT JOIN `app_wc_item_attributes_values` AS d ON ( d.`values_wc_process_id` = a.`wc_process_id` ) 
		WHERE p.`wpi_pallet_id` = '$pallet_id' 
		GROUP BY a.`wc_process_asset_code` ORDER BY b.`wci_name`, p.`wpi_item_order`, `wc_process_asset_code` ASC" ;
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows()>0)
		{
			
			while($row = $dbc->db_fetch_assoc(true))	
			{
				$data[] = $row;
			}
		}
		$outputArray = array();
		if(count($data))
		{
			foreach($data as $row)
			{
				$rowData = array();
				$rowData['Item'] = $row['wci_name'];
				$rowData['Make'] = $row['wc_process_item_make'];
				$rowData['Model'] = $row['wc_process_item_model'];
				$rowData['Code'] = $row['wc_process_asset_code'];
				$rowData['Serial'] = $row['wc_process_item_sr_no'];
				$rowData['Sku'] = $row['wc_process_item_sku'];
				$rowData['Stock'] = $row['wc_process_item_stock'] ? "In Stock" : "Out of Stock";
				$sql1="SELECT b.`attribute_name`, c.`values_data` FROM `app_wc_item_attributes_relation` AS a 
				INNER JOIN `app_wc_item_attributes` AS b ON a.`attribute_relation_attribute_id` = b.`attribute_id` 
				LEFT JOIN `app_wc_item_attributes_values` as c ON a.`attribute_relation_id` = c.`values_realtion_id` AND a.`attribute_relation_item_id` = c.`values_item_id` AND a.`attribute_relation_attribute_id` = c.`values_attribute_id` 
				WHERE a.`attribute_relation_exportable` = '1' AND a.`attribute_relation_item_id` = '".$row['wc_process_item_id']."' AND c.`values_wc_process_id` = '".$row['wc_process_id']."' AND a.`attribute_relation_status` = '1' ORDER BY b.`attribute_element_type`, b.`attribute_name`";
//echo $sql1;				
				$dbc1 	= 	new DB();
				$result1	=	$dbc1->db_query($sql1);
				if($dbc1->db_num_rows()>0)	
				{	
					while($row1 = $dbc1->db_fetch_assoc(true))
					{
						$rowData[$row1['attribute_name']] = $row1['values_data'];
					}			
				}
				$outputArray[] = $rowData;
			}
		}
		return $outputArray;
	}
	
}
?>