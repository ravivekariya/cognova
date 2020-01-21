<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Order Management System</title>
        <link href="./css/bootstrap.min.css" rel="stylesheet" />
        <link href="./css/bootstrap-responsive.min.css" rel="stylesheet" />
        <!--ace styles-->
        <link rel="stylesheet" href="./css/ace.min.css" />
        <link rel="stylesheet" href="./css/ace-responsive.min.css" />
        <link rel="stylesheet" href="./css/style.main.css" />
        <link rel="stylesheet" href="./css/customize.css" />
		<!--fonts-->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" />
    </head>
	<body class="navbar">
		<div class="main-container container-fluid">
			<div class="page-content">
				<div class="" id="div-order-invoice">
					<!--PAGE CONTENT BEGINS-->
					<div class="row-fluid view-order">
						<div class="span12 offset1-remove">
							<div class="widget-box transparent invoice-box">
								<div class="widget-header widget-header-large">
									<h3 class="grey lighter pull-left position-relative">
										<!--<i class="icon-leaf green"></i>-->
										ORDER NO : <?php echo $orderDetailArr['order_no']; ?>
									</h3>

									<div class="widget-toolbar no-border invoice-info">
										<span class="invoice-info-label">Orde Date : </span>
										<span class="red"><?php echo $orderDetailArr['order_date']; ?></span>

										<br>
										<span class="invoice-info-label">Due Date : </span>
										<span class="blue"><?php echo $orderDetailArr['order_due_date']; ?></span>
									</div>
								</div>

								<div class="widget-body">
									<div class="widget-main padding-24">
										<div class="row-fluid">
											<div class="row-fluid">
												<div class="span6">
													<div class="row-fluid">
														<b>Akshar Lace</b>
													</div>

													<div class="row-fluid">
														<ul class="unstyled spaced">
															<li>
																<i class="icon-map-marker blue"></i>
																186, Seetanagar Society. Opposite Sundervan Society,
																<br/>
																<i style="margin-left:24px;"></i>Seetanagar to Bootbhavani road, Surat
																<br/>
																<i style="margin-left:24px;"></i>Gujarat, 394211, India
															</li>

															<li>
																<i class="icon-phone blue"></i>
																8460605614, 9727843721
															</li>

															<li>
																<i class="icon-envelope blue"></i>
																aksharlace1964@gmail.com
															</li>
															<li>
																<i class="icon-globe blue"></i>
																www.aksharlace.com
															</li>
														</ul>
													</div>
												</div><!--/span-->

												<div class="span6">
													<div class="row-fluid">
														<b><?php echo ucwords($vendorArr["vendor_comp_name"]); ?></b>
													</div>

													<div class="row-fluid">
														<ul class="unstyled spaced">
															<li>
																<i class="icon-caret-right green"></i>
																<?php echo ucwords($vendorArr["vendor_name"]); ?>
															</li>

															<li>
																<i class="icon-map-marker green"></i>
																<?php echo $vendorArr["vendor_address"]; ?>, <?php echo $vendorArr["vendor_city"]; ?><br/>
																<i style="margin-left:24px;"></i><?php echo $vendorArr["vendor_state"]; ?>, <?php echo $vendorArr["vendor_postal_code"]; ?>, <?php echo $vendorArr["vendor_country"]; ?>
															</li>

															<li>
																<i class="icon-phone green"></i>
																<?php echo $vendorArr["vendor_phone"]; ?>
															</li>

															<li>
																<i class="icon-envelope green"></i>
																<?php echo $vendorArr["vendor_email"]; ?>
															</li>
														</ul>
													</div>
												</div><!--/span-->
											</div><!--row-->

											<div class="space"></div>

											<div class="row-fluid">
												<table class="table table-striped table-bordered">
													<thead>
														<tr>
															<th class="center">#</th>
															<th>Item</th>
															<th class="hidden-phone">Quantity</th>
															<th class="hidden-480">Rate</th>
															<th>Amount</th>
														</tr>
													</thead>

													<tbody>
														<?php
														$cnt = 1;
														foreach($orderDetailArr['orderProductDetailsArr'] AS $prodRow)
														{
														?>
															<tr>
																<td class="center"><?php echo $cnt; ?></td>
																<td><?php echo $prodRow["prod_name"];  ?></td>
																<td><?php echo $prodRow['prod_qty']; ?></td>
																<td><?php echo $prodRow['price_per_qty']; ?></td>
																<td><?php echo $prodRow['prod_total_amount']; ?></td>
															</tr>
														<?php
															$cnt++;				
														}
														?>
													</tbody>
												</table>
											</div>

											<div class="hr hr8 hr-dotted"></div>

											<div class="row-fluid">
												<div class="span5 pull-left declaration">
													<b>Declaration</b>
													<ol>
														<li>Subject to Surat Jurisdiction.</li>
														<li>Goods Once Delivered Will Not be Taken Back or <br/>
														Replaced and no Refund Will be allowed. We are not <br/>
														responsible for any loss or damage in transit.
														</li>
														<li>Any Claims for quantity must be made within 7 Days.</li>
														<li>Payment Due date is 20th of Subsequent Month.</li>
														<li>Interest @18% Charged After the Due Date of the Bill.</li>
													</ol>
												</div>
												<div class="span4 pull-left">
													<b>Bank Detail</b>
												</div>
												<div class="span3">
													<div class="row-fluid">
														<div class="span12 pull-right">
															<h5 class="pull-right">
																<b>Total :</b> <i class="icon-inr"></i>
																<span class="red"> <?php echo $orderDetailArr['sub_total_amount']; ?></span>
															</h5>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span12 pull-right">
															<h5 class="pull-right">
																<b><i>C.GST (6%) :</i></b> <i class="icon-inr"></i>
																<span class="red"><?php echo $orderDetailArr['total_cgst']; ?></span>
															</h5>
														</div>
													</div>

													<div class="row-fluid">
														<div class="span12 pull-right">
															<h5 class="pull-right">
																<b><i>S.GST (6%) :</b></i> <i class="icon-inr"></i>
																<span class="red"><?php echo $orderDetailArr['total_sgst']; ?></span>
															</h5>
														</div>
													</div>

													<div class="row-fluid">
														<div class="span12 pull-right">
															<h5 class="pull-right">
																<b><i>GST (12%) :</i></b> <i class="icon-inr"></i>
																<span class="red"><?php echo $orderDetailArr['total_gst']; ?></span>
															</h5>
														</div>
													</div>
												</div>
											</div>
											<div class="hr hr8 hr-dotted"></div>
											<div class="row-fluid">
												<div class="span12 pull-right">
													<h5 class="pull-left">
														<b>INR <?php echo ucfirst($totalAmountInWords); ?></b>
													</h5>
													<h5 class="pull-right">
														<b>Final Amount :</b> <i class="icon-inr"></i>
														<span class="red"><?php echo $orderDetailArr['final_total_amount']; ?></span>
													</h5>
												</div>
											</div>

											<div class="space-6"></div>

											<div class="row-fluid">
												<div class="span12">
													<table id="sample-table-1" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th class="span6">
																	Customer's Seal and Signture
																</th>
																<th class="span6">For Akshar Lace</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<th class="span6" style="height:60px;">&nbsp;
																</th>
																<th class="span6">&nbsp;</th>
															</tr>
														</tbody>
													</table>
												</div>
											</div>

											<div class="row-fluid">
												<div class="span12 well">
													Thank you for choosing our products. We believe you will be satisfied by our services.
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--PAGE CONTENT ENDS-->
				</div><!--/.span-->
			</div><!-- page-content -->  
		</div><!-- main-container -->
		<script src="./js/jquery-2.0.3.min.js"></script>
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="./js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
			});
		</script>
	</body>
</html>
