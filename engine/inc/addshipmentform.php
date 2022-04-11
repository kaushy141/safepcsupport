<div class="row" id="collection_form_container">
  <div class="col-xs-12 col-sm-12 col-md-12">
  <form id="addshipment" name="addshipment">
    <div class="card">
      <div class="card-header"><i class="fa fa-align-justify"></i>Shipment
      </div>
      <div class="card-block">
	  
		<div class="row">	
			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class="form-group">
				  <label for="shipment_type_id">Shipment Type<sup>*</sup></label>
				  <select id="shipment_type_id" name="shipment_type_id" class="form-control shipment_type_id" size="1">
                  <option data-weight="0" value=""> -- Select Shipment Type -- </option>
					<?php					
					$shipmentType = new ShipmentType(isset($shipment_type_id)?$shipment_type_id:"0");
					$shipmentTypeList = $shipmentType->getOptionsArray();
					if($shipmentTypeList)
					foreach($shipmentTypeList as $_shipment)
					{?>
					<option data-weight="<?php echo $_shipment['shipment_min_weight']; ?>" value="<?php echo $_shipment['shipment_type_id']; ?>"><?php echo $_shipment['shipment_type_name']?></option>
					<?php
					}
					?>
				  </select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class="form-group">
				  <label for="shipment_shipper_id">Shipment Shipper<sup>*</sup></label>
				  <select id="shipment_shipper_id" name="shipment_shipper_id" class="form-control shipment_shipper_id" size="1">
					<?php
					$shipper = new ShipmentShipper(isset($shipment_shipper_id)?$shipment_shipper_id:"0");
					$shipperList = $shipper->getOptionsArray();
					if($shipperList)
					foreach($shipperList as $_shipper)
					{?>
					<option <?php if(isset($shipment_shipper_id) && $shipment_shipper_id){
						if($shipment_shipper_id == $_shipper['shipment_shipper_id'])
							echo "selected";
					}else{
						if($_shipper['shipment_shipper_default'])
							echo "selected";
					}?> value="<?php echo $_shipper['shipment_shipper_id']; ?>"><?php echo $_shipper['shipment_shipper_name']?>, <?php echo $_shipper['shipment_shipper_company']?> <?php echo $_shipper['shipment_shipper_city']?>, <?php echo $_shipper['shipment_shipper_post_code']?></option>
					<?php
					}
					?>
				  </select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class="form-group">
				  <label for="shipment_origin_id">Shipment Origin<sup>*</sup></label>
				  <select id="shipment_origin_id" name="shipment_origin_id" class="form-control shipment_origin_id" size="1">
					<?php
					$shipmentOrigin = new ShipmentOrigin(isset($shipment_origin_id)?$shipment_origin_id:"0");
					$shipmentOriginList = $shipmentOrigin->getOptionsArray();
					if($shipmentOriginList)
					foreach($shipmentOriginList as $_origin){?>
					<option <?php if(isset($shipment_origin_id) && $shipment_origin_id){
						if($shipment_origin_id == $_origin['shipment_origin_id'])
							echo "selected";
					}else{
						if($_origin['shipment_origin_default'])
							echo "selected";
					}?> value="<?php echo $_origin['shipment_origin_id']; ?>"><?php echo $_origin['shipment_origin_name']?>, <?php echo $_origin['shipment_origin_company']?>  <?php echo $_origin['shipment_origin_city']?>, <?php echo $_origin['shipment_origin_post_code']?></option>
					<?php
					}
					?>
				  </select>
				</div>
			</div>
		</div>
		<div class="row">	  
          
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="shipment_billing_weight">Shipment Weight<sup>*</sup></label>
              <input class="form-control" id="shipment_billing_weight" name="shipment_billing_weight" maxlength="20" placeholder="Enter Weight" type="number" step="0.01" value="<?=isset($shipment_billing_weight)?$shipment_billing_weight:"0.50";?>">
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="shipment_billing_weight_unit">Shipment Weight Unit<sup>*</sup></label>
              <select id="shipment_billing_weight_unit" name="shipment_billing_weight_unit" class="form-control" size="1">
				<option value='KGS'>KGS</option>
                <option value='LBS'>LBS</option>
              </select>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
				  <label for="shipment_service_code" class="text-success">Shipment Service<sup>*</sup></label>
				  <select id="shipment_service_code" name="shipment_service_code" class="form-control shipment_service_code" size="1">
					
				  </select>
				</div>
          </div>
        </div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="shipment_to_name">Shipment To Name<sup>*</sup></label>
              <input class="form-control" id="shipment_to_name" name="shipment_to_name" maxlength="50" placeholder="Enter customer name" type="text" value="<?=isset($shipment_to_name)?$shipment_to_name:"";?>">
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="shipment_to_company">Company Name </label>
              <input class="form-control" id="shipment_to_company" name="shipment_to_company" maxlength="50" placeholder="Enter Company name" type="text" value="<?=isset($shipment_to_company)?$shipment_to_company:"";?>">
            </div>
          </div>
		  
		  <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="shipment_to_phone_number">Phone Number<sup>*</sup> </label>
              <input class="form-control" id="shipment_to_phone_number" name="shipment_to_phone_number" maxlength="20" placeholder="Enter Phone number" type="text" value="<?=isset($shipment_to_phone_number)?$shipment_to_phone_number:"";?>">
            </div>
          </div>
          
          
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="shipment_to_email">Email<sup>*</sup> </label>
              <input class="form-control" id="shipment_to_email" name="shipment_to_email" maxlength="50" placeholder="Enter email id" type="email" value="<?=isset($shipment_to_email)?$shipment_to_email:"";?>">
            </div>
          </div>
        </div>
		<div class="row">
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="shipment_to_address_line1">Address Line 1<sup>*</sup></label>
              <input class="form-control" id="shipment_to_address_line1" name="shipment_to_address_line1" maxlength="100" placeholder="Enter Address line 1" type="text" value="<?=isset($shipment_to_address_line1)?$shipment_to_address_line1:"";?>">
            </div>
          </div>          
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="shipment_to_city">City<sup>*</sup></label>
              <input class="form-control" id="shipment_to_city" name="shipment_to_city" maxlength="100" placeholder="Enter city name" type="text" value="<?=isset($shipment_to_city)?$shipment_to_city:"";?>">
            </div>
          </div>
          <div id="div_shipment_to_state_code" class="col-xs-12 col-sm-6 col-md-4" style="display:none;">
            <div class="form-group">
              <label for="shipment_to_state_code">State (Administrative Area)<sup>*</sup></label>
              <input class="form-control" id="shipment_to_state_code" name="shipment_to_state_code" maxlength="100" placeholder="Enter State code" type="text" value="<?=isset($shipment_to_state_code)?$shipment_to_state_code:"";?>">
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="customer_address_country">Country Name<sup>*</sup></label>
              <select id="shipment_to_country_code" name="shipment_to_country_code" class="form-control" size="1">
                <?php
				$country = new Country();
				echo $country->getOptions(isset($shipment_to_country_code) ? $shipment_to_country_code : 0, 'iso2');
				?>
              </select>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="shipment_to_post_code">PostCode <sup>*</sup></label>
              <input class="form-control input_text_upper" id="shipment_to_post_code" name="shipment_to_post_code" maxlength="10" placeholder="Enter postcode" type="text" value="<?=isset($shipment_to_post_code)?strtoupper($shipment_to_post_code):"";?>">
            </div>
          </div>
		  <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group pt-1">
              <button type="button" id="btnAddressValidation" class="btn btn-default btn-block btn-outline-success mt-2">Validate Address</button>
            </div>
          </div>
          
        </div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-lg-12">
				<div class="card">
					<div class="card-header"><i class="fa fa-file-text"></i> Shipment Details</div>
					<div class="card-body p-1">
						<table class="table">
						<tr>
							<td><textarea rows="4" id="shipment_description" name="shipment_description" class="form-control"><?php echo trim($shipment_description)?></textarea></td>
						</tr>
						</table>					
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">			
			<h5 class="card-title"><span class="icon icon-primary mb-3 icon-sm">
                            <i class="fa fa-forward" aria-hidden="true"></i>
                        </span> <span>Select Products</span></h5>
						<table class="table">
						<tr>
						<th><i class="fa fa-check-circle" aria-hidden="true"></th>
						<th>Name</th>
						<th>SKU</th>
						<th>SR.No.</th>
						</tr>
						<?php 
						if(isset($shipment_products) && count($shipment_products)){
						foreach($shipment_products as $_product){
						?>
						<tr>
						<td>
						<label class="switch switch-icon switch-pill switch-success pull-left">
						<input class="switch-input shipment_products" id="shipment_products_<?php echo $_product['product_id']?>" value="<?php echo $_product['product_id']?>" name="shipment_products[]" type="checkbox">
						<span class="switch-label" data-on="✓" data-off="✕"></span> <span class="switch-handle"></span>
						</label>
						</td>
						<td><?php echo $_product['product_name']?>
						<?php if($_product['product_track'] != ''){
							?>
							<br/><span class='text-muted'>Product already Shipped</span> <span class="badge badge-success"><?php echo $_product['product_track']?></span>
						<?php
						}?>
						</td>
						<td><?php echo $_product['product_sku']?></td>
						<td><?php echo $_product['product_srno']?></td>
						<tr>
						<?php }
						}?>
					</table>
			</div>
		</div>
        <!--/row--> 
      </div>
    </div>    
    <div class="card-footer">
      <div class="row">
        <div class="col-sm-12">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-md"></i> Reset</button>
          &nbsp;
          <button type="button" id="btn_complaint_submit" onClick="confirmMessage.Set('Are you sure to get shipment qoutes...?', 'proceedToGetQoutes');" class="btn btn-success mt-0 submission_handler_btn"><i class="fa fa-check-circle fa-lg"></i> Get Qoutes</button>
        </div>
      </div>
    </div>
    <input type="hidden" id="action" name="action" value="<?=$action;?>"  />
    <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
    <input type="hidden" id="shipment_id" name="shipment_id" value="<?=isset($shipment_id)?$shipment_id:"0";?>"  />
	<input type="hidden" id="shipment_for" name="shipment_for" value="<?=$shipment_for;?>"  />
	<input type="hidden" id="shipment_for_id" name="shipment_for_id" value="<?=$shipment_for_id;?>"  />
    </div>
  </form>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	
	if(!($("#shipment_to_country_code").val() == 'US' || $("#shipment_to_country_code") == 'CA')){
		$("#shipment_to_state_code").val('');
	}
	$("#shipment_type_id").on("change", function(){
		if($(this).val() != '')			
		{
			console.log($(this).attr('data-weight'));
			$("#shipment_billing_weight").val($(this).children("option:selected").attr('data-weight'));
			var data={
			action	:	'shipment/getshipmentcode',
			shipment_type_id : $(this).val()
			};
				
			$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
					beforeSend: function(){
					message("process|Validating Shipping Address...",0);
					dissableSubmission();
				},		
				success:function(output){
					enableSubmission(output);
					var arr	=	JSON.parse(output);	
					if(arr[0]==200)
					{
						$("#shipment_service_code").html(arr[2]);
					}
					message(arr[1],500);
				}
			});
		}
		else{
			$("#shipment_service_code").html('');
		}
			
	});
	
	$("#shipment_to_country_code").on("change", function(){
		if($(this).val() == 'US' || $(this).val() == 'CA'){
			$("#div_shipment_to_state_code").show();
		}
		else{
			$("#div_shipment_to_state_code").hide();			
			$("#shipment_to_state_code").val('');
		}			
	});
	
	if('<?php echo isset($shipment_to_country_code) ? $shipment_to_country_code : ''?>' == 'US' || '<?php echo isset($shipment_to_country_code) ? $shipment_to_country_code : ''?>' == 'CA' )
	{
		$("#shipment_to_state_code").val('');	
	}
	
	$("#btnAddressValidation").on('click', function(){
		if(validateFields("shipment_to_city, shipment_to_country_code, shipment_to_post_code"))
		{
			var data={
			action	:	'shipment/validateshippingaddress',
			shipment_type_id : $("#shipment_type_id").val(),
			shipment_to_city  : $("#shipment_to_city").val(),
			shipment_to_country_code  : $("#shipment_to_country_code option:selected").val(),
			shipment_to_post_code  : $("#shipment_to_post_code").val()
		};
			
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Validating Shipping Address...",0);
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
	});
});
function proceedToGetQoutes()
{
	var formFields	=	"shipment_type_id, shipment_service_code, shipment_billing_weight, shipment_to_name, shipment_to_phone_number, shipment_to_address_line1, shipment_to_city, shipment_to_country_code, shipment_to_post_code, shipment_description";
	if($("#shipment_to_country_code").val() == 'US' || $("#shipment_to_country_code").val() == 'CA')
		formFields += ', shipment_to_state_code';
	if(validateFields(formFields))
	{
		if($(".shipment_products:checked").length == 0){
			message('warning|Please select at least one product',3000);
			return false;
		}
		
		var data={
			action	:	$("#action").val()
		};
		data = $.extend(data, $("#addshipment").serializeFormJSON());		
	
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Getting Shipment Qoutes...",0);
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{					
					Redirect('shipmentconfirm/'+arr[2]);
				}
				message(arr[1],3000);
			}
		})	
	}
}

</script> 