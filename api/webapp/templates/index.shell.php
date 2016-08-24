<?php
	header('Content-Type: application/json');
	/* @var $ajax_form BasicAjaxForm */ 
	$ajax_form = $this->getContext()->getRequest()->getAttribute('ajax_form');
	if (is_null($ajax_form)) {
		$ajax_form = new \Mojavi\Form\AjaxForm();
	}
?>
<?php $output = $ajax_form->toArray(); ?>
<?php echo @json_encode($output) ?>
