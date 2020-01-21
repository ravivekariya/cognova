<?php include(APPPATH.'views/top.php'); ?>
<?php
$this->load->helper('form');
$attributes = array('class' => 'frm_add_record form-horizontal', 'id' => 'frm_add_module', 'name' => 'frm_add_module');
echo form_open('c=setting&m=saveModule', $attributes);
?>
<div class="page-header position-relative">
    <h1>Add Module</h1>
</div>

<input type="hidden" name="action" value="<?php echo $strAction; ?>" id="action"/>
<input type="hidden" name="module_id" value="<?php echo $id; ?>" id="module_id" />
<input type="hidden" id="txt_counter" name="txt_counter" value="0" />
<input type="hidden" id="from_page" name="from_page" value="<?php echo $from_page; ?>" />

<div class="row-fluid" id="printFrmDiv">
    <div class="span10">
        <fieldset>
            <div class="control-group">
                <label for="form-field-1" class="control-label">Module Name <span class="red">*</span></label>
                <div class="controls">
                    <input type="text" id="txt_module_name" name="txt_module_name" class="required span6" value="<?php echo $rsEdit->module_name; ?>" />
                </div>
            </div>
            
            <div class="control-group">
                <label for="form-field-1" class="control-label">Panel Id <span class="red">*</span></label>
                <div class="controls">
                    <select class="required span6" name="slt_panel_id" id="slt_panel_id" >
                    	<?php echo $this->Page->generateComboByTable("panel_master","panel_id","panel_name","","",$rsEdit->panel_id,"Select Panel"); ?>
                    </select>
                </div>
            </div>
            
            <div class="control-group">
                <label for="form-field-1" class="control-label">Module Url<span class="red">*</span></label>
                <div class="controls">
                    <input type="text" id="txt_module_url" name="txt_module_url" class="span6 required" value="<?php echo $rsEdit->module_url; ?>" />
                </div>
            </div>
            <div class="control-group">
                <label for="form-field-1" class="control-label">Sequence<span class="red">*</span></label>
                <div class="controls">
                    <input type="text" id="txt_seq" name="txt_seq" class="span6 required" value="<?php echo $rsEdit->seq; ?>" />
                </div>
            </div>
            <div class="control-group">
                <label for="form-field-1" class="control-label">Menu Right Button<span class="red">*</span></label>
                <div class="controls">
                    <!--<input type="checkbox" id="is_right_button" name="is_right_button" class="span1" value="is_right_button" />-->
                    <?php  $is_right_button = $rsEdit->is_right_button ;?>
                    <select class="span2" name="slt_is_right_button" id="slt_is_right_button">
                    	<option value="0" <?php if($is_right_button == 0){ echo "selected"; }?>>No</option>
                        <option value="1" <?php if($is_right_button == 1){ echo "selected"; }?>>Yes</option>
                    </select>
                    <span id="row_dim">
                    <input type="text" id="txt_right_button_link" name="txt_right_button_link" placeholder="Right Button URL" class="span4 required" value="<?php echo $rsEdit->right_button_link; ?>" />
                    </span>
                </div>
            </div>
            <div class="control-group">
                <label for="form-field-1" class="control-label">Status<span class="red">*</span></label>
                <div class="controls">
                	<select class="required span6" name="slt_status" id="slt_status" >
                    	<?php echo $this->Page->generateComboByTable("combo_master","combo_key","combo_value",0,"where combo_case='STATUS' order by seq",$rsEdit->status,""); ?>
                    </select>
                </div>
            </div>
            
            <div class="control-group non-printable">
                <div class="controls">
                    <input type="submit" class="btn btn-primary btn-small" value="Save" onclick="return submit_form(this.form);">
                    <input type="button" class="btn btn-primary btn-small" value="Cancel" onclick="window.history.back()" >
                </div>
            </div>
        </fieldset>
    </div>
</div>

<?php echo form_close(); ?>

<?php include(APPPATH.'views/bottom.php'); ?>

<script type="text/javascript">
$(document).ready(function(){
	$(function() {
		$('#row_dim').hide(); 
		$('#slt_is_right_button').change(function(){
			if($('#slt_is_right_button').val() == '1') {
				$('#row_dim').show();
				if(!$("#txt_right_button_link").hasClass('required'))
					$("#txt_right_button_link").addClass('required')
			} else {
				$('#row_dim').hide();
				if($("#txt_right_button_link").hasClass('required'))
					$("#txt_right_button_link").removeClass('required')
			} 
		});
		$("#slt_is_right_button").change();
	});
});
</script>
