<?php
    /* @var $split Gun\Split */
    $split = $this->getContext()->getRequest()->getAttribute("split", array());
    $offers = $this->getContext()->getRequest()->getAttribute("offers", array());
    $verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
    $domain_groups = $this->getContext()->getRequest()->getAttribute("domain_groups", array());
    $data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div id="header">
   <h2><a href="/export/split-search">Splits</a> <small>New Split</small></h2>
</div>
<div class="help-block">Splits define rules that determine how and when an export receives data</div>
<br/>
<div id="tab-content-container" class="tab-content">
    <form class="form-horizontal" name="split_form" method="POST" action="" autocomplete="off" role="form">
        <input type="hidden" name="status" value="<?php echo \Gun\Split::SPLIT_STATUS_ACTIVE ?>" />

        <div class="form-group">
            <label class="col-md-2 control-label" for="name">Name</label>
            <div class="col-md-10">
                <input type="text" id="name" name="name" class="form-control" placeholder="Name" required value="<?php echo $split->getName() ?>" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label" for="name">Description</label>
            <div class="col-md-10">
                <textarea name="description" id="description" class="form-control" placeholder="Enter Description..." required><?php echo $split->getDescription() ?></textarea>
            </div>
        </div>

        <div class="col-md-offset-2">
            <hr />
            <div class="help-block">Select filters below to define how this split will run</div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label" for="vertical_id">Verticals</label>
            <div class="col-md-10">
                <select class="form-control" name="vertical_id[]" id="vertical_id" multiple required placeholder="all verticals">
                    <?php foreach($verticals AS $vertical) { ?>
                        <option value="<?php echo $vertical->getId(); ?>"<?php echo in_array($vertical->getId(), $split->getVerticalId()) ? ' selected="selected"' : ''; ?>><?php echo $vertical->getName() ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label" for="offer_id ">Offers</label>
            <div class="col-md-10">
                <select class="form-control" name="offer_id[]" id="offer_id" required multiple placeholder="all offers">
                    <?php foreach($offers AS $offer) { ?>
                        <option value="<?php echo $offer->getId(); ?>"<?php echo in_array($offer->getId(), $split->getOfferId()) ? ' selected="selected"' : ''; ?>><?php echo $offer->getName() ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label" for="domain_id">Domains</label>
            <div class="col-md-10">
                <select class="form-control" name="domain_group_id[]" id="domain_group_id" required multiple placeholder="all domains">
                    <?php foreach($domain_groups AS $domain_group) { ?>
                        <option value="<?php echo $domain_group->getId(); ?>"<?php echo in_array($domain_group->getId(), $split->getDomainGroupId()) ? ' selected="selected"' : ''; ?>><?php echo $domain_group->getName() ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label" for="domain_id ">Required Fields</label>
            <div class="col-md-10">
                <select class="form-control" name="data_field_id[]" id="data_field_id" required multiple placeholder="no fields required">
                    <?php foreach($data_fields AS $data_field) { ?>
                        <option value="<?php echo $data_field->getId(); ?>"<?php echo in_array($data_field->getId(), $split->getDataFieldId()) ? ' selected="selected"' : ''; ?>><?php echo $data_field->getName() ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <input type="submit" name="__save" class="btn btn-success" value="Save" />
            </div>
        </div>
    </form>
</div>
<script>
//<!--
$(document).ready(function() {
    $('#vertical_id').selectize();
    $('#domain_group_id').selectize();
    $('#offer_id').selectize();
    $('#data_field_id').selectize();

    $('#source').change(function() {
        if ($('#source').val() == '1') {
            $('#vertical_source').removeClass('hidden');
            $('#offer_source').addClass('hidden');

            $('#vertical_id').prop('disabled', '');
            $('#offer_id').prop('disabled','disabled');
        } else {
            $('#vertical_source').addClass('hidden');
            $('#offer_source').removeClass('hidden');

            $('#vertical_id').prop('disabled','disabled');
            $('#offer_id').prop('disabled', '');
        }
    });
});
//-->
</script>
