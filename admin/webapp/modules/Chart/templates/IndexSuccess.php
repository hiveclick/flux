<?php
    header('Content-Type: application/json');
    /* @var $ajax_form BasicAjaxForm */ 
    $ajax_form = $this->getContext()->getRequest()->getAttribute('ajax_form');
    if (is_null($ajax_form)) {
        $ajax_form = new BasicAjaxForm();
    }
?>
// Data table response
<?php 
$response = array(
            "version" => "0.6",
            "status" => "ok",
            "sig" => "1029305520",
            "table" => array(
                    "cols" => $ajax_form->getRecord()->getCols(),
                    "rows" => $ajax_form->getRecord()->getRows()
            ) 
);
?>
google.visualization.Query.setResponse(<?php echo @json_encode($response) ?>);
