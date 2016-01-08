<?php
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$example_qs = array('_ck' => $campaign->getKey(), 'firstname' => 'john', 'lastname' => 'smith', 'email' => 'john@mctesterson.com', 'addr' => '123 Test Street', 'city' => 'Test City', 'state' => 'UT', 'zip' => '84057', 'phone' => '8015551212');
	/* @var $filter \Flux\Link\DataField */
	foreach ($campaign->getOffer()->getOffer()->getSplit()->getSplit()->getFilters() as $filter) {
		if (in_array($filter->getDatafield()->getKeyname(), array('fn','ln','em','ph','cty','st','zip','addr'))) { continue; }
		$values = $filter->getDataFieldValue();
		if (is_array($values) && count($values) > 0) {
			$example_qs[$filter->getDatafield()->getKeyName()] = array_shift($values);
		} else {
			$example_qs[$filter->getDatafield()->getKeyName()] = '';
		}
	} 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Bootstrap and jQuery base classes -->
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
		<style>
			body {
				font-family:verdana,arial,sans-serif;
			}
			tr, td, th {
				page-break-inside: avoid !important;
			}
		</style>
	</head>
	<body>
		<h2>Posting Instructions for <?php echo $campaign->getOffer()->getOfferName() ?></h2>
		<div class="help-block">
			These are the posting instructions to send data to the <b><?php echo $campaign->getOffer()->getOfferName() ?></b> campaign through our API.  The 
			available fields and their possible values are outlined below.  All posts should use the following url: 
		</div>
		<code class="text-center"><?php echo MO_API_URL ?>/rt/post-lead</code>
		<h3>Posting Fields</h3>
		<div class="help-block">
			The available fields you can pass in are defined below.  Most fields and values are case-sensitive (i.e pass in YES instead of Yes or yes).  Fields that accept more than one value need to be suffixed with square brackets - [].  
		</div>
		<table class="table table-responsive">
			<thead>
				<tr>
					<th>Name</th>
					<th>Field</th>
					<th>Required?</th>
					<th>Values</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Campaign Key</td>
					<td>_ck</td>
					<td>Yes</td>
					<td><b><?php echo $campaign->getKey() ?></b></td>
				</tr>
				<tr>
					<td>First name</td>
					<td>firstname</td>
					<td>Yes</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Last name</td>
					<td>lastname</td>
					<td>Yes</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Phone</td>
					<td>phone</td>
					<td>Yes</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Email</td>
					<td>email</td>
					<td>Yes</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Address</td>
					<td>address</td>
					<td>Yes</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>City</td>
					<td>city</td>
					<td>Yes</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>State</td>
					<td>state</td>
					<td>Yes</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Postal Code</td>
					<td>zip</td>
					<td>Yes</td>
					<td>&nbsp;</td>
				</tr>
				<?php 
					/* @var $filter \Flux\Link\DataField */
					foreach ($campaign->getOffer()->getOffer()->getSplit()->getSplit()->getFilters() as $filter) { 
						if (in_array($filter->getDatafield()->getKeyname(), array('fn','ln','em','ph','cty','st','zip','addr'))) { continue; }
				?>   
					<tr>
						<td><?php echo $filter->getDatafield()->getName() ?></td>
						<td><?php echo $filter->getDatafield()->getKeyName() ?><?php echo ($filter->getDatafield()->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY) ? '[]' : '' ?></td>
						<td>Yes</td>
						<td><?php echo implode(", ", $filter->getDataFieldValue()) ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<h3>Response</h3>
		<div class="help-block">
			Responses are received in JSON.  The response object will contain several top-level elements such as RESULT, ERRORS, RECORD, and META.  
			Failed responses will have ERRORS and the RESULT will be "failed".  Successful responses will not have any ERRORS and the RESULT will 
			be "success".  
			<p />
			Additionally, successful responses will have the RECORD element filled with the internal lead id, received time, and any other optional
			messages.  The lead id is also duplicated in the META record.  The META record is reserved for future use.					 
		</div>
		<h4>Example Post Url</h4>
		<pre><?php echo MO_API_URL ?>/rt/post-lead?<?php echo http_build_query($example_qs, null, '&') ?></pre>
		<h4>Successful response</h4>
		<pre>{"result":"SUCCESS","errors":[],"meta":{"insert_id":"552f6fd4d9b868286a8dd08d","rows_affected":1},"record":{"lead":"552f6fd4d9b868286a8dd08d","response":"success","received_time":"04\/16\/2015 01:16:21","_id":0}}</pre>
		<h4>Failed response</h4>
		<pre>{"result":"FAILED","errors":["Validation failed on Phone with value ''"],"meta":{"insert_id":0,"rows_affected":0},"record":{"lead":"552f77f5d9b86844708dd08d","response":"Validation failed on Phone with value ''","received_time":"04\/16\/2015 01:51:01","_id":0}}</pre>
	</body>
</html>