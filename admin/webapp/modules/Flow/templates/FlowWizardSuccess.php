<?php
	/* @var $flow Flux\Flow */
	$flow = $this->getContext()->getRequest()->getAttribute("flow", array());
?>
<div class="page-header">
   <h2><a href="/flow/flow-search">Flows</a> <small>New Flow</small></h2>
</div>
<div class="help-block">Use this page to add a new flow to the system.  This flow will have default rules loaded for it</div>
<br/>
<div id="tab-content-container" class="tab-content">
	<form name="main_form" method="POST" class="form-horizontal" autocomplete="off">

		<div class="form-group">
			<label class="col-sm-2 control-label" for="name">Name</label>
			<div class="col-sm-10">
				<input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $flow->retrieveValueHtml('name'); ?>" />
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label" for="request_name">Request Name</label>
			<div class="col-sm-10">
				<input type="text" id="request_name" name="request_name" class="form-control" placeholder="Request Name" value="<?php echo $flow->retrieveValueHtml('request_name'); ?>" />
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label" for="status">Status</label>
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
			  <input type="submit" name="__save" class="btn btn-success" value="Save Flow" />
			</div>
		</div>

	</form>
</div>