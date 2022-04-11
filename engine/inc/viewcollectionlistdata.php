<div class="row">  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Collection Request List 
      <div class="card-actions">
      <?php if(isAdmin()):?>
            <a data-title="Add New Complaint" class="redirect" title="Add New Collection" href="<?php echo $app->siteUrl("addcollection");?>"><i class="icon-star icons font-2xl d-block m-t-2"></i></a>
            <a data-title="Collection PDF Report" title="Generate PDF Report" href="javascript:newWindow('<?=DOC::COLLECTIONLIST();?>');"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
            <?php endif; ?>
        </div>
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>Date</th>
              <th>Code</th>
              <th>Customer</th>
              <th>Location</th>
              <th>Company</th>
              <th>Collector</th>
              <th>Status</th>
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
				"action"	:	"viewcollectionlist",				
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
		'fnCreatedRow': function (nRow, aData, iDataIndex) {
			$(nRow).find('td').not(":last").attr('onclick', "Redirect('"+aData[9]+"')"); // or whatever you choose to set as the id
		},
		"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[8]);},
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [6,7], orderable: false },
					 { className: "hidden-xs hidden-sm visible-md visible-lg", "targets": [ 3,4,5,6 ] },
					 { className: "word_break", "targets": [ 2 ] }
					]
    } );
} );


</script> 
