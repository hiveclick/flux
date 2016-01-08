<?php 
/* @var $server_monitor \Flux\ServerMonitor */
$server_monitor = $this->getContext()->getRequest()->getAttribute('server_monitor', array());
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart', 'controls']}]}"></script>
<div class="page-header">
	<h1>Monitoring</h1>
</div>
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
			<div class="panel-heading"><span class="<?php echo (strpos($server_monitor->getRaidStatusCore9(), 'degraded') !== false) ? 'text-danger' : 'text-success' ?>">Core 09 RAID (Main Flux Server)</span></div>
			<div class="panel-body">
				<?php echo nl2br($server_monitor->getRaidStatusCore9()) ?>
			</div>
		</div>
	</div>
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
			<div class="panel-heading"><span class="<?php echo (strpos($server_monitor->getRaidStatusCore8(), 'degraded') !== false) ? 'text-danger' : 'text-success' ?>">Core 8 RAID (Main Hosting Server)</span></div>
			<div class="panel-body">
				<?php echo nl2br($server_monitor->getRaidStatusCore8()) ?>
			</div>
		</div>
	</div>
</div>

<script>
//<!--
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
//-->
</script>