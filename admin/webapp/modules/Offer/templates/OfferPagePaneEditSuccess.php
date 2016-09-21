<?php
	/* @var $offer_page \Flux\OfferPage */
	$offer_page = $this->getContext()->getRequest()->getAttribute("offer_page", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Edit Offer Page</h4>
</div>
<form id="offer_page_edit_form" method="PUT" action="/offer/offer-page" autocomplete="off">
	<input type="hidden" name="_id" value="<?php echo $offer_page->getId() ?>" />
	<input type="hidden" name="offer_id" value="<?php echo $offer_page->getOffer()->getOfferId() ?>" />
	<input type="hidden" name="preview_url" value="<?php echo $offer_page->getPreviewUrl() ?>" />
	<input type="hidden" name="file_path" value="<?php echo $offer_page->getFilePath() ?>" />
	<input type="hidden" name="priority" value="<?php echo $offer_page->getPriority() ?>" />
	<div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic Settings</a></li>
			<li role="presentation" class=""><a href="#advanced" role="tab" data-toggle="tab">Advanced Settings</a></li>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active" id="basic">
			   <div class="help-block">These are the main settings for this offer page.</div>
				<br/>
				<div class="form-group">
					<label class="control-label" for="name">Name</label>
		   			<input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $offer_page->getName() ?>" />
				</div>
	
				<div class="form-group">
					<label class="control-label" for="page_name">Description</label>
					<textarea name="description" id="description" rows="3" class="form-control" placeholder="Enter brief description about this page..."><?php echo $offer_page->getDescription() ?></textarea>
				</div>

				<hr />
				<div class="help-block">Enter filename of this page located on the server.  This is how we can associate clicks to this page.</div>
				<p />
				<div class="form-group">
					<label class="control-label" for="page_name">Page Name</label>
			   		<input type="text" id="page_name" name="page_name" class="form-control" placeholder="Page Filename" value="<?php echo $offer_page->getPageName() ?>" />
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade in active" id="advanced">
				<div class="help-block">These are advanced settings that should only be changed if you know what you are doing.</div>
				<p />
				<div class="form-group">
					<label class="control-label" for="page_name">File path</label>
	   				<input type="text" id="file_path" name="file_path" class="form-control" placeholder="Full Page path" value="<?php echo $offer_page->getFilePath() ?>" />
				</div>
	
				<div class="form-group">
					<label class="control-label" for="page_name">Preview Url</label>
	   				<input type="text" id="preview_url" name="preview_url" class="form-control" placeholder="Preview Url" value="<?php echo $offer_page->getPreviewUrl() ?>" />
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" name="__save" class="btn btn-primary" value="Save Page" />
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#offer_page_edit_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Offer Page Updated', 'The offer page has been updated successfully');
		}
	},{keep_form: 1});
});
//-->
</script>