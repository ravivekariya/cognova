<?php include(APPPATH.'views/top.php'); ?>

<style type="text/css">
    .page {
        width: 21cm;
        min-height: 29.7cm;
        padding: 2cm;
        margin: 1cm auto;
    }
    .subpage {
        padding: 1cm;
        border: 3px #000 solid;
        height: 256mm;
    }

   /* @page {
        size: A4;
        margin: 0;
    }*/
   /* @media print {
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }*/
</style>

<input type="hidden" name="orderId" id="orderId" value="<?php echo $orderDetailArr["order_id"]; ?>">
<!--PAGE CONTENT BEGINS-->
<div class="row-fluid view-order">
    <div class="span12 offset1-remove">
        <div class="widget-box transparent invoice-box">
            <div class="widget-header widget-header-large">
                <h3 class="grey lighter pull-left position-relative">
                    <!--<i class="icon-leaf green"></i>-->
                    CHALLAN NO : <?php echo $orderDetailArr['outward_challan_no']; ?>
                </h3>

                <div class="widget-toolbar hidden-480 div-icons non-printable">
                    <!--<a href="index.php?c=invoice&m=generatePdf&orderId=<?php /*echo $orderDetailArr['order_id']; */?>" target="_blank" title="PDF">
                        <i class="icon-file bigger-120"></i>
                    </a>-->
                    <!--<a href="index.php?c=invoice&m=generateMailForm&action=E&orderId=<?php echo $orderDetailArr['order_id']; ?>" title="Mail">
							<i class="icon-envelope bigger-120"></i>
						</a>-->
                    <a href="index.php?c=order&m=createOrder&action=E&orderId=<?php echo $orderDetailArr['order_id']; ?>" title="Edit">
                        <i class="icon-pencil bigger-120"></i>
                    </a>
                    <a href="javascript:void(0);" onClick="javascript:printContent('print-challan');" title="Print">
                        <i class="icon-print bigger-120"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="" id="print-challan">

        <div class="page" style=" width: 21cm; min-height: 29.7cm; padding: 2cm; margin: 1cm auto;">
            <div class="subpage" style=" padding: 1cm; border: 3px #000 solid; height: 256mm;">
                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#000000;">
                    <tr>
                        <td>
                            <table width="100%" border="0" cellpadding="0" cellspacing="5" align="center">
                                <tr>
                                    <td height="30"></td>
                                </tr>
                                <tr align="center">
                                    <td><strong>COGNOVA HEAT TREATMENT LLP</strong></td>
                                </tr>
                                <tr align="center">
                                    <td bgcolor="#000000" height="1"></td>
                                </tr>
                                <tr align="center">
                                    <td>Dhwani-4, Nr. Pipaliya Toll Plaza, Rajkot - Gondal N.H.-27, Vill. - Ardoi,</td>
                                </tr>
                                <tr align="center">
                                    <td>Rajkot - 360030, <strong>Email:</strong>info@cognova.in &nbsp;<strong>Web:</strong> cognova.in</td>
                                </tr>

                            </table>

                        </td>
                    </tr>
                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr align="center">
                        <td bgcolor="#000000" style="display:inline-block; padding-bottom:5px; padding-left:15px; padding-right:15px; padding-top:5px;"><strong style="color:#ffffff">DELIVERY CHALLAN</strong></td>
                    </tr>
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" border="0" cellpadding="2" cellspacing="0" align="center">
                                <tr valign="top">
                                    <td width="55%">
                                        <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse; border-color:#000000;">
                                            <tr valign="top" height="81">
                                                <td width="15%">M/s,</td>
                                                <td><?php echo ucwords($vendorArr["vendor_name"]); ?> </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse; border:0px solid #000000;">
                                            <tr valign="top" height="25">
                                                <td width="50%">Challan No.</td>
                                                <td><?php echo $orderDetailArr["outward_challan_no"]; ?></td>
                                            </tr>
                                            <tr valign="top" height="25">
                                                <td width="50%">Cust. Challan No.</td>
                                                <td><?php echo $orderDetailArr["customer_challan_no"]; ?></td>
                                            </tr>
                                            <tr height="25">
                                                <td>Date:</td>
                                                <td><?php echo $orderDetailArr['order_date']; ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse; border-color:#000000;">
                                <tr>
                                    <td width="5%" align="center"><strong>Sr. No.</strong></td>
                                    <td width="45%"><strong>Particulars</strong></td>
                                    <td width="10%" align="center"><strong>Qty.</strong></td>
                                    <td width="10%" align="center"><strong>Weight/Qty</strong></td>
                                    <td width="10%" align="center"><strong>Total Weight</strong></td>
                                    <td width="20%" align="center"><strong>Remarks</strong></td>
                                </tr>

                                <?php
                                $cnt = 1;
                                if(is_array($orderDetailArr['orderProductDetailsArr']) && count($orderDetailArr['orderProductDetailsArr']))
                                {
                                    foreach($orderDetailArr['orderProductDetailsArr'] AS $prodRow)
                                    {
                                        ?>
                                        <tr>
                                            <td align="center"><?php echo $cnt; ?></td>
                                            <td>
                                                <?php echo $prodRow["prod_name"];  ?>
                                                <?php
                                                    if(isset($prodRow['process_ids']) && $prodRow['process_ids'])
                                                    {
                                                        $processIdA = json_decode($prodRow['process_ids']);
                                                        $i=1;
                                                        echo '<span style="font-size:12px; display: block">';
                                                        foreach ($processIdA AS $processId)
                                                        {
                                                            $concat = ($i > 1) ? ", ":"";
                                                            echo $concat.$processA[$processId];
                                                            $i++;
                                                        }
                                                        echo '</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo $prodRow["prod_qty"];  ?></td>
                                            <td><?php echo $prodRow["weight_per_qty"];  ?></td>
                                            <td><?php echo $prodRow["prod_total_weight"];  ?></td>
                                            <td><?php echo $orderDetailArr["order_note"]; ?></td>
                                        </tr>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }
                                }

                                $remainCnt = 15-$cnt;

                                for($remainCnt; $remainCnt >=1; $remainCnt--)
                                {
                                    ?>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>

                        </td>
                    </tr>
                    <tr valign="top">
                        <td align="right" style="font-size:13px;">For, <strong>COGNOVA HEAT TREATMENT LLP</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
	<!--PAGE CONTENT ENDS-->
</div><!--/.span-->
<?php include(APPPATH.'views/bottom.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
});
</script>