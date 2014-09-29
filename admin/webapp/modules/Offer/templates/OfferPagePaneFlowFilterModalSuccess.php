<?php 
    /* @var $offer_page_flow \Flux\OfferPageFlow */
    $offer_page_flow = $this->getContext()->getRequest()->getAttribute('offer_page_flow', array());
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel">Manage Filters</h4>
</div>
<form id="offer_page_flow_filter_modal_form" method="POST" action="">
    <input type="hidden" id="offer_page_flow_filter_modal_rule_position" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][position]" value="<?php echo $offer_page_flow->getPosition() ?>" />
    <div class="modal-body">
        <div class="form-group">
            <label>
                Filter data when
            </label>
            <select id="filter_type" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][filter_type]" class="span2 filter_type">
                <option value="1" <?php echo ($offer_page_flow->getFilterType() == '1' ? "SELECTED" : "") ?>>any condition matches</option>
                <option value="2" <?php echo ($offer_page_flow->getFilterType() == '2' ? "SELECTED" : "") ?>>all conditions match</option>
            </select>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Conditions</h3></div>
            <div class="panel-body" id="filter_conditions">
                <?php if (count($offer_page_flow->getFilterConditions()) > 0) { ?>
                    <?php 
                        /* @var $filter_condition \Flux\OfferPageFlowFilter */ 
                        foreach ($offer_page_flow->getFilterConditions() as $key => $filter_condition) { 
                    ?>
                        <div class="row form-group filter-condition-group-item">
                            <div class="col-md-4">
                                <select id="filter_data_field_id-<?php echo $key ?>" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][filter_conditions][<?php echo $key ?>][data_field_id]" class="filter_data_field_id">
                                    <?php foreach(\Flux\DataField::retrieveActiveDataFields() AS $dataFieldId => $dataField) { ?>
                                        <?php if ($dataField->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
                                            <option <?php echo ($dataField->getId() == $filter_condition->getDataFieldId()) ? "SELECTED=\"SELECTED\"" : "" ?> value="<?php echo $dataField->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => $dataField->getId(), 'name' => $dataField->getName(), 'keyname' => $dataField->getKeyName(), 'description' => $dataField->getDescription(), 'request_names' => implode(", ", array_merge(array($dataField->getKeyName()), $dataField->getRequestName()))))) ?>"><?php echo $dataField->getName() ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control filter_op" id="filter_op-<?php echo $key ?>" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][filter_conditions][<?php echo $key ?>][filter_op]">
                                    <option value="1" <?php echo ($filter_condition->getFilterOp() == '1' ? "SELECTED" : "") ?>>contains</option>
                                    <option value="2" <?php echo ($filter_condition->getFilterOp() == '2' ? "SELECTED" : "") ?>>begins with</option>
                                    <option value="3" <?php echo ($filter_condition->getFilterOp() == '3' ? "SELECTED" : "") ?>>ends with</option>
                                    <option value="4" <?php echo ($filter_condition->getFilterOp() == '4' ? "SELECTED" : "") ?>>is</option>
                                    <option value="5" <?php echo ($filter_condition->getFilterOp() == '5' ? "SELECTED" : "") ?>>is not</option>
            						<option value="6" <?php echo ($filter_condition->getFilterOp() == '6' ? "SELECTED" : "") ?>>does not contain</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control filter_value" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][filter_conditions][<?php echo $key ?>][filter_value]" value="<?php echo implode(",", $filter_condition->getFilterValue()) ?>" />
                            </div>
                            <div class="col-md-1 hidden-xs hidden-sm">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-condition">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </div>
                            <div class="col-md-1 visible-xs visible-sm">
                                <button type="button" class="form-control btn btn-danger btn-sm btn-remove-condition">remove condition</button>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="no_filters" style="display:none;"><em>No conditions, all traffic will be accepted</em></div>
                <?php } else { ?>
                    <div class="no_filters" style="display:block;"><em>No conditions, all traffic will be accepted</em></div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-info" id="add_filter_btn">Add Filter</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>
<!--  This div will be cloned when a new filter is added -->
<div class="row form-group filter-condition-group-item" style="display:none;" id="dummy_filter_condition">
    <div class="col-md-4">
        <select class="filter_data_field_id" id="filter_data_field_id-dummy_condition_index" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][filter_conditions][dummy_condition_index][data_field_id]">
            <?php foreach(\Flux\DataField::retrieveActiveDataFields() AS $dataFieldId => $dataField) { ?>
                <?php if ($dataField->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
                    <option value="<?php echo $dataField->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => $dataField->getId(), 'name' => $dataField->getName(), 'keyname' => $dataField->getKeyName(), 'description' => $dataField->getDescription(), 'request_names' => implode(", ", array_merge(array($dataField->getKeyName()), $dataField->getRequestName()))))) ?>"><?php echo $dataField->getName() ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-control filter_op" id="filter_op-dummy_condition_index" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][filter_conditions][dummy_condition_index][filter_op]">
            <option value="1">contains</option>
            <option value="2">begins with</option>
            <option value="3">ends with</option>
            <option value="4">is</option>
            <option value="5">is not</option>
            <option value="6">does not contain</option>
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" class="form-control filter_value" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][filter_conditions][dummy_condition_index][filter_value]" value="" />
    </div>
    <div class="col-md-1 hidden-xs hidden-sm">
        <button type="button" class="btn btn-danger btn-sm btn-remove-condition">
            <span class="glyphicon glyphicon-remove"></span>
        </button>
    </div>
    <div class="col-md-1 visible-xs visible-sm">
        <button type="button" class="form-control btn btn-danger btn-sm btn-remove-condition">remove condition</button>
    </div>
</div>
<script>
//<!--
$(document).ready(function() {
    $selectize_filter_data_field_options = {
    	valueField: '_id',
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
        }
    };
	
	/* Render existing filter type select box */
	$('#offer_page_flow_filter_modal_form .filter_type').selectize();

	/* Render existing filter type select box */
	$('#offer_page_flow_filter_modal_form .filter_value').selectize({
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });

    /* Render existing filter op select boxes */
    $('#filter_conditions .filter_op').selectize();

    /* Render existing data field select boxes */
    $('#filter_conditions .filter_data_field_id').selectize($selectize_filter_data_field_options);

    /* Remove a condition */
    $('#filter_conditions').on('click', '.btn-remove-condition', function() {
    	$(this).closest('.form-group').remove();
    	if ($('#filter_conditions > .filter-condition-group-item').length == 0) {
 		   $('.no_filters').show();
    	}
    });

    /* Add a new filter to the list of conditions */
    $('#add_filter_btn').on('click', function() {
    	var index_number = $('#filter_conditions > .filter-condition-group-item').length;
        var filter_condition_div = $('#dummy_filter_condition').clone(true);
        filter_condition_div.html(function(i, oldHTML) {
            oldHTML = oldHTML.replace(/dummy_condition_index/g, index_number);
            return oldHTML;
        });
        filter_condition_div.removeAttr('id');
        filter_condition_div.find('.filter_op').selectize();
        filter_condition_div.find('.filter_data_field_id').selectize($selectize_filter_data_field_options);
        $('#filter_conditions').append(filter_condition_div);
        filter_condition_div.show();
        $('.no_filters').hide();
    });

    /* Handle a form submit by converting it to a text representative and hidden input fields on the main page */
    $('#offer_page_flow_filter_modal_form').on('submit', function(event) {
    	var position = $('#offer_page_flow_filter_modal_rule_position').val();
        var input_html = $('<div></div>');
        var input_text = '';
        var filter_text = '';
        // Generate the filter text
        $.each($(this).find(':input'), function(i, item) {
        	input_html.append($('<input type="hidden" />').attr('name', $(item).attr('name')).attr('value', $(item).val()));
        	if ($(item).hasClass('filter_type')) {
      		    if ($(item).val() == 1) {
      		    	input_text = '<div class="offer_page_flow_filter_description_filter_type">Filters applied when <strong>any</strong> condition matches:</div>';
      		    } else {
      		    	input_text = '<div class="offer_page_flow_filter_description_filter_type">Filters applied when <strong>all</strong> conditions match:</div>';
      		    }
        	} else if ($(item).hasClass('filter_data_field_id')) {
        		filter_text += '<li>When the data field <strong>' + $(item)[0].selectize.getItem($(item)[0].selectize.getValue()).find('.name').html() + '</strong> ';
        	} else if ($(item).hasClass('filter_op')) {
        	    if ($(item).val() == '1') {
        	    	filter_text += ' <em>contains</em> ';
        	    } else if ($(item).val() == '2') {
        	    	filter_text += ' <em>begins with</em> ';
        	    } else if ($(item).val() == '3') {
        	    	filter_text += ' <em>ends with</em> ';
        	    } else if ($(item).val() == '4') {
        	    	filter_text += ' <em>is</em> ';
        	    } else if ($(item).val() == '5') {
        	    	filter_text += ' <em>is not</em> ';
        	    } else if ($(item).val() == '6') {
        	    	filter_text += ' <em>does not contain</em> ';
            	}
        	} else if ($(item).hasClass('filter_value')) {
        	    filter_text += '<strong>' + ($(item).val().trim() != '' ? $(item).val() : 'blank') + '</strong></li>';
        	}
        });
        // If we do not have any filters, then just do no filtering
        if (filter_text == '') {
        	input_text = '<em>No conditions, all traffic will be accepted</em>';
        	$('.offer_page_flow_filter_div-' + position).html(input_text);
        } else {
            // Save the hidden form elements into the filter div on the main page
            $('.offer_page_flow_filter_div-' + position).html(input_html.html() + input_text + '<ul>' + filter_text + '</ul>');
        }
        // Serialize this form and change the add/modify filter button to pass the serialized values
        $('.add_filter_btn-' + position).attr('href', '/offer/offer-page-pane-flow-filter-modal?' + $(this).serialize());
        // Hide the modal
        $('#flow_filter_modal').modal('hide');
        $('#changes_alert').show();
        event.preventDefault();
    });
});
//-->
</script>