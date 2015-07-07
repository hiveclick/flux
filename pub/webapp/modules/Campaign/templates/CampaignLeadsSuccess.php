<?php
	/* @var $user Flux\User */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart', 'controls']}]}"></script>
<div class="page-header">
	<!-- Actions -->
	<div class="pull-right">
		<div class="visible-sm visible-xs">
			<div class="btn-group">
  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
				</ul>
			</div>
		</div>
		<div class="hidden-sm hidden-xs">
			<div class="btn-group" role="group">
			</div>
		</div>
	</div>
	<h1><img class="img-thumbnail" src="/images/traffic-sources/<?php echo $campaign->getTrafficSource()->getTrafficSourceIcon() != '' ? $campaign->getTrafficSource()->getTrafficSourceIcon() : 'unknown' ?>_48.png" border="0" /> Campaign for <?php echo $campaign->getOffer()->getOfferName() ?> <small><?php echo $campaign->getClient()->getClientName() ?></small></h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/campaign/campaign-search">Campaigns</a></li>
	<li><a href="/campaign/campaign?_id=<?php echo $campaign->getKey() ?>">Campaign #<?php echo $campaign->getKey() ?></a></li>
	<li class="active">Leads</li>
</ol>

<!-- Page Content -->
<div class="help-block">These are the leads that have been generated on this campaign</div>
<br/>
<!-- content -->
<div class="panel panel-info">
	<div id='lead-header' class='grid-header panel-heading clearfix'>Search for leads</div>
	<div class="panel-body">
		<form id="lead_search_form" method="GET" action="/api">
			<input type="hidden" name="func" value="/lead/lead-search">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="sort" name="sort" value="_id" />
			<input type="hidden" id="sord" name="sord" value="desc" />
            <input type="hidden" id="campaign_id" name="campaign_id_array[]" value="<?php echo $campaign->getKey() ?>" />
			<div class="row">
    			<div class="form-group text-left col-md-4">
    				<label>Search by lead name or id: </label>
    				<input type="text" class="form-control" placeholder="search by name or id" size="35" id="txtSearch" name="keywords" value="" />
    			</div>
    			<div class="form-group text-left col-md-5">
    				<label>Only show leads with the following fields set: </label>
    				<select class="form-control selectize" name="required_fields[]" id="required_fields" multiple placeholder="Add required fields..."></select>
    			</div>
    			<div class="form-group text-left col-md-3">
    				<label>Show leads created on: </label>
    				<input type="text" class="form-control" placeholder="search by created date" size="35" id="start_date" name="start_date" value="<?php echo isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '' ?>" />
    			</div>
			</div>
			
			<div class="text-center">
				<input type="submit" value="search" class="btn btn-primary" />
			</div>
		</form>
	</div>
</div>

<div class="panel panel-primary">
	<div id='lead-header' class='grid-header panel-heading clearfix'>&nbsp;</div>
	<div id="lead-grid"></div>
	<div id="lead-pager" class="panel-footer"></div>
</div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
   		{id:'_id', name:'Lead #', field:'_id', sort_field:'_id', def_value: ' ', width:175, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
   			var ret_val = '<div style="line-height:16pt;">'
   			ret_val += '<a href="/campaign/campaign-lead?_id=' + value + '">' + value + '</a>';
   			ret_val += '<div class="small text-muted">';
   			ret_val += ' (' + dataContext._t.offer.offer_name + ' on ' + dataContext._t.client.client_name + ')';
   			ret_val += '</div>';
   			ret_val += '</div>';
   			return ret_val;
   		}},
   		{id:'contact_name', name:'Lead Name', field:'_id', sort_field:'_d.fn', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
   			var name = (dataContext._d.name == undefined) ? '' : dataContext._d.name;
   			if (name == '') {
   			    var name = (dataContext._d.fn == undefined) ? '' : dataContext._d.fn;
   			    name += (dataContext._d.ln == undefined) ? '' : (' ' + dataContext._d.ln);
   			}			
   			var email = (dataContext._d.em == undefined) ? '' : 'E: ' + dataContext._d.em;
   			var phone = (dataContext._d.ph1 == undefined) ? '' : ', P: ' + dataContext._d.ph1;
   			var ret_val = '<div style="line-height:16pt;">'
   				ret_val += '<a href="/campaign/campaign-lead?_id=' + value + '">' + name + '</a>';
   				ret_val += '<div class="small text-muted">';
   				ret_val += ' (' + email + phone + ')';
   				ret_val += '</div>';
   				ret_val += '</div>';
   				return ret_val;
   		}}
   		<?php
   			/* @var $data_field \Flux\DataField */
   			foreach ($data_fields as $key => $data_field) {
   		?>
   			<?php if (trim($data_field->getKeyName()) != '' && trim($data_field->getKeyName()) != 'fn' && ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT || $data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING || $data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT)) { ?>
   				,
   				<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
   					{id:'<?php echo $data_field->getKeyName() ?>', name:'<?php echo ucfirst(strtolower(preg_replace("/[^a-zA-Z0-9 ]/", "", $data_field->getName()))) ?>', field:'_d.<?php echo $data_field->getKeyName() ?>', hidden: true, def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
   						return value;
   					}}
   				<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
   					{id:'<?php echo $data_field->getKeyName() ?>', name:'<?php echo ucfirst(strtolower(preg_replace("/[^a-zA-Z0-9 ]/", "", $data_field->getName()))) ?>', field:'_t.<?php echo $data_field->getKeyName() ?>', hidden: true, def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
   						<?php if ($data_field->getKeyName() == \Flux\DataField::DATA_FIELD_REF_CLIENT_ID) { ?>
   							if (value.client_name) {
   								return value.client_name;
   							} else {
   								return '<em class="text-muted">-- not set --</em>';
   							}
   						<?php } else if ($data_field->getKeyName() == \Flux\DataField::DATA_FIELD_REF_OFFER_ID) { ?>
   							if (value.offer_name) {
   								return value.offer_name;
   							} else {
   								return '<em class="text-muted">-- not set --</em>';
   							}
   						<?php } else { ?>
   							return value;
   						<?php } ?>
   					}}
   				<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
   					{id:'<?php echo $data_field->getKeyName() ?>', name:'<?php echo ucfirst(strtolower(preg_replace("/[^a-zA-Z0-9 ]/", "", $data_field->getName()))) ?>', field:'_e.<?php echo $data_field->getKeyName() ?>', hidden: true, def_value: ' ', sortable:false, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
   						var ret_val;
   						$.each(dataContext._e, function(i, item) {
   							if (item.data_field.data_field_id == '<?php echo $data_field->getId() ?>') {
   								// This is our event
   							        ret_val = '<div style="line-height:16pt;">'
   								    if (item.v == '1') {
   								        ret_val += 'Yes';
   								    } else {
   								    	ret_val += 'No';
   								    }
   									ret_val += '<div class="small text-muted">';
   									if (moment.unix(item.t.sec).isBefore(moment().startOf('day'), 'day')) {
   										ret_val += ' (' + moment.unix(item.t.sec).format("MMM Do [at] LT") + ')';
   									} else {
   									    ret_val += ' (' + moment.unix(item.t.sec).format("LT") + ')';
   									}
   									ret_val += '</div>';
   									ret_val += '</div>';
   							}
   						});
   						return ret_val;
   					}}
   				<?php } ?>
   			<?php } ?>
   		<?php } ?>
   	];

   	slick_grid = $('#lead-grid').slickGrid({
   		pager: $('#lead-pager'),
   		form: $('#lead_search_form'),
   		columns: columns,
   		useFilter: false,
   		cookie: '<?php echo $_SERVER['PHP_SELF'] ?>',
   		pagingOptions: {
   			pageSize: <?php echo \Flux\Preferences::getPreference('items_per_page', 25) ?>,
   			pageNum: 1
   		},
   		slickOptions: {
   			defaultColumnWidth: 150,
   			forceFitColumns: true,
   			enableCellNavigation: false,
   			width: 800,
   			rowHeight: 48
   		}
   	});

   	$('#start_date').datepicker();

   	$("#txtSearch",'#lead_search_form').keyup(function(e) {
   		// clear on Esc
   		if (e.which == 27) {
   			this.value = "";
   		} else if (e.which == 13) {
   			$('#lead_search_form').trigger('submit');
   		}
   	});

   	
   	$('#required_fields','#lead_search_form').selectize({
   		valueField: 'key_name',
   		labelField: 'name',
   		searchField: ['name', 'description', 'request_names'],
   		dropdownWidthOffset: 150,
   		options: [
               <?php foreach ($data_fields as $data_field) { ?>
                   <?php echo json_encode($data_field->toArray()) ?>,
               <?php } ?>		
   		],
   		render: {
   			item: function(item, escape) {
   				var label = item.name || item.key;            
   	            return '<div class="item">' + escape(label) + '</div>';
   			},
   			option: function(item, escape) {
   				var label = item.name || item.key;
   	            var caption = item.description ? item.description : null;
   	            var keyname = item.key_name ? item.key_name : null;
   	            var tags = item.tags ? item.tags : null;
   	            var tag_span = '';
   				$.each(tags, function(j, tag_item) {
   					tag_span += '<span class="label label-default">' + escape(tag_item) + '</span> ';
   				});	            
   	            return '<div style="border-bottom: 1px dotted #C8C8C8;">' +
   	                '<b>' + escape(label) + '</b> <span class="pull-right label label-success">' + escape(keyname) + '</span><br />' +
   	                (caption ? '<span class="text-muted small">' + escape(caption) + ' </span>' : '') +
   	                '<div>' + tag_span + '</div>' +
   	            '</div>';
   			}
   		}
   	});
    // Preload any required fields from a saved search
    <?php if (isset($_REQUEST['required_fields']) && is_array($_REQUEST['required_fields'])) { ?>
	<?php foreach ($_REQUEST['required_fields'] as $required_field) { ?>
    $('#required_fields','#lead_search_form').selectize()[0].selectize.addItem('<?php echo $required_field ?>');
	<?php } ?>
	<?php } ?>
   
    $('#lead_search_form').trigger('submit');
});
//-->
</script>