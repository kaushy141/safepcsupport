<?php if(isset($wc_id) && $wc_id !=0) echo drawCollectionProcedure($wc_id);?>

<div class="row" id="collection_form_container">
  <div class="col-xs-12 col-sm-12 col-md-12">
  <form id="addcollection" name="addcollection">
    <div class="card">
      <div class="card-header"><i class="fa fa-align-justify"></i>Customer Details Form
        <?php if(isset($wc_id) && $wc_id!=0):?>
        <div class="card-actions"> <a data-title="Chat with <?=isset($customer_fname)?$customer_fname:"Customer";?>" title="<?=isset($customer_fname)?$customer_fname:"Customer";?>" class="dropdown-item" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?=$wc_id?>|W', '<?=$wc_code?> Log Report')"> <i class="fa fa-wechat fa-fw"></i> </a> </div>
        <?php endif; ?>
      </div>
      <div class="card-block">
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="customer_email">Customer Email<sup>*</sup></label>
              <input class="form-control" id="customer_email" name="customer_email" maxlength="50" placeholder="Enter customer Email id"  onkeyup="getDropdown(this, 'Customer<=>customer_email',true)" type="email" value="<?=isset($customer_email)?$customer_email:"";?>" <?php if(isset($customer_email) && $customer_email !="") echo " readonly=\"readonly\"";?> >
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
              <label for="customer_address_street_number">House Number<sup>*</sup></label>
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
              <label for="customer_address_locality">Address Locality<sup>*</sup></label>
              <input class="form-control" id="customer_address_locality" name="customer_address_locality" maxlength="100" placeholder="Enter Address locality" type="text" value="<?=isset($customer_address_locality)?$customer_address_locality:"";?>">
            </div>
          </div>
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
        <!--/row--> 
      </div>
    </div>
    <div class="card">
      <div class="card-header"><i class="fa fa-newspaper-o"></i> Collection Items Info
        <?php if($wc_id == 0):?>
        <div class="card-actions"> <a data-title="Add Collection Item" title="Add Collection Item" onclick="openCollectionItem()" data-toggle="modal" data-target="#appModal" href="#"><i class="fa fa-plus-square  fa-fw"></i></a> </div>
        <?php endif; ?>
      </div>
      <div class="card-block">
        <div class="row" id="collection_item_id_box">
          <?php if($wc_id == 0):?>
          <div class="col-md-12" data-toggle="modal" data-target="#appModal" onclick="openCollectionItem()">
            <center style="color:#AAA;">
              All Collection Items will be appear here<br/>
              Click on " <i class="fa fa-plus-square  fa-fw"></i> " icon to Add Items
            </center>
          </div>
          <?php else:?>
          <div class="col-md-12">
            <center style="color:#AAA;">
              Use <a href="<?php echo $app->basePath("managecollection/$wc_id");?>">validation</a> method to Update or Add new Collection Item
            </center>
          </div>
          <?php endif; ?>
        </div>
        <!--/row--> 
      </div>
    </div>
    <div class="card">
      <div class="card-header"><i class="fa fa-file-text"></i> Collection Reports </div>
      <div class="card-block">
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="wc_consignment_note_code">Consignment Note Code<sup></sup></label>
              <input class="form-control" id="wc_consignment_note_code" name="wc_consignment_note_code" maxlength="50" placeholder="Enter Consignment Note Code" type="text" value="<?=isset($wc_consignment_note_code)?$wc_consignment_note_code:"";?>">
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="wc_on_behalf_of_user">On Behalf of(if Any)<sup></sup></label>
              <input class="form-control" id="wc_on_behalf_of_user" name="wc_on_behalf_of_user" maxlength="50" placeholder="Enter On Behalf of(if Any)" type="text" value="<?=isset($wc_on_behalf_of_user)?$wc_on_behalf_of_user:"";?>">
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="wc_carrier_id">Carrier Service<sup>*</sup></label>
              <select id="wc_carrier_id" name="wc_carrier_id" onchange="loadOptions('wc_vehicle_id', 'CarrierVehicle', this.value, 0)" class="form-control" size="1">
                <?php
                $Carrier = new Carrier(0);
				echo $Carrier->getOptions(isset($wc_carrier_id)?$wc_carrier_id:"0");
				?>
              </select>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="wc_loading_time">Loading Time<sup></sup></label>
              <div class="input-group clockpicker" data-placement="left" data-align="top">
                <input type="text" class="form-control" id="wc_loading_time" name="wc_loading_time" maxlength="9"  value="<?=isset($wc_loading_time)?$wc_loading_time:"";?>" placeholder="Loading time Hour : Minute">
                <span class="input-group-addon"> <span class="fa fa-clock-o fa-fw"></span> </span> </div>
              <script type="text/javascript">
				$(document).ready(function(e) {
                   $('.clockpicker').clockpicker({placement: 'top', autoclose:true, donetext: 'Done'}); 
                });
            	</script> 
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="wc_help_member">Help member from Customer<sup></sup></label>
              <input class="form-control" id="wc_help_member" name="wc_help_member" maxlength="50" placeholder="Members count" type="number" min="0" value="<?=isset($wc_help_member)?intval($wc_help_member):"0";?>">
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="wc_manager_id">Assign Collection Manager<sup>*</sup></label>
              <select id="wc_manager_id" name="wc_manager_id" class="form-control" size="1">
                <?php
                $wcm = new Employee(0);
				echo $wcm->getUserOption(5, isset($wc_manager_id)?$wc_manager_id:"0");
				?>
              </select>
            </div>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="form-group">
              <label> Collection mode ?</label>
              <br/>              
                  <div class="col-xs-4">
                    <label class="switch switch-icon switch-pill switch-success">
                    <input class="switch-input wc_is_drop_off" <?=(!isset($wc_is_drop_off) || (isset($wc_is_drop_off) && $wc_is_drop_off == 0))? "checked":"";?> id="wc_is_collection" value="0" name="wc_is_drop_off" type="radio">
                    <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label><br/><strong>Collection</strong>
                  </div>
              	  <div class="col-xs-4">
                    <label class="switch switch-icon switch-pill switch-info">
                    <input class="switch-input wc_is_drop_off" <?=(isset($wc_is_drop_off) && $wc_is_drop_off == 1)? "checked":"";?> id="wc_is_drop_off" value="1" name="wc_is_drop_off" type="radio">
                    <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label><br/><strong>DropOff</strong>
                  </div>
                  <div class="col-xs-4">
                    <label class="switch switch-icon switch-pill switch-warning">
                    <input class="switch-input wc_is_drop_off" <?=(isset($wc_is_drop_off) && $wc_is_drop_off == 2)? "checked":"";?> id="wc_is_pickup" value="2" name="wc_is_drop_off" type="radio">
                    <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label><br/><strong>Pickup</strong>
                  </div>
            </div>
          </div>
		  
		  </div>
		  <div class="row mt-2">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
			 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 data_col_toggle" id="data_toggle_vehicle_select_box">
                <div class="form-group">
                  <label for="wc_vehicle_id">Select Carrier Vehicle<sup>*</sup></label>
				  <a class="pull-right togle_option text-primary" style="cursor: pointer;" data-togle-id="data_toggle_vehicle_text_box">New Vehicle</a>
                  <select id="wc_vehicle_id" name="wc_vehicle_id" class="form-control" size="1">
                    <?php
                	$CarrierVehicle = new CarrierVehicle(0);
					echo $CarrierVehicle->getOptions(isset($wc_vehicle_id)?$wc_vehicle_id:"0");
					?>
                  </select>
                </div>
              </div>  
              <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 data_col_toggle" id="data_toggle_vehicle_text_box">
                <div class="form-group">
                  <label for="wc_drop_off_vehicle">Write Vehicle Number<sup>*</sup></label>
				  <a class="pull-right togle_option text-success" style="cursor: pointer;" data-togle-id="data_toggle_vehicle_select_box">Select Vehicle</a>
                  <input class="form-control" id="wc_drop_off_vehicle" onkeyup="getDropdown(this, 'Collection<=>wc_drop_off_vehicle')"  name="wc_drop_off_vehicle" maxlength="50" placeholder="Drop off Vehicle Number" type="text" value="<?=isset($wc_drop_off_vehicle)?$wc_drop_off_vehicle:"";?>">
                </div>
              </div>             
			  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 data_col_toggle" id="data_toggle_driver_select_box">
                <div class="form-group">
                  <label for="wc_driver_id">Select Driver<sup>*</sup></label>
				  <a class="pull-right togle_option text-primary" style="cursor: pointer;" data-togle-id="data_toggle_driver_text_box">New Driver</a>
                  <select id="wc_driver_id" name="wc_driver_id" class="form-control" size="1">
                    <?php
					$wcm = new Employee(0);
					echo $wcm->getUserOption(6, isset($wc_driver_id)?$wc_driver_id:"0");
					?>
                  </select>
                </div>
              </div>
			  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 data_col_toggle" id="data_toggle_driver_text_box">
                <div class="form-group">
                  <label for="wc_drop_off_driver">Write Driver name<sup>*</sup></label>
				  <a class="pull-right togle_option text-success" style="cursor: pointer;"  data-togle-id="data_toggle_driver_select_box">Select Driver</a>
                  <input class="form-control" id="wc_drop_off_driver" onkeyup="getDropdown(this, 'Collection<=>wc_drop_off_driver')"  name="wc_drop_off_driver" maxlength="100" placeholder="Drop off driver name" type="text" value="<?=isset($wc_drop_off_driver)?$wc_drop_off_driver:"";?>">
                </div>
              </div>
            </div>
          </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_due_date">Collection Due Date/Time<sup>*</sup></label>
            <div class="input-group date">
              <input type='text' class="form-control" id="wc_due_date" name="wc_due_date" placeholder="YYYY-MM-DD" value="<?=isset($wc_due_date)?date("Y-m-d h:i A", strtotime($wc_due_date)):date('Y-m-d h:i A');?>" />
              <span class="input-group-addon">
              <label style="margin-bottom:0px;" for="wc_due_date"><i class="fa fa-calendar fa-fw"></i></label>
              </span> </div>
            <script type="text/javascript">
				$(function () {
					$('#wc_due_date').datetimepicker({
						format: 'yyyy-mm-dd HH:ii P',
						autoclose:true,
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true,
						fontAwesome : true,
						showMeridian: true,
					});
				});
            </script> 
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_status_id">Collection Status<sup>*</sup></label>
            <select id="wc_status_id" name="wc_status_id" class="form-control" size="1">
              <?php
                $WcStatus = new WcStatus(0);
				echo $WcStatus->getOptions(isset($wc_status_id)?$wc_status_id:"0");
				?>
            </select>
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_is_local_authority"><i class="fa fa-meh-o fa-fw"></i> &nbsp;is Collector local authority? </label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="wc_is_local_authority" value="1" name="wc_is_local_authority" type="checkbox" <?=(isset($wc_is_local_authority) && $wc_is_local_authority)?"checked":"";?> >
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_mail_to_customer"><i class="fa fa-envelope-o fa-fw"></i> &nbsp;Mail to Customer</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="wc_mail_to_customer" value="1" name="wc_mail_to_customer" type="checkbox" <?=(isset($wc_mail_to_customer) && $wc_mail_to_customer)?"checked":"checked";?>>
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_mail_to_collector"><i class="fa fa-user-secret fa-fw"></i> &nbsp;Mail to Collector</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="wc_mail_to_collector" value="1" name="wc_mail_to_collector" type="checkbox" <?=(isset($wc_mail_to_collector) && $wc_mail_to_collector)?"checked":"checked";?>>
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_collection_report"><i class="fa fa-file-pdf-o fa-fw"></i> &nbsp; Generate Collection Report</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="wc_collection_report" value="1" name="wc_collection_report" type="checkbox" <?=(isset($wc_collection_report) && $wc_collection_report)?"checked":"";?> >
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_transfer_note"><i class="fa fa-file-pdf-o fa-fw"></i> &nbsp;Get Waste Transfer Note</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="wc_transfer_note" value="1" name="wc_transfer_note" type="checkbox" <?=(isset($wc_transfer_note) && $wc_transfer_note)?"checked":"";?>>
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_consignment_note"><i class="fa fa-file-pdf-o fa-fw"></i> &nbsp;H.W.Consignment Note</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="wc_consignment_note" value="1" name="wc_consignment_note" type="checkbox" <?=(isset($wc_consignment_note) && $wc_consignment_note)?"checked":"";?>>
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_mail_hwcn_to_customer"><i class="fa fa-envelope-o fa-fw"></i> &nbsp;Send Hazardous Report to Customer</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="wc_mail_hwcn_to_customer" value="1" name="wc_mail_hwcn_to_customer" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_mail_wcnn_to_customer"><i class="fa fa-envelope-o fa-fw"></i> &nbsp;Send Waste Cons. to Customer</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="wc_mail_wcnn_to_customer" value="1" name="wc_mail_wcnn_to_customer" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="wc_mail_docn_to_customer"><i class="fa fa-envelope-o fa-fw"></i> &nbsp;Send Duty of care to Customer</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="wc_mail_docn_to_customer" value="1" name="wc_mail_docn_to_customer" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
      </div>
      <?php if(isset($wc_id) && $wc_id !=0):?>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <button type="button" id="btn_report_req_submit_cert_customer" onClick="sendCertificate();" class="btn btn-warning mt-1 submission_handler_btn"><i class="fa fa-check-circle fa-fw"></i> Send Certificate to Customer</button>
          <?php if(isset($wc_is_certificate_sended) && $wc_is_certificate_sended==1)echo "<br/><i class=\"fa fa-check-square-o fa-fw text-success\"></i><span class=\"text-success\"> Certificate send to customer</span>"?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <button type="button" id="btn_report_req_submit_hwc_collector" onClick="sendhwcnto(1);" class="btn btn-warning mt-1 submission_handler_btn"><i class="fa fa-check-circle fa-fw"></i> Send Haz.WC to Collector</button>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <button type="button" id="btn_report_req_submit_hwc_courier" onClick="sendhwcnto(2);" class="btn btn-warning mt-1 submission_handler_btn"><i class="fa fa-check-circle fa-fw"></i> Send Haz.WC to Courier Service</button>
        </div>
      </div>
      <?php endif; ?>
      <?php if(!isset($wc_id) || $wc_id ==0):?>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="form-group">
            <label for="is_back_date_collection">For Back date Collection</label>
            <br/>
            <label for="is_back_date_collection"><i class="fa fa-history fa-fw"></i> &nbsp;is this Back date collection..?</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="is_back_date_collection" value="1" name="is_back_date_collection" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" id="back_date_collection_date_box" style="display:none;">
          <div class="form-group">
            <label for="back_date_collection_date">Collection Back Date<sup>*</sup></label>
            <div class="input-group date">
              <input class="form-control" id="back_date_collection_date" name="back_date_collection_date" placeholder="YYYY-MM-DD" value="" type="text">
              <span class="input-group-addon">
              <label style="margin-bottom:0px;" for="back_date_collection_date"><i class="fa fa-calendar fa-fw"></i></label>
              </span> </div>
            <script type="text/javascript">
                    $('#back_date_collection_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,
						endDate  : '<?php echo date('Y-m-d')?>',						
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
          </div>
        </div>
      </div>
      <?php endif; ?>
	<?php if(isAdmin()):?>
	<div class="row">
		  <div class="col-md-12 mt-1 text-right">
			<?php 
			$creator = new Employee((isset($wc_created_by) && $wc_created_by != 0 ) ? $wc_created_by : getLoginId());
			$creatorData = $creator->getDetails();
			$wc_created_date = isset($wc_created_date) ? $wc_created_date : date('Y-m-d H:i:s');
			?>
			<div class="pull-right pl-1"><img class="img img-circle" style="margin-top:0px; margin-bottom:0px; height:40px;" src="<?php echo getResizeImage($creatorData["user_image"],50)?>"/></div>
			<div class="pull-right">Originally created by <?php echo $creatorData['user_name']?> <i class="fa fa-check-circle text-success"></i><br/>
<span class="text-muted" style="font-size: 0.9em;"><?php echo dateView($wc_created_date, 'NOW')?> <?php echo dateView($wc_created_date, 'FULL')?></span>			</div>
		  </div>
	</div>	
	<?php endif;?>
      <!--/row--> 
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-sm-12">
          <button type="reset" class="btn btn-outline-danger"><i class="fa fa-refresh m-t-2"></i> Reset</button>
          &nbsp;
          <button type="button" id="btn_complaint_submit" onClick="confirmMessage.Set('Are you sure to <?=$wc_id==0?"add":"update"?> collection information...?', 'addCollection');" class="btn btn-success mt-0 submission_handler_btn"><i class="fa fa-check"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span> COLLECTION </button>
        </div>
      </div>
    </div>
    <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"collection/addcollection";?>"  />
    <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
    <input type="hidden" id="wc_id" name="wc_id" value="<?=isset($wc_id)?$wc_id:"0";?>"  />
    <input type="hidden" id="customer_id" name="customer_id" value="<?=isset($customer_id)?$customer_id:"0";?>"  />
    <input type="hidden" id="customer_address_id" name="customer_address_id" value="<?=isset($customer_address_id)?$customer_address_id:"0";?>"  />
    </div>
  </form>
</div>
</div>
<div class="row" style="display:none;" id="collection_submited_container">
  <div class="col-sm-12">
    <form id="collection_submitted_form" name="collection_submitted_form">
      <div class="card">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="card card-inverse card-success text-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Collection Request saved successfully</p>
                    <p> <b id="collection_wc_code"></b><br/>
                    </p>
                    <p> <a id="collection_r_hwc_link" href="#" class="btn btn-primary text-white btn-block"><span class="fa fa-file-pdf-o fa-fw mb-1"></span><br/>
                      Download Hazardous Waste Consignment</a> </p>
                    <p> <a id="collection_r_wcn_link" href="#" class="btn btn-primary text-white btn-block"><span class="fa fa-file-pdf-o fa-fw mb-1"></span><br/>
                      Download Waste Collection (WCN)</a> </p>
                    <p> <a id="collection_r_doc_link" href="#" class="btn btn-primary text-white btn-block"><span class="fa fa-file-pdf-o fa-fw mb-1"></span><br/>
                      Download Duty of Care: Waste Transfer</a> </p>
                    <p> <a id="collection_r_cer_link" href="#" class="btn btn-primary text-white btn-block"><span class="fa fa-file-pdf-o fa-fw mb-1"></span><br/>
                      Download Certificate</a> </p>
                  </blockquote>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 col-md-6">
                  <p><a id="collection_update_link" href="" class="btn btn-info text-white btn-block"><span class="fa fa-cloud-upload mb-1"></span> &nbsp; Update</a></p>
                </div>
                <div class="col-sm-6 col-md-6">
                  <p><a href="<?php echo $app->siteUrl('addcollection');?>" class="btn btn-default text-black btn-block"><span class="fa fa-plus fa-fw mb-1"></span> &nbsp; Add New Collection</a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(e) {
	$("#is_back_date_collection").on("click", function(){
		if($(this).is(":checked")){
	    	$("#back_date_collection_date_box").show();
			$("#back_date_collection_date").focus();
		}else{
			$("#back_date_collection_date_box").hide();
			$("#back_date_collection_date").val("");
		}			
	});  
	
		
	$(".togle_option").on("click", function(){
		$(this).parents(".data_col_toggle").hide();		
		$("#"+$(this).attr('data-togle-id')).show();
		if($(this).next(".form-control").is('input'))
			$(this).next(".form-control").val('');
		else
			$(this).next(".form-control").val(0);
	})

	if(<?=(isset($wc_id) && intval($wc_id) > 0) ? intval($wc_id) : 0 ?> == 0 || <?php echo isset($wc_vehicle_id) ? intval($wc_vehicle_id) : 0?> != 0)
	{
		$("#data_toggle_vehicle_select_box").show();
		$("#data_toggle_vehicle_text_box").hide();
		$("#wc_drop_off_vehicle").val('');
	}
	else
	{
		$("#data_toggle_vehicle_select_box").hide();
		$("#data_toggle_vehicle_text_box").show();
		$("#wc_vehicle_id").val(0);
	}
	if(<?=(isset($wc_id) && intval($wc_id) > 0) ? intval($wc_id) : 0 ?> == 0 || <?php echo isset($wc_driver_id) ? intval($wc_driver_id) : 0?> != 0)
	{
		$("#data_toggle_driver_select_box").show();
		$("#data_toggle_driver_text_box").hide();
		$("#wc_drop_off_driver").val('');
	}
	else
	{
		$("#data_toggle_driver_select_box").hide();
		$("#data_toggle_driver_text_box").show();
		$("#wc_driver_id").val(0);
	}
});

function sendhwcnto(usertype)
{	
	var dataAjax={
				action		:	'collection/sendhwcnto',
				usertype 	:	usertype,
				wc_id		:	$("#wc_id").val()					
			};
	$.ajax({type:'POST', data:dataAjax, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("connecting|Connecting...",0);
			dissableSubmission();
		},		
		success:function(output){ 
			enableSubmission(output);
			var arr	=	JSON.parse(output);		
			message(arr[1]);
		}
	})	
		
}

function loadOptions(targetField, class_name, value, id)
{
	if(value !=0)
	{
		var dataAjax={
					action		:	'collection/loadoptions',
					class_name	:	class_name,
					value		:   value,
					id			:	id					
				};
		$.ajax({type:'POST', data:dataAjax, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				//message("connecting|Connecting...",0);
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0] == 200)	
				$("#"+targetField).html(arr[2]);
				//message(arr[1],500);
			}
		})
	}
}

$(document).ready(function(e) {
    loadOptions('wc_vehicle_id', 'CarrierVehicle', $("#wc_carrier_id>option:selected").val(), <?=isset($wc_vehicle_id)?$wc_vehicle_id:0;?>);
});

function sendCertificate()
{	
	var dataAjax={
				action		:	'collection/sendcertificate',
				wc_id		:	$("#wc_id").val()					
			};
	$.ajax({type:'POST', data:dataAjax, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("connecting|Connecting...",0);
			dissableSubmission();
		},		
		success:function(output){
			enableSubmission(output);
			var arr	=	JSON.parse(output);		
			message(arr[1]);
		}
	})	
}


function addCollection()
{
	var formFields	=	"customer_email, customer_phone, customer_type_id, customer_fname, customer_lname, customer_address_postcode, customer_address_street_number, customer_address_route, customer_address_locality, customer_address_administrative_area, customer_address_country, wc_status_id";
	
	if(validateFields(formFields))
	{
		if($("#wc_id").val() ==0 && $("#is_back_date_collection").is(":checked")){
			if($("#back_date_collection_date").val()==""){
				message("danger|Collection Back date must be filled.",2000);
				return false;
			}
		}
		
		if($(".wc_is_drop_off:checked").val() == 1)
		{
			if($("#wc_drop_off_vehicle").val()==""){
				message("danger|Drop off vehicle number should be filled.",2000);
				$("#wc_drop_off_vehicle").focus();
				return false;
			}
			
			if($("#wc_drop_off_driver").val()==""){
				message("danger|Drop off driver name should be filled.",2000);
				$("#wc_drop_off_driver").focus();
				return false;
			}
		}
		else
		{
			if($("#wc_vehicle_id").val()==""){
				message("danger|Vehicle should be selected.",2000);
				$("#wc_vehicle_id").focus();
				return false;
			}
			
			if($("#wc_driver_id").val()==""){
				message("danger|Driver should be selected.",2000);
				$("#wc_driver_id").focus();
				return false;
			}
		}
		
		var data={
			action	:	$("#action").val(),
			wc_is_drop_off : $(".wc_is_drop_off:checked").val()	
		};
		data = $.extend(data, $("#addcollection").serializeFormJSON());		
	console.log(data);
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Submitting Collection Request...",0);
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#collection_wc_code").html(arr[3]);
					$("#collection_form_container").remove();
					$("#collection_submited_container").show();
					$("#collection_update_link").attr("href","<?php echo $app->siteUrl('updatecollection')?>/"+arr[2]);
					$("#collection_r_hwc_link").attr("href",arr['hwc_link']);
					$("#collection_r_wcn_link").attr("href",arr['wcn_link']);
					$("#collection_r_doc_link").attr("href",arr['doc_link']);
					$("#collection_r_cer_link").attr("href",arr['cer_link']);
				}
				message(arr[1],2000);
			}
		})	
	}
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


<?php 
$WcItem = new WcItem();
?>
var collectionItemList = <?php echo json_encode($WcItem->getList((isset($wc_id) && $wc_id != 0)? $wc_id : 0));?>;

function openCollectionItem()
{
	setPopup(1, "Add Collection Item(s)");
	var wci_item_key_value_id = [];
	if($(".wci_item_key_value_id").length>0) 
	$(".wci_item_key_value_id").each(function(index, element) {
        wci_item_key_value_id.push($(this).val());
    });
	console.log(wci_item_key_value_id);
	var bodyHtml='';
	for(var i=0; i<collectionItemList.length;i++)
	{ 
		var checkedStatus = $.inArray(collectionItemList[i]["wci_id"],wci_item_key_value_id) >-1?'checked="checked"':'';
		var qty = $("#item_selected_quantity_"+collectionItemList[i]["wci_id"]).text();
		var wet = $("#item_selected_weight_"+collectionItemList[i]["wci_id"]).text();		
		var showBox = 'none;';
		var chamount = 0;
		var pdamount = 0;
		var chformat = 0;
		var itmdesc  = '';
		var chkpaid = '';
		var chkcharge = '';
		if(checkedStatus!="")
		{
			qty = qty==""?1:qty;
			wet = wet==""?1:wet;
			
			chamount = $("#item_selected_chamount_"+collectionItemList[i]["wci_id"]).val();
			pdamount = $("#item_selected_pdamount_"+collectionItemList[i]["wci_id"]).val();
			chformat = $("#item_selected_chformat_"+collectionItemList[i]["wci_id"]).val();
			itmdesc =  $("#item_selected_itmdesc_"+collectionItemList[i]["wci_id"]).val();
			if($("#item_selected_chformat_"+collectionItemList[i]["wci_id"]).val()=='PAID')
				chkpaid = 'selected';
			else
				chkcharge = 'selected';
			
			
			var showBox = 'block;';
		}
		else
		chkcharge = 'selected';		
		
		bodyHtml += '<div class="form-group"><div class="col-md-12"><div class="checkbox"><label class="pl-0" for="wcitem_'+collectionItemList[i]["wci_id"]+'"><input '+checkedStatus+' id="wcitem_'+collectionItemList[i]["wci_id"]+'" name="wci_item_id_raw" data-position="'+i+'" value="'+collectionItemList[i]["wci_id"]+'" class="wci_item_id_raw" type="checkbox"><b><i class="fa fa-fw '+collectionItemList[i]["wci_item_icon"]+'"></i> '+collectionItemList[i]["wci_name"]+' ('+collectionItemList[i]["wci_type_name"]+')</b></label><div class="form-group" style="display:'+showBox+';" id="wc_item_container_box_'+collectionItemList[i]["wci_id"]+'">';
		bodyHtml += '<div class="row">';
		bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><label class="pl-0">Quantity</label><input type="number" class="form-control" name="item_quantity_'+collectionItemList[i]["wci_id"]+'" value="'+qty+'" min="0"  id="item_quantity_'+collectionItemList[i]["wci_id"]+'"></div>';
		
		bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><label class="pl-0">Weight/item</label><input type="number" class="form-control" name="item_weight_'+collectionItemList[i]["wci_id"]+'" value="'+wet+'" min="0" id="item_weight_'+collectionItemList[i]["wci_id"]+'"></div>';
		
		bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><label class="pl-0">Total Amount</label><input type="number" class="form-control" name="item_charge_amount_'+collectionItemList[i]["wci_id"]+'" min="0"  value="'+chamount+'" id="item_charge_amount_'+collectionItemList[i]["wci_id"]+'"></div>';
		
		bodyHtml += '<div class="col-xs-12 detailholder mt-1 d-none" id="expander-block-'+i+'"><div class="row">';
		
		bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><label class="pl-0">Total Paid Amount</label><input type="number" class="form-control" name="item_paid_amount_'+collectionItemList[i]["wci_id"]+'" min="0"  value="'+pdamount+'" id="item_paid_amount_'+collectionItemList[i]["wci_id"]+'"></div>';
			
		
		bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><label>Charge</label><div class="form-group"><label for="wcr_item_format_CHARGE_'+collectionItemList[i]["wci_id"]+'">to Customer </label><input class="wcr_item_charge_format_'+collectionItemList[i]["wci_id"]+'" id="wcr_item_format_CHARGE_'+collectionItemList[i]["wci_id"]+'" value="CHARGE" name="wcr_item_charge_format_charge_'+collectionItemList[i]["wci_id"]+'" type="radio" '+chkcharge+' > </div> </div>';
		
		
		
		bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><label>Pay</label><div class="form-group"><label for="wcr_item_format_PAID_'+collectionItemList[i]["wci_id"]+'" '+chkcharge+'>to Customer </label><input class="wcr_item_charge_format_'+collectionItemList[i]["wci_id"]+'" id="wcr_item_format_PAID_'+collectionItemList[i]["wci_id"]+'" value="PAID" name="wcr_item_charge_format_charge_'+collectionItemList[i]["wci_id"]+'" type="radio" '+chkpaid+'></div></div>';
		
		bodyHtml += '</div></div>';
		
		bodyHtml += '<div class="col-xs-12 col-sm-12 col-md-12 mt-1"><label class="pl-0">Items Description</label><label class="pull-right text-primary expander" data-expand-id="expander-block-'+i+'">Expand[+]</label> <textarea  class="form-control" rows="2" name="item_description_'+collectionItemList[i]["wci_id"]+'" id="item_description_'+collectionItemList[i]["wci_id"]+'">'+itmdesc+'</textarea></div>';
		
		bodyHtml += '</div></div></div></div></div>';
	}
	modal.Body(bodyHtml);
	modal.Footer('<button type="reset" class="btn btn-default" >Reset</button> <button type="button" onclick="addCollectionItems();" class="btn btn-success" >Add Items</button> <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	
	$(".wci_item_id_raw").on("change", function(){
		if($(this).is(":checked"))
		$("#wc_item_container_box_"+$(this).val()).slideDown();
		else
		$("#wc_item_container_box_"+$(this).val()).slideUp();
	});
}

$(document).on("click", ".expander", function(e){
	$("#"+$(this).attr("data-expand-id")).removeClass('d-none');
	$(this).remove();
});

function addCollectionItems()
{
	var wc_id = <?php echo $wc_id ?>;
	var collectionHTML= '';
	if($(".wci_item_id_raw:checked").length>0)
	{
		$(".wci_item_id_raw:checked").each(function(index, element) { 
			var val = $(this).val();
			var qty = $("#item_quantity_"+val).val();
			var wet = $("#item_weight_"+val).val(); 
			
			var chamount = $("#item_charge_amount_"+val).val(); 
			var pdamount = $("#item_paid_amount_"+val).val(); 
			var chformat = $(".wcr_item_charge_format_"+val+":checked").val(); //alert(chformat);
			var itmdesc  = $("#item_description_"+val).val(); 
			
			var seq = $(this).attr("data-position");
			var data= collectionItemList[seq];
			collectionHTML +='<div class="col-xs-12 col-sm-3 col-md-4 col-lg-3" id="item-collection-container-'+data['wci_id']+'"><div class="card"><div class="card-header card-success">'+data['wci_name']+(wc_id == 0 ? '<div class="card-actions"><a class="btn-close" href="javascript:removeCollectionItem('+data['wci_id']+');"><i class="icon-close"></i></a></div>': '') + '</div><div class="card-block p-a-1 clearfix"><div class="text-uppercase font-weight-bold font-sm  pull-left">Quantiy: <span id="item_selected_quantity_'+data['wci_id']+'">'+qty+'</span></div><div class="text-uppercase font-weight-bold font-sm pull-right">Weight/item: <span id="item_selected_weight_'+data['wci_id']+'">'+wet+'</span></div></div></div><input type="hidden" name="wci_item_id[]" class="wci_item_id wci_item_key_value_id" value="'+data['wci_id']+'"><input type="hidden" name="wci_qtiy_id[]" class="wci_item_id" value="'+qty+'"><input type="hidden" name="wci_weit_id[]" class="wci_item_id" value="'+wet+'"><input type="hidden" name="wci_chamount_id[]" id="item_selected_chamount_'+data['wci_id']+'" class="wci_item_id" value="'+chamount+'"><input type="hidden" name="wci_pdamount_id[]" id="item_selected_pdamount_'+data['wci_id']+'" class="wci_item_id" value="'+pdamount+'"><input type="hidden" name="wci_chformat_id[]" id="item_selected_chformat_'+data['wci_id']+'" class="wci_item_id" value="'+chformat+'"> <input type="hidden" name="wci_itmdesc_id[]" id="item_selected_itmdesc_'+data['wci_id']+'" class="wci_item_id" value="'+itmdesc+'"></div>';
		});
		$("#collection_item_id_box").html(collectionHTML);	
				
	}
	modal.Hide();
	return false;
}

function autofillCollection(collectionArray)
{
	openCollectionItem();
	if(collectionArray.length>0)
	{
		for(var i=0; i<collectionArray.length; i++)
		{
			$("#wcitem_"+collectionArray[i]['key']).attr("checked",1);
			$("#item_quantity_"+collectionArray[i]['key']).val(collectionArray[i]['wcr_item_qty']);
			$("#item_weight_"+collectionArray[i]['key']).val(collectionArray[i]['wcr_item_weight']);
			
			$("#item_charge_amount_"+collectionArray[i]['key']).val(collectionArray[i]['wcr_item_weight']);
			$("#item_paid_amount_"+collectionArray[i]['key']).val(collectionArray[i]['wcr_item_weight']);
			if(collectionArray[i]['wcr_item_charge_format']!="")
				$("#wcr_item_format_"+collectionArray[i]['wcr_item_charge_format']+"_"+collectionArray[i]['key']).prop("checked",true);	
			else	
				$("#wcr_item_format_CHARGE_"+collectionArray[i]['key']).prop("checked",true);	
			$("#item_description_"+collectionArray[i]['key']).val(collectionArray[i]['wcr_item_description']);
			
		}		
		addCollectionItems();
	}
}

<?php if(isset($collection_item_list_array) && $collection_item_list_array !=""):?>
autofillCollection(<?php echo json_encode($collection_item_list_array)?>);
<?php endif; ?>

function removeCollectionItem(id)
{
	$("#item-collection-container-"+id).remove();
	if($("#collection_item_id_box").html()=="")
	$("#collection_item_id_box").html('<div class="col-md-12" data-toggle="modal" data-target="#appModal" onclick="openCollectionItem()"><center style="color:#AAA;">All Collection Items will be appear here<br/> Click on " <i class="fa fa-plus-square  fa-fw"></i> " icon to Add Items</center></div>');	
}
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