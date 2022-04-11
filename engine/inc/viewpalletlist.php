<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> 
      	<i class="fa fa-align-justify"></i> Pallet List 
        <div class="card-actions">
            <a class="redirect" data-title="Add New Pallet" title="Add New Pallet" href="<?php echo $app->siteUrl("addpallet");?>"><i class="icon-plus icons font-2xl d-block m-t-2"></i></a>
            <a data-title="Carrier PDF Report" title="Generate PDF Report" href="#" onclick="newWindow('<?=DOC::PALLETLIST()?>')"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
        </div>
      </div>
      
       <div class="block-fluid table-sorting clearfix">
       
        <table id="tbldatatable_pallet" class="table table-striped">
          <thead>
            <tr>
              <th>Code</th>
              <th class="hidden-xs hidden-sm hidden-md">Name</th>
              <th>Items</th>
              <th class="hidden-xs hidden-sm hidden-md">isFull</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			$Pallet = new Pallet(0);
			echo $Pallet->getRecords();
			?>
          </tbody>
          <tfoot>
          </tfoot>
        </table> 
       </div>       
      
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">

function statusAction(field)
{
	var data={
			action	:	"collection/updatecarrierstatus",
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


$("#tbldatatable_pallet").dataTable();
</script> 
