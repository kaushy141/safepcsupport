<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Complaint Request List 
      
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Date</th>
              <th>Code</th>
              <th>Customer</th>
              <th>Contact</th>
              <th>Technician</th>
              <th>Status</th>
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
				"action"	:	"viewcomplaintlist",				
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
		columnDefs: [{ targets: [6], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 3,4 ] }]
    } );
} );

function openLogForm(id, title)
{
	setPopup(id, title);
	var bodyHtml = '<div class="col-md-12"><div class="row">';
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><label for="poplogtext">Complaint Description<sup>*</sup></label><textarea id="log_comment_text" name="log_comment_text" rows="3" class="form-control" placeholder="Enter log text here..."></textarea></div>';
	bodyHtml +='</div>';
	bodyHtml +='</div></div>';
	modal.Body(bodyHtml);
	var idData = $("#keyid").val().split('|');
	var dataAjax={
					action	:	'employee/getloghistory',
					id		:	idData[0],
					complaint_format:idData[1]							
				};
		$.ajax({type:'POST', data:dataAjax, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				popmessage("connecting|Loading Log history...",0);
				modal.History("");
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)	
				{ 
					var logTextObj = arr[2];
					if(logTextObj.length>0)
					{ 
						for(var i=0; i<logTextObj.length;i++)
						{
							var logText = logTextObj[i];
							modal.PrependHistory('<div class="callout callout-'+(logText.logger_type=='E'?"info":"warning")+' m-a-0 p-y-1"><div class="avatar pull-xs-left l_r_b_l"><img src="'+logText.logger_image+'" class="img-avatar" alt="'+logText.logger_name+'"></div><div class="l_r_b_r">'+logText.complaint_log_text+'</div><div class="l_r_b_c"><small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; '+logText.log_time+'</small>&nbsp; &nbsp;<small class="text-muted"><i class="icon-location-pin"></i>&nbsp; '+logText.logger_name+'</small></div></div><hr class="m-x-1 m-y-0">');
						}
					}
				}	
				popmessage(arr[1]);
			}
		})	
}
function submitPopup()
{ 
	if(validateFields("log_comment_text", true))
	{
		var idData = $("#keyid").val().split('|');
		var dataAjax={
					action	:	'repair/insertcomplaintlog',
					logtext :	$("#log_comment_text").val(),
					id		:	idData[0],
					complaint_format:idData[1]						
				};
		$.ajax({type:'POST', data:dataAjax, url:'fcm.php', 		
			beforeSend: function(){
				popmessage("connecting|Connecting...",0);
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)	
				{
					$("#log_comment_text").val('');
					/*var logText = arr[2][0];
					modal.PrependHistory('<div class="callout callout-'+(logText.logger_type=='E'?"info":"warning")+' m-a-0 p-y-1"><div class="avatar pull-xs-left l_r_b_l"><img src="'+logText.logger_image+'" class="img-avatar" alt="'+logText.logger_name+'"></div><div class="l_r_b_r">'+logText.complaint_log_text+'</div><div class="l_r_b_c"><small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; '+logText.log_time+'</small>&nbsp; &nbsp;<small class="text-muted"><i class="icon-location-pin"></i>&nbsp; '+logText.logger_name+'</small></div></div><hr class="m-x-1 m-y-0">');*/
				}	
				if(arr[0]==500)	
				popmessage(arr[1], 0);
				else
				popmessage(arr[1]);
			}
		})	
	}
}
</script> 
