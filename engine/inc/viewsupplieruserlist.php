<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> 
      	<i class="fa fa-align-justify"></i> <?php echo $formHeading;?>
        <div class="card-actions">
            <a class="redirect" data-title="Add New Employee" title="Add New Employee" href="<?php echo $app->siteUrl("addemployee");?>"><i class="icon-user-follow icons font-2xl d-block m-t-2"></i></a>
        </div>
      </div>
      <div class="table-responsive">
       <div class="block-fluid table-sorting clearfix">
       
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Pic</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Address</th>
              <th>Type</th>
              <th>Added</th>
              <th>Status</th>
              <th>Action</th>
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

function statusAction(field)
{
	var data={
			action	:	"employee/updateemployeestatus",
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

var data = {
				"action"	:	"viewsupplieruserlist",				
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
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [8], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,3,4,5,6 ] }]
    } );
} );

</script> 
