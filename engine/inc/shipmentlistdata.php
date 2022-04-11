<div class="row">  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> <?=$formHeading?>
      </div>
      <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Code</th>
              <th>Comp.</th>
			  <th>User</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">Charges</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">Tracking</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">Country</th>
              <th>Date</th>
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
								
		   };
	var coumnter = 0;
	var datatable;
		var postdata = {
		action	:	"viewshipmentlist"
	};

datatable = $('#tblSortable').DataTable( {
	"processing": true,
	"serverSide": true,
	"bStateSave": true,
	"ajax":  {
		"url": "<?=$app->basePath("server_processing.php")?>",
		"type": "POST",
		"data": function ( d ) {
			$.extend(d, postdata);
		}
	},
	'fnCreatedRow': function (nRow, aData, iDataIndex) {
        $(nRow).find('td').not(":last").attr('onclick', "Redirect('"+aData[8]+"')"); // or whatever you choose to set as the id
    },
	"order": [[ 0, 'desc' ]],
	columnDefs: [{ targets: [7], orderable: false },
				 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 3,4,5 ] },
				 { className: "word_break", "targets": [2] }],
} );
	
</script> 
