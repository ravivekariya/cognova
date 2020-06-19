		
        </div><!-- page-content -->
        
    </div><!-- main-content -->

</div><!-- main-container container-fluid -->
    
<!-- Footer -->
<div class="container-fluid">
</div>
<!--/.container-fluid-->

</div>

<script src="./js/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
	if("ontouchend" in document) document.write("<script src='js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/fuelux/fuelux.wizard.min.js"></script>
<script src="./js/jquery.validate.min.js"></script>
<script src="./js/select2.min.js"></script>
<script src="./js/bootbox.min.js"></script>
<script src="./js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="./js/jquery.ui.touch-punch.min.js"></script>
<script src="./js/jquery.slimscroll.min.js"></script>
<script src="./js/jquery.easy-pie-chart.min.js"></script>
<script src="./js/jquery.sparkline.min.js"></script>
<!--<script src="./js/flot/jquery.flot.min.js"></script>
<script src="./js/flot/jquery.flot.pie.min.js"></script>
<script src="./js/flot/jquery.flot.resize.min.js"></script>-->
<script src="./js/jquery.maskedinput.min.js" language="javascript"></script>
<script src="./js/date-time/bootstrap-datepicker.min.js"></script>
<script src="./js/date-time/bootstrap-timepicker.min.js"></script>
<script src="./js/jquery.form.js"></script>
<!--<script src="./js/jquery.dataTables.min.js"></script>-->
<script src="./js/jquery.dataTables.1.10.19.min.js"></script>
<script src="./js/jquery.dataTables.bootstrap.js"></script>
<script src="./js/jquery.popupoverlay.js"></script>

<!-- chosen combobox -->
<script src="./js/chosen.jquery.min.js"></script>
        
<!--ace scripts-->
<script src="./js/ace-elements.min.js"></script>
<script src="./js/ace.min.js"></script>
<script src="./js/common.js" language="javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("change","#btnSelProd",function(){
			$("#divInventoryRes").html('');
			$("#divInventoryStageRes").html('');
			var prod_id = $(this).val();
			if(prod_id == 0 || prod_id == "")
			{
				return false;
			}
			var param = {};
			param['prod_id'] = prod_id;
			param['json'] = 1;
			param['stage_in_process'] = 1;
			param['stage_in_stock'] = 1;
			$.ajax({
				type:"POST",
				data:param,
				dataType: "json",
				url:"index.php?c=inventory&m=getInventoryDetail",
				success:function(res)
				{
					//return false;
					var html = "";
					var stageHtml = "";
					html += '<div class="infobox infobox-green infobox-custom"><div class="infobox-data infobox-data-custom"><span class="infobox-data-number">'+parseInt(res.in_stock)+'</span><div class="infobox-content">In Stock</div></div></div>';
                    
                    html += '<div class="infobox infobox-blue infobox-custom"><div class="infobox-data infobox-data-custom"><span class="infobox-data-number">'+parseInt(res.in_process)+'</span><div class="infobox-content">In Process</div></div></div>';

					if(Object.keys(res.stage_inventory_process).length > 0)
					{
						stageHtml += '<div class="widget-box transparent"><div class="widget-header"><h5 class="bigger lighter">Stage wise In Process</h5></div><div class="widget-body"><div class="widget-main no-padding"><ul class="unstyled list-striped pricing-table-header">';

						$.each(res.stage_inventory_process,function(key,value){
							stageHtml += '<li>'+value.ps_name+' <span class="span-inv-box">'+value.total_qty+'</span></li>'
						});

						stageHtml += '</ul></div></div></div>';
					}

					if(Object.keys(res.stage_inventory_stock).length > 0)
					{
						stageHtml += '<div class="widget-box transparent"><div class="widget-header"><h5 class="bigger lighter">Stage wise In Stock</h5></div><div class="widget-body"><div class="widget-main no-padding"><ul class="unstyled list-striped pricing-table-header">';

						$.each(res.stage_inventory_stock,function(key,value){
							stageHtml += '<li>'+value.ps_name+' <span class="span-inv-box">'+value.total_qty+'</span></li>'
						});

						stageHtml += '</ul></div></div></div>';
					}


					$("#divInventoryRes").html(html);
					$("#divInventoryStageRes").html(stageHtml);
				}
			});
		});

		// chnage product dropdown according to selected type E.g Product or Component
		$(document).on("click","#radio_prod_typ input[type='radio']",function(){
			var param = {};
			param['prod_type'] = $(this).val();
			$.ajax({
				type:"POST",
				data:param,
				url:"index.php?c=commonajax&m=getProductComboByType",
				success:function(res)
				{
					$("#btnSelProd").html(res);
					$("#btnSelProd").trigger("liszt:updated");
				}
			});
		});
	});
</script>
</body>
</html>