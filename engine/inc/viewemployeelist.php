<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> 
      	<i class="fa fa-align-justify"></i> Employee List 
        <div class="card-actions">
            <a class="redirect" data-title="Add New Employee" title="Add New Employee" href="<?php echo $app->siteUrl("addemployee");?>"><i class="icon-user-follow icons font-2xl d-block m-t-2"></i></a>
            <a data-title="Employee PDF Report" title="Generate PDF Report" href="javascript:newWindow('<?=DOC::EMPLIST()?>');"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
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
				"action"	:	"viewemployeelist",				
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

$(document).on('click', '.rechargewallet', function(){
	var rec_user_id = $(this).attr('data-user-id');
	var rec_user_img = $(this).attr('data-user-image');
	var rec_user_name = $(this).attr('data-user-name');
	setPopup(rec_user_id, "<i class=\"icon-wallet\"></i> Recharge Wallet");
	var bodyHtml = '';
	bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-6"><label>Recharging for</label><br/><img class="img-circle" src="'+rec_user_img+'"> '+rec_user_name+'<br/><p><span><i class="fa fa-money text-warning"></i> Crrent Balance : <span class="user_current_balance">Loading...</span></span></p></div>';
	bodyHtml += '<div class="col-xs-12 col-sm-6 col-md-6"><label>&pound; Amount(GBP)</label><input type="number" class="form-control" name="recharge_trans_amount" value="0" min="0" step="0.5" id="recharge_trans_amount"></div>';
	modal.Body(bodyHtml);
	modal.Footer('<button type="reset" class="btn btn-default" >Reset</button> <button type="button" onclick="confirmMessage.Set(\'Are you sure to recharge...?\', \'proceedtoRecharge\');" class="btn btn-success" >Recharge</button> <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
	getWalletAmount(rec_user_id);
});

function getWalletAmount(user_id){
	var data={
			'action'	:	"recharge/getusercurrentbalance",
			'user_id'	:	user_id,
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				//message("process|Connecting...",0);
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);
				$(".user_current_balance").text(arr[2]);
			}
		})	
	
}

function proceedtoRecharge(){
	var user_id = $("#keyid").val();
	var amount	= $("#recharge_trans_amount").val();
	var data={
			action	:	"recharge/createrecharge",
			recharge_trans_user_id	:	user_id,
			recharge_trans_amount	:	amount			
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...",0);
			},		
			success:function(output){ 
				modal.Hide();
				var arr	=	JSON.parse(output);
				message(arr[1],2000);
			}
		})	
	
}
</script> 
