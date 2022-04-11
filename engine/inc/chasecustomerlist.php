<style>	
.customer-searchg-input{
	box-shadow: none;
	border-bottom: 1px solid #ddd;
	width: 100%;
	padding: 5px 8px;
}
.selected-customer{ background-color: #e9e9e9; border: 1px solid #ddd; }
#customer_chase_comment_box{background-color:#fafafa; height:60px; border-top:1px solid #bbb; margin-left:-12px;}
.comment-textarea{ width:100%; padding:5px 8px; border:none; height:40px; border-bottom:1px solid #bbb;}
.btn-comment-box{ padding:10px 2px;}
.btn-chase-comment{ line-height:2; }
.chase-comment-label{ font-weight:400; font-size:13px; color: #999;}

.img-avator-chase-customer{width: 24px !important;
height: 24px !important;}
.input-group-addon-custom{ border:none; border-radius:2px; cursor:pointer;}
.extra_detail_container, #collapseOneDetails{overflow:hidden;}
.my-card-header{ background-color:transparent; border:none;}
.my-card-header h5{ text-align:right;}
.my-accordion{ width:100%;}
.chasecustomermentionbox{ max-height:26px;}
.mentiony-container .mentiony-popover {
	top:auto;
bottom: 30px;}
</style>

<div class="row">
	<div class="col-sm-12 cl-xs-12">
		<div class="card">
			<div id="chat_bix_container_href" class="card-header">
				<strong>Chase Customer <span class="selected_customer_name"></span></strong>
				<div class="card-actions">
					<a class="redirect" data-title="Upload xml" title="Upload xml" href="javascript::void(0)"><i class="icon-arrow-up-circle icons"></i></a>
				</div>
			</div>
			<div class="card-block" style="padding-top:0px; padding-bottom:0px;">
				<div class="row">
					<div class="col-md-3 col-sm-12" style="border-right:1px solid #ddd">
						<div id="chase_customer_list" class="card-block">
							<div class="row">
								<div class="form-group w-100" data-placement="left" data-align="top" data-step="8" data-title="Customer type filter" data-intro="Apply customer filter by type here">
									
								  <select name="filter_chase_customer_type" class="form-control filter_chase_customer_type" size="1">
									<option value="">All Customer type</option>
									<?php
									$chase_customer_type =  ChaseCustomer::getChaseCustomerTypeArray();
									if(count($chase_customer_type)){
										foreach($chase_customer_type as $_type){
											echo "<option value=\"$_type\">$_type</option>";
										}
									}
									?>
								  </select>
									
								</div>
								<div class="input-group" data-placement="left" data-align="top" data-step="1" data-title="Search customer" data-intro="Search customer by name, email, company, contact, status.">
									<input id="filter_customer_list" class="form-control customer-searchg-input" placeholder="Search by name, email, company, contact, status" type="text" onkeyup="filterCustomerList()"> 
									<span class="input-group-addon input-group-addon-custom bg-info"> <span class="fa fa-plus fa-fw" aria-hidden="true"></span> </span>
								</div>
							</div>
							<div class="row">
								<div id="chase_customer_list_block" class="chase_customer_list_block" data-step="2" data-title="Customer list" data-intro="Customer list according to search keyword. By default top 50 latest updated customer will available"></div>
							</div>
						</div>
					</div>
					<div class="col-md-9 col-sm-12">
						<div id="customer_chase_record_box" style="overflow-x: hidden; overflow-y: scroll;" data-step="3" data-title="Customer log details" data-intro="Customer full log details will available here."> </div>
						<div id="customer_chase_comment_box">
							<div class="col-md-10 col-sm-8 pl-0">
								<div class="row">
									<div class="col-sm-12" data-step="4" data-title="Log comment box" data-intro="Enter comments log for your recent conversation.">
										<textarea class="comment-textarea" placeholder="Write customer's log here..."></textarea>
									</div>
									<div class="col-sm-12">
										<div class="row">
											<div class="col-sm-12 px-2" style="padding-top: 3px">
												<div class="row">
													<div class="col-md-6">
														<label class="text-muted chase-comment-label" data-step="5" data-title="Mark mailed" data-intro="Right check this checkbox if you have mailed to customer this time"><input type="checkbox" name="mark_mailed" class="mark_mailed"> Mark mailed</label> &nbsp; 
														<label class="text-muted chase-comment-label" data-step="6" data-title="Mark Called" data-intro="Right check this checkbox if you have called to customer this time"><input type="checkbox" name="mark_called" class="mark_called"> Mark Called</label>
													</div>
													<div class="col-md-6 chasecustomermentionbox" data-step="7" data-title="Tagging" data-intro="You can tag a person. Respected person will instant notify">
														<textarea id="schedule_user_mention" name="schedule_user_mention" spellcheck="false" rows="1" class="form-control ccnmention mention-input-extra" placeholder="@Tag user here..."></textarea>
													</div>
												</div>												
											</div>
										</div>
									</div>								
								</div>
							</div>
							<div class="col-md-2 col-sm-4 btn-comment-box" data-step="9" data-title="Save Details" data-intro="Click to save this log">
								<input type="hidden" id="chase_customer_id" name="chase_customer_id" value="0"/>
								<a type="button" class="btn-chase-comment btn btn-block btn-success">Save</a>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>
<script type="text/javascript">
var marginHeight = 55;
$(".customer-list-item, .btn-chase-comment, .input-group-addon-custom").unbind();
$(document).ready(function(){
	$("#chase_customer_list").css('height', $('#main').height() - marginHeight);
	$("#customer_chase_record_box").css('height', $('#main').height() - ($('#customer_chase_comment_box').height() + marginHeight));
	$("#chase_customer_list_block").css('height', $('#chase_customer_list').height() - $('#chase_customer_list .row').height());
	filterCustomerList();
	showMeIntro('intro-chase-customer');
	
	$('.ccnmention').mentiony({
		applyInitialSize:   false,
		showMentionedItem: false,
		onDataRequest: function (mode, keyword, onDataRequestCompleteCallback) {
			if(mentionUserData == null){
				var dataAjax = {
					action: 'system/getmentionuser',
					keyword: keyword
				};
			   $.ajax({
						method: "POST",
						url: sitePath+"ajax.php",
						data:dataAjax,
						dataType: "json",
						success: function (response) {
							var data = response.data;
							mentionUserData = data;
							data = jQuery.grep(data, function( item ) {
								return item.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1;
							});
							onDataRequestCompleteCallback.call(this, data);
						}
					});
				onDataRequestCompleteCallback.call(this, data);
			}
			else{
				var data = mentionUserData;
				data = jQuery.grep(data, function( item ) {
					return item.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1;
				});
				onDataRequestCompleteCallback.call(this, data);
			}
		},
		timeOut: 0,
		debug: 1,
	});
});
</script>