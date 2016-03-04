<?php
	/* @var $adcampaign \Flux\AdCampaign */
	$adcampaign = $this->getContext()->getRequest()->getAttribute("adcampaign", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/adwords/campaign-search">Ad Campaigns</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<h1>Ad Campaigns</h1>
	</div>
	<div class="help-block">These are all the campaigns assigned to the clients and offers</div>
	<div class="panel panel-primary">
		<div id='adcampaign-header' class='grid-header panel-heading clearfix'>
			<form id="adcampaign_search_form" class="form-inline" method="GET" action="/api">
				<input type="hidden" name="func" value="/adwords/ad-campaign">
				<input type="hidden" name="format" value="json" />
				<input type="hidden" id="page" name="page" value="1" />
				<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
				<input type="hidden" id="sort" name="sort" value="name" />
				<input type="hidden" id="sord" name="sord" value="asc" />
				<div class="text-right">
					<div class="form-group text-left">
						<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="<?php echo $campaign->getKeywords() ?>" />
					</div>
				</div>
			</form>
		</div>
		<div id="adcampaign-grid"></div>
		<div id="adcampaign-pager" class="panel-footer"></div>
	</div>
</div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'name', field:'_id', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/campaign/campaign?_id=' + dataContext._id + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">';
			ret_val += dataContext.name;
			ret_val += '</div>';
			ret_val += '</div>';
			return ret_val;
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