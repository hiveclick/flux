<?php
	/* @var $flow Flux\Flow */
	$flow = $this->getContext()->getRequest()->getAttribute("flow", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
?>
<style type="text/css">
	.depth-0 {
		padding-left:0;
	}
	.depth-1 {
		padding-left:10px;
	}
	.depth-2 {
		padding-left:20px;
	}
	.depth-3 {
		padding-left:30px;
	}
	.depth-4 {
		padding-left:40px;
	}
	.depth-5 {
		padding-left:50px;
	}
	.inactive {
		background-color:#ccc;
		font-style:italic;
	}
	.inactive-child {
		background-color:#ccc;
	}
</style>
<div class="page-header">
	<div class="pull-right visible-xs">
		<button class="navbar-toggle collapsed visible-xs" type="button" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<h2><a href="/flow/flow-search">Flows</a> <small><?php echo $flow->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
	<ul id="flow_tabs" class="nav nav-pills">
		<li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Main</a></li>
		<li><a id="tabs-a-flow" href="#tabs-nodes" data-toggle="tab" data-url="/flow/flow-pane-node?_id=<?php echo $flow->getId() ?>">Flow</a></li>
	</ul>
</div>
<div id="tab-content-container" class="tab-content">
	<div id="tabs-main" class="tab-pane active">
		<div class="help-block">You can manage a flow on this screen by adding rules and filters to it</div>
		<br/>
		<form name="main_form" method="POST" class="form-horizontal" autocomplete="off">
			<input type="hidden" name="_id" value="<?php echo $flow->getId() ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
				<div class="col-sm-10">
					<input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $flow->getName() ?>" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label hidden-xs" for="request_name">Request Name</label>
				<div class="col-sm-10">
					<input type="text" id="request_name" name="request_name" class="form-control" placeholder="Request Name" value="<?php echo $flow->getRequestName() ?>" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
				<div class="col-sm-10">
					<select class="form-control" name="status" id="status" placeholder="Status">
						<?php foreach(\Flux\Flow::retrieveStatuses() AS $status_id => $status_name) { ?>
						<option value="<?php echo $status_id; ?>"<?php echo $flow->retrieveValue('status') == $status_id ? ' selected' : ''; ?>><?php echo $status_name; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <input type="submit" name="__save" class="btn btn-success" value="Save" />
				  <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Flow" />
				</div>
			</div>

		</form>
	</div>
	<div id="tabs-nodes" class="tab-pane"></div>
</div>
<script type="text/javascript">
//<!--
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

	$('#btn_delete').click(function() {
		if (confirm('Are you sure you want to delete this flow and completely remove it from the system?')) {
			$.rad.del('/api', { func: '/flow/flow/<?php echo $flow->getId() ?>' }, function(data) {
				$.rad.notify('Flow Removed', 'This flow has been removed from the system.');
			});
		}
	});

	// Store the last clicked tab so it can be loaded on page refreshes
	var localTabStorageName = <?php echo json_encode('flow_tab_' . $flow->getId()); ?>;
	var lastTab = sessionStorage.getItem(localTabStorageName);
	if (lastTab) {
		$('a[href='+lastTab+']').tab('show');
	} else {
		$('ul.nav-pills a:first').tab('show');
	}
});
//-->
</script>