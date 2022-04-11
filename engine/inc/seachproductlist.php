<div class="row">
  <div class="col-sm-12">
    <div class="card">
    <div class="card-header"><i class="fa fa-align-justify"></i> <strong>
        <?=$formHeading?>
        </strong>        
      </div>
      <div class="card-block">
        <div class="row">
          <div class="col-md-12">
            <form name="addbarcode" id="addbarcode" onsubmit="return searchProductSku();">
              <div class="row">
                <div class="col-sm-6 col-xs-12 col-md-4">
                  <div class="form-group">                    
                    <input class="form-control input_text_upper" id="product_sku" name="product_sku" maxlength="2000" placeholder="Search SKU/SR.No./Code" type="text" value="">
                  </div>
                </div>
                <div class="col-sm-6 col-xs-12 col-md-8">
                  <button onclick="searchProductSku()" type="button" class="btn btn-outline-success"><i class="fa fa-search"></i> Search</button>
                  <button onclick="resetSearchList()" type="button" class="btn btn-default"> Reset</button>
				  <button onclick="addAllProductonCurrentLot()" type="button" class="btn btn-outline-info"><i class="fa fa-plus"></i> Add All Product on Current Lot</button>
                </div>
              </div>
            </form>
          </div>
          
            <div class="col-md-12">
            <table id="tableproductsearch" class="table table-bordered" width="100%" style="width:100%">
                <thead>
                    <tr>
                        <th><input class="select-checkboxall" type="checkbox" value="1"></th>
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
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
var table = $('#tableproductsearch').DataTable({columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }],
    select: {
        style: 'os',
        selector: 'td:first-child'
    },}); 

var storedata = null;
if(app_filter_state == 1 && window.localStorage.getItem(window.location.href)){
 storedata = JSON.parse(window.localStorage.getItem(window.location.href));	
}
$(document).ready(function(e) {
	if(storedata != null)
	{
		for(var i=0; i<storedata.length; i++){
			var product = storedata[i];
			addNewRow({
						'id': product['id'],
						'origin': product['origin'],
						'stock': product['stock'],
						'location' : product['location'],
						'sku'  	: product['sku'], 
						'age'  	: product['age'], 
						'name' 	: product['name'], 
						'srno' 	: product['srno'], 
						'url' 	: product['url'],
						'batch_code' 	: product['batch_code'],
						'lot_code' 	: product['lot_code'],
						'label' 	: product['label']
					});
		}
	}
});

$(document).on("change", "th input.select-checkboxall", function() {	
	var myTable = $('#tableproductsearch').dataTable();	
	if ($(this).hasClass("checked")) {
		$(this).removeClass('checked');
		$("input.select-checkbox", myTable.fnGetNodes()).each(function(){
			$(this).prop('checked', false);
		});
	} else {
		$(this).addClass('checked');
		$("input.select-checkbox", myTable.fnGetNodes()).each(function(){
			$(this).prop('checked', true);
		});
	}
});

function resetSearchList(){
	if(app_filter_state == 1 && window.localStorage.getItem(window.location.href)){
	 window.localStorage.setItem(window.location.href, null);	
	}
	Redirect('searchproduct');
}
function addNewRow(record){
	var chatCodearr = record.lot_code.split('|');
	record.chat_code = record.id+'|'+chatCodearr[0];
	
	$('#tableproductsearch').dataTable().fnAddData( [
							'<input class="select-checkbox checked" type="checkbox" name="chkitem[]" value="'+record.lot_code+'">', 
							record.origin + '<br/>' +record.location+ '<br/>' +(record.stock == '1' ? '<span class="badge badge-success">In stock</span>':'<span class="badge badge-danger">Out of stock</span>'), 
							record.sku, 
							record.batch_code, 
							record.age, 
							record.name, 
							record.srno, 
							"<div class=\"btn-group\"><button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fa fa-navicon fa-lg m-t-2\"></i> <span class=\"sr-only\">Toggle Dropdown</span></button><div class=\"dropdown-menu dropdown-menu-right\"><a class=\"dropdown-item redirect\" href=\""+record.url+"\"><i class=\"fa fa-share-square-o fa-lg m-t-2\"></i> View</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('"+record.chat_code+"', 'Log Report')\"><i class=\"fa fa-comments-o fa-lg m-t-2\"></i> Comment Log</a><a class=\"dropdown-item addtolot\" data-lot=\""+record.lot_code+"\" href=\"#\"><i class=\"fa fa-plus fa-lg m-t-2 text-success\"></i> Add to lot</a><a class=\"dropdown-item\" href=\"javascript:newWindow('"+record.label+"')\"><i class=\"fa fa-barcode fa-lg m-t-2\"></i> Print Barcode</a></div></div>"
						]);	
}
function searchProductSku(){
	var product_sku = $("#product_sku").val().trim().toUpperCase();
	if(product_sku != "")
	{
		table .clear().draw();	
		var data={
			action		:	'product/searchproductbysku',
			product_sku	: 	product_sku
		};		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
				beforeSend: function(){
				message("process|Searching products for <b>"+product_sku+"</b>...", 0);
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
							'origin': product['origin'], 
							'stock': product['stock'], 
							'location': product['location'], 
							'sku'  	: product['sku'], 
							'age'  	: product['age'], 
							'name' 	: product['name'], 
							'srno' 	: product['srno'], 
							'url' 	: product['url'],
							'batch_code' 	: product['batch_code'],
							'lot_code' 	: product['lot_code'],
							'label' 	: product['label']
						});
					}
				}
				message(arr[1],500);
			}
		});
	}
	else
		$("#product_sku").focus();
	return false;
}

function addAllProductonCurrentLot(){
	var products = [];
		var myTable = $('#tableproductsearch').dataTable();		
		$("input.select-checkbox:checked", myTable.fnGetNodes()).each(function(){
			products.push($(this).val());
		});
		
		
		var data={
					action	:	'system/addsearchproductstolot',
					products:	products
				};
		
		$.ajax({type:'POST', data:data, url:sitePath +'ajax.php', 		
			beforeSend: function(){
				message("process|Connecting...");
				dissableSubmission();
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0] == 200){				
					updateLot(arr[2]);
				}
				else if(arr[0] == 300){	
					updateLot(arr[2], true);
				}
				message(arr[1],1000);
			}
		});	
}
</script> 