<style type="text/css">
.word_wrap_break_word {
    word-wrap: break-word;
	word-break:break-all;
}
</style>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-block">
        <div class="row" id="collection_item_id_boxb">
          <div class="col-md-12">
            <div class="row">
            <form id="addcredentials" name="addcredentials">
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                  <label for="credentials_weburl">Web url<sup>*</sup></label>
                  <input class="form-control" id="credentials_weburl" name="credentials_weburl" maxlength="100" placeholder="Enter credentials weburl" type="url" value="<?=isset($credentials_weburl)?$credentials_weburl:"";?>">
                </div>
              </div>
              
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                  <label for="credentials_username">Username<sup>*</sup></label>
                  <input class="form-control" id="credentials_username" name="credentials_username" maxlength="50" placeholder="Enter username" type="text" value="<?=isset($credentials_username)?$credentials_username:"";?>">
                </div>
              </div>
              
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                  <label for="credentials_password">Password<sup>*</sup></label>
                  <input class="form-control" id="credentials_password" name="credentials_password" maxlength="50" placeholder="Enter password" type="text" value="<?=isset($credentials_password)?$credentials_password:"";?>">
                </div>
              </div>
              
              <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="form-group">
                  
                      <div class="col-xs-6">
                        <label class="switch switch-icon switch-pill switch-success">
                        <input class="switch-input credentials_scope" <?=(!isset($credentials_scope) || (isset($credentials_scope) && $credentials_scope == 'Private'))? "checked":"";?> id="credentials_scope_private" value="Private" name="credentials_scope" type="radio">
                        <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label><br/><strong>Private</strong>
                      </div>
                      <div class="col-xs-6">
                        <label class="switch switch-icon switch-pill switch-info">
                        <input class="switch-input credentials_scope" <?=(isset($credentials_scope) && $credentials_scope == 'Public')? "checked":"";?> id="credentials_scope_public" value="Public" name="credentials_scope" type="radio">
                        <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label><br/><strong>Public</strong>
                      </div>                      
                </div>
              </div>
              
              <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="form-group">
              <button onclick="resetCredentialForm()" type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
        <button type="button" onClick="confirmMessage.Set('Are you sure to save credentials information...?', 'saveCredentials');" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle-o"></i> &nbsp; <?=$btnText?></button>
        <input type="hidden" id="action" name="action" value="<?=$action?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="credentials_id" name="credentials_id" value="0"  />
        
        		</div>            
          	</div>
        	</form>
            </div>            
          </div>
        </div>
        <!--/row--> 
      </div>
    </div>
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Credentials list </div>
      <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>Web</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">User</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">Pass</th>
              <th>scope</th>
              <th class="hidden-xs hidden-md hidden-sm visible-lg">status</th>
              <th>action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
			if(isset($credentialslist) && count($credentialslist)){
				foreach($credentialslist as $_credentials)
				{
			?>
            <tr id="row_<?php echo $_credentials['credentials_id'];?>">
              <td><?php echo viewText($_credentials['credentials_weburl'])?><br/><span class="text-muted"><?php echo dateView($_credentials['credentials_last_update'], 'FULL')?></span></td>
              <td class="hidden-xs hidden-md hidden-sm visible-lg"><?php echo viewText($_credentials['credentials_username'])?></td>
              <td class="hidden-xs hidden-md hidden-sm visible-lg"><?php echo viewText($_credentials['credentials_password'])?></td>
              <td><?php echo viewText($_credentials['credentials_scope'])?></td>
              <td class="hidden-xs hidden-md hidden-sm visible-lg"><?php echo statusView($_credentials['credentials_id'], $_credentials['credentials_status'])?></td>
              <td><?php echo actionView(array(
			  									array("name"=>"Edit", "icon"=>"fa-edit", "url"=>"javascript:editCredentials($_credentials[credentials_id])", "class"=>""),
												array("name"=>"Delete", "icon"=>"fa-trash", "url"=>"javascript:deleteCredentials($_credentials[credentials_id])", "class"=>"")
											  )
										)?></td>
            </tr>
            <?php	
				}	
			}
			?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

$(document).ready(function(e) {
	
	$("#tbldatatable").DataTable({"columnDefs": [
    { className: "word_wrap_break_word", "targets": [ 0,1,2 ] }
  ]});
	
});

function resetCredentialForm(){
	$("#credentials_id").val(0);
}
function editCredentials(id){
	var trData = $("#row_"+id+" td").toArray();
	var ArrayData = [];
	
	$.each($("#row_"+id+" td"), function(index, value){
		ArrayData.push($(this).html().split('<br>')[0]);
	});
	console.log(ArrayData);
	$("#credentials_id").val(id);
	$("#credentials_weburl").val(ArrayData[0]);
	$("#credentials_username").val(ArrayData[1]);
	$("#credentials_password").val(ArrayData[2]);
	if(ArrayData[3] == 'Private')
		$("#credentials_scope_private").prop("checked", true);
	else
		$("#credentials_scope_public").prop("checked", true);
}

function deleteCredentials(id){
	if(confirm("Are you sure to delete credentials...?"))
	{
	var data={
			action	: "system/deletecredentials",
			credentials_id : id
		};
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...");
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				if(arr[0] == 200){	
					var table = $('#tbldatatable').DataTable();
					table.row("#row_"+id).remove().draw();
				}				
				message(arr[1],500);
			}
		});
	}
}

function saveCredentials(){
	var formFields	=	"credentials_weburl, credentials_username, credentials_password";
	
	if(validateFields(formFields))
	{		
		var data={
			action	: $("#action").val()
		};
		data = $.extend(data, $("#addcredentials").serializeFormJSON());
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...");
			},		
			success:function(output){
				var arr	=	JSON.parse(output);	
				if(arr[0] == 200){	
					var record = arr[2]	;
					var table = $('#tbldatatable').DataTable();
					if($("#credentials_id").val() > 0)
					{
						table.row("#row_"+$("#credentials_id").val()).remove().draw();
					}
					$("#credentials_id").val(record['credentials_id']);	
					 var rowNode = table.row.add( [record['credentials_weburl'], record['credentials_username'], record['credentials_password'], record['credentials_scope'], record['status'], record['action'] ] );
					 rowNode.node().id = "row_"+record['credentials_id'];
					 table.draw(false)
				}				
				message(arr[1],500);
			}
		});
	}
}
</script> 