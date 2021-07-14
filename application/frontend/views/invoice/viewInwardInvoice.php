<?php include(APPPATH.'views/top.php'); ?>

<style type="text/css">
    /*.page {
        width: 21cm;
        min-height: 29.7cm;
        padding: 2cm;
        margin: 1cm auto;
    }*/
    .subpage {
        padding: 20px 0;
        border: 3px #000 solid;
        height: auto;
    }
    /*table { width: 100%; }*/
    /* @page {
         size: A4;
         margin: 0;
     }*/
    @media print {
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
    }

</style>
<style media="screen">
    @media print {
        html {
            background: none;
            padding: 0;
        }
        body,body.navbar-fixed{margin:0; padding: 0; box-shadow: none; }
        .subpage{padding:0; margin: 0 !important;}
        .page{transform: rotate(90deg);}
    }
</style>
<input type="hidden" name="orderId" id="orderId" value="<?php echo $orderDetailArr["order_id"]; ?>">
<!--PAGE CONTENT BEGINS-->
<div class="row-fluid view-order">
    <div class="span12 offset1-remove">
        <?php //echo "<pre>";print_r($orderDetailArr); echo "</pre>";?>
        <div class="widget-box transparent invoice-box">
            <div class="widget-header widget-header-large">
                <h3 class="grey lighter pull-left position-relative">
                    <!--<i class="icon-leaf green"></i>-->
                    CHALLAN NO : <?php echo $orderDetailArr['order_no']; ?>
                    <?php
                    //echo '<pre>';
                    //print_r($orderDetailArr);
                    ?>
                </h3>

                <div class="widget-toolbar hidden-480 div-icons non-printable">
                    <!--<a href="index.php?c=invoice&m=generatePdf&orderId=<?php /*echo $orderDetailArr['order_id']; */?>" target="_blank" title="PDF">
                        <i class="icon-file bigger-120"></i>
                    </a>-->
                    <!--<a href="index.php?c=invoice&m=generateMailForm&action=E&orderId=<?php echo $orderDetailArr['order_id']; ?>" title="Mail">
							<i class="icon-envelope bigger-120"></i>
						</a>-->
                    <!--<a href="index.php?c=order&m=createOrder&action=E&orderId=<?php /*echo $orderDetailArr['order_id']; */?>" title="Edit">
                        <i class="icon-pencil bigger-120"></i>
                    </a>-->
                    <a href="javascript:void(0);" onClick="javascript:printInwardChallanContent('print-challan');" title="Print">
                        <i class="icon-print bigger-120"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="" id="print-challan">

        <!-- <div class="page" style=" width: 21cm; min-height: 29.7cm; padding: 2cm; margin: 1cm auto;"> -->
        <div class="page">
            <div class="subpage" style=" padding: 20px 0; margin-top:70px;border: none; height: auto;">
                <table width="400" border="0" cellpadding="0" cellspacing="0" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000000; margin:0 ;">
                    <tr align="center">
                        <td colspan="3"><strong style="font-size:20px;border: solid 1px #000;padding: 10px 20px;">READY FOR HEAT TREATMENT</strong></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td width="50"><img src="./img/cognova-v-logo.jpg" style="width:40px;" /></td>
                        <td width="20">&nbsp;</td>
                        <td>
                            <table width="100%">
                                <tr align="left">
                                    <td width="60%"  style="padding-bottom:5px; ">
                                        <strong>Customer:</strong> <?php echo ucwords($vendorArr["vendor_name"]); ?>
                                    </td>
                                    <td  width="40%" style="padding-bottom:5px; ">
                                        <strong>Custome Challan No.:</strong> <?php echo $orderDetailArr['customer_challan_no']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="5"></td>
                                </tr>
                                <tr align="left">
                                    <td width="60%" style="padding-bottom:5px; ">
                                        <strong>Part No.:</strong> <?php echo $orderDetailArr['orderProductDetailsArr'][0]['prod_name']; ?>
                                    </td>
                                    <td width="40%" style="padding-bottom:5px; ">
                                        <strong>Qty.:</strong> <?php echo $orderDetailArr['orderProductDetailsArr'][0]['prod_qty']; ?>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td width="60%"  style="padding-bottom:5px; ">
                                        <strong>Process:</strong>
                                        <?php
                                        $processIdA = json_decode($orderDetailArr['orderProductDetailsArr'][0]['process_ids']);
                                        $i=1;
                                        foreach ($processIdA AS $processId)
                                        {
                                            $concat = ($i > 1) ? ", ":"";
                                            echo $concat.$processA[$processId];
                                            $i++;
                                        }
                                        ?>
                                    </td>
                                    <td width="40%" style="padding-bottom:5px; ">
                                        <strong>Material:</strong> <?php echo $orderDetailArr['material_grade']; ?>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td width="60%" style="padding-bottom:5px; ">
                                        <strong>Specification:</strong> <?php echo $orderDetailArr['specification']; ?>
                                    </td>
                                    <td width="40%" style="padding-bottom:5px; ">
                                        <strong>Result:</strong> 
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td width="60%" style="padding-bottom:5px; ">
                                        <strong>Charge No.:</strong> 
                                    </td>
                                    <td width="40%" style="padding-bottom:5px; ">
                                        <strong>Weight:</strong> <?php echo $orderDetailArr['orderProductDetailsArr'][0]['prod_total_weight']; ?>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td width="60%" style="padding-bottom:5px; ">
                                        <strong>Remark:</strong> <?php echo $orderDetailArr['order_note']; ?>
                                    </td>
                                    <td width="40%"  style="padding-bottom:5px; ">
                                        <strong>Ref. No:</strong> <?php echo $orderDetailArr['order_no']; ?>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td width="60%" style="padding-bottom:5px; ">
                                        <strong>Sing.:</strong>
                                    </td>
                                    <td width="40%" style="padding-bottom:5px; ">
                                        <strong>Date:</strong> <?php echo $orderDetailArr['order_date']; ?>
                                    </td>
                                </tr>       
                            </table>        
                        </td>
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