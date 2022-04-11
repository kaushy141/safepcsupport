<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i><?php echo $formHeading; ?>
      
      </div>	  
       <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>Code</th>
              <th>Reference</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Created</th>
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
	var formdata = {};
	var postdata = {
			action	:	"viewcreditnotelist"
		}

var datatable = $('#<?php echo $TABEL_ID;?>').DataTable( {
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
	"order": [[ 0, 'desc' ]],
	columnDefs: [{ targets: [5], orderable: false },
				 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 3,4 ] },
				 { className: "word_break", "targets": [1] }],
} );


</script> 
