<?php require_once 'setup.php'; ?>
<?php
//header('Content-Type: application/json');
if(isset($_REQUEST['action']))
{
	$action=@$_REQUEST['action']; 
	if(function_exists($action))
	{
		if(isset($_REQUEST['android_app_logged_in_user_id']) || $action=="user_login")
		{
			if(!isset($_SESSION['user_id'])){
				if(ALLOW_ANDROID_USER_RE_SESSION)
					loadSession(intval($_REQUEST['android_app_logged_in_user_id']));
				else
				{
					echo response(array("status"=>400,"message"=>"Your Session Expired. Please Login to continue...")); die;
				}
			}
			else
			{
				if(!chekAouthAppUser($_REQUEST['android_app_logged_in_user_id']))
				{
					echo response(array("status"=>400,"message"=>"You are not Authorised to access App. Please contact to safePcDisposal"));die;
				}
			}
			call_user_func($action);	
		}
		else
			echo response(array("status"=>400,"message"=>"Your Session Expired. Please Login to continue...")); die;			
	}
	else
		echo response(array("status"=>400,"message"=>"Invalid Action found. Please Refresh and try again."));
}
else
	echo response(array("status"=>400,"message"=>"No Action found. Please Refresh and try again."));
	
#=================================================================================================

								# Function Body Started
								
								function response($responseArray = NULL)
								{
									$responseArray["NEW_SESSION_ID"] = session_id();
									$responseArray["OLD_SESSION_ID"] = isset($_REQUEST['android_app_logged_in_session_id'])?$_REQUEST['android_app_logged_in_session_id']:"";
									return json_encode($responseArray);
								}

#=================================================================================================
function chekAouthAppUser($user_id)
{
	$emp = new Employee($user_id);
	$user = $emp->getDetails();
	if($user['user_allow_app']==1)
		return true;
	else
		return false;
}

function loadSession($user_id, $app_format = 'APP'){
	global $app;
	$emp = new Employee($user_id);
	$user = $emp->getDetails();
	
	$_SESSION['user_id']				=	$user['user_id'];
	$_SESSION['user_name']				=	$user['user_name'];
	$_SESSION['user_fname']				=	$user['user_fname'];
	$_SESSION['user_lname']				=	$user['user_lname'];
	$_SESSION['user_type_id']			=	$user['user_type_id'];
	$_SESSION['user_type_name']			=	$user['user_type_name'];
	$_SESSION['user_email']				=	$user['user_email'];
	$_SESSION['user_phone']				=	$user['user_phone'];
	$_SESSION['user_image']				=	$user['user_image'];
	$_SESSION['user_is_email_verified']	=	$user['user_is_email_verified'];
	$_SESSION['user_is_mobile_verified']=	$user['user_is_mobile_verified'];
	$_SESSION['user_allow_app']			=	$user['user_allow_app'];				
	$_SESSION['user_is_admin']			=	$user['user_is_admin'];	
	$_SESSION['user_fcm_token']			=	$user['user_fcm_token'];
	$_SESSION['user_timeout']			=	$user['user_timeout'];
	$_SESSION['app_theme']				=	$user['user_theme'];
	$_SESSION['app_log_type']			=	'E';
	$_SESSION['app_format']				=	$app_format;
	$_SESSION['APP_ACCESS']				= 	true;	
	
	$emp->setLive();	
	setcookie("LOGOFF" , false, time() - (86400 * 1)); // 86400 = 1 day
	setcookie("TIMEOUT" , $user['user_timeout'], time() + (86400 * 1)); // 86400 = 1 day
	$_SESSION['app_log_login_id'] = $app->logLogin();
}

function user_registration(){ //echo response($_REQUEST);die;
	$user_fcm_token = isset($_REQUEST['user_fcm_token'])?$_REQUEST['user_fcm_token']:NULL;
	
	if($user_fcm_token!=NULL){	
	/*
		$user_fname = $user_lname = $user_email = $user_mobile = "";
		$user_allow_notification = $user_status = 0;
		$user_password = gePassword();
		$requestData = sanitizePostData($_REQUEST);
		extract($requestData);
		$user_email = strtolower($user_email);
		$employee = new Employee();
		/*if(!$fcm_registered_email_id = $employee->isDeviceFcmExist($user_fcm_token))
		{
			if(!$employee->IsEmialAvailable($user_email))
			{
				$user_id = $employee->add($user_fname, $user_lname, $user_email, $user_password, $user_mobile, $user_fcm_token, $user_allow_notification, $user_status);
				echo response(array("status"=>"200","message"=>"User Registration Complted Successfully.", "data"=>array("user_id"=>$user_id), , "ACTION"=>"SIGNUP"));
			}
			else
				echo response(array("status"=>"300","message"=>"User Email Already Exist."));
		}
		else*/
			echo response(array("status"=>"300","message"=>"Registration form Device is Currently Closed. Please use Web Portal for registration."));
	}
}



function user_login(){ //echo response($_REQUEST);die;
	global $app;
	$user_email = $user_password = $device_name = $app_format = "";
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);
	$user_email = strtolower($user_email);
	
	$employee = new Employee();
	if($user_id = $employee->authenticate($user_email, $user_password))
	{
		$user = new Employee($user_id);
		$record= $user->getDetails();
		$record['user_image_url'] = $app->basePath($record['user_image'], false);
		$record['user_session_id'] = session_id();
		loadSession($record['user_id'], $app_format);
		$DeviceManager = new DeviceManager();
		$DeviceManager->setUser($record['user_id']);
		$existingUser = $DeviceManager->checkUserDevice($user_fcm_token);
		if($existingUser)
		{
			if($existingUser['fab_user_id'] != $DeviceManager->fab_user_id)
			{
				$oldDeviceManager = new DeviceManager($existingUser['fab_id']);
				$oldDeviceManager->Deactivate();
				$DeviceManager->addDevice($user_fcm_token, $device_name);
			}
		}
		else
		{
			$DeviceManager->addDevice($user_fcm_token, $device_name);
		}
				
		echo response(array("status"=>"200","message"=>"User Logged in Successfully.", "data"=>$record, "ACTION"=>"LOGIN"));
	}
	else
		echo response(array("status"=>"300","message"=>"No User Found.", "data"=>NULL));
}

function app_record_count()
{
	global $app;
	$data = array();
	$record = $app->record_count();
	$data["COUNT"] = $record;
	
	$complaint = new Complaint(0);	
	$data["CHART"]["COMPLAINT_ORIGIN"] = $complaint->ComplaintOriginchart();
	$data["CHART"]["COMPLAINT_PROBLEM"] = $complaint->complaintRecordProblemCountChart();	
	
	
	echo response(array("status"=>"200","message"=>"App Records Count Loaded.", "data"=>$data));
}

function collection_list(){ //echo response($_REQUEST);die;
	global $app;
	$record_count = 0; $searchkeyword = "";
	$searchkeyword = "";
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);
	
	$collection = new Collection();
	if($collectionData = $collection->getListView($record_count, $searchkeyword))
	{
		echo response(array("status"=>"200","message"=>"Collection Records Loaded.", "data"=>$collectionData));
	}
	else
		echo response(array("status"=>"300","message"=>"No more collection records found.", "data"=>NULL));
}

function collection_info(){
	global $app;
	$colection_count = 0;
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);
	
	$collection = new Collection();
	if($collectionInfo = $collection->getDetailsByCode($wc_code))
	{
		$collectionInfo['hwcr_link'] = DOC::HWCN($collectionInfo['wc_id']);
		$collectionInfo['wcnn_link'] = DOC::WCNN($collectionInfo['wc_id']);
		$collectionInfo['docn_link'] = DOC::DOCN($collectionInfo['wc_id']);
		$collectionInfo['cert_link'] = DOC::CERT($collectionInfo['wc_id']);
		
		echo response(array("status"=>"200","message"=>"Collection Records Loaded.", "data"=>$collectionInfo));
	}
	else
		echo response(array("status"=>"300","message"=>"No Collection Information Found.", "data"=>NULL));
}

function send_certificate_to_customer()
{
	global $app;
	$wc_id = 0;
	$data = sanitizePostData($_REQUEST);
	extract($data);
	if($wc_id!=0)
	{
		$collection = new Collection($wc_id);
		$collectionData = $collection->load();
		$email = new Email("New Collection Certificate for #$collectionData[wc_code]");	
		$email->to($collectionData['customer_email'], $collectionData['customer_name']);
		
		$email->addFile(DOC::CERT($wc_id), $app->siteName." Collection Certificate - $collectionData[wc_code].pdf")
		->template('send_document', array("message"=>"We are happy to inform you that your Collection Certificate is ready against Collection Order number : #$collectionData[wc_code]"))->send();
		Activity::add("send Hazard waste Consignment Certificate to Customer|^|{$collectionData['wc_code']}","W", $wc_id); 
		echo response(array("status"=>"200","message"=>"Collection Certificate send to customer Successfully", "data"=>$collectionData));
	}
	else
		echo response(array("status"=>"300","message"=>"No more Collection Record Found", "data"=>NULL));

}

function get_collection_snapshot(){
	global $app;
	$wc_id = 0;
	$data = sanitizePostData($_REQUEST);
	extract($data);
	if($wc_id!=0)
	{
		$mediaItems = array();
		$mediaItems['wc_id'] = $wc_id;
		$mediaItems['wc_code'] = $wc_code;
		$WcRecordMedia = new WcRecordMedia(0);
		$recorded_snapshot_list = $WcRecordMedia->getRecordList($wc_id);
		if($recorded_snapshot_list){
			
			for($i=0; $i<count($recorded_snapshot_list);$i++)
			{
				$mediaItem = $recorded_snapshot_list[$i];
				$mediaItem['collection_media_record_image'] = $app->basePath($mediaItem['record_file_path'], false);
				$mediaItem['record_created_date'] = "Uploaded on : ".dateView($mediaItem['record_created_date'], "FULL")." By ".$mediaItem['uploader_name'];
				$mediaItem['record_remark'] = "Image Description : ".$mediaItem['record_remark'];
				
				$recorded_snapshot_list[$i] = $mediaItem;
			}			
			$mediaItems['media'] = $recorded_snapshot_list;
			echo response(array("status"=>"200","message"=>"Collection media images Loaded", "data"=>response($mediaItems)));
		}
		else
		{
			$mediaItems['media'] = NULL;
			echo response(array("status"=>"200","message"=>"No Collection media images Found", "data"=>$mediaItems));	
		}
	}
}

function upload_collection_image()
{
	global $app;
	$wc_id = 0;
	//$data = sanitizePostData($_REQUEST);
	$data = $_REQUEST;
	extract($data);
	$record_added_by = 1;
	$record_status  = 1;
	$record_file_path = "upload/collection/record/".$wc_code."_".time().".png";
	if(file_put_contents($record_file_path, base64_decode(str_replace(' ','+',$image_bitmap))))
	{
		$WcRecordMedia = new WcRecordMedia(0);
		$record_id = $WcRecordMedia->add($wc_id, $record_file_name, $record_file_path, $record_added_by, $record_remark, $record_status);
		echo response(array("status"=>"200","message"=>"File saved on server", "data"=>$wc_id));
	}
	else
		echo response(array("status"=>"300","message"=>"Ooops...File Couldn't saved on server", "data"=>NULL));
}

function upload_signature_image()
{
	global $app;
	$wc_id = 0;
	//$data = sanitizePostData($_REQUEST);
	$data = $_REQUEST;
	extract($data);
	$record_added_by = $_SESSION['user_id'];
	$record_status  = 1;
	$signaturePath = "upload/collection/sign/".$wc_code."-".time().".png";
	if(file_put_contents($signaturePath, base64_decode(str_replace(' ','+',$image_bitmap))))
	{
		$collection = new Collection($wc_id);
		$collectionData = $collection->getDetails();
		$collection->update( array("wc_transferor_signature"=>$signaturePath));
		Activity::add("added Media Snapshot|^|{$collectionData['wc_code']}","W", $wc_id); 
		echo response(array("status"=>"200","message"=>"Signature File saved on server", "data"=>$wc_id));
	}
	else
		echo response(array("status"=>"300","message"=>"Ooops...Signature File Couldn't saved on server", "data"=>NULL));
}

function complaint_list(){ //echo response($_REQUEST);die;
	global $app;
	$record_count = 0; $searchkeyword = "";
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);
	
	$complaint = new Complaint();
	if($complaintData = $complaint->getListView($record_count, $searchkeyword))
	{
		echo response(array("status"=>"200","message"=>"Complaint Records Loaded.", "data"=>$complaintData));
	}
	else
		echo response(array("status"=>"300","message"=>"No more Complaint Records Found.", "data"=>NULL));
}

function complaint_info(){
	global $app;
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);	
	$complaint = new Complaint();
	if($complaintInfo = $complaint->getDetailsByCode($complaint_ticket_number))
	{
		$complaintInfo['customer_image'] = $app->basePath($complaintInfo['customer_image'],false);
		$complaintInfo['technician_image'] = $app->basePath($complaintInfo['technician_image'],false);
		$complaintInfo['complaint_created_date'] = dateView($complaintInfo['complaint_created_date'],"FULL");
		$complaintInfo['complaint_due_date'] = dateView($complaintInfo['complaint_due_date'], "DATE");
		
		$complaintInfo['complaint_is_backup'] = $complaintInfo['complaint_is_backup'] ? "Yes" : "No";
		$complaintInfo['complaint_is_disk_provided'] = $complaintInfo['complaint_is_disk_provided'] ? "Yes" : "No";
		$complaintInfo['complaint_product_is_under_waranty'] = $complaintInfo['complaint_product_is_under_waranty'] ? "Yes" : "No";
		
		
		$complaintInfo['cinv_link'] = DOC::CINV($complaintInfo['complaint_id']);
		$complaintInfo['cprt_link'] = DOC::CPRT($complaintInfo['complaint_id']);
		
		$hardwareType = new HardwareType();
		$complaintInfo['complaint_product_hardware_not_working'] = $hardwareType->simplelistName(explode(",", $complaintInfo['complaint_product_hardware_not_working']), ", ");
		echo response(array("status"=>"200","message"=>"Complaint Record Loaded.", "data"=>$complaintInfo));
	}
	else
		echo response(array("status"=>"300","message"=>"No Complaint Information Found.", "data"=>NULL));
}

function customer_list(){ //echo response($_REQUEST);die;
	global $app;
	$record_count = 0; $searchkeyword = "";
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);
	
	$customer = new Customer();
	if($customerData = $customer->getListView($record_count, $searchkeyword))
	{
		echo response(array("status"=>"200","message"=>count($customerData)." Customer Records Loaded.", "data"=>$customerData));
	}
	else
		echo response(array("status"=>"300","message"=>"No more Customer records found.", "data"=>NULL));
}

function customer_info(){
	global $app;
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);	
	$customer = new Customer($customer_id);
	if($customerInfo = $customer->getAndroidDetails())
	{
		$customerInfo['customer_image'] = $app->basePath($customerInfo['customer_image'], false);
		$customer_complaint  = Complaint::getAndroidCustomerComplaint($customer_id);
		$customer_collection = Collection::getAndroidCustomerCollection($customer_id);
		$customerInfo["complaint"] = $customer_complaint;
		$customerInfo["collection"] = $customer_collection;
		echo response(array("status"=>"200","message"=>"Customer Record Loaded.", "data"=>$customerInfo));
	}
	else
		echo response(array("status"=>"300","message"=>"No Costomer Information Found.", "data"=>NULL));
}

function update_customer_status(){
	global $app;
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);	
	$customer = new Customer($customer_id);
	if($customer->update(array("customer_status"=>$customer_status)))
	{
		$Customer_name = $customer->get('customer_fname');
		Activity::add(status($customer_status)." Customer <b>".$Customer_name."</b> staus");
		echo response(array("status"=>"200","message"=>"Customer \"$Customer_name\" Record Updated.", "data"=>NULL));
	}
	else
		echo response(array("status"=>"300","message"=>"Oops..Customer \"$Customer_name\" Record Couln't Updated on server.", "data"=>NULL));
}

function employee_list(){ //echo response($_REQUEST);die;
	global $app;
	$record_count = 0; $searchkeyword = "";
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);
	
	$employee = new Employee();
	if($employeeData = $employee->getListView($record_count, $searchkeyword))
	{
		echo response(array("status"=>"200","message"=>count($employeeData)." Customer Records Loaded.", "data"=>$employeeData));
	}
	else
		echo response(array("status"=>"300","message"=>"No more employee records found.", "data"=>NULL));
}

function employee_info(){
	global $app;
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);	
	$employee = new Employee($user_id);
	if($employeeInfo = $employee->getAndroidDetails())
	{
		$employeeInfo['user_image'] = $app->basePath($employeeInfo['user_image'], false);
		echo response(array("status"=>"200","message"=>"Employee Record Loaded.", "data"=>$employeeInfo));
	}
	else
		echo response(array("status"=>"300","message"=>"No Employee Information Found.", "data"=>NULL));
}

function update_employee_status(){
	global $app;
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);	
	$employee = new Employee($user_id);
	if(isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin']==1)
	{
		if($employee->update(array("user_status"=>$user_status)))
		{
			$Employee_name = $employee->get('user_fname');
			Activity::add(status($user_status)." Employee <b>".$Employee_name."</b> staus");
			echo response(array("status"=>"200","message"=>"Employee \"$Employee_name\" Record Updated.", "data"=>NULL));
		}
		else
			echo response(array("status"=>"300","message"=>"Oops..Employee \"$Employee_name\" Record Couln't Updated on server.", "data"=>NULL));
	}
	else
		echo response(array("status"=>"300","message"=>"Ooops... You are not an Administrator. Admi Permission Required.", "data"=>NULL));
}

function update_employee_allow_notification()
{
	global $app;
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);	
	$employee = new Employee($user_id);
	if($employee->update(array("user_allow_notification"=>$user_allow_notification)))
	{
		$Employee_name = $employee->get('user_fname');
		Activity::add(status($user_allow_notification)." Employee <b>".$Employee_name."</b> App Notification staus");
		echo response(array("status"=>"200","message"=>"Employee $Employee_name's App Notification  Record Updated.", "data"=>NULL));
	}
	else
		echo response(array("status"=>"300","message"=>"Oops..Employee $Employee_name's App Notification Record Couln't Updated on server.", "data"=>NULL));
}

function order_notification_list(){ //echo response($_REQUEST);die;
	global $app;
	$record_count = 0; $searchkeyword = "";
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);
	
	$OrderNotification = new OrderNotification();
	if($OrderNotificationData = $OrderNotification->getListView($record_count, $searchkeyword))
	{
		echo response(array("status"=>"200","message"=>count($OrderNotificationData)." Order Notification Records Loaded.", "data"=>$OrderNotificationData));
	}
	else
		echo response(array("status"=>"300","message"=>"No more Order Notification records found.", "data"=>NULL));
}

function download_hazard_waste_collection_report(){
	global $app;
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);	
	$hwc_link = unSecureUrl(DOC::HWCN($wc_id));	
	echo response(array("status"=>"200","message"=>"Hazard Waste Collection Report for \"$wc_code\" Generated successfully.", "data"=>array("pdfurl"=>$hwc_link)));	
}
function download_wastage_consignment_report(){
	global $app;
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);	
	$wcn_link = unSecureUrl(DOC::WCNN($wc_id));
	echo response(array("status"=>"200","message"=>"Wastage Consignment Report for \"$wc_code\" Generated successfully.", "data"=>array("pdfurl"=>$wcn_link)));	
}
function download_duty_of_care_report(){
	global $app;
	$requestData = sanitizePostData($_REQUEST);
	extract($requestData);	
	$doc_link = unSecureUrl(DOC::DOCN($wc_id));
	echo response(array("status"=>"200","message"=>"Duty of Care Report for \"$wc_code\" Generated successfully.", "data"=>array("pdfurl"=>$doc_link)));	
}
function request_customer_feedback()
{
	global $app;
	$feedback_customer_id = $feedback_module_id = 0;
	$feedback_module_code = "";
	$feedback_status = 1;
	$data = sanitizePostData($_POST);
	extract($data);
	$customerData = array();
	$moduleData = array();
	if($feedback_module_id!=0)
	{
		if($feedback_module_code == "C")
		{
			$complaint = new Complaint($feedback_module_id);
			$moduleData = $complaint->getDetails();
			$feedback_customer_id = $moduleData['complaint_customer_id'];
		}
		if($feedback_module_code == "W")
		{
			$collection = new Collection($feedback_module_id);
			$moduleData = $collection->getDetails();
			$feedback_customer_id = $moduleData['wc_customer_id'];
		}
		$customer = new Customer($feedback_customer_id);
		$customerData = $customer->getDetails();		
	}
	if($customerData && $moduleData)
	{	
		$feedback = new Feedback();
		$feedback_id = $feedback->add($feedback_customer_id, $feedback_module_id, $feedback_module_code, $feedback_status);
		$feedback_hashcode = strtoupper("FD"."-".md5($feedback_id)."-".md5($feedback_customer_id)."-".$feedback_module_code."-".md5($feedback_module_id));
		$feedback_link = $app->basePath("feedback.php?feedback_hashcode=$feedback_hashcode&aouthv1=".strtoupper(md5(strtoupper($feedback_hashcode)))."&likes");
		$data = array("customer_name"=>$customerData['customer_name'], "feedback_link"=>$feedback_link, "message"=>"");
		Activity::add("requested Feedback", "F", 0, $feedback_customer_id, "C");
		$email = new Email($app->siteName." : ". "Feedback request");
		$email->to($customerData['customer_email'], $customerData['customer_name'], $customerData['customer_image']);
		$email->template("customerfeedback", $data);
		$email->send("Thankyou for giving us chance for service on ".$app->siteName);
		echo json_encode(array("status"=>"200","message"=>"Feedback request submitted Successfully"));
	}
	else
		echo json_encode(array("status"=>"300","message"=>"Couldn't send feedback Request.", "data"=>NULL));
}
?>