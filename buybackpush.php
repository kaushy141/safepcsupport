<?php include("setup.php"); ?>
<?php
Modal::load(array('BuybackOrder'));
$DeviceManager = new DeviceManager();
$firebaseIds = $DeviceManager->getAllUserDevice();
$registration_ids = explode("," , $firebaseIds);

$DATA = sanitizePostData($_POST);
Log::Text(json_encode($DATA));
$aouth		= isset($DATA['AOUTH'])	? getData($DATA['AOUTH'])	:	NULL;

$icon 						= getFcmMessageIcon($aouth);
$website_id 		= getWebsiteIdByAouth($aouth);

if($aouth!=NULL && (in_array($DATA['AOUTH'], 
	array(
				md5("www.mac4sale.co.uk"), 
				md5("www.consolekiller.co.uk"), 
				md5("www.technocart.co.uk"), 
				md5("www.refurbmac.co.uk"), 
				md5("www.safepcdirect.co.uk"), 
				md5("www.consolekillerpc.co.uk"),
				md5("www.hotspotmobiles.com"),
				md5("www.mac4sale.ae")
		)
)))
{
	$customer_fname = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = "";
	
	$customerName = explode(" ", $DATA['BUYBACK']['customer_name'],2);
	
	$customer_fname 	= $customerName[0];
	$customer_lname 	= isset($customerName[1])		? $customerName[1]		:	"";
	$customer_email 	= $DATA['BUYBACK']['customer_email'];
	$customer_phone 	= $DATA['BUYBACK']['customer_mobile'];
	$customer_company 	= isset($DATA['BUYBACK']['customer_company']) ? $DATA['BUYBACK']['customer_company'] : "";
	
	$customer_address = isset($DATA['BUYBACK']['customer_address'])	? $DATA['BUYBACK']['customer_address']	:	NULL;
	$buybackOrder =  new BuybackOrder();
	extract($DATA['BUYBACK']);
	if(!$buybackOrder->isOrderExist($order_number, $website_id))
	{
		$customer_email = strtolower($customer_email);
		$Customer        = new Customer(0);
		$customer_id  = $Customer->isEmailExists($customer_email);
		if (!$customer_id) {
			$customer_image  = DEFAULT_USER_IMAGE;
			$customer_type_id = WEBSITE_CUSTOMER;
			$customer_status = 1;
			$customer_password    = gePassword();
			$customer_is_mobile_verified = 0;
			$customer_is_email_verified  = 0;
			$customer_created_by = ADMIN_USER;
			$customer_address_status     = 1;
			
			$customer_code   = $Customer->getNewCode($customer_email, $customer_fname . $customer_lname);
			$customer_id  = $Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
		} 
		$buybackData = array(
                    'customer_id' => $customer_id, 
                    'website_id' => $website_id,
                    'order_number' => $order_number, 
                    'customer_name' => $customer_name, 
                    'customer_mobile' => $customer_mobile, 
                    'customer_email' => $customer_email, 
                    'customer_address' => $customer_address, 
                    'product_sku' => $product_sku, 
					'product_url' => $product_url, 
					'product_image' => $product_image, 
<<<<<<< HEAD
                    'product_name' => $product_name, 
=======
                    'product_name' => $product_name,
                    'product_specification' => $product_specification,
>>>>>>> 77a717f (Version 2)
                    'product_quantity' => $product_quantity, 
                    'buy_price' => $buy_price, 
                    'buy_currency' => $buy_currency, 
                    'base_price' => $base_price, 
                    'base_currency' => $base_currency, 
                    'payout_method' => $payout_method, 
                    'payout_detail' => $payout_detail, 
                    'product_packging' => $product_packging, 
                    'status' => $status, 
                    'created_at' => 'NOW()'
				);
		$buyback_order_id = $buybackOrder->insert($buybackData);		
	}
}
?>