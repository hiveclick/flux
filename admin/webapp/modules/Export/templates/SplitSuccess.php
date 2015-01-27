<?php
	/* @var $split Flux\Split */
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
	$domain_groups = $this->getContext()->getRequest()->getAttribute("domain_groups", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div id="header">
	<h2><a href="/export/split-search">Splits</a> <small><?php echo $split->getName() ?></small></h2>
</div>
<div id="tabs">
	<ul id="split_tabs" class="nav nav-pills">
		<li class="active"><a id="tabs-a-main" href="/export/split?_id=<?php echo $split->getId() ?>">Split</a></li>
		<li><a id="tabs-a-positions" href="/export/split-pane-position?_id=<?php echo $split->getId() ?>">Positions</a></li>
	</ul>
</div>
<div class="help-block">Manage a split and it's rules that determine what exports receive data</div>
<br/>
<form id="split_queue_search_form" method="GET" action="/api">
	<input type="hidden" name="items_per_page" value="100">
	<input type="hidden" name="func" value="/export/split-queue">
	<input type="hidden" name="split_id" value="<?php echo $split->getId() ?>">
</form>
<div class="col-xs-6 col-sm-8 col-md-8 col-lg-9">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">Pending Leads</h4>
		</div>
		<div class="panel-body">
			<table id="split_queue_table" class="table table-hover table-bordered table-striped table-responsive">
				<thead>
					<tr>
						<th>Id</th>
						<th>Email</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="<?php echo count($data_fields) + 1 ?>">
							<div class="alert alert-default text-center"><span class="fa fa-spinner fa-spin"></span> Please wait, loading data...</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">Split Info</h4>
		</div>
		<div class="panel-body">
			<b><?php echo $split->getName() ?></b>
			<div class="help-block"><?php echo $split->getDescription() ?></div>
			<hr />
			<b>Offers</b>
			<ul>
				<?php if (count($split->getOfferId()) > 0) { ?>
					<?php foreach ($split->getOfferId() as $offer_id) { ?>
						<?php 
							$offer = new \Flux\Offer();
							$offer->setId($offer_id);
							$offer->query();
						?>
						<li><?php echo $offer->getName() ?></li>
					<?php } ?>
				<?php } else { ?>
					<li class="help-block"><i>All Offers</i></li>
				<?php } ?>
			</ul>
			<b>Domain Groups</b>
			<ul>
				<?php if (count($split->getDomainGroupId()) > 0) { ?>
					<?php foreach ($split->getDomainGroupId() as $domain_group_id) { ?>
						<?php 
							$domain_group = new \Flux\DomainGroup();
							$domain_group->setId($domain_group_id);
							$domain_group->query();
						?>
						<li><?php echo $domain_group->getName() ?></li>
					<?php } ?>
				<?php } else { ?>
					<li><i class="help-block">All Domain Groups</i></li>
				<?php } ?>
			</ul>
			<b>Required Fields</b>
			<ul>
				<?php if (count($split->getDataFieldId()) > 0) { ?>
					<?php foreach ($split->getDataFieldId() as $data_field_id) { ?>
						<?php 
							$data_field = new \Flux\DataField();
							$data_field->setId($data_field_id);
							$data_field->query();
						?>
						<li><?php echo $data_field->getName() ?></li>
					<?php } ?>
				<?php } else { ?>
					<li class="help-block"><i>All Data Fields</i></li>
				<?php } ?>
			</ul>
		</div>
		<div class="text-center">
			<a data-toggle="modal" data-target="#edit_split_modal" href="/export/split-wizard?_id=<?php echo $split->getId() ?>" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Edit Split Settings</a>
		</div>
		<p class="clearfix" />
	</div>	
	<p />
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">Split Info</h4>
		</div>
		<div class="panel-body">
			<div class="help-block">This split was last run at <?php echo date('m/d/Y g:i:s a', $split->getLastRunTime()->sec) ?>.  If it is frozen, you can attempt to clear the PID information and restart it.</div>
			<form id="split_clear_pid_form" class="text-center" method="POST" action="/api">
				<input type="hidden" name="func" value="/export/split-clear-pid" />
				<input type="hidden" name="_id" value="<?php echo $split->getId() ?>" />
				<input type="submit" class="btn btn-danger" name="btn_submit" value="clear pid" />
			</form>
		</div>
	</div>
</div>

<!-- edit split modal -->
<div class="modal fade" id="edit_split_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {

	$('#split_clear_pid_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Split reset', 'This split has been reset and will resume normal operation within a few minutes');
		}
	});
	
	$('#split_queue_search_form').on('submit', function(e) {
		$('#split_queue_table').DataTable().clearPipeline().draw();
		e.preventDefault();
	});
	
	$('#split_queue_table').DataTable({
		autoWidth: false,
		serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
			url: '/api',
			data: function() {
				return $('#split_queue_search_form').serializeObject();
			},
			method: 'POST'
		}),
		searching: false,
		paging: true,
		dom: 'Rfrtpi',
		columns: [
  				{ name: "_id", data: "_id", createdCell: function (td, cellData, rowData, row, col) {
					$(td).html('<a href="/lead/lead?_id=' + cellData + '">' + cellData + '</a>');
				}},
				{ name: "_d.em", data: "_d.em", createdCell: function (td, cellData, rowData, row, col) {
					$(td).html('<a href="/lead/lead?_id=' + rowData._id + '">' + cellData + '</a>');
				}},
				{ name: "_d.fn", data: "_d.fn", createdCell: function (td, cellData, rowData, row, col) {
					value = rowData._d.fn + ' ' + rowData._d.ln;
					$(td).html('<a href="/lead/lead?_id=' + rowData._id + '">' + value + '</a>');
				}}
				
		  	],
			columnDefs: [
				{
					targets: [ 0,1,2 ], // Show the first column by default
					visible: true,
					orderable: true
					
				}
		   ]
	});

	$('#edit_split_modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
});
//-->
</script>