<?php
    /* @var $revenue_report \Flux\ReportClient */
	$revenue_report = $this->getContext()->getRequest()->getAttribute('revenue_report', array());
	$report_data = $this->getContext()->getRequest()->getAttribute('report_data', array());
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart', 'controls']}]}"></script>

<div class="page-header">
	<div class="pull-right">
        <form id="revenue_report_form" name="revenue_report_form" method="GET" action="/report/revenue-report" autocomplete="off">
    		<select id="report_date" name="report_date" class="form-control" style="width:200px;">
                <option value="<?php echo date('m/01/Y') ?>" <?php echo $revenue_report->getReportDate()->sec == strtotime(date('m/01/Y')) ? 'selected' : '' ?>><?php echo date('F Y') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                <?php for ($i=1;$i<14;$i++) { ?>
                    <option value="<?php echo date('m/01/Y', strtotime(date('m/01/Y') . ' - ' . $i . ' months')) ?>" <?php echo $revenue_report->getReportDate()->sec == strtotime(date('m/01/Y', strtotime(date('m/01/Y') . ' - ' . $i . ' months'))) ? 'selected' : '' ?>><?php echo date('F Y', strtotime(date('m/01/Y') . ' - ' . $i . ' months')) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                <?php } ?>
    		</select>
		</form>
	</div>
   <h2>Revenue Report</h2>
</div>
<div class="help-block">Easily track your revenue by client month over month</div>
<div class="col-xs-12 col-sm-12 visible-xs visible-sm">
    <ul class="list-group" id="revenue_mtd">
        <li class="list-group-item active">
            <span class="badge" id="mtd_total_revenue_top">$0.00</span>
            Revenue
        </li>
    </ul>
    <ul class="list-group">
        <li class="list-group-item active">Stats</li>
        <li class="list-group-item">
            <span class="badge" id="highest_day_top"></span>
            Highest Day
        </li>
        <li class="list-group-item">
            <span class="badge" id="average_day_top"></span>
            Average Day
        </li>
        <li class="list-group-item">
            <span class="badge" id="lowest_day_top"></span>
            Lowest Day
        </li>
        <li class="list-group-item">
            <span class="badge" id="trending_rev_top"></span>
            Trending Revenue
        </li>
    </ul>
</div>
<div class="col-md-9 col-sm-12 col-xs-12">
    <table class="table table-bordered table-condensed table-responsive">
    	<thead>
    		<tr>
    			<th>Sunday</th>
    			<th>Monday</th>
    			<th>Tuesday</th>
    			<th>Wednesday</th>
    			<th>Thursday</th>
    			<th>Friday</th>
    			<th>Saturday</th>
    		</tr>
    	</thead>
    	<tbody>
    	   <?php 
    			$today = date('mdY');
    			$month = date('m', strtotime(date('m/01/Y', $revenue_report->getReportDate()->sec)));
    			$year = date('Y', strtotime(date('m/01/Y', $revenue_report->getReportDate()->sec)));
    			$fdom = date('w', strtotime(date('m/01/Y', $revenue_report->getReportDate()->sec)));
    			$ct=0;
    			$is_current_month = true;
    			while ($is_current_month) {
    				print("<tr>");
    				for($week=1;$week<8;$week++) {
    					$ct++;
    					$value=mktime(0,0,0,$month,$ct-$fdom,$year);
    					if (date("m",$value) == $month) {
    						$is_current_month = true;
    						// Output a table cell for a day in this month
    		?>
    			<td class="small" style="height:150px;">
					<div id="day_<?php echo date('z', $value) ?>" style="padding:0px 0px 5px 0px;border-Bottom:1px dotted #C8C8C8;">
					   <span class="badge"><?php echo date('j', $value) ?></span>
					   <b class="pull-right text-muted" id="daytotal_<?php echo date('z', $value) ?>"></b>
					</div>
    			</td>							
    		<?php 
    			} else { 
    				$is_current_month = false;
    				// Output a table cell for a day the either the previous or next month
    		?>
    			<td class="small" style="height:150px;">
    				<div id="day_<?php echo date('z', $value) ?>" style="padding:0px 0px 5px 0px;border-Bottom:1px dotted #C8C8C8;">
					   <span class="badge" style="background-Color:#C8C8C8;"><?php echo date('j', $value) ?></span>
					   <b class="pull-right text-muted" id="daytotal_<?php echo date('z', $value) ?>"></b>
					</div>
    			</td>							
    		<?php 
    				}
    			}
    			print("</tr>");
    		}
    		?>
    	</tbody>
    </table>
</div>
<div class="col-md-3 col-lg-3 hidden-xs hidden-sm">
    <ul class="list-group" id="revenue_mtd">
        <li class="list-group-item active">
            <span class="badge" id="mtd_total_revenue">$0.00</span>
            Revenue
        </li>
    </ul>
    <ul class="list-group">
        <li class="list-group-item active">Stats</li>
        <li class="list-group-item">
            <span class="badge" id="highest_day"></span>
            Highest Day
        </li>
        <li class="list-group-item">
            <span class="badge" id="average_day"></span>
            Average Day
        </li>
        <li class="list-group-item">
            <span class="badge" id="lowest_day"></span>
            Lowest Day
        </li>
        <li class="list-group-item">
            <span class="badge" id="trending_rev"></span>
            Trending Revenue
        </li>
    </ul>
    
    <ul class="list-group">
        <li class="list-group-item active">
            Graphs
        </li>
        <li class="list-group-item">
             <div id="network_pie_div">
        		<!--Divs that will hold each control and chart-->
        		<div id="network_pie_chart_div" style="width:100%;height:200px;">
        			<div class="text-muted text-center">
        				<span class="fa fa-spinner fa-spin"></span>
        				Loading report data...
        			</div>
        		</div>
            </div>
        </li>
        <li class="list-group-item">
             <div id="daily_col_div">
        		<!--Divs that will hold each control and chart-->
        		<div id="daily_col_chart_div" style="width:100%;height:250px;">
        			<div class="text-muted text-center">
        				<span class="fa fa-spinner fa-spin"></span>
        				Loading report data...
        			</div>
        		</div>
            </div>
        </li>
    </ul>
</div>

<script>
//<!--
$(document).ready(function() {
	$('#report_date').selectize({
		dropdownWidthOffset: 200,
		onChange: function(value) {
			if (!value.length) return;
			$('#revenue_report_form').trigger('submit');
		}
	});

	loadRevenue();
});

function initialize() {
	
}

function loadRevenue() {
	$.rad.get('/api', {func: '/report/report-client', start_date: '<?php echo date('m/1/Y', $revenue_report->getReportDate()->sec) ?>', end_date: '<?php echo date('m/t/Y', $revenue_report->getReportDate()->sec) ?>', ignore_pagination: '1' }, function(data) {
		var revenue_array = new Array();
		var revenue_total_array = new Array();
		if (data.entries) {
			$.each(data.entries, function(i, item) {
				if (item.revenue > 0) {
    				var $daycell = $( "#day_" + (moment.unix(item.report_date.sec).format('DDD') - 1) );
    				var div = $('<div class="text-right small" />').css('color', item.client.client_color).html('$' + $.formatNumber(item.revenue, {format:"#,##0.00", locale:"us"}));
    				$daycell.after(div);
				}

				// Create an array of revenue by network				
				rev_obj_found = false;
				$.each(revenue_array, function(i, rev_obj) {
					if (rev_obj.id == item.client.client_id) {
						rev_obj.revenue += parseFloat(item.revenue);
						rev_obj_found = true;
					}
				});
				if (!rev_obj_found) {
					rev_obj = {'id' : item.client.client_id, 'name': item.client.client_name, 'color': item.client.client_color, 'revenue': parseFloat(item.revenue) };
					revenue_array.push(rev_obj);
				}

				// Create an array of revenue by day
				rev_obj_found = false;
				$.each(revenue_total_array, function(i, rev_obj) {
					if (rev_obj.day_of_year == (moment.unix(item.report_date.sec).format('DDD') - 1)) {
						rev_obj.revenue += parseFloat(item.revenue);
						rev_obj_found = true;
					}
				});
				if (!rev_obj_found) {
					rev_obj = {'day_of_year' : (moment.unix(item.report_date.sec).format('DDD') - 1), 'day_of_month': (moment.unix(item.report_date.sec).format('D')), 'revenue': parseFloat(item.revenue) };
					revenue_total_array.push(rev_obj);
				}

			});	
		}

		// Add the totals to each day that needs them
		$.each(revenue_total_array, function(i, item) {
			if (item.revenue > 0) {
			    $( "#daytotal_" + item.day_of_year).html('$' + $.formatNumber(item.revenue, {format:"#,##0.00", locale:"us"}));
			} else {
				$( "#daytotal_" + item.day_of_year).css('color', '#C8C8C8').html('$' + $.formatNumber(item.revenue, {format:"#,##0.00", locale:"us"}));
			}
		});

		// Add the revenue to the right side
		var total_revenue = 0.00;
		$.each(revenue_array, function(i, item) {
			var li = $('<li />').addClass('list-group-item').html(item.name).appendTo($('#revenue_mtd,#revenue_mtd_top'));
			$('<span class="badge" />').css('background-color', item.color).html('$' + $.formatNumber(item.revenue, {format:"#,##0.00", locale:"us"})).prependTo(li);
			total_revenue += item.revenue;
		});
		$('#mtd_total_revenue,#mtd_total_revenue_top').html('$' + $.formatNumber(total_revenue, {format:"#,##0.00", locale:"us"}));

		// Draw the Network Pie Graph
	    var rev_data_pie = [['Label', 'Revenue']];
	    $.each(revenue_array, function(i, item) {
	    	rev_data_pie.push([item.name, {v:item.revenue, f:'$' + $.formatNumber(item.revenue, {format:"#,##0.00", locale:"us"})}]);
	    });
		
	    var pie_options = {
	      is3D: false,
	      theme: 'maximized'
	    };
	    var pie_data = google.visualization.arrayToDataTable(rev_data_pie);
	    var network_rev_chart = new google.visualization.PieChart(document.getElementById('network_pie_chart_div'));
	    network_rev_chart.draw(pie_data, pie_options);

	    // Draw the daily rev graph
	    var daily_col_array = [['Label', 'Revenue']];
	    revenue_total_array.sort(function(a,b) {
	        return a.day_of_year - b.day_of_year;
	    });
	    var max_day_of_month = 0;
	    $.each(revenue_total_array, function(i, item) {
	    	daily_col_array.push([item.day_of_month, {v:item.revenue, f:'$' + $.formatNumber(item.revenue, {format:"#,##0.00", locale:"us"})}]);
	    	max_day_of_month = item.day_of_month;
	    });
	    for (var i=max_day_of_month;i<<?php echo date("t", $revenue_report->getReportDate()->sec) ?>;i++) {
	    	daily_col_array.push([i, {v:0.00, f:'$0.00'}]);
	    }
		
	    var daily_col_options = {
	      is3D: false,
	      theme: 'maximized'
	    };
	    var daily_col_data = google.visualization.arrayToDataTable(daily_col_array);
	    var daily_col_chart = new google.visualization.ColumnChart(document.getElementById('daily_col_chart_div'));
	    daily_col_chart.draw(daily_col_data, daily_col_options);
		
		// Add the stats to the right
		var low_day_item = {'day_of_year' : 0, 'revenue': 0.00 };
		var high_day_item = {'day_of_year' : 0, 'revenue': 0.00 };
		var avg_day_item = {'days' : 0, 'revenue': 0.00, 'total': 0.00 };
		
		$.each(revenue_total_array, function(i, item) {
			avg_day_item.days++;
			avg_day_item.total += item.revenue;
			
			if (item.revenue > high_day_item.revenue) {
				high_day_item = item;
			}
			if (item.revenue < low_day_item.revenue) {
				low_day_item = item;
			} else if (low_day_item.revenue == 0.00) {
				low_day_item = item;
			}
		});
	    if (avg_day_item.days > 0) {
	    	avg_day_item.revenue = (avg_day_item.total / avg_day_item.days);
	    }
		
		$('#highest_day,#highest_day_top').html('$' + $.formatNumber(high_day_item.revenue, {format:"#,##0.00", locale:"us"}));
		$('#lowest_day,#lowest_day_top').html('$' + $.formatNumber(low_day_item.revenue, {format:"#,##0.00", locale:"us"}));
		$('#average_day,#average_day_top').html('$' + $.formatNumber(avg_day_item.revenue, {format:"#,##0.00", locale:"us"}));
		$('#trending_rev,#trending_rev_top').html('$' + $.formatNumber(avg_day_item.revenue * <?php echo date("t", $revenue_report->getReportDate()->sec) ?>, {format:"#,##0.00", locale:"us"}));
	});
}
//-->
</script>