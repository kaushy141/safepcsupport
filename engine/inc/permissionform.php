<style type="text/css">
.parent_option{ color:#000 !important;}
.child_option{ color:#30728B !important; }
</style>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong>
        <?=$formHeading?>
        <div class="card-actions">
            <a title="Add New Module" href="javascript:addModule(0);"><i class="fa fa-plus-square   font-2xl d-block m-t-2"></i></a>
        </div>
        </strong> <small>Form</small> </div>
      <form id="addpermission" name="addpermission" enctype="multipart/form-data">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-4">
            
              <div class="form-group">
                <label for="user_type_id">User Type<sup>*</sup></label>
                <select id="user_type_id" name="user_type_id" class="form-control" size="1" onchange="loadUserList(this.value);">
                <?php
                $UserType = new UserType(0);
				echo $UserType->getOptions(0);
				?>
              </select>
              </div>
            </div>
            
            
            
            <div class="col-sm-4">
              <div class="form-group">
                <label for="employee_id">User <sup></sup></label>
                <select id="employee_id" name="employee_id" class="form-control" size="1">
              </select>
              </div>
            </div>
            
          </div>
          
          <div class="row">
          <div class="col-sm-12">
          <ul style="list-style:none;">
          <?php 
		  $navbar = new Navbar();
	  	  $navbarList = $navbar->getFullModuleList();
		  
		  $i=0;
		  foreach($navbarList as $nav)
		  {
			 if(isset($nav['info']) && !empty($nav['info']))
			 {			 			 
				 if(isset($nav['child']) && !empty($nav['child']))
				 {				 
					 echo "<li class=\"nav-item nav-dropdown\">";
					 
					 echo "<label for=\"module_id_".$nav['info']['module_id']."\">
					 			<input type=\"checkbox\"  class=\"chk-module chk-module-parent\" id=\"module_id_".$nav['info']['module_id']."\" name=\"module_id\" value=\"".$nav['info']['module_id']."\" size=\"1\" /> 
								&nbsp; <i class=\"".$nav['info']['module_icon']."\" style=\"color:".getColor($i++)."\"></i> 
								".$nav['info']['module_name']."
						  </label> &nbsp; <a href=\"javascript:addModule(".$nav['info']['module_id'].")\"  class=\"editanchor\" data-id=\"".$nav['info']['module_id']."\"><i class=\"fa fa-edit\"></i></a>";
					 echo "<ul style=\"list-style:none;\">";
					 
					 foreach($nav['child'] as $navChild)
					 {
						 echo "<li class=\"nav-item\">
						 		<label for=\"module_id_".$navChild['module_id']."\">
						 			<input type=\"checkbox\"  class=\"chk-module chk-module-child\" data-parent=\"".$nav['info']['module_id']."\" id=\"module_id_".$navChild['module_id']."\" name=\"module_id\" value=\"".$navChild['module_id']."\" size=\"1\" /> 
									&nbsp;<i class=\"".$navChild['module_icon']."\" ></i> 
									".$navChild['module_name']."
								</label> &nbsp; <a href=\"javascript:addModule(".$navChild['module_id'].")\" class=\"editanchor\" data-id=\"".$navChild['module_id']."\"><i class=\"fa fa-edit\"></i></a>
							  </li>";
					 }
					 
					 echo "</ul>";
					 echo "</li>";
				 }
				 else
					echo "<li class=\"nav-item\">
							<label for=\"module_id_".$nav['info']['module_id']."\">
								<input type=\"checkbox\" class=\"chk-module chk-module-parent\" id=\"module_id_".$nav['info']['module_id']."\" name=\"module_id\" value=\"".$nav['info']['module_id']."\" size=\"1\" /> 
								&nbsp; <i class=\"".$nav['info']['module_icon']."\" style=\"color:".getColor($i++)."\"></i> 
								".$nav['info']['module_name']." 
							</label> &nbsp; <a href=\"javascript:addModule(".$nav['info']['module_id'].")\" class=\"editanchor\" data-id=\"".$nav['info']['module_id']."\"><i class=\"fa fa-edit\"></i></a>
						 </li>";
				 
			 } 
		  }	  
		  ?>
          </ul>
          </div>
          </div>
          <!--/row--> 
          
        </div>
        <div class="card-footer">
          <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
          <button type="button" onClick="upgradePermission();" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> <span id="btn_action_name">
          <?=$btnText?></button>
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
var PREV_SELECTED;
$(document).ready(function(e) {
    $("#employee_id").on("change", function(){
		var TOTAL_ACCESS = [];
		if($("#employee_id option:selected").attr("data-check")!="")
		{
			var NEW_PERMISSION = $("#employee_id option:selected").attr("data-check").split(",");
			TOTAL_ACCESS = $.merge(NEW_PERMISSION, PREV_SELECTED);
		}
		else
			TOTAL_ACCESS = PREV_SELECTED;
		checkPermission($.unique(TOTAL_ACCESS));		
	});
	
	
	$(".chk-module-child").on("change", function(){
		if($(this).is(":checked") ) 
		$("#module_id_"+$(this).attr("data-parent")).prop("checked", true);
		else
		{
			if($("input[data-parent='"+$(this).attr('data-parent')+"']:checked").length == 0)
			$("#module_id_" + $(this).attr('data-parent')).prop('checked', false);
		}
	});
	
	$(".chk-module-parent").on("change", function(){
		var isChecked = $(this).is(":checked"); 
		var parent_id = $(this).val(); 
		$("[data-parent='"+parent_id+"']").each(function(index, element) { 
            $(this).prop("checked", isChecked);
        });
	});
	
	/*$(".anchoredit").on("click", function(){
		editModule($(this).attr('data-id'));
	});*/
});
function loadUserList(user_type_id){
	$("#employee_id").html('');
	if(user_type_id != 0)
	{		
		var data={
			action			:	"system/getuserbytype",
			formcode		: 	$("#formcode").val(),
			user_type_id	: 	user_type_id
		};	
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
					$("#employee_id").html(arr[2]);
					PREV_SELECTED = arr[3].split(",");
					checkPermission(PREV_SELECTED);				
				}
				message(arr[1], 5);
			}
		})
	}	
}


function checkPermission(SELECTED){	
	$(".chk-module").each(function(index, element) {
		if(SELECTED.indexOf($(this).val()) > -1)
			$(this).prop('checked', true);
		else
			$(this).prop('checked', false);
	});	
}
function upgradePermission()
{
	var formFields	=	"user_type_id";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()	
		};
		
		data = $.extend(data, $("#addpermission").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);					
				message(arr[1]);
			}
		})	
	}
}



function addModule(module_id)
{
	setPopup(module_id, module_id == 0 ? "Create module":"Update module");
	var bodyHtml = '<div class="col-md-12">';
	
	bodyHtml +='<div class="row">';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="module_name">Module name<sup>*</sup></label> <div class="input-group"> <input type="text" class="form-control" id="module_name" name="module_name" placeholder="Enter module name" value="" /></div></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="module_key">Module key<sup>*</sup></label> <div class="input-group"> <input type="text" class="form-control" id="module_key" name="module_key" placeholder="Enter module key" value="" /></div></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="module_icon">Module Icon<sup>*</sup></label> <div class="input-group"> <input type="text" class="form-control" id="module_icon" name="module_icon" placeholder="Enter module icon" value="" /></div></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="module_parent">Module Parent<sup>*</sup></label> <select id="module_parent" name="module_parent" class="form-control" size="1"> <?php $navbar = new Navbar(0); echo $navbar->getParentOptions(); ?> </select> </div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="module_sort_order">Module Order<sup>*</sup></label> <div class="input-group"> <input type="number" min="0" class="form-control" id="module_sort_order" name="module_sort_order" placeholder="Enter module order" value="" /></div></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="module_color">Module Color<sup>*</sup></label> <div class="input-group"> <input type="text" class="form-control" id="module_color" name="module_color" placeholder="Enter module color #XXXXXX" value="" /></div></div></div>';		
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="module_is_customer_access"><i class="fa fa-user fa-lg m-t-2"></i> &nbsp; is customer access.. ?</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="module_is_customer_access" value="1" name="module_is_customer_access" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div>';
	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="module_is_navbar"><i class="fa fa-bars fa-lg m-t-2"></i> &nbsp; is navbar.. ?</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="module_is_navbar" value="1" name="module_is_navbar" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div>';

	bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="module_is_topbar"><i class="fa fa-book fa-lg m-t-2"></i> &nbsp; is topbar.. ?</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="module_is_topbar" value="1" name="module_is_topbar" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div>';
	
		bodyHtml +='<div class="col-md-6"><div class="form-group"><label for="module_status"><i class="fa fa-suitcase fa-lg m-t-2"></i> &nbsp; Module Status</label><label class="switch switch-icon switch-pill switch-success pull-right"><input class="switch-input" id="module_status" value="1" name="module_status" type="checkbox"><span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label></div></div>';
	
	bodyHtml +='</div>';
	
	bodyHtml +='</div>';
	modal.Body(bodyHtml);
	modal.Footer('<input type="hidden" name="module_id" id="module_id" value=""/><button type="reset" class="btn btn-default" >Reset</button><button type="button" id="popupsubmit" onclick="saveModule();" class="btn btn-success" >'+(module_id == 0 ? "Add Module":"Update Module")+'</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
	modal.Show();
	$("#module_id").val(module_id);
	if(module_id != 0)
	{		
		var data={
			action			:	"system/editmodule",
			formcode		: 	$("#formcode").val(),
			module_id		: 	module_id
		};	
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
					var moduleData = arr[2];
					$("#module_id").val(moduleData['module_id']);
					$("#module_name").val(moduleData['module_name']);
					$("#module_key").val(moduleData['module_key']);
					$("#module_icon").val(moduleData['module_icon']);
					$("#module_parent").val(moduleData['module_parent']);
					$("#module_sort_order").val(moduleData['module_sort_order']);
					$("#module_status").val(moduleData['module_status']);
					$("#module_color").val(moduleData['module_color']);
					$("#module_is_customer_access").prop("checked", moduleData['module_is_customer_access']==1?true:false);
					$("#module_is_navbar").prop("checked", moduleData['module_is_navbar']==1?true:false);
					$("#module_is_topbar").prop("checked", moduleData['module_is_topbar']==1?true:false);
					$("#module_status").prop("checked", moduleData['module_status']==1?true:false);				
				}
				message(arr[1], 5);
			}
		})
	}
}

function saveModule(){
	var formFields	=	"module_name, module_key, module_icon, module_sort_order";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	'system/savemodule'	
		};
		
		data = $.extend(data, $("#modalform").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);					
				message(arr[1]);
			}
		})	
	}
}
</script> 