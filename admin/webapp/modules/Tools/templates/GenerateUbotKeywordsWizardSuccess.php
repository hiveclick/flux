<?php
	/* @var $ubot_queue \Flux\UbotQueue */
	$ubot_queue = $this->getContext()->getRequest()->getAttribute("ubot_queue", array());
?>
<!-- Add breadcrumbs -->
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Generate Ubot Comments</h4>
</div>
<div class="modal-body">
	<div role="tabpanel">
		<ul id="tabs" class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#generate" role="tab" data-toggle="tab">Step #1: Generate Ubot Comments</a></li>
		</ul>
		<form method="POST" action="/api" id="generate_ubot_keyword_form">
			<input type="hidden" name="func" value="/admin/ubot-queue" />
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="generate">
					<div class="form-group">
						<h3>Enter Keywords</h3>
						<div class="help-block">Enter a list of keywords below.  These keywords will be paired with the links and will be submitted to the Pingback sites.</div>
						<textarea class="form-control" id="keyword_array" name="keyword_array" rows="10"></textarea>
					</div>
					<div class="form-group">
						<h3>Enter the link to your site</h3>
						<div class="help-block">Enter the link to your page.  A list of anchor tags will be generated that you will need to place on your page.</div>
						<input type="text" name="link" value="" class="form-control" />
					</div>
					<div class="text-center">
						<input type="submit" value="Generate HTML" id="generate_html" class="btn btn-info btn-lg" />
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	//<!--
	$(document).ready(function() {
		$('#generate_ubot_keyword_form').form(function(data) {
			$.rad.notify('Keywords Generated', 'Keywords have been generated and will be processed shortly.');
			$('#ubot_search_form').trigger('submit');
		});
	});
	//-->
</script>