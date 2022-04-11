<?php

	$multiitems = array();
	$pallet_id = 0;
	$data = sanitizePostData($_POST);
	$alreadyExist = $canProcess = array();
	extract($data);
	if($pallet_id != 0)
	{
		$pallet = new Pallet($pallet_id);
		if($pallet->isExist())
		{
			if($space = $pallet->getAvaialableSpace())
			{
				if($space < count($multiitems))
				{
					echo json_encode(array("300",  "danger|Pallet Don't have sufficient sapce. Only $space is available"));
					die;
				}
				$palletItem = new PalletItems();
				if(count($multiitems))
				{
					foreach($multiitems as $code)
					{
						if(!$palletItem->isItemExist($code))
							$canProcess[] = $code;
						else
							$alreadyExist[] = $code;
					}
					if(count($alreadyExist) == 0)
					{
						foreach($multiitems as $wc_process_asset_code)
						{
							$PalletItems = new PalletItems();
							$wpi_label_number = $PalletItems->getPalletItemLabel();
							$wpi_code_number = $PalletItems->getPalletItemNumber($wpi_label_number);	
							$PalletItems->saveItem($wc_process_asset_code, $pallet_id, $wpi_code_number, $wpi_label_number, 0);
						}
						echo json_encode(array("200", "success|".count($multiitems)." Items Added on Pallet"));
					}
					else
						echo json_encode(array("300",  "danger|".count($alreadyExist)." Items Already alloted on Pallet.<br/>".implode(", ", $alreadyExist)));
				}
				else
					echo json_encode(array("300",  "danger|Collection items not found."));
			}
			else
				echo json_encode(array("300",  "danger|Pallet is Full."));
		}
		else
			echo json_encode(array("300",  "danger|Pallet not found."));
	}
	else{
		echo json_encode(array("300",  "danger|Collection Pallet not found."));
	}

?>