<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> <?php echo $formHeading; ?>
      <div class="card-actions">
      <?php if(isAdmin()):?>
            <a data-title="Add New Induction" class="redirect" title="Add New Induction" href="<?php echo $app->siteUrl("addinduction");?>"><i class="icon-plus icons font-2xl d-block m-t-2"></i></a>
            <a data-title="Complaint PDF Report" title="Generate PDF Report" href="javascript:newWindow('<?=DOC::EMPINDUCTIONLIST();?>');"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
            <?php endif; ?>
        </div>
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortableTable" class="table table-striped">
          <thead>
            <tr>
              <th>Employee</th>
              <th>Date</th>
              <th>Info</th>
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
				"action"	:	"viewempinductionlist",				
		   };
$(document).ready(function() {
    $('#tblSortableTable').DataTable( {
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
		//"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[6]);},
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [4], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 1,3 ] }]
    } );
} );
	
function delEmpInduction(eic_id){
	if(eic_id != 0)
	{
		var data={
					action	:	'employee/delempinduction',
					eic_id : eic_id
				};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#row_"+eic_id).remove();			
				}
				message(arr[1]);
			}
		})	
	}
}

function completeEmpInduction(eic_id){
	if(eic_id != 0)
	{
		var data={
					action	:	'employee/completeempinduction',
					eic_id : eic_id
				};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#row_"+eic_id).find('.completeinduction').remove();		
				}
				message(arr[1]);
			}
		})	
	}
}
</script> 
