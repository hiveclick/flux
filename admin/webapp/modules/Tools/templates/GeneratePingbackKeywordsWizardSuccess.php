<?php
	/* @var $pingback_keyword_queue \Flux\PingbackKeywordQueue */
	$pingback_keyword_queue = $this->getContext()->getRequest()->getAttribute("pingback_keyword_queue", array());
?>
<!-- Add breadcrumbs -->
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Generate Pingbacks</h4>
</div>
<div class="modal-body">
	<div role="tabpanel">
		<ul id="tabs" class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#generate" role="tab" data-toggle="tab">Step #1: Generate Pingbacks</a></li>
			<li class="disabled" id="save_link_li"><a href="#save" role="tab">Step #2: Save Links</a></li>
		</ul>
		<form method="POST" action="/api" id="generate_pingback_keyword_form">
			<input type="hidden" name="func" value="/admin/pingback-keyword-queue" />
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
						<input type="button" value="Generate HTML" id="generate_html" class="btn btn-info btn-lg" />
						<p></p>
						<div class="progress hidden" id="progress">
							<div class="progress-bar" role="progressbar" style="width:0%;"></div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade inactive" id="save">
					<div class="form-group">
						<h3>Copy HTML to your page</h3>
						<div class="help-block">You need to copy the following HTML code into your page and save it before you generate the keywords.  Once you have done this, come back to this page and click the button at the bottom to save your keywords.</div>
						<textarea class="form-control" rows="10" id="html_code"></textarea>
					</div>
					<div class="text-center">
						<input type="submit" id="save_links" value="Save Keywords" class="btn btn-info btn-lg" />
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	//<!--
	$(document).ready(function() {
		$('#generate_html').on('click', function() {
			// disable the title tab
			$('#set_title_li').attr('disabled', 'disabled');
			$('#set_title_li').addClass('disabled');
			$('#set_title_li a').removeAttr('data-toggle', 'tab');
			$('#progress .progress-bar').css('width', '0%');
			$('#progress').removeClass('hidden');
			$.rad.get('/api', {func: '/admin/pingback', ignore_pagination: 1 }, function(data) {
				if (data.entries) {
					var html = "<div style=\"display:none;\">\n";
					$.each($('#keyword_array').val().split("\n"), function(j, keyword) {
						keyword = keyword.replace(" ", "%20");
						$.each(data.entries, function (i, item) {
							html += "  <a href=\"" + item.url + "?" + keyword + "\">" + keyword + "</a>\n";
							$('#progress .progress-bar').css('width', parseInt((i / (data.entries.length - 1)) * 100) + '%');
						});
					});
					html += "</div>\n";
					$('#html_code').val(html);
					$('#save_link_li').removeAttr('disabled');
					$('#save_link_li').removeClass('disabled');
					$('#save_link_li a').attr('data-toggle', 'tab');
					$('#save').removeClass('inactive');
					$('#tabs a[href="#save"]').tab('show');
				}
			});
		});

		/*
		$('#save_links').on('click', function() {
			$('#set_title_li').attr('disabled', 'disabled');
			$('#set_title_li').removeAttr('disabled');
			$('#set_title_li').removeClass('disabled');
			$('#title').removeClass('inactive');
			$('#set_title_li a').attr('data-toggle', 'tab');
			$('#tabs a[href="#title"]').tab('show');
		});
		*/

		$('#generate_pingback_keyword_form').form(function(data) {
			$.rad.notify('Keywords Generated', 'Keywords have been generated and will be processed shortly.');
			$('#pingback_keyword_search_form').trigger('submit');
		});
	});
	//-->
</script>