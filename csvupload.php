<?php include("setup.php"); ?>
<?php include("config/session.php");?>
<?php include("config/fd/facedetector.php");?>
<?php $app = new App();?>
<?php 
$mimes = array('application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.wordprocessingml.document','text/plain','text/csv','text/tsv','text/pdf','application/pdf', 'application/msword', 'application/vnd.ms-powerpoint','application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'image/jpeg', 'image/jpg', 'image/png');
$field_handler	=	"";
if(isset($_POST["field_handler"]) && !is_array($_POST["field_handler"]) && $_POST["field_handler"]!="")
$data = sanitizePostData($_POST);
extract($data);
if($field_handler!="" && isset($_FILES[$field_handler]['tmp_name']) && $_FILES[$field_handler]['tmp_name'] != "")
{
	$file_type 		= $_FILES[$field_handler]['type'];	
	$file_name 		= $_FILES[$field_handler]['name'];
	$file_tmp_name 	= $_FILES[$field_handler]['tmp_name'];	
	$file_tmp_extension = pathinfo($file_name, PATHINFO_EXTENSION);	
	//echo $file_name, "<br/>",$file_type, "<br/>",$file_tmp_extension; die;
	if(($file_type =="text/csv" || $file_type =="application/vnd.ms-excel") && ($field_handler == "ebay_csv_file" || $field_handler == "customer_csv_file")) 
	{
		$ebay_import_status = 1; 	
		$ebay_import_ip_address	 = IP_ADDRESS;
		$ebay_import_user_id = $_SESSION['user_id'];
		$uploadFile 	= "upload/csv/".getDirectorySeparatorPath()."".trim(time().".csv");					
		move_file($file_tmp_name, BP.$uploadFile);
		
		
		$fp = fopen($uploadFile,'r') or die("can't open file");
		$row = 0;
		$file = fopen($uploadFile, "r");
		if($field_handler == "ebay_csv_file")
		{
			$EbaySaleData = new EbaySaleData();
			while (($data = fgetcsv($file, 8000, ",")) !== FALSE) 
			{
				$num = count($data);
				$row++;
				if($row!=1)
				{
					$data = sanitizePostData($data);					
					if($data[10]!="")
					{
						$data[] = 1;
						unset($data[0]);
						if(!$EbaySaleData->isExists($data[1], $data[3]))
						$EbaySaleData->add($data);
					}
				}
			}
				
			$EbayCsv = new EbayCsv();
			$EbayCsv->add($file_name, $row, $ebay_import_user_id, $ebay_import_status, $ebay_import_ip_address);
			Activity::add("imported eBay sales records file <b>$file_name</b>");	
			echo json_encode(array(200, "success|File Imported Successfully", SUCCESSFULL));
		}
		if($field_handler == "customer_csv_file")
		{
			$customer     = new Customer();
			$importCount = 0;
			$importTotalCount = 0;
			$auto_customer_feedback = 0;
			$store_id = 0;
			$postData = sanitizePostData($_POST);
			extract($postData);
			$storeData = NULL;
			if($store_id !=0)
			{
				$store = new Store($store_id);
				$storeData = $store->getDetails();
			}
			while (($data = fgetcsv($file, 8000, ",")) !== FALSE) 
			{
				$num = count($data);
				$row++;
				if($row!=1)
				{
					$data = sanitizePostData($data);					
					if($data[2]!="")
					{
						$data[] = 1;
						unset($data[0]);
						if(!$customer->isEmailExists($data[2]))
						{
							$customer_name = $data[1];
							$customer_name_array = explode(" ", $customer_name, 2);
							$customer_email = strtolower($data[2]);
							$customer_fname = $customer_name_array[0];
							$customer_lname = count($customer_name_array)==2 ? $customer_name_array[1] : $customer_name_array[0];
							
							$customer_code = $customer->getNewCode($customer_email, $customer_fname . $customer_lname);
							$customer_phone = $data[4];
							$customer_company = "";
							$customer_type_id = 3;
							$customer_image = DEFAULT_USER_IMAGE;
							$customer_status             = 1;
							$customer_password           = gePassword();
							$customer_is_mobile_verified = 0;
							$customer_is_email_verified  = 0;
							$customer_created_by = $_SESSION['user_id'];
							
							$customer_id = $customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
							
							
							new SMS($customer_phone, "Hi $customer_fname, Welcome to " . $app->siteName . ". Your account had been created with us successfully. Your username is \"$customer_email\" and Password is $customer_password");
							#==============================trig Email============= 
							$activation_link = $app->basePath("activation.php?r=cst&u=$customer_email&l=" . md5($customer_email . $customer_id) . "&i=" . md5($customer_id . $customer_email));
							$dataArray       = array(
								"customer_name" => $customer_fname,
								"customer_email" => $customer_email,
								"customer_password" => $customer_password,
								"login_page" => $app->basePath("customer-login.php"),
								"activation_link" => $activation_link
							);
							$email           = new Email("New Customer Account on " . $app->siteName);
							$email->to($customer_email, $customer_fname, $app->basePath($customer_image))->template('customer_registration', $dataArray)->send();
							$importCount++;
							
							if($auto_customer_feedback && $store_id != 0 && $storeData != NULL)
							{								
								$feedback_module_code = "U";
								$feedback_module_id = $customer_id;
								$module_section_name  = "Customer";
								$module_section_code  = $customer_name;   
								$feedback_store = $storeData['store_key']; 
								$feedback_status = 1; 
								$store_google_feedback_url     = $storeData['store_google_feedback_url'];
								$store_trustpilot_feedback_url = $storeData['store_trustpilot_feedback_url'];
								$store_facebook_feedback_url   = $storeData['store_facebook_feedback_url']; 
								$feedback          = new Feedback();
								$feedback_id       = $feedback->add($customer_id, $feedback_module_id, $feedback_module_code, $feedback_status, $feedback_store);
								$feedback_hashcode = strtoupper("FD" . "-" . md5($feedback_id) . "-" . md5($customer_id) . "-" . $feedback_module_code . "-" . md5($feedback_module_id));
								$feedback_link     = $app->basePath("feedback.php?feedback_hashcode=$feedback_hashcode&aouthv1=" . strtoupper(md5(strtoupper($feedback_hashcode))) . "&likes");
								$data = array(
									"customer_name" => $customer_name,
									"feedback_link" => $feedback_link,
									"message" => "",
									"store_google_feedback_url" => $store_google_feedback_url,
									"store_trustpilot_feedback_url" => $store_trustpilot_feedback_url,
									"store_facebook_feedback_url" => $store_facebook_feedback_url,
									"module_section_name" =>$module_section_name,
									"module_section_code" => $module_section_name
								);								
								
								$email = new Email($app->siteName . " : " . "Feedback for $module_section_name #$module_section_code on $storeData[store_name]");
								$email->to($customer_email, $customer_name, $app->basePath($customer_image));
								$email->template("customerfeedback", $data);
								$email->send("Thankyou for giving us chance for service on " . $app->siteName);
							}
						}
					}
				}
				$importTotalCount++;
			}
			Activity::add("imported Customer records file <b>$file_name</b>");	
			echo json_encode(array(200, "success|$importCount/$importTotalCount Users Imported Successfully", SUCCESSFULL));
		}
	}
	elseif(in_array($file_type,$mimes)) {
		if($field_handler == "templates_file")
		{
			$templates = new Templates();
			$templates_name = basename($file_name, ".$file_tmp_extension");
			$templates_file_path = DOC_UPLOAD_DIR."templates/".getDirectorySeparatorPath()."templates-".time().".$file_tmp_extension";
			$templates_status = 1;
			if(move_file($file_tmp_name, BP.$templates_file_path))
			{
				$templates->add($templates_name, $templates_file_path, $templates_status);
				echo json_encode(array(200, "success|Templates file Uploaded successfully", SUCCESSFULL));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "licence_file")
		{
			$licences = new Licences();
			$licence_name = basename($file_name, ".$file_tmp_extension");
			$licence_file_path = DOC_UPLOAD_DIR."licence/".getDirectorySeparatorPath()."licence-".time().".$file_tmp_extension";
			$licence_status = 1;
			if(move_file($file_tmp_name, BP.$licence_file_path))
			{
				$licences->add($licence_name, $licence_folder, $licence_file_path, $licence_status);
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "policies_file")
		{
			$Policies = new Policies();
			$policies_name = basename($file_name, ".$file_tmp_extension");
			$policies_file_path = DOC_UPLOAD_DIR."policies/".getDirectorySeparatorPath()."policies-".time().".$file_tmp_extension";
			$policies_status = 1;
			if(move_file($file_tmp_name, BP.$policies_file_path))
			{
				$Policies->add($policies_name, $policies_file_path, $policies_status);
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}elseif($field_handler == "company_invoice_file")
		{
			$CompanyInvoice = new CompanyInvoice();
			$company_invoice_name = basename($file_name, ".$file_tmp_extension");
			$company_invoice_file_path = DOC_UPLOAD_DIR."company/invoice/".getDirectorySeparatorPath()."invoice-".time().".$file_tmp_extension";
			$company_invoice_status = 1;
			if(move_file($file_tmp_name, BP.$company_invoice_file_path))
			{
				$CompanyInvoice->add($company_invoice_name, $company_invoice_file_path, $company_invoice_status);
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "isms_file")
		{
			$isms = new Isms();
			$isms_name = basename($file_name, ".$file_tmp_extension");
			$isms_file_path = DOC_UPLOAD_DIR."isms/isms-".time().".$file_tmp_extension";
			$isms_status = 1;
			if(move_file($file_tmp_name, BP.$isms_file_path))
			{
				$isms->add($isms_name, $isms_file_path, $isms_status);
				echo json_encode(array(200, "success|File Uploaded successfully", SUCCESSFULL));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "cont_ins_file")
		{
			$cont_ins_file_path = DOC_TEMP_UPLOAD_DIR."contarct-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$cont_ins_file_path))
			{
				echo json_encode(array(200, "success|File Uploaded successfully", SUCCESSFULL, "cont_ins_file_path_callback", $cont_ins_file_path));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "utility_doc_file")
		{
			$utility_doc_file_path = DOC_TEMP_UPLOAD_DIR."utility-doc-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$utility_doc_file_path))
			{
				echo json_encode(array(200, "success|File Uploaded successfully", SUCCESSFULL, "utility_doc_file_path_callback", $utility_doc_file_path));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "supplier_doc_file")
		{
			$supplier_doc_file_path = DOC_TEMP_UPLOAD_DIR."supplier-doc-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$supplier_doc_file_path))
			{
				echo json_encode(array(200, "success|File Uploaded successfully", SUCCESSFULL, "supplier_doc_file_path_callback", $supplier_doc_file_path));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}		
		elseif($field_handler == "cont_ins_file")
		{
			$cont_ins_file_path = DOC_TEMP_UPLOAD_DIR."contarct-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$cont_ins_file_path))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "cont_ins_file_path_callback", $cont_ins_file_path));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "checklist_file")
		{
			$cont_ins_file_path = DOC_TEMP_UPLOAD_DIR."checklist-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$cont_ins_file_path))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "checklist_file_path_callback", $cont_ins_file_path));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "manual_file")
		{
			$ManualTrainingDoc = new ManualTrainingDoc();
			$manual_name = basename($file_name, ".php");
			$manual_file_path = DOC_UPLOAD_DIR."manual/".getDirectorySeparatorPath()."manual-".time().".$file_tmp_extension";
			$manual_status = 1;
			if(move_file($file_tmp_name, BP.$manual_file_path))
			{
				$ManualTrainingDoc->add($manual_name, $manual_file_path, $manual_status);
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "user_file")
		{
			$user_cv_file = DOC_TEMP_UPLOAD_DIR."usercv-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$user_cv_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "user_cv_file_callback", $user_cv_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "user_id_card_file")
		{
			$user_id_card_file = DOC_TEMP_UPLOAD_DIR."userid-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$user_id_card_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "user_id_card_file_callback", $user_id_card_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "notice_attachement_file")
		{
			$notice_attachement_file = DOC_TEMP_UPLOAD_DIR."notice-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$notice_attachement_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "notice_attachement_file_callback", $notice_attachement_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "user_education_doc_file")
		{
			$user_education_doc_file = DOC_TEMP_UPLOAD_DIR."userid-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$user_education_doc_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "user_education_doc_file_callback", $user_education_doc_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "employee_id_card_file")
		{
			$employee_id_card_file = DOC_TEMP_UPLOAD_DIR."userid-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$employee_id_card_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "employee_id_card_file_callback", $employee_id_card_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "user_cont_file")
		{
			$user_cont_file = DOC_TEMP_UPLOAD_DIR."user-contract-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$user_cont_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "user_cont_file_path_callback", $user_cont_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "user_char_exp_file")
		{
			$user_char_exp_file = DOC_TEMP_UPLOAD_DIR."user-charexp-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$user_char_exp_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "user_char_exp_file_path_callback", $user_char_exp_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "user_driving_licence_file")
		{
			$user_driving_licence_file = DOC_TEMP_UPLOAD_DIR."user-dl-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$user_driving_licence_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "user_driving_licence_file_path_callback", $user_driving_licence_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "user_labour_card_file")
		{
			$user_labour_card_file = DOC_TEMP_UPLOAD_DIR."user-lc-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$user_labour_card_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "user_labour_card_file_path_callback", $user_labour_card_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "user_passport_file")
		{
			$user_passport_file = DOC_TEMP_UPLOAD_DIR."user-pp-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$user_passport_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "user_passport_file_path_callback", $user_passport_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "user_experience_certificate_file")
		{
			$user_exp_file = DOC_TEMP_UPLOAD_DIR."user-exp-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$user_exp_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "user_exp_file_path_callback", $user_exp_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
		}
		elseif($field_handler == "process_sr_no_file")
		{
			$wc_id 		= $_POST['wc_id'];
			$item_id 	= $_POST['item_id'];
			$collectionItems = CollectionProcess::getAllProcessProductWithSrno($wc_id, $item_id);
			if($collectionItems)
			{
				$type = PHPExcel_IOFactory::identify($file_tmp_name);
				$objReader = PHPExcel_IOFactory::createReader($type);
				$objPHPExcel = $objReader->load($file_tmp_name);
				$file_tmp_extension = pathinfo($file_name, PATHINFO_EXTENSION);	
				
				if(($file_tmp_extension =="xlsx")) 
				{
					$fileDataArray = array();
					$columnArray = array();
					foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
					{
						$xlsxData = $worksheet->toArray();
						if(count($xlsxData))
						{
							$row = 0;
							foreach($xlsxData as $data)
							{
								$row++;
								if($row === 1){
									$columnArray = $data;
								}
								elseif( count($columnArray) == count($data) )
								{
									$fileDataArray[] = array_combine($columnArray, $data);
								}
							}
						}
					}
					if(count($fileDataArray)){
						$fileDataCounter = 0;
						$appliedProcessCodeArray = array();
						foreach($collectionItems as $_item){
							if($_item['wc_process_item_sr_no'] == ""){
								$cp = new CollectionProcess($_item['wc_process_asset_code']);
								$updateArray = array("wc_process_item_sr_no" => $fileDataArray[$fileDataCounter]['Serial']);
								if(isset($fileDataArray[$fileDataCounter]['Make']))
									$updateArray['wc_process_item_make'] = $fileDataArray[$fileDataCounter]['Make'];
								if(isset($fileDataArray[$fileDataCounter]['Model']))
									$updateArray['wc_process_item_model'] = $fileDataArray[$fileDataCounter]['Model'];
								if(isset($fileDataArray[$fileDataCounter]['Name']))
									$updateArray['wc_process_item_name'] = $fileDataArray[$fileDataCounter]['Name'];
								
								$cp->update($updateArray);
								$appliedProcessCodeArray[] = $_item['wc_process_asset_code'];
								$fileDataCounter++;
							}
						}
						if(count($appliedProcessCodeArray))
							echo json_encode(array(200, "success|Top {$fileDataCounter} serial number applied on ".implode(" , ", $appliedProcessCodeArray)));
						else
							echo json_encode(array(200, "success|File Checked successfull but No Serial number applied."));
					}
					else
						echo json_encode(array(300, "warning|No record found on uploaded file."));
				}
				else
					echo json_encode(array(300, "warning|Unsupported file format."));
			}
			else
				echo json_encode(array(300, "warning|Collection items not found."));
		}
		elseif($field_handler == "customer_trade_licence_file")
		{
			$customer_trade_licence_file = DOC_TEMP_UPLOAD_DIR."customer-trade-licence-".time().".$file_tmp_extension";
			if(move_file($file_tmp_name, BP.$customer_trade_licence_file))
			{
				echo json_encode(array(200, "success|file Uploaded successfully", SUCCESSFULL, "customer_trade_licence_file_path_callback", $customer_trade_licence_file));
			}
			else
				echo json_encode(array(300, "warning|File Couldn't Uploaded. Please try again"));
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

function crop($image, $crop_image, $crop_size=200, $x=NULL, $y=NULL)
{
	$original_image = $image;
	$new_image = $crop_image;
	$image_quality = '99';
	
	// Get dimensions of the original image
	list( $current_width, $current_height) = getimagesize( $original_image );
	
	// Get coordinates x and y on the original image from where we
	// will start cropping the image, the data is taken from the hidden fields of form.
	$image_name = pathinfo($original_image);
	$image_extension = strtolower($image_name['extension']);
	
	$height = $width = min($current_width, $current_height);
	if($width < $crop_size){$height = $width = $crop_size;  }  
	
	$crop_width = $crop_height = $crop_size;
	if($x!=NULL && $y!=NULL)
	{
		$x1 = $x;
		$y1 = $y;
	}
	else
	{
		$x1 = round(($current_width - $width)/2);
		$y1 = round(($current_height - $height)/2);
	}
	
	$new = imagecreatetruecolor( $crop_width, $crop_height );
	if($image_extension == "png")
		$current_image = imagecreatefrompng( $original_image );
	if($image_extension == "jpg" || $image_extension == "jpeg")
		$current_image = imagecreatefromjpeg( $original_image );
		
	 imagealphablending($new, false);
	 imagesavealpha($new,true);
	 $transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
	 imagefilledrectangle($new, 0, 0, $crop_width, $crop_height, $transparent);
 
	imagecopyresampled( $new, $current_image, 0, 0, $x1, $y1, $crop_width, $crop_height, $width, $height );
	
	
	if($image_extension == "png")
		imagepng($new, $new_image, $image_quality/10 );
	if($image_extension == "jpg")
		imagejpeg( $new, $new_image, $image_quality );
		
}
?>