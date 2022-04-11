<div class="row">  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Lot List </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>User</th>
			  <th>Name</th>
              <th>Code</th>
              <th>Products</th>
              <th>Created</th>
              <th>Updated</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">
var data = {
				"action"	:	"viewlotlist",				
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
		columnDefs: [{ targets: [6], orderable: false },
					 { className: "hidden-xs hidden-sm visible-md visible-lg", "targets": [ 4,5 ] }
					]
    } );
} );


</script> 
