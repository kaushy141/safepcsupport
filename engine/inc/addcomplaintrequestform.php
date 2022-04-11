<?php Modal::load(array('Returns'));   ?>
<div class="row">
<?php if(isset($complaint_id) && $complaint_id > 0 && $complaint_is_returned >0){?>
<div class="col-sm-12">
    <div class="card">
	<?php 
	$return = new Returns($complaint_is_returned);
	echo $return->getReturnNotice("RMA $complaint_ticket_number");	
	?>
	</div>
</div>
<?php }?>
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"><i class="fa fa-align-justify"></i> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small>
        <div class="card-actions">
          <?php if(isset($complaint_id) && $complaint_id!=0):?>
          <a data-title="Chat with <?=isset($customer_fname)?$customer_fname:"Customer";?>" title="Chat with <?=isset($customer_fname)?$customer_fname:"Customer";?>" class="dropdown-item" href="#" data-toggle="modal" data-target="#appModal" onClick="openChatLogForm('<?=$complaint_id?>|C', '<?=$complaint_ticket_number?> Log Report')"><i class="fa fa-wechat fa-lg m-t-2" style="color:#26BF32;"></i></a>
          <?php endif; ?>
        </div>
      </div>
      <form id="addcomplaint" name="addcomplaint">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_email">Customer Email<sup>*</sup></label>
                <input class="form-control" data-label="Customer email" id="customer_email" name="customer_email" maxlength="50" placeholder="Enter customer Email id"  onkeyup="getDropdown(this, 'Customer<=>customer_email',true)" type="email" value="<?=isset($customer_email)?$customer_email:"";?>" <?php if(isset($customer_email) && isset($complaint_id) && $complaint_id >0 && $customer_email !="") echo " readonly=\"readonly\"";?> >
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_phone">Customer Phone Number<sup>*</sup></label>
                <input class="form-control"data-label="Customer phone number" id="customer_phone" name="customer_phone" maxlength="20" placeholder="Enter customer phone number" type="tel" value="<?=isset($customer_phone)?$customer_phone:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_type_id">Customer Type<sup>*</sup></label>
                <select id="customer_type_id" data-label="Customer type" name="customer_type_id" class="form-control" size="1">
                  <?php
                $CustomerType = new CustomerType(0);
				echo $CustomerType->getOptions(isset($customer_type_id)?$customer_type_id:"0");
				?>
                </select>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_fname">Customer First Name<sup>*</sup></label>
                <input class="form-control" data-label="Customer first name" id="customer_fname" name="customer_fname" maxlength="50" placeholder="Enter customer first name" type="text" value="<?=isset($customer_fname)?$customer_fname:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_lname">Customer Last Name<sup>*</sup></label>
                <input class="form-control" data-label="Customer last name" id="customer_lname" name="customer_lname" maxlength="50" placeholder="Enter customer Last name" type="text" value="<?=isset($customer_lname)?$customer_lname:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_company">Company Name </label>
                <input class="form-control" data-label="Company name" id="customer_company" name="customer_company" maxlength="150" placeholder="Enter Company name" type="text" value="<?=isset($customer_company)?$customer_company:"";?>">
              </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="customer_tax_number input_text_upper">Tax Id/Vat Number </label>
                <input class="form-control" data-label="Customer ID/Vat number" id="customer_tax_number" name="customer_tax_number" maxlength="50" placeholder="Enter Tax Id/Vat Number" type="text" value="<?=isset($customer_tax_number)?$customer_tax_number:"";?>">
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
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_address_street_number">Street Number<sup>*</sup></label>
                <input class="form-control" data-label="Address street number" id="customer_address_street_number" name="customer_address_street_number" maxlength="100" placeholder="Enter Address street number" type="text" value="<?=isset($customer_address_street_number)?$customer_address_street_number:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_address_route">Address Route<sup>*</sup></label>
                <input class="form-control" id="customer_address_route" name="customer_address_route" maxlength="100" placeholder="Enter Address route" type="text" value="<?=isset($customer_address_route)?$customer_address_route:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_address_locality">Address Locality<sup></sup></label>
                <input class="form-control" id="customer_address_locality" name="customer_address_locality" maxlength="100" placeholder="Enter Address locality" type="text" value="<?=isset($customer_address_locality)?$customer_address_locality:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_address_administrative_area">State (Administrative Area)<sup>*</sup></label>
                <input class="form-control" id="customer_address_administrative_area" name="customer_address_administrative_area" maxlength="100" placeholder="Enter State name" type="text" value="<?=isset($customer_address_administrative_area)?$customer_address_administrative_area:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_address_country">Country Name<sup>*</sup></label>
                <input class="form-control" id="customer_address_country" name="customer_address_country" maxlength="100" placeholder="Enter Country name" type="text" value="<?=isset($customer_address_country)?$customer_address_country:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="customer_address_postcode">PostCode <sup>*</sup></label>
                <input class="form-control input_text_upper" id="customer_address_postcode" name="customer_address_postcode" maxlength="10" placeholder="Enter postcode" type="text" value="<?=isset($customer_address_postcode)?$customer_address_postcode:"";?>">
              </div>
            </div>
            <input class="form-control" id="customer_address_geo_location" name="customer_address_geo_location" readonly maxlength="100" type="hidden" value="<?=isset($customer_address_geo_location)?$customer_address_geo_location:"";?>">
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="complaint_description">Request Description<sup>*</sup></label>
                <textarea id="complaint_description" name="complaint_description" rows="4" class="form-control" placeholder="Enter Complaint description"><?=isset($complaint_description)?$complaint_description:"";?>
</textarea>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="complaint_problem_details">Request Problem Details<sup>*</sup></label>
                <textarea id="complaint_problem_details" name="complaint_problem_details" rows="4" class="form-control" placeholder="Enter Complaint Problem Details"><?=isset($complaint_problem_details)?$complaint_problem_details:"";?>
</textarea>
              </div>
            </div>
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="">Mark Product Problem(s)
                  <button type="button" class="btn btn-info btn-sm"  data-toggle="modal" data-target="#appModal" onClick="addProblemNameForm(1, 'Add New Product Problem')">Add New</button>
                </label>
                <div class="col-sm-12">
                  <div class="row" id="app_not_working_problem_mark_box">
                    <?php
                $HardwareProblem = new HardwareProblem(0);
				$app_not_working_problem_mark_array = array();
				if(isset($app_not_working_problem_mark))
				$app_not_working_problem_mark_array = explode(",",$app_not_working_problem_mark);
				echo $HardwareProblem->getCheckbox("app_not_working_problem_mark",$app_not_working_problem_mark_array);
				?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_product_serial">Product Serial Number<sup>*</sup></label>
                <input class="form-control input_text_upper metadataloader" id="complaint_product_serial" name="complaint_product_serial" maxlength="50" placeholder="Enter Product Serial Number" type="text" value="<?=isset($complaint_product_serial)?$complaint_product_serial:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_product_sku">Product SKU<sup>*</sup></label>
                <input class="form-control input_text_upper metadataloader" id="complaint_product_sku" name="complaint_product_sku" maxlength="50" placeholder="Enter Product SKU" type="text" value="<?=isset($complaint_product_sku)? $complaint_product_sku:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_product_model">Product Model Number</label>
                <input class="form-control" id="complaint_product_model" name="complaint_product_model" maxlength="50" onKeyUp="getDropdown(this, 'Complaint<=>complaint_product_model')" placeholder="Enter Product Model Number" type="text" value="<?=isset($complaint_product_model)?$complaint_product_model:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_product_password">Product Password</label>
                <input class="form-control" id="complaint_product_password" name="complaint_product_password" maxlength="50" placeholder="Enter Product Password" type="text" value="<?=isset($complaint_product_password)?$complaint_product_password:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_product_operating_system">Operating System</label>
                <input class="form-control" id="complaint_product_operating_system" name="complaint_product_operating_system"  onkeyup="getDropdown(this, 'Complaint<=>complaint_product_operating_system')" maxlength="100" placeholder="Enter Operating System name" type="text" value="<?=isset($complaint_product_operating_system)?$complaint_product_operating_system:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_product_antivirus">Product Antivirus</label>
                <input class="form-control" id="complaint_product_antivirus" name="complaint_product_antivirus" onKeyUp="getDropdown(this, 'Complaint<=>complaint_product_antivirus')" maxlength="100" placeholder="Enter Product Antivirus" type="text" value="<?=isset($complaint_product_antivirus)?$complaint_product_antivirus:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_due_date">Due Date<sup>*</sup></label>
                <div class="input-group date">
                  <input type='text' class="form-control" id="complaint_due_date" name="complaint_due_date" placeholder="YYYY-MM-DD" value="<?=isset($complaint_due_date)?date("Y-m-d", strtotime($complaint_due_date)):date('Y-m-d');?>" />
                  <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="complaint_due_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                  </span> </div>
                <script type="text/javascript">
                    $('#complaint_due_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,
						<?php if(!isset($complaint_id))echo "startDate  : '".date('Y-m-d')."',";?>						
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_order_number">Related Order number</label>
                <input class="form-control" id="complaint_order_number" name="complaint_order_number" maxlength="20" placeholder="Enter Related Order Number" type="text" value="<?=isset($complaint_order_number)?$complaint_order_number:"";?>">
              </div>
            </div>
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="complaint_description">Mark Hardware not working</label>
                <div class="col-sm-12">
                  <div class="row">
                    <?php
                $HardwareType = new HardwareType(0);
				$complaint_product_hardware_not_working_array = array();
				if(isset($complaint_product_hardware_not_working))
				$complaint_product_hardware_not_working_array = explode(",",$complaint_product_hardware_not_working);
				echo $HardwareType->getCheckbox("complaint_product_hardware_not_working",$complaint_product_hardware_not_working_array);
				?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_sales_record_number">Ebay Sales Record Number</label>
                <input class="form-control" id="complaint_sales_record_number" name="complaint_sales_record_number" maxlength="11" onKeyUp="getDropdown(this, 'EbaySaleData<=>SalesRecordNumber')" placeholder="Search by eBay Sales number or eBay Customer name" type="text" value="<?=isset($complaint_sales_record_number)?$complaint_sales_record_number:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_priority">Priority Value From 1 to 10</label>
                <input class="form-control" id="complaint_priority" name="complaint_priority" maxlength="10" placeholder="Enter Request Priority" max="10" type="number" value="<?=isset($complaint_priority)?$complaint_priority:"0";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_estimated_cost">Estimated Cost<sup>*</sup></label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-gbp"></i></span>
                  <input id="complaint_estimated_cost" name="complaint_estimated_cost" class="form-control" placeholder=".." type="text" min="0" max="9999999" value="<?=isset($complaint_estimated_cost)?$complaint_estimated_cost:"";?>">
                  <span class="input-group-addon">.00</span> </div>
              </div>
            </div>
          </div>
          
          <!--/row-->
          
          <div class="row">
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_is_backup">Product Backuped &nbsp; <i class="fa fa-history fa-lg m-t-2"></i></label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="complaint_is_backup" value="1" name="complaint_is_backup" type="checkbox" <?=(isset($complaint_is_backup) && $complaint_is_backup)?"checked":"";?> >
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_is_disk_provided">Disk Provided &nbsp; <i class="fa fa-hdd-o fa-lg m-t-2"></i></label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="complaint_is_disk_provided" value="1" name="complaint_is_disk_provided" type="checkbox" <?=(isset($complaint_is_disk_provided) && $complaint_is_disk_provided)?"checked":"";?>>
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_product_is_under_waranty">Under Waranty &nbsp; <i class="fa fa-umbrella fa-lg m-t-2"></i></label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="complaint_product_is_under_waranty" value="1" name="complaint_product_is_under_waranty" type="checkbox" <?=(isset($complaint_product_is_under_waranty) && $complaint_product_is_under_waranty)?"checked":"";?>>
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
          </div>
          <!--/row-->
          
          <div class="row">
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_technician_id">Select Technician<sup>*</sup></label>
                <select id="complaint_technician_id" name="complaint_technician_id" class="form-control" size="1">
                  <?php
                $Employee = new Employee(0);
				echo $Employee->getTechnicianOption(isset($complaint_technician_id)?$complaint_technician_id:"0");
				?>
                </select>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_status">Complain Status<sup>*</sup></label>
                <select id="complaint_status" name="complaint_status" class="form-control" size="1">
                  <?php
                $ComplaintStatus = new ComplaintStatus(0);
				echo $ComplaintStatus->getOptions(isset($complaint_status)?$complaint_status:"0");
				?>
                </select>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_refund_status">Refund Status<sup></sup></label>
                <select id="complaint_refund_status" name="complaint_refund_status" class="form-control" size="1">
                  <option value=""> -- </option>
                  <option value="Requested" <?php if(isset($complaint_refund_status) && $complaint_refund_status == 'Requested') echo 'selected';?>>Requested</option>
                  <option value="Completed" <?php if(isset($complaint_refund_status) && $complaint_refund_status == 'Completed') echo 'selected';?>>Completed</option>
                </select>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_product_condition_at_receiving">Product Condition<sup>*</sup></label>
                <input class="form-control" id="complaint_product_condition_at_receiving" name="complaint_product_condition_at_receiving"  onkeyup="getDropdown(this, 'Complaint<=>complaint_product_condition_at_receiving')" placeholder="Enter Product Condition" type="text" value="<?=isset($complaint_product_condition_at_receiving)?$complaint_product_condition_at_receiving:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_store_id">Select Purchase Store<sup>*</sup></label>
                <select id="complaint_store_id" name="complaint_store_id" class="form-control" size="1">
                  <?php
                $store = new Store(0);
				echo $store->getOptions(isset($complaint_store_id)?$complaint_store_id:"0");
				?>
                </select>
              </div>
            </div>
			<div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="complaint_dispatched_store">Select Dispatched Location<sup></sup></label>
                <select id="complaint_dispatched_store" name="complaint_dispatched_store" class="form-control" size="1">
                  <?php
                $storeLocation = new StoreLocation(0);
				echo $storeLocation->getStoreLocationOptions(isset($complaint_dispatched_store)?$complaint_dispatched_store:"", "Dispatch");
				?>
                </select>
              </div>
            </div>
          </div>
          <?php if(isset($complaint_id) && $complaint_id!=0):?>
          <div class="row">
            <div class="col-md-12" style="margin-top: 1px;">
              <div class="card">
                <div class="card-header card-default"><i class="fa fa-file-text"></i> Repair Media Files
                  <div class="card-actions"> <a data-id="<?php echo $complaint_id?>" data-image-type="0"  data-media-section="<?=MediaSection::$repairMedia?>" class="dropdown-item complaint_image_camera" href="#" title="Take image for <?php echo $complaint_ticket_number?> repair request"><i class="fa fa-camera text-danger"></i></a> </div>
                </div>
                <div class="card-block">
                  <div class="row media_uploaded_image_box media_uploaded_image_box_<?php echo $complaint_id;?>_0" id="media_uploaded_image_box_<?php echo $complaint_id;?>">
                    <?php 
					  $iCounter = 0;
					  $cm = new ComplaintMedia(0);
					  $recorded_snapshot_list = $cm->getImageList($complaint_id, 0);
					  if($recorded_snapshot_list):
					  foreach($recorded_snapshot_list as $itemCapt):
						  $iCounter++;
					  ?>
                    <div class="col-xs-6 col-sm-4 col-lg-3 thumb" data-record-id="<?php echo $itemCapt['repair_image_id']; ?>" id="item-complaint-media-container-<?php echo $itemCapt['repair_image_id']; ?>">
                      <div class="card">
                        <div class="card-header card-default">Image <?php echo $iCounter;?>
                          <div class="card-actions"><a href="javascript:removeComplaintMedia('<?php echo $itemCapt['repair_image_id']; ?>', '<?php echo $complaint_id; ?>');"><i class="icon-close"></i></a></div>
                        </div>
                        <div class="card-block p-a-1 clearfix"> <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="Image <?php echo $iCounter;?>" data-image="<?php echo $app->basePath($itemCapt['repair_image_path']); ?>" data-target="#image-gallery"> <img class="img-thumbnail" src="<?php echo getResizeImage($itemCapt['repair_image_path'], 100); ?>" alt="Image <?php echo $iCounter;?>"> </a>
                          <div class="font-weight-normal  text-center"><?php echo $itemCapt['repair_image_title']; ?></div>
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
          <div class="row">
            <div class="col-md-12" style="margin-top: 1px;">
              <div class="card">
                <div class="card-header card-default"><i class="fa fa-file-text"></i> Repair Returned Files
                  <div class="card-actions"> <a data-id="<?php echo $complaint_id?>" data-image-type="1" data-media-section="<?=MediaSection::$repairMedia?>" class="dropdown-item complaint_image_camera" href="#" title="Take image for <?php echo $complaint_ticket_number?> repair request"><i class="fa fa-camera text-danger"></i></a> </div>
                </div>
                <div class="card-block">
                  <div class="row media_uploaded_image_box media_uploaded_image_box_<?php echo $complaint_id;?>_1" id="media_uploaded_image_box_<?php echo $complaint_id;?>">
                    <?php 
					  $iCounter = 0;
					  $cm = new ComplaintMedia(0);
					  $recorded_snapshot_list = $cm->getImageList($complaint_id, 1);
					  if($recorded_snapshot_list):
					  foreach($recorded_snapshot_list as $itemCapt):
						  $iCounter++;
					  ?>
                    <div class="col-xs-6 col-sm-4 col-lg-3 thumb" data-record-id="<?php echo $itemCapt['repair_image_id']; ?>" id="item-complaint-media-container-<?php echo $itemCapt['repair_image_id']; ?>">
                      <div class="card">
                        <div class="card-header card-default">Image <?php echo $iCounter;?>
                          <div class="card-actions"><a href="javascript:removeComplaintMedia('<?php echo $itemCapt['repair_image_id']; ?>', '<?php echo $complaint_id; ?>');"><i class="icon-close"></i></a></div>
                        </div>
                        <div class="card-block p-a-1 clearfix"> <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="Image <?php echo $iCounter;?>" data-image="<?php echo $app->basePath($itemCapt['repair_image_path']); ?>" data-target="#image-gallery"> <img class="img-thumbnail" src="<?php echo getResizeImage($itemCapt['repair_image_path'], 100); ?>" alt="Image <?php echo $iCounter;?>"> </a>
                          <div class="font-weight-normal  text-center"><?php echo $itemCapt['repair_image_title']; ?></div>
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
		  <div class="row">
            <div class="col-md-12 mt-1">
              <div class="card">
                <div class="card-header card-default"><i class="fa fa-file-text"></i> Repair Replaced/repaired Files
                  <div class="card-actions"> <a data-id="<?php echo $complaint_id?>" data-image-type="2" data-media-section="<?=MediaSection::$repairMedia?>" class="dropdown-item complaint_image_camera" href="#" title="Take image for <?php echo $complaint_ticket_number?> repair request"><i class="fa fa-camera text-danger"></i></a> </div>
                </div>
                <div class="card-block">
                  <div class="row media_uploaded_image_box media_uploaded_image_box_<?php echo $complaint_id;?>_2" id="media_uploaded_image_box_<?php echo $complaint_id;?>">
                    <?php 
					  $iCounter = 0;
					  $cm = new ComplaintMedia(0);
					  $recorded_snapshot_list = $cm->getImageList($complaint_id, 2);
					  if($recorded_snapshot_list):
					  foreach($recorded_snapshot_list as $itemCapt):
						  $iCounter++;
					  ?>
                    <div class="col-xs-6 col-sm-4 col-lg-3 thumb" data-record-id="<?php echo $itemCapt['repair_image_id']; ?>" id="item-complaint-media-container-<?php echo $itemCapt['repair_image_id']; ?>">
                      <div class="card">
                        <div class="card-header card-default">Image <?php echo $iCounter;?>
                          <div class="card-actions"><a href="javascript:removeComplaintMedia('<?php echo $itemCapt['repair_image_id']; ?>', '<?php echo $complaint_id; ?>');"><i class="icon-close"></i></a></div>
                        </div>
                        <div class="card-block p-a-1 clearfix"> <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="Image <?php echo $iCounter;?>" data-image="<?php echo $app->basePath($itemCapt['repair_image_path']); ?>" data-target="#image-gallery"> <img class="img-thumbnail" src="<?php echo getResizeImage($itemCapt['repair_image_path'], 100); ?>" alt="Image <?php echo $iCounter;?>"> </a>
                          <div class="font-weight-normal  text-center"><?php echo $itemCapt['repair_image_title']; ?></div>
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
		  <div class="row">
			<div class="col-md-12 mt-1">
				 <div class="card">
					<div class="card-header"><i class="fa fa-file-text"></i> Repair Action</div>
					<div class="card-block">
					  <div class="row py-0">
						<div class="col-xs-12 col-sm-6 col-md-3">
						  <?php
					  if($complaint_status != 8 && $complaint_cancel_user == 0 && $complaint_picking_user){
						  $employee = new Employee($complaint_picking_user);
						  $picUserData = $employee->getDetails();
					  ?>
						  <div class="card">
							<div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
							  <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($picUserData["user_image"],50)?>"> </div>
							  <div class="px-2 justify-content-center">
								<div class="text-value-sm text-primary text-center">Picked by <?php echo $picUserData['user_fname']?> <?php echo $picUserData['user_lname']?></div>
								<div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($complaint_picking_time))?></div>
							  </div>
							</div>
						  </div>
						  <?php
					  }elseif($complaint_status != 8 && $complaint_cancel_user == 0){?>
						  <div class="card">
							<div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="pick" class="orderaction btn btn-outline-warning btn-block">STEP 1: PICK REPAIR</a> </div>
						  </div>
						  <?php
					  }
					  else{
					?>
						  <div class="card">
							<div class="card-body p-0 text-center p-2">
							  <div class="alert alert-warning mb-0" role="alert"> Repair Picking cancelled </div>
							</div>
						  </div>
						  <?php	  
					  }
					  ?>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3">
						  <?php
					  if($complaint_packing_user){
						  $employee = new Employee($complaint_packing_user);
						  $packUserData = $employee->getDetails();
					  ?>
						  <div class="card">
							<div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
							  <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($packUserData["user_image"],50)?>"> </div>
							  <div class="px-2 justify-content-center">
								<div class="text-value-sm text-primary text-center">Packed by <?php echo $packUserData['user_fname']?> <?php echo $packUserData['user_lname']?></div>
								<div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($complaint_packing_time))?></div>
							  </div>
							</div>
							<?php if($complaint_process_user == 0){?>
							<div class="card-body p-0 d-flex align-items-center  justify-content-center p-1">
							  <div class="card-body p-0 d-flex align-items-center  justify-content-between"> <a data-reset="<?php echo md5($complaint_packing_user);?>" data-action="pack" class="orderaction orderupdateaction btn btn-sm btn-info">UPDATE PACKING USER</a> </div>
							</div>
							<?php }?>
						  </div>
						  <?php
					  }elseif($complaint_status != 8 && $complaint_cancel_user == 0){?>
						  <div class="card">
							<div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="pack" class="orderaction btn btn-outline-info btn-block">STEP 2: PACK REPAIR</a> </div>
						  </div>
						  <?php
					  }
					  else{
					?>
						  <div class="card">
							<div class="card-body p-0 text-center p-2">
							  <div class="alert alert-warning mb-0" role="alert"> Repair Packing cancelled </div>
							</div>
						  </div>
						  <?php	  
					  }
					  ?>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3">
						  <?php
					  if($complaint_process_user){
						  $employee = new Employee($complaint_process_user);
						  $procUserData = $employee->getDetails();
					  ?>
						  <div class="card">
							<div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
							  <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($procUserData["user_image"],50)?>"> </div>
							  <div class="px-2 justify-content-center">
								<div class="text-value-sm text-primary text-center">Processed by <?php echo $procUserData['user_fname']?> <?php echo $procUserData['user_lname']?></div>
								<div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($complaint_process_time))?></div>
							  </div>
							</div>
						  </div>
						  <?php
					  }elseif($complaint_status != 8 && $complaint_cancel_user == 0){?>
						  <div class="card">
							<div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="process" class="orderaction btn btn-outline-success btn-block">STEP 3: PROCESS REPAIR</a> </div>
						  </div>
						  <?php
					  }
					  else{
					?>
						  <div class="card">
							<div class="card-body p-0 text-center p-2">
							  <div class="alert alert-warning mb-0" role="alert"> Repair Process cancelled </div>
							</div>
						  </div>
						  <?php	  
					  }
					  ?>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3">
						  <?php
					  if($complaint_status == 8 && $complaint_cancel_user){
						  $employee = new Employee($complaint_cancel_user);
						  $proccancelData = $employee->getDetails();
					  ?>
						  <div class="card">
							<div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
							  <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($proccancelData["user_image"],50)?>"> </div>
							  <div class="px-2 justify-content-center">
								<div class="text-value-sm text-primary text-center">Cancelled by <?php echo $proccancelData['user_fname']?> <?php echo $proccancelData['user_lname']?></div>
								<div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($complaint_cancel_time))?></div>
							  </div>
							</div>
						  </div>
						  <?php
					  }else{?>
						  <div class="card">
							<div class="card-body p-0 d-flex align-items-center  justify-content-between p-2"> <a data-action="cancel" class="orderaction btn btn-outline-danger btn-block">CANCEL REPAIR</a> </div>
						  </div>
						  <?php
					  }
					  ?>
						</div>
					  </div>
					</div>
				  </div>
				  <div class="card">
					<div class="card-header"><i class="fa fa-file-text"></i> Repair Invoice/Label</div>
					<div class="card-block">
					  <div class="row">
						<?php if($complaint_process_user == 0 || isAdminRole()){?>					
						<div class="col-xs-12 col-sm-6 col-md-4">
						  <div class="card complaint_label_image_container">
							<div class="card-body p-0 d-flex align-items-center  justify-content-between p-1">
							  <div class="pl-0 justify-content-start"> <i class="fa fa-file-pdf-o text-danger fa-2x"></i> </div>
							  <div class="px-2 justify-content-center">
								<div class="text-value-sm"><a onClick="openLabelUploader('complaint_label_image', '<?php echo $complaint_id;?>', 'Label');" class="btn btn-info btn-block px-1">Upload Shipment Label </a>								  
								</div>
							  </div>
							</div>
							<div class="px-2 pb-3"> Label can be uploaded till the RMA is not Processed </div>
						  </div>
						</div>
						<?php }?>
						<?php 
						 Modal::load(array('ComplaintLabels'));
						 $complaintLabels = new ComplaintLabels();
						 $shipmentRecords = $complaintLabels->getComplaintShipmentLabel(isset($complaint_id)?$complaint_id:0);
						if(count($shipmentRecords))
						{
							$lCounter = 0;
							foreach($shipmentRecords as $_slabel)
							{
								echo getShipmentLabelBlock($_slabel, array('action' => 'repaircancellabel')); 						
							}
						}
						?>
					  </div>
					</div>
				  </div>
			</div>
		  </div>
          
          <?php endif;?>
          <?php if(isAdmin()):?>
          <div class="row">
            <div class="col-md-12 mt-1 text-right">
              <?php 
			$creator = new Employee((isset($complaint_created_by) && $complaint_created_by != 0 ) ? $complaint_created_by : getLoginId());
			$creatorData = $creator->getDetails();
			$complaint_created_date = isset($complaint_created_date) ? $complaint_created_date : date('Y-m-d H:i:s');
			?>
              <div class="pull-right pl-1"><img class="img img-circle" style="margin-top:0px; margin-bottom:0px; height:40px;" src="<?php echo getResizeImage($creatorData["user_image"],50)?>"/></div>
              <div class="pull-right">Originally created by <?php echo $creatorData['user_name']?> <i class="fa fa-check-circle text-success"></i><br/>
                <span class="text-muted" style="font-size: 0.9em;"><?php echo dateView($complaint_created_date, 'NOW')?> <?php echo dateView($complaint_created_date, 'FULL')?></span> </div>
            </div>
          </div>
          <?php endif;?>
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-outline-danger"><i class="fa fa-refresh m-t-2"></i> Reset</button>
          <button type="button" id="btn_complaint_submit" onClick="rmaFormCheck()" class="btn btn-success submission_handler_btn"><i class="fa fa-check m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span> Request </button>
          <?php if(isset($complaint_id) && $complaint_id!=0):?>
          <a href="#"  data-toggle="modal" data-target="#appModal"  class="btn btn-primary" onClick="openChatLogForm('<?=$complaint_id?>|C', '<?=$complaint_ticket_number?> Log Report')">Comments</a>
          <?php endif;?>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"repair/addcomplaint";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="complaint_id" name="complaint_id" value="<?=isset($complaint_id)?$complaint_id:"0";?>"  />
        <input type="hidden" id="customer_id" name="customer_id" value="<?=isset($customer_id)?$customer_id:"0";?>"  />
        <input type="hidden" id="customer_address_id" name="customer_address_id" value="<?=isset($customer_address_id)?$customer_address_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
var mediaSection = '<?=MediaSection::$repairLabelInvoiceMedia?>';
function completeLabelHandler(e){
	var arr	=	JSON.parse(e.target.response);	
	message(arr[1],2000);
	setTimeout(function(){
		Redirect('updatecomplaintrequest/'+$("#complaint_id").val());	
	}, 2000);
}
$(document).off('change', '#complaint_status');
$("#complaint_status").on("change", function(){
	if(typeof(order_process_user) != 'undefined' && order_process_user == 0 && $(this).val() == 1){
		message('danger|Order is not Processed yet. You can\'t mark order to Complete.');
		$(this).val(order_current_status);
	}
});

$(".orderaction").not(":last").not(".orderupdateaction").not(":first").each(function(e){
	$(this).attr('disabled', true).attr('title', 'Please complete previous steps');			
});

$(document).off('change', '#complaint_is_outsourced_chk');
$(document).on("change", "#complaint_is_outsourced_chk", function(e){
	if($(this).is(":checked"))
		$(".complaint_is_outsourced_box").show();
	else
		$(".complaint_is_outsourced_box").hide();
});

<?php $employee = new Employee();?>
var userOptions = '<?php echo $employee->getUserOption();?>';
var userOutSourceUnit = '<?php echo $employee->getUserOption(15);?>';

function repaircancellabel(label_id){
	if(<?php echo isset($complaint_status) ? $complaint_status : 0?> == 2){
		message("danger|Repair request completed can't cancel label.");
		return;
	}
	var data={
					action		:	'repair/repairordercancellabel',
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


function complaintaction(eventAction){
								   
	var data={
					action				:	'repair/complaintaction',
					order_action		:	eventAction,
					complaint_id		: 	$('#complaint_id').val(),
					user_id 			:   $("#order_user_id").val(),
					user_reset			:	$("#user_reset").val()
									
			};
	if(eventAction == 'pick'){
		if($("#web_apply_same_user_to_pack_chk").is(":checked"))
		{
			data.same_user_to_pack_order = 1;
		}
	}
	if(eventAction == 'pack'){
		if($("#complaint_is_outsourced_chk").is(":checked"))
		{		   
										  
			if($("#complaint_is_outsourced").val() == 0){
				message("danger|Please select outsource unit");
				return false;																				   
			}
			data.complaint_is_outsourced = $("#complaint_is_outsourced").val();
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
					Redirect('updatecomplaintrequest/'+data.complaint_id);	
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
$(document).off('click', '.orderaction');
$('.orderaction').on("click", function(){
	var eventAction = $(this).attr('data-action');
	var user_reset  = typeof($(this).attr('data-reset')) != 'undefined' ? $(this).attr('data-reset'):0;
	var id = $("#complaint_id").val();
	setPopup(id, "Assign RMA Repair's "+eventAction+"ing user");
	var bodyHtml = '';
	bodyHtml += '<div class="col-xs-12"><div class="form-group"><label for="order_user_id">Select '+eventAction+'ing user<sup>*</sup></label> <select id="order_user_id" name="order_user_id" class="form-control" size="1">'+userOptions+'</select></div></div>';
	if(eventAction == 'pick'){
	bodyHtml += '<div class="col-xs-12"><div class="form-group"><label for="web_apply_same_user_to_pack_chk"><i class="icon-user fa-lg m-t-2"></i> &nbsp;Apply Same User for Pack Order ?</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="web_apply_same_user_to_pack_chk" value="1" name="web_apply_same_user_to_pack_chk" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div>';
	}
	if(eventAction == 'pack'){
	bodyHtml += '<div class="col-xs-12"><div class="form-group"><label for="complaint_is_outsourced_chk"><i class="icon-bag fa-lg m-t-2"></i> &nbsp;is Fullfil from OutSource ?</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="complaint_is_outsourced_chk" value="1" name="complaint_is_outsourced_chk" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div> <div class="col-xs-12 complaint_is_outsourced_box"><div class="form-group"><label for="complaint_is_outsourced">Select Outsource unit<sup>*</sup></label> <select id="complaint_is_outsourced" name="complaint_is_outsourced" class="form-control" size="1">'+userOutSourceUnit+'</select></div></div>';
	}
	modal.Body(bodyHtml);
	modal.Footer('<button type="button" onclick="confirmMessage.Set(\'Are you sure to mark '+eventAction+' RMA Reapir ...?\', \'complaintaction\', \''+eventAction+'\');" class="btn btn-outline-success mt-0">Save</button><button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button><input type="hidden" id="user_reset" name="user_reset" value="'+user_reset+'">');
	modal.Show();
		
	if(eventAction == 'pack')			
	$(".complaint_is_outsourced_box").hide();
});

$(document).off('click', '.product_image_camera');
$(".product_image_camera").on("click", function(){
	openMediaUploader($(this).attr('data-media-section'), $(this).attr('data-id'), "collection_record_image", "Seelct product's image source", null);	
	
});

$(document).off('change', '#complaint_assign_technician_chk');
$("#complaint_assign_technician_chk").on("change", function(){
	if($(this).is(":checked")){
		$("#complaint_assign_technician_box").show();
	}else{
		$("#complaint_assign_technician_box").hide();
	}			
}); 
	
$(document).ready(function(){
	<?php if(isset($complaint_id) && $complaint_id >0){?>
	appendPrevNext(<?php echo $Complaint->getPrevNext($app->basePath('updatecomplaintrequest'));?>);
	<?php }?>
	$(document).off('click', '.complaint_image_camera');
	$(".complaint_image_camera").on("click", function(){
	openMediaUploader($(this).attr('data-media-section'), $(this).attr('data-id'), "complaint_record_image", "Select Repair request's image source", $(this).attr('data-image-type'));
	});
});
	
function uploadMediaFile(param)
{
	processUploadMediaFile(param.name, '_'+$("#keyid").val(), '<?=MediaSection::$repairMedia?>');
	// if(confirm("Are you sure to upload this media file... ?"))
	// {
		// var files = _(field_name).files;
		// var formdata = new FormData(); 
		// if(files.length > 0){
			// for (var index = 0; index < files.length; index++) 
			// {
				// formdata.append('webcam[]', files[index]); 
			// }
			
			// formdata.append('repair_image_complaint_id', $("#keyid").val()); 
			// formdata.append('repair_image_media_type', $(".media_file_type:checked").val()); 
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
	console.log(rc);
	if(rc['image_id_array'].length > 0)
	{
		for(var i=0; i < rc['image_id_array'].length; i++){
			$(".media_uploaded_image_box_"+rc['id']+'_'+rc['type']).append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['image_id_array'][i]+'" id="item-complaint-media-container-'+rc['image_id_array'][i]+'"><div class="card"><div class="card-header card-success">Image<div class="card-actions"><a href="javascript:removeComplaintMedia(\''+rc['image_id_array'][i]+'\', \''+rc['id']+'\', \''+rc['type']+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['image_path_array'][i]+'" /></div></div></div>');
		}
	}
	
	message('success|File uploaded', 1000);
	$("#item-web-order-product-media-processing").remove();	
	$(".media_uploaded_image_box_"+rc['id']+'_'+rc['type']+" .empty_image_box").remove();
}


function removeComplaintMedia(id, repair_image_complaint_id, type)
{
	if(confirm("Are you sure to remove this media file... ?"))
	{
		var data={
			action		:	'removecomplaintmedia',
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
					$("#item-complaint-media-container-"+id).remove();
					if($(".media_uploaded_image_box_"+repair_image_complaint_id+'_'+type+" div").length ==0)
					$(".media_uploaded_image_box_"+repair_image_complaint_id+'_'+type).html(EMPTY_IMAGE_BOX);
				}
				message(arr[1],2000);
			}
		})	
	}
	else
		$("#item-complaint-media-container-"+id+" .card").show();
}

function rmaFormCheck(){
	var formFields	=	"customer_email, customer_phone, customer_type_id, customer_fname, customer_lname, customer_address_postcode, customer_address_street_number, customer_address_route, customer_address_administrative_area, customer_address_country, complaint_description, complaint_problem_details, complaint_product_serial, complaint_due_date, complaint_estimated_cost, complaint_technician_id, complaint_status, complaint_product_condition_at_receiving";
	
	if(validateFields(formFields)){		
		confirmMessage.Set('Are you sure to <?=(!isset($complaint_id) ||$complaint_id==0)?"add":"updated"?> request Information...?', 'addComplaint');
	}
}
function addComplaint()
{
	var app_not_working_problem_mark = [];
	$(".app_not_working_problem_mark").each(function(index, element) {
		if($(this).is(":checked"))
		app_not_working_problem_mark.push($(this).val());
		
	});
	
	var complaint_product_hardware_not_working = [];
	$(".complaint_product_hardware_not_working").each(function(index, element) {
		if($(this).is(":checked"))
		complaint_product_hardware_not_working.push($(this).val());
		
	});
	var data={
		action	:	$("#action").val()				
	};
	
	data = $.extend(data, $("#addcomplaint").serializeFormJSON());
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Submitting Reapir Request...", 0);
			dissableSubmission();
		},		
		success:function(output){
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				$("#complaint_id").val(arr[2]);
				$("#customer_id").val(arr[3]);
				$("#customer_address_id").val(arr[4]);
				$("#action").val("repair/updatecomplaint");
				$("#btn_action_name").text("Update");
			}
			message(arr[1],2000);
		}
	});	
}

function callExtraModule(eData)
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

function addProblemNameForm(id, title)
{
	setPopup(id, title);
	var bodyHtml = '<div class="col-md-12"><div class="row">';
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><label for="poplogtext">New Problem Detail<sup>*</sup></label><input class="form-control" id="problem_name" name="problem_name" maxlength="200" placeholder="Write Hardware Problem Type Name" type="text"></div>';
	bodyHtml +='</div>';
	bodyHtml +='</div></div>';
	modal.Body(bodyHtml);
	modal.Footer('<button type="reset" class="btn btn-default" >Reset</button><button type="button" id="popupsubmit" onclick="submitProblemName();" class="btn btn-success" >Save</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
}

function submitProblemName()
{
	if($("#keyid").val()==1)
	{
		if(validateFields("problem_name", true))
		{
			var app_not_working_problem_mark = [];
			$(".app_not_working_problem_mark").each(function(index, element) {
				if($(this).is(":checked"))
				app_not_working_problem_mark.push($(this).val());
				
			});
			
			var dataAjax={
						action		:	'system/addhardwareproblem',
						problem_name 	:	$("#problem_name").val(),
						id			:	$("#keyid").val(),
						popup		:	true,
						autocheck	:	app_not_working_problem_mark						
					};
			$.ajax({type:'POST', data:dataAjax, url:sitePath +'ajax.php', 		
				beforeSend: function(){
					popmessage("connecting|Connecting...",0);
					dissableSubmission();
				},		
				success:function(output){
					enableSubmission(output);
					var arr	=	JSON.parse(output);	
					if(arr[0]==200)	
					{
						$("#problem_name").val('');
						$("#app_not_working_problem_mark_box").html(arr[3]);
					}	
					popmessage(arr[1]);
				}
			})	
		}
	}
}
</script> 
<script language="JavaScript" type="text/javascript">
function take_snapshot() {
	if(!cametaStatus){
		message("danger|Please start camera first...", 2000);
		return false;
	}
	Webcam.snap( function(data_uri) {
		pasteImageBlob = data_uri;
		
		$(".paste_image_result_box").remove();		
        $('<div class="paste_image_result_box"><div class="col-xs-12 mb-2"><img data-name="'+name+'" class="img img-responsive" src="' + data.dataURL +'" ></div><div class="col-xs-6"><button type="button" onClick="confirmMessage.Set(\'Are you sure to upload this image\', \'processPasteMediaFile\');" class="btn btn-block btn-outline-success">Upload</button></div><div class="col-xs-6"><button type="button" class="pasteimagecancel btn btn-block btn-outline-danger">Cancel</button></div></div>').insertAfter($(".paste_image_uploader"));
		
	} );
	// take snapshot and get image data
	// Webcam.snap( function(data_uri) {
		// // display results in page
		// var count = parseInt($(".snapshot_image_count_row").length)+1;
		// var parameter={
			// repair_image_complaint_id	: $("#keyid").val(),
			// repair_image_media_type		: $(".media_file_type:checked").val()
		// };
		// if(confirm("Are you sure to upload this media file... ?"))
		// {
			// $("#media_uploaded_image_box_"+$("#keyid").val()).append('<div class="col-xs-12 col-lg-3"  id="item-media-processing"><center><br/><br/>Processing...<br/><br/>Uploading Snapshot<br/><br/>Please wait</center></div>');
			// Webcam.upload( data_uri, parameter, sitePath+'saveimage.php', function(code, text) {
				// var rc	=	JSON.parse(text);	
				// $("#media_uploaded_image_box_"+rc['repair_image_complaint_id']).append('<div class="col-xs-12 col-lg-3" data-record-id="'+rc['repair_image_id']+'" id="item-complaint-media-container-'+rc['repair_image_id']+'"><div class="card"><div class="card-header card-success">Image<div class="card-actions"><a href="javascript:removeComplaintMedia(\''+rc['repair_image_id']+'\', \''+rc['repair_image_complaint_id']+'\');"><i class="icon-close"></i></a></div></div><div class="card-block p-a-1 clearfix"><img class="img-responsive" src="'+rc['repair_image_path']+'" /></div></div></div>');
	// $("#item-media-processing").remove();	
	// $("#media_uploaded_image_box_"+rc['repair_image_complaint_id']+" .empty_image_box").remove();
			// } );
		// }
	// } );
	
}
	
$(document).ready(function(e) {
	loadGallery(true, 'a.thumbnail');
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