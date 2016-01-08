<?php
	/* @var $split_queue \Flux\SplitQueue */
	$splits = $this->getContext()->getRequest()->getAttribute("splits", array());
?>
<ol class="breadcrumb">
	<li><a href="/export/split-search">Splits</a></li>
	<li class="active">Test Ubot Scripts</li>
</ol>

<div class="container-fluid">
	<div class="page-header">
	   <h1>Test Ubot Scripts</h1>
	</div>
	<div class="help-block">You can test the various Ubot entry points here.  Simply select a page to load.</div>
	<div class="row">
		<div class="col-md-3">
			<h3>Get Next Lead</h3>
			<hr />
			<form method="GET" class="form-horizontal" action="/export/get-next-lead" target="ubot_test_iframe" id="get_next_lead_form">
				<select id="split_id_array" name="split_id_array[]" placeholder="select a split to load" >
					<?php foreach ($splits as $split) { ?>
						<option value="<?php echo $split->getId() ?>"><?php echo $split->getName() ?></option>
					<?php } ?>
				</select>
				<br />
				<div>
					<input type="submit" class="btn btn-info" value="Get Next Lead" />
				</div>
			</form>
		</div>
		<div class="col-md-3">
			<h3>Flag Next Lead</h3>
			<hr />
			<form method="GET" class="form-horizontal" action="/export/flag-next-lead" target="ubot_test_iframe" id="flag_next_lead_form">
				<input type="text" name="_id" value="" class="form-control" placeholder="enter lead id" />
				<br />
				<div>
					<input type="submit" class="btn btn-info" value="Flag Next Lead" />
				</div>
			</form>
		</div>
	</div>
	<hr />
	<iframe name="ubot_test_iframe" frameborder="0" width="100%" height="1000"></iframe>
</div>
<script>
//<!--
$(document).ready(function() {
	
	$('#split_id_array').selectize({
		dropdownWidthOffset: 150,
		allowEmptyOption: true
	}).on('change', function(e) {
		$('#get_next_lead_form').trigger('submit');
	});

	// submit the form to initially fill in the grid
});
//-->
</script>
