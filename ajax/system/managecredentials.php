<?php

	$credentials_id = 0;
	$credentials_status = 1;
	$credentials_weburl = $credentials_username = $credentials_password = $credentials_scope = '';
	$data  = sanitizePostData($_POST);
	extract($data);	
	$credentials = new Credentials($credentials_id);
	if($credentials_id == 0 || $credentials->canEdit())
	{
		$record_data = array(
								"credentials_weburl" => $credentials_weburl,
								"credentials_username" => $credentials_username,
								"credentials_password" => $credentials_password,
								"credentials_scope" => $credentials_scope,
								"credentials_last_update" => 'NOW()'
						);
		if($credentials_id == 0)
		{
			$record_data = array_merge($record_data, array(
												"credentials_owner" => $_SESSION['user_id'],
												"credentials_status" => $credentials_status
												)
								);
			$credentials->insert($record_data);
		}
		else
			$credentials->update($record_data);
			
		$record = $credentials->getDetails();
		$record['status'] = statusView($record['credentials_id'], $record['credentials_status']);
		$record['action'] = actionView(array(
			  									array("name"=>"Edit", "icon"=>"fa-edit", "url"=>"javascript:editCredentials($record[credentials_id])", "class"=>""),
												array("name"=>"Delete", "icon"=>"fa-trash", "url"=>"javascript:deleteCredentials($record[credentials_id])", "class"=>"")
											  )
										);
		echo json_encode(array("200", "success|Crentials saved successfully", $record));		
	}
	else
		echo json_encode(array("300",  "danger|Credentials not permissable"));

?>