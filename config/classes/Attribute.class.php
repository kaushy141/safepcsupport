<?php
class Attribute extends DB{
	public $id;
	public $key_id	=	"attribute_id";
	public $table_name	=	"app_wc_item_attributes";
	public $status	=	"attribute_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}

	function isAvailable($attribute_name){
		$sql="SELECT `attribute_id` FROM `app_wc_item_attributes` WHERE  LOWER(`attribute_name`) = Lower('$attribute_name')";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows()>0 ? true : false;
	}
	
	function isAvailableExceptThis($attribute_name, $attribute_id){
		$sql="SELECT `attribute_id` FROM `app_wc_item_attributes` WHERE  LOWER(`attribute_name`) = Lower('$attribute_name') AND `attribute_id` != '$attribute_id'";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows()>0 ? true : false;
	}
	
	function getOptions()
	{
		global $app;
		$sql="SELECT `attribute_id`, `attribute_name` FROM `".$this->table_name."` WHERE 1 ORDER BY `attribute_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Store - </option>";			
		while($row = $dbc->db_fetch_assoc()){				
			$html.="<option value=\"$row[attribute_id]\">".$row['attribute_name']."</option>";					
		}
		return $html;
	}
	
	function getAttributesList()
	{
		global $app;
		$sql="SELECT `attribute_id`, `attribute_name` FROM `".$this->table_name."` as a WHERE 1 ORDER BY `attribute_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$resultArray = array();		
		while($row = $dbc->db_fetch_assoc(true)){				
			$resultArray[] = $row;			
		}
		return $resultArray;
	}
	
	function getItemAttributesList($attribute_relation_item_id)
	{
		global $app;
		$sql="SELECT GROUP_CONCAT(`attribute_relation_attribute_id`) as attributes, GROUP_CONCAT(`attribute_relation_exportable`) as attributes_export FROM `app_wc_item_attributes_relation` WHERE `attribute_relation_item_id` = '$attribute_relation_item_id'  AND `attribute_relation_status` = '1' ORDER BY `attribute_relation_attribute_id`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$resultArray = array();
		$record = $dbc->db_fetch_assoc(true);
		$attribute = explode(",", $record['attributes']);
		$exportable = explode(",", $record['attributes_export']);
		$resultArray['attribute'] = array_values($attribute);
		$resultArray['exportable'] = array_values($exportable);		
		return $resultArray;
	}
	
	function getItemAttributesArray($attribute_relation_item_id)
	{
		global $app;
		$sql="SELECT GROUP_CONCAT(`attribute_relation_attribute_id`) as attributes FROM `app_wc_item_attributes_relation` WHERE `attribute_relation_item_id` = '$attribute_relation_item_id'  AND `attribute_relation_status` = '1' ORDER BY `attribute_relation_attribute_id`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record = $dbc->db_fetch_assoc(true);
		return explode(",", $record['attributes']);
	}
	
	function addItemAttribute($attribute_relation_item_id, $attribute_relation_attribute_id, $attribute_relation_exportable, $attribute_relation_status = 1){		
		$sql = "INSERT INTO `app_wc_item_attributes_relation`(`attribute_relation_item_id`, `attribute_relation_attribute_id`, `attribute_relation_exportable`, `attribute_relation_status`) VALUES ('$attribute_relation_item_id', '$attribute_relation_attribute_id', '$attribute_relation_exportable', '$attribute_relation_status')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();		
	}
	
	function isItemAttributeRelationExist($attribute_relation_item_id, $attribute_relation_attribute_id){
		$sql = "SELECT `attribute_relation_id` FROM `app_wc_item_attributes_relation` WHERE `attribute_relation_item_id` = '$attribute_relation_item_id' AND `attribute_relation_attribute_id` = '$attribute_relation_attribute_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result) ? true : false;		
	}
	function updateItemAttributeRelation($attribute_relation_item_id, $attribute_relation_attribute_id, $attribute_relation_exportable, $attribute_relation_status){
		$sql = "UPDATE `app_wc_item_attributes_relation` SET `attribute_relation_status` = '$attribute_relation_status', `attribute_relation_exportable` = '$attribute_relation_exportable' WHERE `attribute_relation_item_id` = '$attribute_relation_item_id' AND `attribute_relation_attribute_id` = '$attribute_relation_attribute_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);		
	}

	function getRecords()
	{
		global $app;
		$sql="SELECT * FROM `app_wc_item_attributes` WHERE 1 ORDER BY `attribute_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<tr>";
				$html.="<td>".$row['attribute_name']."</td>";
				$html.="<td>".$row['attribute_element_type']."</td>";
				$html.="<td>".statusView($row['attribute_id'], $row['attribute_status'])."</td>";
				$html.='<td><div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v fa-fw"></i> <span class="sr-only">TD</span>
  </button>
  <div class="dropdown-menu dropdown-menu-right">    
    <a class="dropdown-item redirect" href="'.$app->basePath('updatecollectionattribute/'.$row['attribute_id']).'"><i class="fa fa-pencil fa-fw"></i> Update</a>'.
	($row['attribute_element_type'] == 'bdropdown' ? ('<a class="dropdown-item redirect" href="'.$app->basePath('addollectionattributeoption/'.$row['attribute_id']).'"><i class="fa fa-pencil fa-fw"></i> Manage Option</a>'):'')
	.'<a class="dropdown-item" href="javascript:deleteAttribute('.$row['attribute_id'].')"><i class="fa fa-trash fa-fw"></i> Delete</a>
  </div>
</div></td>';
			$html.="</tr>";		
		}
		return $html;	
	}
	
}
?>