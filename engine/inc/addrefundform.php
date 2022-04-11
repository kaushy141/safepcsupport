<div class="row" id="collection_form_container">
  <div class="col-xs-12 col-sm-12 col-md-12">
  <form id="addrefund" name="addrefund">
    <div class="card">
      <div class="card-header"><i class="fa fa-align-justify"></i>Refund </div>
      <div class="card-block">
        <div class="row">
          <?php if(isset($RefundWarning) && $RefundWarning != ""){?>
          <div class="col-xs-12">
            <div class="alert alert-danger text-center" role="alert"> <i class="fa fa-warning"></i> Warning : <?php echo $RefundWarning;?> </div>
          </div>
          <?php	
            }?>
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="card mb-3">
              <div class="card-header"><i class="fa fa-user"></i> Customer information</div>
              <div class="card-block">
                <div class="bd-example">
                  <dl class="row">
                    <dt class="col-sm-3">Name :</dt>
                    <dd class="col-sm-9">
                      <?=$refund_to_name;?>
                    </dd>
                    <dt class="col-sm-3">Company : </dt>
                    <dd class="col-sm-9">
                      <?=$refund_to_company?$refund_to_company:"-";?>
                    </dd>
                    <dt class="col-sm-3">Phone :</dt>
                    <dd class="col-sm-9">
                      <?=$refund_to_email;?>
                    </dd>
                    <dt class="col-sm-3">Email :</dt>
                    <dd class="col-sm-9">
                      <?=$refund_to_email;?>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="card mb-3">
              <div class="card-header"><i class="fa fa-user"></i> Refund requisite section</div>
              <div class="card-block">
                <div class="bd-example">
                  <dl class="row">
                    <dt class="col-sm-3">Refund For :</dt>
                    <dd class="col-sm-9">
                      <?=$refund_for_name;?>
                    </dd>
                    <dt class="col-sm-3">Reference : </dt>
                    <dd class="col-sm-9">
                      <?=$refund_for_reference;?>
                    </dd>
                    <dt class="col-sm-3">Ref. Dated :</dt>
                    <dd class="col-sm-9">
                      <?=dateView($refund_for_date, 'FULL');?>
                    </dd>
                    <dt class="col-sm-3">Ref. Status :</dt>
                    <dd class="col-sm-9">
                      <?=$refund_for_name;?>
                      was
                      <?=$refund_for_status;?>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <?php if($refundLog = $refund->getRefundLog()){?>
          <div class="col-xs-12">
            <div class="card mb-3">
              <div class="card-header"><i class="fa fa-comments"></i> Refund log</div>
              <div class="card-block">
                <?php
        	foreach($refundLog as $log)
			echo ComplaintLog::drawLogRecord($log);
		?>
              </div>
            </div>
          </div>
          <?php }?>
          
        </div>
        <?php if($refund_id){?>
        <div class="row">
          <div class="col-xs-12">
            <div class="card mb-3">
              <div class="card-header"><i class="fa fa-file-text"></i> Refund Action</div>
              <div class="card-block">
                <div class="row py-0">
                  <?php
                      if($refund_initiated_by != 0)
					  {
                      ?>
                  <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="card">
                      <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                        <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($initiator_image,50)?>"> </div>
                        <div class="px-2 justify-content-center">
                          <div class="text-value-sm text-primary text-center">Initiated by <?php echo $initiator_name?></div>
                          <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($refund_initiated_date))?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                      }
                      ?>
                  <?php
                      if($refund_process_by != 0)
					  {
                      ?>
                  <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="card">
                      <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                        <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($processor_image,50)?>"> </div>
                        <div class="px-2 justify-content-center">
                          <div class="text-value-sm text-primary text-center">Process by <?php echo $processor_name?></div>
                          <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($refund_process_date))?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                      }
                      ?>
                  <?php
                      if($refund_completed_by != 0)
					  {
                      ?>
                  <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="card">
                      <div class="card-body p-0 d-flex align-items-center  justify-content-between p-2">
                        <div class="pl-0 justify-content-start"> <img width="50px" class="img-avator img img-responsive" src="<?php echo getResizeImage($completor_image,50)?>"> </div>
                        <div class="px-2 justify-content-center">
                          <div class="text-value-sm text-primary text-center"><?php echo $refund_status == 1 ? "Completed":"Cancelled"?> by <?php echo $completor_name?></div>
                          <div class="text-value-sm text-center text-muted text-xs"><?php echo date('d M-Y h:i A', strtotime($refund_completed_date))?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                      }
                    ?>
                
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php }?>
		<div class="row">
		<?php if($refund_id == 0 ||$refund_completed_by == 0){?>
          <div class="col-xs-12">
		  <div class="card mb-3">
		  <div class="card-header">
			<i class="icon-bubbles" aria-hidden="true"></i> <span>Comments</span>
			</div>
			<div class="card-body">
            <div class="form-group p-1 mb-0">
              <textarea rows="4" id="refund_comments" data-label="Refund Comments" placeholder="Write Comments here..." name="refund_comments" class="form-control"></textarea>
            </div>
			</div>
			</div>
          </div>
          <?php }?>
		</div>
        <div class="row">
          <div class="col-xs-12">
			<div class="card mb-3">
		  <div class="card-header">
			<i class="fa fa-forward" aria-hidden="true"></i> <span>Refund Products</span>
			</div>
			<div class="card-body p-1">
            <table class="table mb-0 table-folded">
			  <thead>
				  <tr>
					<th>Name</th>
					<th>Price</th>
					<th>Refund</th>
				  </tr>
			  </thead>
              <tbody>
              <?php 
			  if($method == 'FINALISE' || $method == 'FINISH' || $method == 'VIEW')
			  {
				  $product_counter = 0;
				  foreach($refund_products as $_product){
			  ?>
				<tr style='background:#d4efdf'>
                  <td> (<?php echo ++$product_counter;?>) <?php echo $_product['refund_pro_name']?> (<?php echo $_product['refund_pro_sku']?>)
                   </td>
                  <td data-th="Amount : "><?php echo $_product['refund_pro_price']?> <?php echo $refund_for_currency?></td>
                  <td data-th="Refund : "><?php echo $_product['refund_pro_refund']?> <?php echo $refund_for_currency?></td>
                <tr>
              <?php
				  }
			  }
			  else
			  {
			  $netRefundAmount = 0;
			  $product_counter = 0;
				if(isset($refund_products) && count($refund_products)){
				foreach($refund_products as $_product){
					$isProExist = isset($refundProducts[$_product['product_id']]) ? true : false;
					if($isProExist)
					$rProduct = $refundProducts[$_product['product_id']];
					$netRefundAmount+= $isProExist ? $rProduct['refund_pro_refund_price'] : $_product['product_price'];
			  ?>
                <tr <?php echo $isProExist && ($rProduct['refund_pro_refund_price']) ? "style='background:#d4efdf'":""?>>
                  <td> (<?php echo ++$product_counter;?>) <?php echo $_product['product_name']?> (<?php echo $_product['product_sku']?>)
                    <?php 
				echo $isProExist && ($rProduct['refund_pro_refund_price'])? "<span class='badge badge-success'>Refunding</span>":""
				?></td>
                  <td data-th="Amount : "><?php echo $_product['product_price']?> <?php echo $refund_for_currency?></td>
                  <td data-th="Refund : "><div class="form-group mb-0">
                      <div class="input-group">
                        <input class="form-control refund_item_price" id="refund_item_price_<?php echo $_product['product_id']?>" name="refund_item_price[<?php echo $_product['product_id']?>]" max="<?php echo $_product['product_price']?>" min="0" step="0.01" placeholder="Enter refund amount" type="number" value="<?php echo $isProExist ? $rProduct['refund_pro_refund_price'] : $_product['product_price']?>">
                        <span class="input-group-addon"> <?php echo $refund_for_currency?> </span> </div>
                    </div></td>
                <tr>
                  <?php }						
					}
			  }?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="1" class="text-right">
				 
					<?php 					
					if($method != 'VIEW'){
					Modal::load(array('Recharge'));
					$recharge = new Recharge();						
					$myBalance = $recharge->getUserCurrentBalance(getLoginId());
					?>
				  <i class="icon-wallet"></i><strong> Wallet Balance :</strong><span class="fa-2x text-primary">&pound;<?php echo $myBalance;?></span>GBP
				  </td>
                  <td colspan="2" class="text-right"><strong>Net refund amount :</strong> <span class="fa-2x text-success" id="net_refund_amount"><?php echo number_format(($refund_id == 0 || $refund_process_by == 0) ? $netRefundAmount:$refund_amount, 2)?></span> <?php echo $refund_for_currency?>
				<?php }
				if($method != 'VIEW' ){?>
				<input type="hidden" id="refund_amount" name="refund_amount" value="<?=($refund_id == 0 || $refund_process_by == 0) ? $netRefundAmount:$refund_amount?>"  />
				<?php }?>
				</td>
                </tr>
				<?php if($method != 'VIEW' && ($method == 'FINALISE')){?>
				<tr>
					<td class="text-right" colspan="3">
					<?php
					$spendAmount = $recharge->getTodaysRefundAmount(getLoginId());
					if($spendAmount)
						echo "You have already spend &pound;$spendAmount today.<br/>";
					if($myBalance < $refund_amount)
						echo "<strong class=\"text-danger\">Insufficient Balance on Wallet. Please request to recharge</strong><br/>";
					echo "<span class=\"text-primary\">Maximum of <b>&pound;".(PER_DAY_REFUND_LIMIT - $spendAmount)."</b> can be processed today.</span>";
					?>
					</td>
				</tr>
				<?php }?>
              </tfoot>
            </table>
          </div>
		  </div>
		  </div>
        </div>
        <!--/row-->
        <?php if($refund_id && $refund_process_by == 0){?>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="d-flex align-items-center bg-warning justify-content-start p-2">
                        <div class="pl-0 justify-content-start">
						<i class="fa fa-warning fa-lg m-t-2"></i>
						</div>
                        <div class="px-2 justify-content-start">
                          <div class="text-value-sm">Warning: Completing this Step <b>"Process Refund"</b>, further Net Refund amount can't change.</div>
                        </div>
                      </div>
           
          </div>
        </div>
        <?php }?>
        
        <?php if($method == 'FINALISE'){?>
        <?php if($refund_completed_by == 0){?>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="form-group">
              <label> Close this Request by ?</label>
              <br/>              
                  <div class="col-xs-6">
                    <label class="switch switch-icon switch-pill switch-success">
                    <input class="switch-input refund_status" id="refund_status_complete" value="1" name="refund_status" checked="checked" type="radio">
                    <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label><strong> Complete</strong>
                  </div>
              	  <div class="col-xs-6">
                    <label class="switch switch-icon switch-pill switch-danger">
                    <input class="switch-input" id="refund_status_cancell" value="3" name="refund_status" type="radio">
                    <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label> <strong>Cancel</strong>
                  </div>
                  
            </div>
          </div>
        </div>        
        <?php }?>
        <?php }?>
      </div>
    </div>
    <?php if($refund_id == 0 || $refund_completed_by == 0){?>
    <div class="card-footer">
      <div class="row">
        <div class="col-sm-12">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh"></i> Reset</button>
          &nbsp;
          <button type="button" id="btn_refund_submit" onClick="confirmMessage.Set('Are you sure to save refund...?', 'proceedSaveRefund');" class="btn btn-success mt-0 submission_handler_btn"><i class="fa fa-check-circle"></i> <?php echo $btnText;?></button>
        </div>
      </div>
    </div>
    <input type="hidden" id="action" name="action" value="<?=$action;?>"  />
    <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
    <input type="hidden" id="refund_id" name="refund_id" value="<?=isset($refund_id)?$refund_id:"0";?>"  />
    <input type="hidden" id="refund_customer_id" name="refund_customer_id" value="<?=isset($refund_to_id)?$refund_to_id:"0";?>"  />
    <input type="hidden" id="refund_type_code" name="refund_type_code" value="<?=$refund_type_code;?>"  />
    <input type="hidden" id="refund_type_id" name="refund_type_id" value="<?=$refund_type_id;?>"  />
    <?php }else{?>
    <div class="card-footer">
      <div class="row">
        <div class="col-sm-12">
          <p class="text-center pt-1">This Refund was <?php echo $refund_status == 1 ? "Completed":"Cancelled"?> by <?php echo $completor_name?></p>
        </div>
      </div>
    </div>
    <?php }?>
    </div>
  </form>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	
	$('.refund_item_price').on("change", function(){
		var refundAmount = 0;
		$('.refund_item_price').each(function(){
			console.log($(this).val());
			refundAmount = Math.round((refundAmount + parseFloat(($(this).val() == "NaN" || $(this).val() == "") ? 0:$(this).val())+ Number.EPSILON) * 100) / 100;
		});
		$("#net_refund_amount").text(refundAmount);
		$("#refund_amount").val(refundAmount);
	});
	
});
function proceedSaveRefund()
{
	var formFields	=	"refund_comments";	
	if(validateFields(formFields))
	{
		var data={
			action	:	$("#action").val()
		};
		data = $.extend(data, $("#addrefund").serializeFormJSON());		
	
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Saving Refund...",0);
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{					
					Redirect(arr[2]);
				}
				message(arr[1],3000);
			}
		});	
	}	
}

</script> 