<?php 
$isInvoiceGenerated = false;
$readonlyField = "";
$isSalesInvoiceFinished = false;
if($sales_invoice_id >0 && $sales_invoice_is_generated == 1){
	$isInvoiceGenerated = true;
	$readonlyField = "readonly";
	if(!isAdminRole() && ($sales_invoice_status == 1 || $sales_invoice_status == 3))
	$isSalesInvoiceFinished = true;
}?>

<div class="row">
<?php 
$invoiceBasicDetails = "";
if(isset($sales_invoice_id) && $sales_invoice_id >0)
{
  Modal::load(array('Refund'));
  $refund = new Refund();
  $isRefundExist = $refund->isRefundExist('S', $sales_invoice_id, true);
  if($isRefundExist == 1){?>
  <div class="col-xs-12 pt-1">
    <div class="alert alert-danger" role="alert">
      <h4 class="alert-heading">Notice !</h4>
      <p>Refund initiated for this invoice. Please make sure refund status is cancelled before processing this invoice. You can this ignore warnnig if this invoice was cancelled or you are going to cancel.</p>
      <hr>
      <p class="mb-0">Whenever you take any action on this invoice, you should check invoice status on gateway or Refund status.</p>
    </div>
  </div>
  <?php	
  }
  $invoiceBasicDetails = "
  <div class=\"col-sm-12 pt-1\">
    <dl class=\"row\">
	<div class=\"col-sm-12\">
		<div class=\"card\">
                  <div class=\"card-block\" style=\"border-left: 5px solid $wc_status_color_code;\">
".($sales_invoice_is_dummy ? "<div class=\"col-sm-12 pt-1\"><div class=\"alert alert-danger\" role=\"alert\"><i class=\"fa fa-info-circle\"></i> This is a Dummy Invoice. ! Payments, Accounts, Shipping, Commission, Refund should be avaoided.</div></div>" :"")."				  
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Code :</dt>
			<dd class=\"col-xs-8\">#$sales_invoice_number</dd>
		</div>
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Store :</dt>
			<dd class=\"col-xs-8\"><img alt=\"store\" data-logo=\"$store_logo\" style=\"width: 80px;\" src=\"".$app->imagePath($store_logo)."\" /></dd>
		</div>
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Amount :</dt>
			<dd class=\"col-xs-8\"> $sales_invoice_currency ".($sales_invoice_total_amount - $sales_discount_amount)."</dd>
		</div>
		<div class=\"col-sm-6 pt-1\">
			<dt class=\"col-xs-4\">Status :</dt>
			<dd class=\"col-xs-8\">$wc_status_name</dd>
		</div>
  </div></div></div></div>";
	
}?>
  <div class="col-sm-12">
    <form id="addsalesinvoice" name="addsalesinvoice">
      <div class="nav-tabs-boxed">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item"><a data-step="1" data-title="Customer details" data-intro="Customer full details with address" class="nav-link text-center <?php echo (isset($sales_invoice_id) && $sales_invoice_id >0) ? "" : "active"?>" data-toggle="tab" href="#tab-customer" role="tab" aria-controls="customer" aria-selected="false"><i class="icon-user"></i> <span class="hidden-xs">Customer</span></a></li>
		  
          <li class="nav-item"><a data-step="2" data-title="Invoice Products" data-intro="Add or View Products in invoice" class="nav-link text-center" data-toggle="tab" href="#tab-products" role="tab" aria-controls="products" aria-selected="false"><i class="icon-handbag"></i> <span class="hidden-xs">Products</span></a></li>
		  
          <li class="nav-item"><a data-step="3" data-title="Invoice Details" data-intro="See all basic invoice details information including website, payment method, status, discount etc." class="nav-link text-center <?php echo (isset($sales_invoice_id) && $sales_invoice_id >0) ? "active" : ""?>" data-toggle="tab" href="#tab-details" role="tab" aria-controls="details" aria-selected="false"><i class="icon-support"></i> <span class="hidden-xs">Details</span></a></li>
		  <?php if(isset($sales_invoice_id) && $sales_invoice_id >0){?>
          <li class="nav-item"><a data-step="4" data-title="Comments" data-intro="Invoice log and comments with all activity timeline on it" class="nav-link text-center" data-toggle="tab" href="#tab-comments" role="tab" aria-controls="comments" aria-selected="true"><i class="fa fa-comments-o"></i> <span class="hidden-xs">Comments</span></a></a></li>
		  
          <li class="nav-item" data-step="5" data-title="Labels & Shipment" data-intro="Upload or view media files related to invoice"><a class="nav-link text-center" data-toggle="tab" href="#tab-media" role="tab" aria-controls="media" aria-selected="false"><i class="icon-picture"></i> <span class="hidden-xs">Media</span></a></li>
		  
          <li class="nav-item"><a data-step="6" data-title="Invoice Processing" data-intro="Assign work to user for order like picking, packing, processing etc." class="nav-link text-center" data-toggle="tab" href="#tab-actions" role="tab" aria-controls="actions" aria-selected="false"><i class="icon-rocket"></i> <span class="hidden-xs">Actions</span></a></li>
		  
          <li class="nav-item" data-step="7" data-title="Labels & Shipment" data-intro="Upload or Download labels and invoice for order"><a class="nav-link text-center" data-toggle="tab" href="#tab-labels" role="tab" aria-controls="labels" aria-selected="false"><i class="icon-tag"></i> <span class="hidden-xs">Labels</span></a></li>
		  <?php }?>
        </ul>
        <div class="tab-content">
          
          <div id="tab-customer" role="tabpanel" class="tab-pane <?php echo (isset($sales_invoice_id) && $sales_invoice_id >0) ? "" : "active"?>">
            <div class="row"> <?php echo $invoiceBasicDetails;?>
              <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-align-justify"></i> <strong>
                    <?=$formHeading?>
                    </strong>
                    <div class="card-actions"> <a class="redirect" data-title="View All Invoice" title="View All Invoice" href="<?php echo $app->siteUrl("viewsalesinvoice");?>"><i class="fa fa-outdent fa-fw m-t-2"></i></a> </div>
                  </div>
                  <div class="card-block">
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_email">Customer Email<sup>*</sup></label>
                          <input class="form-control" id="customer_email" name="customer_email" maxlength="50" placeholder="Enter customer Email id"  onkeyup="getDropdown(this, 'Customer<=>customer_email',true)" type="email" value="<?=isset($customer_email)?$customer_email:"";?>" <?php if(isset($customer_email) && $customer_email !="") echo " readonly=\"readonly\"";?>>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_phone">Customer Phone Number<sup>*</sup></label>
                          <input class="form-control" id="customer_phone" name="customer_phone" maxlength="20" placeholder="Enter customer phone number" type="tel" value="<?=isset($customer_phone)?$customer_phone:"";?>">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_type_id">Customer Type<sup>*</sup></label>
                          <select id="customer_type_id" name="customer_type_id" class="form-control" size="1">
                            <?php
                $CustomerType = new CustomerType(0);
				echo $CustomerType->getOptions(isset($customer_type_id)?$customer_type_id:"0");
				?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <!--/row-->
                    
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_fname">Customer First Name<sup>*</sup></label>
                          <input class="form-control" id="customer_fname" name="customer_fname" maxlength="50" placeholder="Enter customer first name" type="text" value="<?=isset($customer_fname)?$customer_fname:"";?>">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_lname">Customer Last Name<sup>*</sup></label>
                          <input class="form-control" id="customer_lname" name="customer_lname" maxlength="50" placeholder="Enter customer Last name" type="text" value="<?=isset($customer_lname)?$customer_lname:"";?>">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_company">Company Name </label>
                          <input class="form-control" id="customer_company" name="customer_company" maxlength="150" placeholder="Enter Company name" type="text" value="<?=isset($customer_company)?$customer_company:"";?>">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_tax_number">Tax Id/Vat Number </label>
                          <input class="form-control input_text_upper" id="customer_tax_number" name="customer_tax_number" maxlength="50" placeholder="Enter Tax Id/Vat Number" type="text" value="<?=isset($customer_tax_number)?$customer_tax_number:"";?>">
                        </div>
                      </div>
                    </div>
                    <!--/row-->
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="customer_email">Search Address Here</label>
                          <div id="locationField">
                            <input id="autocomplete" class="inputbox form-control" placeholder="Enter Customer address"
                                         onFocus="geolocate()" type="text">
                            </input>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_address_street_number">Street Number<sup>*</sup></label>
                          <input class="form-control" id="customer_address_street_number" name="customer_address_street_number" maxlength="100" placeholder="Enter Address street number" type="text" value="<?=isset($customer_address_street_number)?$customer_address_street_number:"";?>">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_address_route">Address Route<sup>*</sup></label>
                          <input class="form-control" id="customer_address_route" name="customer_address_route" maxlength="100" placeholder="Enter Address route" type="text" value="<?=isset($customer_address_route)?$customer_address_route:"";?>">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_address_locality">Address Locality<sup></sup></label>
                          <input class="form-control" id="customer_address_locality" name="customer_address_locality" maxlength="100" placeholder="Enter Address locality" type="text" value="<?=isset($customer_address_locality)?$customer_address_locality:"";?>">
                        </div>
                      </div>
                    </div>
                    <!--/row-->
                    
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_address_administrative_area">State (Administrative Area)<sup>*</sup></label>
                          <input class="form-control" id="customer_address_administrative_area" name="customer_address_administrative_area" maxlength="100" placeholder="Enter State name" type="text" value="<?=isset($customer_address_administrative_area)?$customer_address_administrative_area:"";?>">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_address_country">Country Name<sup>*</sup></label>
                          <input class="form-control" id="customer_address_country" name="customer_address_country" maxlength="100" placeholder="Enter Country name" type="text" value="<?=isset($customer_address_country)?$customer_address_country:"";?>">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                          <label for="customer_address_postcode">PostCode <sup>*</sup></label>
                          <input class="form-control input_text_upper" id="customer_address_postcode" name="customer_address_postcode" maxlength="10" placeholder="Enter postcode" type="text" value="<?=isset($customer_address_postcode)?$customer_address_postcode:"";?>">
                        </div>
                      </div>
                      <input class="form-control" id="customer_address_geo_location" name="customer_address_geo_location" readonly="readonly" maxlength="100" type="hidden" value="<?=isset($customer_address_geo_location)?$customer_address_geo_location:"";?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-products" role="tabpanel" class="tab-pane">
            <div class="row"> <?php echo $invoiceBasicDetails;?>
              <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-align-justify"></i> <strong>Products </strong>
                    <div class="card-actions"> <a class="redirect" data-title="View All Invoice" title="View All Invoice" href="<?php echo $app->siteUrl("viewsalesinvoice");?>"><i class="fa fa-outdent fa-fw m-t-2"></i></a> </div>
                  </div>
                  <div class="card-block">
                    <div class="section">
                      <?php if(!$isInvoiceGenerated){?>
                      <h3 class="title text-center bg-inverse p-1">Add Product in Invoice</h3>
                      <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                          <div class="row">
                            <div class="col-xs-8 col-sm-8 col-md-7 col-lg-9">
                              <div class="row">
                                <div class="col-xs-12">
                                  <div class="form-group">
                                    <label for="searched_product_type">Product Type<sup>*</sup></label>
                                    <select id="searched_product_type" name="searched_product_type" class="form-control" size="1">
                                      <?php
						$HardwareType = new HardwareType(0);
						echo $HardwareType->getOptions(isset($searched_product_type)?$searched_product_type:"0");
						?>
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-5 col-lg-3">
                              <div class="row">
                                <div class="col-xs-12">
                                  <label for="add_new_hardware_type">&nbsp;</label>
                                  <a id="add_new_hardware_type" class="btn btn-warning btn-block"  data-toggle="modal" data-target="#appModal" onclick="addNewProductTypeForm(1, 'Add New Product Type')">Add New</a> </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-8">
                          <div class="form-group">
                            <label for="searched_product_name">Search/Write Product Name<sup>*</sup></label>
                            <input class="form-control" id="searched_product_name" name="searched_product_name" maxlength="250" placeholder="Serach/write product name" onfocus="checkType();"  onkeyup="getDropdown(this, 'Product<=>product_name',true, 'product_hardware_id = ' + searched_product_type.value)" type="email" value="">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="searched_product_price">Product price/Unit(inc.Vat)<sup>*</sup></label>
                            <input class="form-control" id="searched_product_price" name="searched_product_price" maxlength="20" placeholder="Product Price" step="0.01" type="number" value="">
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="searched_product_quantity">Product Quantity<sup>*</sup></label>
                            <input class="form-control" id="searched_product_quantity" name="searched_product_quantity" maxlength="5" placeholder="Product quantity" type="number" value="">
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="searched_product_sku">Product SKU<sup>*</sup></label>
                            <input class="form-control input_text_upper" id="searched_product_sku" name="searched_product_sku" maxlength="50" placeholder="Product serial number" type="text" value="">
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="searched_product_batch_code">Product Batch Code<sup>*</sup></label>
                            <input class="form-control input_text_upper" id="searched_product_batch_code" name="searched_product_batch_code" maxlength="20" placeholder="Product batch code" type="text" value="">
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="btn_add_block">Add this Product</label>
                            <button id="btn_add_block" type="button" class="btn btn-info btn-block" onclick="addProductinRow();">Add Product</button>
                          </div>
                        </div>
                      </div>
                      <?php }?>
                      
                    </div>
                    <!--/row-->
                    <div class="section">
                      <div class="row" id="sales_product_item_list"> </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-details" role="tabpanel" class="tab-pane <?php echo (isset($sales_invoice_id) && $sales_invoice_id >0) ? "active" : ""?>">
            <div class="row"> <?php echo $invoiceBasicDetails;?>
              <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-align-justify"></i> <strong>Details </strong>
                    <div class="card-actions"> <a class="redirect" data-title="View All Invoice" title="View All Invoice" href="<?php echo $app->siteUrl("viewsalesinvoice");?>"><i class="fa fa-outdent fa-fw m-t-2"></i></a> </div>
                  </div>
                  <div class="card-block">
                    <div class="row mt-2">
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group" data-step="8" data-title="Store" data-intro="Store from/ for which invoice is generating ">
                          <label for="sales_invoice_store_id">Select Purchase Store<sup>*</sup></label>
                          <select id="sales_invoice_store_id" name="sales_invoice_store_id" class="form-control" size="1">
                            <?php
                $store = new Store(0);
				echo $store->getOptions(isset($sales_invoice_store_id)?$sales_invoice_store_id:"0");
				?>
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group" data-step="9" data-title="Dispatched Location" data-intro="From where product will be shipped">
                          <label for="sales_invoice_dispatched_store">Select Dispatched Location<sup></sup></label>
                          <select id="sales_invoice_dispatched_store" name="sales_invoice_dispatched_store" class="form-control" size="1">
                            <?php
                $storeLocation = new StoreLocation(0);
				echo $storeLocation->getStoreLocationOptions(isset($sales_invoice_dispatched_store)?$sales_invoice_dispatched_store:"", "Dispatch");
				?>
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group" data-step="10" data-title="Invoice cycle" data-intro="Choose invoice auto genetrate mode">
                          <label for="sales_invoice_cycle_mode">Invoice Cycle Mode<sup>*</sup></label>
                          <select id="sales_invoice_cycle_mode" <?php if(isset($sales_invoice_is_original) && $sales_invoice_is_original==0) echo "disabled=\"disabled\""; ?> name="sales_invoice_cycle_mode" class="form-control" size="1">
                            <option value="ONCE" <?php echo (isset($sales_invoice_cycle_mode) && $sales_invoice_cycle_mode == "ONCE") ? "selected":""?>>Once This time only</option>
                            <option value="WEEK" <?php echo (isset($sales_invoice_cycle_mode) && $sales_invoice_cycle_mode == "WEEK") ? "selected":""?>>Weekly</option>
                            <option value="MONTH" <?php echo (isset($sales_invoice_cycle_mode) && $sales_invoice_cycle_mode == "MONTH") ? "selected":""?>>Monthly</option>
                            <option value="YEAR" <?php echo (isset($sales_invoice_cycle_mode) && $sales_invoice_cycle_mode == "YEAR") ? "selected":""?>>Yearly</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="sales_invoice_status">Status<sup>*</sup></label>
                          <select id="sales_invoice_status" <?php echo $isSalesInvoiceFinished ? $readonlyField." disabled" : "" ?> name="sales_invoice_status" class="form-control" size="1">
                            <?php
					$WcStatus = new WcStatus(0);
					echo $WcStatus->getOptions(isset($sales_invoice_status)?$sales_invoice_status:"0");
					?>
                          </select>
                        </div>
                      </div>
					  
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 sales_invoice_is_paid_dependent">
                        <div class="form-group">
                          <label for="sales_invoice_payment_mode">Payment Mode</label>
                          <select id="sales_invoice_payment_mode" name="sales_invoice_payment_mode" class="form-control" size="1">
                            <option value=""> -- Select payment mode -- </option>
                            <option value="CHECK" <?php echo (isset($sales_invoice_payment_mode) && $sales_invoice_payment_mode == "CHECK") ? "selected":""?>>Check</option>
                            <option value="CASH" <?php echo (isset($sales_invoice_payment_mode) && $sales_invoice_payment_mode == "CASH") ? "selected":""?>>Cash</option>
                            <option value="BANK TRANSFER" <?php echo (isset($sales_invoice_payment_mode) && $sales_invoice_payment_mode == "BANK TRANSFER") ? "selected":""?>>Bank Transfer</option>
                            <option value="OTHER" <?php echo (isset($sales_invoice_payment_mode) && $sales_invoice_payment_mode == "OTHER") ? "selected":""?>>Other</option>
                          </select>
                        </div>
                      </div>
					  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						  <div class="form-group">
							<label class="text-info" for="sales_invoice_date">Invoice date<sup>*</sup></label>
							<div class="input-group date">
							  <input class="form-control" id="sales_invoice_date" name="sales_invoice_date" placeholder="YYYY-MM-DD" value="<?=isset($sales_invoice_date)?$sales_invoice_date:"";?>" type="text">
							  <span class="input-group-addon">
							  <label style="margin-bottom:0px;" for="sales_invoice_date"><i class="fa fa-calendar fa-fw"></i></label>
							  </span> </div>
							<script type="text/javascript">
									$('#sales_invoice_date').datepicker({
										format: "yyyy-mm-dd",
										autoclose:true,
										endDate  : '<?php echo date('Y-m-d')?>',						
										daysOfWeekHighlighted: '0,6',
										todayHighlight:true
									});
							</script> 
						  </div>
						</div>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 sales_invoice_is_paid_dependent">
                        <div class="form-group">
                          <label for="sales_invoice_payment_reference">Payment Reference</label>
                          <input class="form-control" id="sales_invoice_payment_reference" name="sales_invoice_payment_reference" maxlength="500" placeholder="Enter invoice payment reference" type="text" value="<?=isset($sales_invoice_payment_reference)?$sales_invoice_payment_reference:"";?>">
                        </div>
                      </div>
					  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="sales_invoice_manufacturer">Manufacturer (if Specified)<sup></sup></label>
                          <input class="form-control" id="sales_invoice_manufacturer" name="sales_invoice_manufacturer" maxlength="50" placeholder="Enter Manufacturer name" type="text" value="<?=isset($sales_invoice_manufacturer)?$sales_invoice_manufacturer:"";?>">
                        </div>
                      </div>
					  
					  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="sales_discount_amount">Discount amount (As Currency)</label>
                          <input class="form-control" id="sales_discount_amount" name="sales_discount_amount" min="0" step="0.01" <?php echo $readonlyField;?> placeholder="Enter discount amount" type="number" value="<?=isset($sales_discount_amount)?$sales_discount_amount:"0";?>">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="sales_invoice_currency">Currency code </label>
                          <input class="form-control input_text_upper" id="sales_invoice_currency" name="sales_invoice_currency" placeholder="Enter 3 char currency code" type="text" value="<?=isset($sales_invoice_currency)?$sales_invoice_currency:"";?>">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="sales_invoice_vat">Sales invoice vat %</label>
                          <input class="form-control" id="sales_invoice_vat" name="sales_invoice_vat" min="0" step="0.01" placeholder="Enter invoice vat percent" type="number" value="<?=isset($sales_invoice_vat)?$sales_invoice_vat:"0";?>">
                        </div>
                      </div>
					  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="sales_payment_paid_amount">Payment paid amount</label>
                          <input class="form-control" id="sales_payment_paid_amount" name="sales_payment_paid_amount" min="0" <?php echo isset($product_sum_total) ? "max=\"$product_sum_total\"" : "" ?> step="0.01"  placeholder="Payment paid amount" type="number" value="<?=isset($sales_payment_paid_amount)?$sales_payment_paid_amount:"0";?>">
                        </div>
                      </div>
					  
                    </div>
                    <div class="row mt-2">
					<table class="table table-stripe">
					<tr>
					<td>
                      <div class="col-xs-12 mt-1">
                        <div class="form-group mb-0">
                          <label for="send_invoice_to_customer"><i class="fa fa-envelope-o fa-fw m-t-2"></i> &nbsp;Send to Customer </label>
                          <label class="switch switch-icon switch-pill switch-success pull-right">
                            <input class="switch-input" id="send_invoice_to_customer" value="1" name="send_invoice_to_customer" type="checkbox">
                            <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                        </div>
                      </div>
					  </td></tr>
					  <tr><td>
                      <div class="col-xs-12 mt-1">
                        <div class="form-group mb-0">
                          <label for="sales_invoice_is_paid"><i class="fa fa-bank fa-fw m-t-2"></i> &nbsp;Is Invoice Fully Paid ?</label>
                          <label class="switch switch-icon switch-pill switch-success pull-right">
                            <input class="switch-input" id="sales_invoice_is_paid" value="1" name="sales_invoice_is_paid"  type="checkbox"  <?php echo (isset($sales_invoice_is_paid) && $sales_invoice_is_paid == 1) ? "checked=\"checked\"":""?> >
                            <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                        </div>
                      </div>
					  </td></tr>
					  <tr><td>
                      <div class="col-xs-12 mt-1">
                        <div class="form-group mb-0">
                          <label for="sales_invoice_is_estimate"><i class="fa fa-sticky-note-o fa-fw m-t-2"></i> &nbsp;is this Estimate ?</label>
                          <label class="switch switch-icon switch-pill switch-success pull-right">
                            <input class="switch-input" id="sales_invoice_is_estimate" value="1" name="sales_invoice_is_estimate"  type="checkbox"  <?php echo (isset($sales_invoice_is_estimate) && $sales_invoice_is_estimate == 1) ? "checked=\"checked\"":""?> >
                            <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                        </div>
                      </div>
					  </td></tr>
					  <tr><td>
                      <div class="col-xs-12 mt-1">
                        <div class="form-group mb-0">
                          <label for="sales_invoice_is_vat_applicable"><i class="fa fa-viacoin fa-fw m-t-2"></i> &nbsp;is Vat Applicable ?</label>
                          <label class="switch switch-icon switch-pill switch-success pull-right">
                            <input class="switch-input" id="sales_invoice_is_vat_applicable" value="1" name="sales_invoice_is_vat_applicable" type="checkbox"  <?php echo (isset($sales_invoice_is_vat_applicable) && $sales_invoice_is_vat_applicable == 1) ? "checked=\"checked\"":""?> >
                            <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                        </div>
                      </div>
                      </td></tr>
					  <tr><td>
					  <div class="col-xs-12 mt-1">
                        <div class="form-group mb-0">
                          <label for="sales_invoice_margin_vat_label"><i class="fa fa-tag fa-fw m-t-2"></i> &nbsp;Show (Margin VAT Scheme) ?</label>
                          <label class="switch switch-icon switch-pill switch-success pull-right">
                            <input class="switch-input" id="sales_invoice_margin_vat_label" value="1" name="sales_invoice_margin_vat_label" type="checkbox"  <?php echo (isset($sales_invoice_margin_vat_label) && $sales_invoice_margin_vat_label == 1) ? "checked=\"checked\"":""?> >
                            <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                        </div>
                      </div>
					  </td></tr>
					  <tr><td>
                      <div class="col-xs-12 mt-1">                        
                        <div class="form-group mb-0">
                          <label for="sales_invoice_is_dummy"><i class="fa fa-mercury fa-fw m-t-2"></i> &nbsp;is this Dummy Invoice ?</label>
                          <label class="switch switch-icon switch-pill switch-danger pull-right">
                            <input class="switch-input" id="sales_invoice_is_dummy" value="1" name="sales_invoice_is_dummy"  type="checkbox"  <?php echo (isset($sales_invoice_is_dummy) && $sales_invoice_is_dummy == 1) ? "checked=\"checked\"":""?> >
                            <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                        </div>
                      </div>
                      </td></tr>
					  <tr><td>
                      <div class="col-xs-12 mt-1">
                        <?php if($sales_invoice_id == 0 || $sales_invoice_is_generated == 0 || isAdminRole()){?>
                        <div class="form-group mb-0">
                          <label id="sales_invoice_is_generated" for="sales_invoice_is_vat_applicable"><i class="fa fa-file-pdf-o  fa-fw m-t-2"></i> &nbsp;Generate Sales Invoice <small class="text-danger"><i class="fa fa-fwarning"></i> Not editable once generate <?php echo isAdminRole() ? "[Non admin]":""?>
						  <?php if(isset($sales_invoice_id) && $sales_invoice_id >0){?>
						  <a target="_blank" href="<?php echo DOC::SALESINV($sales_invoice_id)?>" class="btn btn-warning btn-sm">Preview</a>
						  <?php }?>
						  </small></label>
                          <label class="switch switch-icon switch-pill switch-success pull-right">
                            <input class="switch-input" id="sales_invoice_is_vat_applicable" value="1" name="sales_invoice_is_generated"  type="checkbox"  <?php echo (isset($sales_invoice_is_generated) && $sales_invoice_is_generated == 1) ? "checked=\"checked\"":""?> >
                            <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                        </div>
                        <?php }
						if(!($sales_invoice_id == 0 || $sales_invoice_is_generated == 0)){?>
                        <label><i class="fa fa-download  fa-fw m-t-2"></i> Sales Invoice Generated</label>
                        <a target="_blank" href="<?php echo DOC::SALESINV($sales_invoice_id)?>" class="pull-right btn btn-success btn-sm"><i class=" fa fa-download"></i> Download</a>
                        <?php }?>
                      </div>
					  </td></tr>
					  <tr><td>
					  
					  <?php if(isset($sales_invoice_id) && $sales_invoice_id){?>
                      <div class="col-xs-12 mt-1">
                        <div class="form-group mb-0">
                          <label for="sales_payment_paid_amount"><i class="fa fa-money fa-fw m-t-2"></i> Invoice total amount</label>
                          <span class="pull-right fa-2x"><?php echo $product_sum_total?> <?php echo $currency;?></span> </div>
                      </div>
                      <?php }?>
                      </td></tr>
					  </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
		  <?php if(isset($sales_invoice_id) && $sales_invoice_id >0){?>
		  <div id="tab-comments" role="tabpanel" class="tab-pane">
            <div class="row"> <?php echo $invoiceBasicDetails;?>
              <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-comments-o"></i> Comments 
					<div class="card-actions"><a href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?php echo $sales_invoice_id;?>|S', '<?php echo $sales_invoice_number;?> Log Report')"><i class="fa fa-comments-o"></i></a>
					</div>
				  </div>
                  <div class="card-block">
                    <?php
					$complaintLog = new ComplaintLog();
					if($commentsLog = $complaintLog->getLog($sales_invoice_id, 'S')){
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
					$timelineArry = Activity::getModuleActivityTimeline('S', $sales_invoice_id);
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
		  <div id="tab-media" role="tabpanel" class="tab-pane">
            <div class="row"> <?php echo $invoiceBasicDetails;?>              
              <div class="col-md-12" style="margin-top: 1px;">
                <div class="card">
                  <div class="card-header card-default"><i class="fa fa-file-text"></i> Sales Invoice Media Files
                    <div class="card-actions"> <a data-id="<?php echo $sales_invoice_id?>" class="dropdown-item invoice_image_camera" id="invoice_image_camera_<?php echo $sales_invoice_id?>"  data-media-section="<?=MediaSection::$SalesProductMedia?>" href="#" title="Take image for <?php echo $sales_invoice_number?> sales request"><i class="fa fa-camera text-danger"></i></a> </div>
                  </div>
                  <div class="card-block">
                    <div class="row media_uploaded_image_box" id="media_uploaded_image_box_<?php echo $sales_invoice_id;?>">
                      <?php 
					  $cm = new SalesMedia(0);
					  $iCounter = 0;
					  $recorded_snapshot_list = $cm->getImageList($sales_invoice_id);
					  if($recorded_snapshot_list){
					  foreach($recorded_snapshot_list as $itemCapt):
						 $iCounter++;
					  ?>
                      <div class="col-xs-6 col-sm-4 col-lg-3 thumb" data-record-id="<?php echo $itemCapt['sales_image_id']; ?>" id="item-invoice-media-container-<?php echo $itemCapt['sales_image_id']; ?>">
                        <div class="card">
                          <div class="card-header card-default">Image <?php echo $iCounter;?>
                            <div class="card-actions"><a href="javascript:removeInvoiceMedia('<?php echo $itemCapt['sales_image_id']; ?>', '<?php echo $sales_invoice_id; ?>');"><i class="icon-close"></i></a></div>
                          </div>
                          <div class="card-block p-a-1 clearfix"> <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="Image <?php echo $iCounter;?>" data-image="<?php echo $app->basePath($itemCapt['sales_image_path']); ?>" data-target="#image-gallery"> <img class="img-thumbnail" src="<?php echo getResizeImage($itemCapt['sales_image_path'], 100); ?>" alt="Image <?php echo $iCounter;?>"> </a>
                            <div class="font-weight-normal  text-center"><?php echo $itemCapt['sales_image_title']; ?></div>
                          </div>
                        </div>
                      </div>
                      <?php
						endforeach;
					  }
					?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-actions" role="tabpanel" class="tab-pane">
            <div class="row"> <?php echo $invoiceBasicDetails;?>
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-file-text"></i> Invoice Action</div>
                  <div class="card-block">
                    <div class="row py-0">
                      <div class="col-xs-12 col-sm-6 col-md-3">
                        <?php
		  if($sales_invoice_status != 3 && $sales_invoice_cancel_user == 0 && $sales_invoice_picking_user){
			  $employee = new Employee($sales_invoice_picking_user);
			  $picUserData = $employee->getDetails();
		  ?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                            <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($picUserData["user_image"],50)?>"> </div>
                            <div class="px-2 justify-content-center">
                              <div class="text-value-sm text-primary text-center">Picked by <?php echo $picUserData['user_fname']?> <?php echo $picUserData['user_lname']?></div>
                              <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($sales_invoice_picking_time))?></div>
                            </div>
                          </div>
                        </div>
                        <?php
		  }elseif($sales_invoice_status != 3 && $sales_invoice_cancel_user == 0){?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="pick" class="orderaction btn btn-outline-warning btn-block">STEP 1: PICK INVOICE</a> </div>
                        </div>
                        <?php
		  }
		  else{
		?>
                        <div class="card">
                          <div class="card-body p-0 text-center p-2">
                            <div class="alert alert-warning mb-0" role="alert"> Invoice Picking cancelled </div>
                          </div>
                        </div>
                        <?php	  
		  }
		  ?>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3">
                        <?php
		  if($sales_invoice_packing_user){
			  $employee = new Employee($sales_invoice_packing_user);
			  $packUserData = $employee->getDetails();
		  ?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                            <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($packUserData["user_image"],50)?>"> </div>
                            <div class="px-2 justify-content-center">
                              <div class="text-value-sm text-primary text-center">Packed by <?php echo $packUserData['user_fname']?> <?php echo $packUserData['user_lname']?></div>
                              <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($sales_invoice_packing_time))?></div>
                            </div>
                          </div>
                          <?php if($sales_invoice_process_user == 0){?>
                          <div class="card-body p-0 d-flex align-items-center  justify-content-center p-1">
                            <div class="card-body p-0 d-flex align-items-center  justify-content-between"> <a data-reset="<?php echo md5($sales_invoice_packing_user);?>" data-action="pack" class="orderaction orderupdateaction btn btn-sm btn-info text-white">UPDATE PACKING USER</a> &nbsp; <a title="REMOVE PACKING USER" data-reset="<?php echo md5($sales_invoice_packing_user);?>" data-action="unpack" class="removepackinguser btn btn-sm btn-danger text-white"><i class="fa fa-trash"></i></a> </div>
                          </div>
                          <?php }?>
                        </div>
                        <?php
		  }elseif($sales_invoice_status != 3 && $sales_invoice_cancel_user == 0){?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="pack" class="orderaction btn btn-outline-info btn-block">STEP 2: PACK INVOICE</a> </div>
                        </div>
                        <?php
		  }
		  else{
		?>
                        <div class="card">
                          <div class="card-body p-0 text-center p-2">
                            <div class="alert alert-warning mb-0" role="alert"> Invoice Packing cancelled </div>
                          </div>
                        </div>
                        <?php	  
		  }
		  ?>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3">
                        <?php
		  if($sales_invoice_process_user){
			  $employee = new Employee($sales_invoice_process_user);
			  $procUserData = $employee->getDetails();
		  ?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                            <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($procUserData["user_image"],50)?>"> </div>
                            <div class="px-2 justify-content-center">
                              <div class="text-value-sm text-primary text-center">Processed by <?php echo $procUserData['user_fname']?> <?php echo $procUserData['user_lname']?></div>
                              <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($sales_invoice_process_time))?></div>
                            </div>
                          </div>
                        </div>
                        <?php
		  }elseif($sales_invoice_status != 3 && $sales_invoice_cancel_user == 0){?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="process" class="orderaction btn btn-outline-success btn-block">STEP 3: PROCESS INVOICE</a> </div>
                        </div>
                        <?php
		  }
		  else{
		?>
                        <div class="card">
                          <div class="card-body p-0 text-center p-2">
                            <div class="alert alert-warning mb-0" role="alert"> Invoice Process cancelled </div>
                          </div>
                        </div>
                        <?php	  
		  }
		  ?>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3">
                        <?php
		  if($sales_invoice_status == 3 && $sales_invoice_cancel_user){
			  $employee = new Employee($sales_invoice_cancel_user);
			  $proccancelData = $employee->getDetails();
		  ?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                            <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($proccancelData["user_image"],50)?>"> </div>
                            <div class="px-2 justify-content-center">
                              <div class="text-value-sm text-primary text-center">Cancelled by <?php echo $proccancelData['user_fname']?> <?php echo $proccancelData['user_lname']?></div>
                              <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($sales_invoice_cancel_time))?></div>
                            </div>
                          </div>
                        </div>
                        <?php
		  }else{?>
                        <div class="card">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="cancel" class="orderaction btn btn-outline-danger btn-block">CANCEL INVOICE</a> </div>
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
            <div class="row"> <?php echo $invoiceBasicDetails;?>
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-header"><i class="fa fa-file-text"></i> Invoice Invoice/Label</div>
                  <div class="card-block">
                    <div class="row">
                      <?php if($sales_invoice_process_user == 0 || isAdminRole()){?>
                      <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="card sales_invoice_label_image_container">
                          <div class="card-body p-0 d-flex align-items-center  justify-content-between p-1">
                            <div class="pl-0 justify-content-start"> <i class="fa fa-file-pdf-o text-danger fa-2x"></i> </div>
                            <div class="px-2 justify-content-center">
                              <div class="text-value-sm">
							  <!--<a onClick="$('.sales_invoice_label_image').click()" class="btn btn-info btn-block px-1">Upload Shipment Label </a>
                                <input onchange="uploadOrderLabel(this.name);" type="file" id="sales_invoice_label_image" class="d-none sales_invoice_label_image" name="sales_invoice_label_image">-->
								<a onClick="openLabelUploader('sales_invoice_label_image', '<?php echo $sales_invoice_id;?>', 'Label');" class="btn btn-info text-white">Upload Shipment Label </a>
                              </div>
                            </div>
                          </div>
                          <div class="px-2 pb-3"> Label can be uploaded till the order is not Processed </div>
                        </div>
                      </div>
                      <?php }?>
                      <?php 
			 Modal::load(array('SalesLabels'));
			 $SalesLabels = new SalesLabels();
			 $shipmentRecords = $SalesLabels->getWebOrderShipmentLabel(isset($sales_invoice_id)?$sales_invoice_id:0);
			if(count($shipmentRecords))
			{
				$lCounter = 0;
				foreach($shipmentRecords as $_slabel)
				{
					echo getShipmentLabelBlock($_slabel, array('action' => 'salescancellabel')); 
				}
			}
			?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
		  <?php }?>
		</div>
      </div>
      <?php if(isAdmin()):?>
      <div class="row">
        <div class="col-md-12 mt-1 text-right">
          <?php 
			$creator = new Employee((isset($sales_invoice_creator) && $sales_invoice_creator != 0 ) ? $sales_invoice_creator : getLoginId());
			$creatorData = $creator->getDetails();
			$sales_invoice_created_date = isset($sales_invoice_created_date) ? $sales_invoice_created_date : date('Y-m-d H:i:s');
			?>
          <div class="pull-right pl-1"><img class="img img-circle" style="margin-top:0px; margin-bottom:0px; height:40px;" src="<?php echo getResizeImage($creatorData["user_image"],50)?>"/></div>
          <div class="pull-right">Originally created by <?php echo $creatorData['user_name']?> <i class="fa fa-check-circle text-success"></i><br/>
            <span class="text-muted" style="font-size: 0.9em;"><?php echo dateView($sales_invoice_created_date, 'NOW')?> <?php echo dateView($sales_invoice_created_date, 'FULL')?></span> </div>
        </div>
      </div>
      <?php endif;?>
      <div class="mt-2">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-footer">
                <button type="reset" class="btn btn-outline-danger"><i class="fa fa-refresh fa-fw "></i> Reset</button>
                <button type="button" id="btn_invpoice_submit" onClick="confirmMessage.Set('Are you sure to <?=$sales_invoice_id==0?"add":"updated"?> Sales Invoice...?', 'addSalesInvoice');" class="btn btn-success submission_handler_btn"><i class="fa fa-check fa-fw "></i> <span id="btn_action_name">
                <?=$btnText?>
                </span> Invoice </button>
                <?php if(isset($sales_invoice_id) && $sales_invoice_id > 0){?>
                <div class="btn-group dropup">
                  <button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action<span class="sr-only"></span></button>
                  <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item" href="<?php echo $app->basePath("salesinvoice/$sales_invoice_id"); ?>"><i class="fa fa-refresh fa-fw "></i> Reload</a> <a class="dropdown-item" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?php echo $sales_invoice_id;?>|S', '<?php echo $sales_invoice_number;?> Log Report')"><i class="fa fa-comments-o fa-fw"></i> Log Comments</a>
                    <?php if($sales_invoice_is_generated == 1 || $sales_invoice_is_estimate == 1){?>
                    <a class="dropdown-item" href="javascript:newWindow('<?php echo DOC::SALESINV($sales_invoice_id)?>');"><i class="fa fa-file-pdf-o fa-fw text-danger"></i> Download Invoice</a>
                    <?php }?>
                    <?php if(isAdmin()){?>
                    <a class="dropdown-item" href="javascript:requestFeedback('<?php echo $sales_invoice_id?>', 'S', '<?php echo $sales_invoice_customer_id;?>')"><i class="fa fa-rss fa-fw"></i> Request Feedback</a>
                    <?php }?>
                    <?php if($sales_invoice_is_cancelled == 1){?>
                    <a class="dropdown-item"  href="#" onclick="confirmMessage.Set('Are you sure to undo Cancel Sales Invoice...?', 'undoCancelInvoice', '<?php echo $sales_invoice_id;?>')"><i class="fa fa fa-ban fa-fw text-primary"></i> UnDO Cancel</a> <a class="dropdown-item" target="new" href="<?php echo DOC::SALESINVMEMO($sales_invoice_id)?>"><i class="fa fa fa-file-text-o fa-fw text-danger"></i> Canceled MEMO</a>
                    <?php }else{?>
                    <a class="dropdown-item" href="#" onclick="confirmMessage.Set('Are you sure to undo Cancel Sales Invoice...?', 'cancelInvoice', '<?php echo $sales_invoice_id;?>')"><i class="fa fa fa-ban fa-fw text-danger"></i> Cancel Invoice</a>
                    <?php }?>
                    <a class="dropdown-item redirect" href="<?php echo $app->basePath("addrefund/S/$sales_invoice_id"); ?>"><i class="fa fa-registered text-danger fa-fw"></i> Refund</a> 
					<a class="dropdown-item duplicatesalesinvoice" href="#" onclick="duplicateSalesInvoice('<?php echo $sales_invoice_id;?>')"><i class="fa fa-copy text-info fa-fw"></i> Duplicate</a>
					</div>
                </div>
                <?php }?>
              </div>
              <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addsalesinvoice";?>"  />
              <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
              <input type="hidden" id="sales_invoice_id" name="sales_invoice_id" value="<?=isset($sales_invoice_id)?$sales_invoice_id:"0";?>"  />
              <input type="hidden" id="sales_invoice_customer_id" name="sales_invoice_customer_id" value="<?=isset($customer_id)?$customer_id:"0";?>"  />
              <input type="hidden" id="sales_invoice_customer_address_id" name="sales_invoice_customer_address_id" value="<?=isset($sales_invoice_customer_address_id)?$sales_invoice_customer_address_id:"0";?>"  />
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
var mediaSection = '<?=MediaSection::$salesLabelInvoiceMedia?>';
<?php Modal::load(array('ProductAvailbility'));?>
var availabilityList = <?php $productAvailbility = new ProductAvailbility(); echo json_encode($productAvailbility->getAvailbiltyChecked('S', $sales_invoice_id));?>;
$(document).off('change', '#sales_invoice_is_outsourced_chk', '.duplicatesalesinvoice');
$(".orderaction, .web_order_is_paid, .product_image_camera, .updateImageTitle, .metadataloader, .removepackinguser").off();
var isInvoiceGenerated = <?php echo $isInvoiceGenerated ? 'true':'false';?>;
var readonlyField = '<?php echo $readonlyField;?>';

var order_process_user = '<?php echo isset($sales_invoice_process_user) ? $sales_invoice_process_user : 0; ?>';
var order_current_status = '<?php echo isset($sales_invoice_status) ? $sales_invoice_status : 0; ?>';
function uploadOrderLabel(field_name)
{
	confirmMessage.Set('Are you sure to upload Shipment Label...?', 'proceedUploadOrderLabel', field_name);
}

function getProductCheckList(id){
	var html = '';
	if(availabilityList != null && availabilityList.length > 0){
		for(var i=0; i<availabilityList.length; i++){
			var availCheck = availabilityList[i];
			if(availCheck['pro_avail_product_id'] == id){
				html += "<div class='alert alert-"+availCheck['pro_avail_class']+"'><h4 class=\"alert-heading\"><img style='height:24px' class='img img-circle' src='"+availCheck['user_image']+"' /> "+availCheck['user_fname']+" "+availCheck['user_lname']+"</strong> <span class='pull-right text-xs'>On "+availCheck['pro_avail_checked_time']+"</span></h4><p>Checked this product availability with supplier <strong>"+availCheck['supplier_name']+"</strong> and mark as <strong>"+availCheck['pro_avail_stock_status']+"</strong><br/><i> "+availCheck['pro_avail_remark']+"</i><span class='pull-right mb-1'>Added "+availCheck['pro_avail_created_date_now']+"</span></p></div>";
			}
		}
	}
	return html;
}
function proceedUploadOrderLabel(field_name)
{	
	var files = _(field_name).files;
	var formdata = new FormData(); 
	formdata.append('field_name', field_name);
	if(files.length == 1)
	{
		for (var index = 0; index < files.length; index++) 
		{
			formdata.append('webcam[]', files[index]); 
		}
		formdata.append('mediasection', '<?=MediaSection::$salesLabelInvoiceMedia?>');
		formdata.append('id', $("#sales_invoice_id").val()); 
		is_interval_running = false;
		is_file_uploaded 	= false;
		$(".sales_invoice_label_image_container").html('<div class="col-xs-12 col-lg-3 p-3"  id="item-collection-media-processing"><div id="image_uploader"></div></div>');

		var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", progressHandler, false); 
		ajax.addEventListener("load", completeLabelHandler, false);	
		ajax.addEventListener("error", errorHandler, false); 
		ajax.addEventListener("abort", abortHandler, false); 
		ajax.open("POST", sitePath +"saveimage.php"); 
		ajax.send(formdata);
	}
	else{
		
	}	
}

function completeLabelHandler(e){
	var arr	=	JSON.parse(e.target.response);	
	message(arr[1],2000);
	setTimeout(function(){
		Redirect('salesinvoice/'+$("#sales_invoice_id").val());	
	}, 2000);
}

$(document).on("click", ".btn_save_duplicate_invoice", function(){
	confirmMessage.Set('Are you sure to create a new similar Sales Invoice...?', 'saveDuplicateInvoice', '<?php echo $sales_invoice_id;?>');
});
function duplicateSalesInvoice(sales_invoice_id){
	setPopup(sales_invoice_id, "Add Duplicate invoice");
	var bodyHtml = '<div class="col-md-12"><div class="row">';
    bodyHtml += '<div class="col-sm-12 col-md-6"><div class="form-group"><label for="duplicate_customer_email_id">Customer Email<sup>*</sup></label><input type="email" id="duplicate_customer_email_id" name="duplicate_customer_email_id" required  class="form-control" placeholder="Duplicate invoice customer email" onBlur="getCustomerAddress()" value="'+($("#customer_email").val())+'" /></div></div>';	
	bodyHtml += '<div class="col-sm-12 col-md-6"><p class="text-info"><i class="fa fa-warning text-warning"></i> If you want to change customer in duplicate invoice you are going to create, Just update this email by New customer\'s email address. System will automatically apply a customer associated with this email on this invoice.</p></div>';
	bodyHtml += '<div class="col-sm-12 duplicate_customer_address"></div>';
	bodyHtml += '</div></div>';
	modal.Body(bodyHtml);
	modal.Footer('<button type="button" class="btn btn-success btn_save_duplicate_invoice" >Save Duplicate Invoice</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
	getCustomerAddress();
}

function getCustomerAddress(){
	var data={
					action				:	'sales/getcustomeraddressbyemail',
					duplicate_customer_email_id : $("#duplicate_customer_email_id").val()
			};	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			$(".duplicate_customer_address").html('Checking customer address...');
			dissableSubmission();
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0] == 200){
				$(".duplicate_customer_address").html('');
				var addressHtml = "<label>Select Adddress for Invoice</label><ul class='list-group mb-2'>";
				addressHtml += "<li class='list-group-item d-flex'><label for='address_0'><input id='address_0' class='duplicate_customer_address_id' type='radio' name='duplicate_customer_address_id' value='0'> Use new address <span style='font-weight:normal;'> ( Details can be updated followed by invoice add )</span></label></li>";
				if(arr[2].length > 0){
					for(var i=0; i<arr[2].length; i++){
						var address = arr[2][i];
						addressHtml += "<li class='list-group-item d-flex'><label style='font-weight:normal;' for='address_"+address['customer_address_id']+"'><input id='address_"+address['customer_address_id']+"' class='duplicate_customer_address_id' type='radio' name='duplicate_customer_address_id' value='"+address['customer_address_id']+"'> "+address['customer_address_street_number']+", "+address['customer_address_route']+", "+address['customer_address_locality']+", "+address['customer_address_administrative_area']+", "+address['customer_address_country']+" - "+address['customer_address_postcode']+"</lable></li>";
					}
				}
				addressHtml += "<ul>";
				$(".duplicate_customer_address").html(addressHtml);
			}
			toastMessage(arr[1]);
		}
	});
}

function saveDuplicateInvoice(sales_invoice_id){
	var data={
					action				:	'sales/duplicatesalesinvoice',
					sales_invoice_id	:	sales_invoice_id,
					duplicate_customer_email_id : $("#duplicate_customer_email_id").val(),
					duplicate_customer_address_id : $(".duplicate_customer_address_id:checked").val()
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
				setTimeout(function(){
				Redirect('salesinvoice/'+arr[2]);	
				}, 2000);
				modal.Hide();
			}
			message(arr[1]);
		}
	});
}

function salescancellabel(label_id){
	if(<?php echo isset($sales_invoice_status) ? $sales_invoice_status : 0?> == 1){
		message("danger|Invoice completed can't cancel label.");
		return;
	}
	var data={
					action		:	'sales/salescancellabel',
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
				$(".shipmentlabel_"+label_id).remove();
			}
			message(arr[1]);
		}
	});
}

function salesinvoiceaction(eventAction){
								   
	var data={
					action				:	'sales/salesinvoiceaction',
					order_action		:	eventAction.action,
					sales_invoice_id	: 	$('#sales_invoice_id').val(),
					invoice_user_id 	:   $("#invoice_user_id").val(),
					user_reset			:	$("#user_reset").val()
									
			};
	if(eventAction == 'pick'){
		if($("#web_apply_same_user_to_pack_chk").is(":checked"))
		{
			data.same_user_to_pack_order = 1;
		}
	}
	if(eventAction == 'pack'){
		if($("#sales_invoice_is_outsourced_chk").is(":checked"))
		{		   
										  
			if($("#sales_invoice_is_outsourced").val() == 0){
				message("danger|Please select outsource unit");
				return false;																				   
			}
			data.sales_invoice_is_outsourced = $("#sales_invoice_is_outsourced").val();
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
					setTimeout(function(){
					Redirect('salesinvoice/'+data.sales_invoice_id);	
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

	$("#sales_invoice_status").on("change", function(){
		if(order_process_user == 0 && $(this).val() == 1){
			message('danger|Order is not Processed yet. You can\'t mark order to Complete.');
			$(this).val(order_current_status);
		}
	});

	$(".orderaction").not(":last").not(".orderupdateaction").not(":first").each(function(e){
		$(this).attr('disabled', true).attr('title', 'Please complete previous steps');			
	});
	
	$(document).off('change', '#sales_invoice_is_outsourced_chk');
	$(document).on("change", "#sales_invoice_is_outsourced_chk", function(e){
		if($(this).is(":checked"))
			$(".sales_invoice_is_outsourced_box").show();
		else
			$(".sales_invoice_is_outsourced_box").hide();
	});


	<?php $employee = new Employee();?>
	var userOptions = '<?php echo $employee->getUserOption();?>';
	var userOutSourceUnit = '<?php echo $employee->getUserOption(15);?>';

	$('.orderaction').on("click", function(){
		var eventAction = $(this).attr('data-action');
		var user_reset  = typeof($(this).attr('data-reset')) != 'undefined' ? $(this).attr('data-reset'):0;
		var id = $("#sales_invoice_id").val();
		setPopup(id, "Assign Sales's "+eventAction+"ing user");
		var bodyHtml = '';
		bodyHtml += '<div class="col-xs-12"><div class="form-group"><label for="invoice_user_id">Select '+eventAction+'ing user<sup>*</sup></label> <select id="invoice_user_id" name="invoice_user_id" class="form-control" size="1">'+userOptions+'</select></div></div>';
		if(eventAction == 'pick'){
		bodyHtml += '<div class="col-xs-12"><div class="form-group"><label for="web_apply_same_user_to_pack_chk"><i class="icon-user fa-fw m-t-2"></i> &nbsp;Apply Same User for Pack Order ?</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="web_apply_same_user_to_pack_chk" value="1" name="web_apply_same_user_to_pack_chk" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div>';
		}
		if(eventAction == 'pack'){
		bodyHtml += '<div class="col-xs-12"><div class="form-group"><label for="sales_invoice_is_outsourced_chk"><i class="icon-bag fa-fw m-t-2"></i> &nbsp;is Fullfil from OutSource ?</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="sales_invoice_is_outsourced_chk" value="1" name="sales_invoice_is_outsourced_chk" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div> <div class="col-xs-12 sales_invoice_is_outsourced_box"><div class="form-group"><label for="sales_invoice_is_outsourced">Select Outsource unit<sup>*</sup></label> <select id="sales_invoice_is_outsourced" name="sales_invoice_is_outsourced" class="form-control" size="1">'+userOutSourceUnit+'</select></div></div>';
		}
		modal.Body(bodyHtml);
		modal.Footer('<button type="button" onclick="confirmMessage.Set(\'Are you sure to mark '+eventAction+' invoice ...?\', \'salesinvoiceaction\', {action :\''+eventAction+'\'});" class="btn btn-outline-success mt-0">Save</button><button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button><input type="hidden" id="user_reset" name="user_reset" value="'+user_reset+'">');
		modal.Show();
			
		if(eventAction == 'pack')			
		$(".sales_invoice_is_outsourced_box").hide();
	});

	$(".product_image_camera").on("click", function(){

	setPopup($(this).attr('data-id'), "Seelct product's image source");
	var bodyHtml = '<div class="col-md-12">';
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><label>From Media Gallery</label><span class="input-group-btn"><i class="fa fa-camera"></i> Upload File</span><input class="form-control" id="collection_record_image" name="collection_record_image" style="padding-bottom: 4px; padding-top: 4px; overflow:hidden;" maxlength="100" value="" type="file" onchange="uploadMediaFile({name:this.name});" multiple></div>';
	bodyHtml +='</div>';
		
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='------------- OR --------------';
	bodyHtml +='</div>';
		
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><div id="my_camera" style="margin:0px auto;"></div></div>';
	bodyHtml +='</div>';
		
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><button id="btn_start_camera" type="button" onClick="startCamera(0);" class="btn btn-sm btn-success mt-0"><i class="fa fa-camera-retro fa-fw mt-1 mb-1"></i> Start Cam</button> &nbsp; <button id="btn_capture_camera" disabled="disabled" type="button" onClick="take_snapshot();" class="btn btn-sm btn-success mt-0"><i class="fa fa-camera fa-fw mt-1 mb-1"></i> Take Snapshot</button> &nbsp; <button id="btn_switch_camera" disabled="disabled" type="button" onClick="switchCamera();" class="btn btn-sm btn-success mt-0"><i class="fa fa-camera fa-fw mt-1 mb-1"></i> Switch</button></div>';
	bodyHtml +='</div>';

	bodyHtml +='</div>';

	bodyHtml +='</div>';
	modal.Body(bodyHtml);
	modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();

	});

$(document).ready(function(){
	showMeIntro('intro-sales-invoice');
	<?php if(isset($sales_invoice_id) && $sales_invoice_id >0){?>
	appendPrevNext(<?php echo $SalesInvoice->getPrevNext($app->basePath('salesinvoice'));?>);
	<?php }?>
	$(".invoice_image_camera").on("click", function(){
	openMediaUploader($(this).attr('data-media-section'), $(this).attr('data-id'), "invoice_record_image", "Select Sales Invoice's image source", null);
	
	});
});
	
function uploadMediaFile(param)
{
	processUploadMediaFile(param.name, '_'+$("#keyid").val(), '<?=MediaSection::$SalesProductMedia?>');
	// if(confirm("Are you sure to upload this media file... ?"))
	// {
		// var files = _(field_name).files;
		// var formdata = new FormData(); 
		// if(files.length > 0)
		// {
			// for (var index = 0; index < files.length; index++) 
			// {
				// formdata.append('webcam[]', files[index]); 
			// }
			// formdata.append('sales_image_invoice_id', $("#keyid").val()); 
			// is_interval_running = false;
			// is_file_uploaded 	= false;
			// $("#media_uploaded_image_box_"+$("#keyid").val()).append('<div class="col-xs-12 col-lg-3"  id="item-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading image<br/><br/>Please wait</center></div>');

			// var ajax = new XMLHttpRequest();	
			// ajax.addEventListener("progress", progressMediaUpload, false); 
			// ajax.addEventListener("load", completeMediaUpload, false); 
			// ajax.open("POST", sitePath +"saveimage.php"); 
			// ajax.send(formdata);
		// }		
	// }	
}


function completeMediaUpload(event)
{
	var rc	=	JSON.parse(event.target.responseText);
	message(rc[1],2000);
	if(rc['image_id_array'].length > 0)
	{
		for(var i=0; i < rc['image_id_array'].length; i++){
			$("#media_uploaded_image_box_"+rc['id']).append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['image_id_array'][i]+'" id="item-invoice-media-container-'+rc['image_id_array'][i]+'"><div class="card"><div class="card-header card-success">Image<div class="card-actions"><a href="javascript:removeInvoiceMedia(\''+rc['image_id_array'][i]+'\', \''+rc['id']+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['image_path_array'][i]+'" /></div></div></div>');
		}
	}
	message('success|File uploaded', 1000);
	$("#item-web-order-product-media-processing").remove();	
	$("#media_uploaded_image_box_"+rc['id']+" .empty_image_box").remove();
}


function removeInvoiceMedia(id, sales_image_invoice_id)
{
	if(confirm("Are you sure to remove this media file... ?"))
	{
		var data={
			action		:	'sales/removeinvoicemedia',
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
					$("#item-invoice-media-container-"+id).remove();
					if($("#media_uploaded_image_box_"+sales_image_invoice_id+" div").length ==0)
					$("#media_uploaded_image_box_"+sales_image_invoice_id).html(EMPTY_IMAGE_BOX);
				}
				message(arr[1],2000);
			}
		})	
	}
	else
		$("#item-invoice-media-container-"+id+" .card").show();
}

function checkType()
{
	if($("#searched_product_type").val()==0)
	{
		$("#searched_product_type").focus();	
	}	
}

function addProductinRow()
{
	var product_name = $("#searched_product_name").val();
	var product_price = $("#searched_product_price").val();
	var product_quantity = $("#searched_product_quantity").val();
	var product_sku = $("#searched_product_sku").val();
	var product_batch_code = $("#searched_product_batch_code").val();
	var product_type_id = $("#searched_product_type").val();
	var product_type = $("#searched_product_type option:selected").text();
	if(product_name!="" && product_price!="" && product_quantity >0)
	{
		var p ={
			product_name : product_name,
			product_price : product_price,
			product_quantity : product_quantity,
			product_sku : product_sku,
			product_batch_code : product_batch_code,
			product_type_id : product_type_id,
			product_type : product_type,
			product_purchase_amount : 0,
			product_purchase_source : ''
		};
		addProductRow(0, p);	
	}
	else
		message("danger|Product Type, name, Quantity & Price all must be filled.");		
}

function addProductRow(sipd_id, p)
{
	var isSaved = sipd_id > 0;
	sipd_id = sipd_id > 0 ? sipd_id : $(".invoice_product_item").length + 1;
	p.product_sku = p.product_sku == '' ? '-' : p.product_sku;
	p.product_batch_code = p.product_batch_code == '' ? '-' : p.product_batch_code;
	var collectionHTML ='<div id="sales_product_'+sipd_id+'" class="col-xs-12 col-md-12 col-md-12 col-lg-12 invoice_product_item"><div class="card"><div class="card-header card-default">'+p.product_name+' ['+p.product_type+'] ['+p.product_sku+']'+(!isInvoiceGenerated ? '<div class="card-actions"><a class="btn-close btn-remove"><i class="icon-trash"></i></a></div>':'')+'</div><div class="card-block p-a-1 clearfix"><div class="row"><div class="col-xs-12 col-sm-6 col-lg-3"> <div class="form-group">  <label>Product Quantity<sup>*</sup></label>  <input class="form-control input_text_upper" name="sales_product_quantity[]"  placeholder="Product quantity" type="number" min="0" '+readonlyField+' value="'+p.product_quantity+'">  </div>  </div>  <div class="col-xs-12 col-sm-6 col-lg-3">  <div class="form-group">  <label>Product Batch Code<sup>*</sup></label>  <input class="form-control input_text_upper" name="sales_product_batch_code[]" maxlength="20" placeholder="Product batch code" type="text" value="'+p.product_batch_code+'">  </div>  </div>  <div class="col-xs-12 col-sm-6 col-lg-3">  <div class="form-group">  <label>Product SKU<sup>*</sup></label>  <input class="form-control input_text_upper metadataloader" name="sales_product_sku[]" maxlength="20" placeholder="Product SKU" type="text" value="'+p.product_sku+'">  </div>  </div>  <div class="col-xs-12 col-sm-6 col-lg-3">  	<div class="form-group">  <label>Product Price<sup>*</sup></label>  <input class="form-control" '+readonlyField+' name="sales_product_price[]"  placeholder="Product price" type="number" step="0.01" min="0" value="'+p.product_price+'"></div>  </div> <div class="col-xs-12 col-sm-6 col-lg-3">  	<div class="form-group">  <label>Purchase amount<sup>*</sup></label>  <input class="form-control"  name="sales_purchase_amount[]"  placeholder="Product purchase price" type="number" step="0.01" min="0" value="'+p.product_purchase_amount+'">  </div>  </div><div class="col-xs-12 col-sm-6 col-lg-3">  	<div class="form-group">  <label>Purchase source<sup>*</sup></label>  <input class="form-control" id="sales_purchase_source_'+sipd_id+'"  name="sales_purchase_source[]"  placeholder="Product purchase source name" onkeyup="getDropdown(this, \'SalesInvoiceProduct<=>sipd_purchase_source\')" type="text" maxlength="50" value="'+p.product_purchase_source+'">  </div>  </div>  <input type="hidden" name="sales_product_name[]" value="'+escape(p.product_name)+'"> <input type="hidden" name="sales_product_type_id[]" value="'+p.product_type_id+'"></div>'+(isSaved > 0 ?'<div class="row"><div class="col-sm-12"><hr></div><div class="col-xs-12 col-sm-6 col-lg-3"><div class="input-group"><input class="form-control input_text_upper" placeholder="Enter serial number" type="text" maxlength="50" value=""><span data-sipd-id="'+sipd_id+'" class="input-group-addon p-0 bg-info schedule_box btnaddserialnumber">&nbsp; <span class="fa fa-plus fa-fw"></span> ADD &nbsp; </span></div></div><div class="productserialnobox_'+sipd_id+'"></div></div>':'')+'</div></div></div>';	

	$("#searched_product_name").val('');
	$("#searched_product_price").val('');
	$("#searched_product_quantity").val('');
	$("#searched_product_sku").val('');
	$("#searched_product_batch_code").val('');
	$("#sales_product_item_list").append(collectionHTML);
	<?php
	if(isset($sales_invoice_id) && $sales_invoice_id != 0){?>
		$("#sales_product_"+ sipd_id + " .card .card-block").append('<div class="row"><div class="col-md-12"><div class="px-1 py-2"><a class="btn btn-info redirect" href="'+(sitePath + 'addcomplaintrequest/S/<?php echo $sales_invoice_id;?>/'+sipd_id)+'"><i class="fa-w icon-wrench"></i> Create RMA Request</a> &nbsp; <a data-order-id="<?php echo $sales_invoice_id?>" data-id="'+sipd_id+'" data-section="S" class="btn btn-warning my-2 product_availbility_check" href="javascript:void(0)" title="Check availability from supplier for '+p.product_name+'"><i class="fa fa-check-circle"></i> Add Avilability Check</a></div></div></div><div class="col-sm-12">'+getProductCheckList(sipd_id)+'</div>');
	<?php
	}
	?>
	
}

$(".contentbox").on("change", "#sales_invoice_is_paid", function(e){
	e.preventDefault();
	if($(this).is(":checked")){
		confirmMessage.Set('Are you sure to mark this invoice as completely paid and there is no due amount ?</br/><i class="text-warning fa fa-info-circle"></i> Invoice <b>Total paid amount</b> will be set equal to <b>Total invoice amount</b> automatically.</br/><i class="text-warning fa fa-info-circle"></i> Invoice will be mark as totally paid.', 'proceedtosetinvoiceamount', 1, 'declinedtosetinvoiceamount');
	}
});

function proceedtosetinvoiceamount(flag){
	$("#sales_invoice_is_paid").prop("checked", true);
}

function declinedtosetinvoiceamount(flag){
	$("#sales_invoice_is_paid").prop("checked", false);
}

$(".contentbox").on("click", ".btnaddserialnumber", function(e){ 
	var srno = $(this).prev(".form-control").val().trim();
	var sipd_id = $(this).attr("data-sipd-id");
	if(srno != ""){
		$(this).prev(".form-control").val('');
		updateSerialNumberLabel(1,sipd_id, srno);
	}
});

$(".contentbox").on("click", ".page_ticket_remove", function(e){
	e.preventDefault();
	$ele = $(this).parent();
	$ele.hide(500, function(){
		$ele.remove();
	});
	updateSerialNumberLabel(0, $(this).attr('data-sipd-id'), $(this).attr('data-srno'));
});


function updateSerialNumberLabel(type, sipd_id, srno){
	var data={
		action		:	'sales/updateslaesproductserial',
		srno		:	srno,
		type		:	type,
		sipd_id		: 	sipd_id
	};
	
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|"+(type == 1 ? "Adding":"Removing")+" product serial number...",0);
			dissableSubmission();
		},		
		success:function(output){
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				if(type)
				appendSrno(sipd_id, srno);
			}
			message(arr[1],2000);
		}
	});
	
}

function appendSrno(sipd_id, srno){
	$(".productserialnobox_"+sipd_id).append('<div class="ticket_box"><div class="ticket_text">'+srno+'</div><div title="Remove" class="page_ticket_remove ticket_remove" data-sipd-id="'+sipd_id+'" data-srno="'+srno+'">X</div><div class="clear"></div></div>');
}

function addSalesInvoice()
{
	var formFields	=	"customer_email, customer_phone, customer_type_id, customer_fname, customer_lname, customer_address_postcode, customer_address_street_number, customer_address_route, customer_address_administrative_area, customer_address_country, sales_invoice_store_id, sales_invoice_date";
	
	if(validateFields(formFields))
	{
		if($(".invoice_product_item").length == 0)
		{
			message("danger|Please select atleast one product");
			return false;
		}
		var data={
			action	:	$("#action").val()				
		};
		
		data = $.extend(data, $("#addsalesinvoice").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Submitting Sales Invoice...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#sales_invoice_id").val(arr[2]);
					$("#sales_invoice_customer_id").val(arr[3]);
					$("#sales_invoice_customer_address_id").val(arr[4]);
					$("#action").val('sales/updatesalesinvoice');
					$("#btn_action_name").text('Save');
					setTimeout(function(){Redirect('salesinvoice/'+arr[2])}, 2000);
				}
				message(arr[1],2000);
			}
		});
	}
}

function callExtraModule(eData, element)
{
	if(element.id == "customer_email")
	{
		$("#customer_phone").val(eData.customer_phone);
		$("#customer_type_id").val(eData.customer_type_id);
		$("#customer_fname").val(eData.customer_fname);
		$("#customer_lname").val(eData.customer_lname);
		$("#customer_company").val(eData.customer_company);
		$("#customer_tax_number").val(eData.customer_tax_number);
		$("#customer_address_postcode").val(eData.customer_address_postcode);
		$("#customer_address_street_number").val(eData.customer_address_street_number);
		$("#customer_address_route").val(eData.customer_address_route);
		$("#customer_address_locality").val(eData.customer_address_locality);
		$("#customer_address_administrative_area").val(eData.customer_address_administrative_area);
		$("#customer_address_country").val(eData.customer_address_country);
		$("#customer_address_geo_location").val(eData.customer_address_geo_location);
	}
	else if(element.id == "searched_product_name")
	{
		$("#searched_product_price").val(eData.product_price);
		$("#searched_product_quantity").val(1);
	}
}

function addNewProductTypeForm(id, title)
{
	setPopup(id, title);
	var bodyHtml = '<div class="col-md-12"><div class="row">';
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><label for="hardware_type_name">Hardware type name<sup>*</sup></label><input class="form-control" id="hardware_type_name"  onkeyup="getDropdown(this, \'HardwareType<=>hardware_name\',false)"  name="hardware_type_name" maxlength="50" placeholder="Write Hardware Type Name" type="text"></div>';
	bodyHtml +='</div>';
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><label for="hardware_code">Hardware Code<sup>*</sup></label><input class="form-control" id="hardware_code" onkeyup="this.value = this.value.toUpperCase()" onBlur="checkHardwarecode(this);" name="hardware_code" maxlength="3" placeholder="Write Hardware code" type="text"></div>';
	bodyHtml +='</div>';
	bodyHtml +='</div></div>';
	modal.Body(bodyHtml);
	modal.Footer('<button type="reset" class="btn btn-default" >Reset</button><button type="button" id="popupsubmit" onclick="submitProductTypeName();" class="btn btn-success" >Save</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
}
function submitProductTypeName()
{
	if(validateFields("hardware_type_name, hardware_code"))
	{
		var data={
					action	:	'system/addhardware',
					hardware_name	:	$("#hardware_type_name").val(),
					hardware_code	:	$("#hardware_code").val(),
					hardware_status	:	1,	
					getExtraOption	:	true						
				};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#searched_product_type").html(arr[3]);
					$("#searched_product_type").val(arr[2]);
				}
				else
				$("#hardware_code").val('');
				message(arr[1]);
			}
		})	
	}
}
function checkHardwarecode(field)
{
	if(validateFields("hardware_code") && validateHardwareCode(field))
	{
		var data={
					action	:	'system/iscodeavailable',
					hardware_code	:	$("#hardware_code").val()							
				};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				popmessage("process|Checking...");
			},		
			success:function(output){
				var arr	=	JSON.parse(output);			
				popmessage(arr[1]);
				if(arr[0]!=200)
				$("#hardware_code").val('');				
			}
		})	
	}
}

var invoiceProduct = <?php echo isset($products) ? json_encode($products): "null"; ?>;

$(document).ready(function(e) {
	loadGallery(true, 'a.thumbnail');
	if(invoiceProduct !=null && invoiceProduct.length>0)
	{
		for(var i = 0; i<invoiceProduct.length; i++) {
			var element = invoiceProduct[i];
			var p ={
				product_name : element.product_name,
				product_price : element.product_price,
				product_quantity : element.product_quantity,
				product_sku : element.product_srno,
				product_batch_code : element.product_batch_code,
				product_type_id : element.hardware_id,
				product_type : element.hardware_name,
				product_purchase_amount : element.sipd_purchase_amount,
				product_purchase_source : element.sipd_purchase_source == null ? '' : element.sipd_purchase_source
			};
		
			addProductRow(element.sipd_id, p);
			
			if(element.sipd_product_serial_details != null && element.sipd_product_serial_details != "")
			{
				var srnoArray = element.sipd_product_serial_details.split('|');
				if(srnoArray.length > 0){
					for(var j=0; j < srnoArray.length; j++){
						appendSrno(element.sipd_id, srnoArray[j]);
					}
				}
			}
		}
	}
	
	
	$("#sales_invoice_store_id").on("change", function(){
		var option = $(this).children("option:selected");
		var option_text = option.text();
		var vat_precent = option.attr('data-vat-percent');
		if(option.attr('data-vat-dissable') == 1)
		{
			$("#sales_invoice_is_vat_applicable").prop("checked", false);
			$("#vat_apply_message").html("<span class='text-danger'>"+option_text+" don't apply VAT. You can still apply</span>");
			
		}
		else
		{
			$("#sales_invoice_is_vat_applicable").prop("checked", true);
			$("#vat_apply_message").html("<span class='text-success'>"+option_text+" has "+vat_precent+"% VAT. You can still disable</span>");
		}		
	});
	
	$(".removepackinguser").on("click", function(){
		confirmMessage.Set('Are you sure to remove Packing User ...?', 'proceedRemoveInvoicePackingUSer');
	});
	
});

function proceedRemoveInvoicePackingUSer(){
	var data={
				action		:	'sales/removeinvoicepackinguser',
				sales_invoice_id	: 	$("#sales_invoice_id").val()
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
							Redirect('salesinvoice/'+data.sales_invoice_id);	
						}, 2000);
					}
					message(arr[1],2000);															   
				}
			});
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
			sales_image_invoice_id	:	$("#keyid").val()		
		};
		if(confirm("Are you sure to upload this media file... ?"))
		{
			$("#media_uploaded_image_box_"+$("#keyid").val()).append('<div class="col-xs-12 col-lg-3"  id="item-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading Snapshot<br/><br/>Please wait</center></div>');
			Webcam.upload( data_uri, parameter, sitePath+'saveimage.php', function(code, text) {
				var rc	=	JSON.parse(text);	
				$("#media_uploaded_image_box_"+rc['sales_image_invoice_id']).append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['sales_image_id']+'" id="item-invoice-media-container-'+rc['sales_image_id']+'"><div class="card"><div class="card-header card-success">Image<div class="card-actions"><a href="javascript:removeInvoiceMedia(\''+rc['sales_image_id']+'\', \''+rc['sales_image_invoice_id']+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['sales_image_path']+'" /></div></div></div>');
	$("#item-media-processing").remove();	
	$("#media_uploaded_image_box_"+rc['sales_image_invoice_id']+" .empty_image_box").remove();
			} );
		}
	} );
	
}
	
$(document).ready(function(e) {
	$(".media_uploaded_image_box").each(function(){
		if($(this).children().length == 0)
			$(this).html(EMPTY_IMAGE_BOX);
	});
});

</script> 
<script>
  // This example displays an address form, using the autocomplete feature
  // of the Google Places API to help users fill in the information.

  // This example requires the Places library. Include the libraries=places
  // parameter when you first load the API. For example:
  // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

  var placeSearch, autocomplete;
  var componentForm = {
	street_number: 'short_name',
	route: 'long_name',
	locality: 'long_name',
	administrative_area_level_1: 'long_name',
	country: 'long_name',
	postal_code: 'long_name'
  };
  
  var componentResolver = {
	  street_number: 'customer_address_street_number',
	  route	:	'customer_address_route',
	  locality: 'customer_address_locality',
	  administrative_area_level_1:	'customer_address_administrative_area',
	  country: 'customer_address_country',
	  postal_code:	'customer_address_postcode'
	};

  function initAutocomplete() {
	// Create the autocomplete object, restricting the search to geographical
	// location types.
	autocomplete = new google.maps.places.Autocomplete(
		/** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
		{types: ['geocode']});

	// When the user selects an address from the dropdown, populate the address
	// fields in the form.
	autocomplete.addListener('place_changed', fillInAddress);
  }

  function fillInAddress() {
	// Get the place details from the autocomplete object.
	var place = autocomplete.getPlace();

	for (var component in componentResolver) {
	  document.getElementById(componentResolver[component]).value = '';
	  document.getElementById(componentResolver[component]).disabled = false;
	}

	// Get each component of the address from the place details
	// and fill the corresponding field on the form.
	
	
	for (var i = 0; i < place.address_components.length; i++) {
	  var addressType = place.address_components[i].types[0];
	  if (componentResolver[addressType]) {
		var val = place.address_components[i][componentForm[addressType]];
		//document.getElementById(addressType).value = val;route
		//if(addressType=='street_number')
		//val += (" "+place.address_components[i+1][componentForm['route']]);
		document.getElementById(componentResolver[addressType]).value = val;
	  }
	  document.getElementById("customer_address_geo_location").value = place.geometry.location;
	}
  }

  // Bias the autocomplete object to the user's geographical location,
  // as supplied by the browser's 'navigator.geolocation' object.
  function geolocate() {
	if (navigator.geolocation) {
	  navigator.geolocation.getCurrentPosition(function(position) {
		var geolocation = {
		  lat: position.coords.latitude,
		  lng: position.coords.longitude
		};
		var circle = new google.maps.Circle({
		  center: geolocation,
		  radius: position.coords.accuracy
		});
		autocomplete.setBounds(circle.getBounds());
	  });
	}
  }
</script> 
<?php echo GOOGLE_MAP_API_SCRIPT;?>