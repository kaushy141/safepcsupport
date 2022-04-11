<?php
class Section extends DB{
	public $id;
	public $key_id	=	"activity_section_code";
	public $table_name	=	"app_activity_section";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	static function getData($activity_section_code, $column_name = 'activity_section_name'){
		$sql = "SELECT `$column_name` FROM  `app_activity_section` WHERE `activity_section_code` = '".$activity_section_code."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){		
			$record = $dbc->db_fetch_assoc(true);
			return $record[$column_name];
		}
		else
			return null;
	}
	
	static function getSectionDetails($module_code, $module_id){
		$sql = "SELECT * FROM  `app_activity_section` WHERE `activity_section_code` = '".$module_code."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){		
			$record = $dbc->db_fetch_assoc(true);
			if(!class_exists($record['activity_section_class'])){
				Modal::load(array($record['activity_section_class'])); 
			}
			$modal = new $record['activity_section_class']($module_id);
			if($modal->isExist()){
				$modelRecord = $modal->getDetails();
				$modelRecord['model_name'] = $record['activity_section_name'];
				$modelRecord['model_key'] = $record['activity_section_key'];
				$modelRecord['model_class'] = $record['activity_section_class'];
				return $modelRecord;
			}
			else
				return null;
		}
		else
			return null;
	}
}
?>