<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> 
      	<i class="fa fa-align-justify"></i> Customer List                
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortableActivity" class="table table-striped">
          <thead>
            <tr>
              <th>Pic</th>
              <th>Name</th>
              <th>Contact</th>
              <th>Address</th>
              <th>Type</th>
              <th>Status</th>
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
				"action"	:	"viewactivitylist",				
		   };
$(document).ready(function() {
    $('#tblSortableActivity').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax":  {
            "url": "<?=$app->basePath("server_processing.php")?>",
            "type": "POST",
			"data": data
        },
		"order": [[ 2, 'asc' ]],
		columnDefs: [{ targets: [6], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,3 ] }]
    });
});
</script> 
