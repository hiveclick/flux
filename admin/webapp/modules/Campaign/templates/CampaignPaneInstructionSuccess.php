<?php
    $campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
?>
<div class="form-group" style="display:none;" id="dummy_posting_url_dataField">
    <div class="col-sm-5">
        <select name="posting_url_dataField_name" class="form-control posting_url_change">
            <optgroup label="Events">
            <?php foreach(\Gun\DataField::retrieveActiveDataFields() AS $dataFieldId => $dataField) { ?>
                <?php if ($dataField->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
                    <option value="<?php echo $dataField->getKeyName() ?>"><?php echo $dataField->getName() ?> (<?php echo $dataField->getKeyName() ?>, <?php echo implode(", ", $dataField->getRequestName()) ?>)</option>
                <?php  } ?>
            <?php } ?>
            </optgroup>
            <optgroup label="Data Fields">
                <?php foreach(\Gun\DataField::retrieveActiveDataFields() AS $dataFieldId => $dataField) { ?>
                    <?php if ($dataField->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
                        <option value="<?php echo $dataField->getKeyName() ?>"><?php echo $dataField->getName() ?> (<?php echo $dataField->getKeyName() ?>, <?php echo implode(", ", $dataField->getRequestName()) ?>)</option>
                    <?php } ?>
                <?php } ?>
            </optgroup>
            <optgroup label="Tracking">
                <?php foreach(\Gun\DataField::retrieveActiveDataFields() AS $dataFieldId => $dataField) { ?>
                    <?php if ($dataField->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
                        <option value="<?php echo $dataField->getKeyName() ?>"><?php echo $dataField->getName() ?> (<?php echo $dataField->getKeyName() ?>, <?php echo implode(", ", $dataField->getRequestName()) ?>)</option>
                    <?php } ?>
                <?php } ?>
            </optgroup>
        </select>
    </div>
    <div class="col-sm-5">
        <input type="text" name="posting_url_dataField_value" class="form-control posting_url_change" placeholder="Value" />
    </div>
    <div class="col-sm-2">
        <button type="button" class="btn btn-danger btn-remove-dataField">
            <span class="glyphicon glyphicon-minus"></span> Remove
        </button>
    </div>
</div>

<div class="help-block">Use this form to generate a unique tracking link that you can use</div>
<br />
<form class="form-horizontal" name="offer_instructions_form" method="GET" action="" autocomplete="off" role="form">
    <input type="hidden" name="posting_url_client" class="form-control posting_url_change" value="<?php echo $campaign->getId() ?>">
    <div class="form-group">
        <label class="col-sm-2 control-label" for="example_url">Example Posting URL</label>
        <div class="col-sm-10">
            <textarea type="text" id="example_url" rows="5" name="example_url" class="form-control" /></textarea>
        </div>
    </div>
    <div class="row">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <div class="row">
                <div class="col-sm-12">
                    <div class="help-block">
                        Use the form controls below to create an example Posting URL
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="btn-group has-feedback" data-toggle="buttons">
                        <label class="btn btn-info" title="Whether or not the url will function as a redirect or return json"><input type="checkbox" name="posting_url_save" value="1" class="posting_url_change" /> Save</label>
                        <label class="btn btn-info" title="Whether or not the url will clear any existing cookies, creating a new lead automatically"><input type="checkbox" name="posting_url_clear" value="1" class="posting_url_change" /> Clear</label>
                        <label class="btn btn-info" title="If the posting URL will be used as a pixel, the format of the link is different"><input type="checkbox" name="posting_url_pixel" value="1" class="posting_url_change" /> Pixel</label>
                    </div>
                    <button type="button" class="btn btn-success btn-add-dataField">
                        <span class="glyphicon glyphicon-plus"></span> Add Data Field
                    </button>
                </div>
            </div>
            <div id="dataField_posting_url_container">
            </div>
        </div>
    </div>
</form>
<script>
//<!--
$(document).ready(function() {
    $('.posting_url_change').on('change', function(e) {
        e.preventDefault();
        buildPostingUrl();
    });

    $('.btn-add-dataField').on('click', function() {
        var $dataFieldRow = $('#dummy_posting_url_dataField').clone(true);
        $('#dataField_posting_url_container').append($dataFieldRow);
        $dataFieldRow.show();
        buildPostingUrl();
    });

    $('.btn-remove-dataField').on('click', function() {
        $(this).closest('.form-group').remove();
        buildPostingUrl();
    });

    buildPostingUrl();

});

function buildPostingUrl() {
    var posting_params = {};

    if($('[name=posting_url_pixel]').is(':checked')) {
        posting_params[<?php echo json_encode(\Gun\DataField::DATA_FIELD_AGG_CKID); ?>] = 'REPLACE_WITH_TRACKING_ID';
    } else {
        posting_params[<?php echo json_encode(\Gun\DataField::DATA_FIELD_REF_CAMPAIGN_KEY); ?>] = $('[name=posting_url_client]').val();
    }

    if($('[name=posting_url_save]').is(':checked')) {
        posting_params[<?php echo json_encode(\Gun\Lead::LEAD_SAVE_FLAG); ?>] = 1;
    }

    if($('[name=posting_url_clear]').is(':checked')) {
        posting_params[<?php echo json_encode(\Gun\Lead::LEAD_CLEAR_FLAG); ?>] = 1;
    }

    $('#dataField_posting_url_container .form-group').each(function() {
        var dataFieldName = $(this).find('[name=posting_url_dataField_name]').val();
        var dataFieldValue = $(this).find('[name=posting_url_dataField_value]').val();
        posting_params[dataFieldName] = dataFieldValue;
    });

    var query_string = $.param(posting_params);
    var entire_url = '<?php echo (defined("MO_REALTIME_URL") ? MO_REALTIME_URL : "") . '/r?'; ?>';
    $('[name=example_url]').val(entire_url + query_string);
}
//-->
</script>