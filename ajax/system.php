<?php
function iscodeavailable() //Hardware code 
{
    $hardware_code = checkData($_POST['hardware_code']);
    $hardwaretype  = new HardwareType(0);
    if ($hardwaretype->IsCodeAvailable($hardware_code))
        echo json_encode(array(
            200,
            "success|\"$hardware_code\" Hardware code is available to use"
        ));
    else
        echo json_encode(array(
            300,
            "danger|\"$hardware_code\" Hardware code is allready used..."
        ));
}


function assignproductandsavesalehistory(){
	global $app;
	$data_code = $data_id = $data_sku = $data_item_id = $data_product_id = $data_type_code = $data_product_code = $sell_price = $store_reference = $sell_date = $remark = "";
	$data  = sanitizePostData($_POST);
	extract($data);	
	
	$weborder = new WebsiteOrder($data_id);
	if($weborder->isExist()){
		$weborderData = $weborder->getDetails();
		$customer_id = $weborderData['web_order_customer_id'];
		$address_id = $weborderData['web_order_address_id'];
		if($data_type_code == "B"){
			$batchProduct = new BatchProduct($data_product_id);
			$productData = $batchProduct->getDetails();
			if($productData['product_in_stock'] == 1)
			{
				$bpca_product_id = $productData['product_id'];
				$bpca_customer_id = $customer_id;
				$bpca_customer_address_id = $address_id;
				$bpca_sell_price = $sell_price;
				$bpca_sell_date = $sell_date;
				$bpca_is_returned = 0;
				$bpca_store_id = $weborderData['web_order_website_id'];
				$bpca_store_reference = $store_reference;
				$bpca_remark = $remark;
				$bpca_created_by = getLoginId();
				$bpca_status = 1;
				
				$wo_process_code = $productData['product_code'];
				$wo_product_srno = $productData['product_serial_number'];
				
				$BatchProductSaleHistory = new BatchProductSaleHistory(0);
				$bpcaData = compact('bpca_product_id', 'bpca_customer_id', 'bpca_customer_address_id', 'bpca_sell_price', 'bpca_sell_date', 'bpca_is_returned', 'bpca_store_id', 'bpca_store_reference', 'bpca_remark', 'bpca_created_by', 'bpca_status');
				
				$bpca_id     = $BatchProductSaleHistory->insert($bpcaData);
				$batchProduct->update(array('product_in_stock' => 0));
				Activity::add("Saved Batch Product Sales Record|^|$productData[product_code]", "B", $bpca_product_id);
			}
			else{
				echo json_encode(array("300", "warning|Product is out of stock"));
				die;
			}
		}
		elseif($data_type_code == "P"){
			$colProcess = new CollectionProcess($data_product_id);
			$productData = $colProcess->getDetails();
			
			if($productData['wc_process_item_stock'] == 1)
			{
				$wpca_process_code = $productData['product_id'];
				$wpca_customer_id = $customer_id;
				$wpca_customer_address_id = $address_id;
				$wpca_sell_price = $sell_price;
				$wpca_sell_date = $sell_date;
				$wpca_is_returned = 0;
				$wpca_store_id = $weborderData['web_order_website_id'];
				$wpca_store_reference = $store_reference;
				$wpca_remark = $remark;
				$wpca_created_by = getLoginId();
				$wpca_status = 1;
				
				$wo_process_code = $productData['wc_process_asset_code'];
				$wo_product_srno = $productData['wc_process_item_sr_no'];
				
				$ProcessProductSaleHistory = new ProcessProductSaleHistory(0);
				$bpcaData = compact('wpca_process_code', 'wpca_customer_id', 'wpca_customer_address_id', 'wpca_sell_price', 'wpca_sell_date', 'wpca_is_returned', 'wpca_store_id', 'wpca_store_reference', 'wpca_remark', 'wpca_created_by', 'wpca_status');
				
				$wpca_id     = $ProcessProductSaleHistory->insert($bpcaData);
				$colProcess->update(array('wc_process_item_stock' => 0));
				Activity::add("Saved Process Product Sales Record|^|$wpca_process_code", "P", $wpca_process_code);
			}
			else{
				echo json_encode(array("300", "warning|Product is out of stock"));
				die;
			}
		}
		$websiteOrderProduct = new WebsiteOrderProduct($data_item_id);
		$websiteOrderProduct->update(array("wo_process_code"=>$wo_process_code, "wo_product_srno"=>$wo_product_srno));
		echo json_encode(array("200", "success|Product assigned and set to out of stock"));
	}
	
}

function fulfillcheckforopener(){
	global $app;
	$data_code = $data_id = $data_sku = $data_item_id = "";
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($data_sku != ""){
		$fulFillOptions = WebsiteOrderProduct::checkProductAvailability($data_sku);
		if($fulFillOptions){
			echo json_encode(array("200", "success|Fulfill options loaded", "data"=>$fulFillOptions));
		}
		else
			echo json_encode(array("300", "warning|Fulfill option not available"));
	}
	else
		echo json_encode(array("300", "warning|Product SKU is not valid"));
}

function getmytag(){
	global $app;	
	$user_tag_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$userTag = new UserTag();
	$records = $user_tag_id  ? $userTag->getUserNewTag($user_tag_id) : $userTag->getUserTag();
	echo json_encode(array("200", "success|Tag record fetched", "data"=>$records));
}

function getmypaymentreminder(){
	global $app;	
	$data  = sanitizePostData($_POST);
	extract($data);		
	Modal::load(array('PaymentReminder'));
	$paymentReminder = new PaymentReminder();
	$records = $paymentReminder->getMyPaymentReminder();
	echo json_encode(array("200", "success|Payment reminder record fetched", "data"=>$records));
}

function marktagreaded(){
	global $app;	
	$user_tag_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$userTag = new UserTag($user_tag_id);
	$userTag->update(array("tag_read_time"=>"NOW()"));
	$userTag->Activate();
	echo json_encode(array("200", "success|Mark readed"));
}

function saveentitiesvalue(){
	$entities_key = $entities_values = "";
	$entities_js_allowed = $entities_php_allowed = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	Entities::saveKeyValues($entities_key, $entities_values, $entities_js_allowed, $entities_php_allowed);
	unlinkCacheFile(Entities::getEntitiesConstantsFilename());
	echo json_encode(array("200", "success|Entity values saved"));
}

function applysystemtheme(){
	global $app;
	$theme = 'default';
	$data  = sanitizePostData($_POST);
	extract($data);	
	$user = new Employee($_SESSION['user_id']);
	$oldTheme = $_SESSION['app_theme'];
	$_SESSION['app_theme'] = $theme;
	$css = '<link rel="stylesheet" data-theme="'.$theme.'" href="'.$app->cssPath('theme_'.$theme).'" type="text/css" />';
	$user->update(array('user_theme'=> $theme));
	echo json_encode(array("200", "success|Theme Applied successfully", $css, $app->cssPath('theme_'.$oldTheme)));
}

function saveuserfilterstate(){
	global $app;
	$user_filter_state = 1;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$user_filter_state = 1 - $user_filter_state;
	$user = new Employee($_SESSION['user_id']);
	$_SESSION['app_filter_state'] = $user_filter_state;	
	$user->update(array('user_filter_state'=> $user_filter_state));
	echo json_encode(array("200", "success|Filter State Applied successfully"));
}

function addhardware()
{
	
    global $app;
    $hardware_name  = $hardware_code = $html = "";
    $hardware_status = 1;
	$getExtraOption = false;
    $data           = sanitizePostData($_POST);
    extract($data);
	$HardwareType = new HardwareType(0);
    if ($hardware_name != "" && $hardware_code != "" && $HardwareType->IsCodeAvailable($hardware_code) && $HardwareType->IsNameAvailable($hardware_name)) 
	{
		$hardware_id     = $HardwareType->add($hardware_name, $hardware_code, $hardware_status);
		if ($hardware_id) {
			if (isset($getExtraOption) == true)
				$html = $HardwareType->getOptions();
			echo json_encode(array("200",  "success|New Hardware Type Addedd Successfully", $hardware_id, $html));
			$email = new Email($app->siteName . " : " . "New Hardware Type Added");
			$email->send("New Hardware Type \"$hardware_name\" added on " . $app->siteName);
		} else
			echo json_encode(array("300", "warning|Hardware could not addedd. Please try again."
			));
    } else
        echo json_encode(array("300", "warning|Hardware name found invalid."
        ));

}

function getmentionuser(){
	global $app;
    $data           = sanitizePostData($_POST);
    extract($data);
	$employee = new Employee();
	echo json_encode(array("200",  "success|User mention listed", "data"=>$employee->getSearchedResult()));
}

function addhardwareproblem()
{
    global $app;
    $problem_name   = "";
    $problem_status = 1;
    $problem_id     = 0;
    $popup          = false;
    $autocheck      = array();
    $html           = "";
    $data           = sanitizePostData($_POST);
    extract($data);
    if ($problem_name != "") {
        if ($problem_id == 0) {
            $HardwareProblem = new HardwareProblem(0);
            $hrd_prob_id     = $HardwareProblem->add($problem_name, $problem_status);
            if ($hrd_prob_id) {
                if (isset($popup) == true)
                    $html = $HardwareProblem->getCheckbox("app_not_working_problem_mark", $autocheck);
                echo json_encode(array("200",  "success|New Hardware Problem Type Addedd Successfully",
                    $hrd_prob_id,
                    $html
                ));
                $email = new Email($app->siteName . " : " . "New Hardware Problem Added");
                $email->send("New Hardware Problem \"$problem_name\" added on " . $app->siteName);
            } else
                echo json_encode(array("300", "warning|Hardware Problem could not addedd. Please try again."
                ));
        } else {
            $HardwareProblem = new HardwareProblem($problem_id);
            $updateSuccess   = $HardwareProblem->update(array("problem_name" => $problem_name,
                "problem_status" => $problem_status
            ));
            if ($updateSuccess) {
                Activity::add("added New Hardware Problem <b>$problem_name</b>");
                echo json_encode(array("200",  "success|New Hardware Problem Type Updated Successfully",
                    $hrd_prob_id,
                    $html
                ));
                $email = new Email($app->siteName . " : " . "New Hardware Problem Updated");
                $email->send("New Hardware Problem \"$problem_name\" updated on " . $app->siteName);
            } else
                echo json_encode(array("300", "warning|Hardware Problem could not updated. Please try again."
                ));
        }
    } else
        echo json_encode(array("300", "warning|Hardware Problem name found invalid."
        ));
}
function updatehardwareproblemstatus()
{
    global $app;
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $HardwareProblem = new HardwareProblem($idvalue);
        $status ? $HardwareProblem->Activate() : $HardwareProblem->Deactivate();
        Activity::add(status($status) . " Hardware Problem <b>" . $HardwareProblem->get('problem_name') . "</b> staus");
        echo json_encode(array("200",  "success|Hardware Problem " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Hardware Problem " . status($status));
        $email->send("Hardware Problem <b>\"" . $HardwareProblem->get('problem_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Hardware Problem Status not found."
        ));
}
function updatehardwaretypestatus()
{
    global $app;
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $HardwareType = new HardwareType($idvalue);
        $status ? $HardwareType->Activate() : $HardwareType->Deactivate();
        Activity::add(status($status) . " Hardware type <b>" . $HardwareType->get('hardware_name') . "</b> staus");
        echo json_encode(array("200",  "success|Hardware Type " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Hardware Type " . status($status));
        $email->send("Hardware Type <b>\"" . $HardwareType->get('hardware_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Hardware Type Status not found."
        ));
}

function getdropdown()
{
    $condition = "";
    $data      = sanitizePostData($_POST);
    if (isset($data['condition']))
        $condition = $data['condition'];
    if (isset($data['dropdown'])) {
        $val = explode("<=>", $data['dropdown']);
        if (count($val) == 2) {
            $className = $val[0];
            $fieldName = $val[1];
            $keyword   = $data['keyword'];
            $extra     = isset($data['extra']) ? $data['extra'] : 0;
			if(!class_exists($className)){
				Modal::load(array($className));
			}
            if (class_exists($className) && trim($keyword) != "") {
                $extraData = array();				
                $d         = new $className(0);
                $options   = $d->getDropdownList($fieldName, trim($keyword), $extra, $condition);
                echo json_encode(array("200",
                    $options
                ));
            } else
                echo json_encode(array("300", "warning|No Option Available"
                ));
        } else
            echo json_encode(array("300", "warning|Invalid Request Data"
            ));
    } else
        echo json_encode(array("300", "warning|Invalid Method Calling"
        ));
}

function addappinfo()
{
    global $app;
    $info_app_bank_details = $info_app_address = $info_app_contact = $info_app_invoice_acknowledge = $info_app_invoice_happytext = $info_app_invoice_terms = $info_app_disclaimer = $wc_carrier_certificate_text = $wc_consigner_certificate_text = $wc_carrier_licence_number = $wc_hazardous_waste_licence_number = $wc_environment_permit = $wc_vehicle_registration = $wc_declaration_text = $wc_collection_declaration_text = $wc_authority_name = "";
    $data                  = sanitizePostData($_POST);
    extract($data);
    $appinfo     = new AppInfo();
    $info_app_id = $appinfo->add($info_app_bank_details, $info_app_address, $info_app_contact, $info_app_invoice_acknowledge, $info_app_invoice_happytext, $info_app_invoice_terms, $info_app_disclaimer, $wc_carrier_certificate_text, $wc_consigner_certificate_text, $wc_carrier_licence_number, $wc_hazardous_waste_licence_number, $wc_environment_permit, $wc_vehicle_registration, $wc_authority_name, $wc_declaration_text, $wc_collection_declaration_text);
    Activity::add("added Application Information");
    echo json_encode(array("200",  "success|Application Information added Successfully",
        $info_app_id
    ));
    $email = new Email($app->siteName . " : " . "Application Information Added");
    $email->send("You have successfully added application Detail information on " . $app->siteName);
}
function updateappinfo()
{
    global $app;
    $info_app_bank_details = $info_app_address = $info_app_contact = $info_app_invoice_acknowledge = $info_app_invoice_happytext = $info_app_invoice_terms = $info_app_disclaimer = "";
    $data                  = sanitizePostData($_POST);
    extract($data);
    $appinfo = new AppInfo($info_app_id);
    $appinfo->update(array("info_app_bank_details" => $info_app_bank_details,
        "info_app_address" => $info_app_address,
        "info_app_contact" => $info_app_contact,
        "info_app_invoice_acknowledge" => $info_app_invoice_acknowledge,
        "info_app_invoice_happytext" => $info_app_invoice_happytext,
        "info_app_invoice_terms" => $info_app_invoice_terms,
        "info_app_disclaimer" => $info_app_disclaimer,
        "wc_carrier_certificate_text" => $wc_carrier_certificate_text,
        "wc_consigner_certificate_text" => $wc_consigner_certificate_text,
        "wc_carrier_licence_number" => $wc_carrier_licence_number,
        "wc_hazardous_waste_licence_number" => $wc_hazardous_waste_licence_number,
        "wc_environment_permit" => $wc_environment_permit,
        "wc_vehicle_registration" => $wc_vehicle_registration,
        "wc_declaration_text" => $wc_declaration_text,
        "wc_collection_declaration_text" => $wc_collection_declaration_text,
        "wc_authority_name" => $wc_authority_name
    ));
    Activity::add("updated Application Information");
    echo json_encode(array("200",  "success|Application Information Updated Successfully",
        $info_app_id
    ));
    $email = new Email($app->siteName . " : " . "Application Information Updated");
    $email->send("You have successfully updated application Detail information on " . $app->siteName);
}
function addcomplainttaxinfo()
{
    global $app;
    $complaint_vat_tax = $complaint_e_tax = $complaint_z_tax = $complaint_n_tax = $complaint_r_tax = $complaint_s_tax = "";
    $data              = sanitizePostData($_POST);
    extract($data);
    $ComplaintTax = new ComplaintTax();
    $ComplaintTax->deactivateAll();
    $complaint_tax_id = $ComplaintTax->add(number_format($complaint_vat_tax, 2), number_format($complaint_e_tax, 2), number_format($complaint_z_tax, 2), number_format($complaint_n_tax, 2), number_format($complaint_r_tax, 2), number_format($complaint_s_tax, 2));
    Activity::add("added new Tax Information");
    echo json_encode(array("200",  "success|Complaint Tax Information added Successfully",
        $complaint_tax_id
    ));
    $email = new Email($app->siteName . " : " . "Application Tax Information Added");
    $email->send("You have successfully added application Tax Information Detail on " . $app->siteName . " w.e.f. " . date('d M Y h:i:s A'));
}
function updatecomplainttaxinfo()
{
    global $app;
    $complaint_vat_tax = $complaint_e_tax = $complaint_z_tax = $complaint_n_tax = $complaint_r_tax = $complaint_s_tax = "";
    $data              = sanitizePostData($_POST);
    extract($data);
    $ComplaintTax = new ComplaintTax($complaint_tax_id);
    $ComplaintTax->update(array("complaint_vat_tax" => number_format($complaint_vat_tax, 2),
        "complaint_e_tax" => number_format($complaint_e_tax, 2),
        "complaint_z_tax" => number_format($complaint_z_tax, 2),
        "complaint_n_tax" => number_format($complaint_n_tax, 2),
        "complaint_r_tax" => number_format($complaint_r_tax, 2),
        "complaint_s_tax" => number_format($complaint_s_tax, 2)
    ));
    Activity::add("updated Tax Information");
    echo json_encode(array("200",  "success|Application Complaint Tax Information Updated Successfully",
        $complaint_tax_id
    ));
    $email = new Email($app->siteName . " : " . "Application Tax Information Updated");
    $email->send("You have successfully updaetd application Tax Information Detail on " . $app->siteName);
}

function searchcode(){
	global $app;
	$code = "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if($code !="")
	{
		$code = strtoupper($code);
		$format = $path = "";
		if(preg_match("/[0-9]{4}[W]{1}[0-9]{3}/", $code)==1 && strlen($code)==8){ //1806W025
			$format = "Collection";
			$class = new Collection();
			$record = $class->getDetailsByCode($code);
			$path = "updatecollection/$record[wc_id]";
		}
		elseif(preg_match("/[0-9]{2}[A-Z]{3}[0-9]{5}/", $code)==1 && strlen($code)==10){ //18CPC00050
			$format = "Complaint";
			$class = new Complaint();
			$record = $class->getDetailsByCode($code);
			$path = "viewcomplaint/$record[complaint_id]";
		}
		elseif(preg_match("/[0-9]{8}/", $code)==1 && strlen($code)==8){ //18060033
			$format = "SalesInvoice";
			$class = new SalesInvoice();
			$record = $class->load($code);
			$path = "salesinvoice/$record[sales_invoice_id]";
		}
		elseif(preg_match("/[0-9]{4}[W]{1}[0-9]{7}/", $code)==1 && strlen($code)==12){ //1806W0250005
			$format = "CollectionProcess";
			$record = CollectionProcess::getProcessByCode($code);
			$path = "processcollection/$record[wc_process_wc_id]/$code";
		}
		elseif(preg_match("/[0-9]{4}[B]{1}[0-9]{5}/", $code)==1 && strlen($code)==10){ //2001B00001
			$format = "BatchProduct";
			$record = BatchProduct::getBatchProductByCode($code);
			$path = "updatebatchproduct/$record[product_id]";
		}
		elseif(preg_match("/[0-9]{2}[R]{1}[A-Z]{2}[0-9]{2}/", $code)==1 && strlen($code)==7){ //20RAA43
			Modal::load(array('Refund'));
			$format = "Refund";
			$record = Refund::getRefundByCode($code);
			$path = "viewrefund/".md5($record['refund_id']);
		}
		if($path != "")
		{
			echo json_encode(array("200",  "danger|Loading $format <b>$code</b> details...", $path));		
		}
		else
			echo json_encode(array("300",  "danger|No record found with <b>$code</b> code"));
	}
	else
		echo json_encode(array("300",  "danger|Input code <b>$code</b> doesn't match"));
}

function getuserbytype(){
	$user_type_id = 0;
	global $app;
	$data  = sanitizePostData($_POST);
	extract($data);
	$userType = new UserType($user_type_id);
	$userTypeModuleData = $userType->get("user_type_module_permission");	
	$Employee = new Employee(0);
	$EmployeeData = $Employee->getOptions($user_type_id);	
	echo json_encode(array("200",  "success|Users list loaded", $EmployeeData, $userTypeModuleData));		
}

function getnewweborderinfoforuser(){
	$web_order_id = 0;
	global $app;
	$data  = sanitizePostData($_POST);
	extract($data);
	if(isAdmin()){
		$websiteOrder = new WebsiteOrder();
		$orderData = $websiteOrder->getOrderFromWebOrderID($web_order_id);	
		echo json_encode(array("200",  "success|Web Order fetched", $orderData));		
	}
	else	
		echo json_encode(array("300",  "danger|Permission deniend"));
}

function managepermission(){
	global $app;
	$employee_id = $user_type_id = 0;
	$module_id = array();
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($user_type_id != 0 && !empty($module_id) && is_array($module_id))
	{
		$user_type = new UserType($user_type_id);
		if($user_type->isExist())
		{
			$userTypeData = $user_type->getDetails();
			$employee = new Employee($employee_id);
			if($employee_id !=0 && $employee->isExist())
			{
				$employeeData = $employee->getDetails();
				$newPermission = array_unique(array_diff($module_id, explode(",",$userTypeData['user_type_module_permission'])));
				asort($newPermission);
				$employee->update(array("user_module_access"=> implode(",",$newPermission)));
				echo json_encode(array("200",  "success|User $employeeData[user_fname] access permission updated"));
			}
			else
			{
				asort($module_id);
				$user_type->update(array("user_type_module_permission"=> implode(",", $module_id)));
				echo json_encode(array("200",  "success|User type $userTypeData[user_type_name] access permission updated"));
			}
		}
		else
			echo json_encode(array("300",  "danger|No User type found"));
	}
	else
		echo json_encode(array("300",  "danger|User type must be selected"));
}

function savemodule()
{
	global $app;
	$module_name = $module_key = $module_icon = $module_color = "";
	$module_sort_order = $module_parent = $module_is_customer_access = $module_is_navbar = $module_is_topbar = $module_id = $module_status = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($module_name != "" && $module_key != "" && $module_icon != "")
	{
		$modulePostData = array(
									"module_name" => $module_name,
									"module_key" => $module_key,
									"module_icon" => $module_icon,
									"module_color" => $module_color,
									"module_sort_order" => $module_sort_order,
									"module_parent" => $module_parent,
									"module_is_customer_access" => $module_is_customer_access,
									"module_is_navbar" => $module_is_navbar,
									"module_is_topbar" => $module_is_topbar,
									"module_id" => $module_id,
									"module_status" => $module_status
								);
		$navbar = new Navbar($module_id);
		if($navbar->isExist())
		{
			$navbar->update($modulePostData);
			echo json_encode(array("200",  "success|Module '$module_name' updated"));
		}
		else
		{
			$module_id = $navbar->insert($modulePostData);
			echo json_encode(array("200",  "success|Module '$module_name' added", $navbar->load()));
		}
		
	}
	else
		echo json_encode(array("300",  "danger|All mendate field required"));
}

function editmodule(){	
	global $app;
	$module_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$navbar = new Navbar($module_id);
	if($module_id != 0 && $navbar->isExist())
	{
		echo json_encode(array("200", "success|Module loaded", $navbar->load()));
		
	}
	else
		echo json_encode(array("300",  "danger|Module not found"));
}

function managecredentials(){	
	global $app;
	$credentials_id = 0;
	$credentials_status = 1;
	$credentials_weburl = $credentials_username = $credentials_password = $credentials_scope = '';
	$data  = sanitizePostData($_POST);
	extract($data);	
	$credentials = new Credentials($credentials_id);
	if($credentials_id == 0 || $credentials->canEdit())
	{
		$record_data = array(
								"credentials_weburl" => $credentials_weburl,
								"credentials_username" => $credentials_username,
								"credentials_password" => $credentials_password,
								"credentials_scope" => $credentials_scope,
								"credentials_last_update" => 'NOW()'
						);
		if($credentials_id == 0)
		{
			$record_data = array_merge($record_data, array(
												"credentials_owner" => $_SESSION['user_id'],
												"credentials_status" => $credentials_status
												)
								);
			$credentials->insert($record_data);
		}
		else
			$credentials->update($record_data);
			
		$record = $credentials->getDetails();
		$record['status'] = statusView($record['credentials_id'], $record['credentials_status']);
		$record['action'] = actionView(array(
			  									array("name"=>"Edit", "icon"=>"fa-edit", "url"=>"javascript:editCredentials($record[credentials_id])", "class"=>""),
												array("name"=>"Delete", "icon"=>"fa-trash", "url"=>"javascript:deleteCredentials($record[credentials_id])", "class"=>"")
											  )
										);
		echo json_encode(array("200", "success|Crentials saved successfully", $record));		
	}
	else
		echo json_encode(array("300",  "danger|Credentials not permissable"));
}

function deletecredentials(){
	global $app;
	$credentials_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$credentials = new Credentials($credentials_id);
	if($credentials->canEdit())
	{
		$credentials->remove();
		echo json_encode(array("200", "success|Crentials deleted successfully"));		
	}
	else
	echo json_encode(array("300",  "danger|Credentials not permissable"));
}

function signinlogon(){
	if(getLoginId())
	{
		global $app;	
		$app->logInSignInuser();
		setcookie("LOGOFF", 	false, 	time() - (86400 * 1)); // 86400 = 1 day
		echo json_encode(array("200", "success|Sign in successfully", time()));
	}
	else
		echo json_encode(array("300",  "danger|Session expired. Login again."));
}
function signinlogoff(){
	if(getLoginId())
	{
		global $app;	
		$app->logOffUser();
		setcookie("LOGOFF", 	true, 	time() + (86400 * 1)); // 86400 = 1 day
		setcookie("LOGOFFTIME", time(), time() + (86400 * 1)); // 86400 = 1 day
		echo json_encode(array("200", "success|Log Off successfully", time()));
	}
	else
		echo json_encode(array("300",  "danger|Session expired. Login again."));
	
}

function getsignaturelink(){
	global $app;
	$signature_code = "";
	$signature_class = "";
	$signature_column = "";
	$signature_record_id = "";
	$signature_title = "";
	$signature_validity_second = SIGNATURE_LINK_VALIDITY_TIME;
	$signature_created_date = 'NOW()';
	$signature_is_used = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($signature_class != "" && $signature_column != "" && $signature_record_id != "" ){
		$signature_code = md5(strtolower(trim($signature_class).trim($signature_column).trim($signature_record_id)));
		$signature = new Signature(0);
		$signature_id = $signature->insert(compact('signature_code', 'signature_class', 'signature_column', 'signature_record_id', 'signature_title', 'signature_validity_second', 'signature_created_date', 'signature_is_used'));
		$signature_link = $app->basePath("signature.php?c=".md5($signature_code)."&i=".md5($signature_id)."&v=".SIGNATURE_LINK_VALIDITY_TIME."&t=".time());
		echo json_encode(array("200", "success|Signature link generated successfully", $signature_link));
	}else
		echo json_encode(array("300",  "danger|Invalid input found."));
}

function acceptsignaturerequest(){
	global $app;
	$signature_code = "";
	$signature_path = "";
	$signature_id	= "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if(!is_array($signature_code) && !is_array($signature_path) && !is_array($signature_id)){
		if($signature_code != "" && $signature_path != "" && $signature_id != ""){
			if(file_exists($app->sitePath($signature_path))){
				$s = new Signature();
				if($record = $s->getDetailsSecure(md5($signature_id), md5($signature_code))){
					if($record['signature_is_used'] == 0){
						if(strtotime($record['signature_created_date']) + $record['signature_validity_second'] > time()){						
							$sign_path = "upload/user/sign/".getDirectorySeparatorPath()."link-" . time(). ".png";
							if (move_file($app->sitePath($signature_path), $app->sitePath($sign_path))){
								
								$signClass = new $record['signature_class']($record['signature_record_id']);
								$signClass->update(array($record['signature_column']=> $sign_path));
								
								$signature = new Signature($record['signature_id']);
								$signature->update(array("signature_is_used" => 1, "signature_path"=>$sign_path));
								
								echo json_encode(array("200", "success|Signature uploaded successfully"));
							}else
								echo json_encode(array("300",  "danger|Signature can't saved. try again."));
						}else
							echo json_encode(array("300",  "danger|Signature link expired."));
					}else
						echo json_encode(array("300",  "danger|Signature link already used."));
				}else
					echo json_encode(array("300",  "danger|Invalid Signature link."));
			}else
				echo json_encode(array("300",  "danger|Uploaded signature not found."));
		}else
			echo json_encode(array("300",  "danger|Invalid input found."));
	}else
		echo json_encode(array("300",  "danger|Input format is not correct."));
}

function updatecomplaintlog(){
	global $app;
	$complaint_log_id = 0;
	$complaint_log_text = "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if(intval($complaint_log_id) != 0 && $complaint_log_text != ""){
		$complaintLog = new ComplaintLog($complaint_log_id);	
		if($complaintLog->isExist()){
			$logRecord = $complaintLog->getDetails();
			if((isAdmin() && $logRecord['complaint_user_id'] != 0) || (!isAdmin() && $logRecord['complaint_customer_id'] != 0)){
				if(isAdmin() || ($logRecord['complaint_customer_id'] == getLoginId())){
					$complaint_user_id 		= USER=="ADMIN"?getLoginId():0;
					$complaint_customer_id 	= USER=="ADMIN"?0:getLoginId();
					$complaintLog->saveHistory();
					$complaintLog->update(
						array(
							"complaint_log_text" 	=> $complaint_log_text,
							"complaint_user_id"		=> $complaint_user_id,
							"complaint_customer_id" => $complaint_customer_id,
							"complaint_log_time"	=> "NOW()"
						)
					);
					$record = array
						(
							'msg_format' 	=> 'module',
							'logger_id' 	=> getLoginId(),
							'logger_name' 	=> isAdmin() ? $_SESSION['user_name'] : $_SESSION['customer_name'],
							'logger_image' 	=> $app->imagePath(isAdmin() ? $_SESSION['user_image'] : $_SESSION['customer_image']),
							'logger_type' 	=> getUserType(),
							'complaint_log_text' 	=> htmlspecialchars($complaint_log_text),
							'complaint_id'	=>$logRecord['complaint_id'],
							'complaint_log_id'=>$complaint_log_id,
							'complaint_format'=>$logRecord['complaint_format'],
							'log_time'  	=> dateView(date('Y-m-d H:i:s'),'NOW'),
							'title'			=> "Message From ".(isAdmin() ? $_SESSION['user_name'] : $_SESSION['customer_name']),
							'sound'			=> 1,
							'complaint_log_privacy' => $logRecord['complaint_log_privacy'],
							'largeIcon'		=> $app->imagePath(isAdmin() ? $_SESSION['user_image'] : $_SESSION['customer_image']),
							'smallIcon'		=> $app->imagePath(isAdmin() ? $_SESSION['user_image'] : $_SESSION['customer_image']),
						);
					echo json_encode(array("200", "success|Comment log updated successfully", $record));
						
				}else
					echo json_encode(array("300",  "danger|You can't update else comment than this."));
			}else
				echo json_encode(array("300",  "danger|You are not authorised to update this comment."));
		}else
			echo json_encode(array("300",  "danger|Requested log record not found"));
	}else
		echo json_encode(array("300",  "danger|Input format is not correct."));
}

function getproductdetailsearch(){	
	global $app;
	$keyword = "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if($keyword != ""){
		if($record = WebsiteOrderProduct::checkProductStockStatus($keyword))
			echo json_encode(array("200", "success|Product details found", $record));
		else
			echo json_encode(array("300",  "danger|No product matched."));
	}
	else
		echo json_encode(array("300",  "danger|Input format is not correct."));
}

function updateproductstockbyswitch(){
	global $app;
	$origin = "";
	$id	 = $value = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($id != 0){
		if($origin == "BatchProduct"){
			$product = new BatchProduct($id);
			if($product->isExist()){
				$product->update(array("product_in_stock" => $value ? 1 : 0));
				echo json_encode(array("200", "success|Product Stock status updated"));
			}
			else
				echo json_encode(array("300",  "danger|No product matched."));
		}
		elseif($origin == "CollectionProcess"){
			$product = new CollectionProcess();
			if($product->loadById($id)){
				$product->update(array("wc_process_item_stock" => $value ? 1 : 0));
				echo json_encode(array("200", "success|Product Stock status updated"));
			}
			else
				echo json_encode(array("300",  "danger|Input format is not correct."));
		}
		else
			echo json_encode(array("300",  "danger|Input format is not correct."));
	}
	else
		echo json_encode(array("300",  "danger|No product matched."));
}

function checkexistinglot(){
	
	if(isset($_SESSION['LOT'])){		
		echo json_encode(array("200", "success|Existing lot found", isset($_SESSION['LOT']['PRODUCT']) ? count($_SESSION['LOT']['PRODUCT']) : 0));			
	}
	else
		echo json_encode(array("300",  "danger|No lot available.", ''));
}

function createnewlot(){
	
	if(!isset($_SESSION['LOT'])){
		$_SESSION['LOT'] = array();
		$_SESSION['LOT']['PRODUCT'] = array();
		echo json_encode(array("200", "success|Lot is available to add products"));			
	}
	else
		echo json_encode(array("300",  "danger|A lot already on Progress.", count($_SESSION['LOT']['PRODUCT'])));
}

function clearcurrentlot(){
	global $app;	
	if(isset($_SESSION['LOT']))
	unset($_SESSION['LOT']);
	echo json_encode(array("200", "success|Current lot cleared"));	
}

function addproductonlot(){
	global $app;
	$data  = sanitizePostData($_POST);
	extract($data);
	if(!isset($_SESSION['LOT'])){
		$_SESSION['LOT'] = array();
		$_SESSION['LOT']['PRODUCT'] = array();				
	}
	
	if(!isset($_SESSION['LOT']['PRODUCT']))
		$_SESSION['LOT']['PRODUCT'] = array();
	if(!in_array($product, $_SESSION['LOT']['PRODUCT'])){
		$_SESSION['LOT']['PRODUCT'][] = $product;
		echo json_encode(array("200", "success|Product added to lot", count($_SESSION['LOT']['PRODUCT'])));
	}
	else{
		echo json_encode(array("200", "danger|Product already in lot", count($_SESSION['LOT']['PRODUCT'])));
	}
}

function addsearchproductstolot(){
	$products = array();
	$data  = sanitizePostData($_POST);
	extract($data);	
	if(!count($products))
	{
		echo json_encode(array("300",  "warning|No product found."));
		die;
	}
	if(!isset($_SESSION['LOT'])){
		$_SESSION['LOT'] = array();
		$_SESSION['LOT']['PRODUCT'] = array();				
	}
	
	if(!isset($_SESSION['LOT']['PRODUCT']))
		$_SESSION['LOT']['PRODUCT'] = array();
	
	foreach($products as $_product){
		$_SESSION['LOT']['PRODUCT'][] = $_product;
	}
	echo json_encode(array("200", "success|Products added to current lot", count($_SESSION['LOT']['PRODUCT'])));
}
?>