<?php
	$wc_process_asset_code = $wci_item_name = $wci_item_make = $wci_item_model = $wci_item_srno = array();
	$data  = sanitizePostData($_POST);
    extract($data);
	if(count($wc_process_asset_code))
	{
		$unUniqueSrNo = array();
		foreach($wc_process_asset_code as $p_code)
		{
			$wc_process_item_sr_no = isset($wci_item_srno[$p_code]) ? $wci_item_srno[$p_code] : "";
			$cp = new CollectionProcess($p_code);
			if($cp->isNotUnique('wc_process_item_sr_no', $wc_process_item_sr_no))
			{
				$unUniqueSrNo[] = $wc_process_item_sr_no;
			}
		} 
		if(count($unUniqueSrNo)){
			echo json_encode(array("300",  "warning|Warning : Could not saved. Duplicate serial number found (".implode(",", $unUniqueSrNo).")"));
		}
		else
		{
			foreach($wc_process_asset_code as $p_code)
			{
				$wc_process_item_make = isset($wci_item_make[$p_code]) ? $wci_item_make[$p_code] : "";
				$wc_process_item_name = isset($wci_item_name[$p_code]) ? $wci_item_name[$p_code] : "";
				$wc_process_item_model = isset($wci_item_model[$p_code]) ? $wci_item_model[$p_code] : "";
				$wc_process_item_sr_no = isset($wci_item_srno[$p_code]) ? $wci_item_srno[$p_code] : "";
				$cp = new CollectionProcess($p_code);
				$cp->update(
							array(
								"wc_process_item_make"=>$wc_process_item_make,
								"wc_process_item_name"=>$wc_process_item_name,
								"wc_process_item_model"=>$wc_process_item_model,
								"wc_process_item_sr_no"=>$wc_process_item_sr_no
								)
							);
			}
			echo json_encode(array("200",  "success|Collection Request processed Successfully"));
		}
		exit();	
	}
	else
	{
		echo json_encode(array("300",  "danger|No Processing Code found"));
		exit();	
	}

?>