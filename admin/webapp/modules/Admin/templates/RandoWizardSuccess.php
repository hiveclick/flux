<?php
	/* @var $rando Flux\Rando */
	$rando = $this->getContext()->getRequest()->getAttribute("rando", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Import Random Data</h4>
</div>
<form id="rando_form" method="POST" action="/admin/import-rando" autocomplete="off" role="form" enctype="multipart/form-data">
	<div class="modal-body">
		<div class="help-block">You can use this form to import random data into the system.  You can get a file of random data from <a href="http://www.fakenamegenerator.com">Fake Name Generator</a></div>
		<div class="form-group">
			<label class="control-label" for="import_file">File</label>
			<input type="file" name="import_file" id="import_file" class="form-control" placeholder="Select File..." />
		</div>
		<div class="help-block">
			The file should have the following columns in it
			<ul>
				<li><code>Given Name</code></li>
				<li><code>Surname</code></li>
				<li><code>E-mail Address</code></li>
				<li><code>Telephone number</code></li>
				<li><code>Street address</code></li>
				<li><code>City</code></li>
				<li><code>State abbreviation</code></li>
				<li><code>Postal code</code></li>
				<li><code>Birthday (m/d/yyyy)</code></li>
				<li><code>Username</code></li>
				<li><code>Password</code></li>
				<li><code>Browser user agent</code></li>
				<li><code>National ID number</code></li>
			</ul>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#rando_form').form(function(data) {
		console.log('form submitted');
		$.rad.notify('Random Data Uploaded', 'The random data has been uploaded and imported');
		$('#rando_search_form').trigger('submit');
		$('#edit_rando_modal').modal('hide');
		return false;
	}, {keep_form:1});
});
//-->
</script>