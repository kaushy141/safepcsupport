<div class="row">
  <div class="col-lg-3">
	  <div class="card">
		  <form id="bachproductfilter" name="bachproductfilter">
		<div class="card-header"> <i class="fa fa-align-justify"></i> Filter 
		<a id="resetfilteration" href="#" class="pull-right">Reset</a>
		</div>
			<div class="block-fluid table-sorting clearfix">
				<div class="row">
					<div class="col-xs-12" id="appliedfilter"></div>
				</div>
			</div>
			  <div style="padding: 5px">
      	<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_type';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Type <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
        <?php if(0){?>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_model';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Model <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_operating_system';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Operating System <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
        <div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_batch_type';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Batch type<i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
        <?php }?>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_ram';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">RAM <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_processor';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Processor <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_ssd';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">SSD <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="SSD <?php echo htmlspecialchars($item);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_hdd';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">HDD <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="HDD <?php echo htmlspecialchars($item);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_fusion_drive';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Fusion Drive <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Fusion <?php echo htmlspecialchars($item);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_processor_speed';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Processor speed <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Pro.Speed <?php echo htmlspecialchars($item);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?> GHz</label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_screen_size';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Screen size <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Screen <?php echo htmlspecialchars($item);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?>"</label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_release';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Release year<i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_grade';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Grade<i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_batch_code';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Batch Code<i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><?php echo htmlspecialchars($item);?></label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$field = 'product_under_technician';?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Under Technician <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
				<div class="form-check checkbox filtercheckbox">
					<input class="form-check-input ml-0" data-label="Under Technician" name="<?php echo $field?>" id="<?php echo $field.'_1'?>" type="checkbox" value="1"> &nbsp; 
					<label class="form-check-label" for="<?php echo $field.'_1'?>">Yes</label>
				</div>	
				<div class="form-check checkbox filtercheckbox">
					<input class="form-check-input ml-0" data-label="Not Under Technician" name="<?php echo $field?>" id="<?php echo $field.'_0'?>" type="checkbox" value="0"> &nbsp; 
					<label class="form-check-label" for="<?php echo $field.'_0'?>">No</label>
				</div>	
			</div>
			</div>
		</div>
		
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'product_under_technician_id';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Technician <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			<?php
			$records = $batchProduct->getTechnicianFilteration($field);
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['user_fname'].' '.$item['user_lname']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['user_id']);?>"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_'.$i?>"><img class="img-avatar" src="<?php echo $item['user_image']?>" height="24px"/> <?php echo htmlspecialchars($item['user_fname'].' '.$item['user_lname']);?> (<?php echo $item['record'];?>)</label>
				</div>
				<?php
				}
			}
			?>
			</div>
			</div>
		</div>
		
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$field = 'product_store_location';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Store Location <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
			
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Store-UK" name="<?php echo $field?>" id="<?php echo $field.'_uk'?>" type="checkbox" value="UK"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_uk'?>"><img class="img-avatar" src="<?php echo $app->basePath('img/system/flag/flag_uk.png')?>" height="24px"/> UK</label>
				</div>
				
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Store-UAE" name="<?php echo $field?>" id="<?php echo $field.'_uae'?>" type="checkbox" value="UAE"> &nbsp; 
				<label class="form-check-label" for="<?php echo $field.'_uae'?>"><img class="img-avatar" src="<?php echo $app->basePath('img/system/flag/flag_uae.png')?>" height="24px"/> UAE</label>
				</div>
				
			</div>
			</div>
		</div>
		
		<div class="block-fluid table-sorting clearfix">
			<div class="row">
			<?php 
			$batchProduct = new BatchProduct();
			$field = 'data_filter';
			?>
			<label class="col-md-12 col-form-label"><h5 class="filterheading">Data Filter <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5></label>
			<div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Blank part Number" name="product_part_number" id="product_part_number" type="checkbox" value=""> &nbsp; 
				<label class="form-check-label" for="product_part_number"> Blank Part Number</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Blank SKU" name="product_sku" id="product_sku" type="checkbox" value=""> &nbsp; 
				<label class="form-check-label" for="product_sku"> Blank SKU</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Blank Serial Number" name="product_serial_number" id="product_serial_number" type="checkbox" value=""> &nbsp; 
				<label class="form-check-label" for="product_serial_number"> Blank Serial Number</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Verified" name="product_verified" id="product_verified" type="checkbox" value="null|||is not"> &nbsp; 
				<label class="form-check-label" for="product_verified"> Verified</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Unverified" name="product_verified" id="product_verified_2" type="checkbox" value="null|||is"> &nbsp; 
				<label class="form-check-label" for="product_verified_2"> Unverified</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="InStock" name="product_in_stock" id="product_in_stock_1" type="checkbox" value="1"> &nbsp; 
				<label class="form-check-label" for="product_in_stock_1"> In Stock</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="Out of stock" name="product_in_stock" id="product_in_stock_2" type="checkbox" value="0"> &nbsp; 
				<label class="form-check-label" for="product_in_stock_2"> Out of Stock</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="On the way" name="product_is_on_way" id="product_is_on_way_1" type="checkbox" value="1"> &nbsp; 
				<label class="form-check-label" for="product_is_on_way_1"> On the way</label>
				</div>
				<div class="form-check checkbox filtercheckbox">
				<input class="form-check-input ml-0" data-label="On warehouse" name="product_is_on_way" id="product_is_on_way_2" type="checkbox" value="0"> &nbsp; 
				<label class="form-check-label" for="product_is_on_way_2"> On warehouse</label>
				</div>
			</div>
			</div>
		</div>
        
        
				  </div>
		</form>
	</div>
	</div>
  <div class="col-lg-9">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Product List 
      	<div class="card-actions">
		<a id="exportxslx" class="card-header-action" title="Export to Excel File" href="#">
		<i class="fa fa-file-excel-o"></i>
		</a>
		<a id="printbarcode" class="card-header-action" title="Print Bar code" href="#">
		<i class="fa fa-print"></i>
		</a>
		<a class="card-header-action redirect" title="Add new Batch Product" href="<?php echo $app->basePath('addbatchproduct')?>">
		<i class="fa fa-plus-square"></i>
		</a>
		</div>
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Type</th>
              <th>Code</th>
              <th>Name</th>
              <th>#SerialNo</th>
              <th>Condition</th>
              <th>InStock</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
  
  <!--/col--> 
</div>
<script type="text/javascript">
$('.form-check-input, .filtercontroller, #printbarcode, #exportxslx, #tblSortable').off();
var data = {
								
		   };
	var coumnter = 0;
	var datatable;
	var formdata = {};
		if(app_filter_state == 1 && window.localStorage.getItem(window.location.href)){
		formdata = JSON.parse(window.localStorage.getItem(window.location.href));

		$('.form-check-input').each(function(){
			//console.log($(this).attr('name'));
			if(formdata.hasOwnProperty($(this).attr('name'))){
				var filterVal = formdata[$(this).attr('name')];
				//console.log(filterVal);
				if(filterVal.indexOf($(this).val()) !== -1){
					$(this).attr("checked", true);					
					$("#appliedfilter").append(getFilterLabel($(this)));
				}
			}
		})
	}
	var postdata = {
			action	:	"viewbatchproductlist",
			filter  :   formdata
		}
$(document).ready(function() {   
	
	
	$('.form-check-input').on('change', function(){
		formdata = $("#bachproductfilter").serializeFormJSON();		
		
		postdata = {
			action	:	"viewbatchproductlist",
			filter  :   formdata
		}
		//console.log(postdata);
		
		if(typeof datatable != 'undefined'){
			if(!isDataTableRunning){
				datatable.clear().draw();
				isDataTableRunning = true;
			}
		}
		if($(this).is(":checked"))
			$("#appliedfilter").append(getFilterLabel($(this)));
		else
			$("#tag_"+$(this).attr('id')).remove();
		addfiltersearch();
	});
	
	$('.filtercontroller').on('click', function(){
		var id = $(this).attr('data-id');
		if($(this).hasClass('fa-chevron-down')){
			$("#label_"+id).show();
		}
		else
			$("#label_"+id).hide();
		$(this).toggleClass('fa-chevron-down fa-chevron-up');
	})
	$("div.col-form-label").each(function(){
		$(this).hide();
	})
	$(document).on("click", ".filtercloser", function(e){
		var id = $(this).attr('data-id');
		$("#"+id).prop("checked", false);
		$("#tag_"+id).remove();
		$("#"+id).trigger("change");
		addfiltersearch();
	})
	$("#printbarcode").on("click", function(e){
		var formdata = $("#bachproductfilter").serializeFormJSON();		
		var data = {
			action	:	"product/printbatchproductbarcode",
			filter  :   formdata
		}
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);
				if(arr[0] == 200){
					window.open(sitePath+'report/batchproductbarcode/'+arr[2], true);
				}
				message(arr[1]);
			}
		});
	});
	
	var exportData = <?php echo json_encode(BatchProduct::$exportData);?>;
	$("#exportxslx").on("click", function(e){
		setPopup(0, "Batch Product Export to excel");
		var bodyHtml = '<div class="col-md-12">';
		bodyHtml +='<div class="row">';			
		
		bodyHtml +='<div class="col-sm-12"><div class="row"><h4><i class="fa fa-upload"></i> Select Column to Export</h4></div></div>';
		
		$.each(exportData, function (key, val) {
			bodyHtml +='<div class="col-sm-6"><div class="row"><label for="bacth_colum_export_'+key+'" class="switch switch-icon switch-primary"><input id="bacth_colum_export_'+key+'" name="exportcolumn[]" value="'+key+'" class="switch-input exportcolumnchk exportcolumn_'+key+'" type="checkbox"> <span class="switch-label" data-on="" data-off=""></span><span class="switch-handle"></span></label> <label for="bacth_colum_export_'+key+'"> &nbsp; '+val+'</label></div></div>';
		});
		bodyHtml +='</div>';
		
		bodyHtml +='</div>';
		modal.Body(bodyHtml);
		modal.Footer('<button type="reset" class="btn btn-default">Reset</button><button type="button" id="exportbatchexcel" class="btn btn-outline-success" >Export</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
		modal.Show();	
	
		// var formdata = $("#bachproductfilter").serializeFormJSON();		
		// var data = {
			// action	:	"product/batchproductexportexcel",
			// filter  :   formdata
		// }
		// $.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				// beforeSend: function(){
				// message("process|Connecting...", 0);
				// dissableSubmission();
			// },		
			// success:function(output){ 
				// enableSubmission(output);
				// var arr	=	JSON.parse(output);
				// if(arr[0] == 200){
					// window.open(sitePath+'report/batchproductexportexcel/'+arr[2], true);
				// }
				// message(arr[1]);
			// }
		// });
	});
	
	$(document).on("click", "#exportbatchexcel", function(){
		if($(".exportcolumnchk:checked").length == 0){
			message("warning|Please select atleast one column to export");
			return;
		}
		var formdata = $("#bachproductfilter").serializeFormJSON();	
		columns = []; 
		$(".exportcolumnchk:checked").each(function(){
			columns.push($(this).val());
		});
		var data = {
			action	:	"product/batchproductexportexcel",
			filter  :   formdata,
			columns :	columns
		}
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);
				if(arr[0] == 200){
					window.open(sitePath+'report/batchproductexportexcel/'+arr[2], true);
				}
				message(arr[1]);
			}
		});
	})
	
	
});

datatable = $('#tblSortable').DataTable( {
	"processing": true,
	"serverSide": true,
	"bStateSave": true,
	"ajax":  {
		"url": "<?=$app->basePath("server_processing.php")?>",
		"type": "POST",
		"data": function ( d ) {
			$.extend(d, postdata);
		},
		"complete": function (data) {
            isDataTableRunning = false;
        },
	},
	'fnCreatedRow': function (nRow, aData, iDataIndex) {
        $(nRow).find('td').not(":last").attr('onclick', "Redirect('"+aData[7]+"')"); // or whatever you choose to set as the id
    },
	"order": [[ 0, 'desc' ]],
	columnDefs: [{ targets: [6], orderable: false },
				 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,4,5 ] }],
} );
	
	
	function deleteBatchProduct(product_id){
		if(confirm('Are you sure to delete this record .. ?'))
		{
			var data = { 
							action	:	"product/deletebatchproduct", //system
							product_id : product_id 
					   };
			$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 
			
					beforeSend: function(){	
					message("process|Connecting...", 0);
					dissableSubmission();
				},		
				success:function(output){ 
					enableSubmission(output);
					var arr	=	JSON.parse(output);
					if(arr[0] == 200){
						
					}
					message(arr[1]);
				}
			});
		}		
	}
	
	function verifyBatchProduct(product_id, verify){
		if(confirm('Are you sure to verify this record .. ?'))
		{
			var data = { 
							action	:	"product/verifybatchproduct", 
							product_id : product_id ,
							verify : verify 
					   };
			$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
					beforeSend: function(){
					message("process|Connecting...", 0);
					dissableSubmission();
				},		
				success:function(output){ 
					enableSubmission(output);
					var arr	=	JSON.parse(output);
					if(arr[0] == 200){
						datatable.clear().draw();
					}
					message(arr[1]);
				}
			});
		}		
	}
</script> 