<div class="row">
  <div class="col-sm-12">
    <div class="card">
    <div class="card-header"><i class="fa fa-align-justify"></i> <strong>
        <?=$formHeading?>
        </strong>        
      </div>
      <div class="card-block">
        <div class="row">
            <div class="col-md-12">
            <table id="techproducttable" class="table table-bordered" width="100%" style="width:100%">
                <thead>
                    <tr>                        
						<th>Tech</th>
						<th class="hidden-xs hidden-md hidden-sm visible-lg">Store</th>
                        <th class="hidden-xs hidden-md hidden-sm visible-lg">SKU</th>
						<th class="hidden-xs hidden-md hidden-sm visible-lg">Batch</th>
                        <th class="hidden-xs hidden-md hidden-sm visible-lg">Age</th>
                        <th class="hidden-xs hidden-md hidden-sm visible-lg">Name</th>
                        <th>Sr.No.</th>
                        <th width="30px">Action</th>
                    </tr>
                </thead>
                <tbody>
				<?php
				$product = new Product();
				$_productCollection  = $product->getProductUnderTechnician();
				if(count($_productCollection)){
					foreach($_productCollection as $_product){
						$chatCodearr = explode("|", $_product['lot_code']);
						$_product['chat_code'] = $_product['id'].'|'.$chatCodearr[0];
				?>
					<tr>
						<td class="sorting_1">
							<div class="avatar" data-trigger="hover" data-toggle="popover-ajax" data-popover-action="user" data-popover-id="<?php echo $_product['user_id']?>">
								<img class="img-avatar" src="<?php echo $app->basePath($_product['user_image'])?>" height="40px">
							</div>
							<span class="hidden-xs hidden-md hidden-sm visible-lg"><?php echo $_product['user_fname']?> <?php echo $_product['user_lname']?></span>
						</td>
						<td class="hidden-xs hidden-md hidden-sm visible-lg"><?php echo $_product['origin']?></td>
						<td class="hidden-xs hidden-md hidden-sm visible-lg"><?php echo $_product['code']?><br/><?php echo $_product['sku']?></td>
						<td class="hidden-xs hidden-md hidden-sm visible-lg"><?php echo $_product['batch_code']?></td>
						<td class="hidden-xs hidden-md hidden-sm visible-lg"><?php echo $_product['age']?></td>
						<td class="hidden-xs hidden-md hidden-sm visible-lg"><?php echo $_product['name']?></td>
						<td><?php echo $_product['srno']?></td>
						<td>
							<div class="btn-group">
								<button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								</button>
								<div class="dropdown-menu dropdown-menu-right">    
									<a class="dropdown-item redirect" href="<?php echo $_product['url']?>"><i class="fa fa-share-square-o fa-lg m-t-2"></i> View</a>
									<a class="dropdown-item" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?php echo $_product['chat_code']?>', 'Log Report')"><i class="fa fa-comments-o fa-lg m-t-2"></i> Comment Log</a>
									<a class="dropdown-item" href="javascript:newWindow('<?php echo $_product['label']?>')"><i class="fa fa-barcode fa-lg m-t-2"></i> Print Barcode</a>
								</div>
							</div>
						</td>
					</tr>
				<?php
					}
				}
				?>
                </tbody>
            </table>            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
var table = $('#techproducttable').DataTable();
</script> 