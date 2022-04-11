<?php

	$voting_user_id = $voting_points  = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($voting_user_id != 0)
	{
		Modal::load(array('Voting'));
		$voting = new Voting();
		if(Voting::isRated()){
			echo json_encode(array("300",  "warning|You have already voted for this month"));
		}
		else
		{
			
			$ratingData = array(
									"voting_user_id" => $voting_user_id,
									"voting_voter_id" => getLoginId(),
									"voting_date" => 'NOW()',
									"voting_points" => $voting_points,
									"voting_status" => 1
								);			
			if($voting->insert($ratingData))
			{
				echo json_encode(array("200",  "success|Thank you for voting. Results will be announce on 1st of next month"));
			}
			else
			{
				echo json_encode(array("300",  "warning|Unabel to save your vote. Contact to developer"));
			}
		}		
	}
	else
		echo json_encode(array("300",  "danger|All mendate field required"));


?>