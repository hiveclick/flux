<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<br /><br />
<div class="full col-sm-11">
    <!-- content -->
    <div class="row">
        <!-- main col right -->
        <div class="col-sm-9">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="/report/revenue-report" class="pull-right">View all</a> <h4>Click Traffic</h4></div>
                <div class="panel-body">
                    <div id="click_by_hour_chart_div" style="width:100%;height:250px"><i>Loading report data...</i></div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><a href="#" class="pull-right">View all</a> <h4>Conversion Traffic</h4></div>
                <div class="panel-body">
                    <div id="conversion_by_hour_chart_div" style="width:100%;height:250px"><i>Loading report data...</i></div>
                </div>
            </div>
        </div>


        <!-- main col right -->
        <div class="col-sm-3">
            <div class="panel panel-default well">
                <img class="col-sm-12" src="/api?func=/admin/user-profile-image?_id=<?php echo $this->getUserDetails()->getId() ?>" border="0" />
                <br />
                <a class="lead" href="/admin/profile"><?php echo $this->getUserDetails()->getName() ?></a>
                <br />
                (<small><a href="/logout">logout</a></small>)
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><h4>Common Links</h4></div>
                <div class="panel">
                    <div class="list-group">
                        <a href="/report/spy-report" class="list-group-item">Spy Report</a>
                        <a href="/admin/datafield-search" class="list-group-item">Data Fields</a>
                        <a href="/offer/offer-search" class="list-group-item">Offers</a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><h4>What Is Flux?</h4></div>
                <div class="panel-body">
                    Flux is a feature rich lead tracking and offer path platform.  It supports many dynamic data fields for collecting all data on a path.  It can incorporate with internal or external offers and supports multiple AB testing with the use of flows.
                </div>
            </div>
        </div>
    </div><!--/row-->

    <div class="row" id="footer">
        <div class="col-sm-6">

        </div>
        <div class="col-sm-6">
            <p>
            <a href="#" class="pull-right">&copy;Copyright 2014</a>
            </p>
        </div>
    </div>
    <hr>
</div>
<script>
//<!--
google.load("visualization", "1", {packages:["corechart"]});

$(window).on('debouncedresize', function() {
	drawClickByHourChart();
	drawConversionByHourChart();
});

$(document).ready(function() {
	drawClickByHourChart();
	drawConversionByHourChart();
});

function drawClickByHourChart() {
	$.rad.get("/api", { "func": "/report/graph-click-by-hour", "date_range": "<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_24_HOURS ?>", "tz": "<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>" }, function(data) {
	    if (data.record.series) {
    		var datatable = new google.visualization.DataTable({ cols: data.record.cols, rows: data.record.rows });
    		var view = new google.visualization.DataView(datatable);
    		var chart = new google.visualization.ColumnChart(document.getElementById('click_by_hour_chart_div'));
    		var series = data.record.series;
    		
    		var columns = [];
    	    for (var i = 0; i < datatable.getNumberOfColumns(); i++) {
    	        columns.push(i);
    	    }
        
    	    var options = {
    			title: 'Clicks By Hour',
    			isStacked: true,
    			animation:{
    		        duration: 250,
    		        easing: 'out'
    		    },
    		    series: data.record.series,
    		    hAxis: {
    		    	maxAlternation: 1,
    		    	maxTextLines: 1,
    		    	showTextEvery: 1,
    		    	minTextSpacing: 2,
    		    	gridlines: {color: '#eaeaea', count: 2},
    		    	minorGridlines: {color: '#f4f4f4', count: 1},
    		    	textStyle: { 
    					color: '#737373',
    					fontSize: 11
    				}
    		    },
    		    legend: {
    			    textStyle: { 
    					color: '#737373',
    					fontSize: 11
    				}
    		    },
    		    vAxis: {
    		    	gridlines: {color: '#eaeaea', count: 4},
    		    	minorGridlines: {color: '#f4f4f4', count: 1},
    		    	textStyle: { 
    					color: '#737373',
    					fontSize: 11
    				}
    		    },
    		    chartArea:{
    		    	left:'8%',
    		    	top: '8%',
    			    width:"70%",
    			    height:"80%"
    			},
    		    bar: {
    				groupWidth: 17
    		    }
    		};
    		chart.draw(view, options);
    		google.visualization.events.addListener(chart, 'select', function () {
    	        var sel = chart.getSelection();
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
    	                view.setColumns(columns);
    	                chart.draw(view, options);
    	            }
    	        }
    	    });
    	} else {
    		$('#click_by_hour_chart_div').html('<div class="text-center"><img src="/images/no_graph.png" border="0" class="img-responsive" /></div>');
        }
	});
}

function drawConversionByHourChart() {
	$.rad.get("/api", { "func": "/report/graph-conversion-by-hour", "date_range": "<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_24_HOURS ?>", "tz": "<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>" }, function(data) {
	    if (data.record.series) {
    		var datatable = new google.visualization.DataTable({ cols: data.record.cols, rows: data.record.rows });
    		var view = new google.visualization.DataView(datatable);
    		var chart = new google.visualization.ColumnChart(document.getElementById('conversion_by_hour_chart_div'));
    		var series = data.record.series;
    		
    		var columns = [];
    	    for (var i = 0; i < datatable.getNumberOfColumns(); i++) {
    	        columns.push(i);
    	    }
        
    	    var options = {
    			title: 'Conversions By Hour',
    			isStacked: true,
    			animation:{
    		        duration: 250,
    		        easing: 'out'
    		    },
    		    series: data.record.series,
    		    hAxis: {
    		    	maxAlternation: 1,
    		    	maxTextLines: 1,
    		    	showTextEvery: 1,
    		    	minTextSpacing: 2,
    		    	gridlines: {color: '#eaeaea', count: 2},
    		    	minorGridlines: {color: '#f4f4f4', count: 1},
    		    	textStyle: { 
    					color: '#737373',
    					fontSize: 11
    				}
    		    },
    		    legend: {
    			    textStyle: { 
    					color: '#737373',
    					fontSize: 11
    				}
    		    },
    		    vAxis: {
    		    	gridlines: {color: '#eaeaea', count: 4},
    		    	minorGridlines: {color: '#f4f4f4', count: 1},
    		    	textStyle: { 
    					color: '#737373',
    					fontSize: 11
    				}
    		    },
    		    chartArea:{
    		    	left:'8%',
    		    	top: '8%',
    			    width:"70%",
    			    height:"80%"
    			},
    		    bar: {
    				groupWidth: 17
    		    }
    		};
    		chart.draw(view, options);
    		google.visualization.events.addListener(chart, 'select', function () {
    	        var sel = chart.getSelection();
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
    	                view.setColumns(columns);
    	                chart.draw(view, options);
    	            }
    	        }
    	    });
	    } else {
			$('#conversion_by_hour_chart_div').html('<div class="text-center"><img src="/images/no_graph.png" border="0" class="img-responsive" /></div>');
	    }
	});
}
//-->
</script>