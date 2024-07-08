<?php

   /************************************************************************
    * REQUIRED
    *
    * Access Key ID and Secret Acess Key ID, obtained from:
    * http://aws.amazon.com
    ***********************************************************************/
    define('AWS_ACCESS_KEY_ID', "$AWSAccessKeyId");
    define('AWS_SECRET_ACCESS_KEY', "$ClientSecret");

   /************************************************************************
    * REQUIRED
    *
    * All MWS requests must contain a User-Agent header. The application
    * name and version defined below are used in creating this value.
    ***********************************************************************/
    define('APPLICATION_NAME', "$ApplicationName");
    define('APPLICATION_VERSION', "$ApplicationVersion");

   /************************************************************************
    * REQUIRED
    *
    * All MWS requests must contain the seller's merchant ID and
    * marketplace ID.
    ***********************************************************************/
    define ('MERCHANT_ID', "$SellerId");
    define ('MARKETPLACE_ID', "$MarketplaceId1");


   /************************************************************************
    * OPTIONAL ON SOME INSTALLATIONS
    *
    * Set include path to root of library, relative to Samples directory.
    * Only needed when running library from local directory.
    * If library is installed in PHP include path, this is not needed
    ***********************************************************************/
    set_include_path(get_include_path() . PATH_SEPARATOR . '../.');

   /************************************************************************
    * OPTIONAL ON SOME INSTALLATIONS
    *
    * Autoload function is reponsible for loading classes of the library on demand
    *
    * NOTE: Only one __autoload function is allowed by PHP per each PHP installation,
    * and this function may need to be replaced with individual require_once statements
    * in case where other framework that define an __autoload already loaded.
    *
    * However, since this library follow common naming convention for PHP classes it
    * may be possible to simply re-use an autoload mechanism defined by other frameworks
    * (provided library is installed in the PHP include path), and so classes may just
    * be loaded even when this function is removed
    ***********************************************************************/
     function __autoload($className){
        $filePath = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $includePaths = explode(PATH_SEPARATOR, get_include_path());
        foreach($includePaths as $includePath){
            if(file_exists($includePath . DIRECTORY_SEPARATOR . $filePath)){
                require_once $filePath;
				echo $filePath."<br/>";
                return;
            }
        }
    }

	$includePaths = scandir('Model');
	$includePaths = array_slice($includePaths, 3);
	foreach($includePaths as $includePath){
		
			require_once $includePath;
			//echo $includePath."<br/>";
			
	   
	}	
	
	function processOrder($service, $order, $successImportOrder=0)
	{
	    global $MWSAuthToken;
		if($order['OrderStatus'] != "Canceled" && $order['OrderStatus'] != "Pending")
		{
			$aouth = md5('www.amazon.ae');
			$icon 						= getFcmMessageIcon($aouth);
			$web_order_website_id 		= getWebsiteIdByAouth($aouth);
			$web_order_website_id 		= $web_order_website_id ? $web_order_website_id : 17;						
			
			$web_order_number 			= isset($order['AmazonOrderId'])	? getData($order['AmazonOrderId'])		:	'N/A';
			
			$websiteOrder =  new WebsiteOrder();
			$isOrderExist = $websiteOrder->isOrderExist($web_order_number, $web_order_website_id);
				
			if(!$isOrderExist)
			{
				$request = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
				$request->setSellerId(MERCHANT_ID);
				$request->setAmazonOrderId($web_order_number);
				$request->setMWSAuthToken($MWSAuthToken);
				$productData = array();
				$web_order_total_shipping = 0;
				$web_order_currency 		= isset($order['OrderTotal']['CurrencyCode'])	? getData($order['OrderTotal']['CurrencyCode'])		:	'XXX';	
				$products = invokeListOrderItems($service, $request);
				//print_r($products);die;
				if($products)
				{
					if(count($products))
					{
					    $productDataArray = isset($products[0][0]) ? $products[0] : $products;
						foreach($productDataArray as $_product)
						{
<<<<<<< HEAD
							$productData[] = array(
								"wo_product_name" 			=> getData($_product['Title']),
								"wo_product_sku" 			=> getData($_product['SellerSKU']),
								"wo_product_quantity" 		=> 1,
								"wo_product_sell_price" 	=> getData($_product['ItemPrice']['Amount']/$_product['QuantityOrdered']),
								"wo_product_sell_base_price" => getBaseAmount(getData($_product['ItemPrice']['Amount']/$_product['QuantityOrdered']), $web_order_currency),
								"wo_product_image"			=> AMAZON_PRODUCT_IMAGE,
								"wo_product_premium"		=> (isset($order['IsPremiumOrder']) && $order['IsPremiumOrder'] == true) ? 1 : 0,
								"wo_process_code"			=> ""
							);
=======
						    for($i=0; $i< $_product['QuantityOrdered']; $i++)
						    {
    							$productData[] = array(
    								"wo_product_name" 			=> getData($_product['Title']),
    								"wo_product_sku" 			=> getData($_product['SellerSKU']),
    								"wo_product_quantity" 		=> 1,
    								"wo_product_sell_price" 	=> getData($_product['ItemPrice']['Amount']/$_product['QuantityOrdered']),
    								"wo_product_sell_base_price" => getBaseAmount(getData($_product['ItemPrice']['Amount']/$_product['QuantityOrdered']), $web_order_currency),
    								"wo_product_image"			=> AMAZON_PRODUCT_IMAGE,
    								"wo_product_premium"		=> (isset($order['IsPremiumOrder']) && $order['IsPremiumOrder'] == true) ? 1 : 0,
    								"wo_process_code"			=> ""
    							);
						    }
>>>>>>> 77a717f (Version 2)
							$web_order_total_shipping += isset($_product['ShippingPrice']) ? $_product['ShippingPrice']['Amount']:0;
						}
					}
				}
				if(count($productData))
				{							
					$amazon_address = $order['ShippingAddress'];
					$customer_fname = isset($amazon_address['Name']) ? substr($amazon_address['Name'], 0, strpos($amazon_address['Name'], ' ')) : "Amazon";
					$customer_lname = isset($amazon_address['Name']) ? substr($amazon_address['Name'], strpos($amazon_address['Name'], ' ')) : "User";
					$customer_email = $order['BuyerEmail'];
					$customer_phone = '';
					$customer_company = '';
					$customer_address_postcode = $amazon_address['PostalCode'];
					$customer_address_street_number = isset($amazon_address['AddressLine1']) ? $amazon_address['AddressLine1'] : "N/A";
					$customer_address_route = "";
					$customer_address_locality = isset($amazon_address['City']) ? $amazon_address['City'] : "N/A";
					$customer_address_administrative_area = isset($amazon_address["StateOrRegion"]) ? $amazon_address["StateOrRegion"] : "N/A";
					$customer_address_country = Country::getValueByParameter('name', 'iso2', $amazon_address['CountryCode']);
					$customer_address_geo_location = "";
					$web_order_total_amount 	= isset($order['OrderTotal']['Amount'])		? getData($order['OrderTotal']['Amount'])		:	0;
					
					
					$web_order_payment_method 	= isset($order['PaymentMethod'])	? getData($order['PaymentMethod'])		:	'N/A';
					
					$web_order_is_paid 			= isset($order['ISPAID'])	? getData($order['ISPAID'])		:	'Yes';
											
					$web_order_total_quantity 	= $order['NumberOfItemsShipped'] + $order['NumberOfItemsUnshipped'];
					$web_order_status = STATUS_PENDING;
					$web_order_created_date = date('Y-m-d H:i:s', strtotime($order['PurchaseDate']));						
					
					if($customer_email == "")
						$customer_email = $customer_phone != "" ? $customer_phone : 'anonymous-'.time();
					$customer_email = strtolower($customer_email);
					$Customer        = new Customer(0);
					$web_order_customer_id  = $Customer->isEmailExists($customer_email);
					if (!$web_order_customer_id) {
						$customer_image  = DEFAULT_USER_IMAGE;
						$customer_type_id = 7;
						$customer_status = 1;
						$customer_password    = gePassword();
						$customer_is_mobile_verified = 0;
						$customer_is_email_verified  = 0;
						$customer_created_by = ADMIN_USER;
						$customer_address_status     = 1;
						
						$customer_code   = $Customer->getNewCode($customer_email, $customer_fname . $customer_lname);
						$web_order_customer_id  = $Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
					} 
					
					$CustomerAddress = new CustomerAddress(0);
						$web_order_address_id = $CustomerAddress->isCustomerAddressExists($web_order_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);
						if (!$web_order_address_id)
							$web_order_address_id = $CustomerAddress->add($web_order_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country, $customer_address_postcode, $customer_address_geo_location, $customer_address_status);								
					
					
					$web_order_id = $websiteOrder->insert(array(
						"web_order_customer_id" => $web_order_customer_id,
						"web_order_address_id" => $web_order_address_id,
						"web_order_total_amount" => $web_order_total_amount,
						"web_order_total_shipping" => $web_order_total_shipping,
						"web_order_total_quantity" => $web_order_total_quantity,
						"web_order_is_paid"	=>$web_order_is_paid,
						"web_order_currency" => $web_order_currency,
						"web_order_payment_method" => $web_order_payment_method,
						"web_order_website_id" => $web_order_website_id,
						"web_order_number" => $web_order_number,
						"web_order_created_date" => $web_order_created_date,
						"web_order_status" => $web_order_status
						)
					);
					
					
					foreach($productData as $product)
					{
						$websiteOrderProduct = new WebsiteOrderProduct();
						$product['wo_web_order_id']	= 	$web_order_id;
						for($i=0; $i< $product['wo_product_quantity']; $i++)
						{								
							$websiteOrderProduct->insert($product);
						}
					}
					$successImportOrder++;
				}
			}
		}
		return $successImportOrder;
	}
	
function invokeListOrderItems(MarketplaceWebServiceOrders_Interface $service, $request)
  {
	  $productArray = array();			
      try 
	  {
        $response = $service->ListOrderItems($request);
        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $xmlData = $dom->saveXML();
        $productData = parseXmlToArray($xmlData);
        //print_r($productData);
		if(isset($productData['ListOrderItemsResult']['OrderItems']['OrderItem']))
		{
			$productArray[] = $productData['ListOrderItemsResult']['OrderItems']['OrderItem'];
		}
     } catch (MarketplaceWebServiceOrders_Exception $ex) {
        return false;
     }	 
	return $productArray;
 }

