<div class="row" id="collection_form_container">
  <?php 
  Modal::load(array('Refund'));
  $refund = new Refund();
  $isRefundExist = $refund->isRefundExist('O', $web_order_id);
  if($isRefundExist == 1){?>
  <div class="col-xs-12 col-sm-12 col-md-12 pt-1">
    <div class="alert alert-danger card" role="alert">
      <h4 class="alert-heading">Notice !</h4>
      <p>Refund initiated for this order. Please make sure refund status is cancelled before processing this order. You can this ignore warnnig if this order was cancelled or you are going to cancel.</p>
      <hr>
      <p class="mb-0">Whenever you take any action on this order, you should check order status on gateway or Refund status.</p>
    </div>
  </div>
  <?php	
  }?>
  <?php 
  $orderBasicDetails = "
  <div class=\"col-sm-12 pt-1\">
    <dl class=\"row\">
	<div class=\"col-sm-12\">
		<div class=\"card\" style=\"background-color:$wc_status_color_code;\">
                  <div class=\"card-block\">
						
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Order :</dt>
			<dd class=\"col-xs-8\">#$web_order_number</dd>
		</div>
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Store :</dt>
			<dd class=\"col-xs-8\"><img alt=\"store\" style=\"width: 80px;\" src=\"".$app->imagePath($store_logo)."\" /></dd>
		</div>
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Amount :</dt>
			<dd class=\"col-xs-8\"> $web_order_currency $web_order_total_amount</dd>
		</div>
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Status :</dt>
			<dd class=\"col-xs-8\">$wc_status_name</dd>
		</div>
  </div></div></div></div>";
		  
  ?>
  <div class="col-xs-12 col-sm-12 col-md-12">
    <form id="werborderform" name="werborderform">
      <div class="nav-tabs-boxed">
        <ul class="nav nav-tabs" role="tablist">
        	<li class="nav-item"><a data-step="1" data-title="Information" data-intro="See all basic order information including customer, website, payment method, address and products as well" class="nav-link text-center active" data-toggle="tab" href="#tab-order" role="tab" aria-controls="order" aria-selected="false"><i class="icon-handbag"></i> <span class="hidden-xs">Order</span></a></li>
          <li class="nav-item"><a data-step="2" data-title="Comments" data-intro="Order log and comments with alla activity timeline on it" class="nav-link text-center" data-toggle="tab" href="#tab-comments" role="tab" aria-controls="comments" aria-selected="true"><i class="fa fa-comments-o"></i> <span class="hidden-xs">Comments</span></a></a></li>          
          <li class="nav-item"><a data-step="3" data-title="Order Processing" data-intro="Assign work to user for order like picking, packing, processing etc." class="nav-link text-center" data-toggle="tab" href="#tab-actions" role="tab" aria-controls="actions" aria-selected="false"><i class="icon-rocket"></i> <span class="hidden-xs">Actions</span></a></li>
          <li class="nav-item" data-step="4" data-title="Labels & Shipment" data-intro="Upload or Download labels and invoice for order"><a class="nav-link text-center" data-toggle="tab" href="#tab-labels" role="tab" aria-controls="labels" aria-selected="false"><i class="icon-tag"></i> <span class="hidden-xs">Labels</span></a></li>
          <li class="nav-item"><a data-step="5" data-title="Status update" data-intro="Find or update current order position" class="nav-link text-center" data-toggle="tab" href="#tab-status" role="tab" aria-controls="status" aria-selected="false"><i class="icon-support"></i> <span class="hidden-xs">Status</span></a></li>
          <li class="nav-item"><a data-step="6" data-title="Commision" data-intro="Allot a commision to emplyee against this order " class="nav-link text-center" data-toggle="tab" href="#tab-commission" role="tab" aria-controls="commission" aria-selected="false"><i class="fa fa-bitcoin"></i> <span class="hidden-xs">Commission</span></a></li>
          <li class="nav-item"><a data-step="7" data-title="Refunds" data-intro="Initiate refund for this order" class="nav-link text-center" data-toggle="tab" href="#tab-refund" role="tab" aria-controls="refund" aria-selected="false"><i class="fa fa-registered"></i> <span class="hidden-xs">Refund</span></a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-comments" role="tabpanel" class="tab-pane">
            <div class="row">
            <?php echo $orderBasicDetails;?>
              <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-comments-o"></i> Comments </div>
                  <div class="card-block">
                    <?php
					$complaintLog = new ComplaintLog();
					if($commentsLog = $complaintLog->getLog($web_order_id, 'O')){
						foreach($commentsLog as $_comments)
						echo ComplaintLog::drawLogRecord($_comments);
					}
					else{
						echo "No Comments available";	
					}
					?>
                  </div>
                </div>
              </div>
			  <div class="col-xs-12 col-sm-12 col-md-12">
				<div class="card">
                  <div class="card-header"><i class="fa fa-history"></i> Timeline </div>
                  <div class="card-block">
					<table class="table table-bordered">
					<thead>
					<tr>
						<th>#Img</th>
						<th>Name</th>
						<th>Activity</th>
						<th>Time</th>
					</tr>
					</thead>
					<tbody>
                    <?php 
					$timelineArry = Activity::getModuleActivityTimeline('O', $web_order_id);
					if($timelineArry){
						foreach($timelineArry as $timeline){
					?>
					<tr>
						<td><?php echo $timeline["user_image"]; ?></td>
						<td><?php echo $timeline["user_name"]; ?></td>
						<td><?php echo $timeline["message"]; ?></td>
						<td><?php echo $timeline["time"]; ?></td>
					</tr>
					<?php
						}
					}
					else{
					?>
					<tr>
						<td class="text-center" colspan="4">No activity performend on this order</td>
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
          <div id="tab-order" role="tabpanel" class="tab-pane active">
            <div class="row">
             <?php echo $orderBasicDetails;?>
              <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="card" data-step="8" data-title="Customer Information" data-intro="Order placing Customer full information.">
                  <div class="card-header"><i class="fa fa-user"></i> Customer information
                    <div class="card-actions"> <a class="redirect" href="<?php echo $app->basePath("updatecustomer/$web_order_customer_id")?>"> <small class="text-muted">View</small> </a> </div>
                  </div>
                  <div class="card-block">
                    <div class="bd-example">
                      <dl class="row">
                        <dt class="col-xs-4">Name :</dt>
                        <dd class="col-xs-8">
                          <?=isset($customer_fname)?$customer_fname:"";?>
                          <?=isset($customer_lname)?$customer_lname:"";?>
                        </dd>
                        <dt class="col-xs-4">Email : </dt>
                        <dd class="col-xs-8">
                          <?=isset($customer_email)?$customer_email:"";?>
                        </dd>
                        <dt class="col-xs-4">Phone :</dt>
                        <dd class="col-xs-8">
                          <?=isset($customer_phone)?$customer_phone:"";?>
                        </dd>
                        <dt class="col-xs-4">Type :</dt>
                        <dd class="col-xs-8">
                          <?=isset($customer_type_name)?$customer_type_name:"";?>
                        </dd>
                        <?php if(isset($customer_company) && trim($customer_company)!= ""):?>
                        <dt class="col-xs-4">Company :</dt>
                        <dd class="col-xs-8">
                          <?=isset($customer_company)?$customer_company:"";?>
                        </dd>
                        <?php endif; ?>
                        <dt class="col-xs-4">Status :</dt>
                        <dd class="col-xs-8">
                          <?=isset($customer_status)?"<i class='fa fa-circle text-success'></i> <span class='text-success'>Active</span>":"<i class='fa fa-circle text-danger'></i> <span class='text-danger'>Inactive</span>";?>
                        </dd>
                        <dt class="col-xs-4">Since :</dt>
                        <dd class="col-xs-8">
                          <?=isset($customer_created_date)?date("l, jS M-y h:iA", strtotime($customer_created_date)):""?>
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="card" data-step="9" data-title="Shipping Information" data-title="Information" data-intro="Order Shipping address as per given on order">
                  <div class="card-header"><i class="fa fa-align-justify"></i>Shipping Address</div>
                  <div class="card-block">
                    <div class="bd-example">
                      <dl class="row" style="margin-bottom:0px">
                        <dt class="col-xs-4">Address:</dt>
                        <dd class="col-xs-8">
                          <?=isset($customer_full_address)?$customer_full_address:"";?>
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header"><i class="fa fa-windows"></i>Store information
                    <div class="card-actions"> <img data-step="10" data-title="Store details" data-intro="Store/Website from where customer placed this order. you can get full order detail on this store." alt="store" style="margin-left: -50px; margin-top: 7px; width: 80px; margin-right: 8px;" src="<?php echo $app->imagePath($store_logo)?>" /> </div>
                  </div>
                  <div class="card-block">
                    <div class="bd-example">
                      <dl class="row" style="margin-bottom:0px">
                        <dt class="col-xs-4">Store :</dt>
                        <dd class="col-xs-8">
                          <?=isset($store_name)?$store_name:"";?>
                        </dd>
                        <dt class="col-xs-4">Dated :</dt>
                        <dd class="col-xs-8">
                          <?=isset($web_order_created_date)?date("l, jS M-y h:iA", strtotime($web_order_created_date)):""?>
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="card"  data-step="11" data-title="Pricing details"  data-intro="Order Payment & Shipping amount information">
                  <div class="card-header"> <i class="fa fa-align-justify"></i> Order Price & Shipping </div>
                  <div class="card-body">
                    <ul class="list-group mb-0">
                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Total product Price - <span class="badge badge-primary badge-pill">
                        <?=$web_order_currency;?>
                        <?=isset($web_order_total_amount)?$web_order_total_amount-$web_order_total_shipping:"#######";?>
                        </span> </li>
                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Total shipping Charge -<span class="badge badge-default badge-pill">
                        <?=$web_order_currency;?>
                        <?=isset($web_order_total_shipping)?$web_order_total_shipping:"#######";?>
                        </span> </li>
                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Total Paid Amount - <span class="badge badge-primary badge-pill">
                        <?=$web_order_currency;?>
                        <?=isset($web_order_total_amount)?$web_order_total_amount:"#######";?>
                        </span> </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="card" data-step="12" data-title="Payment gateway details" data-intro="Payment information with payment gateway used by customer at checkout time">
                  <div class="card-header"> <i class="fa fa-credit-card-alt"></i> Order Payments </div>
                  <div class="card-body">
                    <ul class="list-group mb-0">
                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Payment method -<span class="badge badge-pill">
                        <?=isset($web_order_payment_method)?"<img style=\"margin:-10px 0px;\" clas='img img-responsive' src='".$app->basePath('img/system/gateway/'.$web_order_payment_method.'-min.png')."' alt='".$web_order_payment_method."'/>":"#######";?>
                        </span> </li>
                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Website Order's number -<span class="badge badge-info badge-pill">
                        <?=isset($web_order_number)?$web_order_number:"#######";?>
                        </span> </li>
                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Total Ordered Items -<span class="badge badge-warning badge-pill">
                        <?=isset($web_order_total_quantity)?$web_order_total_quantity:"#######";?>
                        </span> </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 mt-2" id="weborderstatusblock" data-step="13" data-title="Payment status notice" data-intro="Payment processed status">
                <?php if($web_order_is_paid == 'Yes'):?>
                <div class="alert alert-success text-center card" role="alert"> Customer successfully paid payment of amount <b>
                  <?=isset($web_order_total_amount)?$web_order_total_amount:"#######";?> <?=$web_order_currency;?>
                  </b>                  
                  on <strong><?=isset($store_name)?$store_name:"";?></strong> 
				  </div>
                <?php elseif($web_order_is_paid == 'No'):?>
                <div class="alert alert-danger text-center" role="alert"> Customer not completed payment of amount <b>
                  <?=isset($web_order_total_amount)?$web_order_total_amount:"#######";?>
                  </b>
                  <?=$web_order_currency;?>
                  on <strong>
                  <?=isset($store_name)?$store_name:"";?>
                  </strong> </div>
                <?php endif;?>
				<?php if($web_order_is_paid !== 'Yes') :?>
                <div class="alert alert-warning text-center" role="alert"> System couldn't check payment status. Please make sure on website<br/>
                  Update Payment status <br/>
                  Payment was successfull
                  <label class="switch switch-icon switch-pill switch-success">
                    <input class="switch-input web_order_is_paid" id="web_order_is_paid_yes" value="Yes" name="web_order_is_paid" type="radio" >
                    <span class="switch-label" data-on="Yes" data-off="Yes"></span> <span class="switch-handle"></span> </label>
                  &nbsp;
                  <label class="switch switch-icon switch-pill switch-danger">
                    <input class="switch-input web_order_is_paid" id="web_order_is_paid_no" value="No" name="web_order_is_paid" type="radio">
                    <span class="switch-label" data-on="No" data-off="No"></span> <span class="switch-handle"></span> </label>
                </div>
                <?php endif;?>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="card">
                  <div class="card-header card-default" data-step="14" data-title="Products in Order" data-intro="Ordered Products details"><i class="fa fa-cart-arrow-down"></i>Order Product list</div>
                  <div class="card-block">
                    <?php
					$websiteOrderProduct = new WebsiteOrderProduct();
                    $productList = $websiteOrderProduct->getList($web_order_id);
				  	$productCounter = 0;
					if(count($productList))
					{
						$productCounter = 0;
						foreach($productList as $product)
						{ 
						$productCounter++;
						?>
                    <div class="card mb-2 mt-0">
                      <div class="card-header"> <i class="icon-tag"></i> <?php echo $productCounter;?> Product
                        <?php if($product['wo_product_premium'] == 1) echo "<img title=\"Premium Product\" src=\"".$app->imagePath(PREMIUM_ICON)."\" style=\"height:20px; width:20px;\">"?>
						<div class="card-actions">
							<a class="fullfillmentopener" data-toggle="modal" data-target="#appModalQuick" data-code="O" data-id="<?php echo $web_order_id;?>" data-reference="<?=isset($web_order_number)?$web_order_number:"";?>" data-sale-price="<?php echo $product['wo_product_sell_price']?>" data-sale-currency="<?=$web_order_currency;?>" data-item-id="<?php echo $product['wo_id'];?>" data-sku="<?php echo $product['wo_product_sku']?>" href="#"><i class="fa fa-check-circle text-info"></i></a>
						</div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-12">
                            <table id="sortingtable<?php echo $productCounter;?>" class="table-product table">
                              <thead>
                                <tr>
                                  <th>Item</th>
                                  <th class="text-center">SKU</th>
                                  <th class="text-center">Qty</th>
                                  <th class="text-center">Price</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <!--<td class="text-center"><a data-id="<?php echo $product['wo_id']?>" data-media-section="<?=MediaSection::$webOrderProductMedia?>" class="product_image_camera" id="product_image_camera_<?php echo $product['wo_id']?>" href="#" title="Take image for <?php echo $product['wo_product_name']?>"><i class="fa fa-2x fa-camera"></i></a></td>-->
                                  <td><?php if($product['wo_product_url'] != ""){?>
                                    <a target="new" href="<?php echo $product['wo_product_url']; ?>" style="display: inline-block; vertical-align: middle;"><img alt="Image" class="img-responsive" style="height:60px;" src="<?php echo $app->imagePath($product['wo_product_image'])?>"/></a>
                                    <?php }?>
																																																																 
				  
			   
																				 
                                    <span class="hidden-xs visible-md visible-lg"><?php echo $product['wo_product_name']?></span>								
									</td>
                                  <td class="text-center"><a target="new" href="<?php echo $product['wo_product_url']?>"><?php echo $product['wo_product_sku']?></a></td>
                                  <td class="text-center"><?php echo $product['wo_product_quantity']?></td>
                                  <td class="text-center"><?=$web_order_currency;?>
                                    <?php echo $product['wo_product_sell_price']?></td>
								<tr/>
								<tr>
                                  <td colspan="4">
								  <div class="row">
								  <div class="col-sm-6 col-md-3 col-xs-12">
								  <div class="form-group">
									<label>Serial Number</label>
									<input class="form-control input_text_upper metadataloader" type="text" maxlength="50" name="wo_product_srno[<?php echo $product['wo_id']?>]" value="<?php echo $product['wo_product_srno']?>" />
								  </div>
								  </div>
								  <div class="col-sm-6 col-md-3 col-xs-12">
								  <div class="form-group">
									<label>Product Code</label>
									<input class="form-control input_text_upper metadataloader" type="text" maxlength="12" name="wo_process_code[<?php echo $product['wo_id']?>]" value="<?php echo $product['wo_process_code']?>" />
								  </div></div>
								  <div class="col-sm-6 col-md-3 col-xs-12">
								  <div class="form-group">
									<label>Purchase Amount(<?php echo $web_order_currency?>)</label>
									<input class="form-control" type="number" step="0.01" min="0" name="wo_purchase_amount[<?php echo $product['wo_id']?>]" value="<?php echo $product['wo_product_purchase_amount']?>" />
								  </div></div>
								  <div class="col-sm-6 col-md-3 col-xs-12">
								  <div class="form-group">
									<label>Purchase Source</label>
									<input class="form-control" type="text" maxlength="50" name="wo_purchase_source[<?php echo $product['wo_id']?>]" value="<?php echo $product['wo_product_purchase_source']?>" />
								  </div>
								  </div>
								  <div class="col-sm-6 col-md-3 col-xs-12">
								  <div class="form-group">
									<label>Shipping Charges(<?php echo $web_order_currency?>)</label>
									<input class="form-control" type="text" maxlength="50" name="wo_shipping_charges[<?php echo $product['wo_id']?>]" value="<?php echo $product['wo_product_shipping_price']?>" />
								  </div>
								  </div>
								  <div class="col-sm-6 col-md-4 col-xs-12 text-center">
								  <a data-id="<?php echo $product['wo_id']?>" data-media-section="<?=MediaSection::$webOrderProductMedia?>" class="btn btn-info product_image_camera my-2" href="#" title="Take image for <?php echo $product['wo_product_name']?>"><i class="fa fa-camera"></i> &nbsp;Upload Media</a> &nbsp; <a data-order-id="<?php echo $web_order_id?>" data-id="<?php echo $product['wo_id']?>" data-section="O" class="btn btn-warning my-2 product_availbility_check" href="javascript:void(0)" title="Check availability from supplier for <?php echo $product['wo_product_name']?>"><i class="fa fa-check-circle"></i> Add Avilability Check</a>
								  </div>
								  </div>
								  <div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">
									<?php
									$productAvailbility = new ProductAvailbility();
									$availabilityList = $productAvailbility->getAvailbiltyChecked('O', $web_order_id, $product['wo_id']);
									if($availabilityList){
										foreach($availabilityList as $_availCheck){											
											echo "<div class='alert alert-{$_availCheck['pro_avail_class']} card'><h4 class=\"alert-heading\"><img style='height:24px' class='img img-circle' src='{$_availCheck['user_image']}' /> {$_availCheck['user_fname']} {$_availCheck['user_lname']}</strong> <span class='pull-right text-xs'>Checked on {$_availCheck['pro_avail_checked_time_full']}</span></h4><p class='word_break'>Checked this product availability with supplier <strong>{$_availCheck['supplier_name']}</strong> and mark as <strong>{$_availCheck['pro_avail_stock_status']}</strong><br/><i> ".applyAnchor($_availCheck['pro_avail_remark'])."</i><span class='pull-right mb-1'>Added {$_availCheck['pro_avail_created_date_now']}</span></p></div>";
										}
									}
									?>
									</div>
								  </div>
								  </td>
                                </tr>
								<?php if($product['wo_product_options'] != ""){?>
								<tr><td colspan="4">
								<div style="display:inline; line-height:2.2">
								<?php
										foreach(json_decode($product['wo_product_options'], true) as $_options){
											echo "<span class=\"badge-success badge_upgrade text-white\">$_options[label] : $_options[value]</span> "; 
										}
								?>
								</div>
								</td></tr>
								<?php }?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12" style="margin-top: 1px;">
                            <div class="card mb-0">
                              <div class="card-header card-default"><i class="fa fa-image"></i> Product Media Files
                                <div class="card-actions"><a href="javascript:void(0)">
                                  <input type="checkbox" class="order_product_all" value="<?php echo $product['wo_id'];?>">
                                  </a></div>
                              </div>
                              <div class="card-block">
                                <div class="row media_uploaded_image_box" id="media_uploaded_image_box_<?php echo $product['wo_id']?>">
                                  <?php 
								  $wopm = new WebsiteOrderProductMedia(0);
								  $recorded_snapshot_list = $wopm->getImageList($product['wo_id']);
								  if($recorded_snapshot_list):
						 $iCounter = 0;
								  foreach($recorded_snapshot_list as $itemCapt):
						 $iCounter++;
								  ?>
                                  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 thumb" data-record-id="<?php echo $itemCapt['wpoi_id']; ?>" id="item-web-order-product-media-container-<?php echo $itemCapt['wpoi_id']; ?>">
                                    <div class="card">
                                      <div class="card-header card-default">
                                        <label>
                                          <input type="checkbox" class="chk_order_product_img order_product_<?php echo $product['wo_id'];?>" value="<?php echo $itemCapt['wpoi_id']; ?>" name="order_product_images[<?php echo $product['wo_id'];?>][]">
                                          <?php echo $iCounter;?>. <?php echo $itemCapt['wpoi_sent_to_customer'] ? "<i title='Sent to Customer' class='fa text-success fa-check-circle'></i>":""?> <span id="product_image_title_<?php echo $itemCapt['wpoi_id']; ?>"><?php echo ($itemCapt['wpoi_title'] != '')? $itemCapt['wpoi_title']:"Image";?> </span></label>
                                        <div class="card-actions"><a class="btn-close" href="javascript:removeWebOrderProductCollectionMedia('<?php echo $itemCapt['wpoi_id']; ?>', '<?php echo $product['wo_id']; ?>');"><i class="icon-close"></i></a></div>
                                      </div>
                                      <div class="card-block p-a-1 clearfix"> <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="Image <?php echo $iCounter;?>" data-image="<?php echo $app->basePath($itemCapt['wpoi_image_path']); ?>" data-target="#image-gallery"> <img class="img-thumbnail" src="<?php echo getResizeImage($itemCapt['wpoi_image_path'], 100); ?>" alt="Image <?php echo $iCounter;?>"> </a>
                                        <div class="font-weight-normal  text-center"><?php echo $itemCapt['wpoi_remark']; ?></div>
                                      </div>
                                      <div class="p-1 text-center"><i class="fa fa-clock-o"></i> <?php echo dateView($itemCapt['wpoi_created_date'])?></div>
                                      <div class="card-block p-a-1 py-0 clearfix form-group">
                                        <div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
                                          <input type="text" class="form-control" id="txt_image_title_<?php echo $itemCapt['wpoi_id']; ?>" name="wc_loading_time" maxlength="50" value="" placeholder="Add/Update Image Title">
                                          <span class="input-group-addon bg-success updateImageTitle" data-id="<?php echo $itemCapt['wpoi_id']; ?>"> <span class="fa fa-check fa-lg"></span> </span> </div>
                                      </div>
                                    </div>
                                  </div>
                                  <?php
									endforeach;
									endif;
								?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php if($product['wo_process_code'] != ""):
						$item = CollectionProcess::get_process_code_details($product['wo_process_code']);
						if($item):
						?>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="card">
                                  <div class="card-header"> <i class="fa fa-credit-card-alt"></i> Product purchase Details </div>
                                  <div class="card-body">
                                    <ul class="list-group">
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Suplier name - <a href="<?php echo $app->siteUrl("updatecustomer/".$item['customer_id'])?>">
                                        <?=$item['customer_fname']. ' '.$item['customer_lname'];?>
                                        </a> </li>
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Suplier Contact -
                                        <?=$item['customer_email'];?>
                                        ,
                                        <?=$item['customer_phone'];?>
                                      </li>
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Address -
                                        <?=$item['customer_full_address'];?>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="card">
                                  <div class="card-header"> <i class="fa fa-credit-card-alt"></i> Product Details </div>
                                  <div class="card-body">
                                    <ul class="list-group">
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Product name -
                                        <?=$item['wci_name']. ' ('.$item['wc_process_item_name'] . ')';?>
                                      </li>
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Make / Model-
                                        <?=$item['wc_process_item_make'];?>
                                        /
                                        <?=$item['wc_process_item_model'];?>
                                      </li>
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Serial number -
                                        <?=$item['wc_process_item_sr_no'];?>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="card">
                                  <div class="card-header"> <i class="fa fa-credit-card-alt"></i> Collection Information </div>
                                  <div class="card-body">
                                    <ul class="list-group">
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Collection number - <a href="<?php echo $app->siteUrl("updatecollection/".$item['wc_id'])?>">
                                        <?=$item['wc_code'];?>
                                        </a> </li>
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Collection date-
                                        <?=$item['wc_completion_date'] == "" ? "Not Available" : date("l, jS M-y h:iA", strtotime($item['wc_completion_date']));?>
                                      </li>
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Collection mode -
                                        <?=$item['wc_is_drop_off'] == 0 ? "Collection" : ($item['wc_is_drop_off'] == 1 ? "Drop Off" : "Pickup");?>
                                      </li>
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Consignment code -
                                        <?=$item['wc_consignment_note']?>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="card">
                                  <div class="card-header"> <i class="fa fa-credit-card-alt"></i>Vehicle/Driver </div>
                                  <div class="card-body">
                                    <ul class="list-group">
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Carrier name -
                                        <?=$item['wc_carrier_id'] != 0 ? ($item['carrier_name'] ." / ".$item['carrier_licence_number']) : "N/A";?>
                                      </li>
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Driver name -
                                        <?=$item['wc_driver_id'] != 0 ? "<img class=\"img-avatar\" alt=\"image\" src=\"".getResizeImage($item["driver_image"],50)."\" height=\"40px\">". $item['driver_name'] : $item['wc_drop_off_driver'];?>
                                      </li>
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Vehicle -
                                        <?=$item['wc_vehicle_id'] != 0 ? $item['carrier_vehicle_reg_number']. ' / '.$item['vehicle_name']. ' / '.$item['vehicle_company']. ' / '.$item['vehicle_model'] : $item['wc_drop_off_vehicle'];?>
                                      </li>
                                      <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Collection mode -
                                        <?=$item['wc_is_drop_off'] == 0 ? "Collection" : ($item['wc_is_drop_off'] == 1 ? "Drop Off" : "Pickup");?>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php endif;?>
                        <?php endif;?>
						<div class="row">
                          <div class="col-md-12">
							  <div class="px-1 py-2">
								<a class="btn btn-info redirect" href="<?php echo $app->basePath("addcomplaintrequest/O/$web_order_id/$product[wo_id]")?>"><i class="fa-w icon-wrench"></i> Create RMA Request</a>
							  </div>
						  </div>
						</div>
                      </div>
                    </div>
                    <?php
						}
					}
					?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-actions" role="tabpanel" class="tab-pane">
            <div class="row">
            <?php echo $orderBasicDetails;?>
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header"> <i class="fa fa-people-carry"></i> Order Action</div>
                  <div class="card-block">
                    <div class="row py-0">
                      <div class="col-xs-12 col-sm-6 col-md-3">
                        <?php
		  if($web_order_status != 3 && $web_order_cancel_user == 0 && $web_order_picking_user){
			  $employee = new Employee($web_order_picking_user);
			  $picUserData = $employee->getDetails();
		  ?>
                        <div class="card">
                          <div class="card-body p-0">
							<div class="d-flex align-items-center justify-content-between p-2">
								<div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($picUserData["user_image"],50)?>"> </div>
								<div class="px-2 justify-content-center">
								  <div class="text-value-sm text-primary text-center">Picked by <?php echo limitText($picUserData['user_fname'] .' '.$picUserData['user_lname'], 10)?></div>
								  <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($web_order_picking_time))?></div>
								</div>
							</div>
							<?php if($web_order_packing_user == 0){?>
							<div class="d-flex align-items-center justify-content-between px-2">
								<div class="p-1 justify-content-center">
								 <a title="Remove Picking User" data-reset="<?php echo md5($web_order_picking_user);?>" data-action="unpick" class="undoaction btn btn-sm btn-danger text-white"><i class="fa fa-trash"></i> Unpick and Set Back to Pending</a>
								</div>
							</div>
							<?php }?>
							
                          </div>
                        </div>
                        <?php
		  }elseif($web_order_status != 3 && $web_order_cancel_user == 0){?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="pick" class="orderaction btn btn-outline-warning btn-block">STEP 1: PICK ORDER</a> </div>
                        </div>
                        <?php
		  }
		  else{
		?>
                        <div class="card">
                          <div class="card-body p-0 text-center p-2">
                            <div class="alert alert-warning mb-0" role="alert"> Order Picking cancelled </div>
                          </div>
                        </div>
                        <?php	  
		  }
		  ?>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3">
                        <?php
		  if($web_order_packing_user){
			  $employee = new Employee($web_order_packing_user);
			  $packUserData = $employee->getDetails();
		  ?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                            <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($packUserData["user_image"],50)?>"> </div>
                            <div class="px-2 justify-content-center">
                              <div class="text-value-sm text-primary text-center">Packed by <?php echo limitText($packUserData['user_fname'] .' '.$packUserData['user_lname'], 10)?></div>
                              <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($web_order_packing_time))?></div>
                            </div>
                          </div>
                          <?php if($web_order_process_user == 0){?>
                          <div class="card-body p-0 d-flex align-items-center  justify-content-center p-1">
                            <div class="card-body p-0 d-flex align-items-center  justify-content-between"> <a data-reset="<?php echo md5($web_order_packing_user);?>" data-action="pack" class="orderaction orderupdateaction btn btn-sm btn-info text-white">UPDATE PACKING USER</a> &nbsp; <a title="Remove Packing User" data-reset="<?php echo md5($web_order_packing_user);?>" data-action="unpack" class="undoaction btn btn-sm btn-danger text-white"><i class="fa fa-trash"></i></a> </div>
                          </div>
                          <?php }?>
                        </div>
                        <?php
		  }elseif($web_order_status != 3 && $web_order_cancel_user == 0){?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="pack" class="orderaction btn btn-outline-info btn-block">STEP 2: PACK ORDER</a> </div>
                        </div>
                        <?php
		  }
		  else{
		?>
                        <div class="card">
                          <div class="card-body p-0 text-center p-2">
                            <div class="alert alert-warning mb-0" role="alert"> Order Packing cancelled </div>
                          </div>
                        </div>
                        <?php	  
		  }
		  ?>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3">
                        <?php
		  if($web_order_process_user){
			  $employee = new Employee($web_order_process_user);
			  $procUserData = $employee->getDetails();
		  ?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                            <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($procUserData["user_image"],50)?>"> </div>
                            <div class="px-2 justify-content-center">
                              <div class="text-value-sm text-primary text-center">Processed by <?php echo limitText($procUserData['user_fname'] .' '.$procUserData['user_lname'], 10)?></div>
                              <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($web_order_process_time))?></div>
                              <div class="text-value-sm text-primary text-center"> <a title="Back to Packing" data-reset="<?php echo md5($web_order_process_user);?>" data-action="unprocess" class="undoaction btn btn-sm btn-danger text-white"><i class="fa fa-trash"></i></a> </div>
                            </div>
                          </div>
                        </div>
                        <?php
		  }elseif($web_order_status != 3 && $web_order_cancel_user == 0){?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="process" class="orderaction btn btn-outline-success btn-block">STEP 3: PROCESS ORDER</a> </div>
                        </div>
                        <?php
		  }
		  else{
		?>
                        <div class="card">
                          <div class="card-body p-0 text-center p-2">
                            <div class="alert alert-warning mb-0" role="alert"> Order Process cancelled </div>
                          </div>
                        </div>
                        <?php	  
		  }
		  ?>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3">
                        <?php
		  if($web_order_status == 3 && $web_order_cancel_user){
			  $employee = new Employee($web_order_cancel_user);
			  $proccancelData = $employee->getDetails();
		  ?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                            <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($proccancelData["user_image"],50)?>"> </div>
                            <div class="px-2 justify-content-center">
                              <div class="text-value-sm text-primary text-center">Cancelled by <?php echo limitText($proccancelData['user_fname'] .' '.$proccancelData['user_lname'], 10)?></div>
                              <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($web_order_cancel_time))?></div>
                            </div>
                          </div>
                        </div>
                        <?php
		  }elseif($web_order_status == 1 && $web_order_complete_user){
			  $employee = new Employee($web_order_complete_user);
			  $complatedData = $employee->getDetails();
		  ?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                            <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($complatedData["user_image"],50)?>"> </div>
                            <div class="px-2 justify-content-center">
                              <div class="text-value-sm text-primary text-center">Completed by <?php echo limitText($complatedData['user_fname'] .' '.$complatedData['user_lname'], 10)?></div>
                              <div class="text-value-sm text-center text-muted text-xs"><?php echo $web_order_complete_time ?  date('d M-Y h:i A', strtotime($web_order_complete_time)) : "Not available"?></div>
                            </div>
                          </div>
                        </div>
                        <?php
		  }else{?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center justify-content-between p-2"> <a data-action="cancel" class="orderaction btn btn-outline-danger btn-block">CANCEL ORDER</a> </div>
                        </div>
                        <?php
		  }
		  ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-labels" role="tabpanel" class="tab-pane">
            <div class="row">
            <?php echo $orderBasicDetails;?>
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-file-text"></i> Order Invoice/Label</div>
                  <div class="card-block">
                    <div class="row">
					  <input onchange="confirmOrderUpload(this.name, '<?php echo $web_order_id;?>', 'Invoice');" type="file" id="web_order_invoice_image" class="d-none web_order_invoice_image" name="web_order_invoice_image">
                      <?php if($web_order_process_user == 0 || isAdminRole()){?>
                      <?php if($web_order_invoice_image == ''){?>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="card web_order_label_image_processing">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-1">
                            <div class="pl-0 justify-content-start"><i class="fa fa-file-pdf-o text-danger fa-2x"></i> </div>
                            <div class="px-0 justify-content-center">
                              <div class="text-value-sm">
								<a onClick="$('.web_order_invoice_image').click()" class="btn btn-warning btn-block px-1 text-white">Upload Invoice </a>
							  </div>
							</div>
							<div class="px-0 justify-content-center">
                              <div class="text-value-sm">
								<a class="btn btn-info btn-block px-1 text-white fetchorderinvoice" data-id="<?php echo $web_order_id;?>">Fetch Invoice </a>							  
                              </div>
                            </div>
                          </div>
                          <div class="p-1"> Can upload untill the order is not Processed</div>
                        </div>
                      </div>
                      <?php }else{?>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center justify-content-between p-1">
                            <div class="pl-0 justify-content-start">Download Invoice</div>
                            <div class="px-0 justify-content-center">
                              <div class="text-value-sm"><a download href="<?php echo $app->basePath($web_order_invoice_image);?>" class="btn btn-success"><i class="fa fa-fw fa-download"></i> Invoice</a></div>
                            </div>
                          </div>
                          <div class="d-flex align-items-center justify-content-between p-1">
                            <div class="pr-1 justify-content-center"><i class="fa fa-fw fa-clock-o"></i> <?php echo dateView($web_order_invoice_date, "FULL")?></div>
							<div class="px-0 justify-content-center pull-right"><a onClick="$('.web_order_invoice_image').click()" class="btn btn-warning btn-block px-1 text-white">Update Invoice </a></div>
							
                          </div>
                        </div>
                      </div>
                      <?php }?>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="card web_order_label_image_processing">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-1">
                            <div class="pl-0 justify-content-start"> <i class="fa fa-file-pdf-o text-danger fa-2x"></i> </div>
                            <div class="px-0 justify-content-center">
                              <div class="text-value-sm"><a onClick="openLabelUploader('web_order_label_image', '<?php echo $web_order_id;?>', 'Label');" class="btn btn-info text-white">Upload Shipment Label </a>
                              </div>
                            </div>
                          </div>
                          <div class="p-1"> Can upload untill the order is not Processed </div>
                        </div>
                      </div>
                      <?php }?>
                      <?php 
			 Modal::load(array('WeborderLabels'));
			 $weborderLabels = new WeborderLabels();
			 $shipmentRecords = $weborderLabels->getWebOrderShipmentLabel(isset($web_order_id)?$web_order_id:0);
			if(count($shipmentRecords))
			{
				foreach($shipmentRecords as $_slabel)
				{
					echo getShipmentLabelBlock($_slabel, array('action' => 'webcancellabel'));  
				}
			}
			?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-commission" role="tabpanel" class="tab-pane">
            <div class="row">
            <?php echo $orderBasicDetails;?>
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-file-text"></i> Comission Staus</div>
                  <div class="card-block">
                    <div class="row">
                      <?php if($web_order_commission_user_file){?>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center justify-content-between p-1 bg-default">
                            <div class="pl-0 justify-content-start">
							<?php
							$commUploaduser = new Employee($web_order_commission_user_file_uploader);
							$commUploaduserDetails = $commUploaduser->getDetails();
							?>
                              <img title="Uploaded by <?php echo $commUploaduserDetails['user_fname'].' '.$commUploaduserDetails['user_lname']?>" class="img-circle" alt="<?php echo $commUploaduserDetails['user_fname']?>" src="<?php echo getResizeImage($commUploaduserDetails["user_image"],50)?>" height="32px"></div>
                            <div class="pl-1 justify-content-start"> <?php echo $commUploaduserDetails["user_fname"].' '.$commUploaduserDetails["user_lname"]?> uploaded commission proof </div>
                            <div class="px-2 justify-content-center">
                              <div class="text-value-sm"><a download href="<?php echo $app->basePath($web_order_commission_user_file);?>" class="btn btn-success"><i class="fa fa-download"></i></a></div>
                            </div>
                          </div>
                          <div class="d-flex align-items-center justify-content-end p-1">
                            <div class="px-1 justify-content-center"><i class="fa fa-clock-o"></i> <?php echo dateView($web_order_commission_user_file_time, "FULL")?></div>
                          </div>
                        </div>
                      </div>
                      <?php }else{?>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group">
                          <div class="card web_order_label_image_processing">
                            <div class="card-body p-0 d-flex align-items-center  justify-content-between p-1">
                              <div class="pl-0 justify-content-start"> <i class="fa fa-file-pdf-o text-danger fa-2x"></i> </div>
                              <div class="px-2 justify-content-center">
                                <div class="text-value-sm"><a data-id="<?php echo $web_order_id?>" data-media-section="<?=MediaSection::$webOrderCommissionFile?>" class="btn btn-warning btn-block px-1 text-white web_order_commission_user_file">Upload Commision proof </a> </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php }?>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="web_order_commission_user_chk">Assign Commission User</label>
                          <br/>
                          <label for="web_order_commission_user_chk"><i class="fa fa-bitcoin fa-lg m-t-2"></i> &nbsp; This was Reference order ?</label>
                          <label class="switch switch-icon switch-pill switch-success pull-right">
                            <input class="switch-input" id="web_order_commission_user_chk" <?php if(isset($web_order_commission_user) && $web_order_commission_user > 0 ) echo "checked";?> value="1" name="web_order_commission_user_chk" type="checkbox">
                            <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" style="display:<?php echo (isset($web_order_commission_user) && $web_order_commission_user > 0 ) ? "box":"none";?>;" id="web_order_assign_commission_user_box">
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="form-group">
                              <label for="web_order_commission_user">Select Commision User<sup>*</sup></label>
                              <select id="web_order_commission_user" name="web_order_commission_user" class="form-control" size="1">
                                <?php
									$Employee = new Employee(0);
									echo $Employee->getUserOption(0, isset($web_order_commission_user)?$web_order_commission_user:"0", "Commission user");
								?>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-status" role="tabpanel" class="tab-pane">
            <div class="row">
            <?php echo $orderBasicDetails;?>
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-file-text"></i> Order Staus</div>
                  <div class="card-block">
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="web_order_assign_technician_chk">Assign Technician</label>
                          <br/>
                          <label for="web_order_assign_technician_chk"><i class="fa fa-wrench fa-lg m-t-2"></i> &nbsp;Under Technician ?</label>
                          <label class="switch switch-icon switch-pill switch-success pull-right">
                            <input class="switch-input" id="web_order_assign_technician_chk" <?php if(isset($web_order_assign_technician) && $web_order_assign_technician > 0 ) echo "checked";?> value="1" name="web_order_assign_technician_chk" type="checkbox">
                            <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8" id="web_order_assign_technician_box" style="display:<?php echo (isset($web_order_assign_technician) && $web_order_assign_technician > 0 ) ? "box":"none";?>;">
                        <div class="row">
                          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                              <label for="web_order_assign_technician">Select Tehnician<sup>*</sup></label>
                              <select id="web_order_assign_technician" name="web_order_assign_technician" class="form-control" size="1">
                                <?php
					$Employee = new Employee(0);
					echo $Employee->getTechnicianOption(isset($web_order_assign_technician)?$web_order_assign_technician:"0");
				?>
                              </select>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                              <label for="web_order_assign_technician_date">Date/Time<sup>*</sup></label>
                              <div class="input-group date">
                                <input type='text' class="form-control" id="web_order_assign_technician_date" name="web_order_assign_technician_date" placeholder="YYYY-MM-DD" value="<?=(isset($web_order_assign_technician_date) && $web_order_assign_technician_date!= '')?date("Y-m-d", strtotime($web_order_assign_technician_date)):'';?>" />
                                <span class="input-group-addon">
                                <label style="margin-bottom:0px;" for="web_order_assign_technician_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                                </span> </div>
                              <script type="text/javascript">
						$('#web_order_assign_technician_date').datepicker({
							format: "yyyy-mm-dd",
							autoclose:true,
							startDate  : '<?=date('Y-m-d')?>',
							daysOfWeekHighlighted: '0,6',
							todayHighlight:true
						});
				</script> 
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="web_order_dispatched_store">Select Dispatched Location<sup></sup></label>
                          <select id="web_order_dispatched_store" name="web_order_dispatched_store" class="form-control" size="1">
                            <?php
                $storeLocation = new StoreLocation(0);
				echo $storeLocation->getStoreLocationOptions(isset($web_order_dispatched_store)?$web_order_dispatched_store:"", "Dispatch");
				?>
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="web_order_status">Order Status<sup>*</sup></label>
                          <select id="web_order_status" name="web_order_status" class="form-control" size="1">
                            <?php
                $WcStatus = new WcStatus(0);
				echo $WcStatus->getOptions(isset($web_order_status)?$web_order_status:"0");
				?>
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="web_order_status">Send order images to customer<sup></sup></label>
                          <a onClick="confirmMessage.Set('Are you sure to send all uploaded product image to customer... ?', 'sendweborderimagetocustomer', 0);" class="btn btn-info btn-block text-white"><i class="fa fa-upload"></i> &nbsp; Send Product images</a> </div>
                      </div>
                      <div class="col-xs-12">
                        <?php if($web_order_process_user == 0){?>
                        <div class="alert alert-warning" role="alert"> Note : STEP 4: To Complete Order option will be available after process the order </div>
                        <?php	
			}?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-refund" role="tabpanel" class="tab-pane">
            <div class="row">
            <?php echo $orderBasicDetails;?>
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-file-text"></i> Refund Status</div>
                  <div class="card-block">
                    <div class="row">
                      <div class="col-xs-12">
                        <div class="card">
                          <div class="card-body">
                            <?php					  
					  $refund = new Refund();					  
					  if($refund->isRefundExist('O', $web_order_id)){?>
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th>Code</th>
                                  <th>Date</th>
                                  <th>Status</th>
                                  <th>Progress</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
					  $refundCillection = $refund->getAllRefund('O', $web_order_id);
					  foreach($refundCillection as $_refund) { 
					  ?>
                                <tr>
                                  <td><?php echo $_refund['refund_code']?></td>
                                  <td><?php echo dateView($_refund['refund_initiated_date'], 'FULL')?></td>
                                  <td><?php echo $_refund['wc_status_name']?></td>
                                  <td><div class="pull-left"> <img height="24px" src="<?php echo getResizeImage($_refund['initiator_image'], 50)?>" class="img-avatar pull-left" title="Initiated by <?php echo $_refund['initiator_name']?>" alt="<?php echo $_refund['initiator_name']?>">
                                  <?php if($_refund['refund_process_by']){?>
                                   &nbsp;<img height="24px" src="<?php echo getResizeImage($_refund['processor_image'], 50)?>" class="img-avatar pull-left" title="Process by <?php echo $_refund['processor_name']?>" alt="<?php echo $_refund['processor_name']?>"> 
                                   <?php }?>
                                    <?php if($_refund['refund_completed_by']){?>
                                   &nbsp;<img height="24px" src="<?php echo getResizeImage($_refund['completor_image'], 50)?>" class="img-avatar pull-left" title="Processed by <?php echo $_refund['completor_name']?>" alt="<?php echo $_refund['completor_name']?>">
                                   <?php }?> </div></td>
                                  <td><div class="btn-group">
                                      <button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-navicon fa-lg m-t-2"></i> <span class="sr-only">Toggle Dropdown</span> </button>
                                      <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item redirect" href="<?php echo $app->basePath("viewrefund/".md5($_refund['refund_id']))?>"><i class="fa fa-truck fa-lg m-t-2"></i> View</a> <a class="dropdown-item redirect" href="<?php echo $app->basePath("addcreditnote/".md5($_refund['refund_id']))?>"><i class="fa fa-credit-card fa-lg m-t-2"></i> Credit Note</a> <a class="dropdown-item" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?php echo $_refund['refund_id']?>|R', '<?php echo $_refund['refund_code']?> Log Report')"><i class="fa fa-comments-o fa-lg m-t-2"></i> Log</a></div>
                                    </div></td>
                                </tr>
                                <?php }?>
                              <tbody>
                            </table>
                            <?php
					  }else{?>
                      		<div class="p-3">
                            <center>
                              No Refund Initiated for this Order
                            </center><br/><br/>
                            <center>
                            <a href="<?php echo $app->basePath("addrefund/O/$web_order_id"); ?>" class="redirect btn btn-info">Initiate Refund</a>
                            </center>
                            </div>
                            <?php 
					  }?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
	  <div class="mt-2">
        <div class="row">
          <div class="col-md-12">
			<div class="alert alert-info" role="alert"> 
			  <label class=""> Is Order under waiting for customer response </label>
			  <label class="switch switch-icon switch-pill switch-success pull-right">
				<input class="switch-input web_order_under_customer_review" id="web_order_under_customer_review" value="1" name="web_order_under_customer_review" type="checkbox" <?php if(isset($web_order_under_customer_review) && $web_order_under_customer_review != null) echo "checked";?> >
				<span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
				<?php if(isset($web_order_under_customer_review) && $web_order_under_customer_review != null){
				?><br/>
				Under customer review since - <?php echo dateView($web_order_under_customer_review, 'FULL')?>
				<?php
				}?>
			</div>
		  
		  </div>
		  
		  
		  <div class="col-md-12">
        <div class="alert alert-success" role="alert">
            <div class="row">
                <div class="col-md-9 col-sm-12">
			<div> 
			  <label class=""> Complement warranty added on this order</label>
			</div>
		  </div>
    		        <div class="col-md-3 col-sm-12">
        		       <div class="form-group">
                		  <select id="web_order_complement_warranty" name="web_order_complement_warranty" class="form-control" size="1">
                		  <option value="0"> No Complement Warranty</option>
                		  <?php
                               for($w=1; $w<=12; $w++){
                                   echo "<option ".((isset($web_order_complement_warranty) && $web_order_complement_warranty == $w) ? "selected" : "")." value='$w'>$w Month Warranty</option>";
                               }
                		  ?>
                          </select>
        		      </div>
        		</div>
		    </div>
		</div>
	  </div>
		  
		  
		  
		  
		  
		</div>
	  </div>
	  
	  
	  
	  
      <div class="mt-2">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-12">
                    <?php if(1 || $web_order_process_user != 0){?>
                    <button type="button" id="btn_complaint_submit" onClick="confirmMessage.Set('Are you sure to update webOrder information...?', 'updateweborder');" class="btn btn-success mt-0 submission_handler_btn"><i class="fa fa-check"></i>
                    <?=$btnText?>
                    </button>
                    <?php }
			?>
                    <a href="#" data-step="15" data-title="Add Comments" data-intro="Add comments on order here"  data-toggle="modal" data-target="#appModal"  class="btn btn-primary" onClick="openChatLogForm('<?php echo $web_order_id; ?>|O', '<img class=\'img-avatar\' src=\'<?php echo $app->imagePath($store_icon)?>\' style=\'height:24px; width:24px;\'> #<?php echo $web_order_number?> Log Report')">Comments</a>
                    <div class="btn-group dropup">
                      <button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action<span class="sr-only"></span></button>
                      <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item redirect" href="<?php echo $app->basePath("viewweborder/$web_order_id"); ?>"><i class="fa fa-refresh"></i> Reload</a>
                        <?php if(isAdmin()){?>
						<a class="dropdown-item" href="javascript:newWindow('<?php echo DOC::WOINVOICE($web_order_id)?>');"><i class="fa fa-file-pdf-o fa-fw text-danger"></i> Download Invoice</a>
                        <a class="dropdown-item redirect" href="<?php echo $app->basePath("addshipment/weborder/$web_order_id"); ?>"><i class="fa fa-truck text-warning"></i> Shipment</a> <a class="dropdown-item redirect" href="<?php echo $app->basePath("addrefund/O/$web_order_id"); ?>"><i class="fa fa-registered text-danger"></i> Refund</a> <a class="dropdown-item" href="#" data-toggle="modal" data-target="#appModal" onclick="checkOrderFulfilment('<?php echo $web_order_id;?>');"><i class="fa fa-cube"></i> Check Fulfilment</a>
                        <?php 
					}?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"updateweborder";?>"  />
              <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
              <input type="hidden" id="web_order_id" name="web_order_id" value="<?=isset($web_order_id)?$web_order_id:"0";?>"  />
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
<?php
if(0 && $web_order_packing_user){
$weborderLabels = new WeborderLabels();
$assignedShipmentRecords = $weborderLabels->getAssignedShipmentLabel($web_order_packing_user);
?>
  <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-3">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-2 mb-1">
        <div class="card">
          <div class="card-header"> <i class="fa fa-align-justify"></i> Other Assigned Order (<?php echo max(0,count($assignedShipmentRecords)-1)?>)</div>
          <div class="card-body pt-1 pb-2 assinordercontainer">
<?php	
	if(count($assignedShipmentRecords) > 1)
	{
		$count = 0;
		foreach($assignedShipmentRecords as $_webShipment)
		{
			$count++;
			$isCurrent = $_webShipment['web_order_id'] == $web_order_id;
			if($_webShipment["web_order_id"] != $web_order_id){
	?>
		  <div class="col-lg-3 col-md-4 col-sm-6 assi_wo_block assi_wo_block_<?php echo $_webShipment["user_id"];?>" id="dashboard_order_label_<?php echo md5($_webShipment['label_id'])?>">
			<div class="card <?php echo $isCurrent ? "gradient_bg text-white" :""?>">
			  <div class="p-1" style="border-bottom: 1px solid #efefef;"><img class="img-circle" alt="<?php echo $_webShipment["user_fname"]?>" src="<?php echo getResizeImage($_webShipment["user_image"],50)?>" height="32px"> &nbsp; <?php echo $_webShipment["user_fname"].' '.$_webShipment["user_lname"]?><span class="text-info pull-right" title="<?php echo $_webShipment["label_downloads"]?> Downloads"><i class="fa fa-download"></i> <span title="<?php echo str_replace("|", "\n",$_webShipment["label_download_records"])?>" id="label_download_count_<?php echo md5($_webShipment['label_id'])?>"><?php echo $_webShipment["label_downloads"]?></span></span>
				<div class="pull-right p-1" style="top:20px; right:0px; position:absolute;"><?php echo orderDelayLevel($_webShipment["web_order_created_date"])?></div>
			  </div>
			  <div class="card-body">
				<div class="p-1 pt-0 d-flex align-items-center justify-content-between">
				  <div class="pl-0 justify-content-start"> <img class="img" alt="Store" src="<?php echo $app->imagePath($_webShipment["store_logo"])?>" height="24px"> </div>
				  <div class="justify-content-end ml-auto pl-1"> <a href="<?php echo $app->basePath('viewweborder/'.$_webShipment["web_order_id"])?>" class="redirect btn btn-outline-info pull-right"><?php echo $_webShipment["web_order_number"]?></a> </div>
				</div>
				<div class="px-1 pt-0 pb-1 d-flex align-items-left justify-content-start"> <img class="img-circle" title="<?php echo $_webShipment['uploader_fname']?>" data-toggle="popover" data-content="Assigned by <?php echo $_webShipment['uploader_fname']?> at <?php echo dateView($_webShipment["label_uploaded_date"], 'FULL')?>" alt="<?php echo $_webShipment['uploader_fname']?>" src="<?php echo getResizeImage($_webShipment["uploader_image"],50)?>" height="20px"> &nbsp; <?php echo limitText($_webShipment['uploader_fname'], 10)?> at <?php echo date("d-M h:iA", strtotime($_webShipment["label_uploaded_date"]))?> </div>
				
			  </div>
			</div>
		  </div>
                  <?php
			}			
		}
	}
  ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php }?>
</div>

<script type="text/javascript">
$(".chk_order_product_img, #web_order_assign_technician_chk, .table-product, #web_order_status, .orderaction, #web_order_is_outsourced_chk, .web_order_is_paid, .product_image_camera, .updateImageTitle, .metadataloader, .removepackinguser, .fullfillmentopener, .label_shipment_required .fetchorderinvoice").unbind();
var order_process_user = '<?php echo $web_order_process_user; ?>';
var order_current_status = '<?php echo $web_order_status; ?>';
var mediaSection = '<?=MediaSection::$webOrderLabelMedia?>';


function completeLabelHandler(e){
	var arr	=	JSON.parse(e.target.response);	
	message(arr[1],2000);
	modal.Hide();
	sendSocketMessage('uploaded label on weborder #<?php echo $web_order_number?>');
	setTimeout(function(){
		Redirect('viewweborder/'+$("#web_order_id").val());	
	}, 2000);
}


$("#web_order_assign_technician_chk").on("change", function(){
	if($(this).is(":checked")){
		$("#web_order_assign_technician_box").show();
	}else{
		$("#web_order_assign_technician_box").hide();
	}			
});
$("#web_order_commission_user_chk").on("change", function(){
	if($(this).is(":checked")){
		$("#web_order_assign_commission_user_box").show();
	}else{
		$("#web_order_assign_commission_user_box").hide();
	}			
}); 

$(document).on("click", ".fetchorderinvoice", function(e){
	e.preventDefault();
	var data={
				action		:	'weborder/fetchorderinvoice',
				web_order_id	:	$(this).attr('data-id')
			};
	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("connecting|Gettting invoice details...",0);
			dissableSubmission();
		},		
		success:function(output){ 
		sendSocketMessage('Fetched weborder invoice #<?php echo $web_order_number?>');
			enableSubmission(output);
			var arr	=	JSON.parse(output);
			if(arr[0] == 200){
				Redirect('viewweborder/'+data.web_order_id);	
			}
			message(arr[1]);
		}
	});	
});

$(".web_order_under_customer_review").on("change", function(){
	
	var data={
				action		:	'weborder/markundercustomerreview',
				order_id	:	$('#web_order_id').val(),
				mark		:	$(this).is(":checked") ? 1 : 0
			};
	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("connecting|Updating order status...",0);
			dissableSubmission();
		},		
		success:function(output){ 
		sendSocketMessage('updated weborder #<?php echo $web_order_number?>');
			enableSubmission(output);
			var arr	=	JSON.parse(output);		
			message(arr[1]);
		}
	});	
});

function updateweborder()
{
	var formFields	=	"web_order_status";
	var validation = true;
	if($("#web_order_is_picked").is(":checked")){
		if($("#web_order_picking_user").val() == 0){
			message("danger|Select order picking user",1000);
			$("#web_order_picking_user").focus();
			return false;
		}
		if($("#web_order_picking_time").val() == ''){
			message("danger|Select order picking time",1000);
			$("#web_order_picking_time").focus();
			return false;
		}
	}
	
	if($("#web_order_is_packed").is(":checked")){
		if($("#web_order_packing_user").val() == 0){
			message("danger|Select order packing user",1000);
  
						   
			$("#web_order_packing_user").focus();
			return false;
		}
		if($("#web_order_packing_time").val() == ''){
			message("danger|Select order packing time",1000);
			$("#web_order_packing_time").focus();
			return false;
		}
	}
	
	if($("#web_order_is_processed").is(":checked")){
		if($("#web_order_process_user").val() == 0){
			message("danger|Select order processing user",1000);
			$("#web_order_process_user").focus();
			return false;
		}
		if($("#web_order_process_time").val() == ''){
			message("danger|Select order processing time",1000);
			$("#web_order_process_time").focus();
			return false;
		}
	}
	
	if($("#web_order_assign_technician_chk").is(":checked")){
		if($("#web_order_assign_technician").val() == 0){
			message("danger|Select technician",1000);
			$("#web_order_assign_technician").focus();
			return false;
		}
		if($("#web_order_assign_technician_date").val() == ''){
			message("danger|Select assigned date",1000);
			$("#web_order_assign_technician_date").focus();
			return false;
		}
																												
	}
	
	if(validateFields(formFields))
	{
		var data={
					action		:	$("#action").val()				
				};
		data = $.extend(data, $("#werborderform").serializeFormJSON());		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("connecting|Connecting...",0);
				dissableSubmission();
			},		
			success:function(output){ 
			sendSocketMessage('updated weborder #<?php echo $web_order_number?>');
				enableSubmission(output);
				var arr	=	JSON.parse(output);		
				message(arr[1]);
			}
		});
	}
		
																										  
}

function webcancellabel(label_id){
	if(<?php echo $web_order_status?> == 1){
		message("danger|Order completed can't cancel label.");
		return;
	}
	var data={
					action		:	'weborder/webordercancellabel',
					label_id	:	label_id
			};	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("connecting|Connecting...",0);
			dissableSubmission();
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);
			if(arr[0] == 200){
				sendSocketMessage('cancelled label for weborder #<?php echo $web_order_number?>');
				$(".shipmentlabel_"+label_id).remove();
			}
			message(arr[1]);
		}
	});
}

function weborderaction(eventAction){
								   
	var data={
					action				:	'weborder/weborderaction',
					order_action		:	eventAction.action,
					web_order_id		: 	$('#web_order_id').val(),
					user_id 			:   $("#order_user_id").val(),
					user_reset			:	$("#user_reset").val()
									
			};			
	if(eventAction.action == 'pick'){
		if($("#web_apply_same_user_to_pack_chk").is(":checked"))
		{
			data.same_user_to_pack_order = 1;
		}
	}
	if(eventAction.action == 'pack'){
		if($("#web_order_is_outsourced_chk").is(":checked"))
		{		   
										  
			if($("#web_order_is_outsourced").val() == 0){
				message("danger|Please select outsource unit");
				return false;																				   
			}
			data.web_order_is_outsourced = $("#web_order_is_outsourced").val();
		}
	}
	
	
	if(data.user_id != 0)
	{
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("connecting|Connecting...",0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0] == 200){
					sendSocketMessage(eventAction.action + ' weborder #<?php echo $web_order_number?>');
					setTimeout(function(){
					Redirect('viewweborder/'+data.web_order_id);	
					}, 2000);
					modal.Hide();
				}
				message(arr[1]);
			}
		});
	}
	else
		message('warning|Please select attendent user');
}

$(document).ready(function() {
	
	$("#web_order_status").on("change", function(){
		if(order_process_user == 0 && $(this).val() == 1){
			message('danger|Order is not Processed yet. You can\'t mark order to Complete.');
			$(this).val(order_current_status);
		}
	});
	
	$(".orderaction").not(":last").not(".orderupdateaction").not(":first").each(function(e){
		$(this).attr('disabled', true).attr('title', 'Please complete previous steps');			
	});
	
	$("#web_order_is_outsourced_chk").off();
	$(document).on("change", "#web_order_is_outsourced_chk", function(e){
		if($(this).is(":checked"))
			$(".web_order_is_outsourced_box").show();
		else
			$(".web_order_is_outsourced_box").hide();
	});
	
	<?php $employee = new Employee();?>
	var userOptions = '<?php echo $employee->getUserOption();?>';
	var userOutSourceUnit = '<?php echo $employee->getUserOption(15);?>';
    
	$('.orderaction').on("click", function(){
		var eventAction = $(this).attr('data-action');
		var user_reset  = typeof($(this).attr('data-reset')) != 'undefined' ? $(this).attr('data-reset'):0;
		var id = $("#web_order_id").val();
		setPopup(id, "Assign Order's "+eventAction+"ing user");
		var bodyHtml = '';
		bodyHtml += '<div class="col-xs-12"><div class="form-group"><label for="order_user_id">Select '+eventAction+'ing user<sup>*</sup></label> <select id="order_user_id" name="order_user_id" class="form-control" size="1">'+userOptions+'</select></div></div>';
		if(eventAction == 'pick'){
		bodyHtml += '<div class="col-xs-12"><div class="form-group"><label for="web_apply_same_user_to_pack_chk"><i class="icon-user fa-lg m-t-2"></i> &nbsp;Apply Same User for Pack Order ?</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="web_apply_same_user_to_pack_chk" value="1" name="web_apply_same_user_to_pack_chk" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div>';
		}
		if(eventAction == 'pack'){
		bodyHtml += '<div class="col-xs-12"><div class="form-group"><label for="web_order_is_outsourced_chk"><i class="icon-bag fa-lg m-t-2"></i> &nbsp;is Fullfil from OutSource ?</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="web_order_is_outsourced_chk" value="1" name="web_order_is_outsourced_chk" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div> <div class="col-xs-12 web_order_is_outsourced_box"><div class="form-group"><label for="web_order_is_outsourced">Select Outsource unit<sup>*</sup></label> <select id="web_order_is_outsourced" name="web_order_is_outsourced" class="form-control" size="1">'+userOutSourceUnit+'</select></div></div>';
		}
		modal.Body(bodyHtml);
		modal.Footer('<button type="button" onclick="confirmMessage.Set(\'Are you sure to mark '+eventAction+' webOrder ...?\', \'weborderaction\', {action :\''+eventAction+'\'});" class="btn btn-outline-success mt-0">Save</button><button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button><input type="hidden" id="user_reset" name="user_reset" value="'+user_reset+'">');
		modal.Show();
			
		if(eventAction == 'pack')			
		$(".web_order_is_outsourced_box").hide();
	});
	
	$(".product_image_camera").on("click", function(e){
		e.preventDefault();
		openMediaUploader($(this).attr('data-media-section'), $(this).attr('data-id'), "collection_record_image", "Seelct product's image source", null);

	});
	$(".web_order_commission_user_file").on("click", function(){
	openMediaUploader($(this).attr('data-media-section'), $(this).attr('data-id'), "web_order_commission_user_file", "Seelct Commission proof file", null);

	});
	
	$(".web_order_is_paid").on("change", function(){
		if(confirm('Are you sure to update payment status.. ?')){
			var data={
			action		:	'weborder/updatewopaymentstatus',
			web_order_id	: 	$("#web_order_id").val(),
			web_order_is_paid : $(".web_order_is_paid:checked").val()
			};
			$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
					beforeSend: function(){
					message("process|Updating order payment status..", 0);
					dissableSubmission();
				},		
				success:function(output){ 
					enableSubmission(output);
					var arr	=	JSON.parse(output);	
					if(arr[0]==200)
					{
						var html_success = '<div class="alert alert-success text-center" role="alert">This order have successfull paid payment of amount <b><?=isset($web_order_total_amount)? $web_order_total_amount:"#######";?></b> <?=$web_order_currency;?> </div>';
						var html_failed = '<div class="alert alert-danger text-center" role="alert"> This order have not completed payment of amount <b><?=isset($web_order_total_amount)?$web_order_total_amount:"#######";?></b> <?=$web_order_currency;?> </div>';
						$("#weborderstatusblock").html(arr[2] == 'Yes' ? html_success : html_failed);
					}
					message(arr[1],2000);															   
				}
			});
		}else{
			$(this).prop('checked', $(this).is("checked"));											
		}
	});
	
	$(".undoaction").on("click", function(){
		confirmMessage.Set('Are you sure to '+$(this).attr('title')+' ...?', 'undoAction', {action : $(this).attr('data-action')});
	})
});



	
function undoAction(step){
	var data={
				action			:	'weborder/undoaction',
				web_order_id	: 	$("#web_order_id").val(),
				step			: 	step.action
			};
			$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
					beforeSend: function(){
					message("process|Removing assigned packing user...", 0);
					dissableSubmission();
				},		
				success:function(output){ 
					enableSubmission(output);
					var arr	=	JSON.parse(output);	
					if(arr[0]==200)
					{
						setTimeout(function(){
							Redirect('viewweborder/'+data.web_order_id);	
						}, 2000);
					}
					message(arr[1],2000);															   
				}
			});
}
/*function uploadMediaFile(param)
{
	processUploadMediaFile(param.name, '_'+$("#keyid").val(), '<?=MediaSection::$webOrderProductMedia?>');
}*/
function uploadMediaFile(param)
{
	console.log(param);
	processUploadMediaFile(param.name, '_'+$("#keyid").val(), param.mediasection);
}

function completeMediaUpload(event)
{
	var rc	=	JSON.parse(event.target.responseText);
	message(rc[1],2000);
	if(rc['image_id_array'].length > 0)
	{
		for(var i=0; i < rc['image_id_array'].length; i++){
			$("#media_uploaded_image_box_"+rc['id']).append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['image_id_array'][i]+'" id="item-web-order-product-media-container-'+rc['image_id_array'][i]+'"><div class="card"><div class="card-header card-success">Image<div class="card-actions"><a class="btn-close" href="javascript:removeWebOrderProductCollectionMedia(\''+rc['image_id_array'][i]+'\', \''+rc['id']+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['image_path_array'][i]+'" /></div></div></div>');
		}																				 
	}	
	message('success|File uploaded', 1000);
	$("#item-media-processing").remove();
	$("#item-web-order-product-media-processing").remove();	
	$("#media_uploaded_image_box_"+rc['id']+" .empty_image_box").remove();
}


function removeWebOrderProductCollectionMedia(id, wo_id)
{
	if(confirm("Are you sure to remove this media file... ?"))
	{
		var data={
			action		:	'weborder/removewebordermedia',
			record_id	: id			
		};
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Removing web order media file...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{					
					$("#item-web-order-product-media-container-"+id).remove();
					if($("#media_uploaded_image_box_"+wo_id+" div").length ==0)
					$("#media_uploaded_image_box_"+wo_id).html(EMPTY_IMAGE_BOX);
				}
				message(arr[1],2000);
			}
		});
   												
	}
}
</script> 
<script language="JavaScript" type="text/javascript">
function take_snapshot() {
	if(!cametaStatus){
		message("danger|Please start camera first...", 2000);
		return false;									 
	}
	
	// take snapshot and get image data
	Webcam.snap( function(data_uri) {
		// display results in page
		var count = parseInt($(".snapshot_image_count_row").length)+1;
		var parameter={
			wpoi_wo_id	:	$("#keyid").val()
		};
		if(confirm("Are you sure to upload this media file... ?"))
		{
			$("#media_uploaded_image_box_"+$("#keyid").val()).append('<div class="col-xs-12 col-lg-3"  id="item-web-order-product-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading Snapshot<br/><br/>Please wait</center></div>');
			Webcam.upload( data_uri, parameter, sitePath+'saveimage.php', function(code, text) {
				var rc	=	JSON.parse(text);	
				$("#media_uploaded_image_box_"+rc['wpoi_wo_id']).append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['record_id']+'" id="item-web-order-product-media-container-'+rc['record_id']+'"><div class="card"><div class="card-header card-success">Cam Image<div class="card-actions"><a class="btn-close" href="javascript:removeWebOrderProductCollectionMedia(\''+rc['record_id']+'\', \''+rc['wpoi_wo_id']+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['wpoi_image_path']+'" /></div></div></div>');
				$("#item-web-order-product-media-processing").remove();
			} );
		}
	});	
}

function sendweborderimagetocustomer(product_id){
	if($(".chk_order_product_img:checked").length == 0){
		message("warning|Please select image to send ..", 2000);
		$(".order_product_all:first").focus();
		return;
	}
	
	var data={
		'action'			:	'weborder/sendweborderimagetocustomer',
		'web_order_id'		: 	$("#web_order_id").val()
	};
	data = $.extend(data, $("#werborderform .chk_order_product_img:checked").serializeFormJSON());	
	
	$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
			beforeSend: function(){
			message("process|Preparing to send product image to customer..", 0);
			dissableSubmission();
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
			}
			message(arr[1],2000);															   
		}
	});
}
appendPrevNext(<?php echo $weborder->getPrevNext($app->basePath('viewweborder'));?>);
$(document).ready(function(e) {	
	showMeIntro('intro-weborder');	
	loadGallery(true, 'a.thumbnail');
	$(".media_uploaded_image_box").each(function(){
		if($(this).children().length == 0)
			$(this).html(EMPTY_IMAGE_BOX);
	});
	
	$(".controll_switch").on("change", function(){
		$(this).is(":checked") ? $("#"+$(this).attr('data-controll')).show() :  $("#"+$(this).attr('data-controll')).hide();
	});
	
	$(".controll_switch").each(function(){
		$(this).is(":checked") ? $("#"+$(this).attr('data-controll')).show() :  $("#"+$(this).attr('data-controll')).hide();
	});
	$(".order_product_all").on("change", function(){		
		$(".order_product_"+$(this).val()).prop("checked", $(this).is(":checked"));
	});
	$(document).on("click", ".updateImageTitle", function(){
		var image_id = $(this).attr('data-id');
		var image_title = $("#txt_image_title_"+image_id).val().trim();
		if(image_title != ''){
			var data={
			action		:	'weborder/updateweborderimagemetadetails',
			image_id	: 	image_id,
			image_title : 	image_title
			};
			$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
					beforeSend: function(){
					message("process|Updating image title..", 0);
					dissableSubmission();
				},		
				success:function(output){ 
					enableSubmission(output);
					var arr	=	JSON.parse(output);	
					if(arr[0]==200)
					{
						$("#product_image_title_"+image_id).text(image_title);
					}
					message(arr[1],2000);															   
				}
			});
		}
	});	
});


</script> 