<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> 
      	<i class="fa fa-align-justify"></i> Carrier List 
        <div class="card-actions">
            <a data-title="Add New Carrier" title="Add New Carrier" class="redirect" href="<?php echo $app->siteUrl("addcarrier");?>"><i class="icon-user-follow icons font-2xl d-block m-t-2"></i></a>
            <a data-title="Carrier PDF Report" title="Generate PDF Report" href="#" onclick="newWindow('<?=DOC::CARRIERLIST()?>')"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
        </div>
      </div>
      <div class="table-responsive">
       <div class="block-fluid table-sorting clearfix">
       
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Logo</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Address</th>
              <th>Licence</th>
              <th>Added</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			$carrier = new Carrier(0);
			echo $carrier->getRecords();
			?>
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

function statusAction(field)
{
	var data={
			action	:	"updatecarrierstatus",
			status	:	Number(field.checked),
			idvalue		:	field.value				
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...",0);
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);
				message(arr[1],2000);
			}
		})	
}


$("#tbldatatable").dataTable({"iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100], "sPaginationType": "full_numbers", "aoColumns": [ { "bSortable": false }, null, null, null, null]});

$("#tbldatatable").dataTable({"iDisplayLength": 10, "sPaginationType": "full_numbers","bLengthChange": false,"bFilter": false,"bInfo": false,"bPaginate": true, "aoColumns": [ { "bSortable": false }, null, null, null, null]});

</script> 
