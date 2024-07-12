<div class="row" id="collection_form_container">
  <div class="col-sm-12">
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
        	<table id="datatable" class="table-bordered table-striped">
            <thead>
            	<tr>
                	<th>Day</th>
                    <th>Start</th>
                    <th>Total</th>                    
                    <th>Log</th>
                </tr>
             </thead>
             <tbody id="table_body">
             </tbody>   
            </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
	$(document).ready(function() { //alert(moment().subtract('days',14));
		$("#btnuserwork").on("click", function(){
			getUserWork();
		});
	});

	function getUserWork()
	{
		var formFields	=	"user_id, user_month";
	
	var table = $('#datatable').DataTable();
 

		if(validateFields(formFields))
		{
			var user_id = $("#user_id").val();
			table.clear().draw();
			var data={
			action		: 'employee/getuserworkrecord',
			user_id 	: user_id,
			user_month 	: $("#user_month").val()	
			};	
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
						console.log(record.length);
						var total_time = 0;
						var working_day = 0;
						for(var i=1; i <= parseInt(arr[3]); i++)
						{
							var itm = record[padDigits(i,2)];
							var d = new Date(arr[6] + "-" +i);
							
							if(typeof(itm) == "undefined")
							{
								console.log(d.getDay());
							var rowNode = table.row.add( [ arr[4]+'-'+padDigits(i,2)+' '+getDayName(d.getDay()), '-',  '-', '-' ] )
							.draw()
							.node();
							 
							$( rowNode )
								.css( 'background-color', ' #fdedec ' )
								.animate( { color: 'black' } );									
							}
							else
							{
								var date = arr[5] + '-' + itm.day_value;
								working_day++;
								total_time +=  parseInt(itm.work_time);
							var rowNode = table.row.add( [ itm.login_day+' '+getDayName(d.getDay()),  itm.start_time, itm.work_time_duration, '<a class="btndaylog btn btn-default btn-xs" data-date="'+date+'" data-user-id="'+user_id+'">Log</a>'] )
							.draw()
							.node();
							 
							$( rowNode )
								.css( 'background-color', ' #d4efdf ' )
								.animate( { color: 'black' } );	
							}
							
						}
						var rowNode = table.row.add( [ 'Total time', working_day + " days" , seconds2time(total_time), ''] )
						.draw()
						.node();
						 
						$( rowNode )
							.css( 'background-color', ' #444 ' )
							.animate( { color: '#fff' } );	
						
					}
					
					message(arr[1],1000);
				}
			});
		}
	}
	
	$(document).ready(function(e) {
        $("#datatable").DataTable({
			"ordering": false,  
			"paging": false, 
			"bSort" :false, 
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false,
			"bAutoWidth": false,
			"searching": false
			});
			
			
		$(document).on("click", ".btndaylog", function(){
			var date = $(this).attr("data-date");
			var user_id = $(this).attr("data-user-id");
			var data={
			action		: 'employee/getdaylogrecord',
			user_id 	: user_id,
			date	 	: date	
			};
			setPopup(0, "Loading... Login history");		
			modal.Show();
			modal.Body(LOADING_HTML);
			$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
					beforeSend: function(){
					message("process|Loading employe worksheet...",0);
					dissableSubmission();
				},		
				success:function(output){
					enableSubmission(output);
					var arr	=	JSON.parse(output);
					message(arr[1],1000);	
					if(arr[0]==200)
					{
						setPopup(1, arr[3] + " Login history");						
						bodyHtml = '<table  id="datatableday" class="table-bordered table-striped">';
						bodyHtml += '<thead><tr><th>Login</th><th>Logout</th><th>Work</th><th>Device</th><th>IP</th></tr></thead>';
						if(arr[2].length > 0)
						{
							bodyHtml += '<tbody>';
							for(var k=0; k<arr[2].length; k++)
							{
								var rc= arr[2][k];
								bodyHtml += '<tr><td>'+rc.start_time+'</td><td>'+rc.stop_time+'</td><td>'+rc.work_time_duration+'</td><td>'+rc.log_device+'</td><td>'+rc.log_ip_address+'</td></tr>';
							}
							bodyHtml += '</tbody>';
						}
						bodyHtml += '</table>';
						modal.Body(bodyHtml);
						modal.Footer('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');						
						//$("#datatableday").DataTable();
						
						$("#datatableday").DataTable({ 
							  destroy: true,
							  searching: false, 
							  paging: false, 
							  ordering: false,
							  info: false //use for reinitialize datatable
						});
					}
					
				}
			});
		});
    });
</script>