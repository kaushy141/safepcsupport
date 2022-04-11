<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <strong><?=$formHeading?></strong> <small>Form</small> </div>
      <form id="addwcitem" name="addwcitem" enctype="multipart/form-data">
      <div class="card-block">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wci_name">Collection Item Name<sup>*</sup></label>
              <input class="form-control" id="wci_name" name="wci_name" maxlength="100" placeholder="Enter Collection Iten name" type="text" value="<?=isset($wci_name)?$wci_name:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wci_ewc_code">Item EWC Code<sup>*</sup></label>
              <input class="form-control" id="wci_ewc_code" name="wci_ewc_code" maxlength="50" placeholder="Enter Item EWC Code" type="text" value="<?=isset($wci_ewc_code)?$wci_ewc_code:"";?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wci_type_id">Item Hazard Type<sup>*</sup></label>
              <select id="wci_type_id" name="wci_type_id" class="form-control" size="1">
                <?php
                $WcItemType = new WcItemType(0);
				echo $WcItemType->getOptions(isset($wci_type_id)?$wci_type_id:"0");
				?>
              </select>
            </div>
          </div>
        
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wci_concentration">Chemical Concentration<sup>*</sup></label>
              <input class="form-control" id="wci_concentration" name="wci_concentration" maxlength="50" placeholder="Enter Chemical Concentration" onkeyup="getDropdown(this, 'WcItemType<=>wci_concentration')" type="text" value="<?=isset($wci_concentration)?$wci_concentration:"";?>">              
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wci_physical_form">Item Physical Form<sup>*</sup></label>
              <select id="wci_physical_form" name="wci_physical_form" class="form-control" size="1">
              	<option <?php echo  (isset($wci_physical_form) && $wci_physical_form =="0" )? "selected": "" ?> value="0"> - Select Item Physical Form - </option>
                <option <?php echo  (isset($wci_physical_form) && $wci_physical_form =="Solid" )? "selected": "" ?> value="Solid"> Solid </option>
                <option <?php echo  (isset($wci_physical_form) && $wci_physical_form =="Liquid" )? "selected": "" ?> value="Liquid"> Liquid </option>
                <option <?php echo  (isset($wci_physical_form) && $wci_physical_form =="Gas" )? "selected": "" ?> value="Gas"> Gas </option>
                <option <?php echo  (isset($wci_physical_form) && $wci_physical_form =="Mixed" )? "selected": "" ?> value="Mixed"> Mixed </option>
              </select>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wci_hazard_codes">Hazard Codes<sup>*</sup></label>
              <input class="form-control" id="wci_hazard_codes" name="wci_hazard_codes" maxlength="50" placeholder="Enter Hazard Codes"  onkeyup="getDropdown(this, 'WcItem<=>wci_hazard_codes')" type="text" value="<?=isset($wci_hazard_codes)?$wci_hazard_codes:"";?>">              
            </div>
          </div>
          
                
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wci_container_type">Deafult Container Used<sup></sup></label>
              <input class="form-control" id="wci_container_type" name="wci_container_type" maxlength="100" placeholder="Enter Deafult Container Used on Packing" type="text" value="<?=isset($wci_container_type)?$wci_container_type:"";?>">              
            </div>
          </div>
          
          <div class="col-sm-4">
             <div class="form-group row">
              <label class="col-xs-12 col-sm-12 col-md-12 form-control-label">Item Type</label>
              <div class="col-xs-12 col-sm-12 col-md-12">
                <label class="radio-inline" for="inline-radio1">
                  <input id="inline-radio1" name="wci_serialize_type" <?=(isset($wci_serialize_type) && $wci_serialize_type == SERIALIZED) ? "checked":""; ?> class="wci_serialize_type" value="<?=SERIALIZED?>" type="radio">
                  <?=SERIALIZED?> </label>
                &nbsp;  &nbsp;
                <label class="radio-inline" for="inline-radio2">
                  <input id="inline-radio2" name="wci_serialize_type" <?=(isset($wci_serialize_type) &&  $wci_serialize_type == UNSERIALIZED) ? "checked":""; ?> class="wci_serialize_type" value="<?=UNSERIALIZED?>" type="radio">
                  <?=UNSERIALIZED?> </label>
              </div>
            </div>
          </div>
          
          <div class="col-sm-4">
             <div class="form-group">
             <ul class="pl-0">
             	<li><b><?=SERIALIZED?> -</b> <i>Those Items which have unique serial number like Computer, Printer, Hard Drive etc.</i></li>
                <li><b><?=UNSERIALIZED?> -</b> <i>Those Items which don't have unique serial number like Cable, mouse Pad, Papers etc.</i></li>
             </ul>
             </div>
          </div>
          
        </div>
        
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="wci_chemical_component">Chemical Component<sup>*</sup></label>
              <div id="locationField">
              <textarea id="wci_chemical_component" name="wci_chemical_component" rows="2" class="form-control" placeholder="Enter Chemical Component"><?=isset($wci_chemical_component)?$wci_chemical_component:"";?></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="wci_description">Description About Item<sup>*</sup></label>
              <div id="locationField">
              <textarea id="wci_description" name="wci_description" rows="3" class="form-control" placeholder="Enter Description About Item"><?=isset($wci_description)?$wci_description:"";?></textarea>
              </div>
            </div>
          </div>
        </div>       
      </div>
      <div class="card-footer">
      <button type="reset" class="btn btn-danger"><i class="fa fa-refresh fa-lg m-t-2"></i> Reset</button>
      <button type="button" onClick="addWcItem();" class="btn btn-success submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2 "></i> Save</button>
      
    </div>
    	<input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"addwcitem";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="wci_id" name="wci_id" value="<?=isset($wci_id)?$wci_id:"0";?>"  />
      </form>
    </div>
    
  </div>
</div>
<!--/col--> 

<!--/col-->
</div>
<script type="text/javascript">
function addWcItem()
{
	var formFields	=	"wci_name, wci_ewc_code, wci_type_id, wci_physical_form, wci_hazard_codes, wci_chemical_component, wci_description";
	
	if(validateFields(formFields))
	{		
		var data={
			action	:	$("#action").val()		
		};
		
		data = $.extend(data, $("#addwcitem").serializeFormJSON());
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
					$("#wci_id").val(arr[2]);
					$("#action").val("collection/updatewcitem");
				}
				message(arr[1]);
			}
		})	
	}
}
</script> 

<div class="row">
<div class="col-lg-12">

    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> Wastage Collection Item List </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tbldatatable" class="table table-striped">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Item Type</th>
              <th>HZ Code</th>
              <th>EWC Code</th>
              <th>Add Date</th>
              <th>Status</th>
              <th>Option</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			$WcItem = new WcItem(0);
			echo $WcItem->getRecords();
			?>
          </tbody>
        </table>
        
      </div>
    </div>
    </div>
  </div>
  
<script type="text/javascript">
$(document).ready(function(e) {
    $("#tbldatatable").dataTable({"iDisplayLength": 10, "aLengthMenu": [5,10,25,50], "sPaginationType": "full_numbers", "aoColumns": [ { "bSortable": false }, null, null, null, null], "order": [[ 0, 'desc' ]],
		columnDefs: [{ targets: [0], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 1,2,3,4,] }]});
});
</script>