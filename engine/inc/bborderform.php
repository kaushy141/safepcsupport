<div class="row" id="buybackorder_form_container">
    <?php
	$orderBasicDetails = "
  <div class=\"col-sm-12 pt-1\">
    <dl class=\"row\">
	<div class=\"col-sm-12\">
		<div class=\"card\">
                  <div class=\"card-block\">

		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Order :</dt>
			<dd class=\"col-xs-8\">#$order_number</dd>
		</div>
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Store :</dt>
			<dd class=\"col-xs-8\"><img alt=\"store\" style=\"width: 80px;\" src=\"" . $app->imagePath($store_logo) . "\" /></dd>
		</div>
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Amount :</dt>
			<dd class=\"col-xs-8\"> $buy_currency $buy_price</dd>
		</div>
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Status :</dt>
			<dd class=\"col-xs-8\">$status</dd>
		</div>
  </div></div></div></div>";

	?>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <form id="bborderform" name="bborderform">

            <div class="row">
                <?php echo $orderBasicDetails; ?>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-comments-o"></i> Comments </div>
                        <div class="card-block">
                            <?php
							$complaintLog = new ComplaintLog();
							if ($commentsLog = $complaintLog->getLog($id, 'K')) {
								foreach ($commentsLog as $_comments)
									echo ComplaintLog::drawLogRecord($_comments);
							} else {
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
									$timelineArry = Activity::getModuleActivityTimeline('K', $id);
									if ($timelineArry) {
										foreach ($timelineArry as $timeline) {
									?>
                                    <tr>
                                        <td><?php echo $timeline["user_image"]; ?></td>
                                        <td><?php echo $timeline["user_name"]; ?></td>
                                        <td><?php echo $timeline["message"]; ?></td>
                                        <td><?php echo $timeline["time"]; ?></td>
                                    </tr>
                                    <?php
										}
									} else {
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
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="card" data-step="8" data-title="Customer Information"
                        data-intro="Order placing Customer full information.">
                        <div class="card-header"><i class="fa fa-user"></i> Customer information
                            <div class="card-actions"> <a class="redirect"
                                    href="<?php echo $app->basePath("updatecustomer/$web_order_customer_id") ?>"> <small
                                        class="text-muted">View</small> </a> </div>
                        </div>
                        <div class="card-block">
                            <div class="bd-example">
                                <dl class="row">
                                    <dt class="col-xs-4">Name :</dt>
                                    <dd class="col-xs-8">
                                        <?= isset($customer_fname) ? $customer_fname : ""; ?>
                                        <?= isset($customer_lname) ? $customer_lname : ""; ?>
                                    </dd>
                                    <dt class="col-xs-4">Email : </dt>
                                    <dd class="col-xs-8">
                                        <?= isset($customer_email) ? $customer_email : ""; ?>
                                    </dd>
                                    <dt class="col-xs-4">Phone :</dt>
                                    <dd class="col-xs-8">
                                        <?= isset($customer_phone) ? $customer_phone : ""; ?>
                                    </dd>
                                    <dt class="col-xs-4">Type :</dt>
                                    <dd class="col-xs-8">
                                        <?= isset($customer_type_name) ? $customer_type_name : ""; ?>
                                    </dd>
                                    <?php if (isset($customer_company) && trim($customer_company) != "") : ?>
                                    <dt class="col-xs-4">Company :</dt>
                                    <dd class="col-xs-8">
                                        <?= isset($customer_company) ? $customer_company : ""; ?>
                                    </dd>
                                    <?php endif; ?>
                                    <dt class="col-xs-4">Status :</dt>
                                    <dd class="col-xs-8">
                                        <?= isset($customer_status) ? "<i class='fa fa-circle text-success'></i> <span class='text-success'>Active</span>" : "<i class='fa fa-circle text-danger'></i> <span class='text-danger'>Inactive</span>"; ?>
                                    </dd>
                                    <dt class="col-xs-4">Since :</dt>
                                    <dd class="col-xs-8">
                                        <?= isset($customer_created_date) ? date("l, jS M-y h:iA", strtotime($customer_created_date)) : "" ?>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="card" data-step="9" data-title="Shipping Information" data-title="Information"
                        data-intro="Order Shipping address as per given on order">
                        <div class="card-header"><i class="fa fa-align-justify"></i>Pickup Address</div>
                        <div class="card-block">
                            <div class="bd-example">
                                <dl class="row" style="margin-bottom:0px">
                                    <dt class="col-xs-4">Address:</dt>
                                    <dd class="col-xs-8">
                                        <?= isset($customer_address) ? $customer_address : ""; ?>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header"><i class="fa fa-windows"></i>Store information
                            <div class="card-actions"> <img data-step="10" data-title="Store details"
                                    data-intro="Store/Website from where customer placed this order. you can get full order detail on this store."
                                    alt="store"
                                    style="margin-left: -50px; margin-top: 7px; width: 80px; margin-right: 8px;"
                                    src="<?php echo $app->imagePath($store_logo) ?>" /> </div>
                        </div>
                        <div class="card-block">
                            <div class="bd-example">
                                <dl class="row" style="margin-bottom:0px">
                                    <dt class="col-xs-4">Store :</dt>
                                    <dd class="col-xs-8">
                                        <?= isset($store_name) ? $store_name : ""; ?>
                                    </dd>
                                    <dt class="col-xs-4">Dated :</dt>
                                    <dd class="col-xs-8">
<<<<<<< HEAD
                                        <?= isset($created_at) ? date("l, jS M-y h:iA", strtotime($web_order_created_date)) : "" ?>
=======
                                        <?= isset($created_at) ? date("l, jS M-y h:iA", strtotime($created_at)) : "" ?>
>>>>>>> 77a717f (Version 2)
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="card" data-step="11" data-title="Pricing details"
                        data-intro="Order Payment & Shipping amount information">
                        <div class="card-header"> <i class="fa fa-align-justify"></i> Order Price & Shipping </div>
                        <div class="card-body">
                            <ul class="list-group mb-0">
                                <li
                                    class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                    Total Price - <span class="badge badge-primary badge-pill">
                                        <?= $buy_currency; ?>
                                        <?= $buy_price * $product_quantity; ?>
                                    </span> </li>
                                <li
                                    class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                    Packging Charges -<span class="badge badge-default badge-pill">
                                        GBP
                                        <?= $product_packging; ?>
                                    </span> </li>
                                <li
                                    class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                    Net Amount to pay - <span class="badge badge-primary badge-pill">
                                        <?= $base_currency; ?>
                                        <?= ($base_price * $product_quantity) - $product_packging; ?>
                                    </span> </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="card" data-step="12" data-title="Payment gateway details"
                        data-intro="Payment information with payment gateway used by customer at checkout time">
                        <div class="card-header"> <i class="fa fa-credit-card-alt"></i> Order Payments </div>
                        <div class="card-body">
                            <ul class="list-group mb-0">
                                <li
                                    class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                    Payout method -<span>
                                        <?= $payout_method; ?>
                                    </span> </li>
                                <li
                                    class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                    Buyback Order's number -<span class="badge badge-info badge-pill">
                                        <?= isset($order_number) ? $order_number : "#######"; ?>
                                    </span> </li>
                                <li
                                    class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                    Buyback Items -<span class="badge badge-warning badge-pill">
                                        <?= isset($product_quantity) ? $product_quantity : "#######"; ?>
                                    </span> </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-header card-default" data-step="14" data-title="Products in Order"
                            data-intro="Ordered Products details"><i class="fa fa-cart-arrow-down"></i>Order Product
                            list</div>
                        <div class="card-block">
                            <?php
							$productList = array(
								array(
									'product_sku' => $product_sku,
									'product_url' => $product_url,
									'product_image' => $product_image,
									'product_name' => $product_name,
									'product_quantity' => $product_quantity,
									'buy_price' => $buy_price,
									'buy_currency' => $buy_currency,
									'payout_detail' => $payout_detail
								)
							);
							$productCounter = 0;
							if (count($productList)) {
								$productCounter = 0;
								foreach ($productList as $product) {
									$productCounter++;
							?>
                            <div class="card mb-2 mt-0">
                                <div class="card-header"> <i class="icon-tag"></i> <?php echo $productCounter; ?>
                                    Product</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="sortingtable<?php echo $productCounter; ?>"
                                                class="table-product table">
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
                                                        <td><?php if ($product['product_url'] != "") { ?>
                                                            <a target="new"
                                                                href="<?php echo $product['product_url']; ?>"
                                                                style="display: inline-block; vertical-align: middle;"><img
                                                                    alt="Image" class="img-responsive"
                                                                    style="height:60px;"
                                                                    src="<?php echo $app->imagePath($product['product_image']) ?>" /></a>
                                                            <?php } ?>
                                                            <span
                                                                class="hidden-xs visible-md visible-lg"><?php echo $product['product_name'] ?></span>
                                                        </td>
                                                        <td class="text-center"><a target="new"
                                                                href="<?php echo $product['product_url'] ?>"><?php echo $product['product_sku'] ?></a>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo $product['product_quantity'] ?></td>
                                                        <td class="text-center"><?= $web_order_currency; ?>
                                                            <?php echo round($product['buy_price'], 2) ?>
                                                            <?php echo $product['buy_currency'] ?></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 1px;">
                                            <div class="card mb-0">
                                                <div class="card-header card-default"><i class="fa fa-credit-card"></i>
                                                    Payout details</div>
                                                <div class="card-block"><?= $product['payout_detail'] ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 1px;">
                                            <div class="card mb-0">
                                                <div class="card-header card-default"><i class="fa fa-image"></i>
                                                    Product Media Files</div>
                                                <div class="card-block">
                                                    <div class="row media_uploaded_image_box"
                                                        id="media_uploaded_image_box_<?php echo $product['wo_id'] ?>">
                                                        <?php
																$bopm = new BuybackOrderProductMedia(0);
																$recorded_snapshot_list = $bopm->getImageList($id);
																if ($recorded_snapshot_list) :
																	$iCounter = 0;
																	foreach ($recorded_snapshot_list as $itemCapt) :
																		$iCounter++;
																?>
                                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 thumb"
                                                            data-record-id="<?php echo $itemCapt['bpoi_id']; ?>"
                                                            id="item-web-order-product-media-container-<?php echo $itemCapt['bpoi_id']; ?>">
                                                            <div class="card">
                                                                <div class="card-header card-default">
                                                                    <label>
                                                                        <input type="checkbox"
                                                                            class="chk_order_product_img order_product_<?php echo $product['wo_id']; ?>"
                                                                            value="<?php echo $itemCapt['bpoi_id']; ?>"
                                                                            name="order_product_images[<?php echo $product['wo_id']; ?>][]">
                                                                        <?php echo $iCounter; ?>.
                                                                        <?php echo $itemCapt['bpoi_sent_to_customer'] ? "<i title='Sent to Customer' class='fa text-success fa-check-circle'></i>" : "" ?>
                                                                        <span
                                                                            id="product_image_title_<?php echo $itemCapt['bpoi_id']; ?>"><?php echo ($itemCapt['bpoi_title'] != '') ? $itemCapt['bpoi_title'] : "Image"; ?>
                                                                        </span></label>
                                                                    <div class="card-actions"><a class="btn-close"
                                                                            href="javascript:removeWebOrderProductCollectionMedia('<?php echo $itemCapt['bpoi_id']; ?>', '<?php echo $product['wo_id']; ?>');"><i
                                                                                class="icon-close"></i></a></div>
                                                                </div>
                                                                <div class="card-block p-a-1 clearfix"> <a
                                                                        class="thumbnail" href="#" data-image-id=""
                                                                        data-toggle="modal"
                                                                        data-title="Image <?php echo $iCounter; ?>"
                                                                        data-image="<?php echo $app->basePath($itemCapt['bpoi_image_path']); ?>"
                                                                        data-target="#image-gallery"> <img
                                                                            class="img-thumbnail"
                                                                            src="<?php echo getResizeImage($itemCapt['bpoi_image_path'], 100); ?>"
                                                                            alt="Image <?php echo $iCounter; ?>"> </a>
                                                                    <div class="font-weight-normal  text-center">
                                                                        <?php echo $itemCapt['bpoi_remark']; ?></div>
                                                                </div>
                                                                <div class="p-1 text-center"><i
                                                                        class="fa fa-clock-o"></i>
                                                                    <?php echo dateView($itemCapt['bpoi_created_date']) ?>
                                                                </div>
                                                                <div class="card-block p-a-1 py-0 clearfix form-group">
                                                                    <div class="input-group" data-placement="left"
                                                                        data-align="top" data-autoclose="true">
                                                                        <input type="text" class="form-control"
                                                                            id="txt_image_title_<?php echo $itemCapt['bpoi_id']; ?>"
                                                                            name="wc_loading_time" maxlength="50"
                                                                            value=""
                                                                            placeholder="Add/Update Image Title">
                                                                        <span
                                                                            class="input-group-addon bg-success updateImageTitle"
                                                                            data-id="<?php echo $itemCapt['bpoi_id']; ?>">
                                                                            <span class="fa fa-check fa-lg"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
																	endforeach;
																endif;
																?>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <a data-id="<?php echo $id ?>"
                                                                data-media-section="<?= MediaSection::$buybackProductMedia ?>"
                                                                class="btn btn-info product_image_camera my-2" href="#"
                                                                title="Take image for <?php echo $product_name ?>"><i
                                                                    class="fa fa-camera"></i> &nbsp;Upload Media</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="px-1 py-2">
                                                <a class="btn btn-info redirect"
                                                    href="<?php echo $app->basePath("addcomplaintrequest/K/$id/$id") ?>"><i
                                                        class="fa-w icon-wrench"></i> Create RMA Request</a>
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
<<<<<<< HEAD
    </div>

    <div class="mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="#" data-step="15" data-title="Add Comments"
                                    data-intro="Add comments on order here" data-toggle="modal" data-target="#appModal"
                                    class="btn btn-primary"
                                    onClick="openChatLogForm('<?php echo $id; ?>|K', '<img class=\'img-avatar\' src=\'<?php echo $app->imagePath($store_icon) ?>\' style=\'height:24px; width:24px;\'> #<?php echo $order_number ?> Log Report')">Comments</a>
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action<span
                                            class="sr-only"></span></button>
                                    <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item redirect"
                                            href="<?php echo $app->basePath("viewbuyackorder/$id"); ?>"><i
                                                class="fa fa-refresh"></i> Reload</a>
                                        <?php if (isAdmin()) { ?>
                                        <a class="dropdown-item"
                                            href="javascript:newWindow('<?php echo DOC::BBINVOICE($id) ?>');"><i
                                                class="fa fa-file-pdf-o fa-fw text-danger"></i> Download Invoice</a>
                                        <?php
										} ?>
=======

            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="#" data-step="15" data-title="Add Comments"
                                        data-intro="Add comments on order here" data-toggle="modal"
                                        data-target="#appModal" class="btn btn-primary"
                                        onClick="openChatLogForm('<?php echo $id; ?>|K', '<img class=\'img-avatar\' src=\'<?php echo $app->imagePath($store_icon) ?>\' style=\'height:24px; width:24px;\'> #<?php echo $order_number ?> Log Report')">Comments</a>
                                    <div class="btn-group dropup">
                                        <button type="button"
                                            class="btn btn-default dropdown-toggle dropdown-toggle-split"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Action<span class="sr-only"></span></button>
                                        <div class="dropdown-menu dropdown-menu-right"> <a
                                                class="dropdown-item redirect"
                                                href="<?php echo $app->basePath("viewbuyackorder/$id"); ?>"><i
                                                    class="fa fa-refresh"></i> Reload</a>
                                            <?php if (isAdmin()) { ?>
                                            <a class="dropdown-item"
                                                href="javascript:newWindow('<?php echo DOC::BBINVOICE($id) ?>');"><i
                                                    class="fa fa-file-pdf-o fa-fw text-danger"></i> Download Invoice</a>
                                            <?php
											} ?>
                                        </div>
>>>>>>> 77a717f (Version 2)
                                    </div>
                                </div>
                            </div>
                        </div>
<<<<<<< HEAD
                    </div>
                    <input type="hidden" id="action" name="action"
                        value="<?= isset($action) ? $action : "updatebborder"; ?>" />
                    <input type="hidden" id="formcode" name="formcode" value="<?= App::getFormcode(); ?>" />
                    <input type="hidden" id="id" name="id" value="<?= isset($id) ? $id : "0"; ?>" />
                </div>
            </div>
        </div>
    </div>
    </form>
=======
                        <input type="hidden" id="action" name="action"
                            value="<?= isset($action) ? $action : "updatebborder"; ?>" />
                        <input type="hidden" id="formcode" name="formcode" value="<?= App::getFormcode(); ?>" />
                        <input type="hidden" id="id" name="id" value="<?= isset($id) ? $id : "0"; ?>" />
                    </div>
                </div>
            </div>
        </form>
    </div>
>>>>>>> 77a717f (Version 2)
</div>

<script type="text/javascript">
$(".chk_order_product_img, #web_order_assign_technician_chk, .table-product, #web_order_status, .orderaction, #web_order_is_outsourced_chk, .web_order_is_paid, .product_image_camera, .updateImageTitle, .metadataloader, .removepackinguser, .fullfillmentopener, .label_shipment_required .fetchorderinvoice")
    .unbind();
var order_process_user = '<?php echo $web_order_process_user; ?>';
var order_current_status = '<?php echo $web_order_status; ?>';
var mediaSection = '<?= MediaSection::$webOrderLabelMedia ?>';


function completeLabelHandler(e) {
    var arr = JSON.parse(e.target.response);
    message(arr[1], 2000);
    modal.Hide();
    sendSocketMessage('uploaded label on weborder #<?php echo $web_order_number ?>');
    setTimeout(function() {
        Redirect('viewweborder/' + $("#web_order_id").val());
    }, 2000);
}

function updateweborder() {
    var formFields = "web_order_status";
    var validation = true;
    if ($("#web_order_is_picked").is(":checked")) {
        if ($("#web_order_picking_user").val() == 0) {
            message("danger|Select order picking user", 1000);
            $("#web_order_picking_user").focus();
            return false;
        }
        if ($("#web_order_picking_time").val() == '') {
            message("danger|Select order picking time", 1000);
            $("#web_order_picking_time").focus();
            return false;
        }
    }

    if ($("#web_order_is_packed").is(":checked")) {
        if ($("#web_order_packing_user").val() == 0) {
            message("danger|Select order packing user", 1000);


            $("#web_order_packing_user").focus();
            return false;
        }
        if ($("#web_order_packing_time").val() == '') {
            message("danger|Select order packing time", 1000);
            $("#web_order_packing_time").focus();
            return false;
        }
    }

    if ($("#web_order_is_processed").is(":checked")) {
        if ($("#web_order_process_user").val() == 0) {
            message("danger|Select order processing user", 1000);
            $("#web_order_process_user").focus();
            return false;
        }
        if ($("#web_order_process_time").val() == '') {
            message("danger|Select order processing time", 1000);
            $("#web_order_process_time").focus();
            return false;
        }
    }

    if ($("#web_order_assign_technician_chk").is(":checked")) {
        if ($("#web_order_assign_technician").val() == 0) {
            message("danger|Select technician", 1000);
            $("#web_order_assign_technician").focus();
            return false;
        }
        if ($("#web_order_assign_technician_date").val() == '') {
            message("danger|Select assigned date", 1000);
            $("#web_order_assign_technician_date").focus();
            return false;
        }

    }

    if (validateFields(formFields)) {
        var data = {
            action: $("#action").val()
        };
        data = $.extend(data, $("#werborderform").serializeFormJSON());
        $.ajax({
            type: 'POST',
            data: data,
            url: sitePath + 'ajax.php',
            beforeSend: function() {
                message("connecting|Connecting...", 0);
                dissableSubmission();
            },
            success: function(output) {
                sendSocketMessage('updated weborder #<?php echo $web_order_number ?>');
                enableSubmission(output);
                var arr = JSON.parse(output);
                message(arr[1]);
            }
        });
    }


}

$(document).ready(function() {

    $("#web_order_status").on("change", function() {
        if (order_process_user == 0 && $(this).val() == 1) {
            message('danger|Order is not Processed yet. You can\'t mark order to Complete.');
            $(this).val(order_current_status);
        }
    });

    $(".orderaction").not(":last").not(".orderupdateaction").not(":first").each(function(e) {
        $(this).attr('disabled', true).attr('title', 'Please complete previous steps');
    });

    $("#web_order_is_outsourced_chk").off();
    $(document).on("change", "#web_order_is_outsourced_chk", function(e) {
        if ($(this).is(":checked"))
            $(".web_order_is_outsourced_box").show();
        else
            $(".web_order_is_outsourced_box").hide();
    });


    $(".product_image_camera").on("click", function(e) {
        e.preventDefault();
        openMediaUploader($(this).attr('data-media-section'), $(this).attr('data-id'),
            "collection_record_image", "Seelct product's image source", null);

    });
    $(".web_order_commission_user_file").on("click", function() {
        openMediaUploader($(this).attr('data-media-section'), $(this).attr('data-id'),
            "web_order_commission_user_file", "Seelct Commission proof file", null);

    });

});

/*function uploadMediaFile(param)
{
	processUploadMediaFile(param.name, '_'+$("#keyid").val(), '<?= MediaSection::$webOrderProductMedia ?>');
}*/
function uploadMediaFile(param) {
    console.log(param);
    processUploadMediaFile(param.name, '_' + $("#keyid").val(), param.mediasection);
}

function completeMediaUpload(event) {
    var rc = JSON.parse(event.target.responseText);
    message(rc[1], 2000);
    if (rc['image_id_array'].length > 0) {
        for (var i = 0; i < rc['image_id_array'].length; i++) {
            $("#media_uploaded_image_box_" + rc['id']).append('<div class="col-xs-12 col-lg-3" data-record-id="' + rc[
                    'image_id_array'][i] + '" id="item-web-order-product-media-container-' + rc['image_id_array'][
                    i
                ] +
                '"><div class="card"><div class="card-header card-success">Image<div class="card-actions"><a class="btn-close" href="javascript:removeWebOrderProductCollectionMedia(\'' +
                rc['image_id_array'][i] + '\', \'' + rc['id'] +
                '\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="' +
                rc['image_path_array'][i] + '" /></div></div></div>');
        }
    }
    message('success|File uploaded', 1000);
    $("#item-media-processing").remove();
    $("#item-web-order-product-media-processing").remove();
    $("#media_uploaded_image_box_" + rc['id'] + " .empty_image_box").remove();
}


function removeWebOrderProductCollectionMedia(id, wo_id) {
    if (confirm("Are you sure to remove this media file... ?")) {
        var data = {
            action: 'weborder/removewebordermedia',
            record_id: id
        };
        $.ajax({
            type: 'POST',
            data: data,
            url: sitePath + 'ajax.php',
            beforeSend: function() {
                message("process|Removing web order media file...", 0);
                dissableSubmission();
            },
            success: function(output) {
                enableSubmission(output);
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    $("#item-web-order-product-media-container-" + id).remove();
                    if ($("#media_uploaded_image_box_" + wo_id + " div").length == 0)
                        $("#media_uploaded_image_box_" + wo_id).html(EMPTY_IMAGE_BOX);
                }
                message(arr[1], 2000);
            }
        });

    }
}
</script>
<script language="JavaScript" type="text/javascript">
function take_snapshot() {
    if (!cametaStatus) {
        message("danger|Please start camera first...", 2000);
        return false;
    }

    // take snapshot and get image data
    Webcam.snap(function(data_uri) {
        // display results in page
        var count = parseInt($(".snapshot_image_count_row").length) + 1;
        var parameter = {
            bpoi_wo_id: $("#keyid").val()
        };
        if (confirm("Are you sure to upload this media file... ?")) {
            $("#media_uploaded_image_box_" + $("#keyid").val()).append(
                '<div class="col-xs-12 col-lg-3"  id="item-web-order-product-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading Snapshot<br/><br/>Please wait</center></div>'
            );
            Webcam.upload(data_uri, parameter, sitePath + 'saveimage.php', function(code, text) {
                var rc = JSON.parse(text);
                $("#media_uploaded_image_box_" + rc['bpoi_wo_id']).append(
                    '<div class="col-xs-12 col-lg-3" data-record-id="' + rc['record_id'] +
                    '" id="item-web-order-product-media-container-' + rc['record_id'] +
                    '"><div class="card"><div class="card-header card-success">Cam Image<div class="card-actions"><a class="btn-close" href="javascript:removeWebOrderProductCollectionMedia(\'' +
                    rc['record_id'] + '\', \'' + rc['bpoi_wo_id'] +
                    '\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="' +
                    rc['bpoi_image_path'] + '" /></div></div></div>');
                $("#item-web-order-product-media-processing").remove();
            });
        }
    });
}


$(document).ready(function(e) {
    showMeIntro('intro-buybackorder');
    loadGallery(true, 'a.thumbnail');
    $(".media_uploaded_image_box").each(function() {
        if ($(this).children().length == 0)
            $(this).html(EMPTY_IMAGE_BOX);
    });

    $(".controll_switch").on("change", function() {
        $(this).is(":checked") ? $("#" + $(this).attr('data-controll')).show() : $("#" + $(this).attr(
            'data-controll')).hide();
    });

    $(".controll_switch").each(function() {
        $(this).is(":checked") ? $("#" + $(this).attr('data-controll')).show() : $("#" + $(this).attr(
            'data-controll')).hide();
    });
    $(".order_product_all").on("change", function() {
        $(".order_product_" + $(this).val()).prop("checked", $(this).is(":checked"));
    });
    $(document).on("click", ".updateImageTitle", function() {
        var image_id = $(this).attr('data-id');
        var image_title = $("#txt_image_title_" + image_id).val().trim();
        if (image_title != '') {
            var data = {
                action: 'buyback/updateimagemetadetails',
                image_id: image_id,
                image_title: image_title
            };
            $.ajax({
                type: 'POST',
                data: data,
                url: sitePath + 'ajax.php',
                beforeSend: function() {
                    message("process|Updating image title..", 0);
                    dissableSubmission();
                },
                success: function(output) {
                    enableSubmission(output);
                    var arr = JSON.parse(output);
                    if (arr[0] == 200) {
                        $("#product_image_title_" + image_id).text(image_title);
                    }
                    message(arr[1], 2000);
                }
            });
        }
    });
});
</script>