<div class="row">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header">
				<strong>
					<?=$formHeading?>
				</strong> <small>Form</small> </div>
			<form id="addebaysalesimport" name="addebaysalesimport" enctype="multipart/form-data">
				<div class="card-block">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="user_image">Select Product .xslx File<sup>*</sup></label>
								<div class="input-group">
									<span class="input-group-addon" style="padding:0px" id="image_uploader"><i class="fa fa-file-excel-o fa-lg"></i></span>
									<input class="form-control" id="product_batch_file" name="product_batch_file" style="padding-bottom: 4px; padding-top: 4px;" maxlength="100" value="" type="file">
								</div>
							</div>
						</div>
						<div class="col-sm-12">
						  <div class="form-group">
							<label for="import_for_update"><i class="fa fa-meh-o fa-lg m-t-2"></i> Import for Update existing record ? </label>
							<label class="switch switch-icon switch-pill switch-success pull-right">
							  <input class="switch-input" id="import_for_update" value="1" name="import_for_update" type="checkbox">
							  <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
						  </div>
						</div>
						<div id="import_for_update_primary" class="col-sm-12" style="display:none;">
						  <div class="form-group">
							  <label for="primary_column_name">Select Primary colum<sup>*</sup></label>
							  <select id="primary_column_name" name="primary_column_name" class="form-control" size="1">
								<option value=""> -- Select Primary Column -- </option>
								<option value="Sku">SKU</option>
								<option value="Serial">SR. Number</option>
							  </select>
							</div>
						</div>

					</div>
				</div>
				<div class="card-footer">
					<button type="reset" class="btn btn-danger"><i class="fa fa-refresh m-t-2"></i> Reset</button>
					<button type="button" onClick="importBatchRecords('product_batch_file');" class="btn btn-primary submission_handler_btn"><i class="fa fa-check-circle fa-lg m-t-2"></i> Check Products</button>

				</div>
			</form>
		</div>

	</div>
	<div class="col-sm-6">
		<div id="csv_uploader" style="text-align:center; margin:20px auto; font-size:22px;"><i class="fa fa-file-excel-o fa-lg"></i>
		</div>
		<div id="csv_message" style="margin:20px auto; "></div>
	</div>
	
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header">XSLX File Coulumn name instruction</div>
			<form id="addebaysalesimport" name="addebaysalesimport" enctype="multipart/form-data">
				<div class="card-block">
					<div class="row">
						<div class="col-sm-12">
							<table class="table">
								<thead>
									<tr>
										<th>Column Number</th>
										<th>Column Name</th>
										<th>Description</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>01</td>
										<td>
											<p><code class="highlighter-rouge">Model</code>
											</p>
										</td>
										<td><span>Column name should be same. This is Required*. Make sure XSLX have value for this column in each row<br/>Format : <code>[TYPE][SPACE][PROCESSOR][SPACE][MODEL][SIZE]["][SPACE][SPEED][GHz][SPACE][RAM][GB][SPACE][SSD/HDD][GB/TB][SPACE][RELEASE]</code><br/>
										Value Example : <code>iMac Core i5 A1312 27" 2.7GHz 4GB 1TB Mid 2011</code></span>
										</td>
									</tr>
									<tr>
										<td>02</td>
										<td>
											<p><code class="highlighter-rouge">Type</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to N/A<br/>Value Example : <code>iMac</code> <code>Macbook</code> <code>N/A</code></span>
										</td>
									</tr>
									
									<tr>
										<td>03</td>
										<td>
											<p><code class="highlighter-rouge">Batchcode</code>
											</p>
										</td>
										<td><span>Column name should be same. This is Required*. Commonly Given on file<br/>Value Example : <code>BATCH10001</code> <code>BATCH10002</code> <code>BATCH10003</code></span>
										</td>
									</tr>
									
									<tr>
										<td>04</td>
										<td>
											<p><code class="highlighter-rouge">Serial</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to N/A</span>
										</td>
									</tr>
									<tr>
										<td>05</td>
										<td>
											<p><code class="highlighter-rouge">Order</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to 0</span>
										</td>
									</tr>
									<tr>
										<td>06</td>
										<td>
											<p><code class="highlighter-rouge">RegId</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to N/A</span>
										</td>
									</tr>
									<tr>
										<td>07</td>
										<td>
											<p><code class="highlighter-rouge">Condition</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to N/A<br/>Value Example : <code>Working</code> <code>N/A</code></span>
										</td>
									</tr>									
									<tr>
										<td>08</td>
										<td>
											<p><code class="highlighter-rouge">Drive</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to N/A<br/>Value Example : <code>HDD</code> <code>SSD</code> <code>N/A</code></span>
										</td>
									</tr>
									<tr>
										<td>09</td>
										<td>
											<p><code class="highlighter-rouge">Reason</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to N/A<br/>Value Example : <code>Screen glass chipped, Mac - Screen is discolored or has LCD burn</code></span>
										</td>
									</tr>
									<tr>
										<td>10</td>
										<td>
											<p><code class="highlighter-rouge">OSystem</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to N/A<br/>Value Example : <code>Sierra</code> <code>Mountain Lion</code> <code>N/A</code></span>
										</td>
									</tr>
									<tr>
										<td>11</td>
										<td>
											<p><code class="highlighter-rouge">Battery</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to N/A<br/>Value Example : <code>115</code> <code>210</code> <code>N/A</code></span>
										</td>
									</tr>
									<tr>
										<td>12</td>
										<td>
											<p><code class="highlighter-rouge">BatchType</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to N/A<br/>Value Example : <code>Faulty / Damaged</code> <code>N/A</code></span>
										</td>
									</tr>
									<tr>
										<td>13</td>
										<td>
											<p><code class="highlighter-rouge">Grade</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to N/A<br/>Value Example : <code>A</code> <code>B</code> <code>C</code> <code>A+</code> <code>B+</code></span>
										</td>
									</tr>
									<tr>
										<td>14</td>
										<td>
											<p><code class="highlighter-rouge">Instock</code>
											</p>
										</td>
										<td><span>Column name should be same. If order values is not available put all values to 0<br/>Value Example : <code>1</code> <code>0</code></span>
										</td>
									</tr>
									<tr>
										<td>15</td>
										<td>
											<p><code class="highlighter-rouge">Sku</code>
											</p>
										</td>
										<td><span>Column name should be exact same.<br/>Value Example : <code>MFS1001</code> <code>SPD00021</code></span>
										</td>
									</tr>
									<tr>
										<td>16</td>
										<td>
											<p><code class="highlighter-rouge">PartNumber</code>
											</p>
										</td>
										<td><span>Column name should be exact same.<br/>Value Example : <code>859758-254</code> <code>CKP0037-887-3546</code></span>
										</td>
									</tr>
									<tr>
										<td>17</td>
										<td>
											<p><code class="highlighter-rouge">Cost</code>
											</p>
										</td>
										<td><span>Column name should be exact same. Cost value currency will be GBP<br/>Value Example : <code>58</code> <code>72.99</code></span>
										</td>
									</tr>
									<tr>
										<td>18</td>
										<td>
											<p><code class="highlighter-rouge">StoreLoc</code>
											</p>
										</td>
										<td><span>Column name should be exact same. Store Location value will be String Type<br/>Available Values : <code>UK</code> <code>UAE</code></span>
										</td>
									</tr>
									<tr>
										<td>19</td>
										<td>
											<p><code class="highlighter-rouge">Verified</code>
											</p>
										</td>
										<td><span>Column name should be exact same. Product verified value will be integer Type<br/>Available Values : <code>1</code> <code>0</code></span>
										</td>
									</tr>
									<tr>
										<td>20</td>
										<td>
											<p><code class="highlighter-rouge">OnWay</code>
											</p>
										</td>
										<td><span>Column name should be exact same. Product on the way value will be integer Type<br/>Available Values : <code>1</code> <code>0</code></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--/col--> 

<!--/col-->
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#import_for_update").on("change", function(){
			if($(this).is(":checked"))
				$("#import_for_update_primary").show();
			else{
				$("#import_for_update_primary").hide();
				$("#primary_column_name").val('');
			}
		})
	});
	var original_csv_file_data = "<i class=\"fa fa-file-excel-o fa-lg\"></i>";
	var call_back_csv_upload_handler;
	var is_interval_csv_running = false;
	var is_csv_file_uploaded = false;

	function importBatchRecords( field_name ) {
		var file = _( field_name ).files[ 0 ];
		var formdata = new FormData();
		var formdata = new FormData();
		formdata.append( field_name, file );
		formdata.append( 'field_handler', field_name );
		formdata.append( 'import_for_update', $("#import_for_update").is(":checked") ? 1:0 );
		formdata.append( 'primary_column_name', $("#primary_column_name option:selected").val() );
		is_interval_csv_running = false;
		is_csv_file_uploaded = false;
		var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener( "progress", progressCSVHandler, false );
		ajax.addEventListener( "load", completeCSVHandler, false );
		ajax.addEventListener( "error", errorCSVHandler, false );
		ajax.addEventListener( "abort", abortCSVHandler, false );
		ajax.open( "POST", sitePath + "xlsxupload.php" );
		ajax.send( formdata );
		_('csv_message').innerHTML = '<center>Processing...</center>';
	}

	function progressCSVHandler( event ) {
		dissableSubmission();
		var percent = ( event.loaded / event.total ) * 100;
		_( "csv_uploader" ).innerHTML = Math.round( percent ) + ' % ';

		if ( is_csv_file_uploaded == false && Math.round( percent ) == 100 ) {
			is_csv_file_uploaded = true;
			call_back_upload_handler_function();
			_( 'ebay_csv_file' ).values = "";
		}

		if ( Math.round( percent ) == 100 )
			_( "csv_uploader" ).innerHTML = 'Processing... <i class="fa fa-refresh fa-lg m-t-2 fa-spin"></i>';
	}

	function call_back_upload_handler_function() {
		is_interval_csv_running = true;
		_( "csv_uploader" ).innerHTML = "Processing... <i class=\"fa fa-refresh fa-lg m-t-2 fa-spin\"></i>";
	}

	function completeCSVHandler( event ) {
		_( 'product_batch_file' ).value = '';
		enableSubmission( event.target.responseText );
		var arr = JSON.parse( event.target.responseText );
		if ( arr[ 1 ] ) {
			message( arr[ 1 ] );
			if ( arr[ 0 ] == 200 ){
				_( "csv_uploader" ).innerHTML = arr[ 2 ];
				_('csv_message').innerHTML = arr[ 3 ];
			}
			else
				_( "csv_uploader" ).innerHTML = original_csv_file_data;
		} else
			_( "csv_uploader" ).innerHTML = original_csv_file_data;
	}

	function errorCSVHandler( event ) {
		enableSubmission( event.target.responseText );
		_( "csv_uploader" ).innerHTML = original_csv_file_data;
		message( "danger|File Upload error. Please try gaian." );
	}

	function abortCSVHandler( event ) {
		enableSubmission( event.target.responseText );
		_( "csv_uploader" ).innerHTML = original_csv_file_data;
		message( "danger|File Upload aborted by user. Please try gaian." );
	}
	
	function proceedtoimport(key){
		var data = { 
						action	:	"product/proceedtobatchimport", 
						key : key 
				   };
		$.ajax({type:'POST', data:data, url:sitePath+'ajax.php', 		
				beforeSend: function(){
				message("process|Connecting...", 0);
				dissableSubmission();
			},		
			success:function(output){ 
				enableSubmission(output);
				var arr	=	JSON.parse(output);
				if(arr[0] == 200){
					_( "csv_uploader" ).innerHTML ='<i class="fa fa-check fa-2x text-success"></i>';
					_('csv_message').innerHTML = '<center>Product imported successfully</center>';
				}
				message(arr[1]);
			}
		})	
	}
</script>