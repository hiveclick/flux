<?php
	/* @var $offer_asset Flux\OfferAsset */
	$offer_asset = $this->getContext()->getRequest()->getAttribute("offer_asset", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Preview Asset</h4>
</div>   
<div class="modal-body">
	<div class="asset_preview text-center">
		<a href="<?php echo $offer_asset->getAdLink() ?>">
			<div class="asset_preview_img thumbnail">
				<img src="data:image/png;base64,<?php echo $offer_asset->getImageData() ?>" border="0" />
			</div>
			<div class="asset_preview_title"><?php echo $offer_asset->getAdTitle() ?></div>
		</a>
		<small class="asset_preview_description"><?php echo $offer_asset->getAdDescription() ?></small>
	</div>
</div>