<?php include("setup.php"); ?>
<?php

$websiteOrderProduct = new WebsiteOrderProduct();
$webOrdersProducts = $websiteOrderProduct->getMultipleQtyProductList();
if(count($webOrdersProducts))
{
	foreach($webOrdersProducts as $product)
	{
		if(!empty($product))
		{
			$wo_web_order_id 	 	= $product['wo_web_order_id'];
			$wo_product_name	 	= $product['wo_product_name'];
			$wo_product_sku 		= $product['wo_product_sku'];
			$wo_product_sell_price 	= $product['wo_product_sell_price'];
			$wo_product_quantity	= $product['wo_product_quantity'];
			$wo_process_code 	 	= "";

			for($i=1; $i< $wo_product_quantity; $i++){
				$websiteOrderProduct->insert(array(
						"wo_web_order_id" => $wo_web_order_id,
						"wo_product_name" => $wo_product_name,
						"wo_product_sku" => $wo_product_sku,
						"wo_product_quantity" => 1,
						"wo_product_sell_price" => $wo_product_sell_price,
						"wo_process_code" => $wo_process_code
					)
				);
			}
			
			$wo = new WebsiteOrderProduct($product['wo_id']);
			$wo->update(array("wo_product_quantity" => "1"));
		}
	}
}
		
?>