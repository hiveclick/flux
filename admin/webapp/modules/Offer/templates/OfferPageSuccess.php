<?php
	/* @var $offer_page Flux\OfferPage */
	$offer_page = $this->getContext()->getRequest()->getAttribute("offer_page", array());
	$servers = $this->getContext()->getRequest()->getAttribute('servers', array());
?>
<!-- CKeditor WYSIWYG editor -->
<script src="/scripts/ckeditor/ckeditor.js"></script>
<div class="page-header">
	<!-- Actions -->
	<div class="pull-right">
		<div class="visible-sm visible-xs">
			<div class="btn-group">
  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a href="/offer/offer-page-pane-edit?_id=<?php echo $offer_page->getId() ?>" data-toggle="modal" data-target="#offer_page_edit_modal">edit page</a></li>
					<li class="divider"></li>
					<li><a href="/offer/offer-page-pane-edit?_id=<?php echo $offer_page->getId() ?>" data-toggle="modal" data-target="#offer_page_flow_modal">manage flow</a></li>
					<li class="divider"></li>
					<li><a href="/offer/offer-page-pane-edit?_id=<?php echo $offer_page->getId() ?>" data-toggle="modal" data-target="#offer_page_preview_modal">preview</a></li>
					<li class="divider"></li>
					<li><a data-toggle="modal" id="btn_delete_sm" data-target="#delete_modal" href="#"><span class="text-danger">delete</span></a></li>
				</ul>				
			</div>
		</div>
		<div class="hidden-sm hidden-xs">
			<div class="btn-group" role="group">
				<a class="btn btn-info" href="/offer/offer-page-pane-edit?_id=<?php echo $offer_page->getId() ?>" data-toggle="modal" data-target="#offer_page_edit_modal">edit page</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-info" href="/offer/offer-page-pane-flow?_id=<?php echo $offer_page->getId() ?>" data-toggle="modal" data-target="#offer_page_flow_modal">manage flow</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-info" href="/offer/offer-page-pane-preview?_id=<?php echo $offer_page->getId() ?>" data-toggle="modal" data-target="#offer_page_preview_modal">preview</a>
			</div>
			<a data-toggle="modal" id="btn_delete" data-target="#delete_modal" class="btn btn-danger" href="#">delete</a>
		</div>
	</div>
	<h1><a href="/offer/offer?_id=<?php echo $offer_page->getOffer()->getOfferId() ?>"><?php echo $offer_page->getOffer()->getOfferName() ?></a> <small><?php echo $offer_page->getPageName() ?></small></h1>
</div>
<ol class="breadcrumb">
	<li><a href="/offer/offer-search">Offers</a></li>
	<li><a href="/offer/offer?_id=<?php echo $offer_page->getOffer()->getOfferId() ?>"><?php echo $offer_page->getOffer()->getOfferName() ?></a></li>
	<li><a href="/offer/offer-page-search?_id=<?php echo $offer_page->getOffer()->getOfferId() ?>">Offer Pages</a></li>
	<li class="active"><?php echo $offer_page->getPageName() ?></li>
</ol>

<div class="help-block">Edit the HTML source for this page and push your changes back to the server</div>
<div class="panel panel-default">
	<div class="panel-body">
		<form role="form" method="GET" action="/offer/offer-page-source" id="load_page_from_server_form" name="load_page_from_server_form">
			<input type="hidden" name="file_path" value="<?php echo $offer_page->getFilePath() ?>" />
			<div class="form-group">
				<label class="control-label" for="server_id">Choose the server from where you want to load the page contents:</label>
				<select name="server_id" id="server_id" class="form-control">
					<?php foreach ($servers as $server) { ?>
						<option value="<?php echo $server->getId() ?>"><?php echo $server->getHostname() ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<input type="submit" name="__submit" value="load page" class="btn btn-success" />
			</div>
		</form>
	</div>
</div>
<div id="base_tag_warning" class="alert small alert-warning">
	If images do not load, you may need to add <code>&lt;base href="http://<?php echo $offer_page->getOffer()->getOffer()->getDomainName() ?>/<?php echo $offer_page->getOffer()->getOffer()->getFolderName() != '' ? $offer_page->getOffer()->getOffer()->getFolderName() . '/' : '' ?>" /&gt;</code> to the top of your template
</div>
<form class="form-inline" role="form" method="POST" action="/offer/offer-page-source" id="push_page_to_server_form" name="push_page_to_server_form">
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

<!-- edit modal -->
<div class="modal fade" id="offer_page_edit_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- flow modal -->
<div class="modal fade" id="offer_page_flow_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- path modal -->
<div class="modal fade" id="offer_page_path_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- preview page modal -->
<div class="modal fade" id="offer_page_preview_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>

<!-- Push offer to server modal -->
<div class="modal fade" id="flow_filter_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>
<!-- Push offer to server modal -->
<div class="modal fade" id="flow_setter_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>
<!-- Push offer to server modal -->
<div class="modal fade" id="flow_navigation_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>

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

	$('#btn_delete,#btn_delete_sm').click(function() {
		if (confirm('Are you sure you want to delete this page and completely remove it from the system?')) {
			$.rad.del('/offer/offer-page/<?php echo $offer_page->getId() ?>', { }, function(data) {
				$.rad.notify('Page Removed', 'This page has been removed from the system.');
				location.replace('/offer/offer-page-search?_id=<?php echo $offer_page->getOffer()->getOfferId() ?>')
			});
		}
	});
});
//-->
</script>
