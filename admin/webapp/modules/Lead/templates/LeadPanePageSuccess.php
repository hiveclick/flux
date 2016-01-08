<?php
	/* @var $lead_page \Flux\LeadPage */
	$lead_page = $this->getContext()->getRequest()->getAttribute('lead_page', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Lead Cookies</h4>
</div>
<div class="modal-body">
	
		<div role="tabpanel">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<?php foreach ($lead_page->getCookies() as $key => $cookie) { ?>
					<li role="presentation" class="<?php echo $key == 0 ? 'active'  : '' ?>">
						<a href="#cookie_<?php echo $key ?>" role="tab" data-toggle="tab">
							Cookie #<?php echo $key + 1 ?>
							<div class="small"><?php echo date('m/d/Y g:i:s', $cookie['t']->sec) ?></div>
						</a>
					</li>
				<?php } ?>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<?php foreach ($lead_page->getCookies() as $key => $cookie) { ?>
					<div role="tabpanel" class="tab-pane <?php echo $key == 0 ? 'active'  : '' ?>" id="cookie_<?php echo $key ?>">
						<div class="help-block">This is the parsed cookie data that was stored when this page was loaded on <?php echo date('m/d/Y g:i:s', $cookie['t']->sec) ?></div>
						<div style="max-height:600px;overflow:auto;">
							<table class="table">
								<thead>
									<th>Parameter</th>
									<th>Value</th>
								</thead>
								<tbody>
									<?php 
										foreach (json_decode($cookie['data']) as $key => $value) {
									?>
										<tr>
											<td><?php echo $key ?></td>
											<td>
												<?php if (is_array($value)) { ?>
													<?php echo implode(", ", $value) ?>
												<?php } else { ?>
													<?php echo $value ?>
												<?php } ?>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>