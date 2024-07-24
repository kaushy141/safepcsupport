<div class="row">

	<?php if (isAdmin()) { ?>
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
								$customer = new Customer();
								$field = 'customer_type_id';
								?>
								<label class="col-md-12 col-form-label">
									<h5 class="filterheading">Customer Type <i data-id="<?php echo $field; ?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5>
								</label>
								<div id="label_<?php echo $field; ?>" class="col-md-12 col-form-label" style="max-height:300px; overflow-y:scroll;">
									<?php
									$records = $customer->getCustomerTypeFilter();
									if (count($records)) {
										$i = 0;
										foreach ($records as $item) {
											$i++;
									?>
											<div class="form-check checkbox filtercheckbox">
												<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['customer_type_name']); ?>" name="<?php echo $field ?>" id="<?php echo $field . '_' . $i ?>" type="checkbox" value="<?php echo htmlspecialchars($item['customer_type_id']); ?>">
												<label class="form-check-label" for="<?php echo $field . '_' . $i ?>"> <?php echo htmlspecialchars($item['customer_type_name']); ?> (<?php echo $item['record']; ?>)</label>
											</div>
									<?php
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>

					<div style="padding: 5px">
						<div class="block-fluid table-sorting clearfix">
							<div class="row">
								<?php
								$customer = new Customer();
								$field = 'customer_address_country';
								?>
								<label class="col-md-12 col-form-label">
									<h5 class="filterheading">Country <i data-id="<?php echo $field; ?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5>
								</label>
								<div id="label_<?php echo $field; ?>" class="col-md-12 col-form-label" style="max-height:300px; overflow-y:scroll;">
									<?php
									$records = $customer->getCustomerCountryFilter();
									if (count($records)) {
										$i = 0;
										foreach ($records as $item) {
											$i++;
									?>
											<div class="form-check checkbox filtercheckbox">
												<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['customer_address_country']); ?>" name="<?php echo $field ?>" id="<?php echo $field . '_' . $i ?>" type="checkbox" value="<?php echo htmlspecialchars($item['customer_address_country']); ?>">
												<label class="form-check-label" for="<?php echo $field . '_' . $i ?>"> <?php echo htmlspecialchars($item['customer_address_country']); ?> (<?php echo $item['record']; ?>)</label>
											</div>
									<?php
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>

					<div style="padding: 5px">
						<div class="block-fluid table-sorting clearfix">
							<div class="row">
								<?php
								$customer = new Customer();
								$field = 'customer_address_administrative_area';
								?>
								<label class="col-md-12 col-form-label">
									<h5 class="filterheading">State <i data-id="<?php echo $field; ?>" class="filtercontroller pull-right fa fa-chevron-down"></i></h5>
								</label>
								<div id="label_<?php echo $field; ?>" class="col-md-12 col-form-label" style="max-height:300px; overflow-y:scroll;">
									<?php
									$records = $customer->getCustomerAdministrativeAreaFilter();
									if (count($records)) {
										$i = 0;
										foreach ($records as $item) {
											$i++;
									?>
											<div class="form-check checkbox filtercheckbox">
												<input class="form-check-input ml-0" data-label="<?php echo htmlspecialchars($item['customer_address_administrative_area']); ?>" name="<?php echo $field ?>" id="<?php echo $field . '_' . $i ?>" type="checkbox" value="<?php echo htmlspecialchars($item['customer_address_administrative_area']); ?>">
												<label class="form-check-label" for="<?php echo $field . '_' . $i ?>"> <?php echo htmlspecialchars($item['customer_address_administrative_area']); ?> (<?php echo $item['record']; ?>)</label>
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
	<?php } ?>
	<div class="col-lg-<?php echo isAdmin() ? 9 : 12; ?>">
		<div class="card">
			<div class="card-header">
				<i class="fa fa-align-justify"></i> Customer List
				<div class="card-actions">
					<a data-title="Customer PDF Report" title="Generate PDF Report" href="javascript:newWindow('<?= DOC::CUSTEXCELLIST() ?>');"><i class="fa fa-file-excel-o font-2xl d-block m-t-2"></i></a>
					<a data-title="Customer PDF Report" title="Generate PDF Report" href="javascript:newWindow('<?= DOC::CUSTLIST() ?>');"><i class="icon-printer icons font-2xl d-block m-t-2"></i></a>
				</div>
			</div>
			<div class="block-fluid table-sorting clearfix">
				<table id="tblSortable" class="table table-striped">
					<thead>
						<tr>
							<th>Pic</th>
							<th>Name</th>
							<th>Contact</th>
							<th>Type</th>
							<th>Status</th>
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
	function statusAction(field) {
		var data = {
			action: "customer/updatecustomerstatus",
			status: Number(field.checked),
			idvalue: field.value
		};
		$.ajax({
			type: 'POST',
			data: data,
			url: sitePath + 'ajax.php',
			beforeSend: function() {
				message("process|Connecting...", 0);
			},
			success: function(output) {
				var arr = JSON.parse(output);
				message(arr[1], 2000);
			}
		});
	}


	var data = {

	};
	var coumnter = 0;
	var datatable;
	var formdata = {};
	if (app_filter_state == 1 && window.localStorage.getItem(window.location.href)) {
		formdata = JSON.parse(window.localStorage.getItem(window.location.href));
		console.log(formdata);
		$('.form-check-input').each(function() {
			console.log($(this).attr('name'));
			if (formdata.hasOwnProperty($(this).attr('name'))) {
				var filterVal = formdata[$(this).attr('name')];
				//console.log(filterVal);
				if (filterVal.indexOf($(this).val()) !== -1) {
					$(this).attr("checked", true);
					$("#appliedfilter").append(getFilterLabel($(this)));
				}
			}
		})
	}
	var postdata = {
		action: "viewcustomerlist",
		filter: formdata
	}
	$(document).ready(function() {

		$('.form-check-input').on('change', function() {
			formdata = $("#tablefilter").serializeFormJSON();

			postdata = {
				action: "viewcustomerlist",
				filter: formdata
			}

			if (typeof datatable != 'undefined') {
				datatable.clear().draw();
			}
			if ($(this).is(":checked"))
				$("#appliedfilter").append(getFilterLabel($(this)));
			else
				$("#tag_" + $(this).attr('id')).remove();
			addfiltersearch();
		});

		$('.filtercontroller').on('click', function() {
			var id = $(this).attr('data-id');
			if ($(this).hasClass('fa-chevron-down')) {
				$("#label_" + id).show();
			} else
				$("#label_" + id).hide();
			$(this).toggleClass('fa-chevron-down fa-chevron-up');
		})
		$("div.col-form-label").each(function() {
			$(this).hide();
		})
		$(document).on("click", ".filtercloser", function(e) {
			var id = $(this).attr('data-id');
			$("#" + id).prop("checked", false);
			$("#tag_" + id).remove();
			$("#" + id).trigger("change");
			addfiltersearch();
		})
	});


	datatable = $('#tblSortable').DataTable({
		"processing": true,
		"serverSide": true,
		"bStateSave": true,
		"ajax": {
			"url": "<?= $app->basePath("server_processing.php") ?>",
			"type": "POST",
			"data": function(d) {
				$.extend(d, postdata);
			}
		},
		'fnCreatedRow': function(nRow, aData, iDataIndex) {
			$(nRow).find('td').not(":last").attr('onclick', "Redirect('" + aData[6] + "')"); // or whatever you choose to set as the id
		},
		//"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[9]);},
		"order": [
			[0, 'desc']
		],
		columnDefs: [{
				targets: [5],
				orderable: false
			},
			{
				className: "hidden-xs hidden-md hidden-sm visible-lg",
				"targets": [2, 3]
			},
			{
				className: "word_break",
				"targets": []
			}
		]
	});
</script>