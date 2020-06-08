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

    <div class="row-fluid">
        <!--<div class="span3 text-left">
            <button type="button" class="btn btn-small btn-success" onclick="javascript:location.href='index.php?c=order&m=createOrder&type=<?php /*echo $_REQUEST["type"]; */?>'"> <i class="icon-plus-sign bigger-125"></i> Generate Challan </button>
            <button type="button" class="btn btn-small btn-danger" onclick="return DeleteRow();" name="btnDelete" id="btnDelete"> <i class="icon-trash bigger-125"></i> Delete </button>
        </div>-->
        <div class="span12 text-left" style="vertical-align:text-top;">
            <form class="form-inline" id="frm_serch_order" name="frm_serch_order" action="" method="get">
                <input type="hidden" name="c" value="order"  />
                <input type="text" class="input-small" placeholder="Challan No" name="search_order" id="search_order" value="<?php echo $search_order;?>"/>
                <input type="text" data-date-format="yyyy-mm-dd" id="from_date" name="from_date" class="input-small date-picker" placeholder="From" value="<?php echo $from_date;?>"/>
                <input type="text" data-date-format="yyyy-mm-dd" id="to_date" name="to_date" class="input-small date-picker" placeholder="To" value="<?php echo $to_date;?>"/>
                <button type="submit" onclick="return submit_form(this.form);" class="btn btn-purple btn-small">
                    Search
                    <i class="icon-search icon-on-right bigger-110"></i>
                </button>
            </form>
        </div>
    </div>
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
                </tr>
                </thead>
                <tbody>
                <?php
                if(count($resultA)==0)
                {
                    echo "<tr>";
                    echo '<td colspan="11" style="text-align:center;">No data found.</td>';
                    echo "</tr>";
                }
                else
                {
                    foreach($resultA as $arrRecord)
                    {
                        $inwardQty = $arrRecord['prod_qty'];
                        $outwardQty = $outwardA[$arrRecord['order_no']][$arrRecord['prod_id']];
                        $pendingQty = $inwardQty - $outwardQty;

                        $strProcess = "";
                        if($arrRecord['process_ids']){
                            $processIdA = json_decode($arrRecord['process_ids']);
                            if(is_array($processIdA) && count($processIdA)){
                                foreach ($processIdA as $processId){
                                    $strProcess .= $processA[$processId].", ";
                                }
                            }
                        }
                        $strProcess = rtrim($strProcess, ", ");
                        echo '<tr>';
                        echo '<td>'. $arrRecord['order_no'] .'</td>';
                        echo '<td>'. $arrRecord['order_date'] .'</td>';
                        echo '<td>'. $vendorA[$arrRecord['customer_id']] .'</td>';
                        echo '<td>'. $prodA[$arrRecord['prod_id']] .'</td>';
                        echo '<td>'. $strProcess .'</td>';
                        echo '<td>'. $inwardQty .'</td>';
                        echo '<td>'. $arrRecord['customer_challan_no'] .'</td>';
                        echo '<td>'. $outwardQty .'</td>';
                        echo '<td>'.$pendingQty.'</td>';
                        echo '<td>0</td>';
                        '</tr>';
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php if($blnAjax != 1): ?>
    <?php include(APPPATH.'views/bottom.php'); ?>
<?php endif; ?>

<script type="text/javascript">
    $('.date-picker').datepicker().next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
    $('#id-date-range-picker-1').daterangepicker().prev().on(ace.click_event, function(){
        $(this).next().focus();
    });
    $(document).ready(function(){
        $('[data-rel=tooltip]').tooltip();
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
