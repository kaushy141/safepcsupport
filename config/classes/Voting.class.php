<?php
class Voting extends DB{
	public $id;
	public $key_id	=	"voting_id";
	public $table_name	=	"app_user_month_voting";
	public $status	=	"voting_status";
	public static $votingOpenDays = 2; //3 Working Days before ending month
		
	function __construct($id=0){		
		$this->id	=	$id;
	}

	static function isRated(){
		$voting_voter_id = getLoginId();
		$sql = "SELECT voting_id FROM app_user_month_voting WHERE voting_voter_id = '$voting_voter_id' AND MONTH(voting_date) = MONTH(NOW()) AND YEAR(voting_date) = YEAR(NOW())";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result);
	}	
						
	static function getCurrentMonthVotingStartDate(){
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); 
		$workingDays = 0;
		$date = date("Y-m-d");
		for($i=$daysInMonth; $i > 1 ; $i--){
			$date = date("Y-m-").sprintf('%02d', $i);
			if(!in_array(date('D', strtotime($date)), array('Fri', 'Sat', 'Sun')))
				$workingDays++;
			if($workingDays == self::$votingOpenDays)
				break;
		}
		return $date;
	}
	
	static function isCurrentMonthVotingStarted(){
		return time() > strtotime(self::getCurrentMonthVotingStartDate());
	}
	
	function calculateWinnerOfTheMonth(){
		$winner = new Winner();
		if(!$winner->isLastMonthWinnerAnnounced())
		{
			$sql = "SELECT COUNT(voting_id) as winner_votes, voting_user_id AS winner_user_id, SUM(voting_points) as winner_points, (CURDATE() - INTERVAL 1 MONTH) as winner_month FROM app_user_month_voting WHERE MONTH(voting_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(voting_date) = YEAR(CURDATE() - INTERVAL 1 MONTH) AND voting_status = '1' GROUP BY voting_user_id order by winner_votes DESC LIMIT 1";			
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			if($dbc->db_num_rows($result)){
				$votingRecord = $dbc->db_fetch_assoc(true);
				if($votingRecord['winner_user_id'])
				{
					$winnderData = array(
						'winner_user_id' => $votingRecord['winner_user_id'],
						'winner_month' => date('Y-m-', strtotime($votingRecord['winner_month'])).'01',
						'winner_votes' => $votingRecord['winner_votes'],
						'winner_points' => $votingRecord['winner_points'],
						'winner_award' => "Employee of the month ".date('M-Y', strtotime($votingRecord['winner_month'])),
						'winner_status' => 1
					);
					$winner->insert($winnderData);
				}
			}
		}
	}
	
	function getTopWinnerOfTheMonth($limit=5){
		$winner = new Winner();
		$recordArray = array();
		$sql = "SELECT COUNT(voting_id) as winner_votes, voting_user_id AS winner_user_id, SUM(voting_points) as winner_points, (CURDATE() - INTERVAL 1 MONTH) as winner_month, app_system_user.user_fname, app_system_user.user_lname, app_system_user.user_image FROM app_user_month_voting INNER JOIN app_system_user ON app_user_month_voting.voting_user_id = app_system_user.user_id WHERE MONTH(voting_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(voting_date) = YEAR(CURDATE() - INTERVAL 1 MONTH) AND voting_status = '1' GROUP BY voting_user_id ORDER BY winner_votes DESC, winner_points DESC LIMIT $limit";			
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){
			while($row = $dbc->db_fetch_assoc(true)){
				$row['user_image'] = getResizeImage($row["user_image"],50);
				$recordArray[] = $row;
			}
		}
		return $recordArray;
	}
	
	static function getWinnerOfTheLastMonth(){
		$winner = new Winner();
		return $winner->getWinnerOfTheLastMonth();
	}
	
	static function getWinnerHtml(){
		self::calculateWinnerOfTheMonth();
		$winningUser = self::getWinnerOfTheLastMonth();
		if($winningUser){
			$rating = round($winningUser['winner_points']/$winningUser['winner_votes'], 2);			
			return "<span class='hidden-sm visible-md' onclick='showLastMonthWinnerTable()'><img class='img img-circle' height='24px' src='{$winningUser['user_image']}' /> Winner of the Month</span>";
		}
	}

}
class Winner extends DB{
	public $id;
	public $key_id	=	"winner_id";
	public $table_name	=	"app_user_month_winner";
	public $status	=	"winner_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function getWinnerOfTheLastMonth(){
		$sql = "SELECT a.* , b.user_id, b.user_fname, b.user_lname, b.user_image FROM app_user_month_winner AS a INNER JOIn app_system_user AS b on a.`winner_user_id` =  b.`user_id` WHERE MONTH(winner_month) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(winner_month) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){		
			$row = $dbc->db_fetch_assoc(true);
			$row["user_image"] = getResizeImage($row["user_image"], 50);
			return $row;			
		}
		else
			return false;
	}
	
	function isLastMonthWinnerAnnounced(){
		$sql = "SELECT winner_id FROM app_user_month_winner WHERE MONTH(winner_month) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(winner_month) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result);
	}
}
?>