<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> <?php echo $formHeading; ?>      
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Img</th>
			  <th>Date</th>
			  <th>Info</th>
              <th>Section</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			//$Complaint = new Complaint(0);
			//echo $Complaint->getRecords();
			?>
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">

var data = {
				"action"	:	"tagnotificationlist",				
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
		'fnCreatedRow': function (nRow, aData, iDataIndex) {
        $(nRow).attr('id', 'row_' + aData[5]); // or whatever you choose to set as the id
    },
		"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[6]);},
		"order": [[ 1, 'desc' ]],
		columnDefs: [{ targets: [4], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 3 ] }]
    } );
} );
	

</script> 
