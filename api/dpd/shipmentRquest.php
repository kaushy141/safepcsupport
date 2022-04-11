<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$endpointurl = ($ProductionMode ? DPD_PROD_URL :DPD_DEVL_URL )."/shipping/shipment$testShipment";
$outputFileName = "XOLTResultRequest.xml";
$labelImageFormatCode = 'PNG';
if(!isset($soapHeaderBody) || $soapHeaderBody == NULL)
$soapHeaderBody = array(
    "jobId" => NULL,
    "collectionOnDelivery" => 'false',
    "generateCustomsData" => "N",
    "invoice" => array(
        "countryOfOrigin" => "$shipment_origin_country_code",
        "invoiceCustomsNumber" => "FDA Reg No",
        "invoiceExportReason" => "Export Reason",
        "invoiceReference" => "Invoice Reference",
        "invoiceType" => 1,
        "shippingCost" => 0.00,
        "invoiceShipperDetails" => array(
            "contactDetails" => array(
                "contactName" => "$shipment_origin_name",
                "telephone" => "$shipment_origin_phone_no"
            ) ,
            "address" => array(
                "organisation" => "$shipment_origin_company",
                "countryCode" => "$shipment_origin_country_code",
                "postcode" => "$shipment_origin_post_code",
                "street" => "$shipment_origin_address_line1",
                "locality" => "$shipment_origin_city",
                "town" => "$shipment_origin_state_code",
                "county" => Country::getValueByParameter('name', 'iso2', $shipment_origin_country_code)
            ) ,
            "vatNumber" => "$shipment_shipper_tax_id"
        ) ,
        "invoiceDeliveryDetails" => array(
            "contactDetails" => array(
                "contactName" => "$shipment_to_name",
                "telephone" => "$shipment_to_phone_number"
            ) ,
            "address" => array(
                "organisation" => "$shipment_to_company",
                "countryCode" => "$shipment_to_country_code",
                "postcode" => "$shipment_to_post_code",
                "street" => "$shipment_to_address_line1",
                "locality" => "$shipment_to_city",
                "town" => $shipment_to_state_code == "" ? $shipment_to_city : $shipment_to_state_code,
                "county" => Country::getValueByParameter('name', 'iso2', $shipment_to_country_code)
            ) ,
            "vatNumber" => "N/A"
        )
    ) ,
    "collectionDate" => date("Y-m-d")."T".date("H:i:s"),
    //"consolidate" => 'false',
    "consignment" => array(
        array(
            "consignmentNumber" => NULL,
            "consignmentRef" => NULL,			
            "parcel" => array(
				"packageNumber"=> 1,

				"parcelProduct" =>
					array(
						 "productCode"=> "123456789",
						 "productTypeDescription"=> "Parcel details",
						 "productItemsDescription"=> "Parcel data details",
						 "productFabricContent"=> "Parcel data details Fabric Content",
						 "countryOfOrigin"=> "$shipment_origin_country_code",
						 "productHarmonisedCode"=> "Parcel data details Harmonised Code",
						 "unitWeight"=> 1,
						 "numberOfItems"=> 1,
						 "unitValue"=> 15,
						"productUrl"=>""
					)				 
			),
            "collectionDetails" => array(
                "contactDetails" => array(
                    "contactName" => "$shipment_origin_name",
                	"telephone" => "$shipment_origin_phone_no"
                ) ,
                "address" => array(
                    "organisation" => "$shipment_origin_company",
					"countryCode" => "$shipment_origin_country_code",
					"postcode" => "$shipment_origin_post_code",
					"street" => "$shipment_origin_address_line1",
					"locality" => "$shipment_origin_city",
					"town" => "$shipment_origin_state_code",
					"county" => Country::getValueByParameter('name', 'iso2', $shipment_origin_country_code)
                )
            ) ,
            "deliveryDetails" => array(
                "contactDetails" => array(
                    "contactName" => "$shipment_to_name",
                	"telephone" => "$shipment_to_phone_number"
                ) ,
                "address" => array(
                    "organisation" => "$shipment_to_company",
					"countryCode" => "$shipment_to_country_code",
					"postcode" => "$shipment_to_post_code",
					"street" => "$shipment_to_address_line1",
					"locality" => "$shipment_to_city",
					"town" => $shipment_to_state_code == "" ? $shipment_to_city : $shipment_to_state_code,
					"county" => Country::getValueByParameter('name', 'iso2', $shipment_to_country_code)
                ) ,

                "notificationDetails" => array(
                    "email" => "$shipment_to_email",
                    "mobile" => "$shipment_to_phone_number"
                )
            ) ,
            "networkCode" => "$shipment_service_code",
            "numberOfParcels" => 1,
            "totalWeight" => $shipment_billing_weight,
            "shippingRef1" => substr($shipment_description, 0, 25),
            "shippingRef2" => "",
            "shippingRef3" => "",
            "customsValue" => 15,
            "deliveryInstructions" => "Deliver safely",
            "parcelDescription" => "",
            "liabilityValue" => NULL,
            //"liability" => 'false',
            "shippersDestinationTaxId" => "",
            "vatPaid" => "Y"
        )
    )
);

//print_r($soapHeaderBody);die;

try {	
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $endpointurl );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array("GeoClient: account/$delisId"));
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array("GeoSession: $geoSession"));	
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($soapHeaderBody) );
	$response = curl_exec($ch);
	$fw = fopen ( $outputFileName, 'w' );	
	fwrite ( $fw, "Response: \n" . $response . "\n". json_encode($soapHeaderBody) );
	fclose ( $fw );	
} catch ( Exception $ex ) {
	echo $ex;
}

?>
