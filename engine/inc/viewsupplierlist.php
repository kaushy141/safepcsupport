<div class="row">
<?php if(isAdmin()){?>
  <div class="col-lg-3">
    <div class="card">
      <form id="tablefilter" name="tablefilter">
        <div class="card-header"> <i class="fa fa-align-justify"></i> Filter <a id="resetfilteration" href="#" class="pull-right">Reset</a></div>
        <div class="block-fluid table-sorting clearfix">
          <div class="row">
            <div class="col-xs-12" id="appliedfilter"></div>
          </div>
        </div>
        <div style="padding: 5px">
          <div class="block-fluid table-sorting clearfix">
            <div class="row">
              <?php 
			$supplier = new Supplier();
			$field = 'supplier_type_id';
			?>
              <label class="col-md-12 col-form-label">
              <h5 class="filterheading">Supplier Type <i data-id="<?php echo $field;?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5>
              </label>
              <div id="label_<?php echo $field;?>" class="col-md-12 col-form-label">
                <?php
			$records = $supplier->getSupplierTypeFilter();
			if(count($records)){
				$i=0;
				foreach($records as $item){
					$i++;
				?>
                <div class="form-check checkbox filtercheckbox">
                  <input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['supplier_type_name']);?>" name="<?php echo $field?>" id="<?php echo $field.'_'.$i?>" type="checkbox" value="<?php echo htmlspecialchars($item['supplier_type_id']);?>">
                  <label class="form-check-label" for="<?php echo $field.'_'.$i?>"> <?php echo htmlspecialchars($item['supplier_type_name']);?> (<?php echo $item['record'];?>)</label>
                </div>
                <?php
				}
			}
			?>
              </div>
            </div>
          </div>
          
          
          
          
          
        </div>
      </form>
    </div>
  </div>
  <?php }?>
  <div class="col-lg-<?php echo isAdmin() ? 9 : 12;?>">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i>Supplier List 
      <div class="card-actions"> <a target="new" title="Download full list" class="dropdown-item" href="<?php echo DOC::SUPPLIERLIST()?>"> <i class="icon-printer icons m-t-2"></i> </a> </div>
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Contact</th>
              <th>Address</th>
              <th>Description</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">
function statusAction(field)
{
	var data={
			action	:	"company_resource/updatesupplierrecordstatus",
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

var data = {
								
		   };
	var coumnter = 0;
	var datatable;
	var formdata = {};
	if(app_filter_state == 1 && window.localStorage.getItem(window.location.href)){
		formdata = JSON.parse(window.localStorage.getItem(window.location.href));
		console.log(formdata);
		$('.form-check-input').each(function(){
			console.log($(this).attr('name'));
			if(formdata.hasOwnProperty($(this).attr('name'))){
				var filterVal = formdata[$(this).attr('name')];
				//console.log(filterVal);
				if(filterVal.indexOf($(this).val()) !== -1){
					$(this).attr("checked", true);					
					$("#appliedfilter").append(getFilterLabel($(this)));
				}
			}
		})
	}
var postdata = {
		action	:	"viewsupplierrecord",
		filter  :   formdata
	}
$(document).ready(function() {
	
	$('.form-check-input').on('change', function(){
		formdata = $("#tablefilter").serializeFormJSON();
		
		postdata = {
			action	:	"viewsupplierrecord",
			filter  :   formdata
		}
		
		if(typeof datatable != 'undefined'){
			datatable.clear().draw();
		}
		if($(this).is(":checked"))
			$("#appliedfilter").append(getFilterLabel($(this)));
		else
			$("#tag_"+$(this).attr('id')).remove();
		addfiltersearch();
	});
	
	$('.filtercontroller').on('click', function(){
		var id = $(this).attr('data-id');
		if($(this).hasClass('fa-chevron-down')){
			$("#label_"+id).show();
		}
		else
			$("#label_"+id).hide();
		$(this).toggleClass('fa-chevron-down fa-chevron-up');
	})
	$("div.col-form-label").each(function(){
		$(this).hide();
	})
	$(document).on("click", ".filtercloser", function(e){
		var id = $(this).attr('data-id');
		$("#"+id).prop("checked", false);
		$("#tag_"+id).remove();
		$("#"+id).trigger("change");
		addfiltersearch();
	})
} );


datatable = $('#tblSortable').DataTable( {
	"language": {
					  "emptyTable": "No Supplier record available"
				},
	"processing": true,
	"serverSide": true,
	"bStateSave": true,
	"ajax":  {
		"url": "<?=$app->basePath("server_processing.php")?>",
		"type": "POST",
		"data": function ( d ) {
			$.extend(d, postdata);
		}
	},
	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			$(nRow).attr("id",'row_' + aData[7]);			
			return nRow;
		},
		"order": [[ 0, 'asc' ]],
		columnDefs: [{ targets: [5,6], orderable: false },
		{ className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [1,3,4 ] },
		{ className: "word_break", "targets": screen.width <786 ? [ 2,4 ] : [] }]
    } );



/*
var data = {
				"action"	:	"viewsupplierrecord",				
		   };
$(document).ready(function() {
    $('#tblSortable').DataTable( {
		"language": {
					  "emptyTable": "No Supplier record available"
					},
        "processing": true,
        "serverSide": true,
        "ajax":  {
            "url": "<?=$app->basePath("server_processing.php")?>",
            "type": "POST",
			"data": data
        },
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			$(nRow).attr("id",'row_' + aData[7]);			
			return nRow;
		},
		"order": [[ 0, 'asc' ]],
		columnDefs: [{ targets: [5,6], orderable: false },
		{ className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [1,3,4 ] },
		{ className: "word_break", "targets": screen.width <786 ? [ 2,4 ] : [] }]
    } );
} );
*/
</script> 