<?php 
include("../../setup.php");
ini_set('display_errors', 1);
error_reporting(1);

$store = new Store();
$storeList = $store->getOptions(0, 'store_is_product_provider', true);
if(count($storeList))
{
	$products = new WebsiteOrderProduct();
	$productCollection = $products->getIncompleteSkuList();	
			
	if(count($productCollection))
	{
		$wop = new WebsiteOrderProduct();
		$wop->updateMulti(array_column($productCollection, 'id'), array("wo_product_fetch_count" => 1));
				
		$fields_string = http_build_query(array('collection' => $productCollection));
		foreach($storeList as $_store)
		{
			try 
			{
				$serviceUrl = $_store['store_link'].'skufetch.php';
				$token = 'kjhdh47bfhy468ddnhddstwlmx';
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $serviceUrl);
				curl_setopt( $ch, CURLOPT_POST, true );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
																"Authorization: Basic $token"
															));
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string);											
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				$response = curl_exec($ch);
				$responseArray  = json_decode($response, true);	
				
				if(isset($responseArray['status']) && $responseArray['status'] == 200){
					if(count($responseArray['data']))
					{
						updateSkuDetails($responseArray['data']);
					}
				}
				else{
					echo date('Y-m-d H:i:s').": Data fetch Error\n";
				}
				
			} catch ( Exception $ex ) {
				echo date('Y-m-d H:i:s').": Data fetch Exception : ".$ex."\n";
			}
		}
	}
}

function updateSkuDetails($skuProductDetailsArray)
{
	if(count($skuProductDetailsArray))
	{
		foreach($skuProductDetailsArray as $_product )
		{
			$websiteOrderProduct = new WebsiteOrderProduct($_product['id']);
			$websiteOrderProduct->update(array(
				"wo_product_url" => $_product['url'],
				"wo_product_image" => $_product['image'],
				"wo_product_premium" => $_product['premium']
			));
			
			echo "Product : {$_product['id']} - {$_product['sku']} updated. ";
		}
	}
}

?>
