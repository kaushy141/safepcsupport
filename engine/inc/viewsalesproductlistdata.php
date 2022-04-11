<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i>Product List 
      <div class="card-actions">
      <?php if(isAdmin()):?>
            <a class="redirect" data-title="Add New Sales Invoice" title="Add New Sales Invoice" href="<?php echo $app->siteUrl("addproduct");?>" href="#"><i class="icon-star icons font-2xl d-block m-t-2"></i></a>
            <?php endif; ?>
        </div>
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>ProductID</th>
              <th>Name</th>
              <th>Qty</th>
              <th>Type</th>
              <th>Price</th>
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
var data = {
				"action"	:	"viewsalesproductlist",				
		   };
$(document).ready(function() {
    $('#<?php echo $TABEL_ID;?>').DataTable( {
        "processing": true,
        "serverSide": true,
		"bStateSave": true,
        "ajax":  {
            "url": "<?=$app->basePath("server_processing.php")?>",
            "type": "POST",
			"data": data
        },
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [0], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,3 ] }]
    } );
} );


</script> 
