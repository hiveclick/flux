<?php
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
	$domain_groups = $this->getContext()->getRequest()->getAttribute("domain_groups", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
?>
<div class="page-header">
	<div class="pull-right"><a class="btn btn-success" href="/export/split-pane-position-wizard?split_id=<?php echo $split->getId() ?>&position=1" data-toggle="modal" data-target="#split_position_modal"><span class="glyphicon glyphicon-plus"></span> Add New Feed</a></div>
	<h2><a href="/export/split-search">Splits</a> <small><?php echo $split->getName() ?></small></h2>
</div>
<div id="tabs">
	<ul id="split_tabs" class="nav nav-pills">
		<li><a id="tabs-a-main" href="/export/split?_id=<?php echo $split->getId() ?>">Split</a></li>
		<li class="active"><a id="tabs-a-positions" href="/export/split-pane-position?_id=<?php echo $split->getId() ?>">Positions</a></li>
	</ul>
</div>
<div class="help-block hidden-xs">Positions help you send your data to the highest paying clients</div>
<form name="split_position_form" method="POST" class="form-horizontal" autocomplete="off">
	<input type="hidden" name="_id" value="<?php echo $split->getId() ?>" />
	<h3>Position 1 <small>This is your first tier of clients that will be given data</small></h3>
	<div class="panel">
		<?php if (count($split->getPosition1()) > 0) { ?>
			<div id="position_master_container" class="panel panel-default">
				<table class="table table-bordered table-striped table-hover table-condensed">
					<thead>
						<tr>
							<th>Feed</th>
							<th>Cap</th>
							<th>Revenue</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($split->getPosition1() as $split_position) { ?>
							<tr>
								<td>
									<a href="/export/split-pane-position-wizard?_id=<?php echo $split_position->getId() ?>" data-toggle="modal" data-target="#split_position_modal"><?php echo $split_position->getClientExport()->getName() ?></a> (<a href="/client/client?_id=<?php echo $split_position->getClientExport()->getClientId() ?>"><?php echo $split_position->getClientExport()->getClient()->getName() ?></a>)
									<br />
									<small class="text-muted">
										<?php if (count($split_position->getDomainGroupId()) > 0) { ?>
											<strong>Domains:</strong>
											<?php foreach ($split_position->getDomainGroupId() as $key => $domain_group_id) { ?>
												<?php echo ($key > 0) ? ", " : "" ?><?php echo \Flux\DomainGroup::retrieveById($domain_group_id)->getName() ?>
											<?php } ?>
											&nbsp;&nbsp;
										<?php } ?>
										<?php if (count($split_position->getDataFieldId()) > 0) { ?>
											<strong>Required Fields:</strong>
											<?php foreach ($split_position->getDataFieldId() as $key => $data_field_id) { ?>
												<?php echo ($key > 0) ? " ," : "" ?><?php echo \Flux\DataField::retrieveById($data_field_id)->getName() ?>
											<?php } ?>
										<?php } ?>
									</small>
								</td>
								<td>
									<?php echo number_format($split_position->getCap(), 0, null, ',') ?>/day
									<br />
									<small class="text-muted"><?php echo number_format($split_position->getDailyCapCount(), 0, null, ',') ?> received today</small>
								</td>
								<td>$<?php echo number_format($split_position->getRevenue(), 2, null, ',') ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } else { ?>
			<div class="text-center">
				<div class="alert alert-warning">You do not have anyone in this position.
					<a href="/export/split-pane-position-wizard?split_id=<?php echo $split->getId() ?>&position=1" class="btn btn-warning" data-toggle="modal" data-target="#split_position_modal">Add Feed</a>
				</div>
			</div>
		<?php } ?>
	</div>

	<h3>Position 2 <small>Clients here will receive data if nobody in position 1 can accept the data</small></h3>
	<div class="panel">
		<?php if (count($split->getPosition2()) > 0) { ?>
			<div class="panel panel-default">
				<table class="table table-bordered table-striped table-hover table-condensed">
					<thead>
						<tr>
							<th>Feed</th>
							<th>Cap</th>
							<th>Revenue</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($split->getPosition2() as $split_position) { ?>
							<tr>
								<td>
									<a href="/export/split-pane-position-wizard?_id=<?php echo $split_position->getId() ?>" data-toggle="modal" data-target="#split_position_modal"><?php echo $split_position->getClientExport()->getName() ?></a> (<a href="/client/client?_id=<?php echo $split_position->getClientExport()->getClientId() ?>"><?php echo $split_position->getClientExport()->getClient()->getName() ?></a>)
									<br />
									<small>
										<?php if (count($split_position->getDomainGroupId()) > 0) { ?>
											<strong>Domains:</strong>
											<?php foreach ($split_position->getDomainGroupId() as $key => $domain_group_id) { ?>
												<?php echo ($key > 0) ? ", " : "" ?>
												<?php echo \Flux\DomainGroup::retrieveById($domain_group_id)->getName() ?>
											<?php } ?>
											&nbsp;&nbsp;
										<?php } ?>
										<?php if (count($split_position->getDataFieldId()) > 0) { ?>
											<strong>Required Fields:</strong>
											<?php foreach ($split_position->getDataFieldId() as $key => $data_field_id) { ?>
												<?php echo ($key > 0) ? ", " : "" ?>
												<?php echo \Flux\DataField::retrieveById($data_field_id)->getName() ?>
											<?php } ?>
										<?php } ?>
									</small>
								</td>
								<td>
									<?php echo number_format($split_position->getCap(), 0, null, ',') ?>/day
									<br />
									<small class="text-muted"><?php echo number_format($split_position->getDailyCapCount(), 0, null, ',') ?> received today</small>
								</td>
								<td>$<?php echo number_format($split_position->getRevenue(), 2, null, ',') ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } else { ?>
			<div class="text-center">
				<div class="alert alert-warning">You do not have anyone in this position.
					<a href="/export/split-pane-position-wizard?split_id=<?php echo $split->getId() ?>&position=2" class="btn btn-warning" data-toggle="modal" data-target="#split_position_modal">Add Feed</a>
				</div>
			</div>
		<?php } ?>
	</div>

	<h3>Position 3 <small>Clients here will receive data if nobody in position 2 can accept the data</small></h3>
	<div class="panel">
		<?php if (count($split->getPosition3()) > 0) { ?>
			<div class="panel panel-default">
				<table class="table table-bordered table-striped table-hover table-condensed">
					<thead>
						<tr>
							<th>Feed</th>
							<th>Cap</th>
							<th>Revenue</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($split->getPosition3() as $split_position) { ?>
							<tr>
								<td>
									<a href="/export/split-pane-position-wizard?_id=<?php echo $split_position->getId() ?>" data-toggle="modal" data-target="#split_position_modal"><?php echo $split_position->getClientExport()->getName() ?></a> (<a href="/client/client?_id=<?php echo $split_position->getClientExport()->getClientId() ?>"><?php echo $split_position->getClientExport()->getClient()->getName() ?></a>)
									<br />
									<small>
										<?php if (count($split_position->getDomainGroupId()) > 0) { ?>
											<strong>Domains:</strong>
											<?php foreach ($split_position->getDomainGroupId() as $key => $domain_group_id) { ?>
												<?php echo ($key > 0) ? ", " : "" ?>
												<?php echo \Flux\DomainGroup::retrieveById($domain_group_id)->getName() ?>
											<?php } ?>
											&nbsp;&nbsp;
										<?php } ?>
										<?php if (count($split_position->getDataFieldId()) > 0) { ?>
											<strong>Required Fields:</strong>
											<?php foreach ($split_position->getDataFieldId() as $key => $data_field_id) { ?>
												<?php echo ($key > 0) ? ", " : "" ?>
												<?php echo \Flux\DataField::retrieveById($data_field_id)->getName() ?>
											<?php } ?>
										<?php } ?>
									</small>
								</td>
								<td>
									<?php echo number_format($split_position->getCap(), 0, null, ',') ?>/day
									<br />
									<small class="text-muted"><?php echo number_format($split_position->getDailyCapCount(), 0, null, ',') ?> received today</small>
								</td>
								<td>$<?php echo number_format($split_position->getRevenue(), 2, null, ',') ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } else { ?>
			<div class="text-center">
				<div class="alert alert-warning">You do not have anyone in this position.
					<a href="/export/split-pane-position-wizard?split_id=<?php echo $split->getId() ?>&position=3" class="btn btn-warning" data-toggle="modal" data-target="#split_position_modal">Add Feed</a>
				</div>
			</div>
		<?php } ?>
	</div>

	<h3>Position 4 <small>Clients here will receive data last if no one else will accept the data</small></h3>
	<div class="panel">
		<?php if (count($split->getPosition4()) > 0) { ?>
			<div id="position_master_container" class="panel panel-default">
				<table class="table table-bordered table-striped table-hover table-condensed">
					<thead>
						<tr>
							<th>Feed</th>
							<th>Cap</th>
							<th>Revenue</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($split->getPosition3() as $split_position) { ?>
							<tr>
								<td>
									<a href="/export/split-pane-position-wizard?_id=<?php echo $split_position->getId() ?>" data-toggle="modal" data-target="#split_position_modal"><?php echo $split_position->getClientExport()->getName() ?></a> (<a href="/client/client?_id=<?php echo $split_position->getClientExport()->getClientId() ?>"><?php echo $split_position->getClientExport()->getClient()->getName() ?></a>)
									<br />
									<small>
										<?php if (count($split_position->getDomainGroupId()) > 0) { ?>
											<strong>Domains:</strong>
											<?php foreach ($split_position->getDomainGroupId() as $key => $domain_group_id) { ?>
												<?php echo ($key > 0) ? ", " : "" ?>
												<?php echo \Flux\DomainGroup::retrieveById($domain_group_id)->getName() ?>
											<?php } ?>
											&nbsp;&nbsp;
										<?php } ?>
										<?php if (count($split_position->getDataFieldId()) > 0) { ?>
											<?php if (count($split_position->getDomainGroupId()) > 0) { ?>, <?php } ?>
											<strong>Required Fields:</strong>
											<?php foreach ($split_position->getDataFieldId() as $key => $data_field_id) { ?>
												<?php echo ($key > 0) ? ", " : "" ?>
												<?php echo \Flux\DataField::retrieveById($data_field_id)->getName() ?>
											<?php } ?>
										<?php } ?>
									</small>
								</td>
								<td>
									<?php echo number_format($split_position->getCap(), 0, null, ',') ?>/day
									<br />
									<small class="text-muted"><?php echo number_format($split_position->getDailyCapCount(), 0, null, ',') ?> received today</small>
								</td>
								<td>$<?php echo number_format($split_position->getRevenue(), 2, null, ',') ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } else { ?>
			<div class="text-center">
				<div class="alert alert-warning">You do not have anyone in this position.
					<a href="/export/split-pane-position-wizard?split_id=<?php echo $split->getId() ?>&position=4" class="btn btn-warning" data-toggle="modal" data-target="#split_position_modal">Add Feed</a>
				</div>
			</div>
		<?php } ?>
	</div>
</form>
<!-- Modal -->
<div class="modal fade" id="split_position_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>


<script>
//<!--
$(document).ready(function() {
	$('#split_position_modal').on('hidden.bs.modal', function() {
		$(this).removeData();
	});
});
//-->
</script>
