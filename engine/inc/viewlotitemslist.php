<div class="row">
  <div class="col-sm-12">
    <div class="card">
    <div class="card-header"><i class="fa fa-align-justify"></i> <strong>
        <?=$formHeading?>
        </strong>        
      </div>
      <div class="card-block">
	  <form id="lotproducts" name="lotproducts">  
        <div class="row">          
          <div class="col-xs-12 col-md-6">
            <div class="form-group">
              <label for="lot_name">Lot name<sup>*</sup></label>
              <input class="form-control" id="lot_name" name="lot_name" maxlength="50" placeholder="Enter lot name" type="text" value="<?=isset($lot_name)?$lot_name:"";?>">
            </div>
          </div> 
		  <?php if(isset($lot_id) && $lot_id > 0){?>
		  <div class="col-xs-12 col-md-6">
            <div class="form-group">
				<label for="lot_name"> </label>
              <a type="button" href="javascript:loadThisLotToCurrent();" class="btn btn-info btn-block">Add Product on this Lot</a>              
            </div>
          </div> 
		  <?php }?>
            <div class="col-md-12">
            <table id="tablelotproduct" class="table table-bordered" width="100%" style="width:100%">
                <thead>
                    <tr>
						<th>Store</th>
                        <th>SKU</th>
						<th>Batch</th>
                        <th>Age</th>
                        <th>Name</th>
                        <th>Sr.No.</th>
                        <th width="30px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>            
          </div>
        </div>
		</form>
      </div>
	  <div class="card-footer">
          <button type="button" id="btn_invpoice_submit" onClick="confirmMessage.Set('Are you sure to save this lot...?', 'savelot');" class="btn btn-success submission_handler_btn"><i class="fa fa-check m-t-2"></i> Save Lot </button>
         
		  <?php if(isset($lot_id) && $lot_id > 0){?>
		  <div class="btn-group dropup">
			<button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action<span class="sr-only"></span></button>
			<div class="dropdown-menu dropdown-menu-right">
				
				<a class="dropdown-item redirect" href="<?php echo $app->basePath("viewlotitems/".$lot_id); ?>"><i class="fa fa-refresh fa-lg m-t-2"></i> Reload</a>
				<a class="dropdown-item" href="#" data-toggle="modal" data-target="#appModal" onclick="openChatLogForm('<?php echo $sales_invoice_id;?>|L', '<?php echo $lot_id;?> Log Report')"><i class="fa fa-comments-o fa-lg m-t-2"></i> Log Comments</a>			
			
			<?php if($lot_id > 0){?>
				<a class="dropdown-item" target="new" href="<?php echo DOC::LOTEXCEL($lot_id)?>"><i class="fa fa fa-file-excel-o fa-lg m-t-2 text-success"></i> Export to Excel</a>
			<?php }?>
			
			</div>
		</div>
		  <?php }?>		
        </div>
        <input type="hidden" id="action" name="action" value="<?=isset($action)?$action:"savelot";?>"  />
        <input type="hidden" id="formcode" name="formcode" value="<?=App::getFormcode();?>"  />
        <input type="hidden" id="lot_id" name="lot_id" value="<?=isset($lot_id)?$lot_id:"0";?>"  />
    </div>
  </div>
</div>
<script type="text/javascript">
var oTable = $('#tablelotproduct').DataTable(); 

function addNewRow(record){
	var row = $('#tablelotproduct').dataTable().fnAddData( [
							record.origin + '<br/>' +record.location+ '<br/>' +(record.stock == '1' ? '<span class="badge badge-success">In stock</span>':'<span class="badge badge-danger">Out of stock</span>'), 
							record.sku, 
							record.batch_code, 
							record.age, 
							record.name, 
							record.srno, 
							'<a class="btn btn-default redirect" href="'+record.url+'"><i class="fa fa-search"></i></a><a data-id="'+record.id+'" class="removelotproduct btn btn-danger"><i class="fa fa-trash"></i></a><input class="productitem" type="hidden" name="product[]" value="'+record.keyname+'">'
						]);	
	var n = $('#tablelotproduct').dataTable().fnSettings().aoData[ row[0] ].nTr;
	n.id = 'row_'+record.id;
}
function getLotItems(){
	
	oTable .clear().draw();	
	var data={
		action		:	'product/getlotitems',
		lot_id		:	$("#lot_id").val()
	};		
	$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
			message("process|Loading lot products...", 0);
		},
		success:function(output){
			var arr	=	JSON.parse(output);	
			if(arr[0] == 200)
			{	
				window.localStorage.setItem(window.location.href, JSON.stringify(arr[2]));	
				for(var i=0; i<arr[2].length; i++)
				{
					var product = arr[2][i];
					addNewRow({
						'id': product['id'], 
						'keyname': product['keyname'], 
						'origin': product['origin'], 
						'stock': product['stock'], 
						'location': product['location'], 
						'sku'  	: product['sku'], 
						'age'  	: product['age'], 
						'name' 	: product['name'], 
						'srno' 	: product['srno'], 
						'url' 	: product['url'],
						'batch_code' 	: product['batch_code']
					});
				}
			}
			message(arr[1],500);
		}
	});
	
	return false;
}

function savelot(){
	if(validateFields("lot_name"))
	{
		var products = [];
		var myTable = $('#tablelotproduct').dataTable();		
		$("input.productitem", myTable.fnGetNodes()).each(function(){
			products.push($(this).val());
		});
		
		
		var data={
					action	:	$("#action").val(),
					products:	products,
					lot_id	:	$("#lot_id").val(),
					lot_name:	$("#lot_name").val()
				};
		
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
					$("#lot_id").val(arr[2]);
					$(".viewcurrentlot").attr("href", sitePath + "viewlotitems");
					resetLot();
					setTimeout(function(){
					Redirect('viewlotitems/'+arr[2]);	
					}, 2000);
				}
				message(arr[1]);
			}
		});	
	}
}

function loadThisLotToCurrent(){
	var data={
					action	:	'product/loadthislottocurrent',
					lot_id	:	$("#lot_id").val()
				};
		
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
					updateLot(arr[2]);	
					$(".viewcurrentlot").attr("href", sitePath + "viewlotitems/"+arr[3]);
				}
				message(arr[1]);
			}
		});
}


getLotItems();
</script> 