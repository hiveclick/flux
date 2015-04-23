<?php
	/* @var $client Flux\Client */
	$client = $this->getContext()->getRequest()->getAttribute("client", array());
	$network_handlers = $this->getContext()->getRequest()->getAttribute("network_handlers", array());
?>
<style>
	#red_<?php echo $client->getId() ?>, #green_<?php echo $client->getId() ?>, #blue_<?php echo $client->getId() ?> {
		float: left;
		clear: left;
		width: 100px;
		margin: 5px;
		font-Size: 5pt;
	}
	#swatch_<?php echo $client->getId() ?> {
		width: 64px;
		height: 64px;
		margin-top: 0px;
		margin-left: 0px;
	}
	.sample_swatch div { width:15px;height:15px;float:left;cursor:pointer; }
	#red_<?php echo $client->getId() ?> .ui-slider-range { background: #ef2929; }
	#red_<?php echo $client->getId() ?> .ui-slider-handle { border-color: #ef2929; }
	#green_<?php echo $client->getId() ?> .ui-slider-range { background: #8ae234; }
	#green_<?php echo $client->getId() ?> .ui-slider-handle { border-color: #8ae234; }
	#blue_<?php echo $client->getId() ?> .ui-slider-range { background: #729fcf; }
	#blue_<?php echo $client->getId() ?> .ui-slider-handle { border-color: #729fcf; }
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo ($client->getId() > 0) ? 'Edit' : 'Add' ?> Client</h4>
</div>
<form id="client_form_<?php echo $client->getId() ?>" method="<?php echo ($client->getId() > 0) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/client/client" />
	<input type="hidden" name="status" value="<?php echo \Flux\Client::CLIENT_STATUS_ACTIVE ?>" />
	<?php if ($client->getId() > 0) { ?>
		<input type="hidden" name="_id" value="<?php echo $client->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
        <!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			<li role="presentation" class=""><a href="#network" role="tab" data-toggle="tab">Network Login</a></li>
		</ul>
		<!-- Tab panes -->
  		<div class="tab-content">
  			<div role="tabpanel" class="tab-pane fade in active" id="basic">
        		<div class="help-block">Clients are used as advertisers or publishers to either manage offers or send traffic</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="name">Name</label>
        			<input type="text" id="name" name="name" class="form-control" placeholder="Enter client's name..." value="<?php echo $client->getName() ?>" />
        		</div>
        
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="type">Client Type</label>
        			<select class="form-control" name="client_type" id="client_type" placeholder="Select the role of this client...">
        				<option value="<?php echo \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN ?>" <?php echo $client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN ? ' selected' : '' ?>>Primary Administrator</option>
        				<option value="<?php echo \Flux\Client::CLIENT_TYPE_AFFILIATE ?>" <?php echo $client->getClientType() == \Flux\Client::CLIENT_TYPE_AFFILIATE ? ' selected' : '' ?>>Affiliate</option>
        			</select>
        		</div>
        		
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="type">Status</label>
        			<select class="form-control" name="status" id="status" placeholder="Select the status of this client...">
        				<option value="<?php echo \Flux\Client::CLIENT_STATUS_ACTIVE ?>" <?php echo $client->getStatus() == \Flux\Client::CLIENT_STATUS_ACTIVE ? ' selected' : '' ?>>Active</option>
        				<option value="<?php echo \Flux\Client::CLIENT_STATUS_INACTIVE ?>" <?php echo $client->getStatus() == \Flux\Client::CLIENT_STATUS_INACTIVE ? ' selected' : '' ?>>Inactive</option>
        			</select>
        		</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="email">Email</label>
        			<input type="email" id="email" name="email" class="form-control" placeholder="Enter client's email..." value="<?php echo $client->getEmail() ?>" />
        		</div>
        	</div>
        	<div role="tabpanel" class="tab-pane fade in" id="network">
                <div class="help-block">You can enter the login and report syncing class here</div>
                <div class="form-group">
        			<label class="control-label hidden-xs" for="login_url">Login URL</label>
        			<input type="text" id="login_url" name="network_url" class="form-control" placeholder="Enter login url" value="<?php echo $client->getNetworkUrl() ?>" />
        		</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="username">Username</label>
        			<input type="text" id="username" name="network_username" class="form-control" placeholder="Enter login username" value="<?php echo $client->getNetworkUsername() ?>" />
        		</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="password">Password</label>
        			<input type="text" id="password" name="network_password" class="form-control" placeholder="Enter login password" value="<?php echo $client->getNetworkPassword() ?>" />
        		</div>
        		<hr />
    			<div class="help-block">Select the color used when displaying this domain group in graphs and charts</div>
    			<div class="form-group">
    				<div class="col-md-2">
    					<table cellpadding="0" cellspacing="0" border="0" class="sample_swatch">
    						<tr>
    							<td>
    								<div style="background-Color:#003366;">&nbsp;</div>
    								<div style="background-Color:#006699;">&nbsp;</div>
    								<div style="background-Color:#0099CC;">&nbsp;</div>
    							</td>
    						</tr>
    						<tr>
    							<td>
    								<div style="background-Color:#006633;">&nbsp;</div>
    								<div style="background-Color:#009933;">&nbsp;</div>
    								<div style="background-Color:#00CC33;">&nbsp;</div>
    							</td>
    						</tr>
    						<tr>
    							<td>
    								<div style="background-Color:#990033;">&nbsp;</div>
    								<div style="background-Color:#CC0033;">&nbsp;</div>
    								<div style="background-Color:#FF0033;">&nbsp;</div>
    							</td>
    						</tr>
    					</table>
    				</div>
    				<div class="col-md-4">
    					<div id="red_<?php echo $client->getId() ?>"></div>
    					<div id="green_<?php echo $client->getId() ?>"></div>
    					<div id="blue_<?php echo $client->getId() ?>"></div>
    				</div>
    				<div class="col-md-6">
    					<div id="swatch_<?php echo $client->getId() ?>" class="img-rounded">
    						<img src="/images/transparent-psd.png" border="0" width="64" height="64" class="img-rounded" />
    					</div>
    					<input type="hidden" id="color_<?php echo $client->getId() ?>" name="color" value="" />
    				</div>
    			</div>
    			<div class="clearfix"></div>
        		<hr />
        		<div class="help-block">You can sync reporting from this network by entering the API credentials below</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="reporting_api_class">Network</label>
        			<select name="reporting_api_class" id="reporting_api_class">
        			     <?php foreach ($network_handlers as $key => $network_handler) { ?>
        			         <option value="<?php echo get_class($network_handler) ?>" <?php echo $client->getReportingApiClass() == get_class($network_handler) ? 'SELECTED' : '' ?>><?php echo get_class($network_handler) ?></option>
        			     <?php } ?>
        			</select>
        		</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="reporting_api_url">API Url</label>
        			<input type="text" id="reporting_api_url" name="reporting_api_url" class="form-control" placeholder="Enter API url" value="<?php echo $client->getReportingApiUrl() ?>" />
        		</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="reporting_api_token">API Token</label>
        			<textarea id="reporting_api_token" name="reporting_api_token" class="form-control" placeholder="Enter API token"><?php echo $client->getReportingApiToken() ?></textarea>
        		</div>
        	</div>
        </div>
	</div>
	<div class="modal-footer">
		<?php if ($client->getId() > 0) { ?>
			<input type="button" class="btn btn-danger" value="Delete Client" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#client_form_<?php echo $client->getId() ?>').form(function(data) {
		$.rad.notify('Client Updated', 'The client has been added/updated in the system');
		$('#client_search_form').trigger('submit');
	}, {keep_form:1});

	$('#client_type,#status,#reporting_api_class').selectize();

	$( "#red_<?php echo $client->getId() ?>, #green_<?php echo $client->getId() ?>, #blue_<?php echo $client->getId() ?>" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 255,
		value: 127,
		slide: refreshSwatch,
		change: refreshSwatch
	});
	loadSwatchFromColor();
	$('.sample_swatch div').click(function() {
		if ($(this).css('backgroundColor').charAt(0)=="#") {
			hex = ($(this).css('backgroundColor').charAt(0)=="#") ? $(this).css('backgroundColor').substring(1,7) : $(this).css('backgroundColor');
			var red = parseInt((hex).substring(0,2),16),
				green = parseInt((hex).substring(2,4),16),
				blue = parseInt((hex).substring(4,6),16);
		} else {
			var parts = $(this).css('backgroundColor').match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
			var red = parts[1],
				green = parts[2],
				blue = parts[3];
		}		
		$("#red_<?php echo $client->getId() ?>").slider("value", red);
		$("#green_<?php echo $client->getId() ?>").slider("value", green);
		$("#blue_<?php echo $client->getId() ?>").slider("value", blue);
	});
	
});

function hexFromRGB(r, g, b) {
	var hex = [
		r.toString( 16 ),
		g.toString( 16 ),
		b.toString( 16 )
	];
	$.each( hex, function( nr, val ) {
		if ( val.length === 1 ) {
			hex[ nr ] = "0" + val;
		}
	});
	return hex.join("").toUpperCase();
}
function refreshSwatch() {
	var red = $("#red_<?php echo $client->getId() ?>").slider("value"),
		green = $("#green_<?php echo $client->getId() ?>").slider("value"),
		blue = $("#blue_<?php echo $client->getId() ?>").slider("value"),
		hex = hexFromRGB(red, green, blue);
	$('#swatch_<?php echo $client->getId() ?>').css("background-color", "#" + hex);
	$('#color_<?php echo $client->getId() ?>').attr('value', "#" + hex);
}

function loadSwatchFromColor() {
	hex = (('<?php echo $client->getColor() ?>').charAt(0)=="#") ? ('<?php echo $client->getColor() ?>').substring(1,7) : ('<?php echo $client->getColor() ?>');
	var red = parseInt((hex).substring(0,2),16),
		green = parseInt((hex).substring(2,4),16),
		blue = parseInt((hex).substring(4,6),16);
	$("#red_<?php echo $client->getId() ?>").slider("value", red);
	$("#green_<?php echo $client->getId() ?>").slider("value", green);
	$("#blue_<?php echo $client->getId() ?>").slider("value", blue);
}

<?php if ($client->getId() > 0) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this client from the system?')) {
		$.rad.del({ func: '/client/client/<?php echo $client->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this client', 'You have deleted this client.  You will need to refresh this page to see your changes.');
			$('#client_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>