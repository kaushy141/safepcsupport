<?php Modal::load(array('ProductAvailbility')); ?><div class="row">
  <style type="text/css">
.award_badge_0 {
background-image:url('<?php echo $app->basePath('img/award.png')?>');
}
.award_badge_1 {
background-image:url('<?php echo $app->basePath('img/award_second.png')?>');
}
.award_badge_2 {
background-image:url('<?php echo $app->basePath('img/award_third.png')?>');
}
</style>
  <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-1">
  <div class="card">
    <div class="flex-row usercontainer">
      <div class="MultiCarousel" data-items="2,3,4,6" data-slide="2" id="MultiCarousel"  data-interval="1000">
        <div class="MultiCarousel-inner">
          <?php 
	  if($totalLogedInUser = Activity::todayUserLogins(24))
	  {
		$month_work = array_column($totalLogedInUser, "month_work");
		$max_work_emp_pos = ($month_work && count($month_work)) ? max($month_work) : 0;
		$awrded_user_array = Activity::getMaxWorkedEmployee();		
		foreach($totalLogedInUser as $user){
			if(in_array($user['log_user_id'], $awrded_user_array))
			{
				plotUser($user);
			}
		}
		foreach($totalLogedInUser as $user){
			if(!in_array($user['log_user_id'], $awrded_user_array))
			{
				plotUser($user);
			}
		}		
		
	  }
function plotUser($logedInuser)
{
	global $awrded_user_array;
	$award_title = $badge = "";
	if(in_array($logedInuser['log_user_id'], $awrded_user_array)){
		$position = array_search($logedInuser['log_user_id'], $awrded_user_array);
		$badge = "award_badge award_badge_$position";
		if($position == 0)
			$award_title = "Maximum hours work in last 30 days";
		elseif($position == 1)
			$award_title = "Second Maximum hours work in last 30 days";
		elseif($position == 2)
			$award_title = "Third Maximum hours work in last 30 days";
	}
	?>
          <div class="item <?php echo $badge ?>" title="<?php echo $award_title ?>">
            <div class="userimageblock" style="/*border-bottom:2px solid <?php echo $logedInuser['color']?>;*/">
              <?php if($logedInuser['user_is_live'] == 1){?>
              <span class="online_badge text-success"><i class="fa fa-circle faa-burst animated"></i> online</span>
              <?php }?>
              <div class="avatar text-center ui-widgets ui-widgets-<?php echo $logedInuser['widget_percent'];?>" style="width:inherit;"> <img class="img-circle img-user" src="<?php echo $logedInuser['user_image']?>" height="90px" /></div>
              <div class="text-center usernameblock"  data-trigger="hover" data-toggle="popover-ajax"  data-popover-action="user" data-popover-id="<?php echo $logedInuser["log_user_id"]?>"> <?php echo limitText($logedInuser['user_name'],15)?> </div>
              <div class="text-center userloginblock text-muted"> Started: <?php echo $logedInuser['start_login']?> </div>
              <div class="text-center usertimeblock text-muted"> Total: <?php echo $logedInuser['duration']?> </div>
			  <div class="text-center small text-muted"><?php echo $logedInuser['browser']?>-<?php echo $logedInuser['os']?> </div>
            </div>
          </div>
          <?php			
	
}  
	  ?>
        </div>
        <button class="btn btn-default leftLst"><</button>
        <button class="btn btn-default rightLst">></button>
      </div>
    </div>
  </div>
</div>
  <script>
  $(document).ready(function () {
    var itemsMainDiv = ('.MultiCarousel');
    var itemsDiv = ('.MultiCarousel-inner');
    var itemWidth = "";

    $('.leftLst, .rightLst').click(function () {
        var condition = $(this).hasClass("leftLst");
        if (condition)
            click(0, this);
        else
            click(1, this)
    });

    ResCarouselSize();

    $(window).resize(function () {
        ResCarouselSize();
    });

    //this function define the size of the items
    function ResCarouselSize() {
        var incno = 0;
        var dataItems = ("data-items");
        var itemClass = ('.item');
        var id = 0;
        var btnParentSb = '';
        var itemsSplit = '';
		$(itemsMainDiv).outerWidth($(".usercontainer").width());
        var sampwidth = $(itemsMainDiv).width();
        var bodyWidth = $('body').width();
        $(itemsDiv).each(function () {
            id = id + 1;
            var itemNumbers = $(this).find(itemClass).length;
            btnParentSb = $(this).parent().attr(dataItems);
            itemsSplit = btnParentSb.split(',');
            $(this).parent().attr("id", "MultiCarousel" + id);
			
            if (bodyWidth >= 1200) {
                incno = itemsSplit[3];
                itemWidth = sampwidth / incno;
            }
            else if (bodyWidth >= 992) {
                incno = itemsSplit[2];
                itemWidth = sampwidth / incno;
            }
            else if (bodyWidth >= 768) {
                incno = itemsSplit[1];
                itemWidth = sampwidth / incno;
            }
            else {
                incno = itemsSplit[0];
                itemWidth = sampwidth / incno;
            }
			itemWidth = Math.min(itemWidth, 220);
            $(this).css({ 'transform': 'translateX(0px)', 'width': itemWidth * itemNumbers });
            $(this).find(itemClass).each(function () {
                $(this).outerWidth(itemWidth);
            });
			
            $(".leftLst").addClass("over");
            $(".rightLst").removeClass("over");

        });
    }


    //this function used to move the items
    function ResCarousel(e, el, s) {
        var leftBtn = ('.leftLst');
        var rightBtn = ('.rightLst');
        var translateXval = '';
        var divStyle = $(el + ' ' + itemsDiv).css('transform');
        var values = divStyle.match(/-?[\d\.]+/g);
        var xds = Math.abs(values[4]);
        if (e == 0) {
            translateXval = parseInt(xds) - parseInt(itemWidth * s);
            $(el + ' ' + rightBtn).removeClass("over");

            if (translateXval <= itemWidth / 2) {
                translateXval = 0;
                $(el + ' ' + leftBtn).addClass("over");
            }
        }
        else if (e == 1) {
            var itemsCondition = $(el).find(itemsDiv).width() - $(el).width();
            translateXval = parseInt(xds) + parseInt(itemWidth * s);
            $(el + ' ' + leftBtn).removeClass("over");

            if (translateXval >= itemsCondition - itemWidth / 2) {
                translateXval = itemsCondition;
                $(el + ' ' + rightBtn).addClass("over");
            }
        }
        $(el + ' ' + itemsDiv).css('transform', 'translateX(' + -translateXval + 'px)');
    }

    //It is used to get some elements from btn
    function click(ell, ee) {
        var Parent = "#" + $(ee).parent().attr("id");
        var slide = $(Parent).attr("data-slide");
        ResCarousel(ell, Parent, slide);
    }

});
  </script>
    
		  
	<div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-2 mb-1">
        <div class="card">
          <div class="card-header" data-step="5" data-title="Assigned Order" data-intro="View assigned order to process"> <i class="fa fa-align-justify"></i> Assigned Order</div>
          <div class="card-body pt-1 pb-2 assinordercontainer">
		  <div class="col-sm-12 mt-1">
				<?php
				$todaysOrderActivity = WebsiteOrder::todaysOrderActivity();
				?>
				<div class="row">
					<div class="col-md-2 col-sm-12">
						<div class="card">
							<div class="card-body text-center p-2">
								<div class="small text-uppercase font-weight-bold">Today's<br/>Order</br/>Progress</div>								
							</div>
						</div>
					</div>
				<?php foreach($todaysOrderActivity as $_orderActivity){?>
					<div class="col-lg-2 col-md-3  col-sm-4 col-xs-6">
						<div class="card">
							<div class="card-body text-center p-2">
								<div class="small text-uppercase" style="color :<?php echo $_orderActivity['color']?>"><?php echo $_orderActivity['label']?></div>
								<div class="text-value-xl pt-1"><?php echo $_orderActivity['records']?></div>
							</div>
						</div>
					</div>
<?php
						//echo "<li class=\"list-group-item d-flex list-group-item-action justify-content-between align-items-center\">Order {$_orderActivity['label']} - <span class=\"badge badge-pill\" style=\"background-color:{$_orderActivity['color']}\">{$_orderActivity['records']}</span> </li>";
						
				}?>
				</div>
		  </div>
            <?php
	Modal::load(array('WeborderLabels'));
	$weborderLabels = new WeborderLabels();
	$assignedShipmentRecords = $weborderLabels->getAssignedShipmentLabel();
	if(count($assignedShipmentRecords))
	{
		?>
            <div class="col-xs-12">
              <div class="nav-tabs-boxed">
                <ul id="assigned_order_tab" class="nav nav-tabs" role="tablist">
                <?php
				$arrayShipmentRecordsUser = array_column($assignedShipmentRecords, 'user_id');
				$activeTab = in_array(getLoginId(), $arrayShipmentRecordsUser) ? getLoginId() : 0;
				$tabUser = array();
				$count = 0;
				$arrayOccurence = array_count_values($arrayShipmentRecordsUser);
				foreach($assignedShipmentRecords as $_webShipment)
				{
					$count++;
					if(!in_array($_webShipment["user_id"], $tabUser)){
						array_push($tabUser, $_webShipment["user_id"]);
						echo "<li class=\"nav-item\" data-id=\"".$_webShipment["user_id"]."\"><span class=\"weborder_tab_badge_count_".$_webShipment["user_id"]." badge badge-success\" style=\"position: absolute; z-index: 1; right: 4px; top: 2px;\">".$arrayOccurence[$_webShipment["user_id"]]."</span>
						<a class=\"nav-link text-center ".((($count==1 && $activeTab == 0) || $activeTab == $_webShipment["user_id"])?"active":"")."\" data-toggle=\"tab\" href=\"#tab-assigneddorder$_webShipment[user_id]\" role=\"tab\" aria-controls=\"assigneddorder$_webShipment[user_id]\" aria-selected=\"false\">
						<img class=\"img-circle d-none d-sm-block weborder_tab_pane_".$_webShipment["user_id"]." \" src=\"". getResizeImage($_webShipment["user_image"],50)."\" height=\"32px\">
						<img class=\"img-circle d-block d-sm-none\" src=\"". getResizeImage($_webShipment["user_image"],50)."\" height=\"20px\">
						<span class=\"hidden-xs\">".limitText($_webShipment["user_fname"],7)."</span></a>
						</li>";
					}
					
				}
				?>
                </ul>
                <div class="tab-content">
                  <?php
		$tabUser = array();
		$count = 0;
		foreach($assignedShipmentRecords as $_webShipment)
		{
			$webLabelData = trim($_webShipment['label_data']) != "" ? explode("*^*", trim($_webShipment['label_data'])) : null;$labelCount = 0;			
			$webLabelData = array_filter($webLabelData);
			$count++;
			$isPremium = strpos($_webShipment['product_premium'], "1") !== false ? true : false;
			if(!in_array($_webShipment["user_id"], $tabUser)){
						array_push($tabUser, $_webShipment["user_id"]);
						if($count != 1)
							echo "</div></div>";
						echo "<div id=\"tab-assigneddorder$_webShipment[user_id]\" role=\"tabpanel\" class=\"tab-pane ".((($count==1 && $activeTab == 0) || $activeTab == $_webShipment["user_id"])?"active":"")."\"><div class=\"row\">";
					}
			?>
                  <div class="col-lg-3 col-md-4 col-sm-6 assi_wo_block assi_wo_block_<?php echo $_webShipment["user_id"];?>" id="dashboard_order_label_<?php echo md5($_webShipment['label_id'])?>">
                    <div class="card <?php //echo $isPremium ? "gradient_bg text-white" :""?>">
                      <div class="p-1" style="border-bottom: 1px solid #efefef;"><img class="img-circle" alt="<?php echo $_webShipment["user_fname"]?>" src="<?php echo getResizeImage($_webShipment["user_image"],50)?>" height="32px"> &nbsp; <?php echo limitText($_webShipment["user_fname"].' '.$_webShipment["user_lname"],8)?> <?php echo $isPremium ? "<img title=\"Contains Premium Product\" src=\"".$app->imagePath(PREMIUM_ICON)."\" style=\"height:20px; width:20px;\">":""?> <span class="text-info pull-right">
					  
					  <div class="btn-group">
							<button type="button" class="btn btn-default dropdown-toggle-split btn-no-norder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fas fa-ellipsis-v"></i></button>
							<div class="dropdown-menu dropdown-menu-right">
							<?php 
							if($webLabelData != null && count($webLabelData)){								
								foreach($webLabelData as $_wLabels){
									$_wLabel = explode("^", $_wLabels);		
									if($_wLabel[0] != 0)
									{
										$labelCount++;
									?>
									<div class="dropdown-item" data-label="<?php echo $_wLabels;?>" style="display:inline-block; width:280px; white-space:revert;">
										<div class="p-0 pt-0 d-flex align-items-center justify-content-start">
											<div class="pl-0 justify-content-start" style="max-width:50px;">
												<img class="img-circle" height="40px" width="40px" src="<?php echo $_wLabel[7]?>"/> 
											</div>
											<div class="justify-content-end ml-auto pl-1">
												<?php echo $_wLabel[5]?> <?php echo $_wLabel[6]?> added label on <?php echo dateView($_wLabel[3], 'FULL')?><br/>
												
												<a class="download_weborder_label btn btn-success btn-sm pull-left text-white" data-group-action="weborder/downloadweborderlabel" data_user="<?php echo $_wLabel[5].' '.$_wLabel[6]?>" data_is_pack_user="<?php echo $_webShipment['web_order_packing_user'] == getLoginId() ? 1:0?>" data_label_id="<?php echo md5($_wLabel[0])?>"><i class="fa fa-download text-white"></i>&nbsp; Download</a>
												
												<span class="text-info pull-right"><i class="fa fa-anchor"></i> <span data-html="true" data-placement="left" title="<?php echo $_wLabel[1]?> Downloads" data-trigger="hover" data-toggle="popover" data-content="<?php echo trim($_wLabel[4]) != null ? str_replace("|", "<br/>",$_wLabel[4]) : 'Not downloaded'?>" id="label_download_count_<?php echo md5($_wLabel[0])?>"><?php echo $_wLabel[1]?></span>
												
											</div>
										</div>
									</div>
								<?php
									}
								}
							}
							if($labelCount == 0){?>
							<div class="dropdown-item" style="display:inline-block; white-space:revert;">
							No Label uploaded yet
							</div>
								<?php
							}
							?>
							<div class="dropdown-item" style="display:inline-block; white-space:revert;">
							No Label uploaded yet
							</div>
							<div class="dropdown-item input-group" style="display:inline-block; white-space:revert;"><label>Update Priority</label><br/>
							<input class="form-control drop-down-input" type="number" min="0" max="10000" value="<?php echo $_webShipment['web_order_priority']?>"><button type="button" data-id="<?php echo $_webShipment['web_order_id']?>" class="btn btn-sm btn-info input-group-addon drop-down-btn btn-update-order-priority">Save</button>
							</div>
							
					  </div></div>
					  <div class="pull-right p-1" style="top:29px; right:-10px; position:absolute;"><?php echo orderDelayLevel($_webShipment["web_order_created_date"])?></div>
					  <?php if($_webShipment["web_order_priority"] > 0){?>
					  <div class="pull-right p-1" style="top:29px; right:45px; position:absolute;">
						<span title="Piority level <?php echo $_webShipment["web_order_priority"]?>" class="badge badge-info badge_priority faa-pulse animated"><i class="fa fa-level-up"></i> <?php echo $_webShipment["web_order_priority"]?></span></div>
						<script>
						$('.weborder_tab_pane_<?php echo $_webShipment["user_id"];?>').addClass('faa-horizontal animated');
						$('.weborder_tab_badge_count_<?php echo $_webShipment["user_id"];?>').addClass('gradient_bg');</script>
					  <?php }?>					  
					  </span>
                        <!--<div class="pull-right p-1" style="top:20px; right:0px; position:absolute;"><?php echo orderDelayLevel($_webShipment["web_order_created_date"])?></div> -->
                      </div>
                      <div class="card-body">
                        <div class="p-1 pt-0 d-flex align-items-center justify-content-between">
                          <div class="pl-0 justify-content-start"> <img class="img" alt="Dawit" src="<?php echo $app->imagePath($_webShipment["store_logo"])?>" height="24px"> </div>
                          <div class="justify-content-end ml-auto pl-1"> <a href="<?php echo $app->basePath('viewweborder/'.$_webShipment["web_order_id"])?>" data-trigger="hover" data-toggle="popover-ajax"  data-popover-action="order" data-popover-id="<?php echo $_webShipment["web_order_id"]?>" class="redirect btn btn-outline-info pull-right"><?php echo $_webShipment["web_order_number"]?></a> </div>
                        </div>
                        
                        <div class="p-0 px-1 d-flex align-items-center justify-content-between">
                          <div class="pl-0 justify-content-start text-left">
                            <?php if($_webShipment['web_order_invoice_image']){?>
                            <a download class="download_weborder_invoice btn btn-warning" href="<?php echo $app->basePath($_webShipment['web_order_invoice_image'])?>"><i class="fa fa-download"></i> &nbsp; Invoice</a>
                            <?php }else{?>
                            <a class="btn btn-warning pull-right text-white" disabled=""><i class="fa fa-warning"></i> No Invoice</a>
                            <?php }?>
                          </div>
                          <div class="justify-content-end ml-auto pl-1 text-xs">
                            <?php if($labelCount > 0){?>
                            <span class="badge badge-success"><?php echo $labelCount;?></span> label available
                            <?php }else{?>
                            <a class="btn btn-danger pull-right text-white" disabled=""><i class="fa fa-warning"></i> No Label</a>
                            <?php }?>
                          </div>
                        </div>
                        <div class="px-1 pt-0 mt-1 d-flex align-items-center justify-content-center">
                          <div class="pl-0 justify-content-center"> 
						  <a class="btn btn-success text-white" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?php echo $_webShipment["web_order_id"];?>|O', '#<?php echo $_webShipment["web_order_number"]?> Log Report')"><i class="fa fa-comments-o"></i></a> 
						  <a data-quick-view="true" data-toggle="modal" data-target="#appModalQuick" data-quick-url="viewweborder/<?php echo $_webShipment['web_order_id']?>" class="btn btn-default text-dark">Quick view</a>	
						  <a class="badge badge-violet py-1 ml-1 text-white"><?php echo $_webShipment['total_quantity'];?> Item</a>
						  </div>
						  </div>
						  <div class="px-1 pt-0 mt-1 d-flex align-items-center justify-content-center text-xs">
						  <?php 
						  $pa = new ProductAvailbility();
						  $paRecord = $pa->getLastAvailbility('O', $_webShipment["web_order_id"]);
						  if(!empty($paRecord)){?>
						  <span data-trigger="hover" data-placement="top" data-html="true" data-toggle="popover" data-title="<img class='img img-circle' style='height:24px' src='<?php echo $paRecord['user_image']?>'/> <?php echo $paRecord['user_fname']?> <?php echo $paRecord['user_lname']?>" data-content="<?php echo $paRecord['user_fname']?> <?php echo $paRecord['user_lname']?> checked product availability with supplier <b><?php echo $paRecord['supplier_name']?></b> and found it <b><?php echo $paRecord['pro_avail_stock_status']?></b>.<br/><i><?php echo $paRecord['pro_avail_remark']?></i><br/>Dated:<?php echo $paRecord['pro_avail_checked_time_full']?>" class="text-<?php echo $paRecord['pro_avail_class']?>"><?php echo $paRecord['pro_avail_stock_status']?> on - <b><?php echo $paRecord['supplier_name']?></b></span> &nbsp;<img class="img img-circle" style="height:14px" alt="<?php echo $paRecord['user_fname']?>" src="<?php echo $paRecord['user_image']?>"/>
						  <?php  
						  }else{?>
							 <span>Product availbility not checked</span> 
						  <?php
						  }
						  ?>
						  </div>
						  <div class="px-1 pt-0 my-1 d-flex align-items-center justify-content-center">
						  <div class="pl-0 justify-content-center"><a data_user="<?php echo $_webShipment["user_fname"].' '.$_webShipment["user_lname"]?>" data_is_pack_user="<?php echo $_webShipment['web_order_packing_user'] == getLoginId() ? 1:0?>" data_label_id="<?php echo md5($_webShipment['label_id'])?>" data_order_id="<?php echo $_webShipment['web_order_id']?>" data-group-action="weborder/weborderaction" data-name="Order" class="mark_weborder_processed btn btn-info text-white"><i class="fa fa-check"></i> Mark Order Processed</a></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
			if($count == count($assignedShipmentRecords))
			echo "</div></div>";
		}
	}
	else{
		echo "<div class=\"empty_image_box col-sm-12 col-md-12 text-center py-3 my-3\" style=\"color:#aaa;\"><i class=\"fa icon-handbag fa-4x\"></i><br/>No Order available to process</div>";
	}
  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
	Modal::load(array('SalesLabels'));
	$SalesLabels = new SalesLabels();
	$assignedInvoiceShipmentRecords = $SalesLabels->getAssignedShipmentLabel();
	
	Modal::load(array('ComplaintLabels'));
	$complaintLabels = new ComplaintLabels();
	$assignedRmaShipmentRecords = $complaintLabels->getAssignedShipmentLabel();
	
	$salesBlockClass = $repairBlockClass = "col-lg-6 col-md-6";
	$slesRepairBlockClass = "col-lg-6 col-md-6 col-sm-12";
	if(count($assignedInvoiceShipmentRecords) > 0 && count($assignedRmaShipmentRecords) == 0){
		$salesBlockClass = "col-lg-12 col-md-12";
		$slesRepairBlockClass = "col-lg-3 col-md-3";
	}
	elseif(count($assignedRmaShipmentRecords) > 0 && count($assignedInvoiceShipmentRecords) == 0){
		$repairBlockClass = "col-lg-12 col-md-12";
		$slesRepairBlockClass = "col-lg-3 col-md-3";
	}
	        
	if(count($assignedInvoiceShipmentRecords))
	{
?>
  <div class="<?php echo $salesBlockClass;?> col-sm-12 mt-0">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-2 mb-1">
        <div class="card">
          <div class="card-header" data-step="6"data-title="Assigned Sales invoice" data-intro="View assigned sales invoice to process"> <i class="fa fa-align-justify"></i> Assigned Sales Invoice
            <div class="card-actions"> <a class="assi_si_block_handler" data-container="assininvoicecontainer" title="Show my Order only" data-id="assi_si_block_<?php echo getLoginId();?>" href="#">Mine</a> <a class="assi_si_block_handler" data-container="assininvoicecontainer"  data-id="assi_si_block" title="Show All Order" href="#">All</a> </div>
          </div>
          <div class="card-body pt-1 assininvoicecontainer">
    <?php
	if(count($assignedInvoiceShipmentRecords))
	{
		foreach($assignedInvoiceShipmentRecords as $_salesShipment)
		{
			$isPremium = false;
			?>
            <div class="<?php echo $slesRepairBlockClass;?> col-sm-12 assi_si_block assi_si_block_<?php echo $_salesShipment["user_id"];?>" id="dashboard_sales_label_<?php echo md5($_salesShipment['label_id'])?>">
              <div class="card <?php //echo $isPremium ? "gradient_bg text-white" :""?>">
                <div class="p-1" style="border-bottom: 1px solid #efefef;"><img class="img-circle" alt="<?php echo $_salesShipment["user_fname"]?>" src="<?php echo getResizeImage($_salesShipment["user_image"],50)?>" height="32px"> &nbsp; <?php echo limitText($_salesShipment["user_fname"].' '.$_salesShipment["user_lname"], 15)?> <?php echo $isPremium ? "<img title=\"Contains Premium Product\" src=\"".$app->imagePath(PREMIUM_ICON)."\" style=\"height:20px; width:20px;\">":""?> <span class="text-info pull-right" title="<?php echo $_salesShipment["label_downloads"]?> Downloads"><i class="fa fa-download"></i> <span  data-html="true" data-trigger="hover" data-toggle="popover" title="Label Downloads" data-content="<?php echo str_replace("|", "<br/>", $_salesShipment["label_download_records"])?>" id="label_download_count_<?php echo md5($_salesShipment['label_id'])?>"><?php echo $_salesShipment["label_downloads"]?></span></span>
                  <div class="pull-right p-1" style="top:20px; right:0px; position:absolute;"><?php echo orderDelayLevel($_salesShipment["sales_invoice_created_date"])?></div>
                </div>
                <div class="card-body">
                  <div class="p-1 pt-0 d-flex align-items-center justify-content-between">
                    <div class="pl-0 justify-content-start"> <img class="img" alt="Dawit" src="<?php echo $app->imagePath($_salesShipment["store_logo"])?>" height="24px"> </div>
                    <div class="justify-content-end ml-auto pl-1"> <a href="<?php echo $app->basePath('salesinvoice/'.$_salesShipment["sales_invoice_id"])?>" class="redirect btn btn-outline-info pull-right"><?php echo $_salesShipment["sales_invoice_number"]?></a> </div>
                  </div>
                  <div class="px-1 pt-0 pb-1 d-flex align-items-left justify-content-start"> 
				  <?php if($_salesShipment["label_uploaded_date"]){?>
				  <img class="img-circle" title="<?php echo $_salesShipment['uploader_fname']?>" data-toggle="popover" data-content="Assigned by <?php echo $_salesShipment['uploader_fname']?> at <?php echo dateView($_salesShipment["label_uploaded_date"], 'FULL')?>" alt="<?php echo $_salesShipment['uploader_fname']?>" src="<?php echo getResizeImage($_salesShipment["uploader_image"],50)?>" height="20px"> &nbsp; <?php echo limitText($_salesShipment['uploader_fname'], 10). ' at '. date("d-M h:iA", strtotime($_salesShipment["label_uploaded_date"]));?> 
				  <?php }else{?>
					  Waiting for label
				<?php
				  }?>
				  
				  </div>
                  <div class="p-0 px-1 d-flex align-items-center justify-content-between">
                    <div class="pl-0 justify-content-start text-left"> <a target="_blank" class="download_weborder_invoice btn btn-warning" href="<?php echo DOC::SALESINV($_salesShipment['sales_invoice_id'])?>"><i class="fa fa-download"></i> &nbsp; Invoice</a> </div>
                    <div class="justify-content-end ml-auto pl-1"> 
					<?php if($_salesShipment['label_id']){?>
					<a class="download_weborder_label btn btn-success pull-right text-white" data-group-action="sales/downloadsalesinvoicelabel" data_user="<?php echo $_salesShipment["user_fname"].' '.$_salesShipment["user_lname"]?>" data_is_pack_user="<?php echo $_salesShipment['sales_invoice_packing_user'] == getLoginId() ? 1:0?>" data_label_id="<?php echo md5($_salesShipment['label_id'])?>"><i class="fa fa-download"></i> &nbsp; Label</a> 
					<?php }else{?>
                            <a class="btn btn-success pull-right text-white" disabled=""><i class="fa fa-warning"></i> No Label</a>
                            <?php }?>
					</div>
                  </div>
				  <div class="px-1 pt-0 mt-1 d-flex align-items-center justify-content-center text-xs">
						  <?php 
						  $pa = new ProductAvailbility();
						  $paRecord = $pa->getLastAvailbility('S', $_salesShipment["sales_invoice_id"]);
						  if(!empty($paRecord)){?>
						  <span data-trigger="hover" data-placement="top" data-html="true" data-toggle="popover" data-title="<img class='img img-circle' style='height:24px' src='<?php echo $paRecord['user_image']?>'/> <?php echo $paRecord['user_fname']?> <?php echo $paRecord['user_lname']?>" data-content="<?php echo $paRecord['user_fname']?> <?php echo $paRecord['user_lname']?> checked product availability with supplier <b><?php echo $paRecord['supplier_name']?></b> and found it <b><?php echo $paRecord['pro_avail_stock_status']?></b>.<br/><i><?php echo $paRecord['pro_avail_remark']?></i><br/>Dated:<?php echo $paRecord['pro_avail_checked_time_full']?>" class="text-<?php echo $paRecord['pro_avail_class']?>"><?php echo $paRecord['pro_avail_stock_status']?> on - <b><?php echo $paRecord['supplier_name']?></b></span> &nbsp;<img class="img img-circle" style="height:14px" alt="<?php echo $paRecord['user_fname']?>" src="<?php echo $paRecord['user_image']?>"/>
						  <?php  
						  }else{?>
							 <span>Product availbility not checked</span> 
						  <?php
						  }
						  ?>
						  </div>
                  <div class="p-1 pt-0 mb-1 d-flex align-items-center justify-content-center">
                    <div class="pl-0 justify-content-center"> <a data_user="<?php echo $_salesShipment["user_fname"].' '.$_salesShipment["user_lname"]?>" data_is_pack_user="<?php echo $_salesShipment['sales_invoice_packing_user'] == getLoginId() ? 1:0?>" data_label_id="<?php echo md5($_salesShipment['label_id'])?>" data_order_id="<?php echo $_salesShipment['sales_invoice_id']?>" data-group-action="sales/salesinvoiceaction" data-name="Sales Invoice" class="mark_weborder_processed btn btn-info text-white"><i class="fa fa-check"></i> Mark Processed</a> <a class="btn btn-success" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?php echo $_salesShipment["sales_invoice_id"];?>|S', '#<?php echo $_salesShipment["sales_invoice_number"]?> Log Report')"><i class="fa fa-comments-o"></i></a></div>
                  </div>
                </div>
              </div>
            </div>
            <?php
		}
	}
	else{
		echo "<div class=\"empty_image_box col-sm-12 col-md-12 text-center py-3 my-3\" style=\"color:#aaa;\"><i class=\"icon-wallet fa-4x\"></i><br/><br/><br/><br/>No assigned Sales Invoice available</div>";
	}
  ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php }?>
  <?php	
	if(count($assignedRmaShipmentRecords))
	{
  ?>
  <div class="<?php echo $repairBlockClass;?> col-sm-12 mt-0">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-2 mb-1">
        <div class="card">
          <div class="card-header" data-step="7" data-title="Assigned RMA" data-intro="View assigned RMA to process"> <i class="fa fa-align-justify"></i> Assigned RMA Repair
            <div class="card-actions"> <a class="assi_c_block_handler" data-container="assinrmacontainer" title="Show my Order only" data-id="assi_si_block_<?php echo getLoginId();?>" href="#">Mine</a> <a class="assi_c_block_handler" data-container="assinrmacontainer"  data-id="assi_c_block" title="Show All Order" href="#">All</a> </div>
          </div>
          <div class="card-body pt-1 assinrmacontainer">
            <?php
			if(count($assignedRmaShipmentRecords))
	{
		foreach($assignedRmaShipmentRecords as $_salesShipment)
		{
			$isPremium = false;
			?>
            <div class="<?php echo $slesRepairBlockClass;?> col-sm-12 assi_c_block assi_c_block_<?php echo $_salesShipment["user_id"];?>" id="dashboard_sales_label_<?php echo md5($_salesShipment['label_id'])?>">
              <div class="card <?php //echo $isPremium ? "gradient_bg text-white" :""?>">
                <div class="p-1" style="border-bottom: 1px solid #efefef;"><img class="img-circle" alt="<?php echo $_salesShipment["user_fname"]?>" src="<?php echo getResizeImage($_salesShipment["user_image"],50)?>" height="32px"> &nbsp; <?php echo limitText($_salesShipment["user_fname"].' '.$_salesShipment["user_lname"],15)?> <?php echo $isPremium ? "<img title=\"Contains Premium Product\" src=\"".$app->imagePath(PREMIUM_ICON)."\" style=\"height:20px; width:20px;\">":""?> <span class="text-info pull-right" title="<?php echo $_salesShipment["label_downloads"]?> Downloads"><i class="fa fa-download"></i> <span   data-html="true" data-trigger="hover" data-toggle="popover" title="Label Downloads" data-content="<?php echo str_replace("|", "<br/>", $_salesShipment["label_download_records"])?>" id="label_download_count_<?php echo md5($_salesShipment['label_id'])?>"><?php echo $_salesShipment["label_downloads"]?></span></span>
                  <div class="pull-right p-1" style="top:20px; right:0px; position:absolute;"><?php echo orderDelayLevel($_salesShipment["complaint_created_date"])?></div>
                </div>
                <div class="card-body">
                  <div class="p-1 pt-0 d-flex align-items-center justify-content-between">
                    <div class="pl-0 justify-content-start"> <img class="img" alt="Dawit" src="<?php echo $app->imagePath($_salesShipment["store_logo"])?>" height="24px"> </div>
                    <div class="justify-content-end ml-auto pl-1"> <a href="<?php echo $app->basePath('updatecomplaintrequest/'.$_salesShipment["complaint_id"])?>" class="redirect btn btn-outline-info pull-right"><?php echo $_salesShipment["complaint_ticket_number"]?></a> </div>
                  </div>
                  <div class="px-1 pt-0 pb-1 d-flex align-items-left justify-content-start"> 
				  <?php if($_salesShipment["label_uploaded_date"]){?>
				  <img class="img-circle" title="<?php echo $_salesShipment['uploader_fname']?>" data-toggle="popover" data-content="Assigned by <?php echo $_salesShipment['uploader_fname']?> at <?php echo dateView($_salesShipment["label_uploaded_date"], 'FULL')?>" alt="<?php echo $_salesShipment['uploader_fname']?>" src="<?php echo getResizeImage($_salesShipment["uploader_image"],50)?>" height="20px"> &nbsp; <?php echo limitText($_salesShipment['uploader_fname'], 10)?> at <?php echo date("d-M h:iA", strtotime($_salesShipment["label_uploaded_date"]))?> 
				  <?php }else{?>
				  Waiting for label
				  <?php }?>
				  </div>
                  <div class="p-0 px-1 d-flex align-items-center justify-content-between">
                    <div class="pl-0 justify-content-start text-left"> <a target="new" download class="download_weborder_invoice btn btn-warning" href="<?php echo DOC::CINV($_salesShipment['complaint_id'])?>"><i class="fa fa-download"></i> &nbsp; Invoice</a> </div>
                    <div class="justify-content-end ml-auto pl-1"> 
					<?php if($_salesShipment['label_id']){?>
					<a class="download_weborder_label btn btn-success pull-right text-white" data-group-action="repair/downloadcomplaintinvoicelabel" data_user="<?php echo $_salesShipment["user_fname"].' '.$_salesShipment["user_lname"]?>" data_is_pack_user="<?php echo $_salesShipment['complaint_packing_user'] == getLoginId() ? 1:0?>" data_label_id="<?php echo md5($_salesShipment['label_id'])?>"><i class="fa fa-download"></i> &nbsp; Label</a>
					<?php }else{?>
                            <a class="btn btn-success pull-right text-white" disabled=""><i class="fa fa-warning"></i> No Label</a>
                            <?php }?>
					</div>
                  </div>
                  <div class="p-1 pt-0 mb-1 d-flex align-items-center justify-content-center">
                    <div class="pl-0 justify-content-center"> <a data_user="<?php echo $_salesShipment["user_fname"].' '.$_salesShipment["user_lname"]?>" data_is_pack_user="<?php echo $_salesShipment['complaint_packing_user'] == getLoginId() ? 1:0?>" data_label_id="<?php echo md5($_salesShipment['label_id'])?>" data_order_id="<?php echo $_salesShipment['complaint_id']?>" data-group-action="repair/complaintaction" data-name="RMA Repair" class="mark_weborder_processed btn btn-info text-white"><i class="fa fa-check"></i> Mark Processed</a> <a class="btn btn-success" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?php echo $_salesShipment["complaint_id"];?>|C', '#<?php echo $_salesShipment["complaint_ticket_number"]?> Log Report')"><i class="fa fa-comments-o"></i></a></div>
                  </div>
                </div>
              </div>
            </div>
            <?php
		}
	}
	else{
		echo "<div class=\"empty_image_box col-sm-12 col-md-12 text-center py-3 my-3\" style=\"color:#aaa;\"><i class=\"fa fa-wrench fa-4x\"></i><br/><br/><br/><br/>No assigned RMA Repair available.</div>";
	}
  ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php }?>
  <script>
  $(".mark_weborder_processed, .download_weborder_label, .assi_si_block_handler").off();
  </script>
  
  <?php //include('card/technician_pending_works.php');?>
  <?php //include('card/recently_ordered_product.php');?>
  <?php //include('card/card_location.php');?>
  <?php
  $collection = new Collection();
  $collecteionForToday = $collection->getTodayCollectionRoute(date("Y-m-d"));
  //$collecteionForToday = $collection->getTodayCollectionRoute("2018-11-06");
  if($collecteionForToday)
  { ?>
  <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 my-2">
    <div class="card">
      <div class="card-header" data-step="10" data-title="Today's Collection" data-intro="Collection required to complete today"> <i class="fa fa-align-justify"></i> Today's Collection (<?php echo count($collecteionForToday);?>)
        <div class="card-actions"> <a title="See Shortest Route" href="<?php echo $app->siteUrl("shortestroute");?>"><i class="fa fa-map    font-2xl d-block m-t-2"></i></a> </div>
      </div>
      <div class="block-fluid table-sorting clearfix">
        <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
          <div class="row">
            <table class="table mb-0">
              <tbody>
                <?php
            foreach($collecteionForToday  as $_collection)
            {
                ?>
                <tr>
                  <td class="hidden-xs"><img class="img-circle" alt="<?php echo $_collection['driver_name']?>" src="<?php echo $_collection['driver_image']?>" height="45px"/></td>
                  <td><?php echo $_collection['customer_name']?><br/>
                    <a class="badge badge-info" data-toggle="popover-ajax" data-popover-action="collection" data-popover-id="<?php echo $_collection['wc_id']?>" href="<?php echo $app->siteUrl("updatecollection/".$_collection['wc_id']);?>"><?php echo $_collection['wc_code']?></a></td>
                  <td><a class="btn btn-sm btn-outline-success" href="tel:<?php echo beutifyMobileNumber($_collection['customer_phone'])?>"><i class="fa fa-phone-square fa-lg"></i> <?php echo beutifyMobileNumber($_collection['customer_phone'])?></a><br/>
                    <a class="btn btn-outline-primary btn-sm" style="margin-top:2px;" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?=$_collection['wc_id']?>|W', '<?=$_collection['wc_code']?> Log Report')"><i class="fa fa-comments-o fa-lg m-t-2"></i> &nbsp; Log a message</a></td>
                  <td class="hidden-xs"><?php echo $_collection['customer_full_address']?></td>
                  <td class="text-right"><a class="btn btn-sm" style="background-color:<?=$_collection['wc_status_color_code']?>;"><?php echo $_collection['wc_status_name']?></a><br/>
                    <a class="btn btn-sm btn-outline-success" style="margin-top:2px;" href="<?php echo $app->siteUrl("updatecollection/".$_collection['wc_id']);?>"><i class="fa fa-info-circle fa-lg m-t-2"></i> &nbsp; View</a></td>
                </tr>
                <?php			
            }          
          
          ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php 
  }?>
  <?php
  $complaint = new Complaint();
  $complaintToday = $complaint->getTodaysComplaint(date("Y-m-d"));
  if($complaintToday)
  { ?>
  <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 my-2">
    <div class="card">
      <div class="card-header" data-step="11" data-title="Todays added RMA" data-intro="Today's added repair request"> <i class="fa fa-align-justify"></i> Today's Added Repair Request (<?php echo count($complaintToday);?>) </div>
      <div class="block-fluid table-sorting clearfix">
        <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
          <div class="row">
            <table class="table mb-0">
              <tbody>
                <?php
            foreach($complaintToday  as $_complaint)
            {
                ?>
                <tr>
                  <td><img class="img-circle" alt="<?php echo $_complaint['technician_name']?>" src="<?php echo $_complaint['technician_image']?>" height="45px"/></td>
                  <td class="hidden-xs"><?php echo $_complaint['customer_name']?><br/>
                    <a class="text-muted" href="<?php echo $app->siteUrl("viewcomplaint/".$_complaint['complaint_id']);?>"><?php echo $_complaint['complaint_ticket_number']?></a> <br/>
                    <span class="text-muted">Due on - <?php echo date('D,d M', strtotime($_complaint['complaint_due_date']))?></span></td>
                  <td><a class="btn btn-outline-primary btn-sm" style="margin-top:2px;" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?=$_complaint['complaint_id']?>|C', '<?=$_complaint['complaint_ticket_number']?> Log Report')"><i class="fa fa-comments-o fa-lg m-t-2"></i> &nbsp; Log a message</a><br/>
                    <span class="text-muted visible-xs hidden-sm hidden-md hidden-lg"><?php echo $_complaint['complaint_ticket_number']?></span> <span class="text-muted visible-xs hidden-sm hidden-md hidden-lg">Due on - <?php echo date('D,d M', strtotime($_complaint['complaint_due_date']))?></span></td>
                  <td class="text-right"><a class="btn btn-sm text-white" style="background-color:<?=$_complaint['complaint_status_clolor_code']?>;"><?php echo limitText($_complaint['complaint_status_name'], 16)?></a><br/>
                    <a class="btn btn-sm btn-outline-success" style="margin-top:2px;" href="<?php echo $app->siteUrl("viewcomplaint/".$_complaint['complaint_id']);?>"><i class="fa fa-info-circle fa-lg m-t-2"></i> &nbsp; View</a></td>
                </tr>
                <?php			
            }         
          
          ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php 
  } 
  ?>
  <?php
  $complaint = new Complaint();
  $complaintDueToday = $complaint->getTodaysDueComplaint(date("Y-m-d"));
  if($complaintDueToday)
  { ?>
  <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 my-2">
    <div class="card">
      <div class="card-header" data-step="12" data-title="Due RMA Today" data-intro="RMA which are due to customer today"> <i class="fa fa-align-justify"></i> Today's Due Repair Request (<?php echo count($complaintDueToday);?>) </div>
      <div class="block-fluid table-sorting clearfix">
        <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
          <div class="row">
            <table class="table mb-0">
              <tbody>
                <?php
            foreach($complaintDueToday  as $_complaint)
            {
                ?>
                <tr>
                  <td><img class="img-circle" alt="<?php echo $_complaint['technician_name']?>" src="<?php echo $_complaint['technician_image']?>" height="45px"/></td>
                  <td class="hidden-xs"><?php echo $_complaint['customer_name']?><br/>
                    <a class="text-muted" href="<?php echo $app->siteUrl("viewcomplaint/".$_complaint['complaint_id']);?>"><?php echo $_complaint['complaint_ticket_number']?></a> <br/>
                    <span class="text-muted">Added on - <?php echo date('D,d M', strtotime($_complaint['complaint_created_date']))?></span></td>
                  <td><a class="btn btn-outline-primary btn-sm" style="margin-top:2px;" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?=$_complaint['complaint_id']?>|C', '<?=$_complaint['complaint_ticket_number']?> Log Report')"><i class="fa fa-comments-o fa-lg m-t-2"></i> &nbsp; Log a message</a><br/>
                    <span class="text-muted visible-xs hidden-sm hidden-md hidden-lg"><?php echo $_complaint['complaint_ticket_number']?> <span class="text-muted visible-xs hidden-sm hidden-md hidden-lg">Added on - <?php echo date('D,d M', strtotime($_complaint['complaint_created_date']))?></span></span></td>
                  <td class="text-right"><a class="btn btn-sm text-white" style="background-color:<?=$_complaint['complaint_status_clolor_code']?>;"><?php echo limitText($_complaint['complaint_status_name'], 16)?></a><br/>
                    <a class="btn btn-sm btn-outline-success" style="margin-top:2px;" href="<?php echo $app->siteUrl("viewcomplaint/".$_complaint['complaint_id']);?>"><i class="fa fa-info-circle fa-lg m-t-2"></i> &nbsp; View</a></td>
                </tr>
                <?php			
            }          
          
          ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php 
  } 
  ?>
  <?php if(isAdminAccess()){?>
  <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
    <div class="row">
		
	  
      <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-2 mb-1">
        <div class="card">
          <div class="card-header" data-step="12" data-title="Sell Order" data-intro="Purchase/Profit report"> <i class="fa fa-align-justify"></i> Sell Profit Summary</div>
          <div class="card-body pt-1 pb-2 sellprofitsummary">
		  <div class="col-sm-12">
		  <div class="row">
			  <div class="col-sm-12 col-md-2">
			  <label><input class="profit-interval" type="radio" name="profit-interval" data-interval="7"> Last 7 days</label>
			  <label><input class="profit-interval" type="radio" name="profit-interval" data-interval="15"> Last 15 days</label>
			  <label><input class="profit-interval" type="radio" name="profit-interval" data-interval="30" checked> Last 30 days</label>
			  <label><input class="profit-interval" type="radio" name="profit-interval" data-interval="cm"> Current month</label>
			  <label><input class="profit-interval" type="radio" name="profit-interval" data-interval="lm"> Last month</label>
			  <label><input class="profit-interval" type="radio" name="profit-interval" data-interval="3m"> Last 3 month</label>
			  <label><input class="profit-interval" type="radio" name="profit-interval" data-interval="6m"> Last 6 month</label>
			  <label><input class="profit-interval" type="radio" name="profit-interval" data-interval="1y"> Last 12 month</label>
			  <label><input class="profit-interval" type="radio" name="profit-interval" data-interval="cy"> Current year</label>
			  <label><input class="profit-interval" type="radio" name="profit-interval" data-interval="ly"> Last year</label>
			  </div>
			  <div class="col-sm-12 col-md-6">
				<div class="chart-wrapper" style="max-height:250px">
					<div class="website-profit-summary-loader">
						<div class="loading_box text-center mt-2 mb-2"><br/><br/><center><span style="color:#C0C0C0;"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><span class="sr-only">Processing...</span><br/><br/>Processing... </span></center></div>'
					</div>
				  <canvas id="canvas-website-profit-summary" height="250"></canvas>
				</div>
<script type="application/javascript">
    
$(document).ready(function(){
	$(".profit-interval").on("change", function(){
		getProfitSummaryData($(this).attr('data-interval'));
	});
	getProfitSummaryData(30);
});
function getProfitSummaryData(interval){
	var data={
				action		:	'weborder/getprofitsummarydata',
				interval	:	interval
			};
	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			$(".summary-orders").html('<i class="fa fa-circle-o-notch fa-spin"></i>');
			$(".summary-purchase").html('<i class="fa fa-circle-o-notch fa-spin"></i>');
			$(".summary-sell").html('<i class="fa fa-circle-o-notch fa-spin"></i>');
			$(".summary-profit").html('<i class="fa fa-circle-o-notch fa-spin"></i>');
			$(".summary-shipment").html('<i class="fa fa-circle-o-notch fa-spin"></i>');
			$(".summary-realised").html('<i class="fa fa-circle-o-notch fa-spin"></i>');
			$('.website-profit-summary-loader').show();
			$('#canvas-website-profit-summary').hide();
		},		
		success:function(output){
			$('.website-profit-summary-loader').hide();
			$('#canvas-website-profit-summary').show();
			var arr	=	JSON.parse(output);	
			drawProfitSummaryChart(arr[2]);
			drawProfitSummaryRecord(arr[2]['recordData']);
		}
	});	
}
function drawProfitSummaryRecord(record){
	$(".summary-orders").html(record.orders);
	$(".summary-purchase").html(record.purchase);
	$(".summary-sell").html(record.sell);
	$(".summary-profit").html(record.profit);
	$(".summary-shipment").html(record.shipment);
	$(".summary-realised").html(record.realised);
}
var chartProfit = null;
function drawProfitSummaryChart(record){
	var ctx = document.getElementById('canvas-website-profit-summary');
	if(chartProfit!= null)
	{
	 chartProfit.destroy();
	}
	var lineChartData = {
        labels : record.chartData.label,
		height:200,
        datasets : [
            {
				label: 'Purchase',
                backgroundColor : 'rgba(  251, 190, 15 , 0.15)',
                borderColor : 'rgba(  251, 190, 15 ,0.75)',
                pointBorderColor : 'rgba(  236, 179, 14 ,1)',
                data : record.chartData.purchase
            },
			{
				label: 'Sell',
                backgroundColor : 'rgba(  35, 145, 254 , 0.15)',
                borderColor : 'rgba(   35, 145, 254 ,0.75)',
                pointBorderColor : 'rgba(   20, 125, 231 ,1)',
                data : record.chartData.sell
            },
			{
				label: 'Profit',
                backgroundColor : 'rgba(  63, 194, 50 , 0.15)',
                borderColor : 'rgba(  63, 194, 50 ,0.75)',
                pointBorderColor : 'rgba( 41, 173, 28 ,1)',
                data : record.chartData.profit
            }
			
        ]
    };
    chartProfit = new Chart(ctx, {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true,
			maintainAspectRatio: false,
			elements:{
				point:{
					radius:2,
					borderWidth:1,
					hoverBorderWidth:2
				},
				line:{
					borderWidth:2,
					borderJoinStyle: 'round'
				}
			},
			plugins: {
			  legend: {
				display: false,
			  },
			}
        }
    });
}
    
</script>
			  </div>
			  <div class="col-sm-12 col-md-4">
				<ul class="list-group">
						<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">A.Completed Orders:<span class="badge badge-default summary-orders"><i class="fa fa-circle-o-notch fa-spin"></i></span></li>
						<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">B.Purchase:<span class="badge badge-warning summary-purchase"><i class="fa fa-circle-o-notch fa-spin"></i></span></li>
						<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">C.Selling:<span class="badge badge-info summary-sell"><i class="fa fa-circle-o-notch fa-spin"></i></span></li>
						<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">D.Profit(B-C):<span class="badge badge-success summary-profit"><i class="fa fa-circle-o-notch fa-spin"></i></span></li>
						<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">E.Shipment:<span class="badge badge-danger summary-shipment"><i class="fa fa-circle-o-notch fa-spin"></i></span></li>
						<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">F.Realised(D-E):<span class="badge badge-success summary-realised"><i class="fa fa-circle-o-notch fa-spin"></i></span></li>
					</ul>
			  </div>
			   
		  </div>
		  
		 </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php }?>		  
		  
		  
		  
		  
		  
  
  
  <div class="col-lg-8 col-md-12">
    <div class="card">
      <div class="card-header" data-step="13" data-title="Activity list" data-intro="Activities performed by employee"> <i class="fa fa-align-justify"></i> Activity List </div>
      <div class="block-fluid table-sorting clearfix">
        <table id="tblSortableActivity" class="table table-striped">
          <thead>
            <tr>
              <th>User</th>
              <th>Name</th>
              <!--<th>Reference</th>-->
              <th>Activity</th>
              <th>Date</th>
              <th>IP</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-12">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card" style="width:100%;">
          <div class="card-header" data-step="14" data-title="Worksheet timeline" data-intro="Employee worksheet timeline as per presence on support system"> <i class="fa fa-align-justify"></i> User's Work Sheet Timeline</div>
          <div class="block-fluid table-sorting clearfix">
            <ul class="icons-list" style="width:100%;">
              <?php 
		$activity = Activity::todayWorking();
		if($activity)
		{
			foreach($activity as $row)
			{ 			
			?>
              <li style="clear:both; height:46px;"> <img width="36px" src="<?=$row['user_image']?>" alt="<?=$row['user_name']?>" style="float:left;" />
                <div class="desc" style="border-bottom:none;">
                  <div class="title">
                    <?=limitText($row['user_name'], 15)?>
                  </div>
                  <small>worked
                  <?=$row['working']?>
                  </small> </div>
                <div class="value">
                  <div class="small text-muted">Last Login
                    <?=$row['last_login_day']?>
                  </div>
                  <strong>
                  <?=$row['last_login']?>
                  </strong> </div>
                <div class="progress progress-xs" style="height:4px; margin-top:-2px;" data-val="<?=$row['seconds']?>" data-total="<?=$row['total']?>">
                  <div class="progress-bar bg-<?=$row['class']?>" role="progressbar" style="width: <?=$row['percent']?>%" aria-valuenow="<?=$row['percent']?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </li>
              <?php
			}	
		}
		?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if(1){?>
<div class="row">
  <div class="col-lg-12">
    <div class="card" style="width:100%;">
      <?php 
		$delayDay = 60;
		$websiteOrder = new WebsiteOrder();
		$fulFillList = $websiteOrder->getFulfillOrder($delayDay);
		?>
      <div class="card-header" data-step="15" data-title="Fulfilled orders" data-intro="System's analysed order list which are fulfilled as per available stock on basis of Order's product SKU"> <i class="fa fa-check-circle text-success"></i> <?php echo count($fulFillList);?> Order are Fulfilled yet Pending
        <div class="card-actions"> <a class="redirect" href="pendingorder"> <small class="text-muted">View All</small> </a> </div>
      </div>
      <div class="card-body p-1">
        <div class="row">
          <?php
				if($fulFillList)
				{?>
          <p>
            <?php
					foreach($fulFillList as $_webOrder)
					{ 			
					?>
          <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2"> <a data-trigger="hover" data-toggle="popover-ajax"  data-popover-action="order" data-popover-id="<?php echo $_webOrder["web_order_id"]?>" class="btn btn-outline-dark btn-sm p-0 redirect" style="margin-bottom: 4px" href="<?php echo ("viewweborder/".$_webOrder['web_order_id'])?>"> <img src='<?php echo getResizeImage($_webOrder["store_icon"], 30)?>'/> <span><?php echo limitText($_webOrder['web_order_number'],9)?> &nbsp; <?php echo orderDelayLevel($_webOrder["web_order_created_date"], 'text-white')?></span> </a> </div>
          <?php
					}	
					?>
          </p>
          <?php
				}
				?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php }?>
<?php if(0){ // Google Web Order Chart?>
<!--Google chart for wweb order-->
<div class="row">
  <div class="col-md-12">
    <ul class="inline-block pull-right">
      <li>Line</li>
      <li>Bar</li>
    </ul>
    <div id="chart_div" style="width: 100%; height: 400px;"></div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">
	<?php 
	$woData = WebsiteOrder::getOrderStatusChart(30);
	?>
	$(document).ready(function(){
		  google.charts.load('current', {'packages':['corechart']});
		  google.charts.setOnLoadCallback(drawVisualization);


		  function drawVisualization() {
			// Some raw data (not necessarily accurate)
			var data = google.visualization.arrayToDataTable([
			  [<?php echo "'".implode("' , '", array_keys($woData[0]))."'"?>]
				<?php foreach($woData as $_woData){
					$_val = array_values($_woData);
					echo  ", ['$_val[0]',".implode(",", array_slice(array_values($_woData), 1))."]";
				}?>
			]);

			var options = {
			  theme: 'maximized',
			  title : 'Last 30 Days Web Order status',
			  vAxis: {title: 'Order'},
			  hAxis: {title: 'Day'},
			  seriesType: 'bars',
			  series: {5: {type: 'line'}},
			  bar : {groupWidth: '70%'}
			  
			  };
			/*  
			var options = {
			  theme: 'maximized',
			  title: 'Last 30 Days Web Order status',
			  curveType: 'function',
			  legend: { position: 'bottom' }
			};
			*/
			var options = {
			  theme: 'maximized',
			  vAxis: {title: 'Order'},
			  hAxis: {title: 'Day'},
			  title: 'Last 30 Days Web Order status',
			  curveType: 'function',
			  series: {5: {type: 'line'}},
			  legend: { position: 'bottom' }
			};


			var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
			chart.draw(data, options);
		  }
	});
      
    </script>
<?php }?>
<script type="text/javascript">
var serverdata = {
				"action"	:	"viewactivitylist",				
		   };
$(document).ready(function() {
    $('#tblSortableActivity').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax":  {
            "url": "<?=$app->basePath("server_processing.php")?>",
            "type": "POST",
			"data": serverdata
        },
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [0,1,2,3,4], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 1,3,4 ] }]
    });
});
</script>
<?php 
$cmp = new Complaint(0);
$emp = new Employee(0);
$inv = new SalesInvoice(0); ?>
<div class="row mt-2">
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="card">
      <div class="card-header"> Repair Revenue By month </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="canvas-repair-revenuue" height="250"></canvas>
        </div>
      </div>
    </div>
    <script type="application/javascript">		
<?php $ChartData = $cmp->getRevenueReport();?>
var randomScalingFactor = function(){ return Math.round(Math.random()*50)};
    var lineChartData = {
        labels : <?php echo json_encode(array_reverse($ChartData['label']))?>,
        datasets : [
            {
				label: 'Repair Revenue (£ <?php echo array_sum($ChartData['value'])?>)',
                backgroundColor : 'rgba( 83,109,230, 0.25)',
                borderColor : 'rgba( 83,109,230,0.85)',
                pointBackgroundColor : 'rgba( 83,109,230,0.9)',
                pointBorderColor : 'rgba( 83,109,230,1)',
                data : <?php echo json_encode(array_reverse($ChartData['value']))?>
            }
        ]
    };

    var ctx = document.getElementById('canvas-repair-revenuue');
    var chart = new Chart(ctx, {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true
        }
    });
</script> 
  </div>
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="card">
      <div class="card-header"> Sales Invoice By month </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="canvas-salesinvoice-revenuue" height="250"></canvas>
        </div>
      </div>
    </div>
    <script type="application/javascript">		
<?php $ChartData = $inv->getRevenueReport();?>
var randomScalingFactor = function(){ return Math.round(Math.random()*50)};
    var lineChartData = {
        labels : <?php echo json_encode(array_reverse($ChartData['label']))?>,
        datasets : [
            {
				label: 'Sales Invoice (£ <?php echo array_sum($ChartData['value'])?>)',
                backgroundColor : 'rgba( 16,196,105, 0.25)',
                borderColor : 'rgba(  16,196,105,0.85)',
                pointBackgroundColor : 'rgba(  16,196,105,0.9)',
                pointBorderColor : 'rgba(  16,196,105,1)',
                data : <?php echo json_encode(array_reverse($ChartData['value']))?>
            }
        ]
    };

    var ctx = document.getElementById('canvas-salesinvoice-revenuue');
    var chart = new Chart(ctx, {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true
        }
    });
</script> 
  </div>
  <div class="col-sm-12 col-md-12 col-lg-12">
    <div class="card">
      <div class="card-header" data-step="16" data-title="Last 30 Day's Order" data-intro="Last 30 days Ordered product summary with completed and cancelled"> Website Order history </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="canvas-website-order-history" height="300"></canvas>
        </div>
      </div>
    </div>
    <script type="application/javascript">		
<?php $ChartData = WebsiteOrder::getOrderHistoryChart();
$WoTotal = array_reverse(array_column($ChartData, 'total'));
$WoCompleted = array_reverse(array_column($ChartData, 'completed'));
$WoCancelled = array_reverse(array_column($ChartData, 'cancelled'));
?>
var randomScalingFactor = function(){ return Math.round(Math.random()*50)};
    var lineChartData = {
        labels : <?php echo json_encode(array_reverse(array_keys($ChartData)))?>,
        datasets : [
            {
				label: 'Placed (<?php echo array_sum($WoTotal)?>)',
                backgroundColor : 'rgba( 83,109,230, 0.75)',
                borderColor : 'rgba( 83,109,230,0.85)',
                //pointBackgroundColor : 'rgba( 83,109,230,0.9)',
                pointBorderColor : 'rgba( 83,109,230,1)',
                data : <?php echo json_encode($WoTotal)?>
            },
			{
				label: 'Completed (<?php echo array_sum($WoCompleted)?>)',
                backgroundColor : 'rgba( 16,196,105, 0.75)',
                borderColor : 'rgba(  16,196,105,0.85)',
                //pointBackgroundColor : 'rgba(  16,196,105,0.9)',
                pointBorderColor : 'rgba(  16,196,105,1)',
                data : <?php echo json_encode($WoCompleted)?>
            },
			{
				label: 'Cancelled (<?php echo array_sum($WoCancelled)?>)',
                backgroundColor : 'rgba( 255,91,91, 0.75)',
                borderColor : 'rgba( 255,91,91,0.85)',
                //pointBackgroundColor : 'rgba( 255,91,91,0.9)',
                pointBorderColor : 'rgba(255,91,91,1)',
                data : <?php echo json_encode($WoCancelled)?>
            }
			
        ]
    };

    var ctx = document.getElementById('canvas-website-order-history');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: lineChartData,
        options: {
            responsive: true
        }
    });
</script> 
  </div>
</div>
<?php if(0){?>
<div class="card">
  <div class="card-block">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
        <h4 class="card-title mb-0">Repair Request Status</h4>
        <div class="small text-muted">Last 30 days from Today</div>
        <div class="chart-wrapper" style="height:300px;margin-top:40px;">
          <canvas id="complaint_status_last_30_days" class="chart" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
  <?php $mainChart = $cmp->ComplaintDueDate();?>
  <div class="card-footer" style="background:none;">
    <ul>
      <li>
        <div class="text-muted">Total Repair Request</div>
        <strong>
        <?=array_sum(explode(",",$mainChart["total"]))?>
        Repair Request</strong>
        <div class="progress progress-xs mt-h" style="height:5px;">
          <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="<?= array_sum(explode(",",$mainChart["total"]))?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
      
      <!--<li>
              <div class="text-muted">Total Complaint</div>
              <strong>78.706 Views (60%)</strong>
              <div class="progress progress-xs mt-h">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </li>-->
      <li class="hidden-sm-down">
        <div class="text-muted">Completed</div>
        <strong>
        <?= array_sum(explode(",",$mainChart["completed"]))?>
        RMA (
        <?= round(((array_sum(explode(",",$mainChart["completed"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>
        %)</strong>
        <div class="progress progress-xs mt-h" style="height:5px;">
          <div class="progress-bar bg-success" role="progressbar" style="width: <?= round(((array_sum(explode(",",$mainChart["completed"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>%" aria-valuenow="<?= array_sum(explode(",",$mainChart["completed"]))?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
      <li class="hidden-sm-down">
        <div class="text-muted">To Be Repaired</div>
        <strong>
        <?= array_sum(explode(",",$mainChart["toberepaired"]))?>
        RMA (
        <?= round(((array_sum(explode(",",$mainChart["toberepaired"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>
        %)</strong>
        <div class="progress progress-xs mt-h" style="height:5px;">
          <div class="progress-bar bg-danger" role="progressbar" style="width: <?= round(((array_sum(explode(",",$mainChart["toberepaired"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>%" aria-valuenow="<?= array_sum(explode(",",$mainChart["toberepaired"]))?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
      <li class="hidden-sm-down" style="">
        <div class="text-muted">WR. Mgr Approval</div>
        <strong>
        <?= array_sum(explode(",",$mainChart["wrapproval"]))?>
        RMA (
        <?= round(((array_sum(explode(",",$mainChart["wrapproval"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>
        %)</strong>
        <div class="progress progress-xs mt-h" style="height:5px;">
          <div class="progress-bar" role="progressbar" style="width: <?= round(((array_sum(explode(",",$mainChart["wrapproval"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>%" aria-valuenow="<?= array_sum(explode(",",$mainChart["wrapproval"]))?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
      <li class="hidden-sm-down">
        <div class="text-muted">Others</div>
        <strong>
        <?= array_sum(explode(",",$mainChart["other"]))?>
        RMA (
        <?= round(((array_sum(explode(",",$mainChart["other"]))/array_sum(explode(",",$mainChart["total"])))*100),2)?>
        %)</strong>
        <div class="progress progress-xs mt-h" style="height:5px;">
          <div class="progress-bar bg-warning" role="progressbar" style="width: <?= isset($mainChart["other"]) ? round(((array_sum(explode(",",$mainChart["other"]))/array_sum(explode(",",$mainChart["total"])))*100),2):0?>%" aria-valuenow="<?= isset($mainChart["other"])? array_sum(explode(",",$mainChart[" other "])):0?>" aria-valuemin="0" aria-valuemax="<?= array_sum(explode(",",$mainChart["total"]))?>"></div>
        </div>
      </li>
    </ul>
  </div>
  <script type="application/javascript">

  var data1 = [<?=$mainChart["total"]?>];
  var data2 = [<?=$mainChart["completed"]?>];
  var data3 = [<?=$mainChart["toberepaired"]?>];
  var data4 = [<?=$mainChart["wrapproval"]?>];
  var data5 = [<?=$mainChart["other"]?>];

  
  var data = {
    labels: [<?=$mainChart["perday"]?>],
    datasets: [
      {
        label: 'Repair Collected',
        backgroundColor: convertHex($.brandInfo,10),
        borderColor: $.brandInfo,
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        data: data1
      },
      {
        label: 'Repair Completed',
        backgroundColor: 'transparent',
        borderColor: $.brandSuccess,
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        data: data2
      },
      {
        label: 'To be Repaired',
        backgroundColor: 'transparent',
        borderColor: $.brandDanger,
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        borderDash: [8, 5],
        data: data3
      },
      {
        label: 'Approval to WH Mgr',
        backgroundColor: 'transparent',
        borderColor: '#909',
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        borderDash: [8, 5],
        data: data4
      },
      {
        label: 'Other',
        backgroundColor: 'transparent',
        borderColor: '#0FF',
        pointHoverBackgroundColor: '#fff',
        borderWidth: 2,
        borderDash: [8, 5],
        data: data5
      }
    ]
  };

  var options = {
	responsive: true,
    maintainAspectRatio: false,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        gridLines: {
          drawOnChartArea: false,
        }
      }],
      yAxes: [{
        ticks: {
          beginAtZero: true,
          maxTicksLimit: 5,
          stepSize: Math.ceil(<?= max(explode(" , ",$mainChart["total"]))?> / 5),
          max: <?= max(explode(" , ",$mainChart["total"]))?>
        }
      }]
    },
    elements: {
      point: {
        radius: 0,
        hitRadius: 10,
        hoverRadius: 4,
        hoverBorderWidth: 3,
      }
    },
  };
  var ctx = $('#complaint_status_last_30_days');
  var mainChart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: options
  });
  </script> 
</div>
<div class="row">
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="card">
      <div class="card-header"> Repair Request Origin Store Chart </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="complaint_origin_store_chart" height="250"></canvas>
        </div>
      </div>
    </div>
    <script type="application/javascript"> 
  <?php  $Data = $cmp->ComplaintOriginchart(); ?>
  var doughnutData = {
        labels: [<?=$Data['labels']?>],
        datasets: [{
            data: [<?=$Data['values']?>],
            backgroundColor: getColor(<?=$Data['count']?>),
            hoverBackgroundColor: getColor(<?=$Data['count']?>)
        }]
    };
    var ctx = document.getElementById('complaint_origin_store_chart');
    var chart = new Chart(ctx, {
        type: 'pie',
        data: doughnutData,
        options: {
            responsive: true
        }
    });
</script> 
  </div>
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="card">
      <div class="card-header"> Repair Request / Collection Request </div>
      <div class="card-block">
        <div class="chart-wrapper">
          <canvas id="canvas-complaint-collection" height="250"></canvas>
        </div>
      </div>
    </div>
    <script type="application/javascript">	
<?php $ChartData = $cmp->complaintCollectionCountChart();?>
var randomScalingFactor = function(){ return Math.round(Math.random()*50)};
    var lineChartData = {
        labels : <?php echo $ChartData['MONTH']?>,
        datasets : [
            {
                label: 'Repair Request',
                backgroundColor : 'rgba(206,17,98,0.2)',
                borderColor : 'rgba(220,5,70,1)',
                pointBackgroundColor : 'rgba(243,18,136,1)',
                pointBorderColor : '#CD055A',
                data : <?php echo $ChartData['COMPLAINT']?>
            },
            {
                label: 'Collection Request',
                backgroundColor : 'rgba(5,115,235,0.2)',
                borderColor : 'rgba(14,124,189,1)',
                pointBackgroundColor : 'rgba(17,86,200,1)',
                pointBorderColor : '#123287',
                data : <?php echo $ChartData['COLLECTION']?>
            }
        ]
    };

    var ctx = document.getElementById('canvas-complaint-collection');
    var chart = new Chart(ctx, {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true
        }
    });
	
</script> 
  </div>
</div>
<?php }?>
<script type="application/javascript">		
$(document).ready(function(e) {
	showMeIntro('dashboard');  
});
$('[data-toggle="popover"]').popover();
</script> 
