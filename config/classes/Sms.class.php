<?php
class SMS{	
	public $mobile_number	=	"complaint_status_id";
	public $message	=	"app_complaint_status_master";
	function __construct($mobile_number, $message){
				
		$this->mobile_number	=	$mobile_number;
		$this->message	=	$message;
		$this->send();
	}
	
	
	function send()
	{
			
	}
}
?>