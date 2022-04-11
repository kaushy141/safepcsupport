<?php include("setup.php"); ?>
<?php 
if(isset($_SESSION['user_id']))
{
	error_reporting(E_ALL);
	$mediasection = "";
	$id = 0;
	$record_status = 1;
	$field_handler = 'webcam';
	$data = sanitizePostData($_POST);
	
	if($data && is_array($data) && !empty($data))
	{
		extract($data);
		if($mediasection == MediaSection::$collectionMedia)
		{
			$collection = new Collection($id);
			$wcData	=	$collection->load();

			$WcRecordMedia = new WcRecordMedia(0);

			if($wcData)
			{
				$image_id_array = [];
				$image_path_array = [];
				if(is_array($_FILES[$field_handler]["name"]) && count($_FILES[$field_handler]["name"])> 0){
					for($index=0; $index < count($_FILES[$field_handler]["name"]); $index++)
					{
						$fileName = $_FILES[$field_handler]["name"][$index];
						$fileTmpLoc = $_FILES[$field_handler]["tmp_name"][$index];  
						$fileType = $_FILES[$field_handler]["type"][$index]; 
						$fileSize = $_FILES[$field_handler]["size"][$index];  
						$fileErrorMsg = $_FILES[$field_handler]["error"][$index];
					

						$image_name = pathinfo($fileName);
						$extension = strtolower($image_name['extension']);
						$name=time()."_".rand(100000,999999).".".$extension;
						$image_path = "upload/collection/record/".getDirectorySeparatorPath().$wcData['wc_code']."_".time().".$extension";
						if (move_file($fileTmpLoc, $app->sitePath($image_path)))
						{
							$image_id_array[] = $WcRecordMedia->add($id, "Image", $image_path, getLoginId(), "Added by $_SESSION[user_fname]", $record_status);
							$image_path_array[] = $app->basePath($image_path);
						}	
					}
				}
						
				echo json_encode(array(200,"success|Image saved on sales request.", "image_path_array"=>$image_path_array, "id"=>$id, "image_id_array"=> $image_id_array));	
				
			}
			else
				echo json_encode(array("300","danger|Collection Record not Exists or No Collection media file found."));
		}
		elseif($mediasection === MediaSection::$repairMedia)
		{
			$cm = new Complaint($id);
			$cData	=	$cm->getDetails();

			if($cData)
			{
				$image_id_array = [];
				$image_path_array = [];
				$repair_image_media_type = isset($mediasection_type) ? intval($mediasection_type) : 0;
				if(is_array($_FILES[$field_handler]["name"]) && count($_FILES[$field_handler]["name"])> 0){
					for($index=0; $index < count($_FILES[$field_handler]["name"]); $index++)
					{
						$fileName = $_FILES[$field_handler]["name"][$index];
						$fileTmpLoc = $_FILES[$field_handler]["tmp_name"][$index];  
						$fileType = $_FILES[$field_handler]["type"][$index]; 
						$fileSize = $_FILES[$field_handler]["size"][$index];  
						$fileErrorMsg = $_FILES[$field_handler]["error"][$index];				

						$image_name = pathinfo($fileName);
						$extension = strtolower($image_name['extension']);
						$name=time()."_".rand(100000,999999).".".$extension;
						$image_path = "upload/comp/image/".getDirectorySeparatorPath().$cData['complaint_id']."_".time().".$extension";
						if (move_file($fileTmpLoc, $app->sitePath($image_path)))
						{
							$cmImage = new ComplaintMedia(0);
							$image_id_array[] = $cmImage->insert(
							array(
								"repair_image_complaint_id"	=>	$id,
								"repair_image_title"		=>	"Image for $cData[complaint_ticket_number]",
								"repair_image_date"			=>	'NOW()',
								"repair_image_path"			=>  $image_path,
								"repair_image_media_type"	=>	$repair_image_media_type,
								"repair_image_status"		=>	1
							));
							$image_path_array[] = $app->basePath($image_path);
						}	
					}
				}
						
				echo json_encode(array(200,"success|Image saved on repair request.", "image_path_array"=>$image_path_array, "id"=>$id, "image_id_array"=> $image_id_array, "type"=>$repair_image_media_type));			
			}
			
			else
				echo json_encode(array("300","danger|Collection Record not Exists or No Collection media file found."));
		}
		elseif($mediasection === MediaSection::$SalesProductMedia)
		{
			$cm = new SalesInvoice($id);
			$cData	=	$cm->getDetails();

			if($cData)
			{
				$image_id_array = [];
				$image_path_array = [];
				if(is_array($_FILES[$field_handler]["name"]) && count($_FILES[$field_handler]["name"])> 0){
					for($index=0; $index < count($_FILES[$field_handler]["name"]); $index++)
					{
						$fileName = $_FILES[$field_handler]["name"][$index];
						$fileTmpLoc = $_FILES[$field_handler]["tmp_name"][$index];  
						$fileType = $_FILES[$field_handler]["type"][$index]; 
						$fileSize = $_FILES[$field_handler]["size"][$index];  
						$fileErrorMsg = $_FILES[$field_handler]["error"][$index];
					

						$image_name = pathinfo($fileName);
						$extension = strtolower($image_name['extension']);
						$name=time()."_".rand(100000,999999).".".$extension;
						$image_path = "upload/sales/image/".getDirectorySeparatorPath().$cData['sales_invoice_id']."_".time().".$extension";
						if (move_file($fileTmpLoc, $app->sitePath($image_path)))
						{
							$cmImage = new SalesMedia(0);
							$image_id_array[] = $cmImage->insert(
									array(
										"sales_image_invoice_id"	=>	$id,
										"sales_image_title"			=>	"Image for $cData[sales_invoice_number]",
										"sales_image_date"			=>	'NOW()',
										"sales_image_path"			=>  $image_path,
										"sales_image_status"		=>	1
									));
							$image_path_array[] = $app->basePath($image_path);
						}	
					}
				}
						
				echo json_encode(array(200,"success|Image saved on sales request.", "image_path_array"=>$image_path_array, "id"=>$id, "image_id_array"=> $image_id_array));
			}
			
			else
				echo json_encode(array("300","danger|Collection Record not Exists or No Collection media file found."));
		}
		elseif($mediasection === MediaSection::$batchProductMedia)
		{
			$bp = new BatchProduct($id);
			$cData	=	$bp->getDetails();

			if($cData)
			{
				$image_id_array = [];
				$image_path_array = [];
				if(is_array($_FILES[$field_handler]["name"]) && count($_FILES[$field_handler]["name"])> 0){
					for($index=0; $index < count($_FILES[$field_handler]["name"]); $index++)
					{
						$fileName = $_FILES[$field_handler]["name"][$index];
						$fileTmpLoc = $_FILES[$field_handler]["tmp_name"][$index];  
						$fileType = $_FILES[$field_handler]["type"][$index]; 
						$fileSize = $_FILES[$field_handler]["size"][$index];  
						$fileErrorMsg = $_FILES[$field_handler]["error"][$index];
					

						$image_name = pathinfo($fileName);
						$extension = strtolower($image_name['extension']);
						$name=time()."_".rand(100000,999999).".".$extension;
						$image_path = "upload/batchpro/image/".getDirectorySeparatorPath().$cData['product_id']."_".time().".$extension";
						if (move_file($fileTmpLoc, $app->sitePath($image_path)))
						{
							$cmImage = new BatchProductMedia(0);
							$image_id_array[] = $cmImage->insert(
									array(
										"image_product_id"	=>	$id,
										"image_label"		=>	"Image for $cData[product_code]",
										"image_created_date"=>	'NOW()',
										"image_path"		=>  $image_path
									));
							$image_path_array[] = $app->basePath($image_path);
						}	
					}
				}
						
				echo json_encode(array(200,"success|Batch Product Image uploaded.", "image_path_array"=>$image_path_array, "id"=>$id, "image_id_array"=> $image_id_array));
			}
			
			else
				echo json_encode(array("300","danger|Batch Product not Exists or No Batch Product media file found."));
		}
		elseif($mediasection === MediaSection::$collectionProductMedia)
		{
			$cm = new CollectionProcess($id);
			$cData	=	$cm->getDetails();

			if($cData)
			{
				$image_id_array = [];
				$image_path_array = [];
				if(is_array($_FILES[$field_handler]["name"]) && count($_FILES[$field_handler]["name"])> 0){
					for($index=0; $index < count($_FILES[$field_handler]["name"]); $index++)
					{
						$fileName = $_FILES[$field_handler]["name"][$index];
						$fileTmpLoc = $_FILES[$field_handler]["tmp_name"][$index];  
						$fileType = $_FILES[$field_handler]["type"][$index]; 
						$fileSize = $_FILES[$field_handler]["size"][$index];  
						$fileErrorMsg = $_FILES[$field_handler]["error"][$index];
					

						$image_name = pathinfo($fileName);
						$extension = strtolower($image_name['extension']);
						$name=time()."_".rand(100000,999999).".".$extension;
						$image_path = "upload/collection/item/".getDirectorySeparatorPath().$cData['wc_process_id']."_".time().".$extension";
						if (move_file($fileTmpLoc, $app->sitePath($image_path)))
						{
							$cmImage = new CollectionItemMedia(0);
							$image_id_array[] = $cmImage->insert(
									array(
										"image_process_code"	=>	$id,
										"image_label"		=>	"Image for $id",
										"image_created_date"			=>	'NOW()',
										"image_path"			=>  $image_path
									));
							$image_path_array[] = $app->basePath($image_path);
						}	
					}
				}
						
				echo json_encode(array(200,"success|Image saved on sales request.", "image_path_array"=>$image_path_array, "id"=>$id, "image_id_array"=> $image_id_array));
			}
			
			else
				echo json_encode(array("300","danger|Collection Record not Exists or No Collection media file found."));
		}
		elseif($mediasection === MediaSection::$webOrderProductMedia)
		{
			$wo = new WebsiteOrderProduct($id);
			$woData	=	$wo->getDetails();

			if($woData)
			{
				$image_id_array = [];
				$image_path_array = [];
				if(is_array($_FILES[$field_handler]["name"]) && count($_FILES[$field_handler]["name"])> 0){
					for($index=0; $index < count($_FILES[$field_handler]["name"]); $index++)
					{
						$fileName = $_FILES[$field_handler]["name"][$index];
						$fileTmpLoc = $_FILES[$field_handler]["tmp_name"][$index];  
						$fileType = $_FILES[$field_handler]["type"][$index]; 
						$fileSize = $_FILES[$field_handler]["size"][$index];  
						$fileErrorMsg = $_FILES[$field_handler]["error"][$index];
					

						$image_name = pathinfo($fileName);
						$extension = strtolower($image_name['extension']);
						$name=time()."_".rand(100000,999999).".".$extension;
						$image_path = "upload/weborder/image/".getDirectorySeparatorPath().$woData['wo_id']."_".time().".$extension";
						if (move_file($fileTmpLoc, $app->sitePath($image_path)))
						{
							$wopm = new WebsiteOrderProductMedia(0);
							$image_id_array[] = $wopm->insert(
							array(
								"wpoi_wo_id"		=>	$id,
								"wpoi_image_path"	=>	$image_path,
								"wpoi_created_date"	=>	'NOW()',
								"wpoi_added_by"		=>  getLoginId(),
								"wpoi_status"		=>	1
							));
							
							$image_path_array[] = $app->basePath($image_path);
						}	
					}
				}
						
				echo json_encode(array(200,"success|Image saved on product.", "image_path_array"=>$image_path_array, "id"=> $id, "image_id_array"=> $image_id_array));
				
			}
			
			else
				echo json_encode(array("300","danger|Collection Record not Exists or No Collection media file found."));
		}
		elseif($mediasection === MediaSection::$webOrderLabelMedia)
		{
			$allowedExtensionArray = array('png', 'jpg', 'jpeg', 'pdf');
			$websiteOrder = new WebsiteOrder($id);
			$webData	=	$websiteOrder->getDetails();
			if($webData)
			{
				if(!isAdminRole()){
					if($field_name == "web_order_label_image" && $webData['web_order_process_user'] != 0)
					{
						echo json_encode(array("300","danger|Web Order Already Processed."));
						die;
					}
					elseif($field_name == "web_order_invoice_image" && $webData['web_order_invoice_image'] != "")
					{
						echo json_encode(array("300","danger|Web Order Invoice Already uploaded."));
						die;
					}
				}
				if(is_array($_FILES[$field_handler]["name"]) && count($_FILES[$field_handler]["name"]) == 1)
				{
					Modal::load(array('WeborderLabels'));
					for($index=0; $index < count($_FILES[$field_handler]["name"]); $index++)
					{
						$fileName = $_FILES[$field_handler]["name"][$index];
						$fileTmpLoc = $_FILES[$field_handler]["tmp_name"][$index];  
						$fileType = $_FILES[$field_handler]["type"][$index]; 
						$fileSize = $_FILES[$field_handler]["size"][$index];  
						$fileErrorMsg = $_FILES[$field_handler]["error"][$index];				
						
						$image_name = pathinfo($fileName);					
						$extension = strtolower($image_name['extension']);
						if(in_array($extension, $allowedExtensionArray))
						{
							$name=time()."_".rand(100000,999999).".".$extension;
							$uploaded_image = "upload/weborder/".($field_name == "web_order_invoice_image" ? "invoice":"label")."/".getDirectorySeparatorPath().trim(normalizeString($image_name['filename']))."_".$id."_".time().".$extension";
							if (move_file($fileTmpLoc, $app->sitePath($uploaded_image)))
							{
								if($field_name == "web_order_invoice_image")
								{							
									$websiteOrder->update(array(
										"web_order_invoice_image" 	=> $uploaded_image,
										"web_order_invoice_date" 	=> "NOW()"
									));
								}
								elseif($field_name == "web_order_label_image")
								{							
									$WeborderLabels = new WeborderLabels();
									$WeborderLabels->insert(array(
										"label_order_id" 		=> $id,
										"label_type"			=> $label_type,
										"label_shipment_type"	=> isset($label_shipment_service_type) ? $label_shipment_service_type : 'NULL',
										"label_shipment_code"	=> isset($label_shipment_service_code) ? $label_shipment_service_code : 0,
										"label_details"	=> isset($label_details_field) ? $label_details_field : 'NULL',										
										"label_path" 			=> $uploaded_image,
										"label_uploaded_by"		=> getLoginId(),
										"label_uploaded_date"	=> "NOW()",
										"label_downloads"	=> 0,
										"label_status"	=> 1
									));
								}
							}
						}
						else{
							echo json_encode(array(300,"success|Only ".implode(", ", $allowedExtensionArray)." file format accepted."));
							die;
						}
					}
					if($field_name == "web_order_invoice_image"){
						Activity::add("uploaded <b>Invoice</b> on |^|{$webData['web_order_number']}", "O", $id);
						echo json_encode(array(200,"success|Invoices Uploaded Successfully."));
					}
					elseif($field_name == "web_order_label_image"){
						Activity::add("uploaded <b>Label</b> on |^|{$webData['web_order_number']}", "O", $id);
						echo json_encode(array(200,"success|Label Uploaded Successfully."));
					}
				}
				else		
					echo json_encode(array("300","danger|Only Single file accepted."));				
			}		
			else
				echo json_encode(array("300","danger|Weborder Not found."));
		}
		elseif($mediasection === MediaSection::$webOrderCommissionFile)
		{
			$allowedExtensionArray = array('png', 'jpg', 'jpeg', 'pdf');
			$websiteOrder = new WebsiteOrder($id);
			$webData	=	$websiteOrder->getDetails();
			if($webData)
			{
				if(!isAdminRole()){
					if($field_name == "web_order_commission_user_file" && $webData['web_order_commission_user_file'] != 0)
					{
						echo json_encode(array("300","danger|Web Order Commission proof already uploaded."));
						die;
					}
				}
				if(is_array($_FILES[$field_handler]["name"]) && count($_FILES[$field_handler]["name"]) == 1)
				{
					for($index=0; $index < 1; $index++)
					{
						$fileName = $_FILES[$field_handler]["name"][$index];
						$fileTmpLoc = $_FILES[$field_handler]["tmp_name"][$index];  
						$fileType = $_FILES[$field_handler]["type"][$index]; 
						$fileSize = $_FILES[$field_handler]["size"][$index];  
						$fileErrorMsg = $_FILES[$field_handler]["error"][$index];				
						
						$image_name = pathinfo($fileName);					
						$extension = strtolower($image_name['extension']);
						if(in_array($extension, $allowedExtensionArray))
						{
							$name=time()."_".rand(100000,999999).".".$extension;
							$uploaded_image = "upload/weborder/comm/".getDirectorySeparatorPath().trim(normalizeString($image_name['filename']))."_".$id."_".time().".$extension";
							if (move_file($fileTmpLoc, $app->sitePath($uploaded_image)))
							{
								$websiteOrder->update(array(
									"web_order_commission_user_file" 	=> $uploaded_image,
									"web_order_commission_user_file_uploader" => getLoginId(),
									"web_order_commission_user_file_time" 	=> "NOW()"
								));							
							}
						}
						else{
							echo json_encode(array(300,"success|Only ".implode(", ", $allowedExtensionArray)." file format accepted."));
							die;
						}
					}
					Activity::add("uploaded <b>Commission Proof</b> on |^|{$webData['web_order_number']}", "O", $id);
					echo json_encode(array(200,"success|Commission proof Uploaded Successfully."));
				}
				else		
					echo json_encode(array("300","danger|Only Single file accepted."));				
			}		
			else
				echo json_encode(array("300","danger|Weborder Not found."));
		}
		elseif($mediasection === MediaSection::$repairLabelInvoiceMedia)
		{
			$allowedExtensionArray = array('png', 'jpg', 'jpeg', 'pdf');
			$complaint = new Complaint($id);
			$webData	=	$complaint->getDetails();
			if($webData)
			{
				if(!isAdminRole()){
					if($field_name == "complaint_label_image" && $webData['complaint_process_user'] != 0)
					{
						echo json_encode(array("300","danger|RMA Reapir Already Processed."));
						die;
					}
					elseif($field_name == "complaint_invoice_image" && $webData['complaint_invoice_image'] != "")
					{
						echo json_encode(array("300","danger|RMA Reapir Invoice Already uploaded."));
						die;
					}
				}
				if(is_array($_FILES[$field_handler]["name"]) && count($_FILES[$field_handler]["name"]) == 1)
				{
					Modal::load(array('ComplaintLabels'));
					for($index=0; $index < count($_FILES[$field_handler]["name"]); $index++)
					{
						$fileName = $_FILES[$field_handler]["name"][$index];
						$fileTmpLoc = $_FILES[$field_handler]["tmp_name"][$index];  
						$fileType = $_FILES[$field_handler]["type"][$index]; 
						$fileSize = $_FILES[$field_handler]["size"][$index];  
						$fileErrorMsg = $_FILES[$field_handler]["error"][$index];				
						
						$image_name = pathinfo($fileName);					
						$extension = strtolower($image_name['extension']);
						if(in_array($extension, $allowedExtensionArray))
						{
							$name=time()."_".rand(100000,999999).".".$extension;
							$uploaded_image = "upload/comp/".($field_name == "complaint_invoice_image" ? "invoice":"label")."/".getDirectorySeparatorPath().trim(normalizeString($image_name['filename']))."_".$id."_".time().".$extension";
							if (move_file($fileTmpLoc, $app->sitePath($uploaded_image)))
							{
								$complaintLabels = new ComplaintLabels();
								$complaintLabels->insert(array(
									"label_complaint_id" 		=> $id,
									"label_type"			=> $label_type,
									"label_shipment_type"	=> isset($label_shipment_service_type) ? $label_shipment_service_type : 'NULL',
									"label_shipment_code"	=> isset($label_shipment_service_code) ? $label_shipment_service_code : 0,
									"label_details"			=> isset($label_details_field) ? $label_details_field : 'NULL',
									"label_path" 				=> $uploaded_image,
									"label_uploaded_by"			=> getLoginId(),
									"label_uploaded_date"		=> "NOW()",
									"label_downloads"			=> 0,
									"label_status"				=> 1
								));
							}
						}
						else{
							echo json_encode(array(300,"warning|Only ".implode(", ", $allowedExtensionArray)." file format accepted."));
							die;
						}
					}
					Activity::add("uploaded <b>Label</b> on |^|{$webData['complaint_ticket_number']}", "C", $id);
					echo json_encode(array(200,"success|Label Uploaded Successfully."));
				}
				else		
					echo json_encode(array("300","danger|Only Single file accepted.."));				
			}		
			else
				echo json_encode(array("300","danger|RMA Reapir Not found."));
		}
		elseif($mediasection === MediaSection::$salesLabelInvoiceMedia)
		{
			$allowedExtensionArray = array('png', 'jpg', 'jpeg', 'pdf');
			$salesinvoice = new SalesInvoice($id);
			$salesData	=	$salesinvoice->getDetails();
			if($salesData)
			{
				if(!isAdminRole()){
					if($field_name == "sales_invoice_label_image" && $salesData['sales_invoice_process_user'] != 0)
					{
						echo json_encode(array("300","danger|Sales Invoice Already Processed."));
						die;
					}
				}
				if(is_array($_FILES[$field_handler]["name"]) && count($_FILES[$field_handler]["name"]) == 1)
				{
					Modal::load(array('SalesLabels'));
					$salesInvoice = new SalesInvoice($id);
					$salesData = $salesInvoice->getDetails();
					for($index=0; $index < count($_FILES[$field_handler]["name"]); $index++)
					{
						$fileName = $_FILES[$field_handler]["name"][$index];
						$fileTmpLoc = $_FILES[$field_handler]["tmp_name"][$index];  
						$fileType = $_FILES[$field_handler]["type"][$index]; 
						$fileSize = $_FILES[$field_handler]["size"][$index];  
						$fileErrorMsg = $_FILES[$field_handler]["error"][$index];				
						
						$image_name = pathinfo($fileName);					
						$extension = strtolower($image_name['extension']);
						if(in_array($extension, $allowedExtensionArray))
						{
							$name=time()."_".rand(100000,999999).".".$extension;
							$uploaded_image = "upload/sales/label/".getDirectorySeparatorPath().trim(normalizeString($image_name['filename']))."_".$id."_".time().".$extension";
							if (move_file($fileTmpLoc, $app->sitePath($uploaded_image)))
							{
								$SalesLabels = new SalesLabels();
								$SalesLabels->insert(array(
									"label_order_id" 		=> $id,
									"label_type"			=> $label_type,
									"label_shipment_type"	=> isset($label_shipment_service_type) ? $label_shipment_service_type : 'NULL',
									"label_shipment_code"	=> isset($label_shipment_service_code) ? $label_shipment_service_code : 0,
									"label_details"			=> isset($label_details_field) ? $label_details_field : 'NULL',
									"label_path" 			=> $uploaded_image,
									"label_uploaded_by"		=> getLoginId(),
									"label_uploaded_date"	=> "NOW()",
									"label_downloads"	=> 0,
									"label_status"	=> 1
								));
							}
						}
						else{
							echo json_encode(array(300,"success|Only ".implode(", ", $allowedExtensionArray)." file format accepted."));
							die;
						}
					}
					if($field_name == "sales_invoice_label_image"){
						Activity::add("uploaded <b>Label</b> on |^|{$salesData['sales_invoice_number']}", "S", $id);
						echo json_encode(array(200,"success|Label Uploaded Successfully."));
					}
				}
				else		
					echo json_encode(array("300","danger|Only Single file accepted."));				
			}		
			else
				echo json_encode(array("300","danger|Sales Invoice Not found."));
		}
	}
	else
		echo json_encode(array("300","danger|No Data Found."));				
}
else
	echo json_encode(array("300","danger|Session Expired. Please login again"));		
?>