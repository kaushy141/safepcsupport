<div class="row" id="collection_form_container">
  <div class="col-xs-12 col-sm-12 col-md-12">
  <form id="confirmShipmentForm" name="confirmShipmentForm">
    <div class="card">
      <div class="card-header"><i class="fa fa-align-justify"></i>Shipment
      </div>
      <div class="card-block">        
		<div class="row">
		  <div class="col-xs-12 col-sm-12 col-md-12">			
            <div class="row">
			<?php if($shipment_id == 0 && isset($responseMessage)){?>		
				<div class="col-xs-12 col-sm-12 col-lg-12 text-center">
					<div class="alert alert-<?php echo strtolower(trim($responseMessage['status']))?>" role="alert"><?php echo strtolower(trim($responseMessage['message']))?></div>
				</div>
			<?php }?>
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<div class="card">
						<div class="card-header"><i class="fa fa-file-text"></i> Shipping Company</div>
						<div class="card-body p-1">
							<div class="text-center"><img src="<?php echo $app->basePath($shipmentTypeData['shipment_type_image'])?>"></div>							
							<div class="text-value text-center pt-1"><?php echo $shipmentTypeData['shipment_type_name']?></div>
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<div class="card">
						<div class="card-header"><i class="fa fa-file-text"></i> Shipper</div>
						<div class="card-body p-1">
							<div class="text-value"><?php echo $shipmentShipperData['shipment_shipper_name']?></div>
							<div class="text-value">Shipper number : <?php echo $shipmentShipperData['shipment_shipper_number']?></div>
							<div><?php echo $shipmentShipperData['shipment_shipper_city']?>, <?php echo $shipmentShipperData['shipment_shipper_post_code']?></div>			
							<small class="text-muted"><?php echo $shipmentShipperData['shipment_shipper_address_line1']?></small>
							
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<div class="card">
						<div class="card-header"><i class="fa fa-file-text"></i> Shipping Origin</div>
						<div class="card-body p-1">
							<div class="text-value"><?php echo $shipmentOriginData['shipment_origin_name']?></div>
							<div>Company : <?php echo $shipmentOriginData['shipment_origin_company']?></div>
							<div><?php echo $shipmentOriginData['shipment_origin_city']?>, <?php echo $shipmentOriginData['shipment_origin_post_code']?></div>
							<small class="text-muted"><?php echo $shipmentOriginData['shipment_origin_address_line1']?></small>
							
						</div>
					</div>
				</div>
				
				
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<div class="card">
						<div class="card-header"><i class="fa fa-file-text"></i> Shipping Charge</div>
						<div class="card-body p-1">
                       	
                            <table class="table">
							<tr>
								<td>Transporation Charge :</td>
								<td class="text-right"><?php echo (trim($MonetaryValue))?> <?php echo (trim($CurrencyCode))?></td>
							</tr>
							<tr>
								<td>Service Charge :</td>
								<td class="text-right"><?php echo (trim($ServiceOptionsCharges))?> <?php echo (trim($CurrencyCode))?></td>
							</tr>
							<tr>
								<td>Total Charge :</td>
								<td class="text-right"><?php echo (trim($TotalCharges))?> <?php echo (trim($CurrencyCode))?></td>
							</tr>
							</table>
                            						
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<div class="card">
						<div class="card-header"><i class="fa fa-file-text"></i> Shipment Weight</div>
						<div class="card-body p-1">
							<table class="table">
							<tr>
								<td>Weight :</td>
								<td class="text-right"><?php echo trim($Weight)?></td>
							</tr>
							<tr>
								<td>Unit :</td>
								<td class="text-right"><?php echo trim($WeightCode)?></td>
							</tr>
							<tr>
								<td>Identification No. :</td>
								<td class="text-right"><?php echo trim($ShipmentIdentificationNumber)?></td>
							</tr>
							</table>							
						</div>
					</div>
				</div>
                
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<div class="card">
						<div class="card-header"><i class="fa fa-file-text"></i> Shipment Service</div>
						<div class="card-body p-1">
							<table class="table">
							<tr>
								<td>Service Code</td>
								<td class="text-right"><?php echo trim($shipment_service_code)?></td>
							</tr>
							<tr>
								<td>Service Name :</td>
								<td class="text-right"><?php echo trim(ShipmentType::getServiceNameByCode($shipmentTypeData['shipment_type_code'], trim($shipment_service_code)))?></td>
							</tr>
							
							</table>							
						</div>
					</div>
				</div>                
                
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<div class="card">
						<div class="card-header"><i class="fa fa-file-text"></i> Shipment To</div>
						<div class="card-body p-1">
							<table class="table">
							<tr>
								<td>Name :</td>
								<td class="text-right"><?php echo trim($AttentionName)?></td>
							</tr>
							<tr>
								<td>Phone :</td>
								<td class="text-right"><?php echo trim($PhoneNumber)?></td>
							</tr>
                            <tr>
								<td>Email :</td>
								<td class="text-right"><?php echo trim($Email)?></td>
							</tr>
							<tr>
								<td>Address :</td>
								<?php ?>
								<td class="text-right"><?php echo trim($address['AddressLine1'])?>, <?php echo trim($address['City'])?>, <?php echo trim(Country::getValueByParameter('name', 'iso2', $address['CountryCode']));?></td>
							</tr>
							<tr>
								<td>Postcode :</td>
								<td class="text-right"><?php echo trim($address['PostalCode'])?></td>
							</tr>
							</table>							
						</div>
					</div>
				</div>
				<?php
				if(isset($trackingData))
				{?>
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<div class="card">
						<div class="card-header"><i class="fa fa-file-text"></i> Tracking</div>
						<div class="card-body p-1">
							<table class="table">
							<tr>
								<td>Tracking Number :</td>
								<td class="text-right"><?php echo trim($trackingData['shipment_tracking_number'])?></td>
							</tr>
							<tr>
								<td>Download Label :</td>
								<td class="text-right">
                                <a href="<?php echo DOC::SHIPMENTLABEL(isset($shipment_id)? md5($shipment_id):0)?>" class="btn btn-success" target="new"> <i class="fa fa-download"></i> Download</a>
                                </td>
							</tr>
							<tr>
								<td>Track Shipment :</td>
								<td class="text-right">
									<a data-shipment-id="<?php echo $shipment_id?>" id="labeldownload" class="shipmenttrack btn btn-sm btn-info"><i class='fa fa-search'></i> Track</a>
								</td>
							</tr>
							</table>							
						</div>
					</div>
				</div>
				<?php	
				}
				?>
				<?php
				if(isset($shipment_description))
				{?>
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<div class="card">
						<div class="card-header"><i class="fa fa-file-text"></i> Shipment Details</div>
						<div class="card-body p-1">
							<table class="table">
							<tr>
								<td>Description:</td>
								<td class="text-right"><?php echo trim($shipment_description)?></td>
							</tr>							
							</table>					
						</div>
					</div>
				</div>
				<?php	
				}
				?>
                
              
           </div>
		   <?php if(1){?>
			<div class="row">
				<div class="col-xs-12">			
					<h5 class="card-title">
						<span class="icon icon-primary mb-3 icon-sm">
							<i class="fa fa-forward" aria-hidden="true"></i>
						</span> 
						<span>Selected Products</span>
					</h5>
					<div class="table-responsive">
					<table id="tableShipmentProduct" class="table">
					<thead>
						<tr>
							<th class="hidden-xs hidden-md hidden-sm visible-lg">Name</th>
							<th>SKU</th>
							<th class="hidden-xs hidden-md hidden-sm visible-lg">SR.No.</th>
							<?php if($shipment_id){?>
							<th>Tracking Id</th>
							<?php }?>
						</tr>
					</thead>
					<tbody>
					<?php 
					if(isset($shipment_products) && count($shipment_products)){
					foreach($shipment_products as $_product){
					?>
						<tr>
							<td class="hidden-xs hidden-md hidden-sm visible-lg"><i class="fa fa-check-circle text-success"></i> <?php echo $_product['product_name']?></td>
							<td><?php echo $_product['product_sku']?></td>
							<td class="hidden-xs hidden-md hidden-sm visible-lg"><?php echo $_product['product_srno']?></td>
							<?php if($shipment_id){?>
							<td><?php echo $_product['product_track']?></td>
							<?php }?>
						<tr>
					<?php }
					}?>
					<tbody>
					</table>
				</div>
				</div>
			</div>
		   <?php }?>
          </div>
		</div>
				
<?php if($shipment_id){?>		
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		  <div class="card-header"> <i class="fa fa-align-justify"></i> Label download History </div>
		   <div class="block-fluid table-sorting clearfix table-responsive">
			<table id="tbldatatable" class="table table-striped">
			  <thead>
				<tr>
				  <th>User</th>
				  <th class="hidden-xs hidden-md hidden-sm visible-lg">Name</th>
				  <th>Download on</th>
				</tr>
			  </thead>
			  <tbody id="sellingrecords">
				<?php 
				$shipment = new Shipment($shipment_id);
				if($record = $shipment->getDownlaodRecords())
					echo $record;
				else
					echo "<tr><td colspan='4'> No Download record</td></tr>";
				?>
			  </tbody>
			</table>
			
		  </div>
		</div>
	</div>
</div>
<?php }?>

 <?php if(isAdmin() && $isShipmentCreated):?>
	<div class="row">
		  <div class="col-md-12 mt-1 text-right">
			<?php 
			$creator = new Employee((isset($trackingData['shipment_created_by']) && $trackingData['shipment_created_by'] != 0 ) ? $trackingData['shipment_created_by'] : getLoginId());
			$creatorData = $creator->getDetails();
			?>
			<div class="pull-right pl-1"> <img class="img img-circle" style="margin-top:0px; margin-bottom:0px; height:40px;" src="<?php echo getResizeImage($creatorData["user_image"],50)?>"/></div>
			<div class="pull-right">Originally created by <?php echo $creatorData['user_name']?> <i class="fa fa-check-circle text-success"></i><br/>
<span class="text-muted" style="font-size: 0.9em;"><?php echo dateView($trackingData['shipment_created_date'], 'NOW')?> <?php echo dateView($trackingData['shipment_created_date'], 'FULL')?></span>			</div>
		  </div>
	</div>	
	<?php endif;?>
        <!--/row--> 
      </div>
    </div>    
    <div class="card-footer">
      <div class="row">
        <div class="col-sm-12">
		<?php if(!$isShipmentCreated){?>
          <button type="button" id="btn_complaint_submit" onClick="confirmMessage.Set('Are you sure to confirm shipment qoutes...?', 'confirmShipmentQoutes');" class="btn btn-success mt-0 submission_handler_btn"><i class="fa fa-check-circle fa-lg"></i> Confirm Shipment Quotes</button>
		<?php }else{?>
		<a class="btn btn-default redirect" href="<?php echo $app->basePath($shipment_for == "weborder" ? "viewwebsiteorder":"")?>">Back to <?php echo $shipment_for; ?> list</a>
		<?php }?>
        </div>
      </div>
    </div>
    <input type="hidden" id="action" name="action" value="<?=$action;?>"  />
    <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
    <input type="hidden" id="request_id" name="request_id" value="<?=isset($request_id)?$request_id:"0";?>"  />
	<input type="hidden" id="shipment_id" name="shipment_id" value="<?=isset($shipment_id)?$shipment_id:"0";?>"  />
    </div>
  </form>
</div>
</div>
<script type="text/javascript">

$("#dpdlabeldownload").on("click", function(){
	var data={
		action	:	'shipment/downloadshipmentlabel',
		shipment_id	:	$('#shipment_id').val()
	};

	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Getting Shipment label...",0);
			dissableSubmission();
		},		
		success:function(output){
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				/*var datasend = arr[2];
				xhttp.open("GET", datasend.url, true);
				xhttp.setRequestHeader('Accept', datasend.header1);
				xhttp.setRequestHeader('GeoClient', datasend.header2);
				xhttp.setRequestHeader('GeoSession',datasend.header3);*/
			}
			message(arr[1],1000);
		}
	});
	return false;
});

$('.shipmenttrack').on('click', function(){
	var data={
		action	:	'shipment/trackshipment',
		tracking_id	:	$(this).attr('data-track-id')
	};

	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Tracking Shipment status...",0);
		},		
		success:function(output){
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				setPopup(data.tracking_id, "Tracking Details");
				modal.Body(arr[2]);
				modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
				modal.Show();
			}
			message(arr[1],3000);
		}
	});
	return false;
});
function confirmShipmentQoutes()
{			
	var data={
		action	:	$("#action").val()
	};
	data = $.extend(data, $("#confirmShipmentForm").serializeFormJSON());		

	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Submitting Shipment Qoutes...",0);
			dissableSubmission();
		},		
		success:function(output){
			enableSubmission(output);
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{					
				Redirect(arr[2]);
			}
			message(arr[1],3000);
		}
	});
	
}

</script> 