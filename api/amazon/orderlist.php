<?php 
include("../../setup.php");
<<<<<<< HEAD
//ini_set('display_errors', 1);
=======
ini_set('display_errors', 1);
>>>>>>> 77a717f (Version 2)
error_reporting(0);
extract(App::exploreApiCredentilas('AmazonOrderApi'));
require_once('Model/.config.inc.php');
require_once('Client.php');

$serviceUrl = "https://mws-eu.amazonservices.com/Orders/2013-09-01";

$CreatedAfter	= (isset($_GET['t']) && $_GET['t'] !='')? gmdate('Y-m-d\TH:i:s', strtotime($_GET['t'])) : gmdate("Y-m-d\TH:i:s\Z", strtotime(' -2 day'));
 $config = array (
   'ServiceURL' => $serviceUrl,
   'ProxyHost' => null,
   'ProxyPort' => -1,
   'ProxyUsername' => null,
   'ProxyPassword' => null,
   'MaxErrorRetry' => 3,
<<<<<<< HEAD
=======
   'x-amz-access-token' => 'amzn1.oa2-cs.v1.79ee57f2e6778754a5f51faeb9f5fe09ac0358525202435515b9dfeae7c8016d'
>>>>>>> 77a717f (Version 2)
 );

 $service = new MarketplaceWebServiceOrders_Client(
        AWS_ACCESS_KEY_ID,
        AWS_SECRET_ACCESS_KEY,
        APPLICATION_NAME,
        APPLICATION_VERSION,
        $config);

/************************************************************************
 * Uncomment to try out Mock Service that simulates MarketplaceWebServiceOrders
 * responses without calling MarketplaceWebServiceOrders service.
 *
 * Responses are loaded from local XML files. You can tweak XML files to
 * experiment with various outputs during development
 *
 * XML files available under MarketplaceWebServiceOrders/Mock tree
 *
 ***********************************************************************/
 // $service = new MarketplaceWebServiceOrders_Mock();

/************************************************************************
 * Setup request parameters and uncomment invoke to try out
 * sample for List Orders Action
 ***********************************************************************/
 // @TODO: set request. Action can be passed as MarketplaceWebServiceOrders_Model_ListOrders
 $request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
 $request->setSellerId(MERCHANT_ID);
 $request->setLastUpdatedAfter($CreatedAfter);
 $request->setMarketplaceId(MARKETPLACE_ID);
 // object or array of parameters
 invokeListOrders($service, $request);

/**
  * Get List Orders Action Sample
  * Gets competitive pricing and related information for a product identified by
  * the MarketplaceId and ASIN.
  *
  * @param MarketplaceWebServiceOrders_Interface $service instance of MarketplaceWebServiceOrders_Interface
  * @param mixed $request MarketplaceWebServiceOrders_Model_ListOrders or array of parameters
  */

  function invokeListOrders(MarketplaceWebServiceOrders_Interface $service, $request)
  {
      try 
	  {
        $response = $service->ListOrders($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
		$xmlData = $dom->saveXML();
        $orderData = parseXmlToArray($xmlData);
<<<<<<< HEAD
=======
		  
>>>>>>> 77a717f (Version 2)
		$successImportOrder = 0;
		if(isset($orderData['ListOrdersResult']['Orders']['Order']))
		{
			$orderList = $orderData['ListOrdersResult']['Orders']['Order'];
			if(count($orderList))
			{
				foreach($orderList as $order)
				{
					$successImportOrder = processOrder($service, sanitizePostData($order), $successImportOrder);
				}			
			}			
		}
		if($successImportOrder)
			echo json_encode(array("200",  "success|$successImportOrder Amazon Order imported successfully."));
		else
			echo json_encode(array("200",  "success|No new Amazon Order found."));
     } catch (MarketplaceWebServiceOrders_Exception $ex) {
        $errMsg = "Caught Exception: " . $ex->getMessage() . "<br/>"."Response Status Code: " . $ex->getStatusCode() . "<br/>"."Error Code: " . $ex->getErrorCode() . "<br/>"."Error Type: " . $ex->getErrorType() . "<br/>"."Request ID: " . $ex->getRequestId() . "<br/>"."XML: " . $ex->getXML() . "<br/>"."ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "<br/>";
		echo json_encode(array("300",  "warning|Amazon Order errror .$errMsg"));	
     }
 }


?>
