<?php

	$signature_code = "";
	$signature_path = "";
	$signature_id	= "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if(!is_array($signature_code) && !is_array($signature_path) && !is_array($signature_id)){
		if($signature_code != "" && $signature_path != "" && $signature_id != ""){
			if(file_exists($app->sitePath($signature_path))){
				$s = new Signature();
				if($record = $s->getDetailsSecure(md5($signature_id), md5($signature_code))){
					if($record['signature_is_used'] == 0){
						if(strtotime($record['signature_created_date']) + $record['signature_validity_second'] > time()){						
							$sign_path = "upload/user/sign/".getDirectorySeparatorPath()."link-" . time(). ".png";
							if (move_file($app->sitePath($signature_path), $app->sitePath($sign_path))){
								
								$signClass = new $record['signature_class']($record['signature_record_id']);
								$signClass->update(array($record['signature_column']=> $sign_path));
								
								$signature = new Signature($record['signature_id']);
								$signature->update(array("signature_is_used" => 1, "signature_path"=>$sign_path));
								
								echo json_encode(array("200", "success|Signature uploaded successfully"));
							}else
								echo json_encode(array("300",  "danger|Signature can't saved. try again."));
						}else
							echo json_encode(array("300",  "danger|Signature link expired."));
					}else
						echo json_encode(array("300",  "danger|Signature link already used."));
				}else
					echo json_encode(array("300",  "danger|Invalid Signature link."));
			}else
				echo json_encode(array("300",  "danger|Uploaded signature not found."));
		}else
			echo json_encode(array("300",  "danger|Invalid input found."));
	}else
		echo json_encode(array("300",  "danger|Input format is not correct."));

?>