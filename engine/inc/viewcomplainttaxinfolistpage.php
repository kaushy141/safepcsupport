<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Complaint Vat Tax List 
      <div class="card-actions">
      <?php if(isAdmin()):?>
            <a data-title="Add Tax Info" title="Add Tax Info" href="<?php echo $app->siteUrl("addcomplainttaxinfo");?>" href="#"><i class="icon-star icons font-2xl d-block m-t-2"></i></a>
            <a data-title="Tax Info PDF Report" title="Tax Info PDF Report" href="#" onclick="newWindow('<?=$app->basePath("report.php?format=tax-list")?>')"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
            <?php endif; ?>
        </div>
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Vat%</th>
              <th>E%</th>
              <th>Z%</th>
              <th>N%</th>
              <th>R%</th>
              <th>S%</th>
              <th>Status</th>
              <th>Time</th>
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
				"action"	:	"viewtaxinfolist",				
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
		columnDefs: [{ targets: [], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [  ] }]
    } );
} );

</script> 
