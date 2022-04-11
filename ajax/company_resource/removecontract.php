<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$ContractInsurance = new ContractInsurance($id);
	if($ContractInsurance->isActive())
	{
		$record = $ContractInsurance->getDetails();
		$file_extension = pathinfo(BP.$record['cont_ins_file_path'], PATHINFO_EXTENSION);
		$cont_ins_file_path = DOC_UPLOAD_DIR."contract/del/contract-".time().".$file_extension";
		if(move_file(BP.$record['cont_ins_file_path'], BP.$cont_ins_file_path))
		{
			unlink(BP.$record['cont_ins_file_path']);
			$ContractInsurance->update(array("cont_ins_file_path" => $cont_ins_file_path));
			$ContractInsurance->Deactivate();
			Activity::add("Removed Contract Document <b>$record[licence_name]</b>","", $id);
			echo json_encode(array("200",  "success|Contarct record removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete Contarct record."));
	}
	else
		echo json_encode(array("300",  "warning|Contarct record not found."));

?>