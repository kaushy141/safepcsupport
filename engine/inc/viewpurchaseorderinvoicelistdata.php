<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> <?=$formHeading?>      
      </div>	  
       <div class="block-fluid table-sorting clearfix">
        <table id="<?php echo $TABEL_ID;?>" class="table table-striped">
          <thead>
            <tr>
              <th>Code</th>
              <th>Dated</th>
			  <th>DueOn</th>
			  <th>Supplier</th>
              <th>Amount</th>
              <th>Discount</th>
              <th>Currency</th>
			  <th>Created</th>
              <th>Action</th>
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
var data = {							
		   };
	var coumnter = 0;
	var datatable;
	var formdata = {};
	if(app_filter_state == 1 && window.localStorage.getItem(window.location.href)){
		formdata = JSON.parse(window.localStorage.getItem(window.location.href));
		$('.form-check-input').each(function(){
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
			action	:	"viewpurchaseorderinvoicelist",
			filter  :   formdata
		}
$(document).ready(function() {   
	
	
	$('.form-check-input').on('change', function(){
		formdata = $("#tablefilter").serializeFormJSON();		
		
		postdata = {
			action	:	"viewpurchaseorderinvoicelist",
			filter  :   formdata
		}
		//console.log(postdata);
		
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


datatable = $('#<?php echo $TABEL_ID;?>').DataTable( {
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
	"order": [[ 0, 'desc' ]],
	"columnDefs": [
					{ targets: [8], orderable: false },
					{ className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 2,5,6,7 ] },
				  ],
} );

</script> 
