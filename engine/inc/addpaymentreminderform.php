<div class="row" id="payment_reminder_form_container">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<form id="addpaymentreminderform" name="addpaymentreminderform">
			<div class="card">
				<div class="card-header">
					<i class="fa fa-plus"/>
					<strong>
						<?=$formHeading?>
					</strong>
				</div>
				<div class="card-block">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group">
								<label for="payment_reminder_recipients">Recipient<sup>*</sup>
								</label>
								<input type="text" data-step="1" data-intro="Recipient name" data-label="Recipient" id="payment_reminder_recipients" name="payment_reminder_recipients" data-label="Recipient name" class="form-control" onKeyUp="getDropdown(this, 'PaymentReminder<=>payment_reminder_recipients')"  value="<?php echo isset($payment_reminder_recipients)?$payment_reminder_recipients:''?>" placeholder="Enter recipient name">
								</input>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group">
								<label for="payment_reminder_related_store">Related store<sup>*</sup>
								</label>
								<select data-step="2" data-intro="Store for which you are requesting purchase order" data-label="Store" id="payment_reminder_related_store" name="payment_reminder_related_store" class="form-control" size="1">
									<?php $store = new Store(0);echo $store->getOptions(isset($payment_reminder_related_store)?$payment_reminder_related_store:"0");
?>
								</select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group">
								<label for="payment_reminder_amount">Payment amount<sup>*</sup>
								</label>
								<input type="number" data-step="3" data-intro="Payment amount" data-label="Amount" step="0.01" min="0" id="payment_reminder_amount" name="payment_reminder_amount" data-label="Payment amount" class="form-control" value="<?php echo isset($payment_reminder_amount)?$payment_reminder_amount:''?>" placeholder="Enter payment amount">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group">
								<label for="payment_reminder_currency">Currency<sup>*</sup>
								</label>
								<select data-step="4" data-intro="Currency in which your reminder will be available. Reminder calculation for payments will be based on selected currency" data-label="Currency" id="payment_reminder_currency" name="payment_reminder_currency" class="form-control" size="1">
									<?php echo getCurrencyType(isset($payment_reminder_currency)?$payment_reminder_currency:null); ?>
								</select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group"  data-step="5" data-intro="Due date for payment" data-label="Due Date">
								<label for="payment_reminder_due_date">Due date<sup>*</sup>
								</label>
								<div class="input-group date">
									<input data-label="Due date" class="form-control" id="payment_reminder_due_date" name="payment_reminder_due_date" placeholder="YYYY-MM-DD" value="<?php echo isset($payment_reminder_due_date)?$payment_reminder_due_date:date('Y-m-d h:i A')?>" type="text">
									<span class="input-group-addon">
										<label style="margin-bottom:0px;" for="payment_reminder_due_date">
											<i class="fa fa-calendar fa-lg m-t-2"/>
										</label>
									</span>
								</div>
								<script type="text/javascript">
									$('#payment_reminder_due_date').datetimepicker({
										format: 'yyyy-mm-dd HH:ii P',
										autoclose:true,
										daysOfWeekHighlighted: '0,6',
										todayHighlight:true,
										fontAwesome : true,
										showMeridian: true,
									});
								</script>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group"  data-step="6" data-intro="Select payment reminder current status" data-label="Status">
							  <label for="payment_reminder_status">Status<sup>*</sup></label>
							  <select id="payment_reminder_status" name="payment_reminder_status" class="form-control" size="1">
								<?php
								$WcStatus = new WcStatus(0);
								echo $WcStatus->getOptions(isset($payment_reminder_status)?$payment_reminder_status:"2");
								?>
							  </select>
							</div>
                      </div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group"  data-step="7" data-intro="Select Either Pay(To send Payment to Recipient) or Receive (To Get Payment from Recipient)" data-label="Payment type">
							  <label> Payment type -</label>
							  <div class="row">
								  <div class="col-xs-6">
									<label class="switch switch-icon switch-pill switch-info">
									<input class="switch-input payment_reminder_type" <?=(!isset($payment_reminder_type) || (isset($payment_reminder_type) && $payment_reminder_type == 1))? "checked":"";?> value="1" name="payment_reminder_type" type="radio">
									<span class="switch-label" data-on="✓" data-off="✕"></span> <span class="switch-handle"></span> </label><strong> Pay</strong>
								  </div>
								  <div class="col-xs-6">
									<label class="switch switch-icon switch-pill switch-success">
									<input class="switch-input payment_reminder_type" <?=(isset($payment_reminder_type) && $payment_reminder_type == 2)? "checked":"";?> value="2" name="payment_reminder_type" type="radio">
									<span class="switch-label" data-on="✓" data-off="✕"></span> <span class="switch-handle"></span> </label><strong> Receive</strong>
								  </div>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-group"  data-step="6" data-intro="Select payment reminder interval to get notified again" data-label="Reminder interval">
							  <label for="payment_reminder_cycle_clock">Reminder interval<sup>*</sup></label>
							  <select id="payment_reminder_cycle_clock" name="payment_reminder_cycle_clock" class="form-control" size="1">
								<?php
								$clockPeriod = PaymentReminder::getPaymentReminderCycleClock();
								foreach($clockPeriod as $_clock)
								echo "<option value='$_clock[id]' ".((isset($payment_reminder_cycle_clock) && $payment_reminder_cycle_clock == $_clock['id']) ? "selected" : "").">$_clock[label]</option>";
								?>
							  </select>
							</div>
                      </div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="payment_reminder_title">Title description</label>
								<textarea data-step="8" data-intro="Details about payment reminder" data-label="Description" id="payment_reminder_title" rows="4" name="payment_reminder_title" class="form-control" placeholder="Enter payment reminder description"><?php echo isset($payment_reminder_title)?$payment_reminder_title:''?></textarea>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group"  data-step="9" data-intro="Tag users to get notified on due date" data-label="Tag people"><textarea id="payment_reminder_mention" name="payment_reminder_mention" spellcheck="false" rows="1" class="form-control payment_reminder_mention mention-input-extra" placeholder="@Tag user here..."></textarea>
							</div>
							<?php if($payment_reminder_id > 0) echo UserTag::getModuleTag('Y', $payment_reminder_id, true);?>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-footer">
					<div class="row">
						<div class="col-sm-12">
							
							<button type="reset" class="btn btn-outline-danger"><i class="fa fa-refresh fa-fw"/> Reset</button>
							&nbsp;
							<button type="button" id="btn_save_payment_reminder" onClick="confirmMessage.Set('Are you sure to save payment reminder...?', 'addpaymentreminderform');" class="btn btn-success mt-0 submission_handler_btn"><i class="fa fa-check fa-fw"/> Save Reminder</button>
							
							
						</div>
					</div>
				</div>
				<input type="hidden" id="action" name="action" value="<?=isset($action)?$action:" ";?>"/>
				<input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"/>
				<input type="hidden" id="payment_reminder_id" name="payment_reminder_id" value="<?=isset($payment_reminder_id)?$payment_reminder_id:" 0";?>"/>
			</div>
		</form>
	</div>
</div>
<script>
$(document).ready(function(){
	showMeIntro('payment-reminder');
	$('.payment_reminder_mention').mentiony({
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

function getReminderMentionyItems(){
	return $(".payment_reminder_mention").parent('div').find(".mentiony-content").find('.mention-area .highlight .mentiony-link').map(function () { return { id : $(this).attr('data-item-id'),
	name : $(this).text()}; }).get();
}

function addpaymentreminderform(){
	var formFields	=	"payment_reminder_recipients,payment_reminder_related_store,payment_reminder_amount,payment_reminder_currency,payment_reminder_due_date";
	if(validateFields(formFields))
	{
		var data={
					action						:	$("#action").val(),
					payment_reminder_tag_user	:	getReminderMentionyItems(),		
					payment_reminder_type		: 	$(".payment_reminder_type:checked").val()
				};
		data = $.extend(data, $("#addpaymentreminderform").serializeFormJSON());		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("connecting|Connecting...",0);
				dissableSubmission();
			},		
			success:function(output){ 
			sendSocketMessage('Saved purchase order');
				enableSubmission(output);
				var arr	=	JSON.parse(output);					
				message(arr[1]);
				if(arr[0] == 200){
				setTimeout(function(){
							Redirect(arr[2]);	
						}, 2000);
				}
			}
		});
	}
}
</script>
	