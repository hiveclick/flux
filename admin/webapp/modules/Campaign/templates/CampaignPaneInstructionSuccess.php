<?php
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
	$example_qs = array('_ck' => $campaign->getKey(), 'firstname' => 'john', 'lastname' => 'smith', 'email' => 'john@mctesterson.com', 'addr' => '123 Test Street', 'city' => 'Test City', 'state' => 'UT', 'zip' => '84057', 'phone' => '8015551212');
	/* @var $filter \Flux\Link\DataField */
	foreach ($campaign->getOffer()->getOffer()->getSplit()->getSplit()->getFilters() as $filter) {
		if (in_array($filter->getDatafield()->getKeyname(), array('fn','ln','em','ph','cty','st','zip','addr'))) { continue; }
		$values = $filter->getDataFieldValue();
		$example_qs[$filter->getDatafield()->getKeyName()] = array_shift($values);
	} 
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Posting Instructions</h4>
</div>
<div class="modal-body">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#ppctraffic" role="tab" data-toggle="tab">PPC Traffic</a></li>
		<li role="presentation" class=""><a href="#hostnpost" role="tab" data-toggle="tab">Host &amp; Post Instructions</a></li>
		<li role="presentation" class=""><a href="#clickfunnels" role="tab" data-toggle="tab">ClickFunnels Instructions</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="ppctraffic">
			<div class="help-block">Use this form to generate a unique tracking link that you can use in Adwords</div>
			<br />
			<form class="form-horizontal" name="offer_instructions_form" method="GET" action="" autocomplete="off" role="form">
				<input type="hidden" name="posting_url_client" class="form-control posting_url_change" value="<?php echo $campaign->getId() ?>">
				<textarea type="text" id="example_url" rows="5" name="example_url" class="form-control" /></textarea>
				<div class="help-block">
					Click Add Datafield below to insert additional fields to the redirect url
				</div>
				<div id="dataField_posting_url_container"></div>
			</form>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="hostnpost">
			<iframe src="/campaign/campaign-post-instruction?_id=<?php echo $campaign->getId() ?>" style="height:600px;width:100%;" seamless frameborder="0"></iframe>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="clickfunnels">
			<div class="help-block">Use this form within ClickFunnels to integrate with this campaign</div>
			<br />
			<textarea readonly class="form-control" rows="20">&lt;form action="<?php echo str_replace("http://", "https://", (defined("MO_REALTIME_URL") ? MO_REALTIME_URL : "")) ?>/cf" method="GET"&gt;
	&lt;input type="hidden" name="redirect" value="CF REDIRECT URL HERE" /&gt;
	&lt;input type="hidden" name="_ck" value="<?php echo $campaign->getId() ?>" /&gt;
	&lt;!-- Uncomment this __clear input if you want to create a new lead every time (no multi-page funnels) --&gt;
	&lt;!--	&lt;input type="hidden" name="__clear" value="1" /&gt; --&gt;

	&lt;!-- Insert other fields here --&gt;
	&lt;input type="text" name="fn" value="" /&gt;
	&lt;input type="text" name="ln" value="" /&gt;
	&lt;input type="text" name="em" value="" /&gt;
	&lt;input type="text" name="ph" value="" /&gt;
&lt;/form&gt;</textarea>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a class="btn btn-info hidden" id="campaign_download_pdf" href="/campaign/campaign-post-instruction-download?_id=<?php echo $campaign->getId() ?>"><span class="fa fa-download"></span> Download PDF</a>
	<button type="button" class="btn btn-success btn-add-dataField"><span class="fa fa-plus"></span> Add Data Field</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

<!-- Dummy datafield posting url div -->
<div class="form-group row" style="display:none;" id="dummy_posting_url_dataField">
	<div class="col-sm-6">
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
	<div class="col-sm-1">
		<button type="button" class="btn btn-danger btn-remove-dataField">
			<span class="glyphicon glyphicon-minus"></span>
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

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		if ($(e.target).attr('href') == '#hostnpost') {
			$('#campaign_download_pdf').removeClass('hidden');
		} else {
			$('#campaign_download_pdf').addClass('hidden');
		}
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
	posting_params[<?php echo json_encode(\Flux\Lead::LEAD_CLEAR_FLAG); ?>] = 1;

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