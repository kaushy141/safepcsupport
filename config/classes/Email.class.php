<?php
  class Email extends DB{
	public $header;
	public $subject;
	public $body;
	public $footer;
	public $bcc = null;
	public $cc = SYSTEM_EMAIL_SENDER_CC;
	public $to = array();
	public $attachement = array();
	public $template="";
	public $toImage = "";
	public $user_name = "";
	public $unsubscriber = "";
	public $emailLogo = "";
	public $storeName = "";
	
	function __construct($subject){
		global $app;
		$this->subject = $subject;	
		$this->emailLogo = $app->imagePath('img/spd-logo-mail-white.png');
		$this->storeName = "SafePcDisposal";
		return $this;
	}
	
	public function to($to, $name=NULL, $image=NULL){
		global $app;
		$this->unsubscriber = $to;
		array_push($this->to, array("email_id"=>$to, "name"=>$name));
		if($this->toImage=="" && $image == NULL)
		{
			if($toImage = $app->getuserEmailImageByEmail($to))
			$this->toImage = $toImage;
		}
		elseif($image!=NULL)
		$this->toImage = $app->imagePath($image);
		
		if($name != NULL)
		$this->user_name = $name;
		return $this;
	}
	
	function setEmailLogo($logo)
	{
		global $app;
		$this->emailLogo = $app->imagePath($logo);
		return $this;
	}
	function setStoreName($name)
	{
		$this->storeName = $name;
		return $this;
	}
	
	public function cc($cc){
		$this->cc .= $cc;
		return $this;
	}
	
	public function bcc($bcc){
		$this->bcc .= $bcc;
		return $this;
	}
	
	public function body($body)
	{
		$this->body .= $body;
		return $this;
	}
	
	public function footer($footer)
	{
		$this->footer .= $footer;
		return $this;
	}
	
	public function subject($subject)
	{
		$this->subject .= $subject;
		return $this;
	}
	
	public function addFile($filename, $name=NULL)
	{
		array_push($this->attachement, array("filename"=>$filename, "name"=>$name));
		return $this;  
	}
	
	public function send($message = NULL)
	{
		global $app;
		$mail = new PHPMailer;
		
		if($message!=NULL && $this->template=="")
		$this->template("default", array("message"=>$message));	
		
		if(!empty($this->to))
		{
			foreach($this->to as $receipients)
			$mail->addAddress($receipients['email_id'], $receipients['name']);	
		}
		
		if(!empty($this->attachement))
		{
			$app_access = isset($_SESSION['APP_ACCESS']) ? $_SESSION['APP_ACCESS'] : 'NOTSET';
			$_SESSION['APP_ACCESS'] = 1;
			foreach($this->attachement as $file)
			{
				if (filter_var($file['filename'], FILTER_VALIDATE_URL))
				{
					$attachment_name = $file['name']!=NULL?$file['name']:(substr($this->subject,0,40).".pdf");
					$mail->AddStringAttachment(file_get_contents($file['filename']), $attachment_name, $encoding = 'base64', $type = 'application/pdf');
				}
				else
				{
					$attachment_name = $file['name']!=NULL?$file['name']:basename($file['filename']);					
					$mail->addAttachment($file['filename'], $attachment_name);
				}
			}
			if($app_access == 'NOTSET')
				unset($_SESSION['APP_ACCESS']);
			else
				$_SESSION['APP_ACCESS'] = $app_access;
		}	
		
		if(empty($this->to) && empty($mail->getAllRecipientAddresses())):
			if(getUserType()=="E")
			{
				$mail->addAddress($_SESSION['user_email'], $_SESSION['user_name']);	
				$this->toImage = $app->imagePath($_SESSION['user_image']);
			}
			else
			{
				$mail->addAddress($_SESSION['customer_email'], $_SESSION['customer_name']);	
				$this->toImage = $app->imagePath($_SESSION['customer_image']);
			}
		endif;
		
		
			$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465; // or 587
			$mail->IsHTML(true);
			$mail->Username = "support@safepcsupport.co.uk";
			$mail->Password = "Support#567890";


			$mail->setFrom(SYSTEM_EMAIL_SENDER, $app->siteName);		
			$mail->Subject = $this->subject;
			$mail->Body = $this->body;
			$mail->AltBody = strip_tags($this->body);	
			$mail->addCC($this->cc);
			if($this->bcc != null)
			$mail->addBCC($this->bcc);
			$mail->send();
			
		
	}
	
	public function template($Templatename, $dataArray)
	{
		global $app;
		$this->template = $Templatename;
		if(empty($this->to) && isset($_SESSION['user_email']))
		$user_name = getUserType()=="E"?$_SESSION['user_name']:$_SESSION['customer_name'];
		else
		$user_name = $this->user_name;
		extract($dataArray);		
		$template_path = BP."/template/".$Templatename.".txt";
		if(file_exists($template_path))
		$template = file_get_contents($template_path);
		$pattern = '#{{(.*?)}}#';
		preg_match_all($pattern,$template,$matches);
		//print_r($matches);
		if(count($matches[0]))
		foreach($matches[0] as $variable)
		{
			$template = str_replace($variable, "$".trim(trim(trim($variable,"{"),"}")), $template);
		}		
		eval("\$data = \"$template\";");
		$baseUrl = $app->siteUrl;
		//$bgColor = getColor();
		$bgColor = "#81D4FA";
		$bodyHtml = '<div style="background-color:#f7f7f7;font:11px Helvetica,Arial,sans-serif;color:#000000"><div style="background-color:#ffffff;width:100%;margin:0;padding:0"><table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td><div style="width:100%; min-height:700px; background:'.$bgColor.'; text-align:center"><table style="width:100%"><tbody><tr><td colspan="3" align="middle" height="50px" style="vertical-align:bottom; padding:10px 0px;"><img width="200px" alt="'.$this->storeName.'" height="46px" src="'.$this->emailLogo.'" > </td></tr><tr><td><div style="max-width:600px; min-width:270px; min-height:500px; margin:0 auto;padding:20px 20px; background-color:#ffffff; border-radius:4px; margin-bottom:50px "><table width="100%"><tbody><tr><td><table style="max-width:600px;border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto" cellspacing="0" cellpadding="0" width="100%" border="0" align="center"><tbody><tr><td valign="top"><h3 style="font-size:30px;font-family:OpenSans,sans-serif;font-weight:bold;text-align:center">'.$this->storeName.' Support System</h3></td></tr><tr><td align="center" style="font-size:16px;line-height:25px;font-family:OpenSans,sans-serif;color:#393939"><div style="width:100%"><center><div style="clear:both;width:160px;min-height:50px; max-height:160px;"><img width="100%" style="border-radius:50%;" src="'.$this->toImage.'" alt="'.$user_name.'"></div></center></div>'.$data.'<br> Happy to serve you Support<br> <br><center><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td width="18px" height="40px">&nbsp;</td><td style="background:#fa6484;color:#ffffff;font-size:13px;font-family:OpenSans,sans-serif;font-weight:bold; border-radius:20px;"> <a style="text-decoration:none; color:#FFF; padding:10px 20px;" href="'.$baseUrl.'dashboard" >Show my Dashboard</a></td><td width="18px" height="40px">&nbsp;</td></tr></tbody></table></center></td></tr><tr><td align="center" style="font-size:13px;font-family:OpenSans,sans-serif;line-height:1.54;color:#393939"> SafePcSupport, Leading Support System of E World UK Group </td></tr><tr><td align="center" style="font-size:10px;font-family:OpenSans,sans-serif;line-height:2;color:#393939"> The '.$this->storeName.' support team<br/><p style="font-size:8px;">If you are getting unwanted emails click <a href="'.getUnSubscribeUrl($this->unsubscriber).'">unsubscribe</a></p></td></tr></tbody></table></td></tr></tbody></table></div></td></tr></tbody></table></div></td></tr><tr><td colspan="3" align="center" style="background:#f5f6f6"></td></tr><tr><td colspan="3" height="40px" style="background:#f5f6f6">&nbsp;</td></tr></tbody></table></div></div>';
		$this->body =  nl2br($bodyHtml);
		//echo $this->body;
		return $this;
	}
	
	function setCrmTemplate($template, $dataArray)
	{		
		global $app;
		$this->template = "blank";
		if(empty($this->to) && isset($_SESSION['user_email']))
		$user_name = getUserType()=="E"?$_SESSION['user_name']:$_SESSION['customer_name'];
		else
		$user_name = $this->user_name;
		extract($dataArray);
		$pattern = '#{{(.*?)}}#';
		preg_match_all($pattern,$template,$matches);
		if(count($matches[0]))
		foreach($matches[0] as $variable)
		{
			$template = str_replace($variable, "$".trim(trim(trim($variable,"{"),"}")), $template);
		}		
		eval("\$data = \"$template\";");
		$baseUrl = $app->siteUrl;
		$bgColor = getColor();
		$bodyHtml = '<div style="background-color:#f7f7f7;font:11px Helvetica,Arial,sans-serif;color:#000000"><div style="background-color:#ffffff;width:100%;margin:0;padding:0"><table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td><div style="width:100%; min-height:700px; background:.$bgColor.; text-align:center"><table style="width:100%"><tbody><tr><td colspan="3" align="middle" height="50px" style="vertical-align:bottom; padding:10px 0px;"><img width="142px" alt="safePcSupport" height="46px" src="'.$baseUrl.'img/spd-logo-mail-white.png" > </td></tr><tr><td><div style="max-width:600px; min-width:270px; min-height:500px; margin:0 auto;padding:20px 20px; background-color:#ffffff; border-radius:4px; margin-bottom:50px "><table width="100%"><tbody><tr><td><table style="max-width:8000px;border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto" cellspacing="0" cellpadding="0" width="100%" border="0" align="center"><tbody><tr><td valign="top"><h3 style="font-size:30px;font-family:OpenSans,sans-serif;font-weight:bold;text-align:center">SafePcDisposal Support System</h3></td></tr><tr><td align="center" style="font-size:16px;line-height:25px;font-family:OpenSans,sans-serif;color:#393939"><div style="width:100%"><center><div style="clear:both;width:160px;min-height:50px; max-height:160px;"><img width="100%" style="border-radius:50%;" src="'.$this->toImage.'" alt="'.$user_name.'"></div></center></div>'.$data.'<br> Happy to serve you Support<br> <br><center><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td width="18px" height="40px">&nbsp;</td><td style="background:#fa6484;color:#ffffff;font-size:13px;font-family:OpenSans,sans-serif;font-weight:bold; border-radius:20px;"> <a style="text-decoration:none; color:#FFF; padding:10px 20px;" href="'.$baseUrl.'dashboard" >Show my Dashboard</a></td><td width="18px" height="40px">&nbsp;</td></tr></tbody></table></center></td></tr><tr><td align="center" style="font-size:13px;font-family:OpenSans,sans-serif;line-height:1.54;color:#393939"> SafePcSupport, Leading Support System of E World UK Group </td></tr><tr><td align="center" style="font-size:10px;font-family:OpenSans,sans-serif;line-height:2;color:#393939"> The safePcDisposal support team<br/><p style="font-size:8px;">If you are getting unwanted emails click <a href="'.getUnSubscribeUrl($this->unsubscriber).'">unsubscribe</a></p></td></tr></tbody></table></td></tr></tbody></table></div></td></tr></tbody></table></div></td></tr><tr><td colspan="3" align="center" style="background:#f5f6f6"></td></tr><tr><td colspan="3" height="40px" style="background:#f5f6f6">&nbsp;</td></tr></tbody></table></div></div>';
		$this->body =  nl2br($bodyHtml);
		//echo $this->body;
		return $this;
	
	}
}
?>