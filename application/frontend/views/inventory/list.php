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
            <table width="100%" cellpadding="5" cellspacing="5" border="0" class="table table-striped table-bordered table-hover dataTable" id="tbl-order-list">
                <thead>
                <tr class="hdr">
                    <th>Challan No</th>
                    <th>Inward Date</th>
                    <th>Customer Name</th>
                    <th>Part</th>
                    <th>Process</th>
                    <th>Inward Qty</th>
                    <th>Customer Challan No</th>
                    <th>Outward Qty.</th>
                    <th>Pending Qty.</th>
                    <th>Pending From(Days)</th>
                    <th>Remarks</th>
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

        var columns = [];

        columns = [
            { "data": "order_no"},
            { "data": "order_date"},
            { "data": "customer_id"},
            { "data": "prod_id"},
            { "data": "process"},
            { "data": "inward_qty" },
            { "data": "customer_challan_no" },
            { "data": "outward_qty" },
            { "data": "pending_qty" },
            { "data": "pending_from_days" },
            { "data": "order_note" }
        ];

        var oTable1 =	$('#tbl-order-list').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "ajax": {
                "url":"index.php?c=inventory&m=getInventoryData",
                "type": "POST",
                "data":  jQuery.parseJSON( '<?php echo $searchParams; ?>' ),
            },
            "columns": columns,
            /*"aoColumns": aoColumns,*/
            "iDisplayLength": 25,
            "search": {
                "regex": true
            }
        });
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
