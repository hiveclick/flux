<?php 
	/* @var $revenue_report \Flux\ReportClient */
	$today_revenue = $this->getContext()->getRequest()->getAttribute('today_revenue', 0);
	$yesterday_revenue = $this->getContext()->getRequest()->getAttribute('yesterday_revenue', 0);
	$monthly_revenue = $this->getContext()->getRequest()->getAttribute('monthly_revenue', 0);
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart', 'controls']}]}"></script>

<div class="hidden-md hidden-lg hidden-sm visible-xs text-center">
	<div class="container-fluid">
		<!-- For the mobile dashboard, only show what is necessary for quick glimpses -->
		<h4>Revenue</h4>
		<h1 class="text-success">$<?php echo number_format($today_revenue, 2, null, ',') ?></h1>
		<hr />
		<h4 class="text-muted">MTD Revenue</h4>
		<h1 class="text-muted">$<?php echo number_format($monthly_revenue, 2, null, ',') ?></h1>
		<hr />
		<h4 class="text-warning">Yesterday Revenue</h4>
		<h1 class="text-warning">$<?php echo number_format($yesterday_revenue, 2, null, ',') ?></h1>
		<hr />
		<a href="/report/revenue-report" class="btn btn-success col-xs-12">Revenue Report</a>
		<div class="clearfix"></div>
		<hr />
		<a href="/export/split-queue-search" class="btn btn-info col-xs-12">Queued Leads</a>
	</div>
</div>
<div class="visible-md visible-lg visible-sm hidden-xs">
	<!-- Add breadcrumbs -->
	<ol class="breadcrumb small">
		<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	</ol>
	<div class="jumbotron panel-main">
		<div class="container-fluid">
			<h1>Dashboard</h1>
			<p class="lead">Flux Lead Manager Version 1.0.1</p>
			<p>
			</p><div class="container-fluid">
				<div class="row">
					<a class="btn btn-info" href="/offer/offer-search" role="button"><span class="fa fa-th"></span> Offers</a>
					<a class="btn btn-info" href="/lead/lead-search" role="button"><span class="fa fa-list-alt"></span> Leads</a>
					<a class="btn btn-info" href="/report/revenue-report" role="button"><span class="fa fa-bar-chart"></span> Reports</a>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<!-- main col left -->
			<div class="col-md-8 col-lg-8 col-sm-8 hidden-xs" id="main_graph_div">
				<ul class="list-group">
					<li class="list-group-item active">
						Conversion Traffic
						<span id="conversion_wait_div" class="pull-right"><span class="fa fa-spinner fa-spin"></span></span>
					</li>
					<li class="list-group-item">
						<div id="conversion_by_hour_div">
							<!--Divs that will hold each control and chart-->
							<div id="conversion_by_hour_chart_div" style="width:100%;height:350px">
								<div class="text-muted text-center" style="padding-Top:125px;">
									<span class="fa fa-spinner fa-spin fa-4x"></span><br />
									Please wait while we load report data...
								</div>
							</div>
							<div id="conversion_by_hour_filter_div" style="width:100%;height:30px"></div>
						</div>
					</li>
				</ul>
				<ul class="list-group">
					<li class="list-group-item active">
						Clicks by Traffic Source
						<span id="click_ts_wait_div" class="pull-right"><span class="fa fa-spinner fa-spin"></span></span>
					</li>
					<li class="list-group-item">
						<div id="traffic_source_by_hour_div">
							<!--Divs that will hold each control and chart-->
							<div id="traffic_source_by_hour_chart_div" style="width:100%;height:300px">
								<div class="text-muted text-center" style="padding-Top:100px;">
									<span class="fa fa-spinner fa-spin fa-4x"></span><br />
									Please wait while we load report data...
								</div>
							</div>
							<div id="traffic_source_by_hour_filter_div" style="width:100%;height:30px"></div>
						</div>
					</li>
				</ul>
				<ul class="list-group">
					<li class="list-group-item active">
						Click Traffic
						<span id="click_wait_div" class="pull-right"><span class="fa fa-spinner fa-spin"></span></span>
					</li>
					<li class="list-group-item">
						<div id="click_by_hour_div">
							<!--Divs that will hold each control and chart-->
							<div id="click_by_hour_chart_div" style="width:100%;height:300px">
								<div class="text-muted text-center" style="padding-Top:100px;">
									<span class="fa fa-spinner fa-spin fa-4x"></span><br />
									Please wait while we load report data...
								</div>
							</div>
							<div id="click_by_hour_filter_div" style="width:100%;height:30px"></div>
						</div>
					</li>
				</ul>
				
			</div>
		
			<!-- main col right -->
			<div class="col-md-4 col-lg-4 col-sm-4 hidden-xs">
				<ul class="list-group" id="revenue_mtd">
					<li class="list-group-item active">
						<span class="badge" id="daily_total_revenue_top">$<?php echo number_format($today_revenue, 2, null, ',') ?></span>
						Revenue
					</li>
				</ul>
				<ul class="list-group">
					<li class="list-group-item active">Stats</li>
					<li class="list-group-item">
						<span class="badge" id="mtd_total_revenue_top">$<?php echo number_format($monthly_revenue, 2, null, ',') ?></span>
						MTD Revenue
					</li>
					<li class="list-group-item">
						<span class="badge" id="yesterday_total_revenue_top">$<?php echo number_format($yesterday_revenue, 2, null, ',') ?></span>
						Yesterday Revenue
					</li>
				</ul>
			
				<div class="panel panel-default">
					<div class="panel-heading"><h5>Common Links</h5></div>
					<div class="panel">
						<div class="list-group">
							<a href="/report/revenue-report" class="list-group-item">Revenue Report</a>
							<a href="/export/split-queue-search" class="list-group-item">Queued Leads</a>
							<a href="/offer/offer-search" class="list-group-item">Offers</a>
							<a href="/lead/lead-search" class="list-group-item">All Leads</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	
	

		
<script>
//<!--
if (!$('#main_graph_div').is(':hidden')) {
	google.setOnLoadCallback(initialize);
}

$(window).on('debouncedresize', function() {
	if (!$('#main_graph_div').is(':hidden')) {
		initialize();
	}
});

function initialize() {
	var query1 = new google.visualization.Query('/chart/graph-click-by-hour?date_range=<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>&tz=<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>');
	query1.send(drawClickByHourChart);

	var query2 = new google.visualization.Query('/chart/graph-conversion-by-hour?date_range=<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>&tz=<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>');
	query2.send(drawConversionByHourChart);

	var query3 = new google.visualization.Query('/chart/graph-traffic-source-by-hour?date_range=<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>&tz=<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>');
	query3.send(drawTrafficSourceByHourChart);
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
			chartArea:{ left:'8%', top: '8%', width: '70%', height:'80%' }
		},
		containerId: 'click_by_hour_chart_div'
	});
	var chart_range_control = new google.visualization.ControlWrapper({ containerId: 'click_by_hour_filter_div', controlType: 'ChartRangeFilter', options: { filterColumnLabel: 'Hour', ui: { chartType: 'LineChart', chartOptions: { chartArea: {left:'8%',width: '90%'}, hAxis: { gridlines: {color: '#eaeaea', count: 30}, minorGridlines: {color: '#f4f4f4', count: 1}, baselineColor: 'none', textStyle: { color: '#737373', fontSize: 11 }}}, minRangeSize: 86400000 /* 1 day */ }}, state: { range: { start: new Date(<?php echo date('Y', strtotime('today')) ?>, <?php echo date('m', strtotime('today'))-1 ?>, <?php echo date('d', strtotime('today')) ?>), end: new Date(<?php echo date('Y', strtotime('tomorrow')) ?>, <?php echo date('m', strtotime('tomorrow'))-1 ?>, <?php echo date('d', strtotime('tomorrow')) ?>) }}});
	google.visualization.events.addListener(dashboard, 'ready', function() {
		$('#click_wait_div').fadeOut();
	});

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
	google.visualization.events.addListener(dashboard, 'ready', function() {
		$('#conversion_wait_div').fadeOut();
	});
	dashboard.bind(chart_range_control, chart);
	dashboard.draw(data); 
}

function drawTrafficSourceByHourChart(response) {
	var data = response.getDataTable();
	var dashboard = new google.visualization.Dashboard(document.getElementById('traffic_source_by_hour_div'));
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
			chartArea:{ left:'8%', top: '8%', width: '70%', height:'80%' }
		},
		containerId: 'traffic_source_by_hour_chart_div'
	});
	var chart_range_control = new google.visualization.ControlWrapper({ containerId: 'traffic_source_by_hour_filter_div', controlType: 'ChartRangeFilter', options: { filterColumnLabel: 'Hour', ui: { chartType: 'LineChart', chartOptions: { chartArea: {left:'8%',width: '90%'}, hAxis: { gridlines: {color: '#eaeaea', count: 30}, minorGridlines: {color: '#f4f4f4', count: 1}, baselineColor: 'none', textStyle: { color: '#737373', fontSize: 11 }}}, minRangeSize: 86400000 /* 1 day */ }}, state: { range: { start: new Date(<?php echo date('Y', strtotime('today')) ?>, <?php echo date('m', strtotime('today'))-1 ?>, <?php echo date('d', strtotime('today')) ?>), end: new Date(<?php echo date('Y', strtotime('tomorrow')) ?>, <?php echo date('m', strtotime('tomorrow'))-1 ?>, <?php echo date('d', strtotime('tomorrow')) ?>) }}});
	google.visualization.events.addListener(dashboard, 'ready', function() {
		$('#click_ts_wait_div').fadeOut();
	});
	dashboard.bind(chart_range_control, chart);
	dashboard.draw(data); 
}
//-->
</script>