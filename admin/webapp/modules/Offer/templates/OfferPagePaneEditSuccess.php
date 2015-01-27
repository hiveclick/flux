<?php
	/* @var $offer_page \Flux\OfferPage */
	$offer_page = $this->getContext()->getRequest()->getAttribute("offer_page", array());
	$servers = $this->getContext()->getRequest()->getAttribute('servers', array());
?>

<div class="help-block">Edit the HTML source for this page and push your changes back to the server</div>
<div class="panel panel-default">
	<div class="panel-body">
		<form class="" role="form" method="GET" action="/api" id="load_page_from_server_form" name="load_page_from_server_form">
			<input type="hidden" name="func" value="/offer/offer-page-source" />
			<input type="hidden" name="file_path" value="<?php echo $offer_page->getFilePath() ?>" />
			<div class="row">
				<label class="control-label" for="server_id">Choose the server from where you want to load the page contents:</label>
			</div>
			<div class="row">
				<div class="col-sm-10">
					<select name="server_id" id="server_id" class="form-control">
						<?php foreach ($servers as $server) { ?>
							<option value="<?php echo $server->getId() ?>"><?php echo $server->getHostname() ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-2"><input type="submit" name="__submit" value="load page" class="btn btn-success" /></div>
			</div>
		</form>
	</div>
</div>
<div id="base_tag_warning" class="alert small alert-warning">
	If images do not load, you may need to add <code>&lt;base href="http://<?php echo $offer_page->getOffer()->getDomainName() ?>/<?php echo $offer_page->getOffer()->getFolderName() != '' ? $offer_page->getOffer()->getFolderName() . '/' : '' ?>" /&gt;</code> to the top of your template
</div>
<form class="form-inline" role="form" method="POST" action="/api" id="push_page_to_server_form" name="push_page_to_server_form">
	<input type="hidden" name="func" value="/offer/offer-page-source" />
	<input type="hidden" name="file_path" value="<?php echo $offer_page->getFilePath() ?>" />
	<input type="hidden" id="push_server_id" name="server_id" value="" />
	<div class="panel panel-default">
		<textarea name="page_source" id="page_source"></textarea>
	</div>
	<div class="text-center">
		<input type="submit" name="__submit" value="save page" class="btn btn-success" />
		<a href="<?php echo $offer_page->getPreviewUrl() ?>" class="btn btn-info" target="preview_page">preview page</a>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#server_id').selectize().change(function() {
		$('#push_server_id').val($('#server_id').val());
	});
	
	$('#load_page_from_server_form').form(function(data) {
		if (data.record) {
			CKEDITOR.instances.page_source.setData(data.record.page_source);
		}
	});

	$('#push_page_to_server_form').form(function(data) {
		$.rad.notify('Page Saved', 'The page has been saved to the server');
	},{
		keep_form: 1,
		prepare: function() {
			$('#push_server_id').val($('#server_id').val());
			CKEDITOR.instances.page_source.updateElement();
			return true;
		}
	});

	CKEDITOR.replace('page_source', {
		startupMode: 'source',
		allowedContent: true,
		height: 450,
	});
	CKEDITOR.config.protectedSource.push(/<\?[\s\S]*?\?>/g);
});
//-->
</script>