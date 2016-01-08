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
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="ppctraffic">
			<div class="help-block">Use this form to generate a unique tracking link that you can use in Adwords</div>
			<br />
			<form class="form-horizontal" name="offer_instructions_form" method="GET" action="" autocomplete="off" role="form">
				<input type="hidden" name="posting_url_client" class="form-control posting_url_change" value="<?php echo $campaign->getId() ?>">
				<textarea type="text" id="example_url" rows="5" name="example_url" class="form-control" /></textarea>
			</form>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="hostnpost">
			<iframe src="/campaign/campaign-post-instruction?_id=<?php echo $campaign->getId() ?>" style="height:600px;width:100%;" seamless frameborder="0"></iframe>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a class="btn btn-info hidden" id="campaign_download_pdf" href="/campaign/campaign-post-instruction-download?_id=<?php echo $campaign->getId() ?>"><span class="glyphicon glyphicon-download"></span> Download PDF</a>
	<button type="button" class="btn btn-success btn-add-dataField"><span class="glyphicon glyphicon-plus"></span> Add Data Field</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

<script>
//<!--

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