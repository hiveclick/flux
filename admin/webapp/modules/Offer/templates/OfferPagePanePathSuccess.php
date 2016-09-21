<?php
	/* @var $offer_page \Flux\OfferPage */
	$offer_page = $this->getContext()->getRequest()->getAttribute("offer_page", array());
?>

<div class="help-block">Use this page to manage different offers displayed to a user in a regpath.</div>
<br/>
<!-- This div will be copied for new rules and the dummy_pagepath_div will be replaced with the current index -->
<div class="form-group path-group-item col-sm-4" style="display:none;" id="dummy_pagepath_div">
	<div class="col-sm-12">
		<h3>Offer #dummy_pagepath_id</h3>
	</div>
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="pull-right">
					<a class="btn-sm btn-info add_offer_btn add_offer_btn-dummy_pagepath_id" href="/offer/offer-page-pane-path-offer-modal?position=dummy_pagepath_id&offer_page_id=<?php echo $offer_page->getId() ?>" data-toggle="modal" data-target="#path_offer_modal">select offer asset</a>
					<a href="#" class="btn-sm btn-danger btn_remove_offer">X</a>
				</div>
				<h3 class="panel-title">Asset</h3>
			</div>
			<div class="panel-body">
				<div class="offer_page_path_navigation_div-dummy_pagepath_id">
					<input type="hidden" name="offer_page_paths[dummy_pagepath_id][position]" value="dummy_pagepath_id" />
					<input type="hidden" name="offer_page_paths[dummy_pagepath_id][offer_id]" value="" />
					<input type="hidden" name="offer_page_paths[dummy_pagepath_id][destination_url]" value="" />
					<input type="hidden" name="offer_page_paths[dummy_pagepath_id][offer_asset_id]" value="" />
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<hr />
</div>
<form class="form-horizontal" id="offer_page_flow_form" name="offer_page_flow_form" method="PUT" action="/offer/offer-page-flow" autocomplete="off" role="form">
	<input type="hidden" name="_id" value="<?php echo $offer_page->getId() ?>" />
	<div id="path_offers">
		
	</div>
	<div class="clearfix"></div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-4">
			<button type="button" class="btn btn-info" id="add_offer_btn">Add Offer</button>
			<input type="submit" name="__saveEvents" class="btn btn-success" value="Save Path Offers" />
		</div>
		<div class="col-sm-6">
			 <div id="path_changes_alert" style="display:none;">
				<div class="alert alert-warning">
					<span class="glyphicon glyphicon-arrow-left pull-left"><span aria-hidden="true"></span><span class="sr-only">Alert</span></span>
					&nbsp;You have made changes to this path and they have not been saved.  Click Save Path Offers to save your changes.
				</div>
			</div>
		</div>
	</div>
</form>
<div class="clearfix"></div>
<!-- Push offer to server modal -->
<div class="modal fade" id="path_offer_modal">
	<div class="modal-dialog">
		<div class="modal-content"></div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
//<!--
$(document).ready(function() {
	/* Remove a rule from the flow rules */
	$('#path_offers').on('click', '.btn_remove_offer', function() {
	   	$(this).closest('.path-group-item').remove();
	});

	/* Handle the form submit */
	$('#offer_page_path_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Path offers updated', 'The path offers have been saved to this offer');
			$('#path_changes_alert').hide();
		}
	},{keep_form:true});

	/* Clear the filter modal when it is hidden */
	$('#path_offer_modal').on('hidden.bs.modal', function() {
		$(this).removeData();
	});

	/* Add a new  rule to the flow rules*/
	$('#add_offer_btn').on('click', function() {
		var index_number = ($('#path_offers > .path-group-item').length + 1);
		var pagepath_div = $('#dummy_pagepath_div').clone(true, true);
		pagepath_div.html(function(i, oldHTML) {
			oldHTML = oldHTML.replace(/pagepathDummyReqName/g, 'offer_page_paths');
			oldHTML = oldHTML.replace(/dummy_pagepath_id/g, index_number);
			return oldHTML;
		});
		pagepath_div.removeAttr('id');
		// Recreate the navigation selectize
		$('#path_offers').append(pagepath_div);
		pagepath_div.show();
	});
});
//-->
</script>