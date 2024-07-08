<?php
class WebsiteOrder extends DB{
	public $id;
	public $key_id	=	"web_order_id";
	public $table_name	=	"app_website_order";
	public $status	=	"web_order_status";	
	function __construct($web_order_id=0){
				
		$this->id	=	$web_order_id;
	}	
	
	function load(){
		$sql = "SELECT a.*, b.*, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, c.`wc_status_name`, c.`wc_status_color_code`, d.`store_name`, d.`store_icon`,d.`store_logo`, CONCAT(e.`customer_address_street_number`,  ', ', e.`customer_address_route`, ', ', e.`customer_address_locality`, ', ', e.`customer_address_administrative_area`, ', ', e.`customer_address_country`, ' - ', e.`customer_address_postcode`) as customer_full_address, f.`customer_type_name`
		FROM  `app_website_order` AS a  
		INNER JOIN `app_customer` AS b ON ( b.`customer_id` = a.`web_order_customer_id` )  
		LEFT JOIN `app_wc_status` AS c ON ( c.`wc_status_id` = a.`web_order_status`  )  
		INNER JOIN `app_store_master` AS d ON ( d.`store_id` = a.`web_order_website_id` ) 
		LEFT JOIN `app_customer_address` as e ON a.`web_order_address_id`= e.`customer_address_id`  
		LEFT JOIN `app_customer_type` as f ON ( b.`customer_type_id` = f.`customer_type_id` ) 
		WHERE  a.`web_order_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()==1)
		{
			$collection_item_list_array = array();
			$row = $dbc->db_fetch_assoc(true);
			return $row;
		}
	}
	
	
	function getOrderFromWebOrderID($web_order_id = 0){
		global $app;
		$condition = $web_order_id != 0 ? " AND a.`web_order_id` > '$web_order_id' ORDER BY a.`web_order_id` DESC LIMIT 3" : " ORDER BY a.`web_order_id` DESC LIMIT 1";
		$sql = "SELECT a.`web_order_id`, a.`web_order_number`, a.`web_order_created_date`, a.`web_order_total_amount`, b.customer_fname,  b.customer_lname,  b.customer_image, c.`wc_status_name`, c.`wc_status_color_code`, d.`store_name`, d.`store_icon`,d.`store_logo`
		FROM  `app_website_order` AS a  
		INNER JOIN `app_customer` AS b ON ( b.`customer_id` = a.`web_order_customer_id` )  
		LEFT JOIN `app_wc_status` AS c ON ( c.`wc_status_id` = a.`web_order_status`  )  
		INNER JOIN `app_store_master` AS d ON ( d.`store_id` = a.`web_order_website_id` )  
		WHERE 1 $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows() > 0)
		{
			while($row = $dbc->db_fetch_assoc(true)){
				$result_array[] = array(
											"title" => "New Order #$row[web_order_number] received from $row[store_name] placed by $row[customer_fname] $row[customer_lname]",
											"image" => $app->imagePath($row["store_icon"]),
											"user"  => $row['customer_fname']. " ". $row['customer_lname'],
											"time"	=> dateView($row["web_order_created_date"],"NOW"),
											"link"	=> $app->siteUrl("viewweborder/".$row["web_order_id"]),
											"limit" => $row['web_order_id']
										);
			}
			$result_array = array_reverse($result_array);
		}
		return $result_array;
	}
	
	function isOrderExist($web_order_number, $web_order_website_id){
		$sql = "SELECT `web_order_id` FROM `app_website_order` WHERE `web_order_number` = '$web_order_number' AND `web_order_website_id` = '$web_order_website_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() > 0  ? true : false;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL, $isUnderCustomerReview = false){
		Modal::load(array('ProductAvailbility'));		
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_website_order`" => array(
															"column"=> array("`web_order_id`",  "`web_order_total_amount`",    "`web_order_payment_method`", "`web_order_number`",  "`web_order_created_date`", 
															 "`web_order_is_paid`", 
															 "`web_order_picking_user`", "`web_order_picking_time`", "`web_order_packing_user`", "`web_order_packing_time`", "`web_order_process_user`",
															 "`web_order_process_time`",
															"web_order_currency", "`web_order_tracking_mark_by`",
															"web_order_status","web_order_priority",
															"web_order_shipment_status", "web_order_assign_technician",
															"GROUP_CONCAT(DISTINCT '<span class=\"badge badge-pill-xs badge-info\">',(SELECT SUM(`wo_product_quantity`) FROM `app_website_order_product` WHERE `wo_web_order_id` = a.`web_order_id` AND p.`wo_product_sku` = `wo_product_sku`), '</span> ', `wo_product_sku`, '<br/>' SEPARATOR '') AS product_sku",
															"GROUP_CONCAT(`wo_product_premium` SEPARATOR ', ') AS product_premium",
															"COUNT(`wo_id`) AS web_order_total_quantity"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_customer`" => array(
															"column"=> array("`customer_id`", "`customer_fname`", "`customer_lname`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_website_order`", "on"=>array("`customer_id`" => "`web_order_customer_id`"))
															),
								"`app_wc_status`" => array(
															"column"=> array("`wc_status_name`", "`wc_status_color_code`"),
															"reference" => "f",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_website_order`", "on"=>array("`wc_status_id`" => "`web_order_status`"))
															),
								"`app_store_master`" => array(
															"column"=> array("`store_name`", "`store_icon`"),
															"reference" => "c",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_website_order`", "on"=>array("`store_id`" => "`web_order_website_id`"))
															),
								"`app_website_order_product`" => array(
<<<<<<< HEAD
															"column"=> array("`wo_product_name`", "`wo_product_sku`", "`wo_product_srno`"),
=======
															"column"=> array("`wo_product_name`", "`wo_product_sku`", "SUM(`wo_product_quantity`) AS wo_product_quantity"),
>>>>>>> 77a717f (Version 2)
															"reference" => "p",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_website_order`", "on"=>array("`wo_web_order_id`" => "`web_order_id`"))
															),
								"`app_customer_address`" => array(
															"column"=> array("`customer_address_country`"),
															"reference" => "q",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_website_order`", "on"=>array("`customer_address_id`" => "`web_order_address_id`"))
															)		
								),
					"ORDER"	=>array("`store_name`", "`store_name`", "`web_order_number`", "`web_order_created_date`","`customer_fname`", "`web_order_total_amount`", "`web_order_total_quantity`", " (now()-`web_order_created_date`) ")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		if($filter != NULL && count($filter)){
			foreach($filter as $field => $values){
				$filedCondArray = array();
				if($field == "web_order_created_date_from"){
					if($values)
						$filedCondArray[] = array("DATE(web_order_created_date)", ">=", sanitizePostData($values));
				}
				elseif($field == "web_order_created_date_to"){
					if($values)
						$filedCondArray[] = array("DATE(web_order_created_date)", "<=", sanitizePostData($values));
				}
				else
				{
					if(is_array($values)){
						foreach($values as $_val){
							$voa = explode("|||", $_val);
							$filedCondArray[] = array("".$field, isset($voa[1]) ? $voa[1] : "=", sanitizePostData($voa[0]));
						}
					}
					else{
						$voa = explode("|||", $values);
						$filedCondArray[] = array("".$field, isset($voa[1]) ? $voa[1] : "=", sanitizePostData($voa[0]));
					}
				}
				$conditionArray[] = $filedCondArray;	
			}
		}
		
		if(isCustomer()){
			$conditionArray[] = array("`web_order_customer_id`", "=", getLoginId());	
		}
		
		if($isUnderCustomerReview){
			$conditionArray[] = array("`web_order_under_customer_review`", " is not ", 'NULL');	
		}
		$this->condition = $conditionArray;
		
										
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`web_order_id`");
			
		
		$sql = $this->getSql();
		$_SESSION['WEB-ORDER']['WEBORDER'] = $this->SqlExceptLimit;
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		//$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		//$num_rows_total= $dbcTotal->db_num_rows($resultTotal);
		$num_rows_total = $this->getTotalCount();
<<<<<<< HEAD
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array(), "sql" => $sql );
=======
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array(), "sql" => $this->SqlExceptLimit );
>>>>>>> 77a717f (Version 2)
		$pa = new ProductAvailbility();
		while($row = $dbc->db_fetch_assoc(true)){	
			$availability="Not Checked";
			$paRecord = $pa->getLastAvailbility('O', $row['web_order_id']);
			if(!empty($paRecord)){
				$availability="<span class='text-{$paRecord['pro_avail_class']}'>{$paRecord['pro_avail_stock_status']}</span>";
			}
			$viewStatus = viewText($row["wc_status_name"]).'<br/>';
			
			$viewStatus.= $row['web_order_status'] == 3 ? '<i title="Order Cancelled" class="fa text-danger fa-circle"></i>&nbsp;' : '<i title="Order Placed" class="fa text-default fa-circle"></i>&nbsp;';
			
			$viewStatus.= $row['web_order_picking_user'] != 0 ? '<i title="Order Picked up" class="fa text-warning fa-circle"></i>&nbsp;' : '<i title="Not Picked yet" class="fa fa-circle-o"></i>&nbsp;';
			
			$viewStatus.= $row['web_order_packing_user'] != 0 ? '<i title="Order Packed" class="fa text-info fa-circle"></i>&nbsp;' : '<i title="Not Packed yet" class="fa fa-circle-o"></i>&nbsp;';
			
			$viewStatus.= $row['web_order_process_user'] != 0 ? '<i title="Order Processed" class="fa text-success fa-circle"></i>&nbsp;' : '<i title="Not Processed yet" class="fa fa-circle-o"></i>&nbsp;';
			
			$viewStatus  = rtrim($viewStatus, '&nbsp;');
			$isPremium = strpos($row['product_premium'], "1") !== false ? true : false;
			
			$output["data"][] = array(
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".$app->imagePath($row["store_icon"])."\" style=\"height:24px; width:24px;\"><span class=\"avatar-status badge-".($row['web_order_is_paid']=='Yes'?'success':($row['web_order_is_paid']=='No'?'danger':'warning'))."\"></span></div><div class='meta_detail_$row[web_order_id]'>".($isPremium ? "<img title=\"Contains Premium Product\" src=\"".$app->imagePath(PREMIUM_ICON)."\" style=\"height:20px; width:20px;\">":"").($row['web_order_tracking_mark_by'] != 0 ? "<img src=\"".$app->imagePath('img/track.png')."\" style=\"height:20px; width:24px;\">":"").($row['web_order_shipment_status'] ? '<span title="'.$row['web_order_shipment_status'].'">'.($row['web_order_shipment_status'] == SHIPMENT_STATUS_SHIPPED ? '<i class="fa fa-truck text-success fa-fw"></i>':'<i class="fa fa-truck text-warning fa-fw"></i>').'</span>' : "")."</div>".(isAdmin() ? "<a data-quick-view=\"true\" data-toggle=\"modal\" data-target=\"#appModalQuick\" data-quick-url=\"viewweborder/{$row['web_order_id']}\" class=\"badge badge-default text-dark\">View</a>":""),		
								viewText($row["store_name"])."<br/><img clas='img img-responsive' style='height:26px;' src='".$app->basePath('img/system/gateway/'.$row["web_order_payment_method"].'-min.png')."' alt='".$row["web_order_payment_method"]."'/>",
								"<span data-trigger=\"hover\" data-toggle=\"popover-ajax\"  data-popover-action=\"order\" data-popover-id=\"{$row['web_order_id']}\">".viewText($row["web_order_number"])."</span>"."<p class='text-muted'>$row[product_sku]</p>",
								dateView($row["web_order_created_date"],"SMALL")."<br/>".$availability,
								viewText($row["customer_fname"] . " ". $row["customer_lname"]),
								viewText($row["web_order_total_amount"]).' '.$row['web_order_currency'],
								$row["web_order_total_quantity"],
								$viewStatus.(($row['web_order_status'] == 3 || $row['web_order_status'] == 1) ? "" : (orderDelayLevel($row["web_order_created_date"]))),
								isAdmin() ? "<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><span class=\"sr-only\">Toggle Dropdown</span></button>
  <div class=\"dropdown-menu dropdown-menu-right\">
    ".(isAdmin()?	
    ("<a class=\"dropdown-item redirect\" href=\"viewweborder/".$row['web_order_id']."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::WOINVOICE($row['web_order_id'])."');\"><i class=\"fa fa-file-pdf-o fa-fw text-danger\"></i> Download Invoice</a>
	<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[web_order_id]|O', '<img class=\'img-avatar\' src=\'".$app->imagePath($row["store_icon"])."\' style=\'height:24px; width:24px;\'> #$row[web_order_number] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>
	<a class=\"dropdown-item redirect\" href=\"addshipment/weborder/".$row['web_order_id']."\"><i class=\"fa fa-truck text-warning fa-fw\"></i> Shipment</a>
	<a class=\"dropdown-item redirect\" href=\"addrefund/O/".$row['web_order_id']."\"><i class=\"fa fa-registered text-danger fa-fw\"></i> Refund</a>
	".($row['web_order_tracking_mark_by'] == 0 ? "<a class=\"dropdown-item tracking_$row[web_order_id]\" href=\"javascript:markTracking($row[web_order_id])\"><img src=\"".$app->imagePath('img/track.png')."\" style=\"height:18px; width:20px;\">&nbsp; Mark Tracking</a>":"")."
	<a class=\"dropdown-item\" href=\"javascript:requestFeedback('".$row['web_order_id']."', 'O', '".$row['customer_id']."')\"><i class=\"fa fa-comments-o fa-fw\"></i> Request Feedback</a>
<a class=\"dropdown-item input-group\" href=\"javascript:void(0)\"><label>Update Priority</label><br/><input class=\"form-control drop-down-input\" type=\"number\" min=\"0\" max=\"10000\" value=\"{$row['web_order_priority']}\"><button type=\"button\" data-id=\"{$row['web_order_id']}\" class=\"btn btn-sm btn-info input-group-addon drop-down-btn btn-update-order-priority\">Save</button></a>"):"")." </div></div>" : "<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[web_order_id]|O', '<img class=\'img-avatar\' src=\'".$app->imagePath($row["store_icon"])."\' style=\'height:24px; width:24px;\'> #$row[web_order_number] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>",
								$_SESSION['app_theme'] != 'dark' ? $row["wc_status_color_code"] : null,
								"viewweborder/".$row['web_order_id']
			);
		}
		return json_encode($output);		
	}
	function getJsonPendingRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL){
		Modal::load(array('ProductAvailbility'));		
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
						"`app_website_order`" => array(
							"column"=> array(
								"`web_order_id`", 
								"`web_order_number`",  "`web_order_created_date`", 
								 "`web_order_is_paid`", 
								 "`web_order_priority`", 
								"web_order_status", 
								"web_order_assign_technician",
								 "GROUP_CONCAT(DISTINCT CONCAT('<span class=\"badge badge-pill-xs badge-info\">', (SELECT SUM(`wo_product_quantity`) FROM `app_website_order_product` WHERE `wo_web_order_id` = a.`web_order_id` AND p.`wo_product_sku` = `wo_product_sku`), '</span> ',`wo_product_name`, '-<b>', `wo_product_sku`, '</b>', '<a target=\"new\" href=\"',`wo_product_url`,'\" class=\"badge badge-info badge_product_image\" data-src=\"', `wo_product_image` ,'\">', `web_order_currency`, ' ',`wo_product_sell_price`, '</a>') SEPARATOR '#|#') AS products",
								 "GROUP_CONCAT(`wo_product_premium` SEPARATOR ', ') AS product_premium"),
							"reference" => "a",
							"join" => NULL
							),								
						"`app_store_master`" => array(
							"column"=> array("`store_name`", "`store_icon`"),
							"reference" => "c",
							"join" => array("type"=>"INNER JOIN", "table"=>"`app_website_order`", "on"=>array("`store_id`" => "`web_order_website_id`"))
							),								
						"`app_website_order_fulfillment`" => array(
							"column"=> array("`ff_status`"),
							"reference" => "d",
							"join" => array("type"=>"LEFT JOIN", "table"=>"`app_website_order`", "on"=>array("`ff_order_id`" => "`web_order_id`"))
							),
						"`app_website_order_product`" => array(
<<<<<<< HEAD
							"column"=> array("`wo_product_name`", "`wo_product_sku`", "`wo_product_srno`"),
=======
							"column"=> array("`wo_product_name`", "`wo_product_sku`", "SUM(`wo_product_quantity`) AS wo_product_quantity"),
>>>>>>> 77a717f (Version 2)
							"reference" => "p",
							"join" => array("type"=>"INNER JOIN", "table"=>"`app_website_order`", "on"=>array("`wo_web_order_id`" => "`web_order_id`"))
							)
						),
					"ORDER"	=>array("`store_name`", "`web_order_created_date`", "`web_order_number`", "`wo_product_name`", "`comments`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		if($filter != NULL && count($filter))
		{
			
			foreach($filter as $field=>$values){
				$filedCondArray = array();
				if($field == "duration"){
					if($values == "today")
					$filedCondArray[] = " DATE(a.web_order_created_date) = CURRENT_DATE ";
					elseif($values == "month")
					$filedCondArray[] = " a.web_order_created_date >= DATE_SUB((DATE_ADD(CURRENT_DATE,INTERVAL -DAY(CURRENT_DATE)+1 DAY)), INTERVAL 1 MONTH) ";
					elseif($values == "quarter")
					$filedCondArray[] = " a.web_order_created_date >= DATE_SUB((DATE_ADD(CURRENT_DATE,INTERVAL -DAY(CURRENT_DATE)+1 DAY)), INTERVAL 3 MONTH) ";
					elseif($values == "halfyear")
					$filedCondArray[] = " a.web_order_created_date >= DATE_SUB((DATE_ADD(CURRENT_DATE,INTERVAL -DAY(CURRENT_DATE)+1 DAY)), INTERVAL 6 MONTH) ";
					elseif($values == "year")
					$filedCondArray[] = " a.web_order_created_date >= DATE_SUB((DATE_ADD(CURRENT_DATE,INTERVAL -DAY(CURRENT_DATE)+1 DAY)), INTERVAL 1 YEAR) ";
					elseif($values == "custom")
					$filedCondArray[] = " a.web_order_created_date >= DATE_SUB((DATE_ADD(CURRENT_DATE,INTERVAL -DAY(CURRENT_DATE)+1 DAY)), INTERVAL 30 DAY) ";
				}
				else
				{
					if(is_array($values)){
						foreach($values as $_val)
							$filedCondArray[] = array("a.".$field, "=", sanitizePostData($_val));
					}
					else
						$filedCondArray[] = array("a.".$field, "=", sanitizePostData($values));
				}
				$conditionArray[] = $filedCondArray;	
			}
			
			/*
			
			foreach($filter as $filed=>$values){
				$filedCondArray = array();
				if(is_array($values)){
					foreach($values as $_val)
						$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($_val));
				}
				else
					$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($values));
				$conditionArray[] = $filedCondArray;	
			}
			*/
		}
		$conditionArray[] = array("a.web_order_status", "!=", 3);
		$conditionArray[] = array("a.web_order_status", "!=", 1);
		$conditionArray[] = array("a.web_order_status", "!=", 5);
		$conditionArray[] = array("a.web_order_picking_user", "=", 0);
		$this->condition = $conditionArray;
										
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`web_order_id`");
			
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		//$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		//$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	
		$num_rows_total = $this->getTotalCount();
<<<<<<< HEAD
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array(), "sql"=>$sql);
=======
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array(), "sql"=>$this->SqlCount);
>>>>>>> 77a717f (Version 2)
		$pa = new ProductAvailbility();
		while($row = $dbc->db_fetch_assoc(true)){
			$availability="Not Checked";
			$paRecord = $pa->getLastAvailbility('O', $row['web_order_id']);
			if(!empty($paRecord)){
				$availability="<span class='text-{$paRecord['pro_avail_class']}'>{$paRecord['pro_avail_stock_status']}</span>";
			}
			$productsArr = explode('#|#', $row["products"]);
			$isPremium = strpos($row['product_premium'], "1") !== false ? true : false;
			$output["data"][] = array(
								"<span class='hidden-xs hidden-sm visible-md visible-lg'>".viewText($row["store_name"])."<br/>".($row['web_order_assign_technician']>0?"<i class='fa fa-wrench text-success'></i>":"").($isPremium ? "<img title=\"Contains Premium Product\" src=\"".$app->imagePath(PREMIUM_ICON)."\" style=\"height:20px; width:20px;\">":"")."</span><img class=\"visible-xs visible-sm hidden-md hidden-lg img-avatar\" src=\"".$app->imagePath($row["store_icon"])."\" style=\"height:24px; width:24px;\"><a data-quick-view=\"true\" data-toggle=\"modal\" data-target=\"#appModalQuick\" data-quick-url=\"viewweborder/{$row['web_order_id']}\" class=\"badge badge-default text-dark\">View</a>",
								dateView($row["web_order_created_date"],"MONTH")."</br>".orderDelayLevel($row["web_order_created_date"])."<br/>".$availability,
								"<span data-trigger=\"hover\" data-toggle=\"popover-ajax\"  data-popover-action=\"order\" data-popover-id=\"{$row['web_order_id']}\">".viewText($row["web_order_number"])."</span>".($row['ff_status'] == 1 ? "<span title=\"Fulfillment available\" class=\"badge\" style=\"background-color:#95cd55;\"><i class='fa fa-check-circle'></i> Fulfilled</span>":""),								
								"<ul class='pl-0 list-unstyled'><li>".implode("</li><li>", $productsArr)."</li></ul>",
								//viewText($row["comments"]),
								"<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[web_order_id]|O', '#$row[web_order_number] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Comments</a>",
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><span class=\"sr-only\"></span></button>
  <div class=\"dropdown-menu dropdown-menu-right\">
    ".(isAdmin()?	
    ("<a class=\"dropdown-item redirect\" href=\"viewweborder/".$row['web_order_id']."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::WOINVOICE($row['web_order_id'])."');\"><i class=\"fa fa-file-pdf-o fa-fw text-danger\"></i> Download Invoice</a>
	<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[web_order_id]|O', '<img class=\'img-avatar\' src=\'".$app->imagePath($row["store_icon"])."\' style=\'height:24px; width:24px;\'> #$row[web_order_number] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>
	<a class=\"dropdown-item redirect\" href=\"addshipment/weborder/".$row['web_order_id']."\"><i class=\"fa fa-truck text-warning fa-fw\"></i> Shipment</a>
	<a class=\"dropdown-item redirect\" href=\"addrefund/O/".$row['web_order_id']."\"><i class=\"fa fa-registered text-danger fa-fw\"></i> Refund</a>
	<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"checkOrderFulfilment('$row[web_order_id]')\"><i class=\"fa fa-cube text-success fa-fw\"></i> Check Fulfilment</a>
	<a class=\"dropdown-item input-group\" href=\"javascript:void(0)\"><label>Update Priority</label><br/><input class=\"form-control drop-down-input\" type=\"number\" min=\"0\" max=\"10000\" value=\"{$row['web_order_priority']}\"><button type=\"button\" data-id=\"{$row['web_order_id']}\" class=\"btn btn-sm btn-info input-group-addon drop-down-btn btn-update-order-priority\">Save</button></a>"):"")." </div></div>"
			);
		}
		return json_encode($output);
	}
	
	static function getOrderHistoryChart(){
		$sql = "SELECT count(`web_order_id`) as order_count,
		SUM(CASE WHEN `web_order_status` = 1 THEN 1 ELSE 0 END) as completed, 
		SUM(CASE WHEN `web_order_status` = 3 THEN 1 ELSE 0 END) as cancelled, 
		DATE_FORMAT(`web_order_created_date`,'%d-%b') as day_name, DATE_FORMAT(`web_order_created_date`,'%Y-%m-%d') as ordercolumn FROM `app_website_order` WHERE 1 GROUP BY ordercolumn order by `web_order_created_date` desc LIMIT 30 ";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[$row['day_name']] = array("total"=>$row['order_count'], "completed"=>$row['completed'], "cancelled"=>$row['cancelled']);				
			}
		}
		return $worder_array;
	}
	
	static function getOrderStatusChart($limit = 15){
		//'Total', 'Picked', 'Packed', 'Processed', 'Pending', 'Completed', 'Cancelled'
		
		//SUM(IF(web_order_picking_user != 0, 1, 0)) AS 'Picked', 
		//SUM(IF(web_order_packing_user != 0, 1, 0)) AS 'Packed', 
		$sql = "SELECT 
		DATE_FORMAT(`web_order_created_date`,'%d-%b') as date, 
		COUNT(web_order_id) as 'Total', 
		SUM(IF(web_order_status = 3, 1, 0)) AS 'Cancelled', 
		SUM(IF(web_order_process_user != 0, 1, 0)) AS 'Processed',
		SUM(IF(web_order_status = 1, 1, 0)) AS 'Completed',  
		SUM(IF(web_order_status = 2, 1, 0)) AS 'Waiting' 
		 FROM `app_website_order` WHERE 1 GROUP BY DATE_FORMAT(`web_order_created_date`,'%Y-%m-%d') order by `web_order_created_date` desc LIMIT $limit";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return array_reverse($worder_array);
	}
	
	function getWebOrderCurrency(){
		$sql = "SELECT `web_order_currency` , COUNT(`web_order_id`) as record FROM `".$this->table_name."` GROUP BY `web_order_currency` ORDER BY `web_order_currency`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
	static function getCurrentOrdersByStatus($status = null){
		$showTillDays = 5;
		global $app;
		$consition = null;
		if($status == 'PENDING')
			$condition = "a.web_order_status != '3'  AND  a.web_order_status != '1'  AND  a.web_order_picking_user = 0";
		elseif($status == 'PICKED')
			$condition = "a.web_order_status != '3'  AND  a.web_order_status != '1'  AND  a.web_order_picking_user != 0 AND  a.web_order_packing_user = 0";
		elseif($status == 'PACKED')
			$condition = "a.web_order_status != '3'  AND  a.web_order_status != '1'  AND  a.web_order_packing_user != 0 AND a.`web_order_process_user` = 0";
		elseif($status == 'PROCESSED')
			$condition = "a.web_order_status != '3'  AND  a.web_order_status != '1'  AND  a.web_order_process_user != 0";
		elseif($status == 'COMPLETED')
			$condition = "a.web_order_status = '1' AND DATE_FORMAT(a.`web_order_complete_time`,'%Y-%m-%d') >= DATE_ADD(CURRENT_DATE , INTERVAL - $showTillDays DAY)";
		elseif($status == 'CANCELLED')
			$condition = "a.web_order_status = '3' AND DATE_FORMAT(a.`web_order_cancel_time`,'%Y-%m-%d') >= DATE_ADD(CURRENT_DATE , INTERVAL - $showTillDays DAY)";
			
		$resultArray = array();
		if($condition != null)	
		{
			$sql = "SELECT a.`web_order_id`, a.`web_order_number`, a.`web_order_created_date`, a.`web_order_is_paid`, a.web_order_status, c.`store_name`, c.`store_icon` FROM  `app_website_order` AS a  
			INNER JOIN `app_store_master` AS c ON ( c.`store_id` = a.`web_order_website_id`)   
			WHERE $condition ORDER BY `web_order_created_date` asc";
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			if($dbc->db_num_rows()>0)
			{			
				while($row = $dbc->db_fetch_assoc(true))
				{
					$row["store_icon"] = $app->imagePath($row["store_icon"]);
					$resultArray[] = $row;				
				}
			}
		}
		return $resultArray;
	}
	
	
	function getCountryFilteration(){
		$sql = "SELECT b.`customer_address_country`, COUNT(a.`web_order_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_customer_address` AS b ON a.`web_order_address_id` = b.`customer_address_id` GROUP BY b.`customer_address_country` ORDER BY b.`customer_address_country`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
	
	function getWebsiteFilteration(){
		$sql = "SELECT a.`web_order_website_id`, b.`store_name`, b.`store_title`, b.`store_icon`, COUNT(a.`web_order_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`web_order_website_id` = b.`store_id` GROUP BY `web_order_website_id` ORDER BY `store_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
	function getStatusFilteration(){
		$sql = "SELECT a.`web_order_status`, b.`wc_status_name`, b.`wc_status_color_code`, COUNT(a.`web_order_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_wc_status` AS b ON a.`web_order_status` = b.`wc_status_id` GROUP BY `web_order_status` ORDER BY `wc_status_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
	
	function getTechnicianFilteration($pendingOnly = false){
		$condition = $pendingOnly ? " AND (a.`web_order_status` != '1' AND a.`web_order_status` != '3')":"";
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`web_order_id`) as record FROM `".$this->table_name."` AS a LEFT JOIN `app_system_user` AS b on a.`web_order_assign_technician` = b.`user_id` WHERE a.`web_order_assign_technician` != 0 GROUP BY a.`web_order_assign_technician` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	function getUserFilterationByName($user_colum_name, $pendingOnly = false){
		$condition = $pendingOnly ? " AND (a.`web_order_status` != '1' AND a.`web_order_status` != '3')":"";
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`web_order_id`) as record FROM `".$this->table_name."` AS a LEFT JOIN `app_system_user` AS b on a.`$user_colum_name` = b.`user_id` WHERE a.`$user_colum_name` != 0 GROUP BY a.`$user_colum_name` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	
	function getPaymentMethodFilteration(){
		$sql = "SELECT `web_order_payment_method`, COUNT(`web_order_id`) as record FROM `".$this->table_name."` GROUP BY `web_order_payment_method` ORDER BY `web_order_payment_method`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
	
	function getWeborderReportData($condition = ""){
		$condition = trim(trim($condition), "''");
		$resultArray = array();
		$sql = "SELECT SUM(a.`web_order_total_amount`) AS amount, SUM(a.`web_order_total_quantity`) AS quantity, a.`web_order_currency` as currency, b.`wc_status_name`, b.`wc_status_color_code` FROM `".$this->table_name."` AS a INNER JOIN `app_wc_status` AS b ON a.`web_order_status` = b.`wc_status_id` $condition GROUP BY `web_order_status`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$total_order_data = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$total_order_data[] = $row;				
			}
		}
		
		$sql = "SELECT SUM(a.`web_order_total_amount`) AS amount, a.web_order_payment_method FROM `".$this->table_name."` AS a $condition GROUP BY a.web_order_payment_method";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$total_payment_data = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$total_payment_data[] = $row;				
			}
		}
															
		$sql = "SELECT COUNT(a.`web_order_id`) AS website, b.`store_name`, b.`store_icon` FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`web_order_website_id` = b.`store_id` $condition GROUP BY b.`store_name` ORDER BY b.`store_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$total_website_data = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$total_website_data[] = $row;				
			}
		}
		
		$resultArray['order'] = $total_order_data;
		$resultArray['payment'] = array(
											'label' => array_column($total_payment_data, 'web_order_payment_method'),
											'value' => array_column($total_payment_data, 'amount')
										);
		$resultArray['website'] = array(
											'label' => array_column($total_website_data, 'store_name'),
											'value' => array_column($total_website_data, 'website')
										);		
		return $resultArray;
	}
	
	function getWebsiteOrderStatistics($conditions, $web_order_currency = 'GBP'){
		$sql = "SELECT b.`store_name`, b.`store_icon`, 
		COUNT(*) as total_order,
		SUM(a.`web_order_total_amount`) as total_amount,
		SUM(CASE WHEN a.`web_order_status` = 1 THEN 1 ELSE 0 END) AS completed, 
		SUM(CASE WHEN a.`web_order_status` = 1 THEN a.`web_order_total_amount` ELSE 0 END) AS completed_amount, 
		SUM(CASE WHEN a.`web_order_status` = 3 THEN 1 ELSE 0 END) AS cancelled, 
		SUM(CASE WHEN a.`web_order_status` = 3 THEN a.`web_order_total_amount` ELSE 0 END) AS cancelled_amount 
		FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`web_order_website_id` = b.`store_id` $conditions AND `web_order_currency` = '$web_order_currency' GROUP BY  b.`store_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$total_website_data = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['total_amount'] = round($row['total_amount'],2);
				$row['completed_amount'] = round($row['completed_amount'],2);
				$row['cancelled_amount'] = round($row['cancelled_amount'],2);
				$total_website_data[] = $row;				
			}
		}
		return $total_website_data;
	}
	
	function getDelayOrder($day = 30){
		$sql = "SELECT a.`web_order_id`, a.`web_order_number`, a.`web_order_created_date`, b.`store_name`, b.`store_icon` FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`web_order_website_id` = b.`store_id` WHERE a.`web_order_status` != 3 AND a.`web_order_status` != 1 AND a.`web_order_created_date` >= DATE_ADD(CURRENT_DATE , INTERVAL -$day DAY) AND  a.`web_order_created_date` <= DATE_ADD(CURRENT_DATE , INTERVAL - 4 DAY)";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$total_website_data = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$total_website_data[] = $row;				
			}
		}
		return $total_website_data;
	}
	
	function getFulfillOrder($day = 30){
		$sql = "SELECT a.`web_order_id`, a.`web_order_number`, a.`web_order_created_date`, b.`store_name`, b.`store_icon` FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`web_order_website_id` = b.`store_id` INNER JOIN `app_website_order_fulfillment` AS c ON c.`ff_order_id` = a.`web_order_id` WHERE a.`web_order_status` NOT IN (1,3,5) AND a.`web_order_created_date` >= DATE_ADD(CURRENT_DATE , INTERVAL -$day DAY) GROUP BY a.`web_order_id`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$total_website_data = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$total_website_data[] = $row;				
			}
		}
		return $total_website_data;
	}
	
	function getPendingOrderExportSql($condition){
		return "SELECT a.`web_order_id`, a.`web_order_number`, a.`web_order_created_date`, a.`web_order_status`,b.`store_name`, b.`store_icon` FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`web_order_website_id` = b.`store_id` $condition";
	}
	
	static function checkFulfillment(){
		$dbcT 	= 	new DB();
		$dbcT->db_query("TRUNCATE TABLE `app_website_order_fulfillment`");
		
		$sql = "SELECT a.`web_order_id`,p.`wo_product_sku` FROM `app_website_order` AS a INNER JOIN `app_website_order_product` AS p ON ( p.`wo_web_order_id` = a.`web_order_id` ) WHERE a.web_order_status != '3' AND a.web_order_status != '1'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{					
			while($row = $dbc->db_fetch_assoc(true))
			{
				if(WebsiteOrderProduct::checkProductAvailability($row['wo_product_sku'])){
					$sqlFulfill = "INSERT INTO `app_website_order_fulfillment`(`ff_order_id`, `ff_status`) VALUES ('$row[web_order_id]','1')";
					$dbcF 	= 	new DB();
					$dbcF->db_query($sqlFulfill);
				}				
			}
		}
		return true;
	}
	
	static function todaysOrderActivity(){
		$sql = "
		SELECT COUNT(`web_order_id`) AS records, 'Picked' AS label, '#FF9900' AS color  FROM `app_website_order` WHERE DATE_FORMAT(`web_order_picking_time`,'%Y-%m-%d') = CURRENT_DATE UNION 
		SELECT COUNT(`web_order_id`) AS records, 'Packed' AS label, '#3399FF' AS color FROM `app_website_order` WHERE DATE_FORMAT(`web_order_packing_time`,'%Y-%m-%d') = CURRENT_DATE  UNION 
		SELECT COUNT(`web_order_id`) AS records, 'Processed' AS label, '#8BC34A' AS color FROM `app_website_order` WHERE DATE_FORMAT(`web_order_process_time`,'%Y-%m-%d') = CURRENT_DATE UNION 
		SELECT COUNT(`web_order_id`) AS records, 'Completed' AS label, '#00CC66 ' AS color FROM `app_website_order` WHERE DATE_FORMAT(`web_order_complete_time`,'%Y-%m-%d') = CURRENT_DATE UNION 
		SELECT COUNT(`web_order_id`) AS records, 'Canceled' AS label, '#FF3300' AS color FROM `app_website_order` WHERE DATE_FORMAT(`web_order_cancel_time`,'%Y-%m-%d') = CURRENT_DATE";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$results_array = array();
		if($dbc->db_num_rows()>0)
		{					
			while($row = $dbc->db_fetch_assoc(true))
			{
				$results_array[] = $row;	
			}
		}
		return $results_array;
	}
	
	function fetchOrderInvoice(){
		$orderData = $this->getDetails();
		if($orderData['web_order_invoice_image'] == '')
		{
			$store = new Store($orderData['web_order_website_id']);
			$storeData = $store->getDetails();
			if($storeData['store_has_invoice'] == 1){
				$timestamp = time();
				$domain = $storeData['store_link'];
				$order_id = $orderData['web_order_id'];
				$endpointurl = "$domain/invoicedata.php?order_id=$order_id";
				$requestData = array(
					"domain" 	=> $domain,
					"order_id" 	=> $order_id,
					"hash_salt" => md5($timestamp.$domain.$order_id),
					"timestamp" => $timestamp
				);
				
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $endpointurl );
				curl_setopt( $ch, CURLOPT_POST, true );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $requestData );
				$response = curl_exec($ch);
				curl_close($ch);
				if($response){
					$responseData = json_decode($response, true);
					if($responseData['status'] == "200"){
						$invoicefile = file_get_contents("$domain/".$responseData['file']);
						$uploaded_image = "upload/weborder/invoice/".getDirectorySeparatorPath()."invoice_".$order_id."_".time().".pdf";
						$tempFile = "temp_invoice_{$order_id}_".time().".pdf";
						saveTempFile($tempFile, $invoicefile);
						if(move_file(getTempFilePath($tempFile), BP.$uploaded_image)){
							$this->update(array(
												"web_order_invoice_image" 	=> $uploaded_image,
												"web_order_invoice_date" 	=> "NOW()"
											));
						}
					}
					return $response;
				}
			}
			else{
				return json_encode(array(300, "Invoice fetch not aloowed on this store"));
			}
		}
		else{
			return json_encode(array(300, "Invoice already fetched or uploaded."));
		}
	}
	
	function geInvoiceProductsQuery($currency = 'GBP', $vat=SALES_VAT_PERCENTAGE)
	{
		return "SELECT CONCAT('$currency ',ROUND((`wo_product_sell_price`*100)/(100+$vat),2)) as product_unit_price, CONCAT('$currency ', ROUND((((`wo_product_sell_price`*100)/(100+$vat)) * `wo_product_quantity`), 2)) as product_amount,  `wo_product_quantity` as product_quantity, CONCAT(`wo_product_name`, CASE WHEN (`wo_product_options` IS NOT NULL) THEN CONCAT(' - <b>', `wo_product_options`, '</b>') ELSE '' END) as product_name, CONCAT('$currency ',ROUND((((`wo_product_sell_price`*100)/(100+$vat)*$vat/100) * `wo_product_quantity`),2)) as product_vat FROM `app_website_order_product` 
		WHERE `wo_web_order_id` = '".$this->id."' ORDER BY wo_id";	
	}
	
	function getInvoiceSum($vat=null)
	{
		$vat = $vat === null ? SALES_VAT_PERCENTAGE : $vat;
		$sql = "SELECT SUM(`wo_product_quantity`) AS items, SUM(ROUND(((`wo_product_sell_price`*100)/(100+$vat) * $vat/100),2) * `wo_product_quantity`) as product_sum_vat, SUM(ROUND((`wo_product_sell_price`*100)/(100+$vat),2) * `wo_product_quantity`) as product_sum_amount FROM `app_website_order_product` WHERE `wo_web_order_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			$data = $dbc->db_fetch_assoc(true);
			return $data;
		}
	}
}

class WebsiteOrderProduct extends DB{
	public $id;
	public $key_id	=	"wo_id";
	public $wo_web_order_id = 0;
	public $table_name	=	"app_website_order_product";
	function __construct($wo_id=0){
				
		$this->id	=	$wo_id;
	}
	
	function getList($wo_web_order_id){
		$sql = "SELECT * FROM `app_website_order_product` WHERE `wo_web_order_id` = '$wo_web_order_id' ORDER BY `wo_product_sku`";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$product_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$product_array[] = $row;
			}
			return $product_array;
		}
		return NULL;
	}
	
	function getOrderGroupedProduct($wo_web_order_id){
		$sql = "SELECT `wo_web_order_id`, `wo_product_sku`, `wo_product_url`, `wo_product_image`,  COUNT(`wo_id`) AS ordered_quantity FROM `app_website_order_product` WHERE `wo_web_order_id` = '$wo_web_order_id' ORDER BY `wo_product_sku`";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$product_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$product_array[] = $row;
			}
			return $product_array;
		}
		return NULL;
	}
	
	function getIncompleteSkuList(){
		$sql = "SELECT `wo_id` AS id, `wo_product_sku` AS sku FROM `app_website_order_product` WHERE `wo_product_sku` != '' AND (`wo_product_url` is null || `wo_product_url` = '') AND `wo_product_fetch_count` = '0' ORDER BY `wo_id` DESC LIMIT 10";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$product_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$product_array[] = $row;
			}
			return $product_array;
		}
		return NULL;
	}
	
	static function getLtestOrderedProducts($limit = 10){
		$sql = "SELECT DISTINCT `wo_web_order_id`, `wo_product_sku`, `wo_product_url`, `wo_product_image` FROM `app_website_order_product` WHERE `wo_product_url` != '' ORDER BY `wo_id` DESC LIMIT $limit";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$product_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$product_array[] = $row;
			}
			return $product_array;
		}
		return NULL;
	}
	
	function getMultipleQtyProductList(){
		$sql = "SELECT * FROM `app_website_order_product` WHERE `wo_product_quantity` > 1";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$product_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$product_array[] = $row;
			}
			return $product_array;
		}
		return NULL;
	}
	
	static function checkProductAvailability($sku, $withOutOfstock = false){
		global $app;
		$multiSqlArr = explode(" ", $sku);
		$multiSqlString = "'".implode("', '", $multiSqlArr)."'";
		$skuPrefixArr = array('MFS', 'CKP', 'SPD');
		$sku = trim(strtoupper($sku));
		$baseSku = preg_replace("/[^0-9 ]/", '', $sku);
		$sqlArr = array();
		$likekeyword = str_replace(' ', '%', $sku);
		$conditionBatchProduct = $withOutOfstock ? "" :" AND `product_in_stock` = '1'  ";
		$conditionCollectionProduct = $withOutOfstock ? "" :" AND `wc_process_item_stock` = '1' ";
		foreach($skuPrefixArr as $_prefix){
			$searchSku = $_prefix.$baseSku;
			$sqlArr[] = "SELECT 'BatchProduct' as origin, `product_in_stock` as stock, CONCAT('updatebatchproduct/',`product_id`) as link, `product_id` as id, `product_code` as code, CONCAT('B|',`product_id`) as lot_code, `product_name` as name, `product_serial_number` as srno, `product_sku` as sku, `product_age_date` as age, `product_store_location` as location, `product_batch_code` as batch_code FROM `app_batch_product` WHERE `product_status` = '1' $conditionBatchProduct AND (`product_sku` = '$searchSku' OR `product_code` = '$sku' OR UPPER(`product_serial_number`) IN ($multiSqlString) OR UPPER(`product_name`) LIKE '%$likekeyword%') 
		UNION 
		SELECT  'CollectionProcess' as origin, `wc_process_item_stock` as stock, CONCAT('updatewcprocessitem/', `wc_process_asset_code`) as link, `wc_process_id` as id, `wc_process_asset_code` as code, CONCAT('P|',`wc_process_asset_code`) as lot_code, `wc_process_item_name` as name, `wc_process_item_sr_no` as srno, `wc_process_item_sku` as sku, `wc_process_age_date` as age, `wc_process_item_location` AS location, (SELECT `values_data` FROM `app_wc_item_attributes_values` WHERE `values_wc_process_id` = `wc_process_id` AND `values_attribute_id` = '57') AS batch_code FROM app_wc_process WHERE (`wc_process_item_sku` = '$searchSku'  OR `wc_process_asset_code` = '$sku' OR UPPER(`wc_process_item_sr_no`)  IN ($multiSqlString) OR UPPER(`wc_process_item_name`) LIKE '%$likekeyword%') $conditionCollectionProduct";
		}
		$sql = implode(' UNION ', $sqlArr);
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$product_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				if($row['origin'] == 'CollectionProcess')
					$row['label'] = DOC::CASSETCODE($row["id"], $row["code"]);
				elseif($row['origin'] == 'BatchProduct')
					$row['label'] = DOC::BATCHPROCODE($row['id']);
				$row['location'] = "<img class=\"img-avatar\" src=\"".$app->basePath('img/system/flag/flag_'.strtolower($row['location']).'.png')."\" height=\"16px\"> <span>{$row['location']}</span>";
				$row['url'] = $app->basePath($row['link']);
				$row['age'] = $row['age'] != "" ? ("<span class='d-none'>".strtotime($row['age'])."</span>".dateView($row['age'], 'NOW')."<br/>".dateView($row['age'], 'DATE')):'N/A';
				$product_array[] = $row;
			}
			return $product_array;
		}
		return NULL;
	}
	
	static function checkProductStockStatus($sku){
		global $app;
		$skuPrefixArr = array('MFS', 'CKP', 'SPD');
		$sku = trim(strtoupper($sku));
		$baseSku = preg_replace("/[^0-9 ]/", '', $sku);
		$sqlArr = array();
		foreach($skuPrefixArr as $_prefix){
			$searchSku = $_prefix.$baseSku;
			$sqlArr[] = "SELECT 'BatchProduct' as origin, `product_in_stock` AS in_stock, CONCAT('updatebatchproduct/',`product_id`) as link, `product_id` as id, `product_code` as code, CONCAT('B|',`product_id`) as lot_code, `product_name` as name, `product_serial_number` as srno, `product_sku` as sku, `product_age_date` as age, `product_store_location` as location FROM `app_batch_product` WHERE `product_status` = '1' AND `product_sku` = '$searchSku' 
		UNION 
		SELECT  'CollectionProcess' as origin, `wc_process_item_stock` AS in_stock, CONCAT('updatewcprocessitem/', `wc_process_asset_code`) as link, `wc_process_id` as id, `wc_process_asset_code` as code, CONCAT('P|',`wc_process_asset_code`) as lot_code, `wc_process_item_name` as name, `wc_process_item_sr_no` as srno, `wc_process_item_sku` as sku, `wc_process_age_date` as age, `wc_process_item_location` AS location FROM app_wc_process WHERE `wc_process_item_sku` = '$searchSku'";
		}
		$sqlArr[] = "SELECT 'BatchProduct' as origin, `product_in_stock` AS in_stock, CONCAT('updatebatchproduct/',`product_id`) as link, `product_id` as id, `product_code` as code, CONCAT('B|',`product_id`) as lot_code, `product_name` as name, `product_serial_number` as srno, `product_sku` as sku, `product_age_date` as age, `product_store_location` as location FROM `app_batch_product` WHERE `product_status` = '1' AND (UPPER(`product_serial_number`) = '$sku' OR UPPER(`product_sku`) = '$sku' OR UPPER(`product_code`) = '$sku') 
		UNION 
		SELECT  'CollectionProcess' as origin, `wc_process_item_stock` AS in_stock, CONCAT('updatewcprocessitem/', `wc_process_asset_code`) as link, `wc_process_id` as id, `wc_process_asset_code` as code, CONCAT('P|',`wc_process_asset_code`) as lot_code, `wc_process_item_name` as name, `wc_process_item_sr_no` as srno, `wc_process_item_sku` as sku, `wc_process_age_date` as age, `wc_process_item_location` AS location FROM app_wc_process WHERE (UPPER(`wc_process_item_sr_no`) = '$sku' OR UPPER(`wc_process_item_sku`) = '$sku' OR UPPER(`wc_process_asset_code`) = '$sku') ORDER BY in_stock desc";
		
		$sql = implode(' UNION ', $sqlArr);
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$product_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				if($row['origin'] == 'CollectionProcess')
					$row['label'] = DOC::CASSETCODE($row["id"], $row["code"]);
				elseif($row['origin'] == 'BatchProduct')
					$row['label'] = DOC::BATCHPROCODE($row['id']);
				$row['location'] = "<img class=\"img-avatar\" src=\"".$app->basePath('img/system/flag/flag_'.strtolower($row['location']).'.png')."\" height=\"16px\"> <span>{$row['location']}</span>";
				$row['url'] = $app->basePath($row['link']);
				$row['age'] = $row['age'] != "" ? ("<span class='d-none'>".strtotime($row['age'])."</span>".dateView($row['age'], 'NOW')."<br/>".dateView($row['age'], 'DATE')):'N/A';
				$product_array[] = $row;
			}
			return $product_array;
		}
		return NULL;
	}
	
	
}

class WebsiteOrderProductMedia extends DB{
	public $id;
	public $key_id		=	"wpoi_id";
	public $table_name	=	"app_website_order_product_image";
	public $status		=	"wpoi_status";	
	public $wpoi_wo_id	=	"wpoi_wo_id";
	function __construct($wpoi_id=0){
				
		$this->id	=	$wpoi_id;
	}
	
	function getImageList($wpoi_wo_id){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `".$this->wpoi_wo_id."` = '$wpoi_wo_id' AND `".$this->status."` = '1' ";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$product_image_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$product_image_array[] = $row;
			}
			return $product_image_array;
		}
		return NULL;
	}
	
}
class ProfitSummary extends DB{
	function __construct($id=0){
				
		$this->id	=	$id;
	}
	
	static function setBasePrice(){
		
		$sql = "SELECT p.`wo_id`, p.`wo_product_purchase_amount`, p.`wo_product_sell_price`, p.`wo_product_shipping_price`, a.`web_order_currency` FROM `app_website_order` AS a INNER JOIN `app_website_order_product` AS p ON ( p.`wo_web_order_id` = a.`web_order_id` ) WHERE 		
		(
			(p.`wo_product_purchase_base_amount` = '0' AND p.`wo_product_purchase_amount` != '0') OR 
			(p.`wo_product_sell_base_price` = '0' AND p.`wo_product_sell_price` != '0') OR 
			(p.`wo_product_shipping_base_price` = '0' AND p.`wo_product_shipping_price` != '0')
		)";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$wo_product = new WebsiteOrderProduct($row['wo_id']);
				$wo_product->update(array(
					"wo_product_purchase_base_amount" =>  getBaseAmount($row['wo_product_purchase_amount'], $row['web_order_currency']),
					"wo_product_sell_base_price" => getBaseAmount($row['wo_product_sell_price'], $row['web_order_currency']),
					"wo_product_shipping_base_price" => getBaseAmount($row['wo_product_shipping_price'], $row['web_order_currency'])
				));
			}
		}
	}
	function getProfitSummary($interval){
		self::setBasePrice();
		$defaultShipmentPrice = 7; // IN GBP
		$condition = "";
		if($interval == "7"){
			$condition = "a.`web_order_created_date` >= (CURDATE() - INTERVAL 7 DAY)";
			$groupby = "DAY(a.`web_order_created_date`)";
			$label = "DAYNAME(a.`web_order_created_date`)";
		}
		elseif($interval == "15"){
			$condition = "a.`web_order_created_date` >= (CURDATE() - INTERVAL 15 DAY)";
			$groupby = "DAY(a.`web_order_created_date`)";
			$label = "DAY(a.`web_order_created_date`)";
		}
		elseif($interval == "30"){
			$condition = "a.`web_order_created_date` >= (CURDATE() - INTERVAL 30 DAY)";
			$groupby = "DAY(a.`web_order_created_date`)";
			$label = "DAY(a.`web_order_created_date`)";
		}
		elseif($interval == "cm"){
			$condition = "(MONTH(a.`web_order_created_date`) = MONTH(CURDATE()) AND YEAR(a.`web_order_created_date`) = YEAR(CURDATE()))";
			$groupby = "DAY(a.`web_order_created_date`)";
			$label = "DAY(a.`web_order_created_date`)";
		}
		elseif($interval == "lm"){
			$condition = "MONTH(a.`web_order_created_date`) = MONTH((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 1 MONTH) AND YEAR(a.`web_order_created_date`) = YEAR((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 1 MONTH)";
			$groupby = "DAY(a.`web_order_created_date`)";
			$label = "DAY(a.`web_order_created_date`)";
		}
		elseif($interval == "3m"){
			$condition = "a.`web_order_created_date` >= ((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 3 MONTH)";
			$groupby = "MONTH(a.`web_order_created_date`)";
			$label = "MONTHNAME(a.`web_order_created_date`)";
		}
		elseif($interval == "6m"){
			$condition = "a.`web_order_created_date` >= ((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 6 MONTH)";
			$groupby = "MONTH(a.`web_order_created_date`)";
			$label = "MONTHNAME(a.`web_order_created_date`)";
		}
		elseif($interval == "1y"){
			$condition = "a.`web_order_created_date` >= ((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 1 YEAR)";
			$groupby = "MONTH(a.`web_order_created_date`)";
			$label = "MONTHNAME(a.`web_order_created_date`)";
		}
		elseif($interval == "cy"){			
			$condition = "YEAR(a.`web_order_created_date`) = YEAR(CURRENT_DATE)";
			$groupby = "MONTH(a.`web_order_created_date`)";
			$label = "MONTHNAME(a.`web_order_created_date`)";
		}
		elseif($interval == "ly"){
			$condition = "YEAR(a.`web_order_created_date`) = YEAR((DATE_ADD(CURRENT_DATE,INTERVAL -DAY(CURRENT_DATE)+1 DAY))- INTERVAL 1 YEAR)";
			$groupby = "MONTH(a.`web_order_created_date`)";
			$label = "MONTHNAME(a.`web_order_created_date`)";
		}
			
		$sql = "SELECT COUNT(DISTINCT a.`web_order_id`) AS orders, SUM(p.`wo_product_purchase_base_amount`) AS purchase, SUM(p.`wo_product_sell_base_price`) AS sell, SUM(CASE WHEN p.`wo_product_shipping_base_price` = '0' THEN $defaultShipmentPrice ELSE p.`wo_product_shipping_base_price` END) AS shipment, $label as label FROM `app_website_order` AS a INNER JOIN `app_website_order_product` AS p ON ( p.`wo_web_order_id` = a.`web_order_id` ) WHERE a.web_order_status = '1' AND $condition GROUP BY $groupby ORDER BY a.`web_order_created_date`";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$record_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['label'] = substr($row['label'],0,3);
				$row['sell'] = round($row['sell'],2);
				$row['purchase'] = round($row['purchase'],2);
				$row['profit'] =  round($row['sell'] - $row['purchase'], 2);
				$row['shipment'] = $row['shipment'] == 0 ? 7 : $row['shipment'];
				$record_array[] = $row;
			}
			$orders = round(array_sum(array_column($record_array, 'orders')));
			$purchase = round(array_sum(array_column($record_array, 'purchase')),2);
			$sell = round(array_sum(array_column($record_array, 'sell')),2);
			$profit = round(array_sum(array_column($record_array, 'profit')),2);
			$shipment = round(array_sum(array_column($record_array, 'shipment')),2);
			return array(
				'chartData' =>array(
					'label' => array_column($record_array, 'label'),
					'purchase' => array_column($record_array, 'purchase'),
					'sell' => array_column($record_array, 'sell'),
					'profit' => array_column($record_array, 'profit')
				),
				'recordData' => array(
					'orders' => $orders,
					'purchase' => '&pound;'.number_format($purchase,2),
					'sell' => '&pound;'.number_format($sell,2),
					'profit' => '&pound;'.number_format($profit,2),
					'shipment' => '&pound;'.number_format($shipment,2),
					'realised' => '&pound;'.number_format($profit - $shipment,2)
				)
			);
		}
		else
			return NULL;
	}
	
}

?>