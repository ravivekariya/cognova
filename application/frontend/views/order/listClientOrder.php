<?php if($blnAjax != 1): ?>
<?php include(APPPATH.'views/top.php'); 
?>
<?php endif; ?>
<div id="divOrder">
	<div class="page-header position-relative">
		<h1><?php echo ucfirst($type); ?> List</h1>
	</div>
	<input type="hidden" id="action" name="action" value="<?php echo $strAction; ?>" />
	<input type="hidden" id="from_page" name="from_page" value="<?php echo $from_page; ?>" />

    <?php echo $flashMessage; ?>

	<div class="row-fluid">
		<div class="span3 text-left">
			<button type="button" class="btn btn-small btn-success" onclick="javascript:location.href='index.php?c=order&m=createOrder&type=<?php echo $_REQUEST["type"]; ?>'"> <i class="icon-plus-sign bigger-125"></i> Generate <?php echo ucfirst($type); ?> Challan </button>
		</div>
	</div>
	<br />
	<div class="row-fluid">
		<div class="span12">
			<table width="100%" cellpadding="5" cellspacing="5" border="0" class="table table-striped table-bordered table-hover dataTable" id="tbl-order-list">
				<thead>
					<tr class="hdr">
                        <?php if($type == "inward") { ?>
                            <th>Challan No</th>
                            <th>Date</th>
                            <th>Customer Challan No</th>
                            <th>Customer Name</th>
                            <th>Part No</th>
                            <th>Qty</th>
                            <th>Material Grade</th>
                            <th>Process Required</th>
                            <th>Specification</th>
                            <th>Remarks</th>
                            <th class="no-sort">Action</th>
                        <?php } else { ?>
                            <th>Ref Challan No</th>
                            <th>Date</th>
                            <th>Dispatch Qty</th>
                            <th>Cut Wt.</th>
                            <th>Total Wt.</th>
                            <th>Customer Challan No</th>
                            <th>Inward Qty</th>
                            <th>Customer Name</th>
                            <th>Part No</th>
                            <th>Material Grade</th>
                            <th>Process Carried Out</th>
                            <th>Specification</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        <?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php
					/*if(count($orderListArr)==0)
					{
						echo "<tr>";
						echo '<td colspan="11" style="text-align:center;">No data found.</td>';
						echo "</tr>";
					}
					else
					{
						foreach($orderListArr as $arrRecord)
						{
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

                            if ($type == "inward") {
                                $strEditLink	=	"index.php?c=order&m=createOrder&action=E&type=".$_REQUEST["type"]."&orderId=".$arrRecord['order_id'];
                                echo '<tr>';
                                echo '<td>'. $arrRecord['order_no'] .'</td>';
                                echo '<td>'. $arrRecord['order_date'] .'</td>';
                                echo '<td>'. $arrRecord['customer_challan_no'] .'</td>';
                                echo '<td>'. $vendorA[$arrRecord['customer_id']] .'</td>';
                                echo '<td>'. $prodA[$arrRecord['prod_id']] .'</td>';
                                echo '<td>'. $arrRecord['prod_qty'] .'</td>';
                                echo '<td>'. $arrRecord['material_grade'] .'</td>';
                                echo '<td>'. $strProcess .'</td>';
                                echo '<td>'. $arrRecord['specification'] .'</td>';
                                echo '<td>'. $arrRecord['order_note'] .'</td>';
                                echo '<td width="20" class="action-buttons" nowrap="nowrap">
                                    <a href="'.$strEditLink.'" class="green" title="Edit"><i class="icon-pencil bigger-130"></i></a>
                                    <a href="javascript:void(0);" class="red delete" title="Delete" id="'.$arrRecord['order_id'].'"><i class="icon-trash bigger-130"></i></a>
							      </td>
							      </tr>';
                            } else {
                                $strEditLink	=	"index.php?c=order&m=createOrder&action=E&type=".$_REQUEST["type"]."&orderId=".$arrRecord['order_id'];
                                echo '<tr>';
                                echo '<td>'. $arrRecord['ref_order_no'] .'</td>';
                                echo '<td>'. $arrRecord['order_date'] .'</td>';
                                echo '<td>'. $arrRecord['prod_qty'] .'</td>';
                                echo '<td>'. $arrRecord['weight_per_qty'] .'</td>';
                                echo '<td>'. $arrRecord['prod_total_weight'] .'</td>';
                                echo '<td>'. $arrRecord['customer_challan_no'] .'</td>';
                                echo '<td>'. $inwardQtyA[$arrRecord['ref_order_no']] .'</td>';
                                echo '<td>'. $vendorA[$arrRecord['customer_id']] .'</td>';
                                echo '<td>'. $prodA[$arrRecord['prod_id']] .'</td>';
                                echo '<td>'. $arrRecord['material_grade'] .'</td>';
                                echo '<td>'. $strProcess .'</td>';
                                echo '<td>'. $arrRecord['specification'] .'</td>';
                                echo '<td>'. $arrRecord['order_note'] .'</td>';
                                echo '<td width="20" class="action-buttons" nowrap="nowrap">
                                    <a href="'.$strEditLink.'" class="green" title="Edit"><i class="icon-pencil bigger-130"></i></a>
                                    <a href="javascript:void(0);" class="red delete" title="Delete" id="'.$arrRecord['order_id'].'"><i class="icon-trash bigger-130"></i></a>
                                    <a href="index.php?c=invoice&m=generateInvoice&orderId='.$arrRecord['order_id'].'" class="blue" title="Invoice"><i class="icon-save bigger-130"></i></a>
							      </td>
							      </tr>';
                            }
						}
					}*/
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
/*	$('.date-picker').datepicker().next().on(ace.click_event, function(){
		$(this).prev().focus();
	});
	$('#id-date-range-picker-1').daterangepicker().prev().on(ace.click_event, function(){
		$(this).next().focus();
	});*/

    var type = '<?php echo $type; ?>';
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
        if(type == "inward"){
            columns = [
                { "data": "order_no"},
                { "data": "order_date", "orderable": false },
                { "data": "customer_challan_no" },
                { "data": "customer_id" },
                { "data": "prod_id" },
                { "data": "prod_qty" },
                { "data": "material_grade" },
                { "data": "process" },
                { "data": "specification" },
                { "data": "order_note" },
                { "data": "actionLink", "orderable": false },
            ];
        } else {
            columns = [
                { "data": "ref_order_no"},
                { "data": "order_date", "orderable": false },
                { "data": "prod_qty"},
                { "data": "weight_per_qty"},
                { "data": "prod_total_weight"},
                { "data": "customer_challan_no" },
                { "data": "inward_qty" },
                { "data": "customer_id" },
                { "data": "prod_id" },
                { "data": "material_grade" },
                { "data": "process" },
                { "data": "specification" },
                { "data": "order_note" },
                { "data": "actionLink", "orderable": false },
            ];
        }

        var oTable1 =	$('#tbl-order-list').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "ajax": {
                "url":"index.php?c=order&m=getOrderData",
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

    $(document).on('click','.delete',function(){
        if(type == "inward"){
            var responce = confirm("All outward entries referenced with this inward challan will be deleted, Are you sure you want to delete?");
        } else {
            var responce = confirm("Are you sure you want to delete outward challan?");
        }

        if(responce==true)
        {
            var orderId = this.id;
            var param = {};
            param['orderId'] = orderId;
            param['type'] = type;
            $.ajax({
                type:"POST",
                data:param,
                url : "index.php?c=order&m=DeleteOrder",
                beforeSend:function(){
                },
                success:function(res){
                    if(res){
                        window.location.reload();
                    }
                },
                error:function(){
                    alert("Please try again"); return false;
                }
            });
        }
    });

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
