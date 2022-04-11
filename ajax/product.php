<?php
function proceedtobatchimport()
{	
    global $app;
    $key  = "";
    $data           = sanitizePostData($_POST);
	$databaseColumNameArray = array(
		'Sku' => 'product_sku',
		'Serial' => 'product_serial_number'
	);
    extract($data);
	if($key != "" && strlen($key) == 32){
		if(isset($_SESSION['BATCH-PROD'][$key]))
		{
			$count = count($_SESSION['BATCH-PROD'][$key]);
			if(isset($_SESSION['BATCH-PROD'][$key]) && $count){
				
				$batchProduct = new BatchProduct();
				$_SESSION['BATCH-PROD'][$key]  = sanitizePostData($_SESSION['BATCH-PROD'][$key]);
				foreach($_SESSION['BATCH-PROD'][$key] as $product){
					$product['product_code'] = $batchProduct->getBatchProductCode();
					$product['product_created_by'] = getLoginId();
					$batchProduct->insert($product);
				}
				unset($_SESSION['BATCH-PROD'][$key]);
				Activity::add("Imported $count batch product successfully");
				echo json_encode(array("200", "success|$count Product imported successfully"));
			}
			else
				echo json_encode(array("300",  "danger|Products not available."));
		}
		elseif(isset($_SESSION['BATCH-PROD-UPDATE'][$key]))
		{
			$count = count($_SESSION['BATCH-PROD-UPDATE'][$key]);
			if(isset($_SESSION['BATCH-PROD-UPDATE'][$key]) && $count){
				
				$_SESSION['BATCH-PROD_UPDATE'][$key]  = sanitizePostData($_SESSION['BATCH-PROD-UPDATE'][$key]);
				//$data = $_SESSION['BATCH-PROD-UPDATE'][$key];
				foreach($_SESSION['BATCH-PROD-UPDATE'][$key] as $product){
					if(isset($product[$_SESSION['BATCH-PROD-UPDATE']['COLUMN']]))
					{					
						$batchProduct = new BatchProduct();
						$batchProduct->loadByColumn($_SESSION['BATCH-PROD-UPDATE']['COLUMN'], $product[$_SESSION['BATCH-PROD-UPDATE']['COLUMN']]);
						$batchProduct->update($product);
					}
				}
				unset($_SESSION['BATCH-PROD'][$key]);
				Activity::add("Imported $count batch product successfully");
				echo json_encode(array("200", "success|$count Product updated successfully"));
			}
			else
				echo json_encode(array("300",  "danger|Products not available."));
		}
	}
	else
		echo json_encode(array("300",  "danger|Security key missmatch."));
}

function getbatchproductdetail(){
	global $app;
    $product_id  = "";
    $data           = sanitizePostData($_POST);
    extract($data);
	if($product_id != ""){
		$batchProduct = new BatchProduct($product_id);
		if($batchProduct->isExist()){		
			
			echo json_encode(array("200", "success|Product loaded successfully", $batchProduct->getDetails()));
		}
		else
			echo json_encode(array("300",  "danger|Products not available."));
	}
	else
		echo json_encode(array("300",  "danger|Security key missmatch."));
}

function savebatchproductrecord(){
	global $app;
    $product_id  = 0;
    $data           = sanitizePostData($_POST);
	$product_status = $product_in_stock = $product_is_on_way = 0;
	$product_verified = 'NULL';
	$create_product_copy = $product_under_technician = $product_under_technician_id = 0;
	$no_of_copy = 0;
	$product_age_date = '';
	$product_price = 0;
    extract($data);
	$product_verified = $product_verified ? 'NOW()' : 'NULL';
	$prodValues = compact('product_order_number', 'product_reg_id', 'product_type', 'product_name', 'product_serial_number', 'product_sku', 'product_model', 'product_condition', 'product_processor', 'product_processor_speed', 'product_screen_size', 'product_ram', 'product_ssd', 'product_hdd', 'product_fusion_drive', 'product_release', 'product_reason', 'product_battery_cycle', 'product_operating_system', 'product_grade', 'product_batch_type', 'product_batch_code', 'product_in_stock', 'product_under_technician', 'product_under_technician_id', 'product_age_date', 'product_price', 'product_status', 'product_verified', 'product_part_number', 'product_store_location', 'product_is_on_way');
	$batchProduct = new BatchProduct($product_id);
	if(($product_serial_number != "" && $product_serial_number != "N/A" ) && $batchProduct->isSerialNumberExist($product_serial_number)){
		echo json_encode(array("300", "warning|Serial number \"$product_serial_number\" already exist."));	
		die;
	}
	if($product_id == 0){
		$prodValues['product_code'] = $batchProduct->getBatchProductCode();
		$prodValues['product_created_date'] = date('Y-m-d H:i:s');
		$prodValues['product_created_by'] = getLoginId();
		
		$product_id = $batchProduct->insert($prodValues);
		$detailData = $batchProduct->getDetails();
		Activity::add("Added Batch Product|^|{$prodValues['product_code']}", "B", $product_id);
		//Activity::add("Added  Batch product <b>$detailData[product_code]</b> successfully");
	}
	else{
		$detailData = $batchProduct->getDetails();
		$batchProduct->update($prodValues);
		Activity::add("Updated Batch Product|^|{$detailData['product_code']}", "B", $product_id);
		//Activity::add("Updated  Batch product <b>$detailData[product_code]</b> successfully");
	}
	$copy_message = "";
	$copy_product_code = array();
	if($create_product_copy == 1 && $no_of_copy > 0){
		
		for($i=0; $i < $no_of_copy ; $i++){
			$newBp = new BatchProduct();
			$prodValues['product_code'] = $newBp->getBatchProductCode();
			$copy_product_code[] = $prodValues['product_code'];
			$prodValues['product_created_date'] = date('Y-m-d H:i:s');
			$newBp->insert($prodValues);
			$newBp->update(array('product_serial_number'=>''));
		}
		$copy_message = "<br/>$no_of_copy Copy of this Product Generated <br/>".implode(", ", $copy_product_code);
	}
	
	echo json_encode(array("200", "success|Product details saved successfully $copy_message", $product_id, $copy_message==""?0:1));	
}

function printbatchproductbarcode(){
	global $app;
	$data = sanitizePostData($_POST);
	if(isset($data['filter']) && !empty($data['filter'])){
		$condition = "WHERE 1";
		foreach($data['filter'] as $filed=>$values){
				$filedCondArray = array();
				if(is_array($values)){
					$condition .= " AND ( ";
					$conditionSubArray = array();
					foreach($values as $_val){
						$conditionSubArray[] = " `$filed` = '".sanitizePostData($_val)."'";
					}
					$condition .= implode(" OR ", $conditionSubArray);
					$condition .= " ) ";
				}
				else
					$condition .= " AND `$filed` = '".sanitizePostData($values)."'";
			}
		$batchProduct = new BatchProduct();
		if(isset($_SESSION['BATCH-PROD']['PRINT']))
			unset($_SESSION['BATCH-PROD']['PRINT']);
		if($product_codes = $batchProduct->getPrintProductCode($condition)){
			$time = time();
			Activity::add("Printed Batch Product barcodes");
			$_SESSION['BATCH-PROD']['PRINT'][md5($time)] = $product_codes;
			echo json_encode(array("200", "success|Product label print intilized", md5($time)));
		}
		else
			echo json_encode(array("300",  "danger|Products not available to print label."));
	}
	else
	echo json_encode(array("300",  "danger|Please apply filter."));
}

function batchproductexportexcel(){
	global $app;
	$columns = array();
	$data = sanitizePostData($_POST);
	if(isset($data['filter']) && !empty($data['filter'])){
		$condition = "WHERE 1";
		foreach($data['filter'] as $filed=>$values){
				$filedCondArray = array();
				if(is_array($values)){
					$condition .= " AND ( ";
					$conditionSubArray = array();
					foreach($values as $_val){
						$conditionSubArray[] = " `$filed` = '".sanitizePostData($_val)."'";
					}
					$condition .= implode(" OR ", $conditionSubArray);
					$condition .= " ) ";
				}
				else
					$condition .= " AND `$filed` = '".sanitizePostData($values)."'";
			}
		$batchProduct = new BatchProduct();
		if(isset($_SESSION['BATCH-PROD']['EXPORT']))
			unset($_SESSION['BATCH-PROD']['EXPORT']);
		if($sql = $batchProduct->getExportProductSql($condition, $data['columns'])){
			$time = time();
			Activity::add("Exported Batch Products list");
			$_SESSION['BATCH-PROD']['EXPORT'][md5($time)]['sql'] = $sql;
			$_SESSION['BATCH-PROD']['EXPORT'][md5($time)]['col'] = $data['columns'];
			echo json_encode(array("200", "success|Product excel export intilized", md5($time), $_SESSION['BATCH-PROD']['EXPORT'][md5($time)]));
		}
		else
			echo json_encode(array("300",  "danger|Products not available to export."));
	}
	else
	echo json_encode(array("300",  "danger|Please apply filter."));
}

function getbatchproducthistorydetail(){
	$bpca_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($bpca_id){
		$batchProductSaleHistory = new BatchProductSaleHistory($bpca_id);
		echo json_encode(array("200", "success|Product sale record detail loaded", $batchProductSaleHistory->getDetails()));
	}
	else{
		echo json_encode(array("300",  "danger|Products saled record not found."));
	}
}

function removeproductsalehistory(){
	$bpca_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($bpca_id){
		$batchProductSaleHistory = new BatchProductSaleHistory($bpca_id);
		$record = $batchProductSaleHistory->getDetails();
		$batchProductSaleHistory->remove();		
		Activity::add("Removed Batch Product Sales Record", "B", $record['bpca_product_id']);
		echo json_encode(array("200", "success|Product sale record detail deleted"));
	}
	else{
		echo json_encode(array("300",  "danger|Products saled record not found."));
	}
}

function saveproductsalehistory(){
	
    global $app;
    $customer_fname              = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location =  "";
    $customer_type_id            = $bpca_sell_price = $bpca_product_id = $bpca_customer_id = $bpca_is_returned =  0;
	$bpca_sell_date = $bpca_returned_date = NULL;
    $bpca_status = 1;
    $customer_password           = gePassword();
    $customer_is_mobile_verified = 0;
    $customer_is_email_verified  = 0;
    $customer_status = $customer_address_status     = 1;
	$bpca_id = 0;
	$customer_created_by = getLoginId();
    $data                        = sanitizePostData($_POST);
	//SELECT `bpca_id`, `bpca_product_id`, `bpca_customer_id`, `bpca_customer_address_id`, `bpca_sell_price`, `bpca_sell_date`, `bpca_is_returned`, `bpca_returned_date`, `bpca_store_id`, `bpca_store_reference`, `bpca_remark`, `bpca_status` FROM `app_batch_product_customer_allocation` WHERE 1
    extract($data);
	$bpca_sell_date = date("Y-m-d H:i:s", strtotime($bpca_sell_date));
    $customer_email = strtolower($customer_email);
    $bpca_created_by = $_SESSION['user_id'];
    $is_new_customer       = false;
    $Customer              = new Customer(0);
    $bpca_customer_id = $Customer->isEmailExists($customer_email);
    if (!$bpca_customer_id) {
        $customer_image        = DEFAULT_USER_IMAGE;
        $customer_code         = $Customer->getNewCode($customer_email, $customer_fname . $customer_lname);
        $bpca_customer_id = $Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
        $is_new_customer       = true;
    } else {
        $Customer = new Customer($bpca_customer_id);
        $Customer->update(array(
			"customer_fname" => $customer_fname,
            "customer_lname" => $customer_lname,
            "customer_phone" => $customer_phone,
            "customer_type_id" => $customer_type_id,
			"customer_company" => $customer_company
        ));
    }
    if (!$bpca_customer_id) {
        echo json_encode(array("300", "Warning|Customer Details Not Full filled. Try again"
        ));
        exit();
    }
    $CustomerAddress     = new CustomerAddress(0);
    $bpca_customer_address_id = $CustomerAddress->isCustomerAddressExists($bpca_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);
    if (!$bpca_customer_address_id)
        $bpca_customer_address_id = $CustomerAddress->add($bpca_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country, $customer_address_postcode, $customer_address_geo_location, $customer_address_status);
	
	
	if ($is_new_customer) {
        new SMS($customer_phone, "Hi $customer_fname, Welcome to " . $app->siteName . ". Your account had been created with us successfully. Your username is \"$customer_email\" and Password is $customer_password");
        #==============================trig Email============= 
        $activation_link = $app->basePath("activation.php?r=cst&u=$customer_email&l=" . md5($customer_email . $bpca_customer_id) . "&i=" . md5($bpca_customer_id . $customer_email));
        $dataArray       = array(
            "customer_name" => $customer_fname,
            "customer_email" => $customer_email,
            "customer_password" => $customer_password,
            "login_page" => $app->basePath("customer-login.php"),
            "activation_link" => $activation_link
        );
        $email           = new Email("New Customer Account on " . $app->siteName);
        $email->to("$customer_email", $customer_fname)->template('customer_registration', $dataArray)->send();
    }
    
	
		
    $BatchProductSaleHistory = new BatchProductSaleHistory($bpca_id);
	$bpcaData = compact('bpca_product_id', 'bpca_customer_id', 'bpca_customer_address_id', 'bpca_sell_price', 'bpca_sell_date', 'bpca_is_returned', 'bpca_returned_date', 'bpca_store_id', 'bpca_store_reference', 'bpca_remark', 'bpca_created_by', 'bpca_status');
	if($bpca_id == 0)
    $bpca_id     = $BatchProductSaleHistory->insert($bpcaData);
	else
    $BatchProductSaleHistory->update($bpcaData);
	Activity::add("Saved Batch Product Sales Record", "B", $bpca_product_id);
	//Activity::add("Saved Batch Product Sales Record");
	echo json_encode(array("200",  "success|New Product Selling Record saved Successfully", $BatchProductSaleHistory->getRecords($bpca_product_id)));
}

function removebatchproductmedia(){
	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$bpm = new BatchProductMedia($record_id);
		$record = $bpm->getDetails();
		$bpm->remove();
		unlinkFile($record['image_path']);
		echo json_encode(array("200",  "success|Batch product image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No Batch product image found."));	
}

function deletebatchproduct(){
	$product_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($product_id){
		$bp = new BatchProduct($product_id);
		if($bp->isExist())
		{
			$bp->remove();
			echo json_encode(array("200",  "success|Batch product removed"));
		}
		else
			echo json_encode(array("300",  "warning|No Batch product found."));
	}
	else
		echo json_encode(array("300",  "warning|Invalid Batch product request."));
}

function searchproductbysku(){
	$product_sku = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($product_sku)
	{
		$products = WebsiteOrderProduct::checkProductAvailability($product_sku, true);
		if($products)
		{
			echo json_encode(array("200",  "success|Products loaded", $products));
		}
		else
			echo json_encode(array("300",  "warning|No product found."));
	}
	else
		echo json_encode(array("300",  "warning|Invalid product SKU."));
	
}

function getlotitems(){
	$lot_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($lot_id){
		Modal::load(array('Lot'));
		$lot = new Lot($lot_id);
		$lot_products = $lot->getProducts();
		if(isset($_SESSION['LOT']['PRODUCT']) && count($_SESSION['LOT']['PRODUCT']))
			$lot_products = array_merge($lot_products, $_SESSION['LOT']['PRODUCT']);
	}else{
		$lot_products = $_SESSION['LOT']['PRODUCT'];
	}
	$products = Product::getLotProducts($lot_products);
	if($products)
	{
		echo json_encode(array("200",  "success|Products loaded", $products));
	}
	else
		echo json_encode(array("300",  "warning|No product found.", $_SESSION));
	
}

function savelot(){
	$lot_id = 0;
	$products = array();
	$data  = sanitizePostData($_POST);
	extract($data);	
	if(!count($products))
	{
		echo json_encode(array("300",  "warning|No product found."));
		die;
	}
	Modal::load(array('Lot'));
	$lot = new Lot($lot_id);
	$lotdata['lot_name'] = $lot_name;
	$lotdata['lot_update_date'] = 'NOW()';
	if($lot_id == 0){
		$lotdata['lot_code'] = $lot->getLotCode();
		$lotdata['lot_created_date'] = 'NOW()';
		$lotdata['lot_created_by'] = getLoginId();
		$lotdata['lot_status'] = 1;
		$lot_id = $lot->insert($lotdata);
	}
	else
		$lot->update($lotdata);
	$allExistingItes = $lot->getExistingItemsIds();
	$existingProducts = array();
	foreach($products as $_product){
		$productData = explode("|", $_product);
		$isExist = $lot->isProductInLot($productData[0],$productData[1], $lot_id);
		if(!$isExist){
			$lotProduct = new LotProduct();
			$existingProducts[] = $lotProduct->insert(array(
				"lot_item_lot_id" 		=> $lot_id, 
				"lot_item_code" 		=> $productData[0], 
				"lot_item_product_id" 	=> $productData[1]
			));
		}
		else
			$existingProducts[] = $isExist;
	}
	$delItems = array_diff($allExistingItes, $existingProducts);
	if(count($delItems))
	$lot->unlinkItems($delItems);
	if(isset($_SESSION['LOT']))
	unset($_SESSION['LOT']);
	echo json_encode(array("200",  "success|Lot Saved", $lot_id));
}

function loadthislottocurrent(){
	$lot_id = 0;
	$data  = sanitizePostData($_POST);	
	extract($data);	
	Modal::load(array('Lot'));	
	$lot = new Lot($lot_id);
	if($lot->isExist()){
		$_SESSION['LOT']['PRODUCT'] = $lot->getProducts();
		$_SESSION['LOT']['ID'] = $lot_id;
		echo json_encode(array("200",  "success|Lot loaded to add products", count($_SESSION['LOT']['PRODUCT']), $lot_id));
	}
	else{
		echo json_encode(array("300",  "warning|No Lot found."));
	}
}

function verifybatchproduct(){
	$product_id = 0;
	$verify = '';
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($product_id){
		$bp = new BatchProduct($product_id);
		if(in_array($verify, array(0,1)) && $bp->isExist())
		{
			$bp->update(array('product_verified'=> $verify ? 'NOW()':'NULL'));
			echo json_encode(array("200",  "success|Batch product ".($verify ? 'Verified':'Unverified')));
		}
		else
			echo json_encode(array("300",  "warning|No Batch product found."));
	}
	else
		echo json_encode(array("300",  "warning|Invalid Batch product request."));
}
?>