<style type="text/css">
.span-bold{ font-weight:600;}
</style>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"><i class="fa fa-align-justify"></i> <strong><?=$formHeading?></strong> <small>Form</small> 
        <div class="card-actions">
            <a data-title="Refresh Complaint" class="redirect" title="Refresh Complaint" data-toggle="modal" data-target="#appModal" onclick="openLogForm('<?=$complaint_id?>|C', '<?=$complaint_ticket_number?> Log Report')" href="#"><i class="fa  fa-wechat fa-lg m-t-2"></i></a>
            <a data-title="Refresh Complaint" title="Refresh Complaint" href="<?php echo $app->siteUrl("viewcomplaint/$complaint_id");?>"><i class="fa fa-refresh fa-lg m-t-2"></i></a>            
        </div>
      </div>
      
      <div class="card-block">
        <div class="row">          
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_email">Customer Email: </label>
              <?=isset($customer_email)?$customer_email:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_phone">Customer Phone Number: </label>
              <?=isset($customer_phone)?$customer_phone:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_type_id">Customer Type: </label>
                <?=isset($customer_type_name)?$customer_type_name:""; ?>
            </div>
          </div>
        </div>
        <!--/row-->
        
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_fname">Customer First Name: </label>
              <?=isset($customer_fname)?$customer_fname:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_lname">Customer Last Name: </label>
              <?=isset($customer_lname)?$customer_lname:"";?>
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_lname">Complaint Log Date: </label>
              <?=isset($customer_lname)?date("d M-Y h:i A", strtotime($complaint_created_date)):"";?>
            </div>
          </div>
          
        </div>
        <!--/row-->
        
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_address_street_number">Street Number: </label>
              <?=isset($customer_address_street_number)?$customer_address_street_number:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_address_route">Address Route: </label>
              <?=isset($customer_address_route)?$customer_address_route:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_address_locality">Address Locality: </label>
              <?=isset($customer_address_locality)?$customer_address_locality:"";?>
            </div>
          </div>
        </div>
        <!--/row-->
        
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_address_administrative_area">State (Administrative Area): </label>
              <?=isset($customer_address_administrative_area)?$customer_address_administrative_area:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_address_country">Country Name: </label>
              <?=isset($customer_address_country)?$customer_address_country:"";?>
            </div>
          </div>
         <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="customer_address_postcode">PostCode : </label>
              <?=isset($customer_address_postcode)?$customer_address_postcode:"";?>
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="span-bold" for="complaint_description">Complaint Description: </label>
              <?=isset($complaint_description)?$complaint_description:"";?>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label class="span-bold" for="complaint_problem_details">Complaint Problem Details: </label>
              <?=isset($complaint_problem_details)?$complaint_problem_details:"";?>
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="span-bold" for="">Product Problem(s): </label>
              <div class="col-sm-12">
                <div class="row" id="app_not_working_problem_mark_box">
                  <?php
                $HardwareProblem = new HardwareProblem(0);
				$app_not_working_problem_mark_array = array();
				if(isset($app_not_working_problem_mark))
				$app_not_working_problem_mark_array = explode(",",$app_not_working_problem_mark);
				echo $HardwareProblem->listName($app_not_working_problem_mark_array);
				?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold">Product Serial Number: </label>
              <?=isset($complaint_product_serial)?$complaint_product_serial:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_product_model">Product Model Number: </label>
              <?=isset($complaint_product_model)?$complaint_product_model:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_product_password">Product Password: </label>
              <?=isset($complaint_product_password)?$complaint_product_password:"";?>
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_product_operating_system">Operating System: </label>
              <?=isset($complaint_product_operating_system)?$complaint_product_operating_system:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_product_antivirus">Product Antivirus: </label>
              <?=isset($complaint_product_antivirus)?$complaint_product_antivirus:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" class="span-bold" for="complaint_due_date">Due Date: </label>
              <?=isset($complaint_due_date)?date("Y-m-d", strtotime($complaint_due_date)):"";?>
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="span-bold" for="complaint_description">Mark Hardware not working: </label>
              <div class="col-sm-12">
                <div class="row">
                <?php
                $HardwareType = new HardwareType(0);
				$complaint_product_hardware_not_working_array = array();
				if(isset($complaint_product_hardware_not_working))
				$complaint_product_hardware_not_working_array = explode(",",$complaint_product_hardware_not_working);
				echo $HardwareType->listName($complaint_product_hardware_not_working_array);
				?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_sales_record_number">Ebay Sales Record Number: </label>
              <?=isset($complaint_sales_record_number)?$complaint_sales_record_number:"";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_priority">Priority Value From 1 to 10: </label>
              <?=isset($complaint_priority)?$complaint_priority:"0";?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_estimated_cost">Estimated Cost: </label>
              <?=isset($complaint_estimated_cost)?$complaint_estimated_cost:"";?>
            </div>
          </div>
        </div>
        <!--/row-->
        
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_is_backup">Product Backuped &nbsp; <i class="fa fa-history fa-lg m-t-2"></i>: </label>
              <?=(isset($complaint_is_backup) && $complaint_is_backup)?icon(1,"Yes"):icon(0,"No");?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_is_disk_provided">Disk Provided &nbsp; <i class="fa fa-hdd-o fa-lg m-t-2"></i>: </label>
              <?=(isset($complaint_is_disk_provided) && $complaint_is_disk_provided)?icon(1,"Yes"):icon(0,"No");?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_product_is_under_waranty">Under Waranty &nbsp; <i class="fa fa-umbrella fa-lg m-t-2"></i>: </label>
              <?=(isset($complaint_product_is_under_waranty) && $complaint_product_is_under_waranty)?icon(1,"Yes"):icon(0,"No");?>
            </div>
          </div>
        </div>
        <!--/row-->
        
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_technician_id">Select Technician: </label>
             	<?=isset($technician_name)?$technician_name:"";?>                
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_status">Complain Status: </label>
              <?php
                $ComplaintStatus = new ComplaintStatus($complaint_status);
				echo $ComplaintStatus->get("complaint_status_name");
			?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="span-bold" for="complaint_product_condition_at_receiving">Product Condition: </label>
              <?=isset($complaint_product_condition_at_receiving)?$complaint_product_condition_at_receiving:"";?>
            </div>
          </div>
        </div>
        <!--/row-->         
      </div>
          	
    </div>
    
  </div>
</div>
<!--/col--> 

<!--/col-->
</div>
<script type="text/javascript">
/*function openLogForm(id, title)
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
					action	:	'getloghistory',
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
					action	:	'insertcomplaintlog',
					logtext :	$("#log_comment_text").val(),
					id		:	idData[0],
					complaint_format:idData[1]					
				};
		$.ajax({type:'POST', data:dataAjax, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				popmessage("connecting|Connecting...",0);
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)	
				{
					$("#log_comment_text").val('');
					var logText = arr[2][0];
					modal.PrependHistory('<div class="callout callout-'+(logText.logger_type=='E'?"info":"warning")+' m-a-0 p-y-1"><div class="avatar pull-xs-left l_r_b_l"><img src="'+logText.logger_image+'" class="img-avatar" alt="'+logText.logger_name+'"></div><div class="l_r_b_r">'+logText.complaint_log_text+'</div><div class="l_r_b_c"><small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; '+logText.log_time+'</small>&nbsp; &nbsp;<small class="text-muted"><i class="icon-location-pin"></i>&nbsp; '+logText.logger_name+'</small></div></div><hr class="m-x-1 m-y-0">');
				}	
				popmessage(arr[1]);
			}
		})	
	}
}*/

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
		$.ajax({type:'POST', data:dataAjax, url:sitePath +'fcm.php', 		
			beforeSend: function(){
				popmessage("connecting|Connecting...",0);
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)	
				{
					$("#log_comment_text").val('');					
				}
				if(arr[2]==false)
				arr[1]+=" <span class='card-warning'>Notification Could not Send.</span>";
				popmessage(arr[1]);
			}
		})	
	}

}

</script> 
