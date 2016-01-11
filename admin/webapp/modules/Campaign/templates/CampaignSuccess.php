<?php
	/* @var $user Flux\User */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart', 'controls']}]}"></script>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/campaign/campaign-search">Campaigns</a></li>
	<li><a href="/offer/offer?_id=<?php echo $campaign->getOffer()->getOfferId() ?>"><?php echo $campaign->getOffer()->getOfferName() ?></a></li>
	<li class="active">Campaign #<?php echo $campaign->getKey() ?></li>
</ol>

<div class="panel-main">
	<div class="media">
		<div class="media-left">
			<img class="thumbnail" src="/images/traffic-sources/<?php echo $campaign->getTrafficSource()->getTrafficSourceIcon() ?>_128.png" border="0" />
		</div>
		<div class="media-body">
			<h4 class="media-heading"><?php echo $campaign->getId() ?></h4>
			<div class="text-muted"><?php echo $campaign->getDescription() ?></div>
			<div class="">Owned by <a href="/offer/offer?_id=<?php echo $campaign->getOffer()->getOfferId() ?>"><?php echo $campaign->getOffer()->getOfferName() ?></a></div>
			<div class="">Pays $<?php echo number_format($campaign->getPayout(), 2, null, ',') ?></div><br />
			<div class=""><i><?php echo $campaign->getRedirectLink() ?>?_id=#_id#&s4=<?php echo $campaign->getS4() ?>&s5=<?php echo $campaign->getS5() ?></i></div> 
			<br /><br />
			<div class="">
				<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#edit_modal" href="/campaign/campaign-pane-edit?_id=<?php echo $campaign->getId() ?>"><span class="fa fa-pencil"></span> edit campaign</a>

				<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#instruction_modal" href="/campaign/campaign-pane-instruction?_id=<?php echo $campaign->getId() ?>"><span class="fa fa-edit"></span> view instructions</a>

				<a class="btn btn-sm btn-info" href="<?php echo $campaign->getRedirectLink() ?>" target="_blank"><span class="fa fa-eye"></span> open landing page</a>
				<a data-toggle="modal" id="btn_delete" data-target="#delete_modal" class="btn btn-sm btn-danger" href="#"><span class="fa fa-trash-o"></span> delete</a>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<br />
	<div class="row">
		<div class="col-md-3">
			<div class="thumbnail" style="min-height:220px;">
				<div class="text-center">
					<div class="fa fa-dollar fa-4x" style="width:128px;padding-Top:35px;"></div>
				</div>
				<div class="caption">
					<h4><?php echo $campaign->getClient()->getClientName() ?></h4>
					<div class="text-muted small">Traffic is purchased by the affiliate <i><?php echo $campaign->getClient()->getClientName() ?></i> and a payout of <i>$<?php echo number_format($campaign->getPayout(), 2, null, ',') ?></i> will be paid to them for conversions generated.</div>
				</div>
			</div>
		</div>
		<div class="col-md-1 text-center text-muted hidden-xs hidden-sm" style="padding-Top:25px;">
			<img src="/images/vspacer.png" border="0" />
		</div>
		<div class="col-md-1 text-center text-muted visible-xs visible-sm" style="padding:0px 0px 15px 0px;">
			<div class="fa fa-arrow-circle-down fa-2x"></div>
		</div>
		<div class="col-md-3">
			<div class="thumbnail" style="min-height:220px;">
				<img src="/images/traffic-sources/<?php echo $campaign->getTrafficSource()->getTrafficSourceIcon() ?>_64.png" width="102" border="0" />
				<div class="caption">
					<h4><?php echo $campaign->getTrafficSource()->getTrafficSourceName() ?></h4>
					<div class="small text-muted">
						Ads bought on <i><?php echo $campaign->getTrafficSource()->getTrafficSourceName() ?></i> should redirect to the landing page at <i><?php echo (defined("MO_REALTIME_URL") ? MO_REALTIME_URL : "") . '/r?' . \Flux\DataField::DATA_FIELD_REF_CAMPAIGN_KEY . '=' . $campaign->getId() ?>&amp;__clear=1</i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-1 text-center text-muted hidden-xs hidden-sm" style="padding-Top:25px;">
			<img src="/images/vspacer.png" border="0" />
		</div>
		<div class="col-md-1 text-center text-muted visible-xs visible-sm" style="padding:0px 0px 15px 0px;">
			<div class="fa fa-arrow-circle-down fa-2x"></div>
		</div>
		<div class="col-md-3">
			 <div class="thumbnail" style="min-height:220px;">
				<img src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=128x128&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=<?php echo $campaign->getRedirectLink() ?>" width="128" border="0" />
				<div class="caption">
					<h4><?php echo $campaign->getOffer()->getOfferName() ?></h4>
					<div class="small text-muted">
						Traffic received on this campaign #<?php echo $campaign->getId() ?> will be redirected to <i><?php echo $campaign->getRedirectLink() ?></i>.
					</div>
				</div>
			</div>
		</div>
	</div>
	<div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="myTab">
			<li role="presentation" class="active"><a href="#flow" aria-controls="flow" role="tab" data-toggle="tab">Flow</a></li>
			<li role="presentation"><a href="#leads" data-href="/campaign/campaign-pane-leads.php?_id=<?php echo $campaign->getId() ?>" aria-controls="leads" role="tab" data-toggle="tab">Leads</a></li>
		</ul>
	
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="flow">
				<div class="help-block">When traffic comes through this campaign, it will use the following rules to determine where it is sent.  If no rules can be found, the default landing page will be used.</div>
				<form method="PUT" action="/api" id="campaign_flow_rules_<?php echo $campaign->getId() ?>">
					<input type="hidden" name="_id" value="<?php echo $campaign->getId() ?>" />
					<input type="hidden" name="func" value="/campaign/campaign-flow-rules" />
					<input type="hidden" name="flow_rules" value="" />
					
					<div id="flow-rules"></div>
					<div id="last_rule_defined">
						<div class="row">
							<div class="col-md-1 text-center text-muted hidden-xs hidden-sm">
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-stack-1x fa-inverse">F</i>
								</span>
							</div>
							<div class="col-md-5 form-inline">
								When there are no available rules <i><b>or</b></i> all other rules are capped
								<hr />
								<div class="form-group">
									tag sub id 
									<div class="col-md-4 input-group">
										<span class="input-group-addon">s4</span>
										<input type="text" class="form-control s4" name="s4" value="<?php echo $campaign->getS4() ?>" />
									</div>
									<div class="col-md-4 input-group">
										<span class="input-group-addon">s5</span>
										<input type="text" class="form-control s5" name="s5" value="<?php echo $campaign->getS5() ?>" />
									</div>
									<div class="small help-block"><i class="fa fa-info-circle"></i> you should tag a sub id to change a landing page (such as a phone number or address)</div>
								</div>
							</div>
							<div class="col-md-1 text-center text-muted hidden-xs hidden-sm">
								<img src="/images/vspacer.png" border="0" />
							</div>
							<div class="col-md-3">
								<select name="redirect_link" id="landing_page_default" class="selectize">
									<?php
										/* @var $landing_page \Flux\Link\LandingPage */ 
										foreach ($campaign->getOffer()->getOffer()->getLandingPages() as $landing_page) { 
									?>
										<option value="<?php echo $landing_page->getUrl() ?>" <?php echo (strpos($campaign->getRedirectLink(), $landing_page->getUrl()) === 0) ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $landing_page->getName(), 'url' => $landing_page->getUrl()))) ?>"><?php echo $landing_page->getName() ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<hr />
					</div>
					<div class="text-center">
						<div class="btn btn-success btn-lg" id="add_flow_rule"><i class="fa fa-plus"></i> Add Rule</div>
						<input type="submit" class="btn btn-primary btn-lg" id="save_flow_rules" name="btn_submit" value="Save Rules" />
					</div>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="leads"></div>
		</div>
	</div>
</div>

<!-- edit modal -->
<div class="modal fade" id="edit_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- instruction modal -->
<div class="modal fade" id="instruction_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- confirm delete modal -->
<div class="modal fade" id="delete_modal"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> Are you certain you want to delete this campaign?  All data associated with it will be removed as well.<p /></div><div class="modal-footer"><div id="confirm_delete" class="btn btn-danger">Yes, I'm sure</div> <div class="btn btn-default" data-dismiss="modal">No, close</div></div></div></div></div></div>

<div id="dummy-flow-rule" class="flow-rule" style="display:none;">
	<div class="row">
		<div class="col-md-1 text-center text-muted hidden-xs hidden-sm">
			<span class="fa-stack fa-lg">
				<i class="fa fa-circle fa-stack-2x"></i>
				<i class="fa fa-stack-1x fa-inverse">dummy_id</i>
			</span>
		</div>
		<div class="col-md-5 form-inline">
			Split traffic up to 
			<div class="form-group">
				<div class="input-group col-md-12">
					<input type="text" class="form-control percent" name="flow_rules[dummy_id][percent]" value="" placeholder="enter percentage" />
					<span class="input-group-addon">%</span>
				</div>
			</div>
			<span class="daily_click_count_display"></span>
			<input class="daily_click_count" type="hidden" name="flow_rules[dummy_id][daily_click_count]" value="" />
			<hr />
			<div class="form-group">
				cap traffic at 
				<div class="input-group">
					<input type="text" class="form-control cap" name="flow_rules[dummy_id][cap]" value="" placeholder="leave empty for no cap" />
					<span class="input-group-addon">#</span>
				</div> 
			</div>
			<p />
			<div class="form-group">
				tag sub id 
				<div class="col-md-4 input-group">
					<span class="input-group-addon">s4</span>
					<input type="text" class="form-control s4" name="flow_rules[dummy_id][s4]" value="" />
				</div>
				<div class="col-md-4 input-group">
					<span class="input-group-addon">s5</span>
					<input type="text" class="form-control s5" name="flow_rules[dummy_id][s5]" value="" />
				</div>
				<div class="small help-block"><i class="fa fa-info-circle"></i> you should tag a sub id to change a landing page (such as a phone number or address)</div>
			</div>
		</div>
		<div class="col-md-1 text-center text-muted hidden-xs hidden-sm">
			<img src="/images/vspacer.png" border="0" />
		</div>
		<div class="col-md-3">
			<select name="flow_rules[dummy_id][landing_page]" class="selectize">
				<?php
					/* @var $landing_page \Flux\Link\LandingPage */ 
					foreach ($campaign->getOffer()->getOffer()->getLandingPages() as $landing_page) { 
				?>
					<option value="<?php echo $landing_page->getUrl() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $landing_page->getName(), 'url' => $landing_page->getUrl()))) ?>"><?php echo $landing_page->getName() ?></option>
				<?php } ?>
			</select>
			<p />
			<div class="form-group">
				<textarea type="text" class="form-control name" name="flow_rules[dummy_id][name]" value="" placeholder="enter note for this rule"></textarea>
			</div>
		</div>
		<div class="col-md-2">
			<div class="btn btn-danger btn-remove-flow-rule"><i class="fa fa-trash"></i></div>
		</div>
	</div>
	<hr />
</div>

<script>
//<!--
$(document).ready(function() {	
	// delete the client information
	$('#confirm_delete').click(function() {
		$.rad.del('/api', {func: '/campaign/campaign/<?php echo $campaign->getId() ?>' }, function() {
			window.location = '/campaign/campaign-search';
		});
	});

	$('#add_flow_rule').click(function() {
		$('#flow-rules').trigger('add', {});
	});

	// button to remove data fields
	$('#flow-rules').on('click', '.btn-remove-flow-rule', function() {
		$(this).closest('.flow-rule').remove();		
	}).on('add', function(event, obj) {
		// Add the State dropdown		
		var index_number = $('#flow-rules .flow-rule').length;
		var $flow_rule = $('#dummy-flow-rule').clone(true);
		$flow_rule.removeAttr('id');
		$flow_rule.html(function(i, oldHTML) {
			oldHTML = oldHTML.replace(/dummy_id/g, (index_number + 1));
			return oldHTML;
		});
		
		if (obj.name) { $flow_rule.find('.name').val(obj.name); }
		if (obj.s4) { $flow_rule.find('.s4').val(obj.s4); }
		if (obj.s5) { $flow_rule.find('.s5').val(obj.s5); }
		if (obj.percent) { $flow_rule.find('.percent').val(obj.percent); }
		if (obj.cap) { $flow_rule.find('.cap').val(obj.cap); }
		if (obj.daily_click_count) { 
			$flow_rule.find('.daily_click_count').val(obj.daily_click_count);
			$flow_rule.find('.daily_click_count_display').html(obj.daily_click_count + ' clicks today');
		}
		
		$('#flow-rules').append($flow_rule);
		
		$flow_rule.find('.selectize').selectize({
			valueField: 'url',
			labelField: 'name',
			searchField: ['name'],
			items: [ obj.landing_page ],
			render: {
				item: function(item, escape) {
					var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
					ret_val += '<img class="media-object img-thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=64x64&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=' + escape(item.url) + '" width="64" border="0" />';
					ret_val += '</div><div class="media-body">';
					ret_val += '<h5 class="media-heading">' + escape(item.name) + '</h5>';
					ret_val += '<div class="text-muted small">' + escape(item.url) + '</div>';
					ret_val += '</div></div>';
					return ret_val;
				},
				option: function(item, escape) {
					var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
					ret_val += '<img class="media-object img-thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=64x64&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=' + escape(item.url) + '" width="64" border="0" />';
					ret_val += '</div><div class="media-body">';
					ret_val += '<h5 class="media-heading">' + escape(item.name) + '</h5>';
					ret_val += '<div class="text-muted small">' + escape(item.url) + '</div>';
					ret_val += '</div></div>';
					return ret_val;
				}
			}
		});
		
		$flow_rule.show();
		$('#save_flow_rules').show();
	});

	$('#landing_page_default').selectize({
		valueField: 'url',
		labelField: 'name',
		searchField: ['name'],
		render: {
			item: function(item, escape) {
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				ret_val += '<img class="media-object img-thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=64x64&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=' + escape(item.url) + '" width="64" border="0" />';
				ret_val += '</div><div class="media-body">';
				ret_val += '<h5 class="media-heading">' + escape(item.name) + '</h5>';
				ret_val += '<div class="text-muted small">' + escape(item.url) + '</div>';
				ret_val += '</div></div>';
				return ret_val;
			},
			option: function(item, escape) {
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				ret_val += '<img class="media-object img-thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=64x64&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=' + escape(item.url) + '" width="64" border="0" />';
				ret_val += '</div><div class="media-body">';
				ret_val += '<h5 class="media-heading">' + escape(item.name) + '</h5>';
				ret_val += '<div class="text-muted small">' + escape(item.url) + '</div>';
				ret_val += '</div></div>';
				return ret_val;
			}
		}
	});

	<?php if (count($campaign->getFlowRules()) > 0) { ?>
		<?php 
			/* @var $flow_rule \Flux\Link\FlowRule */
			foreach ($campaign->getFlowRules() as $key => $flow_rule) {
		?>
			$('#flow-rules').trigger('add', <?php echo json_encode($flow_rule->toArray()) ?>);
		<?php } ?>
	<?php } ?>

	$('#myTab a[data-href]').on('show.bs.tab', function(e) {
		var $this = $(this);
		if ($this.data('loaded') != 1) {
			var url = $this.attr('data-href');
		    //Load the page
		    if (url != null) {
			    $($this.attr('href')).load(url, function(data) {
					$this.data('loaded', 1);
			    });
		    }
		}
    });

	$('#campaign_flow_rules_<?php echo $campaign->getId() ?>').form(function(data) {
		$.rad.notify('Rules Saves', 'The rules have been saved to the flow of this campaign and will take effect immediately.')
	},{keep_form:true});
});
//-->
</script>