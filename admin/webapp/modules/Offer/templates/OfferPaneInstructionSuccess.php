<?php
	$offer = $this->getContext()->getRequest()->getAttribute("offer", array());
	$campaigns = $this->getContext()->getRequest()->getAttribute("campaigns", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Offer Link Instructions</h4>
</div>
<div class="modal-body">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#posting_url" role="tab" data-toggle="tab">Example Posting Url</a></li>
		<li role="presentation" class=""><a href="#tracking_pixel" role="tab" data-toggle="tab">Example Tracking Pixel</a></li>
		<li role="presentation" class=""><a href="#analytic_pixel" role="tab" data-toggle="tab">Example Analytic Pixel</a></li>
		<li role="presentation" class=""><a href="#hostnpost_url" role="tab" data-toggle="tab">Example Host &amp; Post Url</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="posting_url">
			<div class="help-block">Use this url on your PPC campaign to redirect traffic to this offer using the campaign below.</div>
			<div class="form-group">
				<textarea id="example_url" rows="5" name="example_url" class="form-control"></textarea>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="tracking_pixel">
			<div class="help-block">Use this tracking pixel on remote thank you pages to track clicks or conversions on this offer using the campaign below.</div>
			<div class="form-group">
				<textarea id="example_pixel" rows="5" name="example_pixel" class="form-control"></textarea>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="analytic_pixel">
			<div class="help-block">Place this code at the bottom of all your offer pages to enable offer page tracking.</div>
			<pre id="example_analytic_pixel">&lt;!-- Place this pixel at the bottom of all your pages for tracking --&gt;	
&lt;script type="text/javascript"&gt;
	var _op = _op || [];
	_op.push(['_trackPageView']);
	(function() {
	// Save the lead to the localStorage
	localStorage.setItem('flux_data', '&lt;?php echo json_encode(\FluxFE\Lead::getInstance()->getD()) ?&gt;');
	var op = document.createElement('script');
	op.type = 'text/javascript';
	op.async = 'true';
	op.src = ('https:' == document.location.protocal ? 'https://www' : 'http://www') + '.<?php echo defined('MO_ANALYTIC_DOMAIN') ? MO_ANALYTIC_DOMAIN : substr($_SERVER['SERVER_NAME'], strpos($_SERVER['SERVER_NAME'], '.') + 1) ?>/op.js?l=&lt;?php echo \FluxFE\Lead::getInstance()->getId() ?&gt;';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(op, s);
	})();
&lt;/script&gt;</pre>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="hostnpost_url">
			<div class="help-block">Use this url on to have an affiliate POST data to you.</div>
			<div class="form-group">
				<textarea id="hostnpost_url" rows="5" name="hostnpost_url" class="form-control"></textarea>
			</div>
		</div>
	</div>
	<hr />
	<div class="help-block">Use the form controls below to create an example Posting URL</div>
	<div class="form-group">
		<select id="posting_url_campaign" name="posting_url_campaign" placeholder="create a new url from another campaign associated with this offer" class="form-control">
			<?php
				/* @var $campaign \Flux\Campaign */ 
				foreach ($campaigns AS $campaign) { 
			?>
				<option value="<?php echo $campaign->getKey() ?>" data-data="<?php echo htmlentities(json_encode(array('campaign_key' => $campaign->getKey(), 'description' => $campaign->getDescription(), 'client_name' => $campaign->getClient()->getClientName()))) ?>"><?php echo $campaign->getKey() ?></option>
			<?php } ?>
		</select>
	</div>
	<div id="data_field_posting_url_container"></div>
	<div class="clearfix"></div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-success btn-add-dataField"><span class="glyphicon glyphicon-plus"></span> Add Data Field</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

<!-- Dummy data field div -->
<div class="form-group row" style="display:none;" id="dummy_posting_url_data_field">
	<div class="col-sm-6">
		<select name="posting_url_data_field_name" class="form-control posting_url_change">
			<optgroup label="Events">
			<?php foreach($data_fields AS $data_field) { ?>
				<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
					<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
				<?php  } ?>
			<?php } ?>
			</optgroup>
			<optgroup label="Data Fields">
				<?php foreach($data_fields AS $data_field) { ?>
					<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
					<?php } ?>
				<?php } ?>
			</optgroup>
			<optgroup label="Derived Fields">
				<?php foreach($data_fields AS $data_field) { ?>
					<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
					<?php } ?>
				<?php } ?>
			</optgroup>
			<optgroup label="Tracking">
				<?php foreach($data_fields AS $data_field) { ?>
					<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
					<?php } ?>
				<?php } ?>
			</optgroup>
		</select>
	</div>
	<div class="col-sm-5">
		<input type="text" name="posting_url_data_field_value" class="form-control posting_url_change" placeholder="Value" />
	</div>
	<div class="col-sm-1">
		<button type="button" class="btn btn-danger btn-remove-data_field"><span class="glyphicon glyphicon-minus"></span></button>
	</div>
	<div class="clearfix"></div>
</div>

<script>
//<!--
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
	}).on('keyup', function(e) {
		e.preventDefault();
		buildPostingUrl();
	});

	$('#posting_url_campaign').selectize({
		valueField: 'campaign_key',
		labelField: 'description',
		searchField: ['client_name', 'description', 'campaign_key'],
		render: {
			item: function(item, escape) {
	            return '<div style="padding-right:25px;">' +
	                '<b>' + escape(item.campaign_key) + '</b> <span class="pull-right label label-success">' + escape(item.client_name) + '</span><br />' +
	                (item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
	            '</div>';
			},
			option: function(item, escape) {
				return '<div style="padding-right:25px;">' +
	                '<b>' + escape(item.campaign_key) + '</b> <span class="pull-right label label-success">' + escape(item.client_name) + '</span><br />' +
	                (item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
	            '</div>';
			}
		}
	}).on('change', function(e) {
		buildPostingUrl();
	});

	$('.btn-add-dataField').on('click', function() {
		var $dataFieldRow = $('#dummy_posting_url_data_field').clone(true);
		$dataFieldRow.removeAttr('id');
		$dataFieldRow.find('select').selectize($selectize_options);
		$('#data_field_posting_url_container').append($dataFieldRow);
		$dataFieldRow.show();
		buildPostingUrl();
	});

	$('.btn-remove-data_field').on('click', function() {
		$(this).closest('.form-group').remove();
		buildPostingUrl();
	});

	buildPostingUrl();

});

function buildPostingUrl() {
	var posting_params = {};

	var campaign_key = $('[name=posting_url_campaign]').val();
	posting_params[<?php echo json_encode(\Flux\DataField::DATA_FIELD_REF_CAMPAIGN_KEY); ?>] = campaign_key;
	posting_params[<?php echo json_encode(\Flux\Lead::LEAD_CLEAR_FLAG); ?>] = 1;
	

	$('#data_field_posting_url_container .form-group').each(function() {
		var dataFieldName = $(this).find('[name=posting_url_data_field_name]').val();
		var dataFieldValue = $(this).find('[name=posting_url_data_field_value]').val();
		posting_params[dataFieldName] = dataFieldValue;
	});

	var query_string = $.param(posting_params);
	var entire_post_url = '<?php echo (defined("MO_API_URL") ? MO_API_URL : "") . '/rt/post-lead?'; ?>';
	var entire_url = '<?php echo (defined("MO_REALTIME_URL") ? MO_REALTIME_URL : "") . '/r?'; ?>';
	var entire_pixel = '<?php echo (defined("MO_REALTIME_URL") ? MO_REALTIME_URL : "") . '/p?'; ?>';
	$('[name=hostnpost_url]').val(entire_post_url + query_string);
	$('[name=example_url]').val(entire_url + query_string);
	$('[name=example_pixel]').val('<img src="' + entire_pixel + query_string + '" border="0" />');
}
//-->
</script>