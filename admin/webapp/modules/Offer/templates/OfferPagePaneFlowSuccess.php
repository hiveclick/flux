<?php
	/* @var $offer_page \Flux\OfferPage */
	$offer_page = $this->getContext()->getRequest()->getAttribute("offer_page", array());
?>

<div class="help-block">Use this page to define where a user will be taken when they leave this page.  Rules are processed in order from the top.</div>
<br/>
<!-- This div will be copied for new rules and the dummy_pageflow_id will be replaced with the current index -->
<div class="form-group flow-group-item" style="display:none;" id="dummy_pageflow_div">
	<div class="col-sm-12">
		<h3>Rule #dummy_pageflow_id</h3>
	</div>
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="pull-right">
					<a class="btn-sm btn-info add_filter_btn add_filter_btn-dummy_pageflow_id" href="/offer/offer-page-pane-flow-filter-modal?position=dummy_pageflow_id" data-toggle="modal" data-target="#flow_filter_modal">add/modify filters</a>
				</div>
				<h3 class="panel-title">Filters</h3>
			</div>
			<div class="panel-body">
				<div class="offer_page_flow_filter_div-dummy_pageflow_id"><em>No conditions, all traffic will be accepted</em></div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="pull-right">
					<a class="btn-sm btn-info add_setter_btn add_setter_btn-dummy_pageflow_id" href="/offer/offer-page-pane-flow-setter-modal?position=dummy_pageflow_id" data-toggle="modal" data-target="#flow_setter_modal">add/modify setters</a>
				</div>
				<h3 class="panel-title">Setters</h3>
			</div>
			<div class="panel-body">
				<div class="offer_page_flow_setter_div-dummy_pageflow_id"><em>-- No Setters --</em></div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="pull-right">
					<a class="btn-sm btn-info add_navigation_btn add_navigation_btn-dummy_pageflow_id" href="/offer/offer-page-pane-flow-navigation-modal?position=dummy_pageflow_id&offer_page_id=<?php echo $offer_page->getId() ?>" data-toggle="modal" data-target="#flow_navigation_modal">set destination page</a>
					<a href="#" class="btn-sm btn-danger btn_remove_rule">X</a>
				</div>
				<h3 class="panel-title">Navigation</h3>
			</div>
			<div class="panel-body">
				<div class="offer_page_flow_navigation_div-dummy_pageflow_id">
					Redirect to the next page in the flow <em>(default action)</em>
					<input type="hidden" name="offer_page_flows[dummy_pageflow_id][navigation][navigation_type]" value="1" />
					<input type="hidden" name="offer_page_flows[dummy_pageflow_id][navigation][destination_offer_page_id]" value="0" />
					<input type="hidden" name="offer_page_flows[dummy_pageflow_id][navigation][remote_url]" value="" />
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<hr />
</div>
<form class="form-horizontal" id="offer_page_flow_form" name="offer_page_flow_form" method="PUT" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/offer/offer-page-flow" />
	<input type="hidden" name="_id" value="<?php echo $offer_page->getId() ?>" />
	<div id="flow_rules">
		<?php
			/* @var $offer_page_flow \Flux\OfferPageFlow */
			foreach ($offer_page->getOfferPageFlows() as $key => $offer_page_flow) {
				
		?>
			<div class="form-group flow-group-item">
				<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][filter_type]" value="<?php echo $offer_page_flow->getFilterType() ?>" />
				<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][position]" value="<?php echo $offer_page_flow->getPosition() ?>" />
				<div class="col-sm-12"><h3>Rule #<?php echo ($key + 1) ?></h3></div>
				<div class="col-sm-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="pull-right">
								<a class="btn-sm btn-info add_filter_btn add_filter_btn-<?php echo ($key + 1) ?>" href="/offer/offer-page-pane-flow-filter-modal?<?php echo http_build_query($offer_page_flow->toArray(true), null, '&') ?>" data-toggle="modal" data-target="#flow_filter_modal">add/modify filters</a>
							</div>
							<h3 class="panel-title">Filters</h3>
						</div>
						<div class="panel-body">
							<div class="offer_page_flow_filter_div-<?php echo ($key + 1) ?>">
								<?php if (count($offer_page_flow->getFilterConditions()) > 0) { ?>
									<?php if ($offer_page_flow->getFilterType() == \Flux\OfferPageFlow::FILTER_TYPE_ALL) { ?>
										<div class="offer_page_flow_filter_description_filter_type">Filters applied when <strong>all</strong> conditions match:</div>
									<?php } else { ?>
										<div class="offer_page_flow_filter_description_filter_type">Filters applied when <strong>any</strong> condition matches:</div>
									<?php } ?>
									<ul>
									<?php foreach ($offer_page_flow->getFilterConditions() as $filter_key => $filter_condition) { ?>
										<li>
											When the data field <strong><?php echo $filter_condition->getDataField()->getName() ?></strong> 
											<em>
												<?php if ($filter_condition->getFilterOp() == \Flux\OfferPageFlowFilter::FILTER_OP_CONTAINS) { ?>
													contains
												<?php } else if ($filter_condition->getFilterOp() == \Flux\OfferPageFlowFilter::FILTER_OP_BEGINS_WITH) { ?>
													begins with
												<?php } else if ($filter_condition->getFilterOp() == \Flux\OfferPageFlowFilter::FILTER_OP_ENDS_WITH) { ?>
													ends with
												<?php } else if ($filter_condition->getFilterOp() == \Flux\OfferPageFlowFilter::FILTER_OP_IS) { ?>
													is
												<?php } ?>
											</em> <strong><?php echo implode(", ", $filter_condition->getFilterValue()) ?></strong>
										<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][filter_conditions][<?php echo ($filter_key + 1) ?>][data_field_id]" value="<?php echo $filter_condition->getDataFieldId() ?>" />
										<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][filter_conditions][<?php echo ($filter_key + 1) ?>][filter_op]" value="<?php echo $filter_condition->getFilterOp() ?>" />
										<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][filter_conditions][<?php echo ($filter_key + 1) ?>][filter_value]" value="<?php echo implode(",",$filter_condition->getFilterValue()) ?>" />
										</li>
									<?php } ?>
									</ul>
								<?php } else { ?>
									<em>No conditions, all traffic will be accepted</em>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="pull-right">
								<a class="btn-sm btn-info add_setter_btn add_setter_btn-<?php echo ($key + 1) ?>" href="/offer/offer-page-pane-flow-setter-modal?<?php echo http_build_query($offer_page_flow->toArray(true), null, '&') ?>" data-toggle="modal" data-target="#flow_setter_modal">add/modify setters</a>
							</div>
							<h3 class="panel-title">Setters</h3>
						</div>
						<div class="panel-body">
							<div class="offer_page_flow_setter_div-<?php echo ($key + 1) ?>">
								<?php if (count($offer_page_flow->getSetters()) > 0) { ?>
									Assign the following fields:
									<ul>
										<?php foreach ($offer_page_flow->getSetters() as $setter_key => $setter) { ?>
										<li>
											<em>
												<?php if ($setter->getSetterOp() == \Flux\OfferPageFlowSetter::SETTER_SET) { ?>
													Set
												<?php } else if ($setter->getSetterOp() == \Flux\OfferPageFlowSetter::SETTER_SET_IF_EMPTY) { ?>
													Set (if blank)
												<?php } else if ($setter->getSetterOp() == \Flux\OfferPageFlowSetter::SETTER_INCREMENT) { ?>
													Increment
												<?php } else if ($setter->getSetterOp() == \Flux\OfferPageFlowSetter::SETTER_DECREMENT) { ?>
													Decrement
												<?php } ?>
											</em>
											data field <strong><?php echo $setter->getDataField()->getName() ?></strong> 
											<?php if ($setter->getSetterOp() == \Flux\OfferPageFlowSetter::SETTER_SET) { ?>
												to
											<?php } else if ($setter->getSetterOp() == \Flux\OfferPageFlowSetter::SETTER_SET_IF_EMPTY) { ?>
												to
											<?php } else if ($setter->getSetterOp() == \Flux\OfferPageFlowSetter::SETTER_INCREMENT) { ?>
												by
											<?php } else if ($setter->getSetterOp() == \Flux\OfferPageFlowSetter::SETTER_DECREMENT) { ?>
												by
											<?php } ?>
											<strong><?php echo $setter->getSetterValue() ?></strong>
											<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][setters][<?php echo ($setter_key + 1) ?>][data_field_id]" value="<?php echo $setter->getDataFieldId() ?>" />
											<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][setters][<?php echo ($setter_key + 1) ?>][setter_op]" value="<?php echo $setter->getSetterOp() ?>" />
											<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][setters][<?php echo ($setter_key + 1) ?>][setter_value]" value="<?php echo $setter->getSetterValue() ?>" />
										</li>
									<?php } ?>
									</ul>
								<?php } else { ?>
									<em>-- No Setters --</em>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="pull-right">
								<a class="btn-sm btn-info add_navigation_btn add_navigation_btn-<?php echo ($key + 1) ?>" href="/offer/offer-page-pane-flow-navigation-modal?<?php echo http_build_query($offer_page_flow->toArray(true), null, '&') ?>&offer_page_id=<?php echo $offer_page->getId() ?>" data-toggle="modal" data-target="#flow_navigation_modal">set destination page</a>
								<a href="#" class="btn-sm btn-danger btn_remove_rule">X</a>
							</div>
							<h3 class="panel-title">Navigation</h3>
						</div>
						<div class="panel-body">
							<div class="offer_page_flow_navigation_div-<?php echo ($key + 1) ?>">
								<?php if ($offer_page_flow->getNavigation()->getNavigationType() == \Flux\OfferPageFlowNavigation::NAVIGATION_TYPE_LOCAL) { ?>
									<?php if ($offer_page_flow->getNavigation()->getDestinationOfferPageId() > 0) { ?>
										Redirect to another page in this offer:
										<p />
										<div>
											<div class="media">
												<span class="pull-left thumbnail">
													<?php if (trim($offer_page_flow->getNavigation()->getDestinationOfferPage()->getPreviewUrl()) != '') { ?>
			 											<img width="75" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=150x150&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=<?php echo $offer_page_flow->getNavigation()->getDestinationOfferPage()->getPreviewUrl() ?>" border="0" />
			 										<?php } else { ?>
			 											<img src="/images/no_preview.png" border="0" />
			 										<?php } ?>
												</span>
			  									<div class="media-body">
			  										<div class="title">
			  											<span class="name"><?php echo trim($offer_page_flow->getNavigation()->getDestinationOfferPage()->getName()) != '' ? $offer_page_flow->getNavigation()->getDestinationOfferPage()->getName() : 'No Name' ?></span>
			  										</div> 
												<span class="description"><?php echo trim($offer_page_flow->getNavigation()->getDestinationOfferPage()->getDescription()) != '' ? $offer_page_flow->getNavigation()->getDestinationOfferPage()->getDescription() : 'no description' ?></span>
												<div class="text-success small"><?php echo $offer_page_flow->getNavigation()->getDestinationOfferPage()->getPageName() ?></div>
													</div>
												</div> 
											</div>
										<?php } else { ?>
											Redirect to the next page in the flow <em>(default action)</em>
										<?php } ?>
								<?php } else { ?>
									Redirect to an external url:
									<p />
									<div>
										<div class="media">
											<span class="pull-left thumbnail">
												<img width="128" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=150x150&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=<?php echo urlencode($offer_page_flow->getNavigation()->getRemoteUrl()) ?>" border="0" />
			 								</span>
			 							</div>
			 							<div class="media-body">
											<div style="word-break: break-all;" class="small"><?php echo $offer_page_flow->getNavigation()->getRemoteUrl() ?></div>
										</div>
									</div>
								<?php } ?>
								<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][navigation][navigation_type]" value="<?php echo $offer_page_flow->getNavigation()->getNavigationType() ?>" />
								<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][navigation][destination_offer_page_id]" value="<?php echo $offer_page_flow->getNavigation()->getDestinationOfferPageId() ?>" />
								<input type="hidden" name="offer_page_flows[<?php echo ($key + 1) ?>][navigation][remote_url]" value="<?php echo htmlentities($offer_page_flow->getNavigation()->getRemoteUrl()) ?>" />
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<hr />
			</div>
		<?php } ?>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-4">
			<button type="button" class="btn btn-info" id="add_page_btn">Add Rule</button>
			<input type="submit" name="__saveEvents" class="btn btn-success" value="Save Page Flow" />
		</div>
		<div class="col-sm-6">
			 <div id="changes_alert" style="display:none;">
				<div class="alert alert-warning">
					<span class="glyphicon glyphicon-arrow-left pull-left"><span aria-hidden="true"></span><span class="sr-only">Alert</span></span>
					&nbsp;You have made changes to this flow and they have not been saved.  Click Save Page Flow to save your changes.
				</div>
			</div>
		</div>
	</div>
   
</form>
<div class="clearfix"></div>
<!-- Push offer to server modal -->
<div class="modal fade" id="flow_filter_modal">
	<div class="modal-dialog">
		<div class="modal-content"></div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Push offer to server modal -->
<div class="modal fade" id="flow_setter_modal">
	<div class="modal-dialog">
		<div class="modal-content"></div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Push offer to server modal -->
<div class="modal fade" id="flow_navigation_modal">
	<div class="modal-dialog">
		<div class="modal-content"></div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
//<!--
$(document).ready(function() {
	$selectize_navigation_options = {
		valueField: '_id',
		labelField: 'name',
		searchField: ['name', 'description'],
		render: {
			item: function(item, escape) {
				return '<div>' +
					'<div class="media">' +
					'<span class="pull-left thumbnail">' + 
			 		(item.preview_url ? '<img width="75" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=150x150&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=' + escape(item.preview_url) + '" border="0" />' : '<img src="/images/no_preview.png" border="0" />') +
			  	   '</span>' + 
			  		'<div class="media-body">' +
					'<span class="title">' + 
					'<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
					'</span>' +
					'<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
					'<div class="text-success small">' + item.page_name + '</div>' +
					'</div>' +
					'</div>' + 
					'</div>';
			},
			option: function(item, escape) {
				return '<div>' +
					'<div class="media">' +
					'<span class="pull-left thumbnail">' + 
			 		(item.preview_url ? '<img width="75" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=150x150&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=' + escape(item.preview_url) + '" border="0" />' : '<img src="/images/no_preview.png" border="0" />') +
			  		'</span>' + 
			  		'<div class="media-body">' +
					'<span class="title">' + 
					'<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
					'</span>' +
					'<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
					'<div class="text-success small">' + item.page_name + '</div>' +
					'</div>' +
					'</div>' + 
					'</div>';
			}
		},
	};

	$('#flow_rules').on('click', '.manage_destination_page', function(event) {
		$(this).attr('href', '/offer/offer-page?_id=' + $(this).siblings('.offer_page_flow_navigation').val());
	});

	/* Setup selectize for the navigation pages */
	$('#flow_rules .offer_page_flow_navigation').each(function(i,item) {
		$(item).selectize($selectize_navigation_options);
	});

	/* Remove a rule from the flow rules */
	$('#flow_rules').on('click', '.btn_remove_rule', function() {
	   	$(this).closest('.flow-group-item').remove();
	});

	/* Handle the form submit */
	$('#offer_page_flow_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Page Flow updated', 'The page flow have been saved to the offer');
			$('#changes_alert').hide();
		}
	},{keep_form:true});

	/* Clear the filter modal when it is hidden */
	$('#flow_filter_modal').on('hidden.bs.modal', function() {
		$(this).removeData();
	});

	/* Clear the setter modal when it is hidden */
	$('#flow_setter_modal').on('hidden.bs.modal', function() {
		$(this).removeData();
	});

	/* Clear the navigation modal when it is hidden */
	$('#flow_navigation_modal').on('hidden.bs.modal', function() {
		$(this).removeData();
	});

	/* Add a new  rule to the flow rules*/
	$('#add_page_btn').on('click', function() {
		var index_number = ($('#flow_rules > .flow-group-item').length + 1);
		var pageflow_div = $('#dummy_pageflow_div').clone(true, true);
		pageflow_div.html(function(i, oldHTML) {
			oldHTML = oldHTML.replace(/pageflowDummyReqName/g, 'offer_page_flows');
			oldHTML = oldHTML.replace(/dummy_pageflow_id/g, index_number);
			return oldHTML;
		});
		pageflow_div.removeAttr('id');
		// Recreate the navigation selectize
		pageflow_div.find('.offer_page_flow_navigation').selectize($selectize_navigation_options);
		$('#flow_rules').append(pageflow_div);
		pageflow_div.show();
	});
});
//-->
</script>