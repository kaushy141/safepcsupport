<?php
	Modal::load(array('Voting'));
	$voting = new Voting();
	$winnerList = $voting->getTopWinnerOfTheMonth(5);
	$currentMonthVotingStartDate = Voting::getCurrentMonthVotingStartDate();
	$html = "";
	if(count($winnerList)){
		$html.= "<table class='table w-100 mb-0'>";
		$html.= "<thead><tr>";
		$html.= "<th class='text-center'>Rank</th><th>Employee</th><th class='text-center'>Votes</th><th class='text-right'>Points</th>";
		$html.= "</tr></thead>";
		$html.= "<tbody>";
		$i = 0;
		$winner = array();
		foreach($winnerList as $_winner){
			$class="";
			if($i == 0){
				$winner = $_winner;
				$class = "user_crown";
			}
			$i++;
			$html.= "<tr><td class='text-center'>{$i}</td><td><img class='img img-circle {$class}' height='36px' src='{$_winner['user_image']}'/> {$_winner['user_fname']} {$_winner['user_lname']}</td><td class='text-center'>{$_winner['winner_votes']}</td><td class='text-right'>{$_winner['winner_points']}</td></td>";
		}
		$html.= "</tbody>";
		$html.= "<tfoot>";
		$html.= "<tr><td class='text-center pt-2'><i class='fa fa-crown text-warning fa-2x faa-burst animated'></i></td><td colspan='3' class='text-center pt-2'><strong><img class='im img-circle' height='36px' src='{$winner['user_image']}'/> {$winner['user_fname']} {$winner['user_lname']}, Congratulates you for winning & excellent work.</strong></td></tr>";
		$html.= "<tr><td colspan='4' class='text-center pt-2'>";
		if(strtotime($currentMonthVotingStartDate) > time()){
			$html.= "<span class='text-muted'>Next Voting for ".(date('F', strtotime($currentMonthVotingStartDate)))." month will be start from ".(date('l, d-F', strtotime($currentMonthVotingStartDate))).". Good luck !!!</span>";
		}else{
			$html.= "<i class='fa fa-circle-o faa-burst animated text-danger'></i> &nbsp; <span class='text-success'>Votting for ".(date('F', strtotime($currentMonthVotingStartDate)))." month is live</span>";
		}
		$html.= "</td></tr>";
		$html.= "</tfoot>";
		$html.= "</table>";
	}
	echo json_encode(array("200",  "success|Winner details loaded", $html));
	
?>