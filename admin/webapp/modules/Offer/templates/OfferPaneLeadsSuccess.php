<?php
	/* @var $offer \Flux\Offer */
	$offer = $this->getContext()->getRequest()->getAttribute('offer', array());
?>
<form id="offer-lead-form" method="GET" action="/lead/lead-search">
	<input type="hidden" name="format" value="json" />
	<input type="hidden" id="page" name="page" value="1" />
	<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
	<input type="hidden" id="sort" name="sort" value="_id" />
	<input type="hidden" id="sord" name="sord" value="desc" />
	<input type="hidden" name="offer_id_array[]" value="<?php echo $offer->getId() ?>" />
	<input type="hidden" name="required_fields[]" value="conv" />
	<input type="hidden" name="required_fields[]" value="pa" />
	<input type="hidden" name="start_date" value="<?php echo date('m/d/Y', strtotime('now - 7 days'))?>" />
</form>
<div class="panel panel-primary">
	<div id='offer-lead-header' class='grid-header panel-heading clearfix'></div>
	<div id="offer-lead-grid"></div>
	<div id="offer-lead-pager" class="panel-footer"></div>
</div>
<script>
//<!--
$(document).ready(function() {
	var offer_lead_columns = [
 		{id:'_id', name:'Lead #', field:'_id', sort_field:'_id', def_value: ' ', width:175, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			var ret_val = '<div style="line-height:16pt;">'
 			ret_val += '<a href="/lead/lead?_id=' + value + '">' + value + '</a>';
 			ret_val += '<div class="small text-muted">';
 			ret_val += ' (<a href="/offer/offer?_id=' + dataContext._t.offer._id + '">' + dataContext._t.offer.name + '</a> on ' + dataContext._t.client.name + ' last updated ' + moment.unix(parseInt(dataContext._id.toString().substring(0,8), 16 )).format("MMM Do [at] LT") + ')';
 			ret_val += '</div>';
 			ret_val += '</div>';
 			return ret_val;
 		}},
 		{id:'contact_name', name:'Lead Name', field:'_d.fn', sort_field:'_d.fn', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			var name = (dataContext._d.name == undefined) ? '' : dataContext._d.name;
 			if (name == '') {
 				var name = (dataContext._d.fn == undefined) ? '' : dataContext._d.fn;
 				name += (dataContext._d.ln == undefined) ? '' : (' ' + dataContext._d.ln);
 			}			
 			var email = (dataContext._d.em == undefined) ? '' : 'E: ' + dataContext._d.em;
 			var phone = (dataContext._d.ph1 == undefined) ? '' : ', P: ' + dataContext._d.ph1;
 			var ret_val = '<div style="line-height:16pt;">'
 				ret_val += '<a href="/lead/lead?_id=' + value + '">' + name + '</a>';
 				ret_val += '<div class="small text-muted">';
 				ret_val += ' (' + email + phone + ')';
 				ret_val += '</div>';
 				ret_val += '</div>';
 				return ret_val;
 		}},
 		{id:'events', name:'Events', field:'_e', sort_field:'_id', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			var ret_val = '<table class="table table-condensed table-bordered">';
 			ret_val += '<tr>';
 			$.each(dataContext._e, function(i, item) {
				ret_val += '<td class="small">';
				ret_val += item.data_field.name;
				ret_val += '<div class="small text-muted">';
				if (moment.unix(item.t.sec).isBefore(moment().startOf('day'), 'day')) {
					ret_val += ' (' + moment.unix(item.t.sec).format("MMM Do") + ')';
				} else {
					ret_val += ' (' + moment.unix(item.t.sec).format("LT") + ')';
				}
				ret_val += '</div>';
				ret_val += '</td>';
 			});
 			ret_val += '</tr>';
 			ret_val += '</table>';
 			return ret_val;
 		}}
 	];

 	slick_grid = $('#offer-lead-grid').slickGrid({
 		pager: $('#offer-lead-pager'),
 		form: $('#offer-lead-form'),
 		columns: offer_lead_columns,
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

 	$('#offer-lead-form').trigger('submit');

});
//-->
</script>