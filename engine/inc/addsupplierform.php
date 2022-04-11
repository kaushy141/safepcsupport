<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?php echo $formHeading; ?></strong> <small>Form</small> </div>
      <form id="addsupplierrecordform" name="addsupplierrecordform">
        <div class="card-block">
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="supplier_name">Supplier Name<sup>*</sup></label>
                <input class="form-control" id="supplier_name" name="supplier_name" maxlength="100" placeholder="Enter supplier name" type="text" value="<?=isset($supplier_name)?$supplier_name:"";?>">
              </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
              <label for="supplier_type_id">Supplier Type<sup>*</sup></label>
              <select id="supplier_type_id" name="supplier_type_id" class="form-control" size="1">
                <?php
                $supplierType = new SupplierType(0);
				echo $supplierType->getOptions(isset($supplier_type_id)?$supplier_type_id : 0);
				?>
              </select>
            </div>
          </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="supplier_email">Supplier email<sup>*</sup></label>
                <input class="form-control" id="supplier_email" name="supplier_email" maxlength="50" placeholder="Enter supplier email" type="email" value="<?=isset($supplier_email)?$supplier_email:"";?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="supplier_contact">Supplier contact<sup>*</sup></label>
                <input class="form-control" id="supplier_contact" name="supplier_contact" maxlength="100" placeholder="Enter supplier contact" type="text" value="<?=isset($supplier_contact)?$supplier_contact:"";?>">
              </div>
            </div>
			
			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="supplier_company_name">Company Name<sup>*</sup></label>
                <input class="form-control" id="supplier_company_name" name="supplier_company_name" maxlength="200" placeholder="Enter supplier company name" type="text" value="<?=isset($supplier_company_name)?$supplier_company_name:"";?>">
              </div>
            </div>
			
			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="supplier_company_number">Company Number<sup></sup></label>
                <input class="form-control" id="supplier_company_number" name="supplier_company_number" maxlength="50" placeholder="Enter supplier company number" type="text" value="<?=isset($supplier_company_number)?$supplier_company_number:"";?>">
              </div>
            </div>
			
			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="supplier_website">Suplier Website<sup></sup></label>
                <input class="form-control" id="supplier_website" name="supplier_website" maxlength="200" placeholder="Enter supplier website" type="text" value="<?=isset($supplier_website)?$supplier_website:"";?>">
              </div>
            </div>
			
			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="supplier_vat_no">Suplier Vat No.<sup></sup></label>
                <input class="form-control" id="supplier_vat_no" name="supplier_vat_no" maxlength="50" placeholder="Enter supplier vat number" type="text" value="<?=isset($supplier_vat_no)?$supplier_vat_no:"";?>">
              </div>
            </div>
			
			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="supplier_skype_id">Suplier Skype Id<sup></sup></label>
                <input class="form-control" id="supplier_skype_id" name="supplier_skype_id" maxlength="50" placeholder="Enter supplier Skype id" type="text" value="<?=isset($supplier_skype_id)?$supplier_skype_id:"";?>">
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
            <label for="supplier_doc_file"><i class="fa fa-file-pdf-o fa-lg m-t-2 text-danger"></i> &nbsp; Upload File Document</label>
              <input type="file" name="supplier_doc_file" id="supplier_doc_file" class="btn btn-default btn-block save" data-action="save-png" onchange="filesUpload('supplier_doc_file');" />
              <input type="hidden" name="supplier_doc_file_path" id="supplier_doc_file_path" value="" />
              <span class="file_uploader" id="file_uploader"></span>
            </div>
          </div>
		  
		  			<div class="col-xs-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="supplier_supply_process">Suplier process type<sup></sup></label>
                <select id="supplier_supply_process" name="supplier_supply_process" class="form-control" size="1">
                <?php
				$supplierProcess = Supplier::getProcessArray();
				$html="<option value=\"\"> - Select Supplier process - </option>";	
		
				foreach($supplierProcess as $supplier_supply_process){
					$html.="<option value=\"$supplier_supply_process\" ".((isset($supplier_supply_process) && $_supplier_supply_process ==$supplier_supply_process)?"selected":"")." >".$supplier_supply_process."</option>";	
				}
				echo $html;
				?>
              </select>
              </div>
            </div>
			
            </div>
          <div class="row">
			<div class="col-sm-12">
			  <div class="form-group">
                <label for="supplier_bank_details">Supplier Bank details<sup>*</sup></label>
                <textarea id="supplier_bank_details" name="supplier_bank_details" rows="3" maxlength="500" class="form-control" placeholder="Enter supplier bank details..."><?=isset($supplier_bank_details)?$supplier_bank_details:"";?>
</textarea>
              </div>
             
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label for="supplier_address">Supplier address<sup>*</sup></label>
                <input class="form-control" id="supplier_address" name="supplier_address" maxlength="500" placeholder="Enter supplier address" type="text" value="<?=isset($supplier_address)?$supplier_address:"";?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="supplier_description">Supplier description<sup>*</sup></label>
                <textarea id="supplier_description" name="supplier_description" rows="3" maxlength="1000" class="form-control" placeholder="Enter supplier description..."><?=isset($supplier_description)?$supplier_description:"";?>
</textarea>
              </div>
            </div>
          </div>
          <!--/row-->
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
          <button type="button" onClick="confirmMessage.Set('Are you sure to save supplier Information...?', 'addSupplierInfo')" class="btn btn-sm btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i>
          <?=$btnText?>
          </span></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addsupplierrecord";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="supplier_id" name="supplier_id" value="<?=isset($supplier_id)?$supplier_id:"0";?>"  />
      </form>
    </div>
  </div>
  <!--/col--> 
  
  <!--/col--> 
</div>
<script type="text/javascript">
function supplier_doc_file_path_callback(path){
	$("#supplier_doc_file_path").val(path);
}
function addSupplierInfo()
{
	if(validateFields("supplier_name, supplier_email, supplier_contact, supplier_address, supplier_description"))
	{
		var data={
					action	:	$("#action").val()						
				};
		data = $.extend(data, $("#addsupplierrecordform").serializeFormJSON());
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
					$("#addsupplierrecordform").reset;			
				}
				message(arr[1]);
			}
		})	
	}
}

</script>

