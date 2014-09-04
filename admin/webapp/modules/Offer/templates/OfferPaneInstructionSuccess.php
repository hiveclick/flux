<?php
    $offer = $this->getContext()->getRequest()->getAttribute("offer", array());
    $campaigns = $this->getContext()->getRequest()->getAttribute("campaigns", array());
?>
<div class="help-block">Use this page to generate a tracking url for a campaign or a tracking pixel for firing events</div>
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

<br/>
<form class="form-horizontal" name="offer_instructions_form" method="GET" action="" autocomplete="off" role="form">
    <div class="form-group">
        <label class="col-sm-2 control-label" for="example_url">Example Posting URL</label>
        <div class="col-sm-10">
            <textarea id="example_url" rows="5" name="example_url" class="form-control"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="example_pixel">Example Tracking Pixel</label>
        <div class="col-sm-10">
            <textarea id="example_pixel" rows="5" name="example_pixel" class="form-control"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="example_pixel">Example Analytic Pixel</label>
        <div class="col-sm-10">
            <pre id="example_analytic_pixel">&lt;!-- Place this pixel at the bottom of all your pages for tracking --&gt;
&lt;script type="text/javascript"&gt;
    var _op = _op || [];
    _op.push(['_trackPageView']);
    (function() {
    var op = document.createElement('script');
    op.type = 'text/javascript';
    op.async = 'true';
    op.src = ('https:' == document.location.protocal ? 'https://www' : 'http://www') + '.<?php echo defined('MO_ANALYTIC_DOMAIN') ? MO_ANALYTIC_DOMAIN : substr($_SERVER['SERVER_NAME'], strpos($_SERVER['SERVER_NAME'], '.') + 1) ?>/op.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(op, s);
    })();
&lt;/script&gt;</pre>
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
                    <select name="posting_url_client" class="form-control posting_url_change">
                    <?php
                        /* @var $campaign \Gun\Campaign */ 
                        foreach($campaigns AS $campaign) { 
                    ?>
                        <option value="<?php echo $campaign->getOfferId() ?>,<?php echo $campaign->getClientId() ?>,<?php echo $campaign->getId() ?>"><?php echo $campaign->getClient()->getName() . ' &ndash; ' . $offer->getName() ?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="btn-group" data-toggle="buttons">
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
    }).on('keyup', function(e) {
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
        var offer_client_campaign_pair = $('[name=posting_url_client]').val().split(",");
        //posting_params[<?php echo json_encode(\Gun\DataField::DATA_FIELD_REF_OFFER_ID); ?>] = offer_client_campaign_pair[0];
    	//posting_params[<?php echo json_encode(\Gun\DataField::DATA_FIELD_REF_CLIENT_ID); ?>] = offer_client_campaign_pair[1];
    	posting_params[<?php echo json_encode(\Gun\DataField::DATA_FIELD_REF_CAMPAIGN_KEY); ?>] = offer_client_campaign_pair[2];
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
    var entire_pixel = '<?php echo (defined("MO_REALTIME_URL") ? MO_REALTIME_URL : "") . '/p?'; ?>';
    $('[name=example_url]').val(entire_url + query_string);
    $('[name=example_pixel]').val('<img src="' + entire_pixel + query_string + '" border="0" />');
}
//-->
</script>