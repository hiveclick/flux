<?php
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Posting Instructions</h4>
</div>
<div class="modal-body">
    <!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">PPC Traffic</a></li>
		<li role="presentation" class=""><a href="#hostnpost" role="tab" data-toggle="tab">Host &amp; Post Instructions</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="basic">
            <div class="help-block">Use this form to generate a unique tracking link that you can use in Adwords</div>
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
    	</div>
    	<div role="tabpanel" class="tab-pane fade in" id="hostnpost">
    	    <div style="max-height:600px;overflow:auto;">
    	        <h2>Posting Instructions for <?php echo $campaign->getOffer()->getOfferName() ?></h2>
    	        <div class="help-block">
    	            These are the posting instructions to send data to the <b><?php echo $campaign->getOffer()->getOfferName() ?></b> offer through our API.  The 
    	            available fields and their possible values are outlined below.  All posts should use the following url: 
    	        </div>
    	        <code class="text-center"><?php echo MO_API_URL ?>/rt/post-lead</code>
    	        <h3>Posting Fields</h3>
    	        <div class="help-block">
    	            The available fields you can pass in are defined below.  Fields that accept more than one value need to be suffixed with square brackets - [].  
    	        </div>
    	        <table class="table table-responsive">
    	            <thead>
    	                <tr>
    	                    <th>Name</th>
    	                    <th>Field</th>
    	                    <th>Required?</th>
    	                    <th>Values</th>
    	                </tr>
    	            </thead>
    	            <tbody>
    	                <tr>
    	                    <td>Campaign Key</td>
    	                    <td>_ck</td>
    	                    <td>Yes</td>
    	                    <td><b><?php echo $campaign->getKey() ?></b></td>
    	                </tr>
    	                <tr>
    	                    <td>First name</td>
    	                    <td>firstname</td>
    	                    <td>Yes</td>
    	                    <td>&nbsp;</td>
    	                </tr>
    	                <tr>
    	                    <td>Last name</td>
    	                    <td>lastname</td>
    	                    <td>Yes</td>
    	                    <td>&nbsp;</td>
    	                </tr>
    	                <tr>
    	                    <td>Phone</td>
    	                    <td>phone</td>
    	                    <td>Yes</td>
    	                    <td>&nbsp;</td>
    	                </tr>
    	                <tr>
    	                    <td>Email</td>
    	                    <td>email</td>
    	                    <td>Yes</td>
    	                    <td>&nbsp;</td>
    	                </tr>
    	                <tr>
    	                    <td>Address</td>
    	                    <td>address</td>
    	                    <td>Yes</td>
    	                    <td>&nbsp;</td>
    	                </tr>
    	                <tr>
    	                    <td>City</td>
    	                    <td>city</td>
    	                    <td>Yes</td>
    	                    <td>&nbsp;</td>
    	                </tr>
    	                <tr>
    	                    <td>State</td>
    	                    <td>state</td>
    	                    <td>Yes</td>
    	                    <td>&nbsp;</td>
    	                </tr>
    	                <tr>
    	                    <td>Postal Code</td>
    	                    <td>zip</td>
    	                    <td>Yes</td>
    	                    <td>&nbsp;</td>
    	                </tr>
    	                <?php 
    	                    /* @var $filter \Flux\Link\DataField */
    	                    foreach ($campaign->getOffer()->getOffer()->getSplit()->getSplit()->getFilters() as $filter) { 
    	                        if (in_array($filter->getDatafield()->getKeyname(), array('fn','ln','em','ph','cty','st','zip','addr'))) { continue; }
    	                ?>   
        	                <tr>
        	                    <td><?php echo $filter->getDatafield()->getName() ?></td>
        	                    <td><?php echo $filter->getDatafield()->getKeyName() ?></td>
        	                    <td>Yes</td>
        	                    <td><?php echo implode(",", $filter->getDataFieldValue()) ?></td>
        	                </tr>
    	                <?php } ?>
    	            </tbody>
    	        </table>
    	        <h3>Response</h3>
    	        <div class="help-block">
    	            Responses are received in JSON.  The response object will contain several top-level elements such as RESULT, ERRORS, RECORD, and META.  
    	            Failed responses will have ERRORS and the RESULT will be "failed".  Successful responses will not have any ERRORS and the RESULT will 
    	            be "success".  
    	            <p />
    	            Additionally, successful responses will have the RECORD element filled with the internal lead id, received time, and any other optional
    	            messages.  The lead id is also duplicated in the META record.  The META record is reserved for future use.    	             
    	        </div>
    	        <h4>Example Post Url</h4>
    	        <pre><?php echo MO_API_URL ?>/rt/post-lead?_ck=<?php echo $campaign->getKey() ?>&amp;firstname=john&amp;lastname=smith&amp;addr=123%20Test%20Street&amp;city=Test%20City&amp;state=UT&amp;zip=84057&amp;email=john@mctesterson.com</pre>
    	        <h4>Successful response</h4>
    	        <pre>{"result":"SUCCESS","errors":[],"meta":{"insert_id":"552f6fd4d9b868286a8dd08d","rows_affected":1},"record":{"lead":"552f6fd4d9b868286a8dd08d","response":"success","received_time":"04\/16\/2015 01:16:21","_id":0}}</pre>
    	        <h4>Failed response</h4>
    	        <pre>{"result":"FAILED","errors":["Validation failed on Phone with value ''"],"meta":{"insert_id":0,"rows_affected":0},"record":{"lead":"552f77f5d9b86844708dd08d","response":"Validation failed on Phone with value ''","received_time":"04\/16\/2015 01:51:01","_id":0}}</pre>
    	    </div>
    	</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

<!-- Dummy datafield posting url div -->
<div class="form-group" style="display:none;" id="dummy_posting_url_dataField">
	<div class="col-sm-5">
		<select name="posting_url_dataField_name" class="form-control posting_url_change">
			<optgroup label="Events">
			<?php foreach($data_fields AS $data_field) { ?>
				<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
					<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
				<?php  } ?>
			<?php } ?>
			</optgroup>
			<optgroup label="Tracking">
				<?php foreach($data_fields AS $data_field) { ?>
					<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
					<?php } ?>
				<?php } ?>
			</optgroup>
			<optgroup label="Data Fields">
				<?php foreach($data_fields AS $data_field) { ?>
					<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
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

<script>
//<!--
//Define our data field options
var $selectize_options = {
	valueField: 'key_name',
	labelField: 'name',
	searchField: ['name', 'description', 'request_names'],
	dropdownWidthOffset: 150,
	render: {
		item: function(item, escape) {
			var label = item.name || item.key;
            var caption = item.description ? item.description : null;
            var keyname = item.key_name ? item.key_name : null;
            var tags = item.tags ? item.tags : null;
            var tag_span = '';
			$.each(tags, function(j, tag_item) {
				tag_span += '<span class="label label-default">' + escape(tag_item) + '</span> ';
			});	            
            return '<div style="width:100%;padding-right:25px;">' +
                '<b>' + escape(label) + '</b> <span class="pull-right label label-success">' + escape(keyname) + '</span><br />' +
                (caption ? '<span class="text-muted small">' + escape(caption) + ' </span>' : '') +
                '<div>' + tag_span + '</div>' +   
            '</div>';
		},
		option: function(item, escape) {
			var label = item.name || item.key;
            var caption = item.description ? item.description : null;
            var keyname = item.key_name ? item.key_name : null;
            var tags = item.tags ? item.tags : null;
            var tag_span = '';
			$.each(tags, function(j, tag_item) {
				tag_span += '<span class="label label-default">' + escape(tag_item) + '</span> ';
			});	            
            return '<div style="border-bottom: 1px dotted #C8C8C8;">' +
                '<b>' + escape(label) + '</b> <span class="pull-right label label-success">' + escape(keyname) + '</span><br />' +
                (caption ? '<span class="text-muted small">' + escape(caption) + ' </span>' : '') +
                '<div>' + tag_span + '</div>' +
            '</div>';
		}
	},
	onChange: function(value) {
		buildPostingUrl();
	}
};

$(document).ready(function() {
	$('.posting_url_change').on('change', function(e) {
		e.preventDefault();
		buildPostingUrl();
	});

	$('.btn-add-dataField').on('click', function() {
		var $dataFieldRow = $('#dummy_posting_url_dataField').clone(true);
		$dataFieldRow.removeAttr('id');
		$dataFieldRow.find('select').selectize($selectize_options);
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

	posting_params[<?php echo json_encode(\Flux\DataField::DATA_FIELD_REF_CAMPAIGN_KEY); ?>] = $('[name=posting_url_client]').val();
	
	if($('[name=posting_url_save]').is(':checked')) {
		posting_params[<?php echo json_encode(\Flux\Lead::LEAD_SAVE_FLAG); ?>] = 1;
	}

	if($('[name=posting_url_clear]').is(':checked')) {
		posting_params[<?php echo json_encode(\Flux\Lead::LEAD_CLEAR_FLAG); ?>] = 1;
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