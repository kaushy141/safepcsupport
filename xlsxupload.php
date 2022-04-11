<?php include("setup.php"); ?>
<?php include("config/session.php");?>
<?php $app = new App();?>
<?php 
$field_handler	=	"";
if(isset($_POST["field_handler"]) && !is_array($_POST["field_handler"]) && $_POST["field_handler"]!="")
$field_handler	=	$_POST["field_handler"];

if($field_handler!="" && isset($_FILES[$field_handler]['tmp_name']) && $_FILES[$field_handler]['tmp_name'] != "")
{
	$file_type 		= $_FILES[$field_handler]['type'];	
	$file_name 		= $_FILES[$field_handler]['name'];
	$file_tmp_name 	= $_FILES[$field_handler]['tmp_name'];	
	$file_tmp_extension = pathinfo($file_name, PATHINFO_EXTENSION);	
	//echo $file_name, "<br/>",$file_type, "<br/>",$file_tmp_extension; die;
	if(($file_tmp_extension =="xlsx")) 
	{		
		if($field_handler == "product_batch_file")
		{
			if(isset($_SESSION['BATCH-PROD']))
				unset($_SESSION['BATCH-PROD']);
			if(isset($_SESSION['BATCH-PROD-UPDATE']))
				unset($_SESSION['BATCH-PROD-UPDATE']);
			if(isset($_SESSION['BATCH-PROD-COLUMN']))
				unset($_SESSION['BATCH-PROD-COLUMN']);						
						
			$columnArray = array();
			$import_for_update = 0;
			$primary_column_name = "";
			if(isset($_POST["import_for_update"]) && !is_array($_POST["import_for_update"]) && $_POST["import_for_update"]!="")
			$import_for_update	=	$_POST["import_for_update"];
		
			if(isset($_POST["primary_column_name"]) && !is_array($_POST["primary_column_name"]) && $_POST["primary_column_name"]!="")
			$primary_column_name	=	$_POST["primary_column_name"];

			$type = PHPExcel_IOFactory::identify($file_tmp_name);
			$objReader = PHPExcel_IOFactory::createReader($type);
			$objPHPExcel = $objReader->load($file_tmp_name);
			$product_created_date = date('Y-m-d H:i:s');
			
			$BATCH_ARRAY = array();
			$BATCH_COLUMN = null;
			
			$product_age_date = date('Y-m-d');
			$product_status = 1;
			$possibleProcessorValues = Entities::getKeyValues('possible_processor_name');
			$possibleProcessor = $possibleProcessorValues ? explode(",", $possibleProcessorValues) : array();
			
			$batchCodeArray = array();
			$product_created_by = getLoginId();
			$uniqueSerialCheckArray = array();
			
			$xslxToDbColumnMapArray = array(
				'Order' 		=> array('column' => 'product_order_number', 		'default' => 0),
				'RegId' 		=> array('column' => 'product_reg_id',				'default' => 'N/A'),
				'Type' 			=> array('column' => 'product_type',				'default' => 'N/A'),
				'Serial' 		=> array('column' => 'product_serial_number',		'default' => 'N/A'),
				'Condition' 	=> array('column' => 'product_condition',			'default' => 'N/A'),
				'Drive' 		=> array('column' => 'product_drive',				'default' => NULL),
				'Reason' 		=> array('column' => 'product_reason',				'default' => 'N/A'),				
				'Battery' 		=> array('column' => 'product_battery_cycle',		'default' => 'N/A'),
				'OSystem' 		=> array('column' => 'product_operating_system',	'default' => 'N/A'),
				'BatchType' 	=> array('column' => 'product_batch_type',			'default' => 'N/A'),
				'Batchcode' 	=> array('column' => 'product_batch_code',			'default' => NULL),
				'Instock' 		=> array('column' => 'product_in_stock',			'default' => 1),
				'Grade' 		=> array('column' => 'product_grade',				'default' => 1),
				'Sku' 			=> array('column' => 'product_sku',					'default' => NULL),
				'PartNumber' 	=> array('column' => 'product_part_number',			'default' => NULL),
				'Cost' 			=> array('column' => 'product_price',				'default' => 0),
				'StoreLoc' 		=> array('column' => 'product_store_location', 		'default' => 'UK'),	
				'onWay' 		=> array('column' => 'product_is_on_way', 			'default' => '0'),						
			);			
			$skipRow = array();
			$row = 0;
			$totalItems = 0;
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
			{
				$xlsxData = $worksheet->toArray();
				if(count($xlsxData))
				{
					foreach($xlsxData as $data)
					{
						$row++;
						if($row === 1){
							$columnArray = $data;
						}
						elseif( count($columnArray) == count($data) )
						{
							$totalItems++;
							$dataArray = array_combine($columnArray, $data);							
							$rowData = array();
							foreach($xslxToDbColumnMapArray as $_column => $_dbColumn){
								$rowData[$_dbColumn['column']] = isset($dataArray[$_column]) ? trim($dataArray[$_column]) : $_dbColumn['default'];
							}
							
							extract($rowData);
							
							if($product_serial_number != 'N/A' && $product_serial_number != ""){
								if(in_array($product_serial_number, $uniqueSerialCheckArray)){
									echo json_encode(array(300, "warning|File must have unique serial number \"product_serial_number\" repeated")); die;
								}
								else{
									array_push($uniqueSerialCheckArray, $product_serial_number);
								}
							}
							$product_verified = (isset($dataArray['Verified']) && $dataArray['Verified'] == 1) ?  'NOW()' : null;
							if($import_for_update == 1 && $primary_column_name != ""){
								if(isset($dataArray[$primary_column_name]) && trim($dataArray[$primary_column_name]) != "")
								{
									$BATCH_COLUMN = $xslxToDbColumnMapArray[$primary_column_name]['column'];
									$prodValues = array();						
								
									foreach($xslxToDbColumnMapArray as $_column => $_dbColumn){
										if(isset($dataArray[$_column])){
											$prodValues[$_dbColumn['column']] = trim($dataArray[$_column]);
										}
									}
									
									$BATCH_ARRAY[] = $prodValues; 
								}
								else{
									$skipRow[] = $row;
								}
							}
							elseif($import_for_update == 0 && $product_batch_code != NULL) //By Passing special check
							{
								if($product_batch_code == NULL || $product_batch_code == ""){
									echo json_encode(array(300, "warning|File must have Same Batchcode column value for each row.", $xlsxData)); die;
								}
								$Model = isset($dataArray['Model']) ?  $dataArray['Model'] : NULL;
								//iMac Core i5 A1418 21.5" 2.7GHz 8GB 1TB Late 2012
								$modalArr = preg_split("/GHz/i", $Model);
								if(count($modalArr) == 2)
								{
									$modalArr[0] = explode(" ", trim($modalArr[0]));
									$modalArr[1] = explode(" ", trim($modalArr[1])); 

									$product_ram = strtoupper($modalArr[1][0]);
									$product_hdd = $product_drive == 'HDD' ? strtoupper($modalArr[1][1]) : 0;
									$product_ssd = $product_drive == 'SSD' ? strtoupper($modalArr[1][1]) : 0;
									if($product_drive == 'NULL' || ($product_hdd == 0 && $product_ssd == 0)){
										if(intval($modalArr[1][1]) == 0)
										{
											$product_ssd = strtoupper($modalArr[1][1]);
											$product_hdd = strtoupper($modalArr[1][1]);
										}
										elseif(intval($modalArr[1][1]) <= 256 && (intval($modalArr[1][1])."GB" == strtoupper($modalArr[1][1]) || $modalArr[1][1]."MB" == strtoupper($modalArr[1][1])))
											$product_ssd = strtoupper($modalArr[1][1]);
										else
											$product_hdd = strtoupper($modalArr[1][1]);
									}
									$product_release = implode(" ", array_slice($modalArr[1], 2));

									$modalArray0 =  array_reverse($modalArr[0]);
									$product_processor_speed = $modalArray0[0];
									$product_screen_size = intval($modalArray0[1]);
									$product_model = $modalArray0[2];
									$product_processor = "";
									$product_name = $Model;
									foreach($possibleProcessor as $_proc){
										if(strpos(strtolower($Model), trim(strtolower($_proc)))){
											$product_processor = $_proc;
											break;
										}									
									}							
									if(strpos(strtolower($product_processor), "core") !== false || in_array($product_processor, $possibleProcessor)){
										
										$prodValues = compact('product_order_number', 'product_reg_id', 'product_type', 'product_name', 'product_serial_number', 'product_sku', 'product_model', 'product_condition', 'product_processor', 'product_processor_speed', 'product_screen_size', 'product_ram', 'product_ssd', 'product_hdd', 'product_release', 'product_reason', 'product_battery_cycle', 'product_operating_system', 'product_grade',  'product_batch_type', 'product_batch_code', 'product_created_date', 'product_in_stock', 'product_age_date', 'product_status', 'product_created_by', 'product_part_number', 'product_price', 'product_store_location', 'product_verified','product_is_on_way');
										$BATCH_ARRAY[] = $prodValues; 
									}else{
										$skipRow[] = $row;
									}
								}
								else{
									$skipRow[] = $row;
								}
							}
						}
					}
				}
				else
					echo json_encode(array(300, "warning|Files Don't have rows."));
			}
			
			
			if($import_for_update == 0 && count($xlsxData) == count($skipRow)){					
				echo json_encode(array(300, "warning|File may have multiple Batch Code or Don't have batch code."));
				die;
			}
			else if($import_for_update == 1 && $primary_column_name != "")
			{	
				$message = "<i class='fa fa-fw fa-check text-primary'></i> ".($totalItems). " Total Products in xslx file<br/>";
				$message .= "<i class='fa fa-fw fa-check text-success'></i> ".count($BATCH_ARRAY). " Success Products found to update<br/>";
				if(count($skipRow) > 0){
					$message .= "<i class='fa fa-fw fa-close text-danger'></i> ".count($skipRow). " Failed to update in Row number (".implode(', ', $skipRow).")<br/>Skip these rows and proceed to update <br/>";
				}
				
				$message .= "<center><br/><a href=\"javascript:proceedtoimport('".md5(strtotime($product_created_date))."')\" class='btn  btn-success text-white'>Update now</a><br/>";
				$_SESSION['BATCH-PROD-UPDATE'][md5(strtotime($product_created_date))] = $BATCH_ARRAY;
				$_SESSION['BATCH-PROD-UPDATE']['COLUMN'] = $BATCH_COLUMN;
				echo json_encode(array(200, "success|File Product Checked Successfully</center>", SUCCESSFULL, $message));
			}
			else
			{
				$message = "<i class='fa fa-fw fa-check text-primary'></i> ".($totalItems). " Total Products found to import<br/>";
				$message .= "<i class='fa fa-fw fa-check text-success'></i> ".count($BATCH_ARRAY). " Success Products found to import<br/>";
				if(count($skipRow) > 0){
					$message .= "<i class='fa fa-fw fa-close text-danger'></i> ".count($skipRow). " Failed to import in Row number (".implode(', ', $skipRow).")<br/>Skip these rows and proceed to import <br/>";
				}
				
				$message .= "<center><br/><a href=\"javascript:proceedtoimport('".md5(strtotime($product_created_date))."')\" class='btn  btn-success text-white'>Import now</a><br/>";
				$_SESSION['BATCH-PROD'][md5(strtotime($product_created_date))] = $BATCH_ARRAY;
				echo json_encode(array(200, "success|File Product Checked Successfully</center>", SUCCESSFULL, $message));
			}
		}
		else
			echo json_encode(array(300, "warning|Unsupported Action found."));
	}
	else
	{
	  echo json_encode(array(300, "warning|Unsupported file. Please try again"));
	}
}
else
echo json_encode(array(300, "danger|Ooops... File Upload handler not found. Please refresh and try again."));
?>