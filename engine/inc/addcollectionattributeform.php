<div class="row">
  <div class="col-sm-6">
    <div class="card">
	<form name="addatribute" id="addattibute">
      <div class="card-header"> <strong>Add New Attribute </strong> <small>Form</small> </div>
      <div class="card-block">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">Attribute Name</label>
              <input class="form-control" id="attribute_name" value="<?php if(isset($attribute_name)) echo $attribute_name;?>" name="attribute_name" maxlength="100" placeholder="Enter attribute name" type="text">
            </div>
          </div>
        </div>
        <!--/row-->
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="ccnumber">Type</label>
              <select <?php echo (isset($attribute_id) && $attribute_id > 0) ? 'disabled' : "";?> id="attribute_element_type" name="attribute_element_type" class="form-control" size="1">
				<option value=""> -Select Attribute type - </option>
				<option value="atext" <?php echo (isset($attribute_element_type) && $attribute_element_type == 'atext') ? "selected" : "";?>>Text Field</option>
				<option value="bdropdown" <?php echo (isset($attribute_element_type) && $attribute_element_type == 'bdropdown') ? "selected" : "";?>>Dropdown Combo</option>
				<option value="cboolean" <?php echo (isset($attribute_element_type) && $attribute_element_type == 'cboolean') ? "selected" : "";?>>Yes / No</select>
            </div>
          </div>
        </div>
        <div class="form-group row">
          
          <div class="col-md-12">
            <div class="form-group">
            <label for="attribute_is_filtrer"><i class="fa fa-stop-circle-o fa-lg m-t-2"></i> &nbsp;Attribute is filter</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="attribute_is_filtrer" value="1" <?php echo (isset($attribute_is_filtrer) && $attribute_is_filtrer == 1) ? "checked" : "";?> name="attribute_is_filtrer" type="checkbox" >
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
          </div>
		  
		  <div class="col-md-12">
            <div class="form-group">
            <label for="attribute_status"><i class="fa fa-stop-circle-o fa-lg m-t-2"></i> &nbsp;Attribute is active</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="attribute_status" value="1" <?php echo (isset($attribute_status) && $attribute_status == 1) ? "checked" : "";?> name="attribute_status" type="checkbox" checked="">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
      	<input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
		<input type="hidden" id="attribute_id" name="attribute_id" value="<?php echo isset($attribute_id) ? $attribute_id : 0;?>"  />
        <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>
        <button type="button" onClick="addAttribute();" class="btn btn-sm btn-primary submission_handler_btn"><i class="fa fa-dot-circle-o"></i> Submit</button>
		<?php
		if(isset($attribute_id) && $attribute_id > 0){
		?>
		<a href="<?php echo $app->basePath('addcollectionattribute')?>" class="btn btn-sm btn-default"><i class="fa fa-share-square-o"></i> Add new Attribute</a>
		<?php
		}
		?>
      </div>
	  </form>
    </div>
  </div>
  <!--/col-->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Attribute name </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			$attribute = new Attribute(0);
			echo $attribute->getRecords();
			?>
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">

function addAttribute()
{
	if(validateFields("attribute_name","attribute_element_type"))
	{
		var data={
					action	:	'collection/saveattribute'				
				};
		data = $.extend(data, $("#addattibute").serializeFormJSON());
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
				}
				message(arr[1]);
			}
		})	
	}
}

function statusAction(field)
{
	var data={
			action	:	"collection/updateattributestatus",
			status	:	Number(field.checked),
			idvalue		:	field.value				
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...",0);
				dissableSubmission();
			},		
			success:function(output){
				enableSubmission(output); 
				var arr	=	JSON.parse(output);
				message(arr[1],2000);
			}
		})	
}

$("#tbldatatable").dataTable();

</script> 
