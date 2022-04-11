<pre>
<?php
include("setup.php");
function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            $results[] = str_ireplace('/home/safepcsupportco/public_html/live.safepcsupport.co.uk/', '', $path);
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            //$results[] = $path;
        }
    }

    return $results;
}

$sql = "SELECT `image_path` AS path FROM app_batch_product_images
UNION SELECT `checklist_file` AS path FROM app_checklist_report
UNION SELECT `customer_image` AS path FROM app_customer 
UNION SELECT `company_invoice_file_path` AS path FROM app_company_invoice
UNION SELECT `company_trademark` AS path FROM app_company_record
UNION SELECT `label_path` AS path FROM app_complaint_record_label
UNION SELECT `cont_ins_file_path` AS path FROM app_contract_insurance
UNION SELECT `employee_signature` AS path FROM app_employee_contract
UNION SELECT `employee_id_card` AS path FROM app_employee_contract 
UNION SELECT `eic_employee_signature` AS path FROM app_employee_inducation_checklist
UNION SELECT `eic_hr_signature` AS path FROM app_employee_inducation_checklist		
UNION SELECT `eil_employee_signature` AS path FROM app_employee_leaver_checklist
UNION SELECT `eil_hr_signature` AS path FROM app_employee_leaver_checklist 
UNION SELECT `notice_attachement` AS path FROM app_employee_notice
UNION SELECT `training_trainee_signature` AS path FROM app_employee_training
UNION SELECT `training_trainer_signature` AS path FROM app_employee_training
UNION SELECT `isms_file_path` AS path FROM app_isms_register
UNION SELECT `licence_file_path` AS path FROM app_licence
UNION SELECT `manual_file_path` AS path FROM app_manual_training_doc
UNION SELECT `policies_file_path` AS path FROM app_policies
UNION SELECT `repair_image_path` AS path FROM app_repair_record_images
UNION SELECT `rfc_signature` AS path FROM app_rfc_record
UNION SELECT `label_path` AS path FROM app_sales_invoice_label
UNION SELECT `sales_image_path` AS path FROM app_sales_record_images
UNION SELECT `shipment_type_image` AS path FROM app_shipment_types
UNION SELECT `signature_path` AS path FROM app_signature_capturer	
UNION SELECT `store_icon` AS path FROM app_store_master
UNION SELECT `store_logo` AS path FROM app_store_master
UNION SELECT `supplier_image` AS path FROM app_supplier_record
UNION SELECT `supplier_doc_file` AS path FROM app_supplier_record
UNION SELECT `user_image` AS path FROM app_system_user
UNION SELECT `user_signature` AS path FROM app_system_user
UNION SELECT `user_id_card` AS path FROM app_system_user
UNION SELECT `user_experience_certificate` AS path FROM app_system_user
UNION SELECT `user_education_doc` AS path FROM app_system_user 
UNION SELECT `user_driving_licence` AS path FROM app_system_user
UNION SELECT `user_cv_file` AS path FROM app_system_user  
UNION SELECT `templates_file_path` AS path FROM app_templates
UNION SELECT `user_pay_contract_file` AS path FROM app_user_pay_setup
UNION SELECT `user_char_exp_file` AS path FROM app_user_pay_setup 
UNION SELECT `utility_doc_file` AS path FROM app_utilities
UNION SELECT `image_path` AS path FROM app_wc_process_images		
UNION SELECT `wc_transferor_signature` AS path FROM app_wc_record
UNION SELECT `record_file_path` AS path FROM app_wc_record_images
UNION SELECT `web_order_invoice_image` AS path FROM app_website_order
UNION SELECT `web_order_commission_user_file` AS path FROM app_website_order 
UNION SELECT `label_path` AS path FROM app_website_order_label
UNION SELECT `wpoi_image_path` AS path FROM app_website_order_product_image";

$dbc 	= 	new DB();
$dbc->db_query($sql);
$dbArray = array();
$totalworking = (8*60*60);
if($dbc->db_num_rows()){
	while($row = $dbc->db_fetch_assoc()){
		if($row['path'])
		$dbArray[] = $row['path'];
	}
}

$fileArray = getDirContents(__DIR__.'/upload');
$extraOnFile = array_diff($fileArray, $dbArray);
//print_r($dbArray);
print_r($extraOnFile);
?> 