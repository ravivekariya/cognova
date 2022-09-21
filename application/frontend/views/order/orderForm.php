<?php include(APPPATH.'views/top.php'); ?>
<div class="page-header position-relative">
    <?php
    if($type == "inward"){
        echo "<h1>Generate Inward Challan</h1>";
    } elseif ($type == "outward"){
        echo "<h1>Generate Outward Challan</h1>";
    }
    ?>

</div>
<div class="row-fluid">
    <div class="span12">
        <?php echo $flashMessage; ?>
    	<form class="form-horizontal" id="frmOrder"/>
            <?php if($type == "inward"){ ?>
                <div class="control-group">
                    <label class="control-label" for="form-input-readonly">Challan No</label>
                    <div class="controls">
                        <input readonly="" type="text" class="span4 required" id="txtOrderNo" name="txtOrderNo" value="<?php echo $orderNo; ?>" disabled="disabled" />
                    </div>
                </div>
            <?php } else { ?>
                <div class="control-group">
                    <label class="control-label" for="form-input-readonly">Ref. Inward Challan No</label>
                    <div class="controls">
                        <input type="text" class="span4 required" id="txtOrderRefNo" name="txtOrderRefNo" value="<?php echo ($refOrderNo) ? $refOrderNo : $orderDetailArr['ref_order_no']; ?>" />
                        <?php if(!$refOrderNo) { ?>
                            <button onclick="javascript:loadOutwardChallanForm();" type="button" class="btn btn-purple btn-small">Search<i class="icon-search icon-on-right bigger-110"></i></button>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php
            if($strAction == "A" && $type == "outward" && !$refOrderNo ) {

            } else {
            ?>

                <div class="control-group">
                    <label class="control-label" for="form-field-2">Customer</label>

                    <div class="controls">
                        <select id="selCustomer" name="selCustomer" class="required chzn-select">
                            <?php echo $this->Page->generateComboByTable("vendor_master", "vendor_id", "vendor_name", "", "where status='ACTIVE' order by vendor_name", $orderDetailArr['customer_id'], "Select Customer"); ?>
                        </select>
                        <a id="" href="#cust-form" data-toggle="modal">Add New Customer<i
                                    class="icon-external-plus"></i></a>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="form-input-readonly">Customer challan No</label>

                    <div class="controls">
                        <input type="text" class="span4" id="txtCustChallanNo" name="txtCustChallanNo"
                               value="<?php echo $orderDetailArr['customer_challan_no']; ?>"/>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="form-field-4">Date</label>

                    <div class="controls">
                        <input type="text" id="txtOrderDate" name="txtOrderDate" class="date-picker span4 required" placeholder="dd-mm-yyyy" value="<?php echo ($strAction == "E") ? $orderDetailArr['order_date'] : ""; ?>">
                        <span class="add-on">
                        <i class="icon-calendar"></i>
                    </span>
                    </div>
                </div>

                <!-- START ADD ITEM DETAILS -->
                <div class="widget-box transparent">
                    <div class="widget-body">
                        <div class="widget-main">
                            <h3 class="smaller lighter blue">
                                Item Details
                            </h3>
                            <!-- START ITEM DETAILS -->
                            <table class="table table-striped tbl-item-dtl">
                                <thead>
                                <tr>
                                    <th class="span2">
                                        <a id="" href="#prod-form" data-toggle="modal"> Add Product <i
                                                    class="icon-plus"></i></a>
                                        <br/>Product Name
                                    </th>
                                    <th class="span2">Process</th>
                                    <?php if ($type == "outward") { ?>
                                        <th class="span2">Inward Qty</th>
                                        <th class="span1">Dispatch Qty</th>
                                        <th class="span1">Pending Qty</th>
                                    <?php } ?>
                                    <th class="span1">Quantity</th>
                                    <th class="span2">Weight</th>
                                    <th class="span1">Total weight</th>
                                    <?php if ($type == "inward") { ?>
                                    <th class="span1">Action</th>
                                    <?php } ?>
                                </tr>
                                </thead>

                                <tbody>
                                <tr class="entry-hidden hide">
                                    <td class="span2">
                                        <select class="form-control product calc" name="selProduct"
                                                id="selProduct">
                                            <?php // echo $this->Page->generateComboByTable("product_master", "prod_id", "prod_name", "", "where status='ACTIVE'", "", "Select Product"); ?>
                                            <?php echo $this->Page->generateOrderProductCombo(""); ?>
                                        </select>
                                    </td>
                                    <td class="span2">
                                        <select class="form-control" name="selProcess" id="selProcess"
                                                multiple="" data-placeholder="Choose a Process...">
                                            <?php echo $this->Page->generateComboByTable("process", "id", "name", "", "where status='ACTIVE'", "", ""); ?>
                                        </select>
                                    </td>
                                    <td class="span1">
                                        <input class="form-control span12 calc" type="text" name="txtProductQty"id="txtProductQty" placeholder="QTY"/>
                                    </td>
									<td class="span2">
										<input class="form-control span12 calc" name="txtProductWeight"
											   id="txtProductWeight" type="text" placeholder="INR"/>
									</td>
									<td class="span1">
										<label id="prodTotalWeight" name="prodTotalWeight" class="span12">0.0</label>
									</td>
                                    <?php if ($type == "inward") { ?>
                                    <td class="span1">
                                        <button id="btnOrderProdAdd" type="button" class="btn btn-remove btn-danger" disabled>
                                            <span class="icon-minus bigger-110"></span></button>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <?php
                                $cnt = 0;
                                $prodDetailsArr = $orderDetailArr['orderProductDetailsArr'];
                                if (count($prodDetailsArr) > 0) {
                                    foreach ($prodDetailsArr AS $key => $productRow) {
                                        $processIds = "";
                                        if ($productRow['process_ids']) {
                                            $processIds = implode(",", json_decode($productRow['process_ids']));
                                        }
                                        ?>
                                        <tr class="entry" id="<?php echo $cnt; ?>" opd-id="<?php echo $productRow['opd_id']; ?>">
                                            <td class="span2">
                                                <select class="form-control product calc chzn-select required" name="selProduct"
                                                        id="selProduct<?php echo $cnt; ?>">
                                                    <?php // echo $this->Page->generateComboByTable("product_master", "prod_id", "prod_name", "", "where status='ACTIVE'", $productRow['prod_id'], "Select Product"); ?>
                                                    <?php echo $this->Page->generateOrderProductCombo($productRow['prod_id']); ?>
                                                </select>
                                            </td>
                                            <td class="span2">
                                                <select class="form-control chzn-select required" name="selProcess"
                                                        id="selProcess" multiple=""
                                                        data-placeholder="Choose a Process...">
                                                    <?php echo $this->Page->generateComboByTable("process", "id", "name", "", "where status='ACTIVE'", $processIds, ""); ?>
                                                </select>
                                            </td>
                                            <?php if ($type == "outward") { ?>
                                                <td class="span2">
                                                    <label id="prodInwardQty<?php echo $cnt; ?>"name="prodInwardQty"class="span12"><?php echo $proceedQtyA['inwardQtyA'][$productRow['prod_id']]; ?></label>
                                                </td>
                                                <td class="span1">
                                                    <label id="prodProceedQty<?php echo $cnt; ?>"name="prodProceedQty"class="span12"><?php echo $proceedQtyA['outwardProceedQtyA'][$productRow['prod_id']]; ?></label>
                                                </td>
                                                <td class="span1">
                                                    <label id="prodProceedRemainQty<?php echo $cnt; ?>"name="prodProceedRemainQty"class="span12">
                                                        <?php 
                                                            $inwardQty  = $proceedQtyA['inwardQtyA'][$productRow['prod_id']];
                                                            $ProceedQty = $proceedQtyA['outwardProceedQtyA'][$productRow['prod_id']]; 
                                                            echo $inwardQty - $ProceedQty;
                                                        ?>
                                                    </label>
                                                </td>
                                            <?php } ?>
                                            <td class="span1">
                                                <input class="form-control span12 required calc" type="text" name="txtProductQty" id="txtProductQty<?php echo $cnt; ?>" saved-qty="<?php echo ($strAction == "E") ? $productRow['prod_qty'] : 0;  ?>" placeholder="QTY" value="<?php echo ($strAction == "E") ? $productRow['prod_qty'] : ""; ?>"/>
                                            </td>                                            
											<td class="span2">
												<input class="form-control span12 required calc"
													   name="txtProductWeight"
													   id="txtProductWeight<?php echo $cnt; ?>" type="text"
													   placeholder="INR"
													   value="<?php echo $productRow['weight_per_qty']; ?>"/>
											</td>
											<td class="span1">
												<label id="prodTotalWeight<?php echo $cnt; ?>"
													   name="prodTotalWeight"
													   class="span12"><?php echo $productRow['prod_total_weight']; ?></label>
											</td>
                                            <?php if ($type == "inward") { ?>
                                            <td class="span1">
                                                <?php
                                                    if ($cnt == 0) {
                                                        ?>
                                                        <button class="btn btn-success btn-add" type="button"
                                                                id="btnOrderProdAdd"><span
                                                                class="icon-plus bigger-110" disabled></span></button>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button id="btnOrderProdAdd" type="button"
                                                                class="btn btn-remove btn-danger"><span
                                                                class="icon-minus bigger-110" disabled></span></button>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }
                                } else {
                                    ?>
                                    <tr class="entry" id="0">
                                        <td class="span2">
                                            <!--<input class="form-control span12 required calc" type="text" name="txtProduct" id="txtProduct0" placeholder="Item" />-->
                                            <select class="form-control product calc chzn-select required" name="selProduct"
                                                    id="selProduct0">
                                                <?php // echo $this->Page->generateComboByTable("product_master", "prod_id", "prod_name", "", "where status='ACTIVE'", "", "Select Product"); ?>
                                                <?php echo $this->Page->generateOrderProductCombo(""); ?>
                                            </select>
                                        </td>
                                        <td class="span2">
                                            <select class="form-control chzn-select required" name="selProcess"
                                                    id="selProcess0" multiple="" data-placeholder="Choose a Process...">
                                                <?php echo $this->Page->generateComboByTable("process", "id", "name", "", "where status='ACTIVE'", "", ""); ?>
                                            </select>
                                        </td>
                                        <td class="span1">
                                            <input class="form-control span12 required calc" type="text" name="txtProductQty" id="txtProductQty0" placeholder="QTY"/>
                                        </td>
										<td class="span2">
											<input class="form-control span12 required calc" name="txtProductWeight"
												   id="txtProductWeight0" type="text" placeholder="INR"/>
										</td>
										<td class="span1">
											<label id="prodTotalWeight0" name="prodTotalWeight"
												   class="span12">0.0</label>
										</td>
                                        <?php if ($type == "inward") { ?>
                                        <td class="span1">
                                            <button class="btn btn-success btn-add" type="button" id="btnOrderProdAdd" disabled>
                                                <span class="icon-plus bigger-110"></span>
                                            </button>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <!-- END ITEM DETAILS -->

                            <!-- START TOTAL -->
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td align="right">
                                        <div class="span12">
                                            <div class="span6">
                                            </div>
                                            <div class="span6 divTotal">
                                                <div class="control-group">
                                                    <label class="control-label">TOTAL</label>
                                                    <div class="controls">
                                                        <label class="control-label span5"
                                                               id="lblSubTotal"><?php echo $orderDetailArr['sub_total_amount']; ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!-- END TOTAL -->
                        </div>
                    </div>
                </div>
                <!-- END ITEM DETAILS -->
				
				<div class="control-group">
                    <label class="control-label" for="form-input-readonly">Material Grade</label>

                    <div class="controls">
                        <input type="text" class="span4" id="txtMaterialGrade" name="txtMaterialGrade"
                               value="<?php echo $orderDetailArr['material_grade']; ?>"/>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="form-input-readonly">Specification</label>

                    <div class="controls">
                        <input type="text" class="span4" id="txtSpec" name="txtSpec"
                               value="<?php echo $orderDetailArr['specification']; ?>"/>
                    </div>
                </div>
                <?php if($type == "inward"){ ?>
                <div class="control-group">
                    <label class="control-label" for="form-input-readonly">Delivery Date</label>
                    <div class="controls">
                        <input type="text" id="txtDeliveryDate" name="txtDeliveryDate" class="date-picker span4" placeholder="dd-mm-yyyy" value="<?php echo ($strAction == "E") ? $orderDetailArr['delivery_date'] : ""; ?>">
                        <span class="add-on">
                            <i class="icon-calendar"></i>
                        </span>
                    </div>
                </div>
				<?php } ?>
                <div class="control-group">
                    <label class="control-label" for="form-input-readonly">Note</label>

                    <div class="controls">
                        <textarea id="txtNote" class="span12"><?php echo $orderDetailArr['order_note']; ?></textarea>
                    </div>
                </div>

                <?php if($this->Page->getSession("strUserType") != 7) { ?>
                <!-- START BUTTON SECTION -->
                <div class="form-actions" align="left">
                    <button class="btn btn-info" type="submit" id="orderFormSubmit"><i class="icon-ok bigger-110"></i>Submit</button>
                    <button class="btn" type="reset"><i class="icon-undo bigger-110"></i>Reset</button>
                    <input type="hidden" name="hdnRefOrderId" id="hdnRefOrderId" value="<?php echo $refOrderId; ?>"/>
                    <input type="hidden" name="hdnOrderId" id="hdnOrderId" value="<?php echo $orderId; ?>"/>
                    <input type="hidden" name="hdnAction" id="hdnAction" value="<?php echo $strAction; ?>"/>
                    <input type="hidden" name="hdnType" id="hdnType" value="<?php echo $type; ?>"/>
                </div>
                <!-- END BUTTON SECTION -->
                <?php } ?>
            <?php
            }
            ?>
        </form>						
    </div>
</div>    
</div>

<!-- START Modal popup for Customer Form -->
<div id="cust-form" class="modal hide fade" tabindex="-1" style="position:absolute;">
	<form class="form-horizontal" id="frmVendor" name="frmVendor">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="blue bigger">Add Customer</h4>
		</div>

		<div class="modal-body overflow-visible">
			<div class="row-fluid">
				<div class="span10">
					<fieldset>
						<div class="control-group">
							<label for="form-field-1" class="control-label">Customer Name <span class="red">*</span></label>
							<div class="controls">
								<input type="text" id="txt_vendor_name" name="txt_vendor_name" class="required span6" />
							</div>
						</div>
						
						<div class="control-group">
							<label for="form-field-1" class="control-label">Company Name </label>
							<div class="controls">
								<input type="text" id="txt_vendor_comp_name" name="txt_vendor_comp_name" class="span6" />
							</div>
						</div>
						
						<div class="control-group">
							<label for="form-field-1" class="control-label">Phone No.</label>
							<div class="controls">
								<input type="text" id="txt_vendor_phone" name="txt_vendor_phone" class="span6" />
							</div>
						</div>
						
						<div class="control-group">
							<label for="form-field-1" class="control-label">Customer Email</label>
							<div class="controls">
								<input type="text" id="txt_vendor_email" name="txt_vendor_email" class="span6 isemail" />
							</div>
						</div>            
								   
						<div class="control-group">
							<label for="form-field-1" class="control-label">Customer Address</label>
							<div class="controls">                    
								<textarea  id="txt_vendor_address" name="txt_vendor_address" class="span6"></textarea>
							</div>
						</div>
						
						<div class="control-group">
							<label for="form-field-1" class="control-label">City</label>
							<div class="controls">
								<input type="text" id="txt_vendor_city" name="txt_vendor_city" class="span6" />
							</div>
						</div>
						
						<div class="control-group">
							<label for="form-field-1" class="control-label">State</label>
							<div class="controls">
								<input type="text" id="txt_vendor_state" name="txt_vendor_state" class="span6" />
							</div>
						</div>
						
						<div class="control-group">
							<label for="form-field-1" class="control-label">Country</label>
							<div class="controls">
								<input type="text" id="txt_vendor_country" name="txt_vendor_country" class="span6" />
							</div>
						</div>
						
						<div class="control-group">
							<label for="form-field-1" class="control-label">Postal Code</label>
							<div class="controls">
								<input type="text" id="txt_vendor_postal_code" name="txt_vendor_postal_code" class="span6" />
							</div>
						</div>
						
						<div class="control-group">
							<label for="form-field-1" class="control-label">Status</label>
							<div class="controls">
								<select class="required span6" name="slt_status" id="slt_status" >
									<?php echo $this->Page->generateComboByTable("combo_master","combo_key","combo_value",0,"where combo_case='STATUS' order by seq","",""); ?>
								</select>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-small btn-primary" id="saveCustomer">
				<i class="icon-ok"></i>
				Save
			</button>
			<button type="reset" class="btn btn-small"><i class="icon-undo"></i>Reset</button>
		</div>
	</form>
</div>
<!-- END Modal popup for Customer Form -->

<!-- START Modal popup for product Form -->
<div id="prod-form" class="modal hide fade" tabindex="-1" style="position:absolute;">
    <form class="form-horizontal" id="frmProduct" name="frmProduct">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="blue bigger">Add Product</h4>
        </div>

        <div class="modal-body overflow-visible">
            <div class="row-fluid">
                <div class="span12">
                    <fieldset>
                        <div class="control-group">
                            <label for="form-field-1" class="control-label">Product Name <span class="red">*</span></label>
                            <div class="controls">
                                <input type="text" id="txt_prod_name" name="txt_prod_name" class="required span8"/>
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="form-field-1" class="control-label">Product Description</label>
                            <div class="controls">
                                <textarea type="text" id="txt_prod_desc" name="txt_prod_desc" class="span8" value=""></textarea>
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="form-field-1" class="control-label">Status<span class="red">*</span></label>
                            <div class="controls">
                                <select class="required span8" name="slt_status" id="slt_status" >
                                    <?php echo $this->Page->generateComboByTable("combo_master","combo_key","combo_value",0," where combo_case='STATUS' order by seq","",""); ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="form-field-1" class="control-label">Vendor <span class="red">*</span></label>
                            <div class="controls">
                                <select id="selCustomer" name="selCustomer" class="">
                                    <?php echo $this->Page->generateComboByTable("vendor_master", "vendor_id", "vendor_name", "", "where status='ACTIVE' order by vendor_name", "", "Select Customer"); ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-small btn-primary" id="saveProduct">
                <i class="icon-ok"></i>
                Save
            </button>
            <button type="reset" class="btn btn-small"><i class="icon-undo"></i>Reset</button>
        </div>
    </form>
</div>
<!-- END Modal popup for product Form -->

<?php include(APPPATH.'views/bottom.php'); ?>

<script type="text/javascript">
$(document).ready(function(){
	$(".calc").blur();
	/* Add product for order entry */
	var row = '<?php echo $cnt; ?>';
	$(document).on('click', '#frmOrder .btn-add', function(e)
    {
		row++;
		e.preventDefault();
		var controlForm =  $('table.tbl-item-dtl tbody');
        var currentEntry = $('table.tbl-item-dtl tbody tr.entry-hidden');
        var newEntry = $(currentEntry.clone()).appendTo(controlForm);

		newEntry.removeClass("entry-hidden hide").addClass("entry");
        newEntry.attr('id',row);

		newEntry.find('#selProduct').addClass("required");
		newEntry.find('#selProduct').addClass("chzn-select");
		newEntry.find('#selProcess').addClass("required");
		newEntry.find('#selProcess').addClass("chzn-select");
		newEntry.find('#txtProductQty').addClass("required");
		newEntry.find('#txtProductWeight').addClass("required");

		newEntry.find('input').removeClass('border-red');
		newEntry.find('select').removeClass('border-red');

		newEntry.find('#selProduct').attr('id','selProduct'+row);
		newEntry.find('#selProcess').attr('id','selProcess'+row);
		newEntry.find('#txtProductQty').attr('id','txtProductQty'+row);
		newEntry.find('#txtProductWeight').attr('id','txtProductWeight'+row);

		newEntry.find('#prodTotalWeight').attr('id','prodTotalWeight'+row);

        $(".chzn-select").chosen();
    }).on('click', '.btn-remove', function(e)
    {
		e.preventDefault();
		$(this).parents('.entry:last').remove();
		$(".calc").blur();
		return false;
	});
	
	$(document).on("blur",".calc",function(){
		$(".text-error").remove();
		var trid = $(this).parent().parent().attr("id");
		var prodQty = $("#txtProductQty"+trid).val();


		var prodQty = (prodQty != "")?parseInt(prodQty):prodQty;
		var weightPerQty = $("#txtProductWeight"+trid).val();
		var weightPerQty = (weightPerQty != "")?parseFloat(weightPerQty):weightPerQty;
		
		var productTotalAmount = "";
		
		var totalQty = 0;
		var subTotal = 0;
		
		$("#prodTotalWeight"+trid).text("0.0");

		// Calculate Product total amount
		if(prodQty != "" && weightPerQty != "")
		{
			productTotalAmount = parseFloat(weightPerQty*prodQty);
		}
		
		// Set product total amount
		if(productTotalAmount != "")
		{
			$("#prodTotalWeight"+trid).text(productTotalAmount);
		}
		
		// calculate sub total
		$('table.tbl-item-dtl tbody tr label').each(function(){
			subTotal = parseFloat(subTotal) + parseFloat($(this).text());
		});

		// set subtotal
		$("#lblSubTotal").text(subTotal);
	});

	/* START Save Order */
	$(document).on("submit","#frmOrder",function(e){

        $.validator.setDefaults({ ignore: ":hidden:not(.chzn-select)" })
		e.preventDefault();
		var error = 0;
		$(".errmsg").remove();
		if(!submit_form(this))
		{
			error++;
		}

		var productArr = {};
		var chkProductArr = [];
		var prodError = 0;
		var qtyExceedError = 0;
		$('table.tbl-item-dtl tbody tr.entry').each(function(){
			var trid = $(this).attr('id');
			var opdid = $(this).attr('opd-id');
			var prod_id = $(this).find('select[name="selProduct"]').val();

            var inwardQty = null;
            var proceedQty = 0;
			if($(this).find('label[name="prodInwardQty"]').length > 0){
                inwardQty = parseInt($(this).find('label[name="prodInwardQty"]').text());
            }

            if($(this).find('label[name="prodProceedQty"]').length > 0){
                proceedQty = parseInt($(this).find('label[name="prodProceedQty"]').text());
            }

            var savedQty =  parseInt($(this).find('input[name="txtProductQty"]').attr('saved-qty'));

			productArr[trid] = {};
			productArr[trid]['opdId'] = opdid;
			productArr[trid]['prodId'] = $(this).find('select[name="selProduct"]').val();
			productArr[trid]['processIds'] = $(this).find('select[name="selProcess"]').val();
			productArr[trid]['prodQty'] = parseInt($(this).find('input[name="txtProductQty"]').val());
			productArr[trid]['weightPerQty'] = $(this).find('input[name="txtProductWeight"]').val();
			productArr[trid]['prodTotalWeight'] = $(this).find('label[name="prodTotalWeight"]').text();

            // Check Qty exceed or not
            if(inwardQty != null)
            {
                var totalQty =  proceedQty+productArr[trid]['prodQty']-savedQty;
                if(totalQty > inwardQty){
                    $("#txtProductQty"+trid).addClass("border-red");
                    qtyExceedError++;
                }
            }

			// Check duplicate product validation
			var found = jQuery.inArray(prod_id, chkProductArr);
			if (found >= 0) {
				$("#selProduct"+trid).addClass("border-red");
				prodError++;
			} else {
				chkProductArr[trid] = prod_id;
			}
		});

        if(qtyExceedError > 0)
        {
            alert("Outward Qty can not exceed Inward Qty");
            error++;
        }

		if(prodError > 0)
		{
			alert("Same product selected more than one time");
			error++;
		}

		if(error > 0)
		{
			return false;
		}

        $("#orderFormSubmit").attr("disabled", true);
		
		var param = {};
		param['hdnAction'] = $("#hdnAction").val();
		param['hdnOrderId'] = $("#hdnOrderId").val();
		param['hdnRefOrderId'] = $("#hdnRefOrderId").val();
		if($("#txtOrderNo").val() != undefined){
            param['txtOrderNo'] = $("#txtOrderNo").val();
        } else {
            param['txtOrderNo'] = null;
        }
		param['txtOrderRefNo'] = $("#txtOrderRefNo").val();
		param['selCustomer'] = $("#selCustomer").val();
		param['txtCustChallanNo'] = $("#txtCustChallanNo").val();
		param['txtMaterialGrade'] = $("#txtMaterialGrade").val();
		param['txtSpec'] = $("#txtSpec").val();
		param['txtOrderDate'] = $("#txtOrderDate").val();
        param['txtDeliveryDate'] = $("#txtDeliveryDate").val();
		param['txtNote'] = $("#txtNote").val();
		param['subTotal'] = $("#lblSubTotal").text();
		param['hdnType'] = $("#hdnType").val();
		param['productArr'] = productArr;

		$.ajax({
			type:"POST",
			data:param,
			url:"index.php?c=order&m=saveOrder",
			success:function(res)
			{
				if(res == "1")
				{
					alert("Order saved successfully");
					window.location.href="index.php?c=order&type="+ $("#hdnType").val();
				}
				else
				{
                    $("#orderFormSubmit").attr("disabled", false);
				    alert("Data not saved property please try again!"); return false;
				}
			}
		})
	});
	/* END Save Order */

	/* START Save Vendor */
	$("#frmVendor").submit(function(e){
		e.preventDefault();
		$(".errmsg").remove();
		if(!submit_form(this))
		{
			return false;
		}
		
		var param = $("#frmVendor").serialize();
		$.ajax({
			type:"POST",
			url:"index.php?c=vendor&m=ajaxSaveVendor",
			data:param,
			beforeSend:function(){
			},
			success:function(r){
				var res = $.trim(r).split("|");
				if(res[1] == "success")
				{
					var vendor_name = $("#txt_vendor_name").val();
					var option = "<option value="+res[0]+" selected='selected'>"+vendor_name+"</option>"
					$("#selCustomer").append(option);
					$('#frmVendor')[0].reset();
                    $('#selCustomer').trigger("liszt:updated");
					$("#cust-form").modal('hide');
				}
				else if(res[1] == "exist")
				{
					alert("Party already added");
				}
				else
				{
					alert(r);
				}
			}
		});
	});
	/* END Save Vendor */

    /* START Save Product */
    $("#frmProduct").submit(function(e){
        e.preventDefault();
        $(".errmsg").remove();
        if(!submit_form(this))
        {
            return false;
        }

        var param = $("#frmProduct").serialize();
        $.ajax({
            type:"POST",
            url:"index.php?c=product&m=ajaxSaveProduct",
            data:param,
            beforeSend:function(){
            },
            success:function(r){
                var res = $.trim(r).split("|");
                if(res[1] == "success")
                {
                    var product_name = $("#txt_prod_name").val();
                    var option = "<option value="+res[0]+">"+product_name+"</option>"
                    $(".product").append(option).val(res[0]);
                    $('#frmProduct')[0].reset();
                    $('.product').trigger("liszt:updated");
                    $("#prod-form").modal('hide');
                }
                else if(res[1] == "exist")
                {
                    alert("Product already added");
                }
                else
                {
                    alert(r);
                }
            }
        });
    });
    /* END Save Product */
});

/* START Get product details */
$(document).on("change","#selProduct0",function(e){
	e.preventDefault();
	
	if($("#hdnType").val() == "inward")
	{
		var param = {};
		param['prod_id'] = $(this).val();

		$.ajax({
			type:"GET",
			data:param,
			dataType: "json",
			url:"index.php?c=order&m=getProductDetails",
			success:function(res)
			{
				$("#txtMaterialGrade").val(res.prod_grade);
				$("#txtProductWeight0").val(res.prod_weight);
				$("#txtSpec").val(res.prod_specification);
				
				$(".calc").blur();
			}
		});
	}
});
/* END Get product details */


function loadOutwardChallanForm() {
    var refOrderNo = $("#txtOrderRefNo").val();

    if(refOrderNo){
        window.location.href = window.location.href+"&refOrderNo="+refOrderNo;
    } else {
        alert("Please enter Ref Inward challan No"); return false;
    }
}
</script>