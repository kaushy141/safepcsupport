<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> <?php echo $formHeading; ?>
      <div class="card-actions">
      <?php if(isAdmin()):?>
            <a data-title="Add New Contract" class="redirect" title="Add New Contract" href="<?php echo $app->siteUrl("empcontract");?>"><i class="icon-star icons font-2xl d-block m-t-2"></i></a>
            <a data-title="Complaint PDF Report" title="Generate PDF Report" href="javascript:newWindow('<?=DOC::EMPCONTRACTLIST();?>');"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
            <?php endif; ?>
        </div>
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Country</th>
              <th>Contact</th>
              <th>Job</th>
              <th>Created</th>
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
				"action"	:	"viewempcontractlist",				
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
        $(nRow).attr('id', 'row_' + aData[6]); // or whatever you choose to set as the id
    },
		//"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[6]);},
		"order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [5], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2, 3,4 ] }]
    } );
} );
	
function delEmpContract(employee_id){
	if(employee_id != 0)
	{
		var data={
					action	:	'employee/delempcontract',
					employee_id : employee_id
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
					$("#row_"+employee_id).remove();			
				}
				message(arr[1]);
			}
		})	
	}
}
	
function sendContractCopyToEmployee(employee_id)
{
	if(employee_id != 0)
	{
		var data={
					action	:	'employee/sendcontractdoctoemployee',
					employee_id : employee_id
				};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);				
				message(arr[1]);
			}
		})	
	}
}
	
function getSignatureLink(signature_record_id){
	if(signature_record_id != 0)
	{
		var data={
					action		:	'system/getsignaturelink',
			signature_class		:	'ContractEmployee',
			signature_column 	:	'employee_signature',
			signature_record_id :	signature_record_id
				};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);		
				if(arr[0] == 200){
					setPopup(signature_record_id, "Copy signature link");
	var bodyHtml = '<div class="col-md-12">';
	bodyHtml +='<div class="row">';			
		
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><p id="signaturelinktext">'+arr[2]+'</p></div>';
	bodyHtml +='</div>';	
	
	bodyHtml +='</div>';
	
	bodyHtml +='</div>';
	modal.Body(bodyHtml);
	modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
				}
				message(arr[1]);
			}
		})	
	}
}
</script> 
