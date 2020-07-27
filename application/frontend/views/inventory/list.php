<?php if($blnAjax != 1): ?>
    <?php include(APPPATH.'views/top.php');
    ?>
<?php endif; ?>
<div id="divOrder">
    <div class="page-header position-relative">
        <h1>Shop Floor Inventory</h1>
    </div>
    <input type="hidden" id="action" name="action" value="<?php echo $strAction; ?>" />
    <input type="hidden" id="from_page" name="from_page" value="<?php echo $from_page; ?>" />
    <br />
    <div class="row-fluid">
        <div class="span12">
            <div id="search-product-container" class="hide">
                <select class="form-control" name="prod_id" id="prod_id">
                    <?php echo $this->Page->generateComboByTable("product_master", "prod_id", "prod_name", "", "where status='ACTIVE'", "", "Select Product"); ?>
                </select>
            </div>
            <div id="search-process-container" class="hide">
                <select class="form-control" name="processIds" id="processIds" multiple="" data-placeholder="Choose a Process...">
                    <?php echo $this->Page->generateComboByTable("process", "id", "name", "", "where status='ACTIVE'", "", ""); ?>
                </select>
            </div>
            <div id="search-customer-container" class="hide">
                <select class="form-control" name="customer_id" id="customer_id">
                    <?php echo $this->Page->generateComboByTable("vendor_master", "vendor_id", "vendor_name", "", "where status='ACTIVE' order by vendor_name", "", "Select Customer"); ?>
                </select>
            </div>
            <table width="100%" cellpadding="5" cellspacing="5" border="0" class="table table-striped table-bordered table-hover dataTable" id="tbl-order-list">
                <thead>
                <tr class="hdr">
                    <th search-field="order_no">Challan No</th>
                    <th search-field="outward_challan_no">Outward Challan No</th>
                    <th search-field="order_date">Inward Date</th>
                    <th search-field="customer_id">Customer Name</th>
                    <th search-field="prod_id">Part</th>
                    <th search-field="processIds">Process</th>
                    <th search-field="prod_qty">Inward Qty</th>
                    <th search-field="customer_challan_no">Customer Challan No</th>
                    <th search-field="outward_qty">Outward Qty.</th>
                    <th search-field="pending_qty">Pending Qty.</th>
                    <!--<th>Pending From(Days)</th>-->
                    <th search-field="order_note">Remarks</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php if($blnAjax != 1): ?>
    <?php include(APPPATH.'views/bottom.php'); ?>
<?php endif; ?>

<script type="text/javascript">
    /*$('.date-picker').datepicker().next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
    $('#id-date-range-picker-1').daterangepicker().prev().on(ace.click_event, function(){
        $(this).next().focus();
    });*/
    $(document).ready(function(){
        $('[data-rel=tooltip]').tooltip();

        var aoColumns = [];
        $("table#tbl-order-list thead th").each(function (e) {
            if($(this).hasClass("no-sort")){
                aoColumns.push({"bSortable": false});
            } else {
                aoColumns.push(null);
            }
        });

        var oldExportAction = function (self, e, dt, button, config) {
            if (button[0].className.indexOf('buttons-excel') >= 0) {
                if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
                }
                else {
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                }
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
        };

        var newExportAction = function (e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;

            dt.one('preXhr', function (e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;

                dt.one('preDraw', function (e, settings) {
                    // Call the original action function
                    oldExportAction(self, e, dt, button, config);

                    dt.one('preXhr', function (e, s, data) {
                        // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                        // Set the property to what it was before exporting.
                        settings._iDisplayStart = oldStart;
                        data.start = oldStart;
                    });

                    // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                    setTimeout(dt.ajax.reload, 0);

                    // Prevent rendering of the full data to the DOM
                    return false;
                });
            });

            // Requery the server with the new one-time export settings
            dt.ajax.reload();
        };

        var columns = [];

        columns = [
            { "data": "order_no"},
            { "data": "outward_challan_no"},
            { "data": "order_date"},
            { "data": "customer_id"},
            { "data": "prod_id"},
            { "data": "process"},
            { "data": "inward_qty" },
            { "data": "customer_challan_no" },
            { "data": "outward_qty" },
            { "data": "pending_qty" },
            /*{ "data": "pending_from_days" },*/
            { "data": "order_note" }
        ];

        var oTable1 =	$('#tbl-order-list').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "ajax": {
                "url":"index.php?c=inventory&m=getInventoryData",
                "type": "POST",
                /*"data":  jQuery.parseJSON( '<?php echo $searchParams; ?>' ),*/
                "data":  function(data){
                    /*data.type = type;*/
                    $(".datatable-search").each(function () {
                        var key = this.id;
                        console.log(key);
                        console.log($("#tbl-order-list #"+key).val());
                        data[key] = $("#tbl-order-list #"+key).val();
                    });
                },
            },
            "columns": columns,
            /*"aoColumns": aoColumns,*/
            "lengthMenu": [25, 50,100,500],
            "iDisplayLength": 25,
            "searching": false,
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<span class="fa fa-file-excel-o"></span> Export to Excel',
                    action: newExportAction
                }
            ],
        });

        $('#tbl-order-list thead tr').clone(true).appendTo( '#tbl-order-list thead' );
        $('#tbl-order-list thead tr:eq(1) th').each( function (i) {
            if($(this).hasClass("sorting")){
                $(this).removeClass("sorting");
                $(this).addClass("sorting_disabled");
            }

            var searchField = $(this).attr('search-field');
            var title = $(this).text();
            title = replaceAll(title, ".", "");

            if(searchField == undefined){
                $(this).html('');
            }
            else if(searchField == "prod_id"){
                $(this).html($("#search-product-container").html());
                $("#tbl-order-list #prod_id").addClass("chzn-select");
                $("#tbl-order-list #prod_id").addClass("datatable-search");
                $(".chzn-select").chosen();
            } else if(searchField == "processIds"){
                $(this).html($("#search-process-container").html());
                $("#tbl-order-list #processIds").addClass("chzn-select");
                $("#tbl-order-list #processIds").addClass("datatable-search");
                $(".chzn-select").chosen();
            } else if(searchField == "customer_id"){
                $(this).html($("#search-customer-container").html());
                $("#tbl-order-list #customer_id").addClass("chzn-select");
                $("#tbl-order-list #customer_id").addClass("datatable-search");
                $(".chzn-select").chosen();
            } else if(searchField == "order_date") {
                $(this).html('<input type="text" id="' + searchField + '_from" name="' + searchField + '_from" data-date-format="dd-mm-yyyy" class="datatable-search date-picker" placeholder="From ' + title + '" /> <input type="text" id="' + searchField + '_to" name="' + searchField + '_to" data-date-format="dd-mm-yyyy" class="datatable-search date-picker" placeholder="To ' + title + '" />');
                datePickerConfig();
            } else {
                $(this).html( '<input type="text" id="'+searchField+'" name="'+searchField+'" class="datatable-search" placeholder="'+title+'" />' );
            }

            $( 'input', this ).on( 'keyup change', function () {
                oTable1.fnDraw();
            });
        } );
    });

    function loadViewOrder(orderId)
    {
        var param = {};
        param['orderId'] = orderId;
        $.ajax({
            type:"POST",
            data:param,
            url : "index.php?c=order&m=viewClientOrder",
            beforeSend:function(){
            },
            success:function(res){
                $("#divOrder").html(res);
            },
            error:function(){
                alert("Please try again"); return false;
            }
        });
    }

    /*$(".viewOrder").click(function(){
        var orderId = this.id;
        var param = {};
        param['orderId'] = orderId;
        $.ajax({
            type:"POST",
            data:param,
            url : "index.php?c=order&m=viewClientOrder",
            beforeSend:function(){
            },
            success:function(res){
                $("#divOrder").html(res);
            },
            error:function(){
                alert("Please try again"); return false;
            }
        });
    });*/
</script>
