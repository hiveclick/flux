<?php
	$links = $this->getContext()->getRequest()->getAttribute('links', array());
?>

<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/report/report-home">Reports</a></li>
	<li><a href="/report/link-check-report">Link Check Report</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<h2>Link Check Report</h2>
	</div>
	<div class="help-block">Check to see if the affiliate links are working or not</div>
	<br/>
	<div id="links">
		<table class="table table-responsive table-bordered table-striped">
		<?php foreach ($links as $key => $link) { ?>
			<tr>
				<td class=""><?php echo $link['name'] ?></td>
				<td class="text-center">
					<?php if ($link['status'] == 'up') { ?>
						<span id="link_status_<?php echo $i ?>" class="text-success"><i class="fa fa-check fa-2x"></i></span>
					<?php } else { ?>
						<span id="link_status_<?php echo $i ?>" class="text-danger"><i class="fa fa-minus-circle fa-2x"></i></span>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</div>
</div>
