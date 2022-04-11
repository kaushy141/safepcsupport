<style type="text/css">
.ticket_box {
	padding: 8px 10px;
	padding-right: 0px;
	display: inline-block;
	margin: 2px;
	margin-right: 6px;
	font-size: 14px;
	color: #555;
	border: 1px dashed #bbb;
}

.ticket_box:hover {
	box-shadow: 1px 1px 3px #bbb;
	border-color: #F77A77;
}
.ticket_text {
	color:#646464;
	font-weight:600;
	float: left;
	display: inline-block;
	position: relative;
	padding-right: 5px;
}
.ticket_text:hover {
	color: #000;
}
.ticket_remove {
	float: right;
	display: inline-block;
	position: relative;
	cursor: pointer;
	font-weight: bold;
	padding: 8px 8px;
	color: #ccc;
	margin-top: -8px;
	margin-bottom: -8px;
	background: #f1f1f1;
	margin-left: 1px;
}
.ticket_remove:hover {
	background-color: #FF3737;
	color: #FFF;
}
.ticket_edit{
	float: right;
	display: inline-block;
	position: relative;
	cursor: pointer;
	font-weight: bold;
	padding: 8px 8px;
	color: #ccc;
	margin-top: -8px;
	margin-bottom: -8px;
	background: #f1f1f1;
	margin-left: 1px;
}
.ticket_edit:hover {
	background-color: #2BAE70;
	color: #FFF;
} 
.drag{
	float: left;
	display: inline-block;
	position: relative;
	cursor: move;
	padding: 8px 8px;
	color: #ccc;
	margin-top: -8px;
	margin-bottom: -8px;
	background: #f1f1f1;
	margin-left: -8px;
	margin-right: 5px;
}
.drag:hover {
	background-color: #7A7A7A;
	color: #FFF;
} 
.ticket_box_list {
	padding: 8px 8px;
	padding-right: 0px;
	display: inline-block;
	margin: 0px 2px;
	margin-right: 6px;
	font-size: 14px;
	color: #555;
	border: 1px solid #ddd;
	width: 100%;
	background: #f3f3f3;
	min-height: 38px;
}
.ticket_box_list:hover {
	box-shadow: 1px 1px 3px #bbb;
	border-color: #bbb;
}
.process_item_container{ padding-left:0px;}
.data-text{ color:#AAA;}
</style>
<div class="row" id="collection_form_container">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-block">
        <div class="row" id="collection_item_id_boxb">
          <div class="col-md-12">
            <div class="row">
              <div class="col-sm-6 col-xs-12 col-md-4">
                  <div class="form-group">
                <label for="option_value">Attribute Options<sup>*</sup></label>
				<div class="input-group">
                <input class="form-control" id="option_value" name="option_value" maxlength="50" placeholder="Enter Option value" type="text" value="">
				<span class="input-group-addon">
				<span id="addoptionbtn" title="Add Option" class="fa fa-plus fa-lg" style="cursor:pointer;"></span>
			  </span>
              </div>
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
    <form id="managepalletitems" name="managepalletitems">
      <div class="card">
        <div class="card-header card-defualt"><i class="fa fa-newspaper-o"></i> <?php echo $formHeading;?></div>
        <div class="card-block">
          <div class="row">
            <div class="col-md-12">            
              <ul class="process_item_container" id="process_item_id_boxb">  
				<?php
				$options = $wcitem->getAttributesOptions($attribute_id);
				if($options){
					foreach($options as $_option){
						echo "<div class='ticket_box'><div class='ticket_text'>".$_option."</div><div title='Remove item' class='ticket_remove'>X</div><div class='clear'></div><input type='hidden' class='optionvalue' value='".htmlspecialchars($_option)."' name='options[]'></div>";
					}
				}
				?>
              </ul>
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
        <input type="hidden" id="attribute_id" name="attribute_id" value="<?=isset($attribute_id)?$attribute_id:"0";?>"  />
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">

$(document).ready(function(e) {

    $("#addoptionbtn").on("click", function(){
		var text = $("#option_value").val();
		var addoption = true;
		$(".optionvalue").each(function(){
			if($(this).val().toLowerCase().trim() == text.toLowerCase().trim()){
				addoption = false;
				return;
			}
		});
		if(addoption){
			$("#process_item_id_boxb").append(getBarcodeLebel(text));
			$("#option_value").val('');
		}
		else
			message('warning|Option is already exist');
	});	
	
	$(document).on("click", ".ticket_remove", function(e){
		$ele = $(this).parent();
		$ele.hide(500, function(){
			$ele.remove();
			managebtnopenitemform();
		});
	});

});

function getBarcodeLebel(text){
	return "<div class='ticket_box'><div class='ticket_text'>"+text+"</div><div title='Remove item' class='ticket_remove'>X</div><div class='clear'></div><input type='hidden' class='optionvalue' value='"+text+"' name='options[]'></div>";
}

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