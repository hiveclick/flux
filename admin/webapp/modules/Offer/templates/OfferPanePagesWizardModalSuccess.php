<?php
	/* @var $offer_page Flux\OfferPage */
	$offer_page = $this->getContext()->getRequest()->getAttribute("offer_page", array());
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel">Add New Page</h4>
</div>
<form class="form-horizontal" id="offer_page_wizard_form" name="offer_page_wizard_form" method="POST" action="/api" autocomplete="off">
	<input type="hidden" name="func" value="/offer/offer-page" />
	<input type="hidden" name="offer_id" value="<?php echo $offer_page->getOfferId() ?>" />
	<input type="hidden" name="priority" value="100" />
	<div class="modal-body">
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
			<div class="col-sm-10">
				<input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $offer_page->getName() ?>" />
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="page_name">Description</label>
			<div class="col-sm-10">
				<textarea name="description" id="description" rows="3" class="form-control" placeholder="Enter brief description about this page..."><?php echo $offer_page->getDescription() ?></textarea>
			</div>
		</div>
	
		<hr />
		<div class="help-block">Enter filename of this page located on the server.  This is how we can associate clicks to this page.</div>
		<p />
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="page_name">Page Name</label>
			<div class="col-sm-10">
				<input type="text" id="page_name" name="page_name" class="form-control" required placeholder="Page filename" value="<?php echo $offer_page->getPageName() ?>" />
			</div>
		</div>
		<hr />
		<div class="help-block">These are advanced settings that should only be changed if you know what you are doing.</div>
		<p />
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="page_name">File path</label>
			<div class="col-sm-10">
				<input type="text" id="file_path" name="file_path" class="form-control" required placeholder="Enter file page (<?php echo $offer_page->getOffer()->getDocrootDir() ?>)" value="<?php echo $offer_page->getFilePath() ?>" />
			</div>
		</div>
		<p />
	
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="page_name">Preview Url</label>
			<div class="col-sm-10">
				<input type="text" id="preview_url" name="preview_url" class="form-control" required placeholder="Enter preview url (<?php echo $offer_page->getOffer()->getDomainName() ?>)" value="<?php echo $offer_page->getPreviewUrl() ?>" />
			</div>
		</div>
		<p />
	</div>
    <div class="modal-footer">
        <input type="submit" name="__save" class="btn btn-success" value="Save" />
    </div>
</form>
	
<script>
//<!--
$(document).ready(function() {
	$('#offer_page_wizard_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Page Added', 'The page has been added to this offer');
			$('#offer_page_wizard_modal').modal('hide');
		}
	});	
});
//-->
</script>