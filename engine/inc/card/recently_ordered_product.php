<div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-2 mb-1">
    <div class="card">
      <div class="card-header" data-step="9" data-title="Recently order's Product" data-intro="Recently Ordered products from e-Commerce websites"> <i class="fa fa-align-justify"></i> Recently Ordered Products
        <div class="card-actions"> <a title="View Orders" href="<?php echo $app->siteUrl("viewwebsiteorder");?>"><i class="icon-handbag fa-lg"></i></a> </div>
      </div>
      <div class="card-body pb-1 px-1">
        <div class="row flex-row usercontainer">
          <?php 
	 $latestPro = WebsiteOrderProduct::getLtestOrderedProducts(12);
	  if($latestPro)
	  {
		foreach($latestPro  as $_product)
		{
			?>
          <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="userimageblock">
              <div class="text-center"><a target="new" href="<?php echo $_product['wo_product_url']; ?>"><img height="80px" class="img-flex" src="<?php echo $_product['wo_product_image']?>" /></a></div>
              <div class="text-center usernameblock"><a class="redirect" href="<?php echo $app->siteUrl("viewweborder/".$_product['wo_web_order_id']);?>"><?php echo limitText($_product['wo_product_sku'],20)?></a></div>
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