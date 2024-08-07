<?php
class EmailLog extends DB
{
	public $id;
	public $key_id	=	"id";
	public $table_name	=	"app_email_logs";
	public $status	=	"status";
	function __construct($id = 0)
	{
		$this->id	=	intval($id);
	}




	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter = NULL)
	{

		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
			"TABLES" => array(
				"`app_email_logs`" => array(
					"column" => array("`id`", "`service_name`", "`message_id`", "`from_email`", "`to_email`", "`subject`", "`send_by`", "`status`", "`created_date`"),
					"reference" => "a",
					"join" => NULL
				),
				"`app_system_user`" => array(
					"column" => array("`user_fname`", "`user_lname`", "`user_image`"),
					"reference" => "b",
					"join" => array("type" => "INNER JOIN", "table" => "`app_email_logs`", "on" => array("`user_id`" => "`user_id`"))
				)
			),
			"ORDER"	=> array("`created_date`", "CONCAT(`user_fname`,  `user_lname`)", "`to_email`", "`subject`", "`status`")
		);
		$this->searchKeyword = $searchKeyword;

		$conditionArray = array();
		if ($filter != NULL && count($filter)) {
			foreach ($filter as $field => $values) {
				$filedCondArray = array();
				if (is_array($values)) {
					foreach ($values as $_val)
						$filedCondArray[] = array($this->getColumnReference($this->aColumn, $field), "=", sanitizePostData($_val));
				} else
					$filedCondArray[] = array($this->getColumnReference($this->aColumn, $field), "=", sanitizePostData($values));
				$conditionArray[] = $filedCondArray;
			}
		}
		$this->condition = $conditionArray;
		$this->orderPosition = $orderPosition;
		$this->orderDirection = $orderDirection;
		$this->groupby = array("a.`id`");

		$sql = $this->getSql();
		//echo $sql;
		//die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);

		$dbcTotal 	= 	new DB();
		$_SESSION['REPORT']['EMAIL-LOGS-EXPORT'] = $sqlTotal = $this->SqlExceptLimit;
		$resultTotal = $dbcTotal->db_query($sqlTotal);
		$num_rows_total = $dbcTotal->db_num_rows($resultTotal);

		$output = array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total, "data" => array());
		while ($row = $dbc->db_fetch_assoc()) {
			$output["data"][] = array(
				dateView($row["created_date"], "NOW"),
				$row["user_fname"] . " " . $row["user_lname"] . "<br/><div class=\"small text-muted\"> </div>",
				$row["to_email"] . "<div>" . $row["customer_phone"] . "</div>",
				$row["subject"],
				statusView($row["id"], $row["status"]),
			);
		}
		return json_encode($output);
	}
}
