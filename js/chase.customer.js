$(document).ready(function(){
dragElement(document.getElementById(("chase_customer_notification")));
	$(".ccr_controll").on("click", function(){
		if($(this).find("i").hasClass('fa-angle-up')){
			$(".ccn_body").slideUp(500);
			$(".chase_customer_notification").addClass('chase_customer_minimise');
			window.localStorage.setItem("ccn_status", 0);
		}
		else{
			$(".ccn_body").slideDown(500);
			$(".chase_customer_notification").removeClass('chase_customer_minimise');
			window.localStorage.setItem("ccn_status", 1);
		}
		$(this).find("i").toggleClass('fa-angle-up fa-angle-down');
	});
	if(CHASE_CUTOMER_NOTIFICATION == true){
		fetchScheduledChaseCustomer();
		setInterval(fetchScheduledChaseCustomer, 1000*60*3);	
	}
});

function fetchScheduledChaseCustomer(){
	var data={
			action	:	'customer/getchasecustomerscheduled'
		};		
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)
			{
				appendScheduledCustomer(arr[2]);
				updateChaseCustomerContainer();
			}
		}
	});	
}

function appendScheduledCustomer(data){
	if(data.length > 0){
		$(".chase_customer_notification").removeClass('d-none');
		for(var i=0; i< data.length; i++){
			var item = data[i];
			if($(".ccn_body .chase_customer_"+item.chase_customer_id).length  == 0){
				$(".ccn_body").append(getChaseCustomerNotifHtml(item));
				$(".ccn_header").addClass('gradient_bg');
			}
		}
	}	
}

function getChaseCustomerNotifHtml(item){
    var chase_customer_class = "";
    if(item['chase_customer_status'] == "Active")
        chase_customer_class = "text-success";
    else if(item['chase_customer_status'] == "In active")
        chase_customer_class = "text-warning";
    else if(item['chase_customer_status'] == "On hold")
        chase_customer_class = "text-info";
    else if(item['chase_customer_status'] == "Booked Clients")
        chase_customer_class = "text-violet";
    
    
	return '<div class="customer-list-item chase_customer_'+item['chase_customer_id']+'" data-customer-id="'+item['chase_customer_id']+'">\
				<div class="avatar pull-left">\
					<img src="'+item['chase_customer_image']+'" class="img-avatar" alt="'+item['chase_customer_name']+'">\
				</div>\
				<div class="div-overflow-hidden py-0 pl-1">\
					<span id="customer_chat_box_id_91">\
						<span class="cname">'+item['chase_customer_name']+'</span><br/>\
						<span class="text-muted text-xs">'+item['chase_customer_company']+'</span>\
					</span>\
				</div>\
				<div class="w-100 px-1">\
					<span class="text-muted text-xs"><i class="fa fa-clock"></i> '+item['chase_customer_schedule_time']+'</span>\
						&nbsp; <span class="text-xs '+chase_customer_class+'"><i class="fa fa-user"></i> '+item['chase_customer_status']+'</span>\
						&nbsp; <span class="text-muted text-xs"><i class="fa fa-check"></i> '+item['chase_customer_type']+'</span>\
				</div>\
				<div class="clear clearer">\
					<div class="action-block">\
						<a href="#" class="chase_customer_view" data-id="'+item['chase_customer_id']+'"><i class="fa fa-eye fa-fw"></i></a>\
						<br/><a href="#" class="chase_customer_hide text-danger" data-id="'+item['chase_customer_id']+'"><i class="fa fa-trash fa-fw"></i></a>\
					</div>\
				</div>\
			</div>';
}

$(document).on("click", ".customer-list-item", function(){
	var targetEle = $(this);
	$(".customer-list-item").each(function(){
		$(this).removeClass('selected-customer');
	});
	targetEle.addClass('selected-customer');
	$(".selected_customer_name").text('- ' + targetEle.find('.cname').text());
	var chase_customer_id = targetEle.attr('data-customer-id');
	$("#chase_customer_id").val(chase_customer_id);
	getCustomerChaseHistory(chase_customer_id);
});

$(document).on("click", ".chase_customer_notification", function(){
	$(this).find('.ccn_header').removeClass('gradient_bg');
});

$(document).on("click", ".chase_customer_delete", function(e){
	e.preventDefault();
	confirmMessage.Set('Are you sure to delete customer record...?', 'deleteChaseCustomer', $(this).attr('data-id'));
});
$(document).on("click", ".copytochasecustomer", function(e){
	e.preventDefault();
	confirmMessage.Set('Are you sure to copy customer record in Chase Customer...?', 'copyToChaseCustomer', $(this).attr('data-id'));
});

$(document).on("click", ".input-group-addon-custom", function(){
	var data={
			action	:	'customer/getchasecustomerdetail',
			customer_id	:	0
		};		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				setPopup(0, "Loading customer details...");
				modal.Body(LOADING_HTML);
				modal.Footer('');
				modal.Show();
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					createChaseCustomerForm(arr[2]);
				}
			}
		});
});

$(document).on("click", ".chase_customer_view", function(e){
	e.stopPropagation();
	getChaseCustomerDetail($(this).attr('data-id'));
});

$(document).on("click", ".chase_customer_hide", function(e){
	e.stopPropagation();
	hideChaseCustomerDetail($(this).attr('data-id'));
});



$(document).on("click", ".btn-chase-comment", function(){
	saveChaseCustomerLog();
});

function deleteChaseCustomer(customer_id){
	var data={
			action	:	'customer/deletechasecustomer',
			customer_id	:	customer_id
		};		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("progress|Deleting customer record...");
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$(document).find("[data-customer-id='" + customer_id + "']").remove();
				}
				message(arr[1]);
			}
		});
}

function copyToChaseCustomer(customer_id){
	var data={
			action	:	'customer/copycustomertochasecustomer',
			customer_id	:	customer_id
		};		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("progress|Coppying customer record...");
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					
				}
				message(arr[1]);
			}
		});
}

function getChaseCustomerDetail(customer_id)
{
	
	var data={
			action	:	'customer/getchasecustomerdetail',
			customer_id	:	customer_id
		};		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				setPopup(0, "Loading customer details...");
				modal.Body(LOADING_HTML);
				modal.Footer('');
				modal.Show();
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					createChaseCustomerForm(arr[2]);
				}
			}
		});	
	
}
function hideChaseCustomerDetail(customer_id)
{
	
	var data={
			action	:	'customer/hidechasecustomerdetail',
			customer_id	:	customer_id
		};		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$(".ccn_body .chase_customer_"+customer_id).remove();
					updateChaseCustomerContainer();
				}
			}
		})	
	
}

function updateChaseCustomerContainer(){
	var items = $(".ccn_body .customer-list-item").length;
	if(items == 0)
	{
		$("#chase_customer_notification").hide();
		$(".schedule_chase_customer_count").text('');
	}
	else
	{
		var ccn_status = '1';
		if (typeof(window.localStorage.getItem("ccn_status")) != 'undefined') {
			ccn_status = window.localStorage.getItem("ccn_status");
		}
		if(ccn_status != '0'){
			$("#chase_customer_notification").show();
		}
		$(".schedule_chase_customer_count").text(items);
	}
}

function createChaseCustomerForm(data){
	var type_options = data.type_options;
	var status_options = data.status_options;
	if(typeof(data.chase_customer_id) == 'undefined'){
		data = {
			'chase_customer_id' : 0, 'chase_customer_name' : '', 'chase_customer_email' : '', 'chase_customer_type' : '', 'chase_customer_contact' : '', 'chase_customer_company' : '', 'chase_customer_job_level' : '', 'chase_customer_job_title' : '', 'chase_customer_job_function' : '', 'chase_customer_job_department' : '', 'chase_customer_job_division' : '', 'chase_customer_job_sic2_code' : '', 'chase_customer_job_sic2_details' : '', 'chase_customer_sic4_code' : '', 'chase_customer_job_sic4_details' : '', 'chase_customer_byd_industries' : '', 'chase_customer_address' : '', 'chase_customer_city' : '', 'chase_customer_state' : '', 'chase_customer_zipcode' : '', 'chase_customer_employee' : '', 'chase_customer_revenue' : '', 'chase_customer_founded' : '', 'chase_customer_company_type' : '', 'chase_customer_fax' : '', 'chase_customer_website' : '', 'chase_customer_country' : '', 'chase_customer_about' : '', 'chase_customer_joining_date' : '', 'chase_customer_updated_date' : '', 'chase_customer_updated_by' : '', 'chase_customer_last_email_on' : '', 'chase_customer_last_call_on' : '', 'chase_customer_last_time' : '', 'chase_customer_schedule_date' : '', 'chase_customer_is_prime' : '', 'chase_customer_status': ''
		}
		data.type_options = type_options;
		data.status_options = status_options;
	}
	setPopup(data.chase_customer_id, data != null ? "Customer #"+data.chase_customer_name : "Add new Customer");
	var bodyHtml = '<div class="col-md-12">';
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-12">';
	bodyHtml +='<div class="form-group"><label for="chase_customer_about">About '+data.chase_customer_name+'</label><textarea class="form-control" id="chase_customer_about" name="chase_customer_about" maxlength="5000" rows="3" data-label="Details about this customer" placeholder="Write details about customer '+data.chase_customer_about+'">'+(data.chase_customer_about != null ? data.chase_customer_about : '')+'</textarea></div></div>';
	bodyHtml +='</div>';
	
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_name">Name<sup>*</sup></label><input class="form-control" id="chase_customer_name" name="chase_customer_name" maxlength="50" placeholder="Enter customer name"   type="text" value="'+(data.chase_customer_name != null ? data.chase_customer_name : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_email">Email<sup>*</sup></label><input class="form-control" id="chase_customer_email" name="chase_customer_email" maxlength="50" placeholder="Enter customer email" type="text" value="'+(data.chase_customer_email != null ? data.chase_customer_email : '')+'"></div></div>';
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_type">Type<sup>*</sup></label><select id="chase_customer_type" name="chase_customer_type" class="form-control" size="1">'+data.type_options+'</select></div></div>';
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_contact">Contact</label><input class="form-control" id="chase_customer_contact" name="chase_customer_contact" maxlength="50" placeholder="Enter customer contact"   type="text" value="'+(data.chase_customer_contact != null ? data.chase_customer_contact : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_company">Company</label><input class="form-control" id="chase_customer_company" name="chase_customer_company" maxlength="100" placeholder="Enter customer company name"   type="text" value="'+(data.chase_customer_company != null ? data.chase_customer_company : '')+'"></div></div>';
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_job_title">Job title</label><input class="form-control" id="chase_customer_job_title" name="chase_customer_job_title" maxlength="250" placeholder="Enter customer job title"   type="text" value="'+(data.chase_customer_job_title != null ? data.chase_customer_job_title : '')+'"></div></div>';
	bodyHtml +='</div>';
	
	bodyHtml +='<div class="row extra_details_row">';
	
	bodyHtml +='<div class="accordion my-accordion" id="accordion" role="tablist"><div class="mb-0"><div class="card-header my-card-header" id="headingOne" role="tab"><h5 class="my-0"><a data-toggle="collapse" href="#collapseOneDetails" aria-expanded="false" aria-controls="collapseOneDetails" class="">Expand more details + </a></h5></div><div class="collapse" id="collapseOneDetails" role="tabpanel" aria-labelledby="headingOne" style="height:0px;" data-parent="#accordion"><div class="extra_detail_container">';
	
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_job_function">Job function</label><input class="form-control" id="chase_customer_job_function" name="chase_customer_job_function" maxlength="250" placeholder="Enter customer job function"   type="text" value="'+(data.chase_customer_job_function != null ? data.chase_customer_job_function : '')+'"></div></div>';
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_job_department">Department</label><input class="form-control" id="chase_customer_job_department" name="chase_customer_job_department" maxlength="250" placeholder="Enter job department" type="text" value="'+(data.chase_customer_job_department != null ? data.chase_customer_job_department : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_job_division">Job division</label><input class="form-control" id="chase_customer_job_division" name="chase_customer_job_division" maxlength="250" placeholder="Enter job division"   type="text" value="'+(data.chase_customer_job_division != null ? data.chase_customer_job_division : '')+'"></div></div>';

	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_job_sic2_code">SIC2 Code</label><input class="form-control" id="chase_customer_job_sic2_code" name="chase_customer_job_sic2_code" maxlength="250" placeholder="Enter SIC2 code"   type="text" value="'+(data.chase_customer_job_sic2_code != null ? data.chase_customer_job_sic2_code : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_job_sic2_details">SIC2 Details</label><input class="form-control" id="chase_customer_job_sic2_details" name="chase_customer_job_sic2_details" maxlength="250" placeholder="Enter SIC2 details"   type="text" value="'+(data.chase_customer_job_sic2_details != null ? data.chase_customer_job_sic2_details : '')+'"></div></div>';

	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_sic4_code">SIC4 Code</label><input class="form-control" id="chase_customer_sic4_code" name="chase_customer_sic4_code" maxlength="250" placeholder="Enter SIC4 code"   type="text" value="'+(data.chase_customer_sic4_code != null ? data.chase_customer_sic4_code : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_job_sic4_details">SIC4 Details</label><input class="form-control" id="chase_customer_job_sic4_details" name="chase_customer_job_sic4_details" maxlength="250" placeholder="Enter SIC4 details" type="text" value="'+(data.chase_customer_job_sic4_details != null ? data.chase_customer_job_sic4_details : '')+'"></div></div>';
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_byd_industries">BYD Industries</label><input class="form-control" id="chase_customer_byd_industries" name="chase_customer_byd_industries" maxlength="250" placeholder="Enter BYD Industries details"   type="text" value="'+(data.chase_customer_byd_industries != null ? data.chase_customer_byd_industries : '')+'"></div></div>';	
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_address">Address</label><input class="form-control" id="chase_customer_address" name="chase_customer_address" maxlength="250" placeholder="Enter customer address"   type="text" value="'+(data.chase_customer_address != null ? data.chase_customer_address : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_city">City</label><input class="form-control" id="chase_customer_city" name="chase_customer_city" maxlength="250" placeholder="Enter city name"   type="text" value="'+(data.chase_customer_city != null ? data.chase_customer_city : '')+'"></div></div>';
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_state">State</label><input class="form-control" id="chase_customer_state" name="chase_customer_state" maxlength="250" placeholder="Enter state name"   type="text" value="'+(data.chase_customer_state != null ? data.chase_customer_state : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_country">Country</label><input class="form-control" id="chase_customer_country" name="chase_customer_country" maxlength="250" placeholder="Enter country name"   type="text" value="'+(data.chase_customer_country != null ? data.chase_customer_country : '')+'"></div></div>';	
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_zipcode">Zipcode</label><input class="form-control" id="chase_customer_zipcode" name="chase_customer_zipcode" maxlength="100" placeholder="Enter zip code"   type="text" value="'+(data.chase_customer_zipcode != null ? data.chase_customer_zipcode : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_employee">Emplyee</label><input class="form-control" id="chase_customer_employee" name="chase_customer_employee" maxlength="10" placeholder="Enter no of employee"  type="text" value="'+(data.chase_customer_employee != null ? data.chase_customer_employee : '')+'"></div></div>';
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_revenue">Revenue</label><input class="form-control" id="chase_customer_revenue" name="chase_customer_revenue" maxlength="10" placeholder="Enter revenue"   type="text" value="'+(data.chase_customer_revenue != null ? data.chase_customer_revenue : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_founded">Founded</label><input class="form-control" id="chase_customer_founded" name="chase_customer_founded" maxlength="4" placeholder="Enter founded year"   type="text" value="'+(data.chase_customer_founded != null ? data.chase_customer_founded : '')+'"></div></div>';
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_company_type">Type</label><input class="form-control" id="chase_customer_company_type" name="chase_customer_company_type" maxlength="50" placeholder="Enter customer type"   type="text" value="'+(data.chase_customer_company_type != null ? data.chase_customer_company_type : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_fax">FAX NO.</label><input class="form-control" id="chase_customer_fax" name="chase_customer_fax" maxlength="100" placeholder="Enter fax number"   type="text" value="'+(data.chase_customer_fax != null ? data.chase_customer_fax : '')+'"></div></div>';
	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_website">Website</label><input class="form-control" id="chase_customer_website" name="chase_customer_website" maxlength="100" placeholder="Enter website url"   type="text" value="'+(data.chase_customer_website != null ? data.chase_customer_website : '')+'"></div></div>';	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_joining_date">Joining date</label><input class="form-control" id="chase_customer_joining_date" name="chase_customer_joining_date" maxlength="50" placeholder="Enter customer joining date"   type="text" value="'+(data.chase_customer_joining_date != null ? data.chase_customer_joining_date : '')+'"></div></div>';
	bodyHtml +='</div>';
	bodyHtml +='</div></div></div></div>';
	

	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_last_email_on">Last mail on</label><input class="form-control" id="chase_customer_last_email_on" name="chase_customer_last_email_on" maxlength="50" placeholder="Enter customer Last mailed On"   type="text" value="'+(data.chase_customer_last_email_on != null && data.chase_customer_last_email_on !='0000-00-00 00:00:00' ? data.chase_customer_last_email_on : '')+'"></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_last_call_on">Lat call on</label><input class="form-control" id="chase_customer_last_call_on" name="chase_customer_last_call_on" maxlength="50" placeholder="Enter customer last call on"   type="text" value="'+(data.chase_customer_last_call_on != null && data.chase_customer_last_call_on !='0000-00-00 00:00:00' ? data.chase_customer_last_call_on : '')+'"></div></div>';
	bodyHtml +='</div>';

	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_schedule_date">Next meeting schedule on</label><input class="form-control" id="chase_customer_schedule_date" name="chase_customer_schedule_date" maxlength="50" placeholder="Enter customer next schedule date"   type="text" value="'+(data.chase_customer_schedule_date != null && data.chase_customer_schedule_date !='0000-00-00 00:00:00' ? data.chase_customer_schedule_date : '')+'"></div></div>';	
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_last_call_on">Status</label><select id="chase_customer_status" name="chase_customer_status" class="form-control" size="1">'+data.status_options+'</select></div></div>';
	bodyHtml +='</div>';	
	
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="chase_customer_is_prime"><i class="fa fa-star text-warning fa-fw" aria-hidden="true"></i> &nbsp;This is a prime customer ?</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="chase_customer_is_prime" value="1" name="chase_customer_is_prime" type="checkbox" '+(data.chase_customer_is_prime == 1 ? 'checked':'')+'><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div>';	
	bodyHtml +='</div>';
	
	modal.Body(bodyHtml);
	modal.Footer('<button type="reset" class="btn btn-default" >Reset</button><button type="button" onclick="updateChaseCustomerDetail();" class="btn btn-success" >Save</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
	$("#chase_customer_joining_date, #chase_customer_last_email_on, #chase_customer_last_call_on, #chase_customer_schedule_date").datetimepicker({ format: "yyyy-mm-dd hh:ii", pickerPosition: "top-left", autoclose: true, todayBtn: true, fontAwesome : true  });
	
	$("#chase_customer_type").select2({
	  tags: true,
	  createTag: function (params) {
		var term = $.trim(params.term);

		if (term === '') {
		  return null;
		}

		return {
		  id: term,
		  text: term,
		  newTag: true // add additional parameters
		}
	  }
	});
}

function filterCustomerList()
{
	var keyword = $("#filter_customer_list").val().toLowerCase();
	var data={
			action	:	'customer/getchasecustomer',
			keyword	:	keyword,
			type	:	$(".filter_chase_customer_type").val()
		};
		$(".selected_customer_name").text('');	
		$("#chase_customer_id").val(0);
		$("#customer_chase_comment_box").addClass('block_disabled');
		$(".mark_mailed").attr('checked', false);
		$(".mark_called").attr('checked', false);
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				//message("process|Searching customer...");
				$("#chase_customer_list_block").html(LOADING_HTML);
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					var html='';
					if(arr[2].length != 0)
					{					
						for(var i=0; i<arr[2].length; i++)
						{
							var rowData = arr[2][i];
							html+='<div class="customer-list-item" data-customer-id="'+rowData.chase_customer_id+'"><div class="avatar pull-left"><img src="'+rowData.chase_customer_image+'" class="img-avatar" alt="'+rowData.chase_customer_name+'"></div><div class="div-overflow-hidden py-0 pl-1"><span id="customer_chat_box_id_'+rowData.chase_customer_id+'"><span class="cname">'+rowData.chase_customer_name+'</span><br/><span class="text-muted text-xs">'+rowData.chase_customer_company+'</span></span></div><div class="clear clearer"><div class="action-block text-right"><a href="#" class="chase_customer_view" data-id="'+rowData.chase_customer_id+'">view</a><br/><a href="#" class="chase_customer_delete text-danger" data-id="'+rowData.chase_customer_id+'">Delete</a>\</div></div></div>';
						}
					}
					else
						html = EMPTY_IMAGE_BOX ;
					
					$("#chase_customer_list_block").html(html); 
					
					if(arr[3] != 0){
						$('.customer-list-item[data-customer-id="'+arr[3]+'"]').trigger('click');
					}
				}
			}
		})	
	
}
function getCustomerChaseHistory(customer_id)
{	
	$("#customer_chase_record_box").html(LOADING_HTML);	
	var data={
			action			:	"customer/getcustomerchasehistory",
			customer_id		:	customer_id					
		};
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
		},		
		success:function(output){ 
			var arr	=	JSON.parse(output);			
			if(arr[0]==200)	
			{
				$("#customer_chase_record_box").html('');
				$("#customer_chase_comment_box").removeClass('block_disabled');
				if(arr[2] != ""){
					for(var i=0; i<arr[2].length; i++){			
						$("#customer_chase_record_box").append(getChaseLogHtml(arr[2][i]));
					} 
				}				
				else
				{
					$("#customer_chase_record_box").html(EMPTY_TEXT_BOX); 
				}
			}	
		}
	});
}

function getChaseCustomerMentionyItems(){
	return $("div.chasecustomermentionbox").find('a.mentiony-link').map(function () { return { id : $(this).attr('data-item-id'),
	name : $(this).text()}; }).get();
}
function updateChaseCustomerDetail(){
	var customer_id = $("#keyid").val();
	var formData = $("#modalform").serializeFormJSON();
	if(!isEmail($("#chase_customer_email").val())){
		toastMessage('warning|Please enter valid email');
		return false;
	}
	if($("#chase_customer_name").val() == ""){
		toastMessage('warning|Customer name is required');
		return false;
	}
	var data={
				action		:	"customer/updatechasecustomerdetail",
				customer_id	:	customer_id,
				chase_customer_type : $("#chase_customer_type").val()
		};
	data = $.extend(data, formData);
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
		},		
		success:function(output){ 		
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)	
			{
				$("#keyid").val(arr[2]);
			}
			toastMessage(arr[1]);
		}
	});	
}

function saveChaseCustomerLog(){
	var customer_id = $("#chase_customer_id").val();
	var comment_text = $(".comment-textarea").val();
	if(comment_text != '')
	{
		var data={
				action			:	"customer/savechasecustomerlog",
				customer_id		:	customer_id,
				comment_text	: 	comment_text,
				mark_mailed		: 	$(".mark_mailed").is(":checked") ? 1: 0,
				mark_called		: 	$(".mark_called").is(":checked") ? 1: 0,
				mentiony : getChaseCustomerMentionyItems()
			};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				$(".btn-chase-comment").text('Saving...');
				$(".btn-chase-comment").attr('disabled', true);
			},		
			success:function(output){ 
				$(".btn-chase-comment").text('Save');	
				$(".btn-chase-comment").attr('disabled', false);		
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)	
				{				
					$(".comment-textarea").val('');
					$(".mark_mailed").prop('checked', false);
					$(".mark_called").prop('checked', false);
					getCustomerChaseHistory(customer_id);
				}
				toastMessage(arr[1]);
			}
		});
	}
	else{
		toastMessage('warning|Please enter log comments');
		$(".comment-textarea").focus();
	}
}

function getChaseLogHtml(item){
	return '<div id="ccr_id_'+item['ccr_id']+'" class="callout callout-info m-a-0 p-y-1">\
				<div>\
					<div class="pull-xs-left l_r_b_l">\
						<img src="'+item['user_image']+'" class="img-avatar img-avator-chase-customer" alt="'+item['user_fname']+'">\
					</div>\
					<div class="l_r_b_r logtextbox">'+item['ccr_comments']+'</div>'+ (item['complaint_log_tag'] != null ? ('<div class="log_tag_user"><i class="icon icon-tag"></i> ' + item['complaint_log_tag']+'</div>') : '') +'</div>\
				<div class="log_tag_user"></div><div class="l_r_b_c pr-1 pt-1">\
					<small class="text-muted m-r-1"><i class="icon-clock"></i>&nbsp;'+item['ccr_date']+'\</small> &nbsp; \
					'+(item['ccr_is_called'] == 1 ? '<small class="text-muted"><i class="icon-phone"></i>&nbsp;Phone Called\</small>':'')+'\
					'+(item['ccr_is_mailed'] == 1 ? '<small class="text-muted"><i class="icon-envelope-open"></i>&nbsp; Mailed\</small>':'')+'\
						&nbsp; \
				</div>\
				<hr class="m-x-1 my-0">\
			</div>';
}