<?php
	/* @var $ad_campaign \Flux\AdCampaign */
	$ad_campaign = $this->getContext()->getRequest()->getAttribute("ad_campaign", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/campaign/ad-campaign-search">Ad Campaigns</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<h1>Ad Campaigns</h1>
	</div>
	<div class="help-block">These are all the ad campaigns synced from Google AdWords</div>
	<div class="panel panel-primary">
		<div id='adcampaign-header' class='grid-header panel-heading clearfix'>
			<form id="adcampaign_search_form" class="form-inline" method="GET" action="/adwords/ad-campaign">
				<input type="hidden" name="format" value="json" />
				<input type="hidden" id="page" name="page" value="1" />
				<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
				<input type="hidden" id="sort" name="sort" value="name" />
				<input type="hidden" id="sord" name="sord" value="asc" />
				<div class="text-right">
					<div class="form-group text-left">
						<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="<?php echo $ad_campaign->getKeywords() ?>" />
					</div>
				</div>
			</form>
		</div>
		<div id="adcampaign-grid"></div>
		<div id="adcampaign-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit saved-search modal -->
<div class="modal fade" id="edit_saved_search_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'name', field:'_id', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/campaign/ad-campaign?_id=' + dataContext._id + '">' + dataContext.name + '</a>';
			ret_val += '<div class="small text-muted">';
			ret_val += 'Google Campaign Id: ' + dataContext.campaign_id;
			ret_val += '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'campaign_id', name:'campaign #', field:'campaign_id', sort_field:'campaign_id.name', hidden: true, def_value: ' ', cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', sort_field:'name', def_value: ' ', hidden: true, sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<a href="/client/client?_id=' + dataContext._id + '">' + value + '</a>';
		}},
		{id:'status', name:'status', field:'status', sort_field:'status', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'spend', name:'spend', field:'mtd_cost', sort_field:'mtd_cost', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<b class="text-primary">$' + $.number(dataContext.mtd_cost, 2) + '</b> <span class="text-muted small">/ $' + $.number(dataContext.daily_cost, 2) + ' today</span>';
				ret_val += '<div class="small text-muted">';
				ret_val += '$' + $.number(dataContext.daily_average_cost, 2) + ' cost per lead';
				ret_val += '</div>';
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'clicks', name:'clicks', field:'mtd_clicks', sort_field:'mtd_clicks', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:32pt;">'
				ret_val += '<b class="text-primary">' + $.number(dataContext.mtd_clicks, 0) + ' clicks</b> <span class="text-muted small">/ ' + $.number(dataContext.daily_clicks, 0) + ' today</span>';
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'daily_cost', name:'Spend', field:'daily_cost', sort_field:'daily_cost', hidden: true, def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return ('$' + $.number(value, 2));
		}},
		{id:'daily_clicks', name:'Clicks', field:'daily_clicks', sort_field:'daily_clicks', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return $.number(value, 0);
		}},
		{id:'daily_average_cost', name:'CPL', field:'daily_average_cost', sort_field:'daily_cost', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return ('$' + $.number(value, 2));
		}},
		{id:'mtd_cost', name:'MTD Spend', field:'mtd_cost', sort_field:'daily_cost', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return ('$' + $.number(value, 2));
		}},
		{id:'mtd_clicks', name:'MTD Clicks', field:'mtd_clicks', sort_field:'daily_cost', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return $.number(value, 0);
		}}
	];
	
 	slick_grid = $('#adcampaign-grid').slickGrid({
		pager: $('#adcampaign-pager'),
		form: $('#adcampaign_search_form'),
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
	
 	$("#txtSearch").keyup(function(e) {
  		// clear on Esc
  		if (e.which == 27) {
  			this.value = "";
  		} else if (e.which == 13) {
  			$('#adcampaign_search_form').trigger('submit');
  		}
  	});
	  	
  	$('#adcampaign_search_form').trigger('submit');	
});
//-->
</script>