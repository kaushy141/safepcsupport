<style type="text/css">
*::-webkit-scrollbar {
 width: 6px !important;
 height: 6px !important;
}
*::-webkit-scrollbar-thumb {
 background-color: rgba(0,0,0,0.2);
}
*::-webkit-scrollbar-track {
 background: rgba(255,255,255,0.08);
}
.cst_user_box {
	background: #FFF;
	padding-top: 3px;
	padding-bottom: 3px;
	border-bottom: 1px solid #EBEBEB;
	cursor: pointer;
}
.cst_user_box:hover {
	box-shadow: 1px 0px 3px #3DCCF5;
}
.cst_user_chat_box {
	padding-top: 2px;
	padding-bottom: 2px;
	margin-bottom: 5px;
	background: #FFF;
	border-bottom: 1px solid #DBDBDB;
}
.callout-left-position {
	border-right-color: #FFF;
border-left-width:.25rem;
	border-left-color: #3F5FF3;
	background: #D5F4FF;
}
.callout-right-position {
	border-left-color: #FFF;
border-right-width:.25rem;
	border-right-color: #03BEB5;
	background: #FEF7CB;
}
.ctm-count-tag-pin {
	padding: 0px 6px;
	position: absolute;
	top: -10px;
	right: -1px;
	border-radius: 10rem;
	background-color: #f86c6b;
}

.ctm-count-tag-pin-ticket {
	padding: 0px 6px;
	position: absolute;	
	right: -1px;
	border-radius: 10rem;
}
.ctm-user-count-tag-pin {
	padding: 0px 6px;
	position: absolute;
	top: -10px;
	right: -1px;
	border-radius: 10rem;
	background-color: #37AB23;
}
.text-header-dark{ color: #444 !important; font-weight:600 !important;}
.complaint_status_label{ min-height:40px; font-size:14px;}
</style>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 cl-xs-12">
<div class="row">
  <div class="col-lg-3 col-md-4 col-sm-12 cl-xs-12 pull-left">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Customer List
      <div class="card-actions">
            <a class="btn-minimize collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="icon-arrow-up"></i></a>
        </div>
      </div>
      <div id="collapseExample" class="card-block" style="margin-top:0px; padding-top:0px; padding-bottom:0px;">
        <div class="col-md-12" style="padding-top:5px; padding-bottom:5px; border-bottom:1px solid #ccc;">
          <div class="row">
            <div class="input-group">
              <input id="filter_ustomer_list" name="input1-group2" class="form-control" placeholder="Search Customer" type="text" onkeyup="filterCustomerList()">
              <span class="input-group-btn">
              <button type="button" onclick="filterCustomerList()" class="btn btn-primary"><i class="fa fa-search"></i></button>
              </span> </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row">
            <div style="flex:1,1,0; position:relative; width:100%;">
              <div id="chat_customer_list_block"  style="overflow-x: hidden; overflow-y: scroll; width:100%;  max-height:550px margin-right:-20px; flex-direction: column; z-index: 100;  box-sizing: border-box;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/col-->
  <div class="col-lg-9 col-md-8 col-sm-12 cl-xs-12 pull-right">
    <div class="card">
      <div id="chat_bix_container_href" class="card-header"> <strong><span id="chat_person_name">"Select Customer"</span> <span id="chat_person_compalint_ticket_number"></span></strong> 
      	<div class="card-actions">
            <a class="recordfilteroption" style="padding: 0.75rem 8px; width: auto;" data-title="View All" data-class="recordall" title="View All" href="#">All</a>
            <a class="recordfilteroption" style="padding: 0.75rem 8px; width: auto;" data-title="View Repair Ticket Only" data-class="recordticket" title="View Repair Ticket Only" href="#">Reqt</a>
            <a class="recordfilteroption" style="padding: 0.75rem 8px; width: auto;" data-title="View Collection Request Only" data-class="recordcollection" title="View Collection Request Only" href="#">Coll</a>
			<a class="recordfilteroption" style="padding: 0.75rem 8px; width: auto;" data-title="View Invoice Only" data-class="recordinvoice" title="View Invoice Only" href="#">Invoice</a>
			<a class="recordfilteroption" style="padding: 0.75rem 8px; width: auto;" data-title="View Website Order Only" data-class="recordorder" title="View Website Order Only" href="#">Order</a>
        </div>
      </div>
      <div class="card-block" style="padding-top:0px; padding-bottom:0px;">
        <div class="row">
          <div style="flex:1,1,0; position:relative; width:100%;">
            <div  style="width:100%; max-height:550px; margin-right:-20px; flex-direction: column; z-index: 100;  box-sizing: border-box;">
              <div id="chat_user_message_box" style="min-height:100px; background:#F0F0F0; vertical-align:middle; flex:0,0,1; margin:10 3px 0px; padding:0px; overflow-x: hidden; overflow-y: scroll;"> </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer" id="footermsgsendblock" style="display:none;">
        <div class="input-group">
          <input type="hidden" id="complaint_id" name="complaint_id" value="0" />
          <input type="hidden" id="complaint_format" name="complaint_format" value="" />
          <input type="hidden" id="customer_id" name="customer_id" value="0" />
          <input type="hidden" id="complaint_log_offset_count" name="complaint_log_offset_count" value="0" />
          <input id="customer_compalint_message" class="form-control" size="16" type="text">
          <span class="input-group-btn">
          <button class="btn btn-success" onclick="submitCompalintMessage();" type="button">Send</button>
          </span> </div>
      </div>
      </form>
    </div>
  </div>
  <!--/col--> 
  <div class="clearer clear"></div>
</div>  
</div>
</div>

<script type="text/javascript">
function filterCustomerList()
{
	var keyword = $("#filter_ustomer_list").val().toLowerCase().trim();
	if(keyword!="")
	{
		$(".user_redirect_list").each(function(index, element) {
            if($(this).text().toLowerCase().indexOf(keyword)!=-1)
			{
				$(this).show();
			}
			else
				$(this).hide();
        });
	}
	else
	{
		$(".user_redirect_list").show();
	}
	
}
function loadChatCustomer()
{
	complaint_log_offset_count = 0;
	clearInterval(complaintChatAutoLoadInterval);
	complaintChatAutoLoadInterval = null;
	
	var data={
			action	:	'customer/loadchatcustomer'				
		};		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				$("#chat_customer_list_block").html(LOADING_HTML);
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
							html+='<div data-herf="#chat_bix_container_href" data-id="user-redirect-id-'+rowData.customer_id+'" onclick="loadCustomerChatComplaint('+rowData.customer_id+')" class="user_redirect_list cst_user_box callout callout-'+(rowData.customer_is_live==1?'success':'danger')+' m-a-0 p-y-1"><span class="ctm-user-count-tag-pin" id="customer_unread_message_count_'+rowData.customer_id+'">'+(rowData.chat_count>0?rowData.chat_count:'')+'</span><div class="avatar pull-right"><img src="'+rowData.customer_image+'" class="img-avatar" alt="'+rowData.customer_name+'"><span class="avatar-status tag-'+(rowData.customer_is_live==1?'success':'danger')+'"></span></div><div><strong id="customer_chat_box_id_'+rowData.customer_id+'">'+rowData.customer_name+'</strong></div><small class="text-muted"><i class="icon-location-pin"></i>&nbsp; '+rowData.customer_address+' </small></div>';
						}
					}
					else
						html = EMPTY_IMAGE_BOX ;
					
					$("#chat_customer_list_block").html(html); 
				}
				message(arr[1],1000);
				getComplaintLogAutoloadChatCount();
			}
		})	
}
loadChatCustomer();

function loadCustomerChatComplaint(customer_id)
{
	complaint_log_offset_count=0;
	complaintChatAutoLoadFlag = false;
	clearInterval(complaintChatAutoLoadInterval);
	complaintChatAutoLoadInterval = null;
	$("#complaint_id").val(0);
	$("#chat_person_name").html($("#customer_chat_box_id_"+customer_id).html());
	$("#chat_person_compalint_ticket_number").html("");
	$("#complaint_log_offset_count").val(0);
	$("#customer_id").val(customer_id);
	$("#chat_user_message_box").html(LOADING_HTML);
	
	var data={
			action			:	"repair/loadcustomerchatcomplaint",
			customer_id		:	customer_id					
		};
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("process|Connecting...",0);
			$("#footermsgsendblock").hide();
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
						var Comp = logTextObj[i];
						
						html = '<div onclick="loadComplaintChatMessage('+Comp.id+',\''+Comp.complaint_format+'\')" class="col-lg-3 col-md-6 col-sm-6 col-xs-12 text-center recordall '+(Comp.complaint_format=='C'?"recordticket":Comp.complaint_format=='S'?"recordinvoice":Comp.complaint_format=='O'?"recordorder":"recordcollection")+'" style="margin-top:10px; cursor:pointer; margin-bottom:5px;"><div class="card card-inverse card-'+(((Comp.status_id==2 && Comp.complaint_format=='C') || Comp.status_id==1 && Comp.complaint_format=='W')?"success":"primary")+' text-xs-center"><div class="card-header text-header-dark">'+(Comp.complaint_format=='C'?"Ticket":Comp.complaint_format=='S'? "Sales Invoice":Comp.complaint_format=='O'?"Web Order":"Collection")+' #<span id="compalint_view_ticket_number_'+Comp.id+Comp.complaint_format+'">'+Comp.code+'</span><span class="tag tag-pill ctm-count-tag-pin-ticket tag-danger pull-right">'+(Comp.new_message>0?Comp.new_message:'')+'</span></div><div class="card-block"><blockquote class="card-blockquote"><p>'+Comp.created_date+'</p><p class="complaint_status_label">'+Comp.status_name+'</p></blockquote></div></div>';
						if(i==0)
						$("#chat_user_message_box").html(html);
						else
						$("#chat_user_message_box").append(html);
						
						$(document).scrollTop( $("#chat_bix_container_href").offset().top ); 
					}
					
					
					$(".recordfilteroption").click(function(){
						var itm = $(this);
						$(".recordall").each(function(index, element) {
							$(this).hasClass(itm.attr('data-class'))?$(this).show():$(this).hide();
								
						});
					});
				}
				else
				{
					$("#chat_user_message_box").html(EMPTY_TEXT_BOX); 
				}
			}
			message(arr[1]);	
		}
	})
}

function loadComplaintChatMessage(complaint_id, complaint_format)
{
	complaint_log_offset_count = 0;
	complaintChatAutoLoadFlag = false;
	complaintChatAutoLoadInterval = null;
	$("#chat_person_compalint_ticket_number").html("#"+$("#compalint_view_ticket_number_"+complaint_id+complaint_format).html());
	$("#complaint_id").val(complaint_id);
	$("#complaint_format").val(complaint_format);
	$("#chat_user_message_box").html(LOADING_HTML);	
	$("#complaint_log_offset_count").val(complaint_log_offset_count);
	var data={
			action			:	"repair/loadcomplaintchatmessage",
			customer_id		:	$("#customer_id").val(),
			complaint_id	:	complaint_id,
			complaint_format:	complaint_format					
		};
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
		beforeSend: function(){
			message("process|Connecting...",0);
		},		
		success:function(output){ 
			$("#footermsgsendblock").show();
			var arr	=	JSON.parse(output);	
			if(arr[0]==200)	
			{
				$("#customer_unread_message_count_"+$("#customer_id").val()).text("");
				var logTextObj = arr[2];
				if(logTextObj.length>0)
				{					
					for(var i=0; i<logTextObj.length;i++)
					{
						var logText = logTextObj[i];
						var alignClass 		= logText.logger_type=='E'? "l":"r";
						var alignClassRev 		= logText.logger_type=='E'? "r":"l";
						var alignClassFull 	= logText.logger_type=='E'? "left":"right";
						var alignClassFullRev 	= logText.logger_type=='E'? "right":"left";
						complaint_log_offset_count = logText.complaint_log_id;
						html = '<div class="cst_user_chat_box callout callout-'+(logText.logger_type=='E'?"info":"warning")+' m-a-0 p-y-1 callout-'+alignClassFull+'-position"><div class="avatar pull-xs-'+alignClassFull+' l_r_b_'+alignClassRev+' chat_img_content_box"><img src="'+logText.logger_image+'" class="img-avatar" alt="'+logText.logger_name+'"></div><div class="l_r_b_'+alignClass+' chat_text_content_box">'+logText.complaint_log_text+'</div><div class="l_r_b_c text-'+alignClassFullRev+'"><small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; '+logText.log_time+'</small>&nbsp; &nbsp;<small class="text-muted"><i class="icon-location-pin"></i>&nbsp; '+logText.logger_name+'</small></div></div>';
						if(i==0)
							$("#chat_user_message_box").html(html);
						else
							$("#chat_user_message_box").append(html);
					}
				}
				else
					$("#chat_user_message_box").html('<center>No Complaint Log Found</center>');
			}
			else
			$("#chat_user_message_box").html('<center>Unable to Load Complaint Log</center>');
			message(arr[1]);
			$("#complaint_log_offset_count").val(complaint_log_offset_count);		
		}
	})
}

function submitCompalintMessage()
{
	 
	if(validateFields("customer_compalint_message","complaint_id"))
	{ 
		var dataAjax={
					action	:	'repair/insertcomplaintlog',
					logtext :	$("#customer_compalint_message").val(),
					id		:	$("#complaint_id").val(),
					complaint_format: $("#complaint_format").val()							
				};
		$.ajax({type:'POST', data:dataAjax, url:'fcm.php', 		
			beforeSend: function(){
				message("connecting|Connecting...",0);
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)	
				{
					$("#customer_compalint_message").val('');					
				}
				if(arr[2]==false)
				arr[1]+=" <span class='card-warning'>Notification Could not Send.</span>";
				message(arr[1]);
			}
		})	
	}
}

function getComplaintLogAutoloadChatCount()
{
	if(countComplaintChatAutoLoadInterval!=null && countComplaintChatAutoLoadFlag==false)
	{
		countComplaintChatAutoLoadFlag = true;
		var data={
				action			:	"repair/getcomplaintlogautoloadchatcount"				
			};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				
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
							if(logText.chat_count>0)
								$("#customer_unread_message_count_"+logText.complaint_customer_id).text(logText.chat_count);
							else
							$("#customer_unread_message_count_"+logText.complaint_customer_id).text('');
						}
					}
				}		
			}				
			
		})	
	}
	countComplaintChatAutoLoadFlag = false;
}
if(enableComplaintChatAutoLoadCount)
countComplaintChatAutoLoadInterval = setInterval(getComplaintLogAutoloadChatCount, countComplaintChatAutoLoadIntervalTimeOut);

function verticalAlignWindowMiddle()
{
	var bodyHeight = $(window).height();
	var formHeight = $('.card-footer').height();
	var marginChatTop = bodyHeight-(formHeight+180);
	var marginUserTop = bodyHeight-(formHeight+180);
	if (marginChatTop > 0)
	{
		$('#chat_user_message_box').css('height', marginChatTop);
		$('#chat_customer_list_block').css('height', marginUserTop);
	}
}
verticalAlignWindowMiddle();

</script> 
