<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> 
      	<i class="fa fa-align-justify"></i> Feedback List 
        <div class="card-actions">
            <a data-title="Feedback PDF Report" title="Generate PDF Report" href="#" onclick="newWindow('<?=DOC::CUSTLIST()?>')"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
        </div>        
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Pic</th>
              <th>Name</th>
              <th>Title</th>
              <th>Rating</th>
              <th>Date</th>
              <th>Status</th>
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

function statusAction(field)
{
	var data={
			action	:	"updatefeedbackstatus",
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
				"action"	:	"viewfeedbacklist",				
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
		columnDefs: [{ targets: [], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,5 ] }]
    } );
} );


</script> 
