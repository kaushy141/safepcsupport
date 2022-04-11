<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        </strong> <small>Form</small> </div>
      <form id="addproduct" name="addproduct" enctype="multipart/form-data">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="user_fname">Product Name<sup>*</sup></label>
                <input class="form-control" id="product_name" name="product_name" maxlength="250" placeholder="Enter product name" type="text" value="<?=isset($product_name)?$product_name:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="user_fname">Quantity<sup>*</sup></label>
                <input class="form-control" id="product_quantity" name="product_quantity" maxlength="5" placeholder="Enter product quantity" min="0" max="5000" type="number" value="<?=isset($product_quantity)?$product_quantity:"";?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="product_price">Price<sup>*</sup></label>
                    <input class="form-control" id="product_price" name="product_price" maxlength="10" placeholder="Enter product price" type="text" value="<?=isset($product_price)?$product_price:"";?>">
                  </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="product_inc_vat">Inc.Vat</label><br/>
                    <label class="switch switch-icon switch-pill switch-success pull-right">
                      <input class="switch-input" id="product_inc_vat" value="1" name="product_inc_vat"  checked="checked" type="checkbox">
                      <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="product_hardware_id">Product Type<sup>*</sup></label>
                <select id="product_hardware_id" name="product_hardware_id" class="form-control" size="1">
                  <?php
                $HardwareType = new HardwareType(0);
				echo $HardwareType->getOptions(isset($product_hardware_id)?$product_hardware_id:"0");
				?>
                </select>
              </div>
            </div>
          </div>
          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
          <button type="button" onClick="addProduct();" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?>
          </span> Product </button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addproduct";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="product_id" name="product_id" value="<?=isset($product_id)?$product_id:"0";?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
function addProduct()
{
	var formFields	=	"product_name, product_quantity, product_price, product_hardware_id";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()		
		};
		
		data = $.extend(data, $("#addproduct").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{
					$("#product_name").val("");
					$("#product_price").val("");
					$("#product_quantity").val("");
					$("#product_hardware_id").val(0);
				}
				message(arr[1]);
			}
		})	
	}
}

function checkExistingProduct()
{
	if(validateFields("product_name"))
	{
		var data={
			action			:	'sales/isproductexist',
			product_name	:	$("#product_name").val()							
		};	 
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
					message("process|Checking Products...", 0);
					dissableSubmission();
				},		
				success:function(output){
					enableSubmission(output);
					var arr	=	JSON.parse(output);			
				message(arr[1], 0);
			}
		});	
	}
}
</script> 