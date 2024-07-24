<?php
class Basic
{

	protected $aggredateMethod = array("NOW()", "CURRENT_DATE", "NULL", "null");

	function __construct($id = 0)
	{

		$this->id	=	$id;
	}


	public function getColumnReference($columnArray, $columnName)
	{
		foreach ($columnArray['TABLES'] as $table => $columnsList) {
			if (in_array("{$columnName}", $columnsList['column']) || in_array("`{$columnName}`", $columnsList['column'])) {
				return "{$columnsList['reference']}.{$columnName}";
			}
		}
		return $columnName;
	}

	public function Count($type = NULL)
	{
		$sql = "SELECT COUNT(`" . $this->key_id . "`) as num  FROM `" . $this->table_name . "` WHERE 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data	=	$dbc->db_fetch_assoc();
		return $data[0];
	}

	function loadByMd5($id)
	{
		$sql = "SELECT * FROM `" . $this->table_name . "` WHERE MD5(`" . $this->key_id . "`) = '" . $id . "'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if ($dbc->db_num_rows($result) == 1) {
			$data = $dbc->db_fetch_assoc(true);
			$this->id = $data[$this->key_id];
			return $data;
		} else
			return false;
	}

	function getPrevNext($url, $md5Url = false)
	{
		$sql = "SELECT `" . $this->key_id . "` AS ID, 'NEXT' AS LABEL FROM `" . $this->table_name . "` WHERE `" . $this->key_id . "` = (SELECT MIN(`" . $this->key_id . "`) FROM `" . $this->table_name . "` WHERE `" . $this->key_id . "` > " . $this->id . ") 
		UNION 
		SELECT `" . $this->key_id . "` AS ID, 'PREV' AS LABEL FROM `" . $this->table_name . "` WHERE `" . $this->key_id . "` = (SELECT MAX(`" . $this->key_id . "`) FROM `" . $this->table_name . "` WHERE `" . $this->key_id . "` < " . $this->id . ")";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$resultArray = array(
			"PREV" => NULL,
			"NEXT" => NULL
		);
		if ($dbc->db_num_rows() > 0) {
			while ($row = $dbc->db_fetch_assoc(true))
				$resultArray[$row['LABEL']] = $url . "/" . ($md5Url ? md5($row['ID']) : $row['ID']);
		}
		return json_encode($resultArray);
	}

	function loadByColumn($columname, $columnvalue)
	{
		$sql = "SELECT `" . $this->key_id . "` FROM `" . $this->table_name . "` WHERE `" . $columname . "` = '" . $columnvalue . "'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if ($dbc->db_num_rows($result)) {
			$data = $dbc->db_fetch_assoc(true);
			$this->id = $data[$this->key_id];
			return $this->id;
		} else
			return false;
	}

	function saveHistory()
	{
		$sql = "INSERT INTO `" . $this->table_name . "_history` SELECT * FROM `" . $this->table_name . "` WHERE  `" . $this->key_id . "` = '" . $this->id . "'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
	}

	public function isExist()
	{
		$sql = "SELECT `" . $this->key_id . "` FROM `" . $this->table_name . "` WHERE `" . $this->key_id . "` = '" . $this->id . "' OR MD5(`" . $this->key_id . "`) = '" . $this->id . "' ";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result) ? true : false;
	}
	public function isActive()
	{
		$sql = "SELECT `" . $this->key_id . "` FROM `" . $this->table_name . "` WHERE (`" . $this->key_id . "` = '" . $this->id . "' OR MD5(`" . $this->key_id . "`) = '" . $this->id . "' ) AND `" . $this->status . "` = 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result) ? true : false;
	}

	public function isUnique($column_name, $values)
	{
		if ($values != "") {
			$sql = "SELECT `" . $this->key_id . "` FROM `" . $this->table_name . "` WHERE TRIM(LOWER(`$column_name`)) = TRIM(LOWER('" . $values . "')) AND `" . $this->key_id . "` != '" . $this->id . "' ";
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			return $dbc->db_num_rows($result) > 0 ? false : true;
		} else
			return true;
	}

	public function isNotUnique($column_name, $values)
	{
		return !$this->isUnique($column_name, $values);
	}

	public function remove()
	{
		$sql = "DELETE FROM `" . $this->table_name . "` WHERE `" . $this->key_id . "` = '" . $this->id . "' OR MD5(`" . $this->key_id . "`) = '" . $this->id . "'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
	}

	public function Activate()
	{
		$sql = "UPDATE `" . $this->table_name . "` SET `" . $this->status . "` = 1 WHERE `" . $this->key_id . "` = '" . $this->id . "' OR MD5(`" . $this->key_id . "`) = '" . $this->id . "' ";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
	}

	public function Deactivate()
	{
		$sql = "UPDATE `" . $this->table_name . "` SET `" . $this->status . "` = 0 WHERE `" . $this->key_id . "` = '" . $this->id . "' OR MD5(`" . $this->key_id . "`) = '" . $this->id . "' ";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
	}


	function getLastId()
	{
		$sql = "SELECT MAX(`" . $this->key_id . "`) AS max_id FROM `" . $this->table_name . "`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record = $dbc->db_fetch_assoc(true);
		return $record['max_id'];
	}


	function getDetails()
	{
		$sql = "SELECT * FROM `" . $this->table_name . "` WHERE `" . $this->key_id . "` = '" . $this->id . "' OR MD5(`" . $this->key_id . "`) = '" . $this->id . "'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if ($dbc->db_num_rows($result)) {
			$record = $dbc->db_fetch_assoc(true);
			$this->id = $record[$this->key_id];
			return $record;
		} else
			return false;
	}

	function load()
	{
		return $this->getDetails();
	}

	function getCommonList($orderColumn = NULL)
	{
		global $app;
		$sql = "SELECT * FROM `" . $this->table_name . "` WHERE 1 " . ($orderColumn ? "ORDER BY $orderColumn" : "");
		$dbc 	= 	new DB();
		$resultArray = array();
		$result	=	$dbc->db_query($sql);
		while ($row = $dbc->db_fetch_assoc(true)) {
			$resultArray[] = $row;
		}
		return $resultArray;
	}
	function getFilteration($field_name)
	{
		$sql = "SELECT `$field_name` FROM `" . $this->table_name . "` WHERE `$field_name` != '' GROUP BY `$field_name` ORDER BY `$field_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		while ($row = $dbc->db_fetch_assoc(true))
			$data[] = $row[$field_name];
		return $data;
	}

	function deactivateAll()
	{
		$sql = "UPDATE `" . $this->table_name . "` SET `" . $this->status . "` = 0 ";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}

	function ActivateAll()
	{
		$sql = "UPDATE `" . $this->table_name . "` SET `" . $this->status . "` = 1 ";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}

	public function get($field)
	{
		//$user_id!=NULL?$this->user_id=$user_id:"";
		$field = $field == "user_name" ? "CONCAT(`user_fname` , ' ', `user_lname`)" : $field;
		$sql = "SELECT `$field` FROM `" . $this->table_name . "` WHERE `" . $this->key_id . "` = '" . $this->id . "' OR MD5(`" . $this->key_id . "`) = '" . $this->id . "'";
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if ($dbc->db_num_rows()) {
			$data = $dbc->db_fetch_assoc();
			return $data[$field];
		} else
			return NULL;
	}

	private function getSubSql($array)
	{
		$subsql = " SET ";
		if (count($array)) {
			foreach ($array as $field => $value) {
				if (in_array(strtoupper($value), $this->aggredateMethod))
					$subsql .= " `$field` = $value, ";
				else
					$subsql .= " `$field` = '$value', ";
			}
			return $subsql;
		} else
			return false;
	}

	function updateMulti($idsArray, $dataArray)
	{
		if (!empty($idsArray) && !empty($dataArray)) {
			$subsql = rtrim(trim($this->getSubSql($dataArray)), ",");
			$sql = "UPDATE `" . $this->table_name . "` $subsql WHERE `" . $this->key_id . "` IN ( '" . implode(",", $idsArray) . "' ) ";
			$dbc 	= 	new DB();
			return  $dbc->db_query($sql) ? true : false;
		} else
			return false;
	}

	public function update($dataArray)
	{
		if (!empty($dataArray)) {
			$subsql = rtrim(trim($this->getSubSql($dataArray)), ",");
			$sql = "UPDATE `" . $this->table_name . "` $subsql WHERE `" . $this->key_id . "` = '" . $this->id . "' OR MD5(`" . $this->key_id . "`) = '" . $this->id . "'";
			$dbc 	= 	new DB();
			return  $dbc->db_query($sql) ? true : false;
		} else
			return false;
	}

	public function insert($array)
	{
		$subsql = $this->getSubSql($array);
		$subsql = rtrim(trim($subsql), ",");
		$sql = "INSERT INTO `" . $this->table_name . "` $subsql";
		$dbc 	= 	new DB();
		if ($dbc->db_query($sql)) {
			$this->id = $dbc->db_insert_id();
			return $this->id;
		}
		return false;
	}

	function getOptionsArray($status = null)
	{
		if ($status == null)
			$sql = "SELECT * FROM `" . $this->table_name . "` WHERE 1";
		else
			$sql = "SELECT * FROM `" . $this->table_name . "` WHERE `" . $this->status . "` = '$status'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$resultArray = null;
		while ($row = $dbc->db_fetch_assoc()) {
			$resultArray[] = $row;
		}
		return $resultArray;
	}


	public function getChartData($array)
	{
		$data = array();
		$data['labels'] = implode(" , ", array_keys($array));
		$data['values'] = implode(" , ", array_values($array));
		$data['count'] = count($array);
		return $data;
	}

	public function getDropdownList($fieldName, $keyword, $extra, $condition)
	{
		if ($condition != "")
			$condition = "AND $condition";

		$keyword = str_replace(" ", "%", trim($keyword));
		$fieldName = $fieldName == 'username' ? "CONCAT(`user_fname`, ' ', `user_lname`)" : $fieldName;

		$sql = "SELECT DISTINCT $fieldName as itemlabel  FROM `" . $this->table_name . "` WHERE LOWER($fieldName) LIKE '$keyword%' $condition ORDER BY $fieldName LIMIT 5";

		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if ($result)
			if ($dbc->db_num_rows() > 0) {
				while ($row = $dbc->db_fetch_assoc(true))
					$data[]	= array("label" => $row['itemlabel'], "data-id" => $row['itemlabel'], "value" => $row['itemlabel']);
			}
		return $data;
	}
}
