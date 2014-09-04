<?php 
    /* @var $offer_page_flow \Gun\OfferPageFlow */
    $offer_page_flow = $this->getContext()->getRequest()->getAttribute('offer_page_flow', array());
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel">Manage Setters</h4>
</div>
<form id="offer_page_flow_setter_modal_form" method="POST" action="">
    <div class="modal-body">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Set the following fields:</h3></div>
            <div class="panel-body" id="setters">
                <?php if (count($offer_page_flow->getSetters()) > 0) { ?>
                    <?php
                        /* @var $setter \Gun\OfferPageFlowSetter */ 
                        foreach ($offer_page_flow->getSetters() as $key => $setter) { 
                    ?>
                        <div class="row form-group setter-group-item">
                            <div class="col-md-3">
                                <select class="form-control setter_op" id="setter_op-<?php echo $key ?>" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][setters][<?php echo $key ?>][setter_op]">
                                    <option value="1" <?php echo ($setter->getSetterOp() == '1' ? "SELECTED" : "") ?>>Set</option>
                                    <option value="2" <?php echo ($setter->getSetterOp() == '2' ? "SELECTED" : "") ?>>Increment</option>
                                    <option value="3" <?php echo ($setter->getSetterOp() == '3' ? "SELECTED" : "") ?>>Decrement</option>
                                    <option value="4" <?php echo ($setter->getSetterOp() == '4' ? "SELECTED" : "") ?>>Set (if blank)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="setter_data_field_id-<?php echo $key ?>" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][setters][<?php echo $key ?>][data_field_id]" class="setter_data_field_id">
                                    <?php foreach(\Gun\DataField::retrieveActiveDataFields() AS $dataFieldId => $dataField) { ?>
                                        <?php if ($dataField->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
                                            <option <?php echo ($dataField->getId() == $setter->getDataFieldId()) ? "SELECTED=\"SELECTED\"" : "" ?> value="<?php echo $dataField->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => $dataField->getId(), 'name' => $dataField->getName(), 'keyname' => $dataField->getKeyName(), 'description' => $dataField->getDescription(), 'request_names' => implode(", ", array_merge(array($dataField->getKeyName()), $dataField->getRequestName()))))) ?>"><?php echo $dataField->getName() ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="Value" class="form-control setter_value" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][setters][<?php echo $key ?>][setter_value]" value="<?php echo $setter->getSetterValue() ?>" />
                            </div>
                            <div class="col-md-1 hidden-xs hidden-sm">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-setter">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </div>
                            <div class="col-md-1 visible-xs visible-sm">
                                <button type="button" class="form-control btn btn-danger btn-sm btn-remove-setter">remove condition</button>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="no_setters" style="display:none;"><em>No setters defined</em></div>
                <?php } else { ?>
                    <div class="no_setters" style="display:block;"><em>No setters defined</em></div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-info" id="add_setter_btn">Add Field</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>
<!--  This div will be cloned when a new setter is added -->
<div class="row form-group setter-group-item" style="display:none;" id="dummy_setter">
    <div class="col-md-3">
        <select class="form-control setter_op" id="setter_op-dummy_setter_index" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][setters][dummy_setter_index][setter_op]">
            <option value="1">Set</option>
            <option value="2">Increment</option>
            <option value="3">Decrement</option>
            <option value="4">Set (if blank)</option>
        </select>
    </div>
    <div class="col-md-4">
        <select class="setter_data_field_id" id="setter_data_field_id-dummy_setter_index" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][setters][dummy_setter_index][data_field_id]">
            <?php foreach(\Gun\DataField::retrieveActiveDataFields() AS $dataFieldId => $dataField) { ?>
                <?php if ($dataField->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
                    <option value="<?php echo $dataField->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => $dataField->getId(), 'name' => $dataField->getName(), 'keyname' => $dataField->getKeyName(), 'description' => $dataField->getDescription(), 'request_names' => implode(", ", array_merge(array($dataField->getKeyName()), $dataField->getRequestName()))))) ?>"><?php echo $dataField->getName() ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" placeholder="Value" class="form-control setter_value" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][setters][dummy_setter_index][setter_value]" value="" />
    </div>
    <div class="col-md-1 hidden-xs hidden-sm">
        <button type="button" class="btn btn-danger btn-sm btn-remove-setter">
            <span class="glyphicon glyphicon-remove"></span>
        </button>
    </div>
    <div class="col-md-1 visible-xs visible-sm">
        <button type="button" class="form-control btn btn-danger btn-sm btn-remove-setter">remove condition</button>
    </div>
</div>
<script>
//<!--
$(document).ready(function() {
    $selectize_setter_data_field_options = {
    	valueField: '_id',
        labelField: 'name',
        searchField: ['name', 'description','request_names'],
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
    };
	
	/* Render existing setter type select box */
	$('#offer_page_flow_setter_modal_form .setter_type').selectize();

    /* Render existing setter op select boxes */
    $('#setters .setter_op').selectize();

    /* Render existing data field select boxes */
    $('#setters .setter_data_field_id').selectize($selectize_setter_data_field_options);

    /* Remove a condition */
    $('#setters').on('click', '.btn-remove-setter', function() {
    	$(this).closest('.form-group').remove();
    	if ($('#setters > .setter-group-item').length == 0) {
 		   $('.no_setters').show();
    	}
    });

    /* Add a new setter to the list of conditions */
    $('#add_setter_btn').on('click', function() {
    	var index_number = $('#setters > .setter-group-item').length;
        var setter_div = $('#dummy_setter').clone(true);
        setter_div.html(function(i, oldHTML) {
            oldHTML = oldHTML.replace(/dummy_setter_index/g, index_number);
            return oldHTML;
        });
        setter_div.removeAttr('id');
        setter_div.find('.setter_op').selectize();
        setter_div.find('.setter_data_field_id').selectize($selectize_setter_data_field_options);
        $('#setters').append(setter_div);
        $('.no_setters').hide();
        setter_div.show();
    });

    /* Handle a form submit by converting it to a text representative and hidden input fields on the main page */
    $('#offer_page_flow_setter_modal_form').on('submit', function(event) {
    	var position = '<?php echo $offer_page_flow->getPosition() ?>';
        var input_html = $('<div></div>');
        var setter_text = '';
        var setter_op_text = ' by ';
        // Generate the setter text
        $.each($(this).find(':input'), function(i, item) {
        	input_html.append($('<input type="hidden" />').attr('name', $(item).attr('name')).attr('value', $(item).val()));
        	if ($(item).hasClass('setter_op')) {
        		if ($(item).val() == '1') {
        	    	setter_text += '<li><em>Set</em> ';
        	    	setter_op_text = ' to ';
        		} else if ($(item).val() == '2') {
        	    	setter_text += '<li><em>Increment</em> ';
        	    	setter_op_text = ' by ';
        	    } else if ($(item).val() == '3') {
        	    	setter_text += '<li><em>Decrement</em> ';
        	    	setter_op_text = ' by ';
        	    } else if ($(item).val() == '4') {
        	    	setter_text += '<li><em>Set (if blank)</em> ';
        	    	setter_op_text = ' to ';
        	    }
        	} else if ($(item).hasClass('setter_data_field_id')) {
        		setter_text += ' <strong>' + $(item)[0].selectize.getItem($(item)[0].selectize.getValue()).find('.item_name').html() + '</strong> ';
        		setter_text += setter_op_text;
        	} else if ($(item).hasClass('setter_value')) {
        	    setter_text += '<strong>' + $(item).val() + '</strong></li>';
        	}
        });
        // Save the hidden form elements into the setter div on the main page
        $('.offer_page_flow_setter_div-' + position).html(input_html.html() + 'Assign the following fields:<ul>' + setter_text + '</ul>');
        // Serialize this form and change the add/modify setter button to pass the serialized values
        $('.add_setter_btn-' + position).attr('href', '/offer/offer-page-pane-flow-setter-modal?' + $(this).serialize());
        // Hide the modal
        $('#flow_setter_modal').modal('hide');
        event.preventDefault();
    });
});
//-->
</script>