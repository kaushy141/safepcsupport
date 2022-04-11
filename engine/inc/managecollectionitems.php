<?php if(isset($wc_id) && $wc_id !=0) echo drawCollectionProcedure($wc_id);?>

<div class="row" id="collection_form_container">
  <div class="col-sm-12">
    <form id="managecollection" name="managecollection">
      <div class="card">
        <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i> Collection Items Info</div>
        <div class="card-block">
          <div class="row" id="collection_item_id_boxb">
              <div class="col-md-12">
                <table id="sortingtable" class="table table-striped">
                  <thead>
                    <tr>
                      <th width="10px">#</th>
                      <th>Item</th>
                      <th class="text-center" width="68px">Qty</th>
                      <th class="hidden-xs hidden-sm hidden-md">Weight/I</th>
                      <th class="hidden-xs hidden-sm hidden-md">Charge</th>
                      <th class="hidden-xs hidden-sm hidden-md">Paid</th>
                      <th class="hidden-xs text-right">isCharge</th>
                      <th class="hidden-xs text-right">isPaid</th>
                      <th class="text-center">Details</th>
                    </tr>
                  </thead>
                  <tbody id="collection_item_body">
                    <?php 
			  $WcItem = new WcItem();
			  $collectionItemList = $WcItem->getList((isset($wc_id) && $wc_id != 0)? $wc_id : 0);
			  $cial_array = array();
			  foreach($collection_item_list_array as $saveItem){
			  $cial_array[$saveItem['key']] = $saveItem;
			  }
			  //print_r($collection_item_list_array);
			  
			  foreach($collectionItemList as $item):
			  	$qty = $wet = $chamount = $pdamount = $check_charge = $check_paid = $itmdesc = $checkedStatus = $disableChild = $wc_process_asset_code_data = "";
				if(isset($cial_array[$item['wci_id']]))
				{			//print_r($cial_array[$item['wci_id']]);
					$checkedStatus = "checked";							
					$qty 			= $cial_array[$item['wci_id']]['wcr_item_qty'];
					$wet 			= $cial_array[$item['wci_id']]['wcr_item_weight'];
					$chamount 		= $cial_array[$item['wci_id']]['wcr_item_charge_amount'];
					$pdamount 		= $cial_array[$item['wci_id']]['wcr_item_charge_amount_paid'];
					$check_charge	= $cial_array[$item['wci_id']]['wcr_item_charge_format']=="CHARGE" ? "checked=\"checked\"":"";
					$check_paid 	= $cial_array[$item['wci_id']]['wcr_item_charge_format']=="PAID" ? "checked=\"checked\"":"";
					$itmdesc 		= $cial_array[$item['wci_id']]['wcr_item_description'];
					$wc_process_asset_code_data = $item['wc_process_asset_code'];
				}
				else{
					$disableChild = "disabled";
					$check_charge = "checked=\"checked\"";
				}
			  
			  ?>
                    <tr id="tr_wcitem_<?=$item["wci_id"]?>">
                      <td><input <?=$checkedStatus?> id="wcitem_<?=$item["wci_id"]?>" name="data_wci_item_id[]"  value="<?=$item["wci_id"]?>" class="data_wci_item_id_raw" type="checkbox"></td>
                      <td><label for="wcitem_<?=$item["wci_id"]?>"><?=$item["wci_name"]?></label></td>
                      <td><input type="number" class="form-control text-center" name="data_wci_qtiy_id[]" value="<?=$qty?>"  id="item_quantity_<?=$item["wci_id"]?>" <?=$disableChild?>></td>
                      <td class="hidden-xs hidden-sm hidden-md"><input type="number" class="form-control" name="data_wci_weit_id[]" value="<?=$wet?>" id="item_weight_<?=$item["wci_id"]?>" <?=$disableChild?> ></td>
                      <td class="hidden-xs hidden-sm hidden-md"><input type="number" class="form-control" name="data_wci_chamount_id[]" value="<?=$chamount?>" id="item_charge_amount_<?=$item["wci_id"]?>" <?=$disableChild?> ></td>
                      <td class="hidden-xs hidden-sm hidden-md"><input type="number" class="form-control" name="data_wci_pdamount_id[]" value="<?=$pdamount?>" id="item_paid_amount_<?=$item["wci_id"]?>" <?=$disableChild?> ></td>
                      <td class="tdboxformat hidden-xs text-right" align="center"><label class="switch switch-icon switch-pill switch-success pull-right">
                          <input class="switch-input data_wci_chformat_id" id="wcr_item_format_CHARGE_<?=$item["wci_id"]?>" value="CHARGE" name="data_wci_chformat_id_<?=$item["wci_id"]?>" <?=$check_charge?> type="radio" <?=$disableChild?> >
                          <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></td>
                      <td class="tdboxpaid hidden-xs text-right" align="center"><label class="switch switch-icon switch-pill switch-success pull-right">
                          <input class="switch-input data_wci_chformat_id" id="wcr_item_format_PAID_<?=$item["wci_id"]?>" value="PAID"  name="data_wci_chformat_id_<?=$item["wci_id"]?>" <?=$check_paid?> type="radio" <?=$disableChild?> >
                          <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></td>
                      <td class="tdboxdesc"><input type="text" class="form-control data_wci_itmdesc_id" value="<?=$itmdesc?>" name="data_wci_itmdesc_id[]" id="item_description_<?=$item["wci_id"]?>" <?=$disableChild?>></td>
                      <input type="hidden" name="wc_item_process_asset_code[<?=$item["wci_id"]?>]" <?=$disableChild?> value="<?php echo $wc_process_asset_code_data; ?>" />
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
          </div>
          <!--/row--> 
        </div>
      </div>
      <div class="card">
        <div class="card-header card-primary"><i class="fa fa-file-text"></i> Customer Signature </div>
        <div class="card-block">
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <div id="signature-pad" class="m-signature-pad">
                  <div class="m-signature-pad-body">
                    <canvas></canvas>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-default text-danger clear" onclick="clearSignature(event);" data-action="clear">Clear Sign</button>
                <button type="button" class="btn btn-default text-success save" data-action="save-png" onclick="saveSignature(event);">Upload Sign</button>
                <input type="hidden" value="" name="wc_transferor_signature" id="wc_transferor_signature" />
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group" id="signature_saved_image" style="border:dashed; border-color:#CCC; min-height:190px;">
                <?php if(isset($wc_transferor_signature) && $wc_transferor_signature!=""):?>
                <img alt="No Image found" src="<?php echo $app->basePath($wc_transferor_signature);?>" class="img-responsive" />
                <?php endif; ?>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-12">
				  <div class="form-group">
					<label for="wc_completion_date">Collection Complete Date<sup>*</sup></label>
					<div class="input-group date">
					  <input type='text' class="form-control" id="wc_completion_date" name="wc_completion_date" placeholder="YYYY-MM-DD" readonly="readonly" value="<?=isset($wc_completion_date)?date("Y-m-d", strtotime($wc_completion_date)):date('Y-m-d');?>" />
					  <span class="input-group-addon">
					  <label style="margin-bottom:0px;" for="wc_completion_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
					  </span> </div>
					<script type="text/javascript">
						$('#wc_completion_date').datepicker({
							format: "yyyy-mm-dd",
							autoclose:true,
							startDate  : '<?=date('Y-m-d')?>',
							daysOfWeekHighlighted: '0,6',
							todayHighlight:true
						});
				</script> 
				  </div>
				</div>
                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-12">
                  <div class="form-group">
                    <label for="wc_arrival_time">Arrival Time<sup>*</sup></label>
                    <div class="input-group clockpickerArrival" data-placement="right" data-align="bottom">
                      <input class="form-control" id="wc_arrival_time" name="wc_arrival_time" maxlength="10" placeholder="Enter arrival time" type="text" value="<?=isset($wc_arrival_time)?$wc_arrival_time:"";?>">
                      <span class="input-group-addon"> <span class="fa fa-clock-o fa-lg"></span> </span> </div>
                    <script type="text/javascript">
				$(document).ready(function(e) {
                   $('.clockpickerArrival').clockpicker({placement: 'top', autoclose:true, donetext: 'Done'}); 
                });
            	</script> 
                  </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-12">
                  <div class="form-group">
                    <label for="wc_departure_time">Departure Time<sup>*</sup></label>
                    <div class="input-group clockpickerDeparture" data-placement="right" data-align="bottom" data-autoclose="true">
                      <input class="form-control" id="wc_departure_time" name="wc_departure_time" maxlength="10" placeholder="Enter departure time" type="text" value="<?=isset($wc_departure_time)?$wc_departure_time:"";?>">
                      <span class="input-group-addon"> <span class="fa fa-clock-o fa-lg"></span> </span> </div>
                    <script type="text/javascript">
				$(document).ready(function(e) {
                   $('.clockpickerDeparture').clockpicker({placement: 'top',}); 
                });
            	</script> 
                  </div>
                </div>
              </div>
            </div>
            </div>            
            <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="wc_member_of_staff_name">Member of staff name<sup></sup></label>
                <input class="form-control" id="wc_member_of_staff_name" name="wc_member_of_staff_name" maxlength="500" placeholder="Enter Member of staff name" type="text" value="<?=isset($wc_member_of_staff_name)?$wc_member_of_staff_name:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="wc_authority_member_of_staff">Authority member of staff<sup></sup></label>
                <input class="form-control" id="wc_authority_member_of_staff" name="wc_authority_member_of_staff" maxlength="50" placeholder="EnterAuthority member of staff" type="text" value="<?=isset($wc_authority_member_of_staff)?$wc_authority_member_of_staff:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="wc_status_id">Collection Status<sup>*</sup></label>
                <select id="wc_status_id" name="wc_status_id" class="form-control" size="1">
                  <?php
                $WcStatus = new WcStatus(0);
				echo $WcStatus->getOptions(isset($wc_status_id)?$wc_status_id:"0");
				?>
                </select>
              </div>
            </div>
			</div>
			<div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_mail_hwcn_to_customer"><i class="fa fa-envelope-o fa-lg m-t-2"></i> &nbsp;Send Hazardous Report to Customer</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="wc_mail_hwcn_to_customer" value="1" name="wc_mail_hwcn_to_customer" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_mail_wcnn_to_customer"><i class="fa fa-envelope-o fa-lg m-t-2"></i> &nbsp;Send Wastage Cons. to Customer</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="wc_mail_wcnn_to_customer" value="1" name="wc_mail_wcnn_to_customer" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="wc_mail_docn_to_customer"><i class="fa fa-envelope-o fa-lg m-t-2"></i> &nbsp;Send Duty of care to Customer</label>
                <label class="switch switch-icon switch-pill switch-success pull-right">
                  <input class="switch-input" id="wc_mail_docn_to_customer" value="1" name="wc_mail_docn_to_customer" type="checkbox">
                  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
              </div>
            </div>
          </div>
          <!--/row--> 
        </div>
      </div>
      <div class="card">
        <div class="card-footer">
          <div class="row">
            <div class="col-sm-12">
              <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-md "></i> Reset</button>
              &nbsp;
              <button type="button" onClick="confirmMessage.Set('Are you sure to save Collection validation...?', 'manageCollection');" class="btn btn-success mt-0"><i class="fa fa-check-circle fa-md "></i> <span id="btn_action_name">
              <?=$btnText?>
              </span> COLLECTION </button>
            </div>
          </div>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"managecollection";?>"  />
        <input type="hidden" id="wc_id" name="wc_id" value="<?=isset($wc_id)?$wc_id:"0";?>"  />
      </div>
    </form>
  </div>
</div>
<div class="row" style="display:none;" id="collection_submited_container">
  <div class="col-sm-12">
    <form id="collection_submitted_form" name="collection_submitted_form">
      <div class="card">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="card card-inverse card-success text-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Collection Request saved successfully</p>
                    <p> <b id="collection_wc_code"></b><br/>
                    </p>
                    <p> <a id="collection_r_hwc_link" href="#" class="btn btn-primary text-white btn-block"><span class="fa fa-file-pdf-o fa-lg mb-1"></span><br/>
                      Download Hazardous Waste Consignment</a> </p>
                    <p> <a id="collection_r_wcn_link" href="#" class="btn btn-primary text-white btn-block"><span class="fa fa-file-pdf-o fa-lg mb-1"></span><br/>
                      Download Waste Collection (WCN)</a> </p>
                    <p> <a id="collection_r_doc_link" href="#" class="btn btn-primary text-white btn-block"><span class="fa fa-file-pdf-o fa-lg mb-1"></span><br/>
                      Download Duty of Care: Waste Transfer</a> </p>
                    <p> <a id="collection_r_cer_link" href="#" class="btn btn-primary text-white btn-block"><span class="fa fa-file-pdf-o fa-lg mb-1"></span><br/>
                      Download Certificate</a> </p>
                  </blockquote>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 col-md-6">
                  <p><a id="collection_update_link" href="" class="btn btn-info text-white btn-block"><span class="fa fa-cloud-upload fa-lg mb-1"></span> &nbsp; Update</a></p>
                </div>
                <div class="col-sm-6 col-md-6">
                  <p><a href="<?php echo $app->siteUrl('addcollection');?>" class="btn btn-default text-black btn-block"><span class="fa fa-plus fa-lg mb-1"></span> &nbsp; Add New Collection</a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
function diff(start, end) {
    start = start.split(":");
    end = end.split(":");
    var startDate = new Date(0, 0, 0, start[0], start[1], 0);
    var endDate = new Date(0, 0, 0, end[0], end[1], 0);
    return endDate.getTime() - startDate.getTime();
}
function manageCollection()
{
	
	// if(diff($("#wc_arrival_time").val(), $("#wc_departure_time").val()) <= 0)
	// {
		// message("danger|Departure time must be grater than Arrival time",3000);
		// $("#wc_departure_time_hour").focus();
		// return false;
	// }
	var data_wci_chformat_id = [];
	$(".data_wci_chformat_id:checked").each(function(index, element) {
		if(!$(this).is(":disabled"))
        data_wci_chformat_id.push($(this).val());
    });
	
	
	var data={
		action				:	$("#action").val(),
		'data_wci_chformat_id'	:	data_wci_chformat_id	
	};
	data = $.extend(data, $("#managecollection").serializeFormJSON());		
console.log(data);
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Connecting...",0);
			dissableSubmission();
		},		
		success:function(output){
			var arr	=	JSON.parse(output);	
			enableSubmission(output);
			if(arr[0]==200)
			{
				$("#collection_wc_code").html(arr[3]);
				$("#collection_form_container").remove();
				$("#collection_submited_container").show();
				$("#collection_update_link").attr("href","<?php echo $app->siteUrl('updatecollection')?>/"+arr[2]);
				$("#collection_r_hwc_link").attr("href",arr['hwc_link']);
				$("#collection_r_wcn_link").attr("href",arr['wcn_link']);
				$("#collection_r_doc_link").attr("href",arr['doc_link']);
				$("#collection_r_cer_link").attr("href",arr['cer_link']);
			}
			message(arr[1],2000);
		}
	})	
	
}

</script> 
<script type="text/javascript">
var wrapper = document.getElementById("signature-pad");
var canvas = wrapper.querySelector("canvas");
var signaturePad = new SignaturePad(canvas);

function clearSignature(event)
{
	signaturePad.clear();
	$("#signature_link").val('');
	$("#signature_saved_image").html('');
}

function saveSignature(event)
{
	if (signaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
		var data={
			action	:	'collection/savesignature',
			signature:signaturePad.toDataURL()				
		};		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Saving Signature...", 0);
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#wc_transferor_signature").val(arr[2]);
					$("#signature_saved_image").html('<img src="'+arr[3]+'" class="img-responsive" />');
				}
				else
				{
					$("#signature_link").val('');
					$("#signature_saved_image").html('');
				}
				message(arr[1]);
			}
		})
    }
}
$(".data_wci_item_id_raw").on("change", function(){
	var itm = $(this);
	var id = $(this).attr('id');
	if($(this).prop("checked")){ 		
		$(this).parent("td").parent("tr").find("input").each(function(index, element) {
			if(id  != element.id)
            $(this).prop("disabled", false);
        });
		$("#wcr_item_format_CHARGE_"+itm.val()).prop("checked",true);
	}
	else{
		$(this).parent("td").parent("tr").find("input").each(function(index, element) {
			if(id != element.id)
            $(this).prop("disabled", true);
        });
		
		$("#wcr_item_format_CHARGE_"+itm.val()).prop("checked",false);
		$("#wcr_item_format_PAID_"+itm.val()).prop("checked",false);
	}
});

$(document).ready(function() {
    var table = $('#sortingtable').DataTable( {
        scrollCollapse: false,
        paging:         false,
		ordering: false
    } );
} );
</script>