<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$id = $_GET['id'];
if(isAdmin() && !is_array($id) && $id != "")
{
	Modal::load(array('Shipment', 'ShipmentType'));
	$shipment = new Shipment();
	$record = $shipment->loadByMd5($id);
	//print_r($record);
	if(!empty($record))
	{
		$shipmentType = new ShipmentType($record['shipment_type_id']);
		$shipmentTypeData = $shipmentType->getDetails();
		$shipment->markDownloader();		
		$report	= new Report("Shipment slip $record[shipment_tracking_number] $record[shipment_code]",true);
		//echo $record['shipment_label_image'];die;
		
		if($shipmentTypeData['shipment_type_code'] == 'UPS')
		{
			$imageData = base64_decode($record['shipment_label_image']);
			file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $imageData);
			
			$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
			$report->addData($data);
			$report->setJRXML("shipment-label")->generate();
		}
		elseif($shipmentTypeData['shipment_type_code'] == 'DPD')
		{
			//header('Content-type: application/pdf');
			if($record['shipment_label_image'] == "")
			{
				extract(App::exploreApiCredentilas('DpdShippingApi'));
				$shipmentId = $record['shipment_identification_number'];
				include(BP.'api/dpd/loginRequest.php');			
				$responseArray = json_decode($response, true);
				if($responseArray['error'] == '')
				{
					$geoSession = $responseArray['data']['geoSession'];					
					include(BP.'api/dpd/shipmentLabel.php'); 
					$shipment->update(array('shipment_label_image'=>sanitizePostData($labelResponse)));
					//echo $labelResponse;					
				}
				else
					$labelResponse = "No Data";
			}
			else
				$labelResponse =  $record['shipment_label_image'];
			/*
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->AddPage();
			$pdf->writeHTML($labelResponse, true, false, true, false, '');
			$pdf->lastPage();			
			$pdf->Output('example_006.pdf', 'I');	
			*/	
			echo $labelResponse;			
		}
		elseif($shipmentTypeData['shipment_type_code'] == 'FDX')
		{
			header("Content-Type: application/pdf");
			if($record['shipment_label_image'] == "")
			{
				echo $record['shipment_label_image'];
			}
			else
				echo $record['shipment_label_image'];
		}
	}
	else{
		echo json_encode(array("300",  "warning|Shipment not found"));
	}
}
else
echo json_encode(array("300",  "warning|Shipment not found,"));
?>