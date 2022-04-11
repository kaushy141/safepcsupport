<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?php echo $formHeading; ?></strong></div>
      <form id="addcreditnoteform" name="addcreditnoteform">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="credit_note_reference">Credit Reference<sup>*</sup></label>
                <input class="form-control" id="credit_note_reference" name="credit_note_reference" maxlength="50" placeholder="Enter credit note reference" type="text" value="<?=isset($credit_note_reference)?$credit_note_reference:"";?>">
              </div>
            </div>
            
			<div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="credit_note_date">Credit note date<sup>*</sup></label>
                <div class="input-group date">
                  <input type='text' class="form-control" id="credit_note_date" name="credit_note_date" placeholder="YYYY-MM-DD" value="<?=isset($credit_note_date)?date("Y-m-d", strtotime($credit_note_date)):date('Y-m-d');?>" />
                  <span class="input-group-addon">
                  <label style="margin-bottom:0px;" for="credit_note_date"><i class="fa fa-calendar fa-lg m-t-2"></i></label>
                  </span> </div>
                <script type="text/javascript">
                    $('#credit_note_date').datepicker({
                        format: "yyyy-mm-dd",
						autoclose:true,								
						daysOfWeekHighlighted: '0,6',
						todayHighlight:true
                    });
            </script> 
              </div>
            </div>
			</div>
			<div class="row">
			<div class="col-sm-12">
              <div class="form-group">
                <label for="credit_note_item_description">Description<sup>*</sup></label>
                <input class="form-control" id="credit_note_item_description" name="credit_note_item_description" maxlength="50" placeholder="Enter credit note item description" type="text" value="<?=isset($credit_note_item_description)?$credit_note_item_description:"";?>">
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="credit_note_quantity">Quantity<sup>*</sup></label>
                <input class="form-control" id="credit_note_quantity" name="credit_note_quantity" min="1" max="9999" placeholder="Enter quantity" type="number" value="<?=isset($credit_note_quantity)?$credit_note_quantity:"";?>">
              </div>
            </div> 
            <div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="credit_note_amount">Amount<sup>*</sup></label>
                <input class="form-control" id="credit_note_amount" name="credit_note_amount" step="0.01" min="0" max="9999" placeholder="Enter amount" type="number" value="<?=isset($credit_note_amount)?$credit_note_amount:"";?>">
              </div>
            </div>
			<div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="credit_note_amount_total">Total Amount<sup>*</sup></label>
                <input class="form-control" readonly id="credit_note_amount_total" name="credit_note_amount_total" step="0.01" min="0" max="9999" placeholder="Enter amount" type="number" value="<?=isset($credit_note_amount_total)?$credit_note_amount_total:"";?>">
              </div>
            </div>
            
			<div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="credit_note_vat">Vat Percent<sup>*</sup></label>
                <input class="form-control" id="credit_note_vat" name="credit_note_vat" step="0.01" min="0" max="100" placeholder="Enter vat percent" type="number" value="<?=isset($credit_note_vat)?$credit_note_vat:"0";?>">
              </div>
            </div>
			
			<div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="credit_note_remaining">Remaining amount<sup>*</sup></label>
                <input class="form-control" id="credit_note_remaining" name="credit_note_remaining" step="0.01" min="0" placeholder="Enter remaining amount" type="number" value="<?=isset($credit_note_remaining)?$credit_note_remaining:"0";?>">
              </div>
            </div>
            
			<div class="col-sm-6 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="credit_note_currency">Currency<sup>*</sup></label>
                <input class="form-control" id="credit_note_currency" name="credit_note_currency" maxlength="3" placeholder="Enter amount currency" type="text" value="<?=isset($credit_note_currency)?$credit_note_currency:"";?>">
              </div>
            </div>
			
          </div>
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to save credit note...?', 'saveCreditNote')" class="btn btn-sm btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i>
          Save Credit Note
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
		<input type="hidden" id="credit_note_refund_id" name="credit_note_refund_id" value="<?=isset($credit_note_refund_id)?$credit_note_refund_id:"0";?>" />
        <input type="hidden" id="credit_note_id" name="credit_note_id" value="<?=isset($credit_note_id)?$credit_note_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">
$("#credit_note_quantity, #credit_note_amount, #credit_note_vat").on("change", function(){
	var total_amount = (parseFloat($("#credit_note_quantity").val()) * parseFloat($("#credit_note_amount").val())) + parseFloat(parseFloat($("#credit_note_quantity").val()) * ((parseFloat($("#credit_note_amount").val()) * parseFloat($("#credit_note_vat").val()))/100));
	$("#credit_note_amount_total").val(total_amount);
})
function saveCreditNote()
{
	if(validateFields("credit_note_date, credit_note_reference, credit_note_item_description, credit_note_quantity, credit_note_amount, credit_note_currency, credit_note_vat, credit_note_remaining"))
	{
		var data={
					action	:	$("#action").val()					
				};
		data = $.extend(data, $("#addcreditnoteform").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#credit_note_id").val(arr[2]);				
				}
				message(arr[1]);
			}
		})	
	}
}

</script> 