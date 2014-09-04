<?php
    /* @var $offer \Gun\Offer */
    $offer = $this->getContext()->getRequest()->getAttribute("offer", array());
    $offer_pages = $this->getContext()->getRequest()->getAttribute("offer_pages", array());

?>
<div class="help-block">Shows the pages that have been tracked or found for this offer</div>
<br/>
<form id="offer_page_organize" method="POST" action="/api">
    <input type="hidden" name="func" value="/offer/offer-page-organize" />
    <input type="hidden" name="offer_id" value="<?php echo $offer->getId() ?>" />
    <div id="offer_pages">
        <?php
            /* @var $offer_page \Gun\OfferPage */
            foreach ($offer_pages as $key => $offer_page) {
        ?>
        <div class="page_row panel panel-default">
            <div class="panel-heading">
                <div class="pull-right"><span class="hidden-xs hidden-sm">Position</span> #<?php echo $key + 1 ?></div>
                <div class="glyphicon glyphicon-resize-vertical close page-move pull-left"><span aria-hidden="true"></span><span class="sr-only">Close</span></div>
                <h2 class="panel-title">
                        &nbsp;
                        <a href="/offer/offer-page?_id=<?php echo $offer_page->getId() ?>"><?php echo $offer_page->getName() ?></a>
                        <small>(<?php echo $offer_page->getPageName() ?>)</small>
                </h2>
            </div>
            <div class="panel-body">
                <div class="thumbnail col-sm-2">
                    <img id="offer_page_thumbnail_img_<?php echo $offer_page->getId() ?>" class="page_thumbnail" src="" border="0" alt="Loading thumbnail..." data-url="<?php echo $offer_page->getPreviewUrl() ?>" />
                </div>
                <div class="col-sm-7">
                    <input type="hidden" name="offer_page_id_array[]" value="<?php echo $offer_page->getId() ?>" />
                    <div class="text-muted help-block"><?php echo $offer_page->getDescription() ?></div>
                    <div class="hidden-xs small">
                        <p />
                        <?php if (count($offer_page->getOfferPageFlows()) > 0) { ?>
                            On submit, this page may go to:
                            <ul>
                            <?php foreach ($offer_page->getOfferPageFlows() as $offer_page_flow) { ?>
                                <li><a href="/offer/offer-page?_id=<?php echo $offer_page->getId() ?>#tabs-flow"><?php echo $offer_page_flow->getDestinationOfferPage()->getName() ?></a> <span class="text-muted">(<?php echo $offer_page_flow->getDestinationOfferPage()->getPageName() ?>)</span></li>
                            <?php } ?>
                            </ul>
                        <?php } else { ?>
                            On submit, this page may go to:
                            <ul>
                                <li>The Next page in order (default action)</li>
                            </ul>
                        <?php } ?>
                    </div>
                    <div class="hidden-xs">
                        <p />
                        <small class="text-success"><?php echo $offer_page->getFilePath() ?></small>
                    </div>
                </div>
                <div class="hidden-xs hidden-sm col-sm-3 small well well-default text-right">
                    <strong>Today's Clicks:</strong> <big><?php echo number_format($offer_page->getClickToday(), 0, null, ',') ?></big>
                    <p />
                    <strong>Yesterday's Clicks:</strong> <big><?php echo number_format($offer_page->getClickYesterday(), 0, null, ',') ?></big>
                </div>
                <div class="visible-xs visible-sm col-sm-12">
                    <p />
                    <div class="well well-default small">
                        <div class="col-sm-6 text-left pull-left">
                            Today: <strong><?php echo number_format($offer_page->getClickToday(), 0, null, ',') ?></strong>
                        </div>
                        <div class="col-sm-6 text-right pull-right">
                            Yesterday: <strong><?php echo number_format($offer_page->getClickYesterday(), 0, null, ',') ?></strong>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</form>
<script>
//<!--
$(document).ready(function() {
    $('#offer_page_organize').form(function(data) {
        $.rad.notify('Pages organized', 'The pages have been reorganized');
    });

	$('.page_thumbnail').each(function(i, item) {
		if ($(this).attr('data-url') != '') {
			if ($(this).attr('data-url').indexOf('.local') == -1) {
				$(this).attr('src', 'http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=300x300&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=' + $(this).attr('data-url'));
				//$(this).attr('src', 'http://images.websnapr.com/?size=s&key=nl9dp2uaObL6&hash=' + websnapr_hash + '&url=' + $(this).attr('data-url'));
			} else {
				$(this).attr('src', '/images/no_preview.png');
			}
		} else {
			$(this).attr('src', '/images/no_preview.png');
		}
	});

	$("#offer_pages").sortable({
	    handle: '.page-move',
		cursor: "move",
		axis: 'y',
	    update: function( event, ui ) {
	        $('#offer_page_organize').submit();
	    }
	});
});
//-->
</script>