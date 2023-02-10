<?php Modal::load(array('BuybackOrder')); ?>
<div class="row">
    <?php if (isAdmin()) { ?>
    <div class="col-lg-3">
        <div class="card">
            <form id="tablefilter" name="tablefilter">
                <div class="card-header"> <i class="fa fa-align-justify"></i> Filter <a id="resetfilteration" href="#"
                        class="pull-right">Reset</a></div>
                <div class="block-fluid table-sorting clearfix">
                    <div class="row">
                        <div class="col-xs-12" id="appliedfilter"></div>
                    </div>
                </div>
                <div style="padding: 5px">
                    <div class="block-fluid table-sorting clearfix">
                        <div class="row">
                            <?php
								$buybackOrder = new BuybackOrder();
								$field = 'website_id';
								?>
                            <label class="col-md-12 col-form-label">
                                <h5 class="filterheading">Website <i data-id="<?php echo $field; ?>"
                                        class="filtercontroller pull-right fa fa-chevron-down"></i></h5>
                            </label>
                            <div id="label_<?php echo $field; ?>" class="col-md-12 col-form-label">
                                <?php
									$records = $buybackOrder->getWebsiteFilteration();
									if (count($records)) {
										$i = 0;
										foreach ($records as $item) {
											$i++;
									?>
                                <div class="form-check checkbox filtercheckbox">
                                    <input class="form-check-input ml-0"
                                        data-label="<?php echo htmlspecialchars($item['store_title']); ?>"
                                        name="<?php echo $field ?>" id="<?php echo $field . '_' . $i ?>" type="checkbox"
                                        value="<?php echo htmlspecialchars($item['website_id']); ?>"> &nbsp;
                                    <input class="form-check-input ml-1"
                                        data-label="Not <?php echo htmlspecialchars($item['store_title']); ?>"
                                        name="<?php echo $field ?>" id="inerse_<?php echo $field . '_' . $i ?>"
                                        type="checkbox"
                                        value="<?php echo htmlspecialchars($item['website_id']); ?>|||!="> &nbsp; &nbsp;
                                    <label class="form-check-label" for="<?php echo $field . '_' . $i ?>"><img
                                            class="img-avatar" src="<?php echo $item['store_icon'] ?>" height="24px" />
                                        <?php echo htmlspecialchars($item['store_title']); ?>
                                        (<?php echo $item['record']; ?>)</label>
                                </div>
                                <?php
										}
									}
									?>
                            </div>
                        </div>
                    </div>
                    <div class="block-fluid table-sorting clearfix">
                        <div class="row">
                            <?php
								$field = 'buy_currency';
								?>
                            <label class="col-md-12 col-form-label">
                                <h5 class="filterheading">Currency <i data-id="<?php echo $field; ?>"
                                        class="filtercontroller pull-right fa fa-chevron-down"></i></h5>
                            </label>
                            <div id="label_<?php echo $field; ?>" class="col-md-12 col-form-label">
                                <?php
									$records = $buybackOrder->getBbOrderCurrency();
									if (count($records)) {
										$i = 0;
										foreach ($records as $item) {
											$i++;
									?>
                                <div class="form-check checkbox filtercheckbox">
                                    <input class="form-check-input ml-0"
                                        data-label="<?php echo htmlspecialchars($item['buy_currency']); ?>"
                                        name="<?php echo $field ?>" id="<?php echo $field . '_' . $i ?>" type="checkbox"
                                        value="<?php echo htmlspecialchars($item['buy_currency']); ?>"> &nbsp;
                                    <label class="form-check-label" for="<?php echo $field . '_' . $i ?>">
                                        <?php echo htmlspecialchars($item['buy_currency']); ?>
                                        (<?php echo $item['record']; ?>)</label>
                                </div>
                                <?php
										}
									}
									?>
                            </div>
                        </div>
                    </div>


                    <div class="block-fluid table-sorting clearfix">
                        <div class="row">
                            <?php
								$field = 'created_at';
								?>
                            <label class="col-md-12 col-form-label">
                                <h5 class="filterheading">Date Range <i data-id="<?php echo $field; ?>"
                                        class="filtercontroller pull-right fa fa-chevron-down"></i></h5>
                            </label>
                            <div id="label_<?php echo $field; ?>" class="col-md-12 col-form-label">

                                <div class="form-check checkbox filtercheckbox">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="pl-0" for="created_at_from">From Date<sup></sup></label>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control form-check-input"
                                                        data-label="Date from" id="created_at_from"
                                                        name="created_at_from" placeholder="YYYY-MM-DD" value="">
                                                    <span class="input-group-addon">
                                                        <label style="margin-bottom:0px;" for="created_at_from"><i
                                                                class="fa fa-calendar fa-lg m-t-2"></i></label>
                                                    </span>
                                                </div>
                                                <script type="text/javascript">
                                                $('#created_at_from').datepicker({
                                                    format: "yyyy-mm-dd",
                                                    autoclose: true,
                                                    daysOfWeekHighlighted: '0,6',
                                                    todayHighlight: true,
                                                    showClear: true,
                                                    showClose: true,
                                                    showTodayButton: true
                                                });
                                                </script>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="pl-0" for="created_at_to">To Date<sup></sup></label>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control form-check-input"
                                                        data-label="Date to" id="created_at_to" name="created_at_to"
                                                        placeholder="YYYY-MM-DD" value="">
                                                    <span class="input-group-addon">
                                                        <label style="margin-bottom:0px;" for="created_at_to"><i
                                                                class="fa fa-calendar fa-lg m-t-2"></i></label>
                                                    </span>
                                                </div>
                                                <script type="text/javascript">
                                                $('#created_at_to').datepicker({
                                                    format: "yyyy-mm-dd",
                                                    autoclose: true,
                                                    daysOfWeekHighlighted: '0,6',
                                                    todayHighlight: true,
                                                    showClear: true,
                                                    showClose: true,
                                                    showTodayButton: true
                                                });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">Fetch Buyback order</div>
            <div class="card-body p-2 d-flex align-items-center">
                <div class="row">
                    <div class="col-xs-4 pr-0">
                        <div class="form-group w-100">
                            <label for="fetch_store_id">Store</label>
                            <select name="fetch_store_id" class="form-control fetch_store_id" size="1">
                                <?php $store = new Store(0);
									echo $store->getOptions(0, 'store_is_ecommerce'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-8 pl-0">
                        <div class="form-group w-100">
                            <label for="store_order_id">#Order number</label>
                            <div class="input-group">
                                <input class="form-control fetch_order_id" name="fetch_order_id"
                                    placeholder="Ex-000000000" value="" type="text">
                                <span class="input-group-addon bg-info text-white" style="border: 1px solid #3af;">
                                    <label style="margin-bottom:0px;" for="btn_fetch_store_order"><i
                                            id="btn_fetch_store_order" class="fa fa-search fa-lg m-t-2"
                                            style="cursor:pointer"></i></label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php } ?>
    <div class="col-lg-<?php echo isAdmin() ? 9 : 12; ?>">
        <div class="card">
            <div class="card-header"> <i class="fa fa-align-justify"></i> Buyback order List
                <div class="card-actions">

                </div>
            </div>
            <div class="block-fluid table-sorting clearfix">
                <table id="<?php echo $TABEL_ID; ?>" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Store</th>
                            <th>OrderNo</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Qty</th>
                            <th>status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/col-->
</div>
<script type="text/javascript">
$("#btn_fetch_store_order").off("click");
$("#amazonapiresponse").hide();
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
    action: "viewbuybackorderlist",
    filter: formdata
}
$(document).ready(function() {


    $('.form-check-input').on('change', function() {
        formdata = $("#tablefilter").serializeFormJSON();

        postdata = {
            action: "viewbuybackorderlist",
            filter: formdata
        }
        //console.log(postdata);

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


datatable = $('#<?php echo $TABEL_ID; ?>').DataTable({
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
        $(nRow).find('td').not(":last").not(":first").attr('onclick', "Redirect('" + aData[9] +
        "')"); // or whatever you choose to set as the id
    },
    "rowCallback": function(row, data, index) {
        //$('td', row).css('background-color', data[9]);
    },
    "order": [
        [3, 'desc']
    ],
    columnDefs: [{
            targets: [8],
            orderable: false
        },
        {
            className: "hidden-xs hidden-md hidden-sm visible-lg",
            "targets": [1, 3, 6, 7]
        },
        {
            className: "word_break",
            "targets": [4]
        }
    ]
});


$(document).on("click", "#btn_fetch_store_order", function(e) {
    if (checkingOrder == false) {
        checkingOrder = true;
        e.preventDefault();
        if ($(".fetch_store_id").val() == 0) {
            toastMessage('warning|Please select store first');
            return;
        }
        if ($(".fetch_order_id").val() == 0) {
            toastMessage('warning|Please enter order number');
            return;
        }
        var data = {
            'action': 'weborder/fetchbuybackorder',
            'store_id': $(".fetch_store_id").val(),
            'order_id': $(".fetch_order_id").val()
        };
        $.ajax({
            type: 'POST',
            data: data,
            url: sitePath + 'ajax.php',
            beforeSend: function() {
                $("#btn_fetch_store_order").removeClass('fa-search');
                $("#btn_fetch_store_order").addClass('fa-spin text-info fa-refresh');
            },
            success: function(output) {
                checkingOrder = false;
                $("#btn_fetch_store_order").addClass('fa-search');
                $("#btn_fetch_store_order").removeClass('fa-spin text-info fa-refresh');
                var arr = JSON.parse(output);
                if (arr[0] == 200) {
                    $(".fetch_store_id").val(0);
                    $(".fetch_order_id").val('');
                    setTimeout(function() {
                        Redirect('viewwebsiteorder')
                    }, 2000);
                }
                message(arr[1], 2000);
            }
        });
    }
});

$(document).on("click", "#amazonordersinglesearch", function(e) {
    if ($("#order_id").val() != "") {
        if (checkingOrder == false) {
            checkingOrder = true;
            e.preventDefault();
            var data = {
                order_id: $("#order_id").val()
            };
            $.ajax({
                type: 'POST',
                data: data,
                url: sitePath + 'api/amazon/ordersingle.php',
                beforeSend: function() {
                    $(".amazonorderrefreshloader").removeClass('bg-info fa-amazon');
                    $(".amazonorderrefreshloader").addClass('fa-spin text-info fa-refresh');
                },
                success: function(output) {
                    checkingOrder = false;
                    $(".amazonorderrefreshloader").addClass('bg-info fa-amazon');
                    $(".amazonorderrefreshloader").removeClass('fa-spin text-info fa-refresh');
                    var arr = JSON.parse(output);
                    if (arr[0] == 200) {
                        amazonApiMessage(arr[1], 2000);
                    } else
                        amazonApiMessage(arr[1], 5000);

                }
            });
        } else
            amazonApiMessage("danger|Amazon request on progress... Please wait", 2000);
    } else
        amazonApiMessage("danger|Please enter order number", 2000);
});


function amazonApiMessage(message, timeout) {
    var msg = message.split('|');
    $("#amazonapiresponse").show();
    var msgString = "<p class=\"p-0 m-0 text-" + msg[0] + "\">" + msg[1] + "</p>"
    $("#amazonapiresponse").html(msgString);
    setTimeout(function() {
        $("#amazonapiresponse").html('');
        $("#amazonapiresponse").hide();
    }, timeout);

}
</script>