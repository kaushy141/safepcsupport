<div class="row" id="collection_form_container">
  <div class="col-sm-12">
    <form id="managepalletitems" name="managepalletitems">
      <div class="card">
        <div class="card-block">
          <div class="row" id="collection_item_id_boxb">
            <div class="col-md-12">
              <div class="row">
                <div class="col-sm-6 col-xs-12 col-md-4">
                  <div class="form-group">
                    <label for="attribute_relation_item_id">Select Item<sup>*</sup></label>
                    <div class="input-group">
                      <select id="attribute_relation_item_id" name="attribute_relation_item_id" class="form-control" size="1">
                        <option value='0'> -- Select Item -- </option>
                        <?php
					$wcItem = new WcItem(0);
					$record = $wcItem->getOptionList(0);
					if(count($record)){
						foreach($record as $item){
							echo "<option value='$item[wci_id]'>".$item['wci_name']."</option>";
						}
					}
					?>
                      </select>
                      </span> </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12" id="bar_code_container"> </div>
              </div>
            </div>
          </div>
          <!--/row--> 
        </div>
      </div>
      <div class="card">
        <div class="card-header card-defualt"><i class="fa fa-newspaper-o"></i> <?php echo $formHeading;?></div>
        <div class="card-block">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered table-strip">
                <thead>
                  <tr>
                    <th>Attribute</th>
                    <th class="text-center">Add</th>
                    <th class="text-center">Export</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				$attribute = new Attribute();
				$Attributeoptions = $attribute->getAttributesList();
				if($Attributeoptions){
					foreach($Attributeoptions as $field){
						?>
						<tr>
							<td><?php echo $field['attribute_name'];?></td>
							<td class="text-center"><label class="switch switch-icon switch-pill switch-success">
              <input class="switch-input attributes" id="attribute_<?php echo $field['attribute_id']?>" value="<?php echo $field['attribute_id']?>"  name="attributes" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></td>
							<td class="text-center"><label class="switch switch-icon switch-pill switch-info">
              <input class="switch-input attributes_exp" id="attribute_exp_<?php echo $field['attribute_id']?>" value="<?php echo $field['attribute_id']?>"  name="attributesexp" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></td>
						</tr>
					<?php	
					}
				}
				?>
                </tbody>
              </table>
            </div>
          </div>
          <!--/row--> 
        </div>
      </div>
      <div class="card">
        <div class="card-footer">
          <div class="row">
            <div class="col-sm-12">
              <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg"></i> Reset</button>
              &nbsp;
              <button type="button" onClick="confirmMessage.Set('Are you sure to saev this pallet items...?', 'saveattributeoption');" class="btn btn-success mt-0"><i class="fa fa-check-circle fa-lg"></i>
              <?=$btnText?>
              </button>
            </div>
          </div>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"collection/saveattributeoption";?>"  />
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">

$(document).ready(function(e) {

    $("#attribute_relation_item_id").on("change", function(){
		$("input.attributes").each(function(){
			$(this).prop('checked', false);
		});
		$("input.attributes_exp").each(function(){
			$(this).prop('checked', false);
		});
		var data = {
			action : 'collection/getitemattributes',
			attribute_relation_item_id : $("#attribute_relation_item_id option:selected").val()			
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...");
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				if(arr[0] == 200){		
					var attributes = arr[2];
					for(var i=0; i < attributes.length; i++)
						$("#attribute_" + attributes[i]).prop('checked', true);
					
					var exportable = arr[3];
					$.each(exportable, function(key, value){
						$("#attribute_exp_" + key).prop('checked', value == 1 ? true : false);
					});
				}
				message(arr[1],500);
			}
		})		
	});
	
});


function saveattributeoption(){
	
	var data = $("#managepalletitems").serializeFormJSON();	
	console.log(data);
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Connecting...");
		},		
		success:function(output){
			var arr	=	JSON.parse(output);	
			if(arr[0] == 200){			
				
			}
			message(arr[1],3000);
		}
	})		
}

</script> 