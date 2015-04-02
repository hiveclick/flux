<?php
    /* @var $revenue_report \Flux\ReportClient */
	$revenue_report = $this->getContext()->getRequest()->getAttribute('revenue_report', array());
	$report_data = $this->getContext()->getRequest()->getAttribute('report_data', array());
?>
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
<div class="col-md-9">
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
<div class="col-md-3">
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
            <span class="badge" id="lowest_day"></span>
            Lowest Day
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

function loadRevenue() {
	$.rad.get('/api', {func: '/report/report-client', start_date: '<?php echo date('m/1/Y', $revenue_report->getReportDate()->sec) ?>', end_date: '<?php echo date('m/t/Y', $revenue_report->getReportDate()->sec) ?>', ignore_pagination: '1' }, function(data) {
		console.log(data);
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
					rev_obj = {'day_of_year' : (moment.unix(item.report_date.sec).format('DDD') - 1), 'revenue': parseFloat(item.revenue) };
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
			var li = $('<li />').addClass('list-group-item').html(item.name).appendTo($('#revenue_mtd'));
			$('<span class="badge" />').css('background-color', item.color).html('$' + $.formatNumber(item.revenue, {format:"#,##0.00", locale:"us"})).prependTo(li);
			total_revenue += item.revenue;
		});
		$('#mtd_total_revenue').html('$' + $.formatNumber(total_revenue, {format:"#,##0.00", locale:"us"}));

		// Add the stats to the right
		var low_day_item = {'day_of_year' : 0, 'revenue': 0.00 };
		var high_day_item = {'day_of_year' : 0, 'revenue': 0.00 };
		
		$.each(revenue_total_array, function(i, item) {
			if (item.revenue > high_day_item.revenue) {
				high_day_item = item;
			}
			if (item.revenue < low_day_item.revenue) {
				low_day_item = item;
			} else if (low_day_item.revenue == 0.00) {
				low_day_item = item;
			}
		});
		$('#highest_day').html('$' + $.formatNumber(high_day_item.revenue, {format:"#,##0.00", locale:"us"}));
		$('#lowest_day').html('$' + $.formatNumber(low_day_item.revenue, {format:"#,##0.00", locale:"us"}));
	});
}
//-->
</script>