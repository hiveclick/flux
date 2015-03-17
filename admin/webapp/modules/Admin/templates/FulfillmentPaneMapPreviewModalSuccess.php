<?php
	/* @var $fulfillment \Flux\Fulfillment */
	$fulfillment = $this->getContext()->getRequest()->getAttribute("fulfillment", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Mapping Preview</h4>
</div>
<div class="modal-body">
	<?php if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_POST) { ?>
		This export will POST to the following URL:
		<p />
		<pre><?php echo $fulfillment->getPostUrl() ?></pre>
		<p />
		with these fields set:
		<p />
		<table class="table table-responsive table-bordered">
			<thead>
				<?php foreach ($fulfillment->getMapping() as $fulfillment_mapping) { ?>
					<tr>
						<td><strong><?php echo $fulfillment_mapping->getFieldName() == '' ? $fulfillment_mapping->getDataField()->getDataField()->getKeyName() : $fulfillment_mapping->getFieldName() ?></strong></td>
						<td>
							<?php if ($fulfillment_mapping->getMappingFunc() != \Flux\FulfillmentMap::getDefaultMappingFunc()) { ?>
								<div class="custom-function">
									<button class="btn btn-sm btn-info btn-show-code pull-right">show</button>
									<em>- custom function -</em>
									
									
									<div class="code-preview collapse"><div class="clearfix"></div><pre><?php echo $fulfillment_mapping->getMappingFunc() ?></pre></div>
								</div>
							<?php } else { ?>
								<?php echo $fulfillment_mapping->getDataField()->getDataField()->getKeyName() ?>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			</thead>
		</table>
	<?php } else { ?>
		This export will FTP to the following host:
		<p />
		<pre><?php echo $fulfillment->getFtpUsername() ?>@<?php echo $fulfillment->getFtpHostname() ?></pre>
		<p />
		with these fields set:
		<p />
		<table class="table table-responsive table-bordered">
			<thead>
				<?php foreach ($fulfillment->getMapping() as $fulfillment_mapping) { ?>
					<tr>
						<td><strong><?php echo $fulfillment_mapping->getFieldName() == '' ? $fulfillment_mapping->getDataField()->getDataField()->getKeyName() : $fulfillment_mapping->getFieldName() ?></strong></td>
						<td>
							<?php if ($fulfillment_mapping->getMappingFunc() != \Flux\FulfillmentMap::getDefaultMappingFunc()) { ?>
								<div class="custom-function">
									<button class="btn btn-sm btn-info btn-show-code pull-right">show</button>
									getValue("<em><?php echo $fulfillment_mapping->getDataField()->getDataField()->getKeyName() ?></em>") with <em>custom function</em>
									
									
									<div class="code-preview collapse"><div class="clearfix"></div><pre><?php echo $fulfillment_mapping->getMappingFunc() ?></pre></div>
								</div>
							<?php } else { ?>
								getValue("<em><?php echo $fulfillment_mapping->getDataField()->getDataField()->getKeyName() ?></em>")
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			</thead>
		</table>
	<?php } ?>
</div>
<script>
//<!--
$(document).ready(function() {
	$('.btn-show-code').click(function() {
		var clicked_btn = $(this);
		$(this).closest('.custom-function').find('.code-preview').toggle(0, function() {
			if ($(this).is(':visible')) {
				clicked_btn.text('hide');				
		   } else {
			   clicked_btn.text('show');				
		   }		
	   });
	});
});
//-->
</script>
