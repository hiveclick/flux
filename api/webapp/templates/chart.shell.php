<?php
	header('X-DataSource-Auth:1');
	//header('Access-Control-Allow-Origin:http://www.flux.dev');
	header('Content-Type: text/javascript');
	/* @var $ajax_form BasicAjaxForm */ 
	$ajax_form = $this->getContext()->getRequest()->getAttribute('ajax_form');
	if (is_null($ajax_form)) {
		$ajax_form = new BasicAjaxForm();
	}
?>
<?php 
/*
$response = array(
			"version" => "0.6",
			"status" => "ok",
			"sig" => "1029305520",
			"table" => array(
					"cols" => $ajax_form->getRecord()->getCols(),
					"rows" => $ajax_form->getRecord()->getRows()
			) 
);
google.visualization.Query.setResponse(<?php echo @json_encode($response) ?>);
*/
?>
// Data table response
google.visualization.Query.setResponse({"version":"0.6","status":"ok","sig":"1029305520","table":{"cols":[{"id":"A","label":"","type":"string","pattern":""},{"id":"B","label":"Country code","type":"string","pattern":""},{"id":"C","label":"Population","type":"number","pattern":"#0.###############"},{"id":"D","label":"Population Density","type":"number","pattern":"#0.###############"},{"id":"E","label":"","type":"string","pattern":""}],"rows":[{"c":[{"v":"China"},{"v":"CN"},{"v":1.32297E9,"f":"1322970000"},{"v":137.0,"f":"137"},{"v":""}]},{"c":[{"v":"India"},{"v":"IN"},{"v":1.13013E9,"f":"1130130000"},{"v":336.0,"f":"336"},{"v":""}]},{"c":[{"v":"United States"},{"v":"US"},{"v":3.03605941E8,"f":"303605941"},{"v":31.0,"f":"31"},{"v":""}]},{"c":[{"v":"Indonesia"},{"v":"ID"},{"v":2.31627E8,"f":"231627000"},{"v":117.0,"f":"117"},{"v":""}]},{"c":[{"v":"Brazil"},{"v":"BR"},{"v":1.86315468E8,"f":"186315468"},{"v":22.0,"f":"22"},{"v":""}]},{"c":[{"v":"Pakistan"},{"v":"PK"},{"v":1.626525E8,"f":"162652500"},{"v":198.0,"f":"198"},{"v":""}]},{"c":[{"v":"Bangladesh"},{"v":"BD"},{"v":1.58665E8,"f":"158665000"},{"v":1045.0,"f":"1045"},{"v":""}]},{"c":[{"v":"Nigeria"},{"v":"NG"},{"v":1.48093E8,"f":"148093000"},{"v":142.0,"f":"142"},{"v":""}]},{"c":[{"v":"Russia"},{"v":"RU"},{"v":1.41933955E8,"f":"141933955"},{"v":8.4,"f":"8.4"},{"v":""}]},{"c":[{"v":"Japan"},{"v":"JP"},{"v":1.2779E8,"f":"127790000"},{"v":339.0,"f":"339"},{"v":""}]},{"c":[{"v":""},{"v":""},,,{"v":""}]},{"c":[{"v":""},{"v":""},,,{"v":""}]},{"c":[{"v":""},{"v":""},,,{"v":""}]},{"c":[{"v":""},{"v":""},,,{"v":""}]},{"c":[{"v":""},{"v":""},,,{"v":""}]},{"c":[{"v":""},{"v":""},,,{"v":""}]},{"c":[{"v":""},{"v":""},,,{"v":""}]},{"c":[{"v":""},{"v":""},,,{"v":""}]},{"c":[{"v":""},{"v":"In this sample, we see the Heatmap gadget using two views:"},,,{"v":""}]},{"c":[{"v":""},{"v":"The above gadget shows the whole world,"},,,{"v":""}]},{"c":[{"v":""},{"v":"and the other gadget shows only Asia."},,,{"v":""}]},{"c":[{"v":""},{"v":"The next sheet in this sample, shows the United States view,"},,,{"v":""}]},{"c":[{"v":""},{"v":"which takes data in a different format (U.S. states country code)."},,,{"v":""}]},{"c":[{"v":""},{"v":""},,,{"v":""}]}]}});