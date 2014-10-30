<?php
    /* @var $offer Flux\Offer */
    $offer = $this->getContext()->getRequest()->getAttribute("offer", array());
?>
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
<div id="header">
    <div class="pull-right visible-xs">
        <button class="navbar-toggle collapsed visible-xs" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <h2><a href="/offer/offer-search">Offers</a> <small> <?php echo $offer->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
    <ul class="nav nav-pills" id="offer_tabs">
        <li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Dashboard</a></li>
        <li><a id="tabs-a-edit" href="#tabs-edit" data-toggle="tab" data-url="/offer/offer-pane-edit?_id=<?php echo $offer->getId() ?>">Edit</a></li>
        <li><a id="tabs-a-events" href="#tabs-events" data-toggle="tab" data-url="/offer/offer-pane-event?_id=<?php echo $offer->getId() ?>">Events</a></li>
        <li><a id="tabs-a-pages" href="#tabs-pages" data-toggle="tab" data-url="/offer/offer-pane-pages?_id=<?php echo $offer->getId() ?>">Pages</a></li>
        <li>
            <a type="button" class="dropdown-toggle" data-toggle="dropdown">
                Assets <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a id="tabs-a-assets" href="#tabs-assets" data-toggle="tab" data-url="/offer/offer-pane-assets?_id=<?php echo $offer->getId() ?>">Assets</a></li>
                <li><a href="/offer/offer-asset-wizard?offer_id=<?php echo $offer->getId() ?>">Add New Asset</a></li>
            </ul>
        </li>
        <!--
        <li class="dropdown">
            <a type="button" class="dropdown-toggle" data-toggle="dropdown">
                Campaigns <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a id="tabs-a-campaigns" href="#tabs-campaigns" data-toggle="tab" data-url="/offer/offer-pane-campaign?_id=<?php echo $offer->getId() ?>">View Campaigns</a></li>
                <li><a href="/campaign/campaign-wizard?offer_id=<?php echo $offer->getId() ?>">Add New Campaign</a></li>
            </ul>
        </li>
        -->
        <li><a id="tabs-a-report" href="#tabs-report" data-toggle="tab" data-url="/offer/offer-pane-report?_id=<?php echo $offer->getId() ?>">Report</a></li>
        <li><a id="tabs-a-spy" href="#tabs-spy" data-toggle="tab" data-url="/offer/offer-pane-spy?_id=<?php echo $offer->getId() ?>">Spy</a></li>
        <li><a id="tabs-a-instructions" href="#tabs-instructions" data-toggle="tab" data-url="/offer/offer-pane-instruction?_id=<?php echo $offer->getId() ?>">Instructions</a></li>
    </ul>
</div>

<div id="tab-content-container" class="tab-content">
    <div id="tabs-main" class="tab-pane active">
        <div class="help-block">Get a bird's eye view of this offer and how it is performing below.</div>
        <br/>
        <!-- content -->
        <div class="row">
            <!-- main col right -->
            <div class="col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Click Traffic</h4></div>
                    <div class="panel-body">
                        <div id="click_by_hour_chart_div" style="width:100%;height:250px"><i>Loading report data...</i></div>
                    </div>
                </div>
    
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Conversion Traffic</h4></div>
                    <div class="panel-body">
                        <div id="conversion_by_hour_chart_div" style="width:100%;height:250px"><i>Loading report data...</i></div>
                    </div>
                </div>
            </div>
            
            <!-- main col right -->
            <div class="col-sm-4">
                <div class="panel panel-default text-center">
                    <?php foreach ($offer->getOfferPages() as $offer_page) { ?>    
                        <img id="offer_thumbnail_img" class="page_thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=300x300&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=<?php echo urlencode($offer_page->getPreviewUrl()) ?>" border="0" alt="Loading thumbnail..." data-url="<?php echo $offer_page->getPreviewUrl() ?>" />
                    <?php 
                            break;
                        } 
                    ?>
                    <p />
                    <div>
                        <a class="btn btn-info" href="<?php echo $offer->getPreviewUrl() ?>" target="_blank">Preview Landing Page</a>
                        <br /><small><?php echo $offer->getPreviewUrl() ?></small>
                    </div>
                </div>
                <p />
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <h4>Today's Stats</h4>
                    </div>
                    <div class="panel-body">
                    <h4><?php echo number_format($offer->getDailyClicks(), 0, null, ',') ?> Clicks</h4>
                    <h4><?php echo number_format($offer->getDailyConversions(), 0, null, ',') ?> Conversions</h4>
                    </div>
                </div>
                <p />
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Quick Links</h4></div>
                    <div class="panel">
                        <div class="list-group">
                            <a href="/offer/offer?_id=<?php echo $offer->getId() ?>#tabs-spy" class="list-group-item">Spy Report</a>
                            <a href="/offer/offer?_id=<?php echo $offer->getId() ?>#tabs-pages" class="list-group-item">Pages</a>
                            <a href="/offer/offer?_id=<?php echo $offer->getId() ?>#tabs-events" class="list-group-item">Events</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div id="tabs-edit" class="tab-pane"></div>
    <div id="tabs-events" class="tab-pane"></div>
    <div id="tabs-report" class="tab-pane"></div>
    <div id="tabs-pages" class="tab-pane"></div>
    <div id="tabs-assets" class="tab-pane"></div>
    <div id="tabs-spy" class="tab-pane"></div>
    <div id="tabs-instructions" class="tab-pane"></div>
</div>
<script>
//<!--
google.load("visualization", "1", {packages:["corechart"]});

$(window).on('debouncedresize', function() {
	drawClickByHourChart();
	drawConversionByHourChart();
});

$(document).ready(function() {

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        e.preventDefault();
        var hash = this.hash;
        if ($(this).attr("data-url")) {
            // only load the page the first time
            if ($(hash).html() == '') {
                // ajax load from data-url
                $(hash).load($(this).attr("data-url"));
            }
        }
    }).on('show.bs.tab', function (e) {
    	try {
    	    sessionStorage.setItem(localTabStorageName, $(e.target).attr('href'));
	    } catch (err) { }
    });

    // Store the last clicked tab so it can be loaded on page refreshes
    var localTabStorageName = <?php echo json_encode('offer_tab_' . $offer->getId()); ?>;
    var lastTab = sessionStorage.getItem(localTabStorageName);
    if (location.hash) {
        var hash = location.hash, hashPieces = hash.split('?'), activeTab = $('[href=' + hashPieces[0] + ']');
    }
    if (activeTab) {
        activeTab.tab('show');
    } else {
        if (lastTab) {
            $('a[href='+lastTab+']').tab('show');
        } else {
            $('ul.nav-pills a:first').tab('show');
        }
    }

   	drawClickByHourChart();
   	drawConversionByHourChart();
});

function drawClickByHourChart() {
	$.rad.get("/api", { "func": "/report/graph-click-by-hour", "date_range": "<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_24_HOURS ?>", "tz": "<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>", "offer_id_array": "<?php echo $offer->getId() ?>" }, function(data) {
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
	$.rad.get("/api", { "func": "/report/graph-conversion-by-hour", "date_range": "<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_24_HOURS ?>", "tz": "<?php echo $this->getContext()->getUser()->getUserDetails()->getTimezone() ?>", "offer_id_array": "<?php echo $offer->getId() ?>" }, function(data) {
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