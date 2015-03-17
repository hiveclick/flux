<?php
	/* @var $offer \Flux\Offer */
	$offer = $this->getContext()->getRequest()->getAttribute("offer", array());
	$offer_pages = $this->getContext()->getRequest()->getAttribute("offer_pages", array());

?>
<div class="page-header">
	<!-- Actions -->
	<div class="pull-right">
		<div class="visible-sm visible-xs">
			<div class="btn-group">
  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a href="/offer/offer-page-wizard?offer_id=<?php echo $offer->getId() ?>" data-toggle="modal" data-target="#offer_page_wizard_modal"><span class="glyphicon glyphicon-plus"></span>  add new page</a></li>
				</ul>
			</div>
		</div>
		<div class="hidden-sm hidden-xs">
			<div class="btn-group" role="group">
				<a class="btn btn-success" href="/offer/offer-page-wizard?offer_id=<?php echo $offer->getId() ?>" data-toggle="modal" data-target="#offer_page_wizard_modal"><span class="glyphicon glyphicon-plus"></span>  add new page</a>
			</div>
		</div>
	</div>
	<h1>Offer Pages <small>Manage pages for <?php echo $offer->getName() ?></small></h1>
</div>
<ol class="breadcrumb">
	<li><a href="/offer/offer-search">Offers</a></li>
	<li><a href="/offer/offer?_id=<?php echo $offer->getId() ?>"><?php echo $offer->getName() ?></a></li>
	<li class="active">Offer Pages</li>
</ol>

<div class="help-block">Shows the pages that have been tracked or found for this offer</div>
<p />
<form id="offer_page_organize" method="POST" action="/api">
	<input type="hidden" name="func" value="/offer/offer-page-organize" />
	<input type="hidden" name="offer_id" value="<?php echo $offer->getId() ?>" />
	<div id="offer_pages">
		<?php
			/* @var $offer_page \Flux\OfferPage */
			foreach ($offer_pages as $key => $offer_page) {
		?>
		<div class="page_row col-sm-12">
			<div>
			<div class="col-sm-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span style="cursor:move;" class="page-move glyphicon glyphicon-resize-vertical close pull-left"><span aria-hidden="true"></span><span class="sr-only">Move</span></span>
						<h2 class="panel-title">
							&nbsp;
							<a href="/offer/offer-page?_id=<?php echo $offer_page->getId() ?>"><?php echo $offer_page->getName() ?></a>
							<small>(<?php echo $offer_page->getPageName() ?>)</small>
						</h2>
					</div>
					<div class="panel-body">
						<div class="thumbnail">
							<img id="offer_page_thumbnail_img_<?php echo $offer_page->getId() ?>" class="page_thumbnail" src="" border="0" alt="Loading thumbnail..." data-url="<?php echo $offer_page->getPreviewUrl() ?>" />
						</div>
						<div class="text-muted help-block small"><?php echo $offer_page->getDescription() ?></div>
					</div>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="panel panel-default">
					<div class="panel-heading">
						<a href="/offer/offer-page?_id=<?php echo $offer_page->getId() ?>" class="glyphicon glyphicon-edit close pull-right"><span aria-hidden="true"></span><span class="sr-only">Edit</span></a>
						<h2 class="panel-title">
							Filters &amp; Options
						</h2>
					</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<input type="hidden" name="offer_page_id_array[]" value="<?php echo $offer_page->getId() ?>" />
							
							<div class="small">
							When this page submits the user will be taken to
							<ul>
								<?php if (count($offer_page->getOfferPageFlows()) > 0) { ?>
									<?php foreach ($offer_page->getOfferPageFlows() as $flow_key => $offer_page_flow) { ?>
										<li>
											<?php if (count($offer_page_flow->getFilterConditions()) > 0) { ?>
												Filter when
												<?php 
													/* @var $filter_condition \Flux\OfferPageFlowFilter */
													foreach ($offer_page_flow->getFilterConditions() as $key => $filter_condition) { 
												?>
														<?php if ($key > 0) { ?>
															<?php if ($offer_page_flow->getFilterType() == \Flux\OfferPageFlow::FILTER_TYPE_ALL) { ?>
																<i>and</i>
															<?php } else { ?>
															 	<i>or</i>
															<?php } ?>
														<?php } ?>
														<?php echo $filter_condition->getDataField()->getDataFieldName() ?>
														<?php echo \Flux\Base\OfferPageFlowFilter::getFilterOpText($filter_condition->getFilterOp()) ?>
														<?php echo trim(implode(", ", $filter_condition->getFilterValue())) == '' ? 'blank' : implode(", ", $filter_condition->getFilterValue()) ?>
														<?php if ($key == count($offer_page_flow->getFilterConditions()) - 1) { ?>
															<ul>
																<?php if ($offer_page_flow->getNavigation()->getNavigationType() == '1') { ?>
																	<?php if ($offer_page_flow->getNavigation()->getDestinationOfferPage()->getOfferPageId() == 0) { ?>
																		<li><i>the next page in order</i></li>
																	<?php } else { ?>
																		<li>Local Page: <?php echo $offer_page_flow->getNavigation()->getDestinationOfferPage()->getOfferPageName() ?></li>
																	<?php } ?>
																<?php } else { ?>
																	<li>Remote url: <?php echo $offer_page_flow->getNavigation()->getRemoteUrl() ?></li>
																<?php } ?>
															</ul>
														<?php } ?>
													
												<?php } ?>
											<?php } else { ?>
												No filters set, all <?php echo ($flow_key > 0) ? 'other' : '' ?> traffic allowed and sent to
												<ul>
													<?php if ($offer_page_flow->getNavigation()->getNavigationType() == '1') { ?>
														<?php if ($offer_page_flow->getNavigation()->getDestinationOfferPage()->getOfferPageId() == 0) { ?>
															<li><i>the next page in order</i></li>
														<?php } else { ?>
															<li>Local Page: <?php echo $offer_page_flow->getNavigation()->getDestinationOfferPage()->getOfferPageName() ?></li>
														<?php } ?>
													<?php } else { ?>
														<li>Remote url: <?php echo $offer_page_flow->getNavigation()->getRemoteUrl() ?></li>
													<?php } ?>
												</ul>
											<?php } ?>
										</li>
									<?php } ?>
								<?php } else { ?>
									<li><i>the next page in order</i></li>
								<?php } ?>
							</ul>
							</div>
							
							<div class="hidden-xs">
								<small class="text-success"><?php echo $offer_page->getFilePath() ?></small>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
			<hr />
		</div>
		<?php } ?>
	</div>
</form>

<!-- Push offer to server modal -->
<div class="modal fade" id="offer_page_wizard_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>

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
		axis: "y",
		update: function( event, ui ) {
			$('#offer_page_organize').submit();
		}
	});
});
//-->
</script>