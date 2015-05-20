<?php 
/* @var $server_monitor \Flux\ServerMonitor */
$server_monitor = $this->getContext()->getRequest()->getAttribute('server_monitor', array());
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart', 'controls']}]}"></script>

<div class="page-header">
	<h1>System Settings</h1>
</div>
<div class="container-fluid">
	<div role="tabpanel">
		<!-- Nav tabs -->
		<ul id="tabs" class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#settings" aria-controls="home" role="tab" data-toggle="tab">Global System Settings</a></li>
			<li role="presentation"><a href="#updates" role="tab" data-toggle="tab">Updates</a></li>
			<li role="presentation"><a href="#opcache" role="tab" data-toggle="tab">Opcache</a></li>
			<li role="presentation"><a href="#monitoring" role="tab" data-toggle="tab">Monitoring</a></li>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active" id="settings">
                <form id="system_values_form"  method="POST" action="/api">
                	<input type="hidden" name="func" value="/admin/preferences">
                	<h3 class="sub-header">Email Notifications</h3>
                	<div class="form-group">
                		<div class="help-block">Emails sent when there are <b class="text-danger">issues or bugs</b> found in the site</div>
                		<input type="hidden" name="preferences_array[support_email]" value="" />
                		<input type="text" id="support_email" class="form-control" placeholder="enter email addresses that should receive support emails" name="preferences_array[support_email]" value="<?php echo \Flux\Preferences::getPreference('SUPPORT_EMAIL'); ?>" />
                	</div>
                	<br />
                	<h3 class="sub-header">Interface Options</h3>
                	<div class="form-group">
                		<div class="help-block">Select how many items to show on search pages by default</div>
                		<select id="items_per_page" name="preferences_array[items_per_page]" placeholder="enter default number of items to show on search pages">
                			<option value="10" <?php echo \Flux\Preferences::getPreference('ITEMS_PER_PAGE') == '10' ? 'selected' : '' ?>>Show up to 10 records per page</option>
                			<option value="25" <?php echo \Flux\Preferences::getPreference('ITEMS_PER_PAGE') == '25' ? 'selected' : '' ?>>Show up to 25 records per page</option>
                			<option value="50" <?php echo \Flux\Preferences::getPreference('ITEMS_PER_PAGE') == '50' ? 'selected' : '' ?>>Show up to 50 records per page</option>
                			<option value="100" <?php echo \Flux\Preferences::getPreference('ITEMS_PER_PAGE') == '100' ? 'selected' : '' ?>>Show up to 100 records per page</option>
                			<option value="200" <?php echo \Flux\Preferences::getPreference('ITEMS_PER_PAGE') == '200' ? 'selected' : '' ?>>Show up to 200 records per page</option>
                		</select>
                	</div>
                	<div class="form-group">
                		<div class="help-block">Display your company name on the navigation bar</div>
                		<input type="hidden" name="preferences_array[brand_name]" value="" />
                		<input type="text" id="brand_name" class="form-control" placeholder="enter your company name or leave blank for the default" class="form-control" name="preferences_array[brand_name]" value="<?php echo \Flux\Preferences::getPreference('BRAND_NAME'); ?>" />
                	</div>
                	<hr />
                	<div class="text-center" colspan="2" >
                		<input type="submit" name="" value="Update" class="btn btn-info">
                	</div>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane fade in" id="updates">
				<h3 class="sub-header">Platform Information</h3>
				<div class="form-group">
				    <div class="help-block">You are currently on version <?php echo \Flux\Preferences::getPreference('version') ?>.  You can check for updates using the button below.</div>
				    <p />
				    <div id="update_btn_div">
				        <div class="btn btn-info" id="update_check_btn">Check for updates</div>
				    </div>
				    <!-- Show the checking for updates div -->
				    <div class="hidden" id="update_check_div">
				        <span class="fa fa-spinner fa-spin"></span>
				        Checking for updates...
				    </div>
				    <!-- Show the currently updated version -->
				    <div class="hidden alert alert-info" id="update_current_version_div">
				        <span class="glyphicon glyphicon-ok"></span>
				        You are already at the most recent version.  Come back and check for more updates from time to time.
				    </div>
				    <!-- Show any updates with an update button and progressbar -->
				    <div class="hidden" id="update_div">
				        <div class="media">
				            <div class="media-left">
                                <span class="fa-3x glyphicon glyphicon-download"></span>
                            </div>
				            <div class="media-body">
                                <h4 class="media-heading"></h4>
				                <div class="media-description"></div>
				                <div class="media-version small"></div>
				                <div class="btn btn-success" id="update_btn">Update</div>
				                <div class="hidden" id="update_progress_div">
				                    <div class="progress" style="margin-bottom:0px;">
                                        <div class="progress-bar" role="progressbar" style="width:0%;"></div>
                                    </div>
                                    <div class="text-muted small" id="update_progress_status"></div>
				                </div>
				            </div>
				        </div>
				    </div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade in" id="opcache">
				<iframe src="/opcache.php" width="100%" height="700" frameborder="0" seamless></iframe>
			</div>
			<div role="tabpanel" class="tab-pane fade in" id="monitoring">
			    <p />
                <?php foreach ($server_monitor->getWarnings() as $warning) { ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <strong>Warning!</strong> <?php echo $warning ?>
                </div>
                <?php } ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">HDD Status</div>
                            <div class="panel-body">
                                <div id="hdd_status_div">
                            		<!--Divs that will hold each control and chart-->
                            		<div id="hdd_status_chart_div" style="width:100%;height:250px">
                            			<div class="text-muted text-center">
                            				<span class="fa fa-spinner fa-spin"></span>
                            				Loading report data...
                            			</div>
                            		</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">RAM Status</div>
                            <div class="panel-body">
                                <div id="ram_status_div">
                            		<!--Divs that will hold each control and chart-->
                            		<div id="ram_status_chart_div" style="width:100%;height:250px">
                            			<div class="text-muted text-center">
                            				<span class="fa fa-spinner fa-spin"></span>
                            				Loading report data...
                            			</div>
                            		</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">Swap Status</div>
                            <div class="panel-body">
                                <div id="swap_status_div">
                            		<!--Divs that will hold each control and chart-->
                            		<div id="swap_status_chart_div" style="width:100%;height:250px">
                            			<div class="text-muted text-center">
                            				<span class="fa fa-spinner fa-spin"></span>
                            				Loading report data...
                            			</div>
                            		</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading"><span class="<?php echo (strpos($server_monitor->getRaidStatusCore28(), 'degraded') !== false) ? 'text-danger' : 'text-success' ?>">Core 28 RAID (Main Flux Server)</span></div>
                            <div class="panel-body">
                                <?php echo nl2br($server_monitor->getRaidStatusCore28()) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading"><span class="<?php echo (strpos($server_monitor->getRaidStatusCore9(), 'degraded') !== false) ? 'text-danger' : 'text-success' ?>">Core 09 RAID (Backup Flux Server)</span></div>
                            <div class="panel-body">
                                <?php echo nl2br($server_monitor->getRaidStatusCore9()) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading"><span class="<?php echo (strpos($server_monitor->getRaidStatusCore8(), 'degraded') !== false) ? 'text-danger' : 'text-success' ?>">Core 8 RAID (Main Hosting Server)</span></div>
                            <div class="panel-body">
                                <?php echo nl2br($server_monitor->getRaidStatusCore8()) ?>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
        </div>
    </div>
</div>
<script>
//<!--
$(document).ready(function() {
	$('#update_check_btn').click(function() {
        // Check for updates
    	checkForUpdates();
    });

    $('#update_btn').click(function() {
        $('#update_btn').addClass('hidden');
        $('#update_progress_div').removeClass('hidden');
    	$.rad.get('/api', { func: '/admin/update-start' }, function(data) {
    		checkProgress();
        });
    });
	
	$('#support_email').selectize({
	    delimiter: ',',
	    persist: false,
	    create: function(input) {
	        return {
	            value: input,
	            text: input
	        }
	    }
	});
	
    $('#items_per_page').selectize();

    $('#system_values_form').form(function(data) {
		$.rad.notify('Settings saved', 'The settings have been saved to the system successfully.');
	},{keep_form:1});
});

google.setOnLoadCallback(initialize);

$(window).on('debouncedresize', function() {
	initialize();
});

function initialize() {
	// Draw the HDD Visualization
	var hdd_data = google.visualization.arrayToDataTable([
	                                                  ['Label', 'Space'],
	                                                  ['Available (<?php echo $server_monitor->getAvailableHddSpace() ?> GB)',  {v:<?php echo $server_monitor->getAvailableHddSpace() ?>, f:'<?php echo $server_monitor->getAvailableHddSpace() ?> GB'}],
	                                                  ['Used (<?php echo $server_monitor->getUsedHddSpace() ?> GB)',  {v:<?php echo $server_monitor->getUsedHddSpace() ?>, f:'<?php echo $server_monitor->getUsedHddSpace() ?> GB'}]
	                                                ]);

    var hdd_options = {
      title: 'HDD Space (<?php echo number_format($server_monitor->getTotalHddSpace(), 0, null, ',') ?> GB)',
      is3D: true,
    };
    
    var hdd_chart = new google.visualization.PieChart(document.getElementById('hdd_status_chart_div'));
    
    hdd_chart.draw(hdd_data, hdd_options);

    // Draw the RAM Visualization
	var ram_data = google.visualization.arrayToDataTable([
	                                                  ['Label', 'Space'],
	                                                  ['Available (<?php echo number_format($server_monitor->getAvailableRam() / 1024 / 1024, 0, null , ',') ?> MB)', {v:<?php echo $server_monitor->getAvailableRam() ?>, f:'<?php echo number_format($server_monitor->getAvailableRam() / 1024 / 1024, 0, null , ',') ?> MB'}],
	                                                  ['Used (<?php echo number_format($server_monitor->getUsedRam() / 1024 / 1024, 0, null , ',') ?> MB)', {v:<?php echo $server_monitor->getUsedRam() ?>, f:'<?php echo number_format($server_monitor->getUsedRam() / 1024 / 1024, 0, null , ',') ?> MB'}]
	                                                ]);

    var ram_options = {
      title: 'RAM Allocation (<?php echo number_format($server_monitor->getTotalRam() / 1024 / 1024 / 1024, 0, null, ',') ?> GB)',
      is3D: true,
    };
    
    var ram_chart = new google.visualization.PieChart(document.getElementById('ram_status_chart_div'));
    
    ram_chart.draw(ram_data, ram_options);

    // Draw the SWAP Visualization
	var swap_data = google.visualization.arrayToDataTable([
	                                                  ['Label', 'Space'],
	                                                  ['Available (<?php echo number_format($server_monitor->getAvailableSwap() / 1024 / 1024, 0, null , ',') ?> MB)', {v:<?php echo $server_monitor->getAvailableSwap() ?>, f:'<?php echo number_format($server_monitor->getAvailableSwap() / 1024 / 1024, 0, null , ',') ?> MB'}],
	                                                  ['Used (<?php echo number_format($server_monitor->getUsedSwap() / 1024 / 1024, 0, null , ',') ?> MB)', {v:<?php echo $server_monitor->getUsedSwap() ?>, f:'<?php echo number_format($server_monitor->getUsedSwap() / 1024 / 1024, 0, null , ',') ?> MB'}]
	                                                ]);

    var swap_options = {
      title: 'Swap Allocation (<?php echo number_format($server_monitor->getTotalSwap() / 1024 / 1024 / 1024, 0, null, ',') ?> GB)',
      is3D: true,
    };
    
    var swap_chart = new google.visualization.PieChart(document.getElementById('swap_status_chart_div'));
    
    swap_chart.draw(swap_data, swap_options);
}

function checkProgress() {
	$.rad.get('/api', {func: '/admin/update-progress' }, function(data) {
		if (data.record) {
			$('.progress-bar', '#update_progress_div').css('width', (data.record.percent_complete) + '%');
			$('#update_progress_status', '#update_progress_div').html(data.record.update_message);
			if (!data.record.is_updating) {
			    // We are all done updating, so stop checking
				$('.progress-bar', '#update_progress_div').css('width', '100%');
				$('#update_progress_status', '#update_progress_div').html('Completing update');
				$(document).oneTime(2000, function () { checkForUpdates(); });
			} else {
				$(document).oneTime(2000, function () { checkProgress(); });
			}		
		}
		
	}, 'json', { show_indicator: false });
}

function checkForUpdates() {
	$('#update_check_div').removeClass('hidden');
	$('#update_div').addClass('hidden');
	$('#update_progress_div').addClass('hidden');
	$('#update_btn_div').addClass('hidden');
	$('#update_current_version_div').addClass('hidden');
	$('.progress-bar', '#update_progress_div').css('width', '0%');
	$('#update_progress_status', '#update_progress_div').html('');
    $.rad.get('/api', { func: '/admin/update-check' }, function(data) {
        if (data.record) {
            if (data.record.update_available) {
                $('.media-heading', '#update_div').html(data.record.newest_package.name + ' (' + data.record.newest_package.version + '.' + data.record.newest_package.release + ')');
                $('.media-description', '#update_div').html(data.record.newest_package.description);
                $('.media-version', '#update_div').html(data.record.newest_package.size);
                $('#update_div').removeClass('hidden');
                if (data.record.is_updating) {
                    $('#update_btn').addClass('hidden');
                    $('#update_progress_div').removeClass('hidden');
                    checkProgress();
                } else {
                	$('#update_btn').removeClass('hidden');
                }
            } else {
            	$('#update_current_version_div').removeClass('hidden');
            }
        }
    	$('#update_check_div').addClass('hidden');
    });
}
//-->
</script>