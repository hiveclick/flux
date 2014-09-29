<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
?>
<div class="form-group" style="display:none;" id="dummy_posting_url_dataField">
    <div class="col-sm-6">
        <select name="posting_url_dataField_name[]" class="form-control selectize">
            <optgroup label="Data Fields">
                <?php foreach(\Flux\DataField::retrieveActiveDataFields() AS $dataFieldId => $dataField) { 
                    $data_field_set = $dataField->getDataFieldSet();
                    array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\DataFieldSet) { $value = $value->toArray(); }});
                ?>
                    <?php if ($dataField->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
                        <option value="<?php echo $dataField->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $dataField->getName(), 'key_name' => $dataField->getKeyName(), 'description' => $dataField->getDescription(), 'data_field_set' => $data_field_set, 'request_names' => array_merge(array($dataField->getKeyName(), $dataField->getRequestName()))))) ?>"><?php echo $dataField->getName() ?> (<?php echo $dataField->getKeyName() ?>, <?php echo implode(", ", $dataField->getRequestName()) ?>)</option>
                    <?php } ?>
                <?php } ?>
            </optgroup>
            <optgroup label="Tracking">
                <?php foreach(\Flux\DataField::retrieveActiveDataFields() AS $dataFieldId => $dataField) { 
                        $data_field_set = $dataField->getDataFieldSet();
                        array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\DataFieldSet) { $value = $value->toArray(); }});
                ?>
                    <?php if ($dataField->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
                        <option value="<?php echo $dataField->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $dataField->getName(), 'key_name' => $dataField->getKeyName(), 'description' => $dataField->getDescription(), 'data_field_set' => $data_field_set, 'request_names' => array_merge(array($dataField->getKeyName(), $dataField->getRequestName()))))) ?>"><?php echo $dataField->getName() ?> (<?php echo $dataField->getKeyName() ?>, <?php echo implode(", ", $dataField->getRequestName()) ?>)</option>
                    <?php } ?>
                <?php } ?>
            </optgroup>
            <optgroup label="Events">
            <?php foreach(\Flux\DataField::retrieveActiveDataFields() AS $dataFieldId => $dataField) { 
                    $data_field_set = $dataField->getDataFieldSet();
                    array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\DataFieldSet) { $value = $value->toArray(); }});
            ?>
                <?php if ($dataField->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
                    <option value="<?php echo $dataField->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $dataField->getName(), 'key_name' => $dataField->getKeyName(), 'description' => $dataField->getDescription(), 'data_field_set' => $data_field_set, 'request_names' => array_merge(array($dataField->getKeyName(), $dataField->getRequestName()))))) ?>"><?php echo $dataField->getName() ?> (<?php echo $dataField->getKeyName() ?>, <?php echo implode(", ", $dataField->getRequestName()) ?>)</option>
                <?php  } ?>
            <?php } ?>
            </optgroup>
        </select>
    </div>
    <div class="col-sm-4">
        <textarea name="posting_url_dataField_value[]" class="form-control selectize-text" placeholder="Enter New Value" rows="3"></textarea>
    </div>
    <div class="col-sm-2">
        <button type="button" class="btn btn-sm btn-danger btn-remove-dataField">
            <span class="glyphicon glyphicon-minus"></span>
        </button>
    </div>
</div>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">Add/Modify Data</h4>
</div>
<form class="form-horizontal" id="lead_data_field_form" name="lead_data_field_form" method="POST" action="/api" autocomplete="off" role="form">
    <input type="hidden" name="func" value="/lead/lead-data-field" />
    <input type="hidden" name="_id" value="<?php echo $lead->getId() ?>" />
    <div class="modal-body">
        Use this form to add or modify data fields on this lead.  Simply click the button to add a data field and set its value.
        <p />
        <div id="dataField_posting_url_container"></div>
        <div class="clearfix" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-success btn-add-dataField"><span class="glyphicon glyphicon-plus"></span> Add Data Field</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form>

<script>
//<!--
$(document).ready(function() {
    var $selectize_options = {
		valueField: 'key_name',
        labelField: 'name',
        searchField: ['name', 'description', 'request_names'],
        dropdownWidthOffset: 150,
    	render: {
            item: function(item, escape) {
                return '<div>' +
                    '<span class="title">' + 
                    '<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
                    '</span>' +
                    '<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
                    '<ul class="meta"><li class="language">Tags:</li><li><span>' + escape(item.request_names ? item.request_names : '') + '</span></li></ul>' +
                '</div>';
            },
            option: function(item, escape) {
                return '<div>' +
                    '<span class="title">' + 
                    '<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
                    '</span>' +
                    '<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
                    '<ul class="meta"><li class="language">Tags:</li><li><span>' + escape(item.request_names ? item.request_names : '') + '</span></li></ul>' +
                '</div>';
            }
    	},
    	onChange: function(value) {
            if (!value.length) return;
            var select_text = $(this.$dropdown).closest('.form-group').find('.selectize-text')[0].selectize;
            var data = this.options[value];
            select_text.clearOptions();
            $(data.data_field_set).each(function(i, item) {
            	select_text.addOption(item);
            });
            select_text.refreshOptions();
        }
    };
	
    $('#lead_data_field_form').form(function(data) {
        $.rad.notify('Data Saved', 'The data fields have been saved to this lead.');
        $('#add-data-field-modal').modal('hide');
    },{keep_form:true});
	
	$('.btn-add-dataField').on('click', function() {
        var $dataFieldRow = $('#dummy_posting_url_dataField').clone(true);
        $('#dataField_posting_url_container').append($dataFieldRow);
        $dataFieldRow.find('.selectize').selectize($selectize_options);
        $dataFieldRow.find('.selectize-text').selectize({
        	valueField: 'value',
            labelField: 'name',
            searchField: ['name'],
            sortField: 'name',
            sortDirection: 'ASC',
            diacritics:true,
            create: true,
            createOnBlur: true
        });
        $dataFieldRow.show();
    });

    $('.btn-remove-dataField').on('click', function() {
        $(this).closest('.form-group').remove();
    });
});
//-->
</script>