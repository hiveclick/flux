<?php
	/* @var $offer Flux\Offer */
	$offer = $this->getContext()->getRequest()->getAttribute("offer", array());
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart', 'controls']}]}"></script>

<ol class="breadcrumb small" style="margin-bottom:0px;">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/offer/offer-search">Offers</a></li>
	<li><a href="/offer/offer-search?vertical_id_array[]=<?php echo $offer->getVertical()->getVerticalId() ?>"><?php echo $offer->getVertical()->getVerticalName() ?></a></li>
	<li class="active"><?php echo $offer->getName() ?></li>
</ol>
<div class="panel-main">
	<div class="media">
		<div class="media-left">
			<?php if ($offer->getRedirectType() != \Flux\Offer::REDIRECT_TYPE_POST) { ?>
				<img class="thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=150x150&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=<?php echo urlencode($offer->getDefaultCampaign()->getRedirectUrl()) ?>" border="0" alt="Loading thumbnail..." data-url="<?php echo $offer->getDefaultCampaign()->getRedirectUrl() ?>" width="150" />
			<?php } else { ?>
				<i class="fa fa-cloud-upload fa-4x fa-border" style="background-Color:white;"></i>
			<?php } ?>
		</div>
		<div class="media-body">
			<h4 class="media-heading"><?php echo $offer->getName() ?></h4>
			<div class=""><?php echo $offer->getStatus() == \Flux\Offer::OFFER_STATUS_ACTIVE ? '<i class="label label-success">Active</i>' : '<i class="label label-danger">Inactive</i>' ?></div>
			<div class="">Owned by <?php echo $offer->getClient()->getClientName() ?></div>
			<div class="">Pays $<?php echo number_format($offer->getPayout(), 2, null, ',') ?></div> 
			<br /><br />
			<div class="">
				<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#edit_modal" href="/offer/offer-pane-edit?_id=<?php echo $offer->getId() ?>"><span class="fa fa-pencil"></span> edit offer</a>
				<!--
				<a class="btn btn-sm btn-info" href="/offer/offer-event-search?_id=<?php echo $offer->getId() ?>"><span class="fa fa-eye"></span> view events</a>
				-->
				<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#instruction_modal" href="/offer/offer-pane-instruction?_id=<?php echo $offer->getId() ?>"><span class="fa fa-edit"></span> view instructions</a>
				<a class="btn btn-sm btn-info" href="<?php echo $offer->getDefaultCampaign()->getRedirectUrl() ?>" target="_blank"><span class="fa fa-eye"></span> open landing page</a>
				<a data-toggle="modal" id="btn_delete" data-target="#delete_modal" class="btn btn-sm btn-danger" href="#"><span class="fa fa-trash-o"></span> delete</a>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<br /><br />

	<div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="myTab">
			<li role="presentation" class="active"><a href="#campaigns" aria-controls="campaigns" role="tab" data-toggle="tab">Campaigns</a></li>
			<li role="presentation"><a href="#leads" data-href="/offer/offer-pane-leads.php?offer_id=<?php echo $offer->getId() ?>" aria-controls="leads" role="tab" data-toggle="tab">Leads</a></li>
			<li role="presentation"><a href="#splits" aria-controls="splits" role="tab" data-toggle="tab">Splits</a></li>
			<li role="presentation"><a href="#pages" aria-controls="pages" role="tab" data-toggle="tab">Pages</a></li>
			<li role="presentation"><a href="#graphs" aria-controls="graphs" role="tab" data-toggle="tab">Traffic Graphs</a></li>
		</ul>
	
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="campaigns">
				<br />
				<?php 
					/* @var $campaign \Flux\Campaign */
					foreach ($offer->getCampaigns() as $campaign) {
				?>
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="media">
									<div class="media-left">
										<img class="thumbnail" src="/images/traffic-sources/<?php echo $campaign->getTrafficSource()->getTrafficSourceIcon() ?>_128.png" border="0" width="64" style="margin-bottom:0px;" />
										<div class="text-muted small text-center">
											<?php echo $campaign->getDailyClicks() ?> clicks<br />
											<?php echo $campaign->getDailyConversions() ?> sales
										</div>
									</div>
									<div class="media-body">
										<h5 class="media-heading"><a href="/campaign/campaign?_id=<?php echo $campaign->getId() ?>"><?php echo $campaign->getId() ?></a></h5>
										<div class="text-muted small"><?php echo $campaign->getDescription() ?></div>
										<div class="small"><i><?php echo $campaign->getRedirectUrl() ?></i></div>
										<div class="help-block">
											<div class="btn-group" role="group">
												<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#instruction_modal" href="/offer/offer-pane-instruction?_id=<?php echo $offer->getId() ?>"><span class="fa fa-edit"></span> view instructions</a>
											</div>
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="leads">
				<div class="text-muted text-center" style="padding-Top:125px;">
					<span class="fa fa-spinner fa-spin fa-4x"></span><br />
					Please wait while we load the leads...
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="splits">
				<br />
				<?php 
					/* @var $split \Flux\Split */
					foreach ($offer->getSplits() as $split) {
				?>
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="media">
									<div class="media-left">
										<div class="text-muted small text-center">
											<?php echo $split->getDailyCount() ?> queued<br />
										</div>
									</div>
									<div class="media-body">
										<h5 class="media-heading"><a href="/export/split?_id=<?php echo $split->getId() ?>"><?php echo $split->getName() ?></a></h5>
										<div class="text-muted small"><?php echo $split->getDescription() ?></div>
										<div class="text-muted small">Fulfills to <a href="/admin/fulfillment?_id=<?php echo $split->getFulfillment()->getFulfillmentId() ?>"><?php echo $split->getFulfillment()->getFulfillmentName() ?></a></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="pages">
				<?php
					/* @var $offer_page \Flux\OfferPage */
					foreach ($offer->getOfferPages() as $key => $offer_page) {
				?>
				<div class="col-xs-6 col-sm-4 col-md-3 offer_page_item" id="offer_page_item_<?php echo $offer_page->getId() ?>">
					<div class="thumbnail">
						<div class="thumbnail">
							<img id="offer_page_thumbnail_img_<?php echo $offer_page->getId() ?>" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=300x300&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=<?php echo $offer_page->getPreviewUrl() ?>" class="page_thumbnail" src="" border="0" alt="Loading thumbnail..." />
							<div class="text-center"><i class="small"><?php echo $offer_page->getPageName() ?></i></div>
						</div>
						<div class="caption">
							<h4><?php echo $offer_page->getName() ?></h4>
							<p>
								<?php echo $offer_page->getDescription() ?>
							</p>
							<p>
								<a href="/offer/offer-page-pane-edit?_id=<?php echo $offer_page->getId() ?>" data-toggle="modal" data-target="#offer_page_edit_modal" class="btn btn-sm btn-primary" role="button">Edit</a> 
								<a href="<?php echo $offer_page->getPreviewUrl() ?>" target="_preview" class="btn btn-sm btn-default" role="button">Preview</a>
								<a href="javascript:$('#offer_page_item_<?php echo $offer_page->getId() ?>').trigger('remove', {_id: '<?php echo $offer_page->getId() ?>' });" class="btn btn-sm btn-danger">Delete</a>
							</p>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="graphs">
				<br />
				<div class="help-block">Get a bird's eye view of this offer and how it is performing below.</div>
				<div class="row">
					<!-- main col right -->
					<div class="col-md-9 col-sm-9">
						<div class="panel panel-default">
							<div class="panel-heading">Click Traffic</div>
							<div class="panel-body">
								<div id="click_by_hour_div">
									<!--Divs that will hold each control and chart-->
									<div id="click_by_hour_chart_div" style="width:100%;height:250px">
										<div class="text-muted text-center">
											<span class="fa fa-spinner fa-spin"></span>
											Loading report data...
										</div>
									</div>
									<div id="click_by_hour_filter_div" style="width:100%;height:50px"></div>
								</div>
							</div>
						</div>
					
						<div class="panel panel-default">
							<div class="panel-heading">Conversion Traffic</div>
							<div class="panel-body">
								<div id="conversion_by_hour_div">
									<!--Divs that will hold each control and chart-->
									<div id="conversion_by_hour_chart_div" style="width:100%;height:250px">
										<div class="text-muted text-center">
											<span class="fa fa-spinner fa-spin"></span>
											Loading report data...
										</div>
									</div>
									<div id="conversion_by_hour_filter_div" style="width:100%;height:50px"></div>
								</div>
							</div>
						</div>
					</div>
		
					<!-- main col right -->
					<div class="col-md-3 col-sm-3">
						<ul class="list-group">
							<li class="list-group-item active">Offer Stats</li>
							<li class="list-group-item">
								<span class="badge"><?php echo number_format($offer->getDailyClicks(), 0, null, ',') ?></span>
								Today's Clicks
							</li>
							<li class="list-group-item">
								<span class="badge"><?php echo number_format($offer->getDailyConversions(), 0, null, ',') ?></span>
								Today's Conversions
							</li>
							 <li class="list-group-item">
								<span class="badge">$<?php echo number_format($offer->getPayout(), 2, null, ',') ?></span>
								Default Payout
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- edit modal -->
<div class="modal fade" id="edit_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- instruction modal -->
<div class="modal fade" id="instruction_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- Push offer to server modal -->
<div class="modal fade" id="pushToServerModal"><div class="modal-dialog"><div class="modal-content"></div></div></div>
<!-- confirm delete modal -->
<div class="modal fade" id="delete_modal"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> Are you certain you want to delete this offer?  All data associated with it will be removed as well.<p /></div><div class="modal-footer"><div id="confirm_delete" class="btn btn-danger">Yes, I'm sure</div> <div class="btn btn-default" data-dismiss="modal">No, close</div></div></div></div></div></div>
<!-- edit page modal -->
<div class="modal fade" id="offer_page_edit_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	// delete the client information
	$('#confirm_delete').click(function() {
		$.rad.del('/api', {func: '/offer/offer/<?php echo $offer->getId() ?>' }, function() {
			window.location = '/offer/offer-search';
		});
	});
	
	$('#myTab a[data-href]').on('shown.bs.tab', function(e) {
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

	$('#offer_page_edit_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	$('#pages').delegate('.offer_page_item', 'remove', function(event, obj) {
		if (obj._id) {
			$.rad.del('/api', { func: '/offer/offer-page/' + obj._id }, function(data) {
				$(this).fadeOut(function() {
					$.rad.notify('Offer Page Deleted', 'The offer page has been deleted from the system');
				});
			});
		}
	});
});

google.setOnLoadCallback(initialize);

$(window).on('debouncedresize', function() {
	initialize();
});

function initialize() {
	var query1 = new google.visualization.Query('/chart/graph-click-by-hour?date_range=<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>&tz=<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>&offer_id_array=<?php echo $offer->getId() ?>');
	query1.send(drawClickByHourChart);

	var query2 = new google.visualization.Query('/chart/graph-conversion-by-hour?date_range=<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>&tz=<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>&offer_id_array=<?php echo $offer->getId() ?>');
	query2.send(drawConversionByHourChart);
}

function drawClickByHourChart(response) {
	var data = response.getDataTable();
	var dashboard = new google.visualization.Dashboard(document.getElementById('click_by_hour_div'));
	var chart = new google.visualization.ChartWrapper({
		chartType: "LineChart",
		options: {
			animation:{ duration: 250, easing: 'out' },
			hAxis: {
				gridlines: {color: '#eaeaea', count: -1, units: { days: {format: ["MMM dd"]}, hours: {format: ["h a", "ha"]}}},
				minorGridlines: {color: '#f4f4f4', count: -1, units: { days: {format: ["MMM dd"]}, hours: {format: ["h a", "ha"]}}},
				textStyle: { color: '#737373', fontSize: 11 },
			},
			legend: { textStyle: { color: '#737373', fontSize: 11 }},
			vAxis: { gridlines: {color: '#eaeaea', count: 4}, minorGridlines: {color: '#f4f4f4', count: 1}, textStyle: { color: '#737373', fontSize: 11 }},
			chartArea:{ left:'8%', top: '8%', width: '80%', height:'80%' }
		},
		containerId: 'click_by_hour_chart_div'
	});
	var chart_range_control = new google.visualization.ControlWrapper({ containerId: 'click_by_hour_filter_div', controlType: 'ChartRangeFilter', options: { filterColumnLabel: 'Hour', ui: { chartType: 'LineChart', chartOptions: { chartArea: {left:'8%',width: '90%'}, hAxis: { gridlines: {color: '#eaeaea', count: 30}, minorGridlines: {color: '#f4f4f4', count: 1}, baselineColor: 'none', textStyle: { color: '#737373', fontSize: 11 }}}, minRangeSize: 86400000 /* 1 day */ }}, state: { range: { start: new Date(<?php echo date('Y', strtotime('today')) ?>, <?php echo date('m', strtotime('today'))-1 ?>, <?php echo date('d', strtotime('today')) ?>), end: new Date(<?php echo date('Y', strtotime('tomorrow')) ?>, <?php echo date('m', strtotime('tomorrow'))-1 ?>, <?php echo date('d', strtotime('tomorrow')) ?>) }}});
	dashboard.bind(chart_range_control, chart);
	dashboard.draw(data); 
}

function drawConversionByHourChart(response) {
	var data = response.getDataTable();
	var dashboard = new google.visualization.Dashboard(document.getElementById('conversion_by_hour_div'));
	var chart = new google.visualization.ChartWrapper({
		chartType: "ColumnChart",
		options: {
			animation:{ duration: 250, easing: 'out' },
			hAxis: {
				gridlines: {color: '#eaeaea', count: -1, units: { days: {format: ["MMM dd"]}, hours: {format: ["h a", "ha"]}}},
				minorGridlines: {color: '#f4f4f4', count: -1, units: { days: {format: ["MMM dd"]}, hours: {format: ["h a", "ha"]}}},
				textStyle: { color: '#737373', fontSize: 11 },
			},
			isStacked: true,
			bar: { groupWidth: 17 },
			legend: { textStyle: { color: '#737373', fontSize: 11 }},
			vAxis: { gridlines: {color: '#eaeaea', count: 4}, minorGridlines: {color: '#f4f4f4', count: 1}, textStyle: { color: '#737373', fontSize: 11 }},
			chartArea:{ left:'8%', top: '8%', width: '80%', height:'80%' }
		},
		containerId: 'conversion_by_hour_chart_div'
	});
	var chart_range_control = new google.visualization.ControlWrapper({ containerId: 'conversion_by_hour_filter_div', controlType: 'ChartRangeFilter', options: { filterColumnLabel: 'Hour', ui: { chartType: 'LineChart', chartOptions: { chartArea: {left:'8%',width: '90%'}, hAxis: { gridlines: {color: '#eaeaea', count: 30}, minorGridlines: {color: '#f4f4f4', count: 1}, baselineColor: 'none', textStyle: { color: '#737373', fontSize: 11 }}}, minRangeSize: 86400000 /* 1 day */ }}, state: { range: { start: new Date(<?php echo date('Y', strtotime('today')) ?>, <?php echo date('m', strtotime('today'))-1 ?>, <?php echo date('d', strtotime('today')) ?>), end: new Date(<?php echo date('Y', strtotime('tomorrow')) ?>, <?php echo date('m', strtotime('tomorrow'))-1 ?>, <?php echo date('d', strtotime('tomorrow')) ?>) }}});
	dashboard.bind(chart_range_control, chart);
	dashboard.draw(data); 
}
//-->
</script>