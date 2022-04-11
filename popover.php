<?php
include("setup.php");
if (isset($_POST['action']) && !is_array($_POST['action']) && $_POST['action'] != "") {
$action = strtolower(trim($_POST['action']));
$id = strtolower(trim($_POST['id']));
	if($action == "order"){
		$websiteOrder = new WebsiteOrder($id);
		if($websiteOrder->isExist()){
			$orderData = $websiteOrder->load();
			$websiteOrderProduct = new WebsiteOrderProduct();
			$orderProducts = $websiteOrderProduct->getOrderGroupedProduct($orderData['web_order_id']);
			$html = "<ul class=\"list-group m-0\">";
			
            $html .= "<li class=\"list-group-item d-flex list-group-item-action justify-content-between align-items-center\">Order No. - <span>{$orderData['web_order_number']}</span> </li>
					  <li class=\"list-group-item d-flex list-group-item-action justify-content-between align-items-center\">Status - <span>{$orderData['wc_status_name']}</span> </li>
                      <li class=\"list-group-item d-flex list-group-item-action justify-content-between align-items-center\">Order date - <span>".dateView($orderData['web_order_created_date'], 'DATE')."</span> </li> 
					  <li class=\"list-group-item d-flex list-group-item-action justify-content-between align-items-center\">";
			$uniQueProArray = array();
			foreach($orderProducts as $_product){
				if(!in_array($_product['wo_product_sku'], $uniQueProArray)){					
					$uniQueProArray[] = $_product['wo_product_sku'];
					$html .= "<div class=\"card-block p-0\">
                    <div class=\"bd-example\">
                      <dl class=\"row\">
                        <dt class=\"col-xs-4 text-center\"><a target=\"_blank\" href=\"{$_product['wo_product_url']}\"><img src=\"{$_product['wo_product_image']}\" width=\"80px\"><br/><center>{$_product['wo_product_sku']}</center></a></dt>
                        <dd class=\"col-xs-8 text-right\">";
                        
						$html .= "<p><strong>Ordered Qty - <span class='badge badge-violet'>{$_product['ordered_quantity']}<span></strong></p> <br/>";	
						$availability = $websiteOrderProduct->checkProductAvailability($_product['wo_product_sku']);
						if($availability){
							$html .= "<b>Fulfilled By ".count($availability)."</b><br/>";
							foreach($availability as $_aval){								
								$html .= "<a class=\"badge badge_upgrade badge-success redirect\" style=\"margin-bottom:2px;\" href=\"{$_aval['link']}\">{$_aval['code']}</a> <br/>";
							}
						}
						else{
							$html .= "<br/><center>No Fulfilled<br/>available</center>";
						}
                    $html .="</dd>
                      </dl>
                    </div>
                  </div>
					
					
					";
				}
			}
            $html .= "</li>";
			$html .= "</ul>";
			echo $html;
		}
		else{
			echo "No Order Available";
		}
	}
	elseif($action == "user"){
		$employee = new Employee($id);
		if($employee->isExist()){
			$empData = $employee->getDetails();	
			$activity = Activity::getUserWorkPerformance($empData['user_id']);		
			$addressArry = 	explode(",", $empData['user_address']);
			$addreesCountry = implode(", ",array_slice($addressArry, count($addressArry)-2, 2));			
            echo "<div class=\"card mb-0\">
					<div class=\"card-body p-1 d-flex align-items-center\">
						<div class=\"bg-gradient-info p-1 mfe-3\">
							<img src=\"".getResizeImage($empData["user_image"],80)."\" />
						</div>
						<div class=\"ml-1\">
							<div class=\"text-value text-info\">{$empData['user_name']}</div>
							<div class=\"text-muted small\">{$empData['user_type_name']}</div>
							<div class=\"text-muted small\">$addreesCountry</div>
							<div class=\"text-muted small\">Since : ".dateView($empData['user_created_date'], 'DATE')."</div>					
						</div>
					</div>
					<div class=\"card-footer px-1 py-0 pt-1 small\">
						<div>Avg Hours {$activity['average_work']} / day</div>
						<div class=\"progress progress-xs\" style=\"height:4px; margin-top:0px; margin-bottom:5px;\" data-val=\"{$activity['average_work']}\" data-total=\"{$activity['max_day_work']}\">
						  <div class=\"progress-bar bg-{$activity['work_class']}\" role=\"progressbar\" style=\"width: {$activity['day_work_percent']}%\" aria-valuenow=\"{$activity['day_work_percent']}\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>
						</div>
						<div>Activity {$activity['average_activity']} / day</div>
						<div class=\"progress progress-xs\" style=\"height:4px; margin-top:0px; margin-bottom:5px;\" data-val=\"{$activity['activity']}\" data-total=\"{$activity['total_activity']}\">
						  <div class=\"progress-bar bg-{$activity['activity_class']}\" role=\"progressbar\" style=\"width: {$activity['average_activity_percent']}%\" aria-valuenow=\"{$activity['average_activity_percent']}\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>
						</div>
					</div>
					</div>";
		}
		else{
			echo "No User Available";
		}
	}
	elseif($action == "collection"){
		$collection = new Collection($id);
		if($collection->isExist()){
			$colectionData = $collection->load();
			$output = "<ul class=\"list-group m-0\">";
            if(count($colectionData['collection_item_list_array'])){
				foreach($colectionData['collection_item_list_array'] as $_item){
					$output.="<li class=\"list-group-item d-flex list-group-item-action justify-content-between align-items-center\">{$_item['wci_name']}. - <span>{$_item['wcr_item_qty']}</span> </li>";
				}
			}
			else{
				$output .= "<li class=\"list-group-item d-flex list-group-item-action justify-content-between align-items-center\">Itens info not available</li>";
			}
			$output.="<li class=\"list-group-item d-flex list-group-item-action justify-content-between align-items-center\">Manager - <span><img class=\"img img-circle\" style=\"height:16px\" src=\"".getResizeImage($colectionData['collection_manager_image'], 50)."\"> {$colectionData['manager_fname']}</span> </li>";
			$output.="<li class=\"list-group-item d-flex list-group-item-action justify-content-between align-items-center\">Driver - <span><img class=\"img img-circle\" style=\"height:16px\" src=\"".getResizeImage($colectionData['driver_image'], 50)."\"> {$colectionData['driver_fname']}</span> </li>";
			$output.="<li class=\"list-group-item d-flex list-group-item-action justify-content-between align-items-center\">Vehicle - <span>{$colectionData['vehicle_registration_number']}</span> </li>";
			$output.= "</ul>";
			echo $output;
		}
		else{
			echo "No User Available";
		}
	}
	elseif($action == "emoji"){
		echo Emoji::getEmojiInput();
	}
}
else{
	echo "No Content Available";
}
?> 