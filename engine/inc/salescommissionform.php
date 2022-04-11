<div class="row" id="collection_form_container">
  <div class="col-sm-12">
  <form id="salescommissionform" name="salescommissionform">
    <div class="card" id="card-detail-print">
      <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i><?php echo $formHeading;?></b>        
      </div>
      <div class="card-block">
        <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4">            
            <div class="form-group">
              <label for="user_id">Employee name<sup>*</sup></label>
              <select id="user_id" name="user_id" class="form-control" size="1">
              <?php
             $user = new Employee(0);
             echo $user->getUserOptionSelect(0);
             ?>
              </select>
            </div>
        </div>
        
        
        <div class="col-xs-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label for="user_month">Work Month<sup>*</sup></label>
            <div class="input-group date">
              <input class="form-control" id="user_month" name="user_month" placeholder="Mon-YEAR" value="" type="text">
              <span class="input-group-addon">
              <label style="margin-bottom:0px;" for="user_month"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
              </span> </div>
            <script type="text/javascript">
                    $('#user_month').datepicker({
                        format: "M-yyyy",
						viewMode: "months", 
						startView: "months", 
						minViewMode: "months",
						todayHighlight:true,
						autoclose:true
                    });
            </script> 
          </div>
        </div>
        
        <div class="col-xs-12 col-sm-6 col-md-4">         
            <div class="form-group">
              <label for="user_id">Get Result<sup></sup></label>
              <input type="button" id="btnuserwork" class="btn btn-block btn-success" value="Submit" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
        	<table class="table table-bordered table-striped">
            <thead>
            	<tr>
                	<th class="px-1"><input type="checkbox" class="chkinvoiceAll" name="chkinvoiceAll" value="all"></th>
					<th>Type</th>
					<th>Record</th>
                    <th class="hidden-xs hidden-sm hidden-md visible-lg">Date</th>
                    <th class="text-right hidden-xs hidden-sm hidden-md visible-lg">Amount</th>  
					<th class="text-center">Comm.%</th>  					
                    <th>Comm. &pound</th>
					<th>Action</th>
                </tr>
             </thead>
             <tbody id="table_body">
             </tbody>   
            </table>
        </div>
        </div>
      </div>
	  <div class="card-footer">
          <div class="row">
            <div class="col-sm-12">              
              <button type="button" disabled id="btn_save_commision" onClick="confirmMessage.Set('Are you sure to save commission  information...?', 'savesalescommission');" class="btn btn-success mt-0 submission_handler_btn"><i class="fa fa-check-circle fa-lg"></i> Save Commission</button>
			  <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"";?>"  />
			  <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
			  <input type="hidden" id="sales_commission_user_id" name="sales_commission_user_id" value="0"  />
			  <input type="hidden" id="sales_commission_month" name="sales_commission_month" value="0"  />
              </div>
          </div>
        </div>
    </div>
	</form>
  </div>
</div>

<script>
	$(document).ready(function() { //alert(moment().subtract('days',14));
		$("#btnuserwork").on("click", function(){
			getEmployeeSales();
		});
	});
	$(document).on("change", ".chkinvoiceAll", function(){
		var isChecked = $(this).is(":checked")
		$('#datatable *').filter('.chkinvoice').each(function(){		
			$(this).attr('checked', isChecked)
		});
	});
	$(".user_id, .user_month").on("change", function(){ alert(12);
		$('#datatable').DataTable().clear();
	});
	$(document).on("change", ".chkinvoice", function(){
		$("#commission_percent_"+$(this).val()).prop('disabled', !$(this).is(":checked")).val(0);
		$("#sales_commission_amount_"+$(this).val()).text('');
		calculateNetCommission();
	});
	function getEmployeeSales()
	{
		var formFields	=	"user_id, user_month";	
		var table = $('#datatable').DataTable();
		if(validateFields(formFields))
		{
			var user_id = $("#user_id").val();
			table.clear().draw();
			var data={
				action		: 'sales/getsalescommissionrecord',
				user_id 	: user_id,
				user_month 	: $("#user_month").val()	
			};	
			$("#btn_save_commision").prop('disabled', true);
			$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
					beforeSend: function(){
					message("process|Loading employe worksheet...",0);
					dissableSubmission();
				},		
				success:function(output){
					enableSubmission(output);
					var arr	=	JSON.parse(output);	
					if(arr[0]==200)
					{
						var record = arr[2];
						if(record.length > 0)
						{
							$("#sales_commission_user_id").val(arr[3]);
							$("#sales_commission_month").val(arr[4]);
							$("#btn_save_commision").prop('disabled', false);
							for(var i=0; i <= record.length; i++)
							{
								var itm = record[i];
								if(typeof(itm) !== "undefined")
								{
									var html = '<tr id="sales_commission_row_'+itm.id+'"><td><input class="chkinvoice" type="checkbox" name="chkinvoice['+itm.type+'][]" value="'+itm.id+'"></td><td>'+(itm.type == 'S' ? 'Sales Invoice' : (itm.type == 'O' ? 'Web Order' : 'Unknown'))+'</td><td>'+itm.code+'</td><td class="hidden-xs hidden-sm hidden-md visible-lg">'+itm.created_date+'</td><td class="hidden-xs hidden-sm hidden-md visible-lg">'+itm.amount+'</td><td><input style="width:80px" class="form-control commission_percent" id="commission_percent_'+itm.id+'" data-amount="'+itm.amount+'"  data-id="'+itm.id+'" name="commission_percent['+itm.type+'][]" min="0" max="100" step="0.01" placeholder="Commission %" type="number" value="0" disabled>'+'</td><td><span class="sales_commission_amount pull-right" id="sales_commission_amount_'+itm.id+'"></span></td><td><a data-id="'+itm.id+'" type="button" class="btn btn-outline-danger btnremovesalescomm"><i class="fa fa-trash"></i></a></td></tr>';
									$("#table_body").append(html);
								}							
							}
							$("#table_body").append('<tr><td colspan="8" class="text-right">Total Commission : <span class="pull-right" id="sales_total_commission_amount">0.00</span></td></tr>');
						}
						 
						
					}					
					message(arr[1],1000);
				}
			});
		}
	}
	
	function savesalescommission(){
		var data={
			action	:	$("#action").val()				
		};
		
		data = $.extend(data, $("#salescommissionform").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Saving details...",0);
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{ 
					$('#datatable').DataTable().clear().draw();
				}					
				message(arr[1],1000);
			}
		});
	}
	
	$(document).on("click", ".btnremovesalescomm", function(){		
		var id = $(this).attr('data-id');
		var data={
			action				: 'sales/removesalesinvoicefromcommission',
			id 	: id
		};	
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Removing Invoice from Commission",0);
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#sales_commission_row_"+id).remove();	
					calculateNetCommission();					
				}					
				message(arr[1],1000);
			}
		});
	});
	
	$(document).on('change', '.commission_percent', function(){
		var id = $(this).attr('data-id');
		var amount = $(this).attr('data-amount');
		var commission = Math.round(((amount * $(this).val()/100) + Number.EPSILON) * 100) / 100;		
		$("#sales_commission_amount_"+id).text(commission.toFixed(2));		
		calculateNetCommission();
	});
	
	function calculateNetCommission(){
		var net_commission = 0;
		$('#datatable *').filter('.sales_commission_amount').each(function(){		
			net_commission = net_commission + (($(this).text() == 'NaN' || $(this).text() == '') ? 0 : parseFloat($(this).text()));
		});	
			
		$("#sales_total_commission_amount").text(net_commission.toFixed(2));
	}	
	
</script>