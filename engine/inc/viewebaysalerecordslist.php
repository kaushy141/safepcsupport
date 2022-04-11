<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> 
      	<i class="fa fa-align-justify"></i> Ebay Sales Records List        
      </div>
      <div class="table-responsive">
       <div class="block-fluid table-sorting clearfix">
       
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Sales Number</th>
              <th>Buyer Fullname</th>
              <th>Buyer Contact</th>
              <th>Address</th>
              <th>Item Title</th>
              <th>Payment Method</th>
              <th>Postage Service</th>
              <th>Transaction ID</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
          </tfoot>
        </table> 
       </div>       
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">


var data = {
				"action"	:	"viewebaysalesrecords",				
		   };
$(document).ready(function() {
    $('#tblSortable').DataTable( {
        "processing": true,
        "serverSide": true,
		"bStateSave": true,
        "ajax":  {
            "url": "<?=$app->basePath("server_processing.php")?>",
            "type": "POST",
			"data": data
        },
		"order": [[ 6, 'desc' ]],
		columnDefs: [{ targets: [6], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,3,4,6 ] }]
    } );
} );

</script> 
