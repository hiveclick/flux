<?php 
    /* @var $revenue_report \Flux\ReportClient */
    $today_revenue = $this->getContext()->getRequest()->getAttribute('today_revenue', 0);
    $yesterday_revenue = $this->getContext()->getRequest()->getAttribute('yesterday_revenue', 0);
    $monthly_revenue = $this->getContext()->getRequest()->getAttribute('monthly_revenue', 0);
    $graph_click_by_hour = $this->getContext()->getRequest()->getAttribute('graph_click_by_hour', array());
    $graph_conversion_by_hour = $this->getContext()->getRequest()->getAttribute('graph_conversion_by_hour', array());
?>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart', 'controls']}]}"></script>
<div class="hidden-sm hidden-xs"><br /><br /></div>

<div class="hidden-md hidden-lg hidden-sm visible-xs text-center">
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
    		
<!-- main col right -->
<div class="col-md-9 col-lg-9 col-sm-9 hidden-xs" id="main_graph_div">

	<div class="panel panel-default">
		<div class="panel-heading"><a href="/lead/lead-search" class="pull-right">View all</a> <h4>Click Traffic</h4></div>
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
		<div class="panel-heading"><a href="/export/split-queue-search" class="pull-right">View all</a> <h4>Conversion Traffic</h4></div>
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
<div class="col-md-3 col-lg-3 col-sm-3 hidden-xs">
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
    	
<script>
//<!--
if (!$('#main_graph_div').is(':hidden')) {
    google.setOnLoadCallback(drawClickByHourChart);
    google.setOnLoadCallback(drawConversionByHourChart);
}
$(window).on('debouncedresize', function() {
	if (!$('#main_graph_div').is(':hidden')) {
    	drawClickByHourChart();
    	drawConversionByHourChart();
	}
});


function drawClickByHourChart() {
	// Generate the graph for the client list hour summary
	$.rad.get('/api', { func: '/report/graph-click-by-hour', date_range: '<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>', "tz": "<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>" }, function(data) {
		if (data.record && data.record.cols && data.record.cols.length > 1) {
		    
			var dashboard = new google.visualization.Dashboard(document.getElementById('click_by_hour_div'));
			var datatable = new google.visualization.DataTable({ cols: data.record.cols, rows: data.record.rows });	
			var dataview = new google.visualization.DataView(datatable);	
			var series = data.record.series;
			
			var columns = [];
			for (var i = 0; i < datatable.getNumberOfColumns(); i++) {
				columns.push(i);
			}
			var $options = {
				animation:{ duration: 250, easing: 'out' },
				hAxis: {
					gridlines: {color: '#eaeaea', count: -1, units: { days: {format: ["MMM dd"]}, hours: {format: ["h a", "ha"]}}},
					minorGridlines: {color: '#f4f4f4', count: -1, units: { days: {format: ["MMM dd"]}, hours: {format: ["h a", "ha"]}}},
					textStyle: { color: '#737373', fontSize: 11 },
				},
				legend: { textStyle: { color: '#737373', fontSize: 11 }},
				vAxis: { 
					gridlines: {color: '#eaeaea', count: 4},
					minorGridlines: {color: '#f4f4f4', count: 1},
					textStyle: { color: '#737373', fontSize: 11 }
				},
				series: data.record.series,
				chartArea:{ left:'8%', top: '8%', width: '70%', height:'80%' }
			};

			// Create a pie chart, passing some options
	        var chart = new google.visualization.ChartWrapper({
	          chartType: 'LineChart',
	          containerId: 'click_by_hour_chart_div',
	          options: $options
	        });
	        
	        var chart_range_control = new google.visualization.ControlWrapper({
	        	controlType: 'ChartRangeFilter',
	            containerId: 'click_by_hour_filter_div',
	            options: {
	              	filterColumnLabel: 'Hour',
		            ui: { chartType: 'LineChart', chartOptions: { chartArea: {left:'8%',width: '70%'}, hAxis: { gridlines: {color: '#eaeaea', count: 30}, minorGridlines: {color: '#f4f4f4', count: 1}, baselineColor: 'none', textStyle: { color: '#737373', fontSize: 11 }}}, minRangeSize: 86400000 /* 1 day */ }
	            },
	            state: { range: { start: new Date(<?php echo date('Y', strtotime('today')) ?>, <?php echo date('m', strtotime('today'))-1 ?>, <?php echo date('d', strtotime('today')) ?>), end: new Date(<?php echo date('Y', strtotime('tomorrow')) ?>, <?php echo date('m', strtotime('tomorrow'))-1 ?>, <?php echo date('d', strtotime('tomorrow')) ?>) }}
			});

	        dashboard.bind(chart_range_control, chart);
	        dashboard.draw(dataview);

	        google.visualization.events.addListener(chart, 'select', function () {
				var sel = dashboard.getSelection();
				// if selection length is 0, we deselected an element
				if (sel.length > 0) {
					// if row is undefined, we clicked on the legend
					if (typeof sel[0].row === 'undefined' || sel[0].row === null) {
						var col = sel[0].column;
						if (columns[col] == col) {
							// hide the data series
							columns[col] = {
								label: datatable.getColumnLabel(col),
								type: datatable.getColumnType(col),
								calc: function () {
									return null;
								}
							};
		
							// grey out the legend entry
							series[col - 1].color = '#eaeaea';
						}
						else {
							// show the data series
							columns[col] = col;
							series[col - 1].color = series[col - 1].orig_color;
						}
						dataview.setColumns(columns);
						dashboard.draw(dataview);
					}
				}
			});
		} else {
			$('#click_by_hour_chart_div').html('<div class="alert alert-warning"><h3 class="text-warning text-center"><span class="glyphicon glyphicon-retweet"></span> We\'re sorry, there is no incoming data to display on this graph yet</h3></div>');
		}
	});
}

function drawConversionByHourChart() {
	// Generate the graph for the client list hour summary
	$.rad.get('/api', { func: '/report/graph-conversion-by-hour', date_range: '<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>', "tz": "<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>" }, function(data) {
		if (data.record && data.record.cols && data.record.cols.length > 1) {

            var dashboard = new google.visualization.Dashboard(document.getElementById('conversion_by_hour_div'));
			var datatable = new google.visualization.DataTable({ cols: data.record.cols, rows: data.record.rows });	
			var dataview = new google.visualization.DataView(datatable);	
			var series = data.record.series;
			
			var columns = [];
			for (var i = 0; i < datatable.getNumberOfColumns(); i++) {
				columns.push(i);
			}
			var $options = {
				animation:{ duration: 250, easing: 'out' },
				hAxis: {
					gridlines: {color: '#eaeaea', count: -1, units: { days: {format: ["MMM dd"]}, hours: {format: ["h a", "ha"]}}},
					minorGridlines: {color: '#f4f4f4', count: -1, units: { days: {format: ["MMM dd"]}, hours: {format: ["h a", "ha"]}}},
					textStyle: { color: '#737373', fontSize: 11 },
				},
				legend: { textStyle: { color: '#737373', fontSize: 11 }},
				vAxis: { 
					gridlines: {color: '#eaeaea', count: 4},
					minorGridlines: {color: '#f4f4f4', count: 1},
					textStyle: { color: '#737373', fontSize: 11 }
				},
				isStacked: true,
				bar: { groupWidth: 17 },
				series: data.record.series,
				chartArea:{ left:'8%', top: '8%', width: '70%', height:'80%' }
			};
		
			// Create a pie chart, passing some options
	        var chart = new google.visualization.ChartWrapper({
	          chartType: 'ColumnChart',
	          containerId: 'conversion_by_hour_chart_div',
	          options: $options
	        });

	        var chart_range_control = new google.visualization.ControlWrapper({
	        	controlType: 'ChartRangeFilter',
	            containerId: 'conversion_by_hour_filter_div',
	            options: {
	              	filterColumnLabel: 'Hour',
		            ui: { chartType: 'ComboChart', chartOptions: { chartArea: {left:'8%',width: '70%'}, hAxis: { gridlines: {color: '#eaeaea', count: 15}, minorGridlines: {color: '#f4f4f4', count: 1}, baselineColor: 'none', textStyle: { color: '#737373', fontSize: 11 }}}, minRangeSize: 86400000 /* 1 day */ }
	            },
	            state: { range: { start: new Date(<?php echo date('Y', strtotime('today')) ?>, <?php echo date('m', strtotime('today'))-1 ?>, <?php echo date('d', strtotime('today')) ?>), end: new Date(<?php echo date('Y', strtotime('tomorrow')) ?>, <?php echo date('m', strtotime('tomorrow'))-1 ?>, <?php echo date('d', strtotime('tomorrow')) ?>) }}
			});

	        dashboard.bind(chart_range_control, chart);
	        dashboard.draw(dataview);

	        google.visualization.events.addListener(chart, 'select', function () {
				var sel = dashboard.getSelection();
				// if selection length is 0, we deselected an element
				if (sel.length > 0) {
					// if row is undefined, we clicked on the legend
					if (typeof sel[0].row === 'undefined' || sel[0].row === null) {
						var col = sel[0].column;
						if (columns[col] == col) {
							// hide the data series
							columns[col] = {
								label: datatable.getColumnLabel(col),
								type: datatable.getColumnType(col),
								calc: function () {
									return null;
								}
							};
		
							// grey out the legend entry
							series[col - 1].color = '#eaeaea';
						}
						else {
							// show the data series
							columns[col] = col;
							series[col - 1].color = series[col - 1].orig_color;
						}
						dataview.setColumns(columns);
						dashboard.draw(dataview);
					}
				}
			});
		} else {
			$('#conversion_by_hour_chart_div').html('<div class="alert alert-warning"><h3 class="text-muted text-center"><span class="glyphicon glyphicon-retweet"></span> We\'re sorry, there is no incoming data to display on this graph yet</h3></div>');
		}
	});	
}
//-->
</script>