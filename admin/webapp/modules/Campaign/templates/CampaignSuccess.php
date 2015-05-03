<?php
	/* @var $user Flux\User */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart', 'controls']}]}"></script>
<div class="page-header">
	<!-- Actions -->
	<div class="pull-right">
		<div class="visible-sm visible-xs">
			<div class="btn-group">
  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a data-toggle="modal" data-target="#edit_modal" href="/campaign/campaign-pane-edit?_id=<?php echo $campaign->getId() ?>">edit campaign</a></li>
					<li class="divider"></li>
					<li><a data-toggle="modal" data-target="#instruction_modal" href="/campaign/campaign-pane-instruction?_id=<?php echo $campaign->getId() ?>">view instructions</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $campaign->getRedirectLink() ?>" target="_blank">preview</a></li>
					<li class="divider"></li>
					<li><a data-toggle="modal" id="btn_delete_sm" data-target="#delete_modal" href="#"><span class="text-danger">delete</span></a></li>
				</ul>
			</div>
		</div>
		<div class="hidden-sm hidden-xs">
			<div class="btn-group" role="group">
				<a class="btn btn-info" data-toggle="modal" data-target="#edit_modal" href="/campaign/campaign-pane-edit?_id=<?php echo $campaign->getId() ?>"><span class="glyphicon glyphicon-edit"></span> edit campaign</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-info" data-toggle="modal" data-target="#instruction_modal" href="/campaign/campaign-pane-instruction?_id=<?php echo $campaign->getId() ?>">view instructions</a>
			</div>
			<div class="btn-group" role="group">
                <a class="btn btn-info" href="<?php echo $campaign->getRedirectLink() ?>" target="_blank"><span class="glyphicon glyphicon-eye-open"></span> preview</a>
            </div>
			<a data-toggle="modal" id="btn_delete" data-target="#delete_modal" class="btn btn-danger" href="#">delete</a>
		</div>
	</div>
	<h1><img class="img-thumbnail" src="/images/traffic-sources/<?php echo $campaign->getTrafficSource()->getTrafficSourceIcon() != '' ? $campaign->getTrafficSource()->getTrafficSourceIcon() : 'unknown' ?>_48.png" border="0" /> Campaign for <?php echo $campaign->getOffer()->getOfferName() ?> <small><?php echo $campaign->getClient()->getClientName() ?></small></h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/campaign/campaign-search">Campaigns</a></li>
	<li><a href="/offer/offer?_id=<?php echo $campaign->getOffer()->getOfferId() ?>"><?php echo $campaign->getOffer()->getOfferName() ?></a></li>
	<li class="active">Campaign #<?php echo $campaign->getKey() ?></li>
</ol>

<!-- Page Content -->
<div class="help-block">Get a bird's eye view of this offer and how it is performing below.</div>
<br/>
<!-- content -->
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
            <li class="list-group-item active">Campaign Stats</li>
            <li class="list-group-item">
                <span class="badge"><?php echo number_format($campaign->getDailyClicks(), 0, null, ',') ?></span>
                Today's Clicks
            </li>
            <li class="list-group-item">
                <span class="badge"><?php echo number_format($campaign->getDailyConversions(), 0, null, ',') ?></span>
                Today's Conversions
            </li>
        </ul>
        <ul class="list-group">
            <li class="list-group-item disabled">Landing Page Preview</li>
            <li class="list-group-item text-center">
                <img class="img-thumbnail page_thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=300x300&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=<?php echo urlencode($campaign->getRedirectLink()) ?>" border="0" alt="Loading thumbnail..." data-url="<?php echo $campaign->getRedirectLink() ?>" />
            </li>
        </ul>
    </div>
</div>

<!-- edit modal -->
<div class="modal fade" id="edit_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- instruction modal -->
<div class="modal fade" id="instruction_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- confirm delete modal -->
<div class="modal fade" id="delete_modal"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> Are you certain you want to delete this campaign?  All data associated with it will be removed as well.<p /></div><div class="modal-footer"><div id="confirm_delete" class="btn btn-danger">Yes, I'm sure</div> <div class="btn btn-default" data-dismiss="modal">No, close</div></div></div></div></div></div>

<script>
//<!--
$(document).ready(function() {
	// delete the client information
	$('#confirm_delete').click(function() {
		$.rad.del('/api', {func: '/campaign/campaign/<?php echo $campaign->getId() ?>' }, function() {
			window.location = '/campaign/campaign-search';
		});
	});
});

google.setOnLoadCallback(initialize);

$(window).on('debouncedresize', function() {
	initialize();
});

function initialize() {
	var query1 = new google.visualization.Query('/chart/graph-click-by-hour?date_range=<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>&tz=<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>&group_type=2&campaign_id_array=<?php echo $campaign->getId() ?>');
	query1.send(drawClickByHourChart);

	var query2 = new google.visualization.Query('/chart/graph-conversion-by-hour?date_range=<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>&tz=<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>&group_type=2&campaign_id_array=<?php echo $campaign->getId() ?>');
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