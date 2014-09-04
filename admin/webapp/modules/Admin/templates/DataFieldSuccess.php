<?php
    /* @var $datafield Gun\DataField */
    $dataField = $this->getContext()->getRequest()->getAttribute("datafield", array());
?>
<div id="header">
    <div class="pull-right visible-xs">
        <button class="navbar-toggle collapsed visible-xs" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <h2><a href="/admin/data-field-search">Data Fields</a> <small><?php echo $dataField->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse" class="navbar-collapse collapse">
    <ul id="datafield-tabs" class="nav nav-pills">
        <li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Data Field</a></li>
        <li><a id="tabs-a-set" href="#tabs-set" data-toggle="tab" data-url="/admin/data-field-pane-set?_id=<?php echo $dataField->getId() ?>">Set</a></li>
    </ul>
</div>
<div id="tab-content-container" class="tab-content">
   <div id="tabs-main" class="tab-pane active">
        <div class="help-block">Define how this data field is linked to a request parameter for tracking</div>
        <br />
        <form class="form-horizontal" name="import_form" method="POST" action="" autocomplete="off" role="form">
             <input type="hidden" name="_id" value="<?php echo $dataField->getId() ?>" />

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
                    <select class="form-control" name="access_type" id="access_type" required placeholder="Access Type">
                        <?php foreach(\Gun\DataField::retrieveSettableAccessTypes() AS $access_type_id => $access_type_name) { ?>
                        <option value="<?php echo $access_type_id; ?>"<?php echo $dataField->getAccessType() == $access_type_id ? ' selected' : ''; ?>><?php echo $access_type_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
                <div class="col-sm-10">
                    <select class="form-control" name="status" id="status" required placeholder="Status">
                        <?php foreach(\Gun\DataField::retrieveStatuses() AS $status_id => $status_name) { ?>
                        <option value="<?php echo $status_id; ?>"<?php echo $dataField->getStatus() == $status_id ? ' selected' : ''; ?>><?php echo $status_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <hr />
            <?php if ($dataField->isAccessTypeSystem()) { ?>
                <div class="alert alert-danger">
                    This is a <code>System</code> data field and should not be changed.  Do so at your own risk.
                </div>
            <?php } ?>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="storage_type">Storage Type</label>
                <div class="col-sm-10">
                    <select class="form-control" name="storage_type" id="storage_type" required placeholder="Storage Type">
                        <?php foreach(\Gun\DataField::retrieveSettableStorageTypes() AS $storage_type_id => $storage_type_name) { ?>
                        <option value="<?php echo $storage_type_id; ?>"<?php echo $dataField->getStorageType() == $storage_type_id ? ' selected' : ''; ?>><?php echo $storage_type_name; ?></option>
                        <?php } ?>
                    </select>
                    <small class="help-block">Select where this field should be stored.  Tracking storage cannot be changed once set, Default storage changes, Event Storage is added</small>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="field_type">Data Type</label>
                <div class="col-sm-10">
                    <select class="form-control" name="field_type" id="field_type" required  placeholder="Data Type">
                        <?php foreach(\Gun\DataField::retrieveSettableTypes() AS $type_id => $type_name) { ?>
                            <option value="<?php echo $type_id; ?>"<?php echo $dataField->getFieldType() == $type_id ? ' selected' : ''; ?>><?php echo $type_name; ?></option>
                        <?php } ?>
                    </select>
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
                    <label class="col-sm-2 control-label hidden-xs" for="<?php echo $additionalParameter->getFieldName() ?>"><?php echo $additionalParameter->getName() ?></label>
                    <div class="col-sm-10">
                        <input type="hidden" name="parameters[<?php echo $additionalParameter->getFieldName() ?>][type]" value="<?php echo $additionalParameter->getType() ?>" />
                        <?php if ($additionalParameter->getType() === \Gun\DataFieldParameter::PARAMETER_TYPE_DATA_FIELD) { ?>
                            <select class="form-control" name="parameters[<?php echo $additionalParameter->getFieldName() ?>][field_value]" id="<?php echo $additionalParameter->getFieldName() ?>"<?php echo $additionalParameter->getRequired() ? ' required':''; ?> placeholder="<?php echo $additionalParameter->getFieldName() ?>">
                                <?php foreach(\Gun\DataField::retrieveActiveDataFields() AS $activeDataField) { ?>
                                    <option value="<?php echo $activeDataField->getId() ?>" <?php echo $activeDataField->getId() == $dataField->retrieveParameterValue($additionalParameter->getFieldName()) ? 'selected' : '' ?>><?php echo $activeDataField->getName() ?></option>
                                <?php } ?>
                            </select>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="__save" class="btn btn-success" value="Save" />
                    <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Data Field" />
                </div>
            </div>
        </form>
    </div>
    <div id="tabs-set" class="tab-pane"></div>
</div>
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
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        e.preventDefault();
        var hash = this.hash;
        if ($(this).attr("data-url")) {
            // only load the page the first time
            if ($(hash).html() == '') {
                // ajax load from data-url
                $(hash).load($(this).attr("data-url"));
            }
        }
    }).on('show.bs.tab', function (e) {
    	try {
       	   sessionStorage.setItem(localTabStorageName, $(e.target).attr('href'));
        } catch (err) { }
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

    $('#btn_delete').click(function() {
        if (confirm('Are you sure you want to delete this data field and completely remove it from the system?')) {
            $.rad.del('/api', { func: '/admin/data-field/<?php echo $dataField->getId() ?>' }, function(data) {
                $.rad.notify('Data Field Removed', 'This data field has been removed from the system.');
            });
        }
    });

    // Store the last clicked tab so it can be loaded on page refreshes
    var localTabStorageName = <?php echo json_encode('datafield_tab_' . $dataField->getId()); ?>;
    var lastTab = sessionStorage.getItem(localTabStorageName);
    if (lastTab) {
        $('a[href='+lastTab+']').tab('show');
    } else {
        $('ul.nav-pills a:first').tab('show');
    }
});
//-->
</script>