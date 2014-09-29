<?php
    /* @var $datafield Flux\DataField */
    $dataField = $this->getContext()->getRequest()->getAttribute("datafield", array());
?>
<div id="header">
   <h2><a href="/admin/data-field-search">Data Fields</a> <small>New Data Field</small></h2>
</div>
<div class="help-block">Data Fields define which data can be collected on offer pages</div>
<br/>
<form class="form-horizontal" name="import_form" method="POST" action="" autocomplete="off" role="form">
    <input type="hidden" name="status" value="<?php echo \Flux\DataField::DATA_FIELD_STATUS_ACTIVE ?>" />
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
        <div class="col-sm-10">
            <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $dataField->getName() ?>" />
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="description">Description</label>
        <div class="col-sm-10">
            <textarea type="text" id="description" name="description" class="form-control" required placeholder="Enter description..."><?php echo $dataField->getDescription() ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="key_name">Key Name</label>
        <div class="col-sm-10">
            <input type="text" id="key_name" name="key_name" class="form-control" required placeholder="Enter unique key name" value="<?php echo $dataField->getKeyName() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="request_name">Request Name</label>
        <div class="col-sm-10">
            <input type="text" id="request_name" name="request_name" class="form-control" required placeholder="Request Name" value="<?php echo implode(",", $dataField->getRequestName()) ?>" />
        </div>
    </div>

     <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="request_name">Tags</label>
        <div class="col-sm-10">
            <input type="text" id="tags" name="tags" class="form-control" placeholder="Tags" value="<?php echo implode(",", $dataField->getTags()) ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="access_type">Access Type</label>
        <div class="col-sm-10">
            <?php if ($dataField->isAccessTypeSystem() === true) { ?>
            <select class="form-control" name="access_type" id="access_type" disabled placeholder="Access Type">
                <?php foreach(\Flux\DataField::retrieveAccessTypes() AS $access_type_id => $access_type_name) { ?>
                <option value="<?php echo $access_type_id; ?>"<?php echo $dataField->getAccessType() == $access_type_id ? ' selected' : ''; ?>><?php echo $access_type_name; ?></option>
                <?php } ?>
            </select>
            <?php } else { ?>
            <select class="form-control" name="access_type" id="access_type" required placeholder="Access Type">
                <?php foreach(\Flux\DataField::retrieveSettableAccessTypes() AS $access_type_id => $access_type_name) { ?>
                <option value="<?php echo $access_type_id; ?>"<?php echo $dataField->getAccessType() == $access_type_id ? ' selected' : ''; ?>><?php echo $access_type_name; ?></option>
                <?php } ?>
            </select>
            <?php } ?>
        </div>
    </div>
    
    <hr />

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="storage_type">Storage Type</label>
        <div class="col-sm-10">
            <?php if($dataField->isAccessTypeSystem() === true) { ?>
            <select class="form-control" name="storage_type" id="storage_type" disabled placeholder="Storage Type">
                <?php foreach(\Flux\DataField::retrieveStorageTypes() AS $storage_type_id => $storage_type_name) { ?>
                    <option value="<?php echo $storage_type_id; ?>"<?php echo $dataField->getStorageType() == $storage_type_id ? ' selected' : ''; ?>><?php echo $storage_type_name; ?></option>
                <?php } ?>
            </select>
            <?php } else { ?>
            <select class="form-control" name="storage_type" id="storage_type" required placeholder="Storage Type">
                <?php foreach(\Flux\DataField::retrieveSettableStorageTypes() AS $storage_type_id => $storage_type_name) { ?>
                <option value="<?php echo $storage_type_id; ?>"<?php echo $dataField->getStorageType() == $storage_type_id ? ' selected' : ''; ?>><?php echo $storage_type_name; ?></option>
                <?php } ?>
            </select>
            <?php } ?>
            <small class="help-block">Select where this field should be stored.  Tracking storage cannot be changed once set, Default storage changes, Event Storage is added</small>
        </div>
        
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="type">Data Type</label>
        <div class="col-sm-10">
            <?php if($dataField->isAccessTypeSystem() === true) { ?>
            <select class="form-control" name="type" id="type" disabled placeholder="Data Type">
                <?php foreach(\Flux\DataField::retrieveFieldTypes() AS $type_id => $type_name) { ?>
                <option value="<?php echo $type_id; ?>"<?php echo $dataField->getFieldType() == $type_id ? ' selected' : ''; ?>><?php echo $type_name; ?></option>
                <?php } ?>
            </select>
            <?php } else { ?>
            <select class="form-control" name="type" id="type" required placeholder="Data Type">
                <?php foreach(\Flux\DataField::retrieveSettableTypes() AS $type_id => $type_name) { ?>
                <option value="<?php echo $type_id; ?>"<?php echo $dataField->getFieldType() == $type_id ? ' selected' : ''; ?>><?php echo $type_name; ?></option>
                <?php } ?>
            </select>
            <?php } ?>
            <small class="help-block">Select what type of data should be expected.  This affects validation and how the data is stored</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="report_group">Report Group</label>
        <div class="col-sm-10">
            <input type="hidden" name="report_group" value="0" />
            <input type="checkbox" id="report_group" name="report_group" value="1" <?php echo $dataField->getReportGroup() == 1 ? 'checked' : ''; ?> />
            <small class="help-block">Select whether this data field can be grouped in reports such as conversion counts or click counts</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="pixel_allowed">Pixel Allowed</label>
        <div class="col-sm-10">
            <input type="hidden" name="pixel_allowed" value="0" />
            <input type="checkbox" id="pixel_allowed" name="pixel_allowed" value="1" <?php echo $dataField->getPixelAllowed() == 1 ? 'checked' : ''; ?> />
            <small class="help-block">Select whether this data field can be set via a pixel fire</small>
        </div>
    </div>

    <?php foreach($dataField->retrieveAdditionalParameters() AS $additionaParameterKey => $additionalParameter) { ?>
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="<?php echo $additionalParameter->retrieveValueHtml('field_name'); ?>"><?php echo $additionalParameter->retrieveValueHtml('name'); ?></label>
        <div class="col-sm-10">
            <input type="hidden" name="parameter_object[<?php echo $additionalParameter->retrieveValueHtml('field_name'); ?>][type]" value="<?php echo $additionalParameter->retrieveValue('type'); ?>" />
            <?php if($additionalParameter->retrieveValue('type') === dataFieldParameter::PARAMETER_TYPE_DATA_FIELD) { ?>
            <select class="form-control" name="parameter_object[<?php echo $additionalParameter->retrieveValueHtml('field_name'); ?>][value]" id="<?php echo $additionalParameter->retrieveValueHtml('field_name'); ?>"<?php echo $additionalParameter->retrieveValueHtml('required') ? ' required':''; ?> placeholder="<?php echo $additionalParameter->retrieveValueHtml('field_name'); ?>">
                <?php foreach(dataField::retrieveActiveDataFields() AS $activeDataField) { ?>
                    <option value="<?php echo $activeDataField->retrieveValueHtml('_id'); ?>"<?php echo $activeDataField->retrieveValue('_id') === $additionalParameter->retrieveValue('value') ? ' selected' : ''; ?>><?php echo $activeDataField->retrieveValueHtml('name'); ?></option>
                <?php } ?>
            </select>
            <?php } else { ?>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="__save" class="btn btn-success" value="Save" />
        </div>
    </div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#pixel_allowed').bootstrapSwitch({
    	onText: 'Yes',
    	offText: 'No'
    });

	$('#report_group').bootstrapSwitch({
    	onText: 'Yes',
    	offText: 'No'
    });
	
    $('#tags,#request_name').selectize({
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
});
//-->
</script>