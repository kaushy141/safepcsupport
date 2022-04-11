<?php 
include("../../setup.php");
//ini_set('display_errors', 1);
error_reporting(0);
extract(App::exploreApiCredentilas('AmazonOrderApi'));
require_once('Model/.config.inc.php');
require_once('Client.php');
$orderId = (isset($_POST['order_id']) && !is_array($_POST['order_id'])) ? trim($_POST['order_id']) : '';
$serviceUrl = "https://mws-eu.amazonservices.com/Orders/2013-09-01";

 $config = array (
   'ServiceURL' => $serviceUrl,
   'ProxyHost' => null,
   'ProxyPort' => -1,
   'ProxyUsername' => null,
   'ProxyPassword' => null,
   'MaxErrorRetry' => 3,
 );

 $service = new MarketplaceWebServiceOrders_Client(
        AWS_ACCESS_KEY_ID,
        AWS_SECRET_ACCESS_KEY,
        APPLICATION_NAME,
        APPLICATION_VERSION,
        $config);

 $request = new MarketplaceWebServiceOrders_Model_GetOrderRequest();
 $request->setSellerId(MERCHANT_ID);
 $request->setAmazonOrderId($orderId);
 
 invokeGetOrder($service, $request);

function invokeGetOrder(MarketplaceWebServiceOrders_Interface $service, $request)
  {
	  $productData = array();
      try {
        $response = $service->GetOrder($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $xmlData = $dom->saveXML();
		$productData = parseXmlToArray($xmlData);
		if(isset($productData['GetOrderResult']['Orders']['Order']))
		{
			$order = $productData['GetOrderResult']['Orders']['Order'];
			if($successImportOrder = processOrder($service, sanitizePostData($order)))
				echo json_encode(array("200",  "success|$successImportOrder Amazon Order imported successfully."));			
			else
				echo json_encode(array("300",  "success|Amazon Order status is $order[OrderStatus]"));
		}
		else
			echo json_encode(array("200",  "success|No new Amazon Order found."));

     } catch (MarketplaceWebServiceOrders_Exception $ex) {
        $errMsg = "Caught Exception: " . $ex->getMessage() . "<br/>"."Response Status Code: " . $ex->getStatusCode() . "<br/>"."Error Code: " . $ex->getErrorCode() . "<br/>"."Error Type: " . $ex->getErrorType() . "<br/>"."Request ID: " . $ex->getRequestId() . "<br/>"."XML: " . $ex->getXML() . "<br/>"."ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "<br/>";
		echo json_encode(array("300",  "warning|Amazon Order errror .$errMsg"));	
     }
 }
?>