<?php
    /* @var $offer_page \Flux\OfferPage */
	$offer_page = $this->getContext()->getRequest()->getAttribute("offer_page", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Preview Offer Page</h4>
</div>
<div class="modal-body">
    <div class="help-block">View a preview of this page</div>
    <div class="thumbnail">
    	<img src="" id="offer_page_preview" border="0" alt="Loading thumbnail..." />
    </div>
    <div class="text-center">
        <a href="<?php echo $offer_page->getPreviewUrl() ?>"><?php echo $offer_page->getPreviewUrl() ?></a>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

<script>
//<!--
$(document).ready(function() {
	if ('<?php echo $offer_page->getPreviewUrl() ?>' != '') {
		if (('<?php echo $offer_page->getPreviewUrl() ?>').indexOf('.local') == -1) {
			$('#offer_page_preview').attr('src', 'http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=<?php echo $offer_page->getPreviewUrl() ?>');
// 			$('#offer_page_preview').attr('src', 'http://images.websnapr.com/?size=s&key=nl9dp2uaObL6&hash=' + websnapr_hash + '&url=' + $(this).attr('data-url'));
		} else {
			$('#offer_page_preview').attr('src', '/images/no_preview.png');
		}
	} else {
		$('#offer_page_preview').attr('src', '/images/no_preview.png');
	}
});
//-->
</script>