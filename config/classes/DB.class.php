<?php
class DB extends Basic{
	public $sql = NULL;
	public $SqlExceptLimit = NULL;
	public $SqlCount = NULL;
	public $resultSet;
	public $connection;
	public $count;
	public $record;	
	public $aColumn;
	public $condition = NULL;
	private $whereCondition = NULL;
	public $groupby;
	public $searchKeyword = NULL;
	public $searchCondition = NULL;
	public $orderPosition;
	public $start;
	public $length;
	public $orderDirection ="ASC";
	public $aggrigriateFunc = array("GROUP_CONCAT", "CASE", "CONCAT", "SUM", "COUNT", "ROUND", "AVERAGE","MAX","MIN");
	
	function __construct(){
		global $conn;	
		$this->connection = $conn;
		return $this->connection;
	}
	
	function db_query($sql)
	{
		if(!$this->resultSet	=	mysqli_query($this->connection, $sql)){
			Log::Text("SQL Error : ".$sql, 'SQL');
			die("Server Could Not Listen. DB Exception Error.<br/><br/>".$sql."<br/><br/>".mysqli_error($this->connection));
		}
		return $this->resultSet;
	}
	
	function db_insert_id()
	{
		return mysqli_insert_id($this->connection);
	}
	
	function getRowCount(){
		return mysqli_affected_rows($this->connection);
	}
	
	function db_num_rows()
	{	
		$this->numrows	=	mysqli_num_rows($this->resultSet);
		return $this->numrows;
	}
	
	function db_fetch_assoc($format = false)
	{
		if($format==true)
			$this->record	=	mysqli_fetch_assoc($this->resultSet);
		else
			$this->record	=	mysqli_fetch_array($this->resultSet);
		return $this->record;
	}
	function getSelect()
	{
		$columsData = $this->aColumn;
		if(count($columsData["TABLES"]))
		{
			$colum_array = array();
			$colum_search_array = array();
			$join_array = array();
			$base_table = "";
			$ahliasTable=array();
			$i = 0;
			foreach($columsData["TABLES"] as $tableName => $data)
			{
				$tableNameArray = explode(" AS ", $tableName);
				$tableName = trim($tableNameArray[0]);
				$reference = (isset($tableNameArray[1]) && trim($tableNameArray[0]) != "") ? trim($tableNameArray[0]) : $data["reference"];
				$tableName = "`".trim($tableName, "`")."`";
				if($i==0)
					$base_table = " ".$tableName." AS ".$reference." ";
					
				$ahliasTable[$tableName]=$reference;
				$j=0;	
				foreach($data["column"] as $column)
				{
					$colmindx = explode("(", $column);
					if(!in_array($colmindx[0], $this->aggrigriateFunc))
					{
						$colum_array[] = $reference.".".$column;	
						$colum_search_array[] = $reference.".".(isset($data["searchAlhias"]["$j"])?$data["searchAlhias"]["$j"]:$column);
					}
					else
					{
						$colum_array[] = $column;
						$colum_search_array[] = isset($data["searchAlhias"]["$j"])?$data["searchAlhias"]["$j"]:$column;
					}
				}
				
				if(isset($data["extracolumn"]) && !empty($data["extracolumn"]))
				{
					foreach($data["extracolumn"] as $exColumn)
					array_push($colum_array, $exColumn);
				}
					
				if($i>0)
				{
					if(isset($data["join"]) && $data["join"]!=NULL)
					{
						$joinclm_array = array();
						foreach($data["join"]["on"] as $t1c =>$t2c)
						{
							$t1Alhias = substr($t1c,0,1)=="'"?"":$ahliasTable[$tableName].".";
							$t2Alhias = substr($t2c,0,1)=="'"?"":$ahliasTable[$data["join"]["table"]].".";
							$joinclm_array[] = $t1Alhias.$t1c." = ".$t2Alhias.$t2c." ";
						}
						$join_array[] = $data["join"]["type"]." ".$tableName." AS ".$reference." ON ( ".implode(" AND ", $joinclm_array)." ) ";					
					}
				}
				$i++;
			}
			if($this->searchKeyword!=NULL && $this->searchKeyword !="")
			{
				$this->searchKeyword = str_ireplace(" ","%", trim($this->searchKeyword));
				foreach($colum_search_array as $column)
				{
					$colmindx = explode("(", $column);
					if(!in_array($colmindx[0], $this->aggrigriateFunc))
					{
						if($column = $this->filterColumn($column))
						$this->searchCondition .=" LOWER(". $column. ") LIKE ". "'%".strtolower($this->searchKeyword)."%' OR ";
					}
				}
				$this->searchCondition = " AND (".rtrim($this->searchCondition, "OR ").")";
			}
		}
		$this->SqlCount = "SELECT COUNT(*) as total FROM ".$base_table." ".implode(" ",$join_array). " ";
		return "SELECT ".implode(", ",$colum_array)." FROM ".$base_table." ".implode(" ",$join_array). " ";
	}
	function bindCondition($condition = NULL)
	{
		$conditionString_array = array();
		$glueOperator = $condition == NULL ? "AND":"OR";
		if($condition==NULL)
		{
			$condition = $this->condition;
		}
		if($condition !=NULL)
		{
			array_filter($condition);
			foreach($condition as $cond)
			{
				if(!empty($cond))
				{
					if(isset($cond[0]) && is_array($cond[0]))
						$conditionString_array[]= "( ".$this->bindCondition($cond)." )";
					elseif(isset($cond[0]) && is_array($cond))
					{
						if(count($cond) == 3){
							list($column, $operator, $value) = $cond;
							if(trim($column) !=""){
								if(!in_array($value, $this->aggredateMethod))
									$conditionString_array[] = " ".$column." $operator '$value' ";
								else
									$conditionString_array[] = " ".$column." $operator $value ";
							}
						}
						else{
							if(trim($cond[0]) !="")
							$conditionString_array[] = " ".$cond[0]." ";
						}
					}
					elseif(is_array($cond))
					{
						$conditionString_array[] = " ".$cond[0]." ";
					}
					else{
						$conditionString_array[] = " ".$cond." ";
					}
				}			
			}
		}
		array_filter($conditionString_array);
		return implode(" $glueOperator ",$conditionString_array);
	}
	function getCondition()
	{
		$condition = trim($this->bindCondition());		
		return " WHERE ".((trim($condition) != "")? $condition:" 1 ") .$this->searchCondition;
	}
	
	function getGroupBy()
	{
		if(!empty($this->groupby) && count($this->groupby))
		{
			return " GROUP BY ".implode(", ",$this->groupby)." ";
		}
	}
	
	function getOrderBy($orderPosition, $orderDirection)
	{
		$columsData = $this->aColumn;
		if(isset($columsData["ORDER"][$orderPosition]))
		{
			if(count($columsData["ORDER"]))
			{
				return " ORDER BY ".$columsData["ORDER"][$orderPosition]." ". $orderDirection ;
			}
		}
	}
	
	function getLimit()
	{
		return " LIMIT ".$this->length." OFFSET ".$this->start."";
	}
	
	function joinTable($tableName, $joinArray)
	{
		$joinData = array();
		foreach($joinArray as $join)
		{
			$joinData[] = " $join ";
		}
		return $tableName. " ON ". implode(" AND ", $joinData);
	}
	
	function filterColumn($column)
	{
		if(strpos(strtolower($column), "group")!==0)
		{
			$searchColumn = explode(" as ", strtolower($column));
			return $searchColumn[0];
		}
		else
			return false;
	}
	
	function getSql()
	{
		$this->SqlExceptLimit = $this->getSelect().$this->getCondition().$this->getGroupBy();
		$this->SqlCount = $this->SqlCount.$this->getCondition();
		return $this->SqlExceptLimit.$this->getOrderBy($this->orderPosition, $this->orderDirection).$this->getLimit();
	}
	
	function getTotalCount(){
	    //echo $this->SqlCount;
	    $dbc 	= 	new DB();
		$resultSet	=	$dbc->db_query($this->SqlCount);
	    $record = $dbc->db_fetch_assoc(true);
	    return $record['total'];
	}
}
?>