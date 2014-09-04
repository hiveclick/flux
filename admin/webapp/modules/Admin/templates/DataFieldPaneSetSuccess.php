<?php
    /* @var ClientExportPaneExportSuccess.php \Gun\DataField */
    $data_field = $this->getContext()->getRequest()->getAttribute("data_field", array());
?>
<div class="help-block">These are the values that can be used in this data field.  Use these when adding data fields to a lead</div>
<br/>
<div class="form-group data-field-set-group-item" style="display:none;" id="dummy_data_field_set_div">
    <div class="col-sm-4">
        <input type="text" name="data_field_set[dummy_set_id][value]" class="form-control" value="" placeholder="enter value" />
        <div class="text-right"><small>0 leads total, 0 leads today</small></div>
    </div>
    <div class="col-sm-6">
        <textarea name="data_field_set[dummy_set_id][name]" class="form-control" placeholder="enter description"></textarea>
    </div>
    <div class="col-sm-2">
        <div>
            <button type="button" class="form-control btn btn-danger btn-remove-item">remove</button>
        </div>
    </div>
    
</div>

<form class="form-horizontal" id="data_field_set_form" name="data_field_set_form" method="PUT" action="/api" autocomplete="off" role="form">
    <input type="hidden" name="func" value="/admin/data-field-set" />
    <input type="hidden" name="_id" value="<?php echo $data_field->getId() ?>" />
    <div id="data_field_set_groups">
        <?php
            $counter = 0;
            /* @var $data_field_set \Gun\DataFieldSet */
            foreach($data_field->getDataFieldSet() AS $data_field_set) {
        ?>
        <div class="form-group data-field-set-group-item">
            <div class="col-sm-4">
                <input type="text" name="data_field_set[<?php echo $counter;?>][value]" class="form-control" value="<?php echo $data_field_set->getValue() ?>" placeholder="enter value" />
                <div class="text-right"><small><?php echo number_format($data_field_set->getLeadTotal(), 0, null, ',') ?> leads total, <?php echo number_format($data_field_set->getDailyTotal(), 0, null, ',') ?> leads today</small></div>
            </div>
            <div class="col-sm-6">
                <textarea name="data_field_set[<?php echo $counter;?>][name]" class="form-control" placeholder="enter description"><?php echo $data_field_set->getName() ?></textarea>
            </div>
            <div class="col-sm-2">
                <div>
                    <button type="button" class="form-control btn btn-danger btn-remove-item">remove</button>
                </div>
            </div>
        </div>
        <?php $counter++; ?>
        <?php } ?>
    </div>
    <hr />
    <div class="form-group row">
        <div class="col-md-offset-2 col-md-10">
            <button type="button" class="btn btn-info" id="add_set_btn"><span class="glyphicon glyphicon-plus"></span> Add Value</button>
            <input type="submit" name="__saveMapping" class="btn btn-success" value="Save Set" />
        </div>
    </div>
    <br />
</form>

<script>
//<!--
$(document).ready(function() {
	$('#data_field_set_form').form(function(data) {
        if (data.record) {
            $.rad.notify('Set Saved', 'These values have been saved to the data field');
        }
    },{keep_form:true});
	
	$('#add_set_btn').on('click', function() {
        var index_number = $('#data_field_set_groups > .data-field-set-group-item').length;
        var data_field_set_div = $('#dummy_data_field_set_div').clone();
        
        data_field_set_div.html(function(i, oldHTML) {
            oldHTML = oldHTML.replace(/dummy_set_id/g, index_number);
            return oldHTML;
        });
        $('#data_field_set_groups').append(data_field_set_div);
        data_field_set_div.show();
    });

    $('#data_field_set_groups').on('click', '.btn-remove-item', function() {
        $(this).closest('.form-group').remove();
    });
});
//-->
</script>