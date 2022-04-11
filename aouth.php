<?php include("setup.php"); ?>
<?php $app = new App();?>
<?php if(isset($_POST['action'])) {
	$action=@$_POST['action']; 

	if(function_exists($action))

		call_user_func($action);

	else

		echo json_encode(array(404,"Invalid Action found. Please Refresh and try again."));

}

else

	echo json_encode(array(404,"No Action found. Please Refresh and try again."));

?>
<?php

function userlogin()
{
	global $app;
	$username = $password = $captcha = "";
	$_POST = sanitizePostData($_POST);
	$username 	= 	strtolower(checkData($_POST['username']));
	$password 	= 	checkData($_POST['password']);
	$captcha 	= 	checkData($_POST['captcha']);
	
	if(ENABLE_LOGIN_CAPTCHA == 1)
	{
		if((!isset($_SESSION["CAPTCHA"]["EMPLOYEE-LOGIN"]) || strtolower($_SESSION["CAPTCHA"]["EMPLOYEE-LOGIN"]) != strtolower($captcha)))
		{
			echo json_encode(array(300,"danger|Captcha code not matched."));
			return;
		}
	}

	if($username && $password)
	{			
		$employee 	= 	new Employee(0);
		$user		=	$employee->login($username, $password);
		
		if($user)
		{
			if(ENABLE_LOGIN_EMAIL_VERIFICATION_CHECK && $user['user_is_email_verified']==0)
			{
				echo json_encode(array(300,"danger|Your email id is not verified. please check and verified."));				
			}
			elseif(ENABLE_LOGIN_MOBILE_VERIFICATION_CHECK && $user['user_is_mobile_verified']==0)
			{
				echo json_encode(array(300,"danger|Your mobile is not verified. please check and verified."));				
			}
			elseif($user['user_status'] == 1)
			{			
				session_destroy();
				session_start();
				session_regenerate_id(true);
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
				$_SESSION['app_filter_state']		=	$user['user_filter_state'];				
				$_SESSION['app_log_type']			=	'E';
				$_SESSION['APP_ACCESS']				= 	true;	
				$_SESSION['app_format']				=	'WEB';

				$employee 	=	new Employee($_SESSION['user_id']);
				$employee->setLive();
				setcookie("LOGOFF" , false, time() - (86400 * 1)); // 86400 = 1 day
				setcookie("TIMEOUT" , $user['user_timeout'], time() + (86400 * 1)); // 86400 = 1 day
				$_SESSION['app_log_login_id'] = $app->logLogin();
				echo json_encode(array(200,"Login successfull. Redirecting to Panel..."));
			}
			else
				echo json_encode(array(300,"danger|Your account is deactivated. please contatc to Administrator."));
		}
		else
			echo json_encode(array(300,"danger|Inavalid Username or Password found."));
	}
	else
		echo json_encode(array(300,"danger|Username and Password must be filled."));
}



function customerlogin()
{
	global $app;
	$_POST = sanitizePostData($_POST);
	$username 	= 	strtolower(checkData($_POST['username']));
	$password 	= 	checkData($_POST['password']);
	if($username && $password)
	{			
		$customer 	= 	new Customer(0);
		$client		=	$customer->login($username, $password);
		if($client)
		{
			if($client['customer_status'] == 1)
			{
				session_destroy();
				session_start();
				$_SESSION['customer_id']				=	$client['customer_id'];
				$_SESSION['customer_name']				=	$client['customer_name'];
				$_SESSION['customer_fname']				=	$client['customer_fname'];
				$_SESSION['customer_lname']				=	$client['customer_lname'];
				$_SESSION['customer_type_id']			=	$client['customer_type_id'];
				$_SESSION['customer_type_name']			=	$client['customer_type_name'];
				$_SESSION['customer_email']				=	$client['customer_email'];
				$_SESSION['customer_phone']				=	$client['customer_phone'];
				$_SESSION['customer_image']				=	$client['customer_image'];
				$_SESSION['customer_is_email_verified']	=	$client['customer_is_email_verified'];
				$_SESSION['customer_is_mobile_verified']=	$client['customer_is_mobile_verified'];
				$_SESSION['customer_created_date']		=	$client['customer_created_date'];
				$_SESSION['app_theme']					= 	isset($client['customer_theme']) ? $client['customer_theme'] : 'default';
				$_SESSION['app_log_type']				=	'C';	
				$_SESSION['APP_ACCESS']					= true;			

				$customer 	=	new Customer($_SESSION['customer_id']);
				$customer->setLive();			

				$_SESSION['app_log_login_id'] = $app->logLogin();
				echo json_encode(array(200,"success|Login successfully. Redirecting to Panel..."));
			}
			else
				echo json_encode(array(300,"danger|Your account is not active."));

		}
		else
			echo json_encode(array(300,"danger|Inavalid Username or Password found."));
	}
	else
		echo json_encode(array(300,"danger|Username and Password must be filled."));

}

function forgotpassword()
{
	global $app;
	$customer_captcha_code_file_fpg_val = $forgot_email = $keyid = "";
	$data = sanitizePostData($_POST);
	extract($data);
	$forgot_email 	= 	strtolower(checkData($forgot_email));
	$keyid 			= 	checkData($keyid);	

	if(isset($_SESSION["CAPTCHA"]["CUSTOMER-FORGOT-PASSWORD"]) && $_SESSION["CAPTCHA"]["CUSTOMER-FORGOT-PASSWORD"]!="" && $_SESSION["CAPTCHA"]["CUSTOMER-FORGOT-PASSWORD"]==$customer_captcha_code_file_fpg_val)
	{
		if($forgot_email && $keyid)
		{
			if(md5(strtolower($_SERVER['SERVER_NAME'].$_SERVER['REMOTE_ADDR']."C"))==$keyid)	
			{	
				$customer 		= 	new Customer(0);
				if($customet_id	=	$customer->getIdByEmail(md5($forgot_email)))
				{
					$user = new Customer($customet_id);
					$data = $user->getDetails();
					$to_user = $data['customer_email'];
					$to_name = $data['customer_name'];
					$to_image = $app->basePath($data['customer_image']);
					$dataArray= array("user_name"=>$data['customer_fname'] ,"user_image"=>image($app->imagePath($data['customer_image']),80,true) , "email"=>$data['customer_email'], "password"=>$data['customer_password'], "login_page"=>$app->basePath("customer-login.php"));
				}
				else
				{
					echo json_encode(array(300,"danger|Unable to get customer account."));
					exit();
				}
			}
			elseif(md5(strtolower($_SERVER['SERVER_NAME'].$_SERVER['REMOTE_ADDR']."E"))==$keyid)
			{
				$employee 		= 	new Employee(0);
				if($user_id	=	$employee->getIdByEmail(md5($forgot_email)))
				{
					$user = new Employee($user_id);
					$data = $user->getDetails();	

					$to_user = $data['user_email'];
					$to_name = $data['user_name'];
					$to_image = $app->basePath($data['user_image']);
					$dataArray= array("user_name"=>$data['user_fname'] , "user_image"=>image($app->imagePath($data['user_image']),80,true) , "email"=>$data['user_email'], "password"=>$data['user_password'], "login_page"=>$app->basePath("login.php"));
				}
				else
				{
					echo json_encode(array(300,"danger|Unable to get user account."));
					exit();
				}
			}
			else
			{
				echo json_encode(array(300,"danger|No User found related this email."));
				exit();
			}

			$email = new Email("Forgot Password request on ".$app->siteName);
			$email->to($to_user, $to_name, $to_image)->template('forgotpassword', $dataArray)->send();
			echo json_encode(array(200,"success|We have send a forgot Password email to your registed email id. Please check your inbox/spam folder"));

		}
		else
			echo json_encode(array(300,"danger|Invalid Email or Attempt Found."));	
	}
	else
		echo json_encode(array("300","warning|Captcha Code not matched. Refresh Captcha and try again."));
}


function updatefeedback()
{	

	global $app;

	$feedback_likes = $feedback_title = $feedback_comments = $feedback_hashcode = "";

	$data = sanitizePostData($_POST);

	extract($data);

	if($feedback_hashcode !="")

	{

		$feedback = new Feedback();

		if($feedback_id = $feedback->isValidForSubmission($feedback_hashcode))
		{
			$updateFeed = new Feedback($feedback_id);
			$FeedBackData = $updateFeed->getDetails();
			$updateFeed->update( array(
										"feedback_star_rating" => $feedback_likes,
										"feedback_title" => $feedback_title,
										"feedback_comments" => $feedback_comments,
										"feedback_completion_date" => "NOW()",
										"feedback_ip_address" => IP_ADDRESS,
										"feedback_status" => 0,										
										"feedback_order_date" => date("Y-m-d H:i:s")
									   )
								);
			$store = new Store();
			$storeData = $store->getDetailsByKey($FeedBackData['feedback_store']);
			Activity::add("updated Feedback", "F", 0, $FeedBackData['feedback_customer_id'], "C");

			$email = new Email($app->siteName." : ". "Feedback success");

			$email->to($FeedBackData['customer_email'], $FeedBackData['customer_name'], $app->basePath($FeedBackData['customer_image']));

			$email->send("Thankyou for submitting Feedback on ".$app->siteName);

			echo json_encode(array("200","success|Thankyou !!!<br/>Feedback submitted Successfully.", $storeData['store_google_feedback_url']));

		}
		else
			echo json_encode(array("300","warning|Ooops.. Feedback url allready used or expired."));
	}
	else
		echo json_encode(array("300","danger|Ooops.. Feedback couldn't submitted. Try again."));
}



function customersignup()
{
	global $app;

	$customer_fname = $customer_lname = $customer_email = $customer_phone = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $customer_captcha_code = "";

	$customer_created_by = 1;
	$customer_address_status = 1;
	$customer_status = 1;
	$customer_password	=	gePassword();
	$customer_is_mobile_verified = 0;
	$customer_is_email_verified = 0;
	$customer_address_status	=	1;
	$customer_type_id = 0;

	$data = sanitizePostData($_POST);
	extract($data);
	
	if(isset($_SESSION["CAPTCHA"]["CUSTOMER-SIGNUP"]) && $_SESSION["CAPTCHA"]["CUSTOMER-SIGNUP"]!="" && $_SESSION["CAPTCHA"]["CUSTOMER-SIGNUP"]==$customer_captcha_code)
	{
		$customer_email = strtolower($customer_email);
		$Customer = new Customer(0);
		$customer_id = $Customer->isEmailExists($customer_email);
		if(!$customer_id)
		{
			unset($_SESSION["CAPTCHA"]["CUSTOMER-SIGNUP"]);
			$customer_image = DEFAULT_USER_IMAGE;	
			$customer_code = $Customer->getNewCode($customer_email, $customer_fname.$customer_lname);			

			$customer_id = $Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, '', $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);			

			$CustomerAddress = new CustomerAddress(0);
			$customer_address_id = $CustomerAddress->isCustomerAddressExists($customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);	

			if(!$customer_address_id)
			$customer_address_id = $CustomerAddress->add($customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country, $customer_address_postcode, $customer_address_geo_location, $customer_address_status);

			new SMS($customer_phone, "Hi $customer_fname, Welcome to ".$app->siteName.". Your account had been created with us successfully. Your username is \"$customer_email\" and Password is $customer_password");

			#==============================trig Email=============

			$activation_link = $app->basePath("activation.php?r=cst&u=$customer_email&l=".md5($customer_email.$customer_id)."&i=".md5($customer_id.$customer_email));

			$dataArray= array("customer_name"=>$customer_fname , "customer_email"=>$customer_email, "customer_password"=>$customer_password, "login_page"=>$app->basePath("customer-login.php"), "activation_link"=>$activation_link);

			$email = new Email("New Customer Account on ".$app->siteName);

			$email->to("$customer_email", $customer_fname, $app->basePath($customer_image))->template('customer_registration', $dataArray)->send();

			echo json_encode(array("200","success|Thankyou !!!<br/>Account created Successfully. Please Click on activation link on email received at (Inbox/Span) folder."));
		}
		else
			echo json_encode(array("300","warning|Ooops.. Couldn't create account./ Registered email allready exist. please login or reset password."));
	}
	else
		echo json_encode(array("300","warning|Captcha Code not matched. Refresh Captcha and try again."));

}

function qrcodelogin()
{
	global $app;	
	$scan_data = "";
	$android_app_logged_in_user_id = 0;
	$android_app_logged_in_session_id = "";
	$data = sanitizePostData($_POST);

	extract($data);

	if($android_app_logged_in_user_id && $scan_data!="")
	{			
		$scanData = explode("-", $scan_data);		
		if(count($scanData) == 4)
		{
			$remote_address 	= $scanData[0];
			$server_signature 	= $scanData[1];
			$session_id 		= $scanData[2];
			$login_secreat 		= $scanData[3];
			
			$employee 		= 	new Employee($android_app_logged_in_user_id);
			$user	=	$employee->getDetails();
	
			if($user)	
			{	
				if($user['user_status'] = 1)	
				{			
					if(session_id($session_id))
					{
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
						$_SESSION['app_log_type']			=	'E';	
						$_SESSION['user_allow_app']			=	$user['user_allow_app'];				
						$_SESSION['user_is_admin']			=	$user['user_is_admin'];	
						$_SESSION['user_fcm_token']			=	$user['user_fcm_token'];	
						$_SESSION['APP_ACCESS']				= true;			
		
						$employee->setLive();
						$_SESSION['app_log_login_id'] = $app->logLogin();	
						echo json_encode(array(200,"Loged in successfully", $session_id));
					}
					else
					echo json_encode(array(300,"Warning...Unable to find user. Please refresh web page."));
				}	
				else	
					echo json_encode(array(300,"Warning...Your account is not active."));	
			}	
			else	
				echo json_encode(array(300,"Error...Inavalid QR Code found"));
		}
		else
			echo json_encode(array(300,"Error...Unknown QR Code format"));
	}
	else
		echo json_encode(array(300,"Error...Restart App."));

}

function qrcodelogout()
{
	global $app;	
	$session_data = "";
	$android_app_logged_in_user_id = 0;
	$android_app_logged_in_session_id = "";
	$data = sanitizePostData($_POST);

	extract($data);

	if($android_app_logged_in_user_id && $session_data!="")
	{			
		$sessionData = explode("|", $session_data);
		$sessionData = array_filter($sessionData);		
		if(count($sessionData))
		{
			foreach($sessionData as $session)
			{
				if(session_id($session))
				session_destroy();
			}
			$message = count($sessionData). " web logged in account loged Out.";
			echo json_encode(array(200,"$message"));
		}
		else
			echo json_encode(array(200,"Opps...No account found to logout"));
	}
	else
		echo json_encode(array(200,"Opps...Error...Restart App."));

}



function unsubscribecustomer()
{	
	global $app;
	$subscribe_email = $subscribe_remark = $subscribe_resion = "";
	$data = sanitizePostData($_POST);
	extract($data);
	if($subscribe_email !="" && $subscribe_resion!="")
	{
		$uns = new Unsubscribe();
		$uns->add($subscribe_email, $subscribe_resion, $subscribe_remark, IP_ADDRESS);
			echo json_encode(array("200","success|Thankyou !!!<br/>Unsubscribtion submitted Successfully."));
	}
	else
		echo json_encode(array("300","danger|Ooops.. Unsubscribtion couldn't submitted. Try again."));
}


function checkmyaccount()
{
	echo isset($_SESSION['user_id']) ? 1 : 0;
}



?>
