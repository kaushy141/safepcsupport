<?php
admin();
$shipment_for = isset($parameter1) ? $parameter1 : 'custom';
$shipment_for_id = isset($parameter2) ? $parameter2 : 0;
$shipment_description = "Shipment #";
Modal::load(array('Shipment', 'ShipmentShipper', 'ShipmentOrigin', 'ShipmentType'));
if($shipment_for && $shipment_for_id){
	if($shipment_for == 'weborder'){
		$weborder = new WebsiteOrder($shipment_for_id);
		$data = $weborder->load();
		$custumer = new Customer($data['web_order_customer_id']);
		$customerData = $custumer->getDetails();
		
		$costumerAddress = new CustomerAddress($data['web_order_address_id']);
		$costumerAddressData = $costumerAddress->getDetails();
		
		$weborderProducts = new WebsiteOrderProduct();
		$productList = $weborderProducts->getList($data['web_order_id']);
		$shipment_description = "#$data[web_order_number] $data[store_name] Order";
		
	}
	
	
	$formData = array(
		'shipment_to_name' => $customerData['customer_fname']. ' '.$customerData['customer_lname'],
		'shipment_to_company' => $customerData['customer_company'] ? $customerData['customer_company'] : ($customerData['customer_fname']. ' '.$customerData['customer_lname']),
		'shipment_to_phone_number' => $customerData['customer_phone'],
		'shipment_to_email' => $customerData['customer_email'],
		'shipment_to_address_line1' => $costumerAddressData['customer_address_street_number']. ', '.$costumerAddressData['customer_address_route'],
		'shipment_to_city'=> $costumerAddressData['customer_address_locality'].', '.$costumerAddressData['customer_address_administrative_area'],
		'shipment_to_state_code'=> $costumerAddressData['customer_address_administrative_area'],
		'shipment_to_country_code'=> Country::getValueByParameter('iso2', 'name', $costumerAddressData['customer_address_country']),
		'shipment_to_post_code'=> $costumerAddressData['customer_address_postcode']
	);	
	
	if($productList){
		foreach($productList as $_product){
			$formData['shipment_products'][] = array(
				"product_id" => $_product['wo_id'],
				"product_name" => $_product['wo_product_name'],
				"product_sku" => $_product['wo_product_sku'],
				"product_image" => $_product['wo_product_image'],
				"product_quantiry" => $_product['wo_product_quantity'],
				"product_srno" => $_product['wo_product_srno'],
				"product_track" => $_product['wo_shipment_tracking']
			);
		}
	}
	extract($formData);
}
$action	=	"shipment/getshipmentqoutes";
$formHeading	=	"Get Shipment";
?>
<?php include("engine/inc/addshipmentform.php"); ?>