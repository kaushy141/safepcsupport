<div class="appsettingformbox" id="appsettingformbox">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">Application Settings</div>
				<div class="card-block">
					<div class="row">
	<?php echo getEntitiyForm('possible_processor_name', 'textarea', 'Possible processors name');?>
	<?php echo getEntitiyForm('APP_PASSWORD_MIN_LENGTH', 'input', 'App min. password length');?>
	<?php echo getEntitiyForm('SYSTEM_EMAIL_SENDER', 'input', 'App Email Sender');?>
	<?php echo getEntitiyForm('SYSTEM_EMAIL_SENDER_CC', 'input', 'App Email keeping cc');?>
	<?php echo getEntitiyForm('SUCCESSFULL', 'input', 'Successful label html on completion');?>
	<?php echo getEntitiyForm('APP_EMPLOYER_NAME', 'input', 'Company default employer name');?>
	<?php echo getEntitiyForm('AJAX_REQUEST_MAX_TIME', 'input', 'Ternimate Ajax request after');?>
	<?php echo getEntitiyForm('FORMCODE_STRING_LENGTH', 'input', 'Form security code length');?>
	<?php echo getEntitiyForm('DEFAULT_FEEDBACK_STORE', 'input', 'System default feedback store');?>
	<?php echo getEntitiyForm('SALES_VAT_PERCENTAGE', 'input', 'App default sales VAT percent');?>
	<?php echo getEntitiyForm('MAX_IDLE_SESSION_TIMEOUT', 'input', 'User\'s maximum idle session timeout');?>
	<?php echo getEntitiyForm('ADMIN_USER', 'input', 'System\'s admin user');?>
	<?php echo getEntitiyForm('ADMIN_ROLE', 'input', 'System\'s Admin User role id');?>
	<?php echo getEntitiyForm('ACCOUNT_MANAGER', 'input', 'System\'s Account manager id');?>
	<?php echo getEntitiyForm('HR_MANAGER', 'input', 'System\'s HR manager id');?>
	<?php echo getEntitiyForm('APP_EMPLOYER_DOMAIN', 'input', 'System\'s employer default domain');?>
	<?php echo getEntitiyForm('COMPANY_NAME', 'input', 'System\'s default company name');?>	
	<?php echo getEntitiyForm('CURRENCY', 'input', 'System\'s default currency code');?>
	<?php echo getEntitiyForm('SIGNATURE_LINK_VALIDITY_TIME', 'input', 'Signature request validity time');?>
	<?php echo getEntitiyForm('PER_DAY_REFUND_LIMIT', 'input', 'Per day refund limit for a single user');?>
	<?php echo getEntitiyForm('MAX_SALARY_REGISTER_VIEW', 'input', 'Salary register month view limit');?>	
	
	<?php echo getEntitiyForm('GOOGLE_RECAPTCHA_PUBLIC_KEY', 'input', 'Google Captcha public Key');?>	
	<?php echo getEntitiyForm('GOOGLE_RECAPTCHA_PRIVATE_KEY', 'input', 'Google Captcha private Key');?>	
	<?php echo getEntitiyForm('GOOGLE_MAP_API_KEY', 'input', 'Google Map API Key');?>	
	
	<?php echo getEntitiyForm('GOOGLE_FIREBASE_API_KEY', 'input', 'Google Firebase API Key');?>	
	<?php echo getEntitiyForm('GOOGLE_FIREBASE_API_AUTH_DOMAIN', 'input', 'Google Firebase API aouth domain');?>	
	<?php echo getEntitiyForm('GOOGLE_FIREBASE_API_DATABASE_URL', 'input', 'Google Firebase API datatbase url');?>	
	<?php echo getEntitiyForm('GOOGLE_FIREBASE_API_PROJECT_ID', 'input', 'Google Firebase API project Id');?>	
	<?php echo getEntitiyForm('GOOGLE_FIREBASE_API_STORAGE_BUCKET', 'input', 'Google Firebase API storage bucket');?>	
	<?php echo getEntitiyForm('GOOGLE_FIREBASE_API_MESSAGING_SENDER_ID', 'input', 'Google Firebase API messaging sender Id');?>	
	<?php echo getEntitiyForm('GOOGLE_FIREBASE_API_APP_ID', 'input', 'Google Firebase API APP Id');?>	
	<?php echo getEntitiyForm('GOOGLE_FIREBASE_API_MEASUREMENT_ID', 'input', 'Google Firebase API measurement Id');?>
	
	<?php echo getEntitiyForm('GOOGLE_FIREBASE_API_URL', 'input', 'Google Firebase API url');?>
	<?php echo getEntitiyForm('GOOGLE_FIREBASE_API_AUTHERISATION_KEY', 'input', 'Google Firebase autherisation Key');?>
	
	<?php echo getEntitiyForm('SPD_GOOGLE_REVIEW_LINK', 'input', 'Default site SPD Google review link');?>
	
	<?php echo getEntitiyForm('UPS_DEVL_URL', 'input', 'UPS Shipping development url');?>
	<?php echo getEntitiyForm('UPS_PROD_URL', 'input', 'UPS Shipping production url');?>
		
	<?php echo getEntitiyForm('DPD_DEVL_URL', 'input', 'DPD Shipping development url');?>
	<?php echo getEntitiyForm('DPD_PROD_URL', 'input', 'DPD Shipping production url');?>
	
	<?php echo getEntitiyForm('FEDEX_DEVL_URL', 'input', 'FEDEX Shipping development url');?>
	<?php echo getEntitiyForm('FEDEX_PROD_URL', 'input', 'FEDEX Shipping production url');?>
	
	<?php echo getEntitiyForm('ENABLE_JS_CSS_CACHE', 'checkbox', 'Enable JS/CSS cache');?>
	<?php echo getEntitiyForm('URL_RETURN_ALLOWED', 'checkbox', 'Allow URL return');?>
	<?php echo getEntitiyForm('ALLOW_ANDROID_USER_RE_SESSION', 'checkbox', 'Allow andorid user re-session');?>
	<?php echo getEntitiyForm('ENABLE_LOGIN_CAPTCHA', 'checkbox', 'Enabale login captcha code');?>	
	<?php echo getEntitiyForm('ENABLE_LOGIN_EMAIL_VERIFICATION_CHECK', 'checkbox', 'Verify email verification on login');?>
	<?php echo getEntitiyForm('ENABLE_LOGIN_MOBILE_VERIFICATION_CHECK', 'checkbox', 'Verify mobile verification on login');?>
	<?php echo getEntitiyForm('LIVE_CHAT_ENABLE_STATUS', 'checkbox', 'Enabale live chat on system');?>
	<?php echo getEntitiyForm('ENABLE_NEWPAY_PENDING_ORDER', 'checkbox', 'Enable fetch newpay finance pending order');?>
					</div>
				</div>
			</div>
		</div>
	</div>
 </div>       
        