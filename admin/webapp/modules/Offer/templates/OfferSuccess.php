<?php
	/* @var $offer Flux\Offer */
	$offer = $this->getContext()->getRequest()->getAttribute("offer", array());
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart', 'controls']}]}"></script>
<div class="page-header">
	<!-- Actions -->
	<div class="pull-right">
		<div class="visible-sm visible-xs">
			<div class="btn-group">
  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a data-toggle="modal" data-target="#edit_modal" href="/offer/offer-pane-edit?_id=<?php echo $offer->getId() ?>">edit offer</a></li>
					<li class="divider"></li>
					<li><a href="/offer/offer-page-search?_id=<?php echo $offer->getId() ?>">view pages</a></li>
					<li><a href="/offer/offer-event-search?_id=<?php echo $offer->getId() ?>">view events</a></li>
					<li class="divider"></li>
					<li><a data-toggle="modal" data-target="#instruction_modal" href="/offer-pane-instruction?_id=<?php echo $offer->getId() ?>">view instructions</a></li>
					<li class="divider"></li>
					<li><a data-toggle="modal" id="btn_delete_sm" data-target="#delete_modal" href="#"><span class="text-danger">delete</span></a></li>
				</ul>
			</div>
		</div>
		<div class="hidden-sm hidden-xs">
			<div class="btn-group" role="group">
				<a class="btn btn-info" data-toggle="modal" data-target="#edit_modal" href="/offer/offer-pane-edit?_id=<?php echo $offer->getId() ?>">edit offer</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-info" href="/offer/offer-page-search?_id=<?php echo $offer->getId() ?>">view pages</a>
				<a class="btn btn-info" href="/offer/offer-event-search?_id=<?php echo $offer->getId() ?>">view events</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-info" data-toggle="modal" data-target="#instruction_modal" href="/offer/offer-pane-instruction?_id=<?php echo $offer->getId() ?>">view instructions</a>
			</div>
			<a data-toggle="modal" id="btn_delete" data-target="#delete_modal" class="btn btn-danger" href="#">delete</a>
		</div>
	</div>
	<h1><?php echo $offer->getName() ?></h1>
</div>
<ol class="breadcrumb">
	<li><a href="/offer/offer-search">Offers</a></li>
	<li class="active"><?php echo $offer->getName() ?></li>
</ol>
<div class="help-block">Get a bird's eye view of this offer and how it is performing below.</div>

<!-- main col right -->
<div class="col-sm-8">
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
<div class="col-sm-4">
    <?php if ($offer->getRedirectType() != \Flux\Offer::REDIRECT_TYPE_POST) { ?>
	<div class="panel panel-default text-center">
		<?php foreach ($offer->getOfferPages() as $offer_page) { ?>	
			<img id="offer_thumbnail_img" class="img-thumbnail page_thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=300x300&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=<?php echo urlencode($offer_page->getPreviewUrl()) ?>" border="0" alt="Loading thumbnail..." data-url="<?php echo $offer_page->getPreviewUrl() ?>" />
		<?php 
				break;
			} 
		?>
		<p />
		<div>
			<a class="btn btn-info" href="<?php echo $offer->getPreviewUrl() ?>" target="_blank">Preview Landing Page</a>
			<p />
			<small><?php echo $offer->getPreviewUrl() ?></small>
		</div>
	</div>
	<p />
	<?php } ?>
	<div class="panel panel-default text-center">
		<div class="panel-heading">
			Today's Stats
		</div>
		<div class="panel-body">
		<h4><?php echo number_format($offer->getDailyClicks(), 0, null, ',') ?> Clicks</h4>
		<h4><?php echo number_format($offer->getDailyConversions(), 0, null, ',') ?> Conversions</h4>
		</div>
	</div>
</div>

<!-- edit modal -->
<div class="modal fade" id="edit_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- instruction modal -->
<div class="modal fade" id="instruction_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- Push offer to server modal -->
<div class="modal fade" id="pushToServerModal"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<script>
//<!--
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
			chartArea:{ left:'0%', top: '8%', width: '80%', height:'80%' }
		},
	    containerId: 'click_by_hour_chart_div'
    });
    var chart_range_control = new google.visualization.ControlWrapper({ containerId: 'click_by_hour_filter_div', controlType: 'ChartRangeFilter', options: { filterColumnLabel: 'Hour', ui: { chartType: 'LineChart', chartOptions: { chartArea: {left:'0%',width: '95%'}, hAxis: { gridlines: {color: '#eaeaea', count: 30}, minorGridlines: {color: '#f4f4f4', count: 1}, baselineColor: 'none', textStyle: { color: '#737373', fontSize: 11 }}}, minRangeSize: 86400000 /* 1 day */ }}, state: { range: { start: new Date(<?php echo date('Y', strtotime('today')) ?>, <?php echo date('m', strtotime('today'))-1 ?>, <?php echo date('d', strtotime('today')) ?>), end: new Date(<?php echo date('Y', strtotime('tomorrow')) ?>, <?php echo date('m', strtotime('tomorrow'))-1 ?>, <?php echo date('d', strtotime('tomorrow')) ?>) }}});
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
			chartArea:{ left:'0%', top: '8%', width: '80%', height:'80%' }
		},
	    containerId: 'conversion_by_hour_chart_div'
    });
    var chart_range_control = new google.visualization.ControlWrapper({ containerId: 'conversion_by_hour_filter_div', controlType: 'ChartRangeFilter', options: { filterColumnLabel: 'Hour', ui: { chartType: 'LineChart', chartOptions: { chartArea: {left:'0%',width: '95%'}, hAxis: { gridlines: {color: '#eaeaea', count: 30}, minorGridlines: {color: '#f4f4f4', count: 1}, baselineColor: 'none', textStyle: { color: '#737373', fontSize: 11 }}}, minRangeSize: 86400000 /* 1 day */ }}, state: { range: { start: new Date(<?php echo date('Y', strtotime('today')) ?>, <?php echo date('m', strtotime('today'))-1 ?>, <?php echo date('d', strtotime('today')) ?>), end: new Date(<?php echo date('Y', strtotime('tomorrow')) ?>, <?php echo date('m', strtotime('tomorrow'))-1 ?>, <?php echo date('d', strtotime('tomorrow')) ?>) }}});
    dashboard.bind(chart_range_control, chart);
    dashboard.draw(data); 
}
//-->
</script>