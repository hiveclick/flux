<?php
    $offer = $this->getContext()->getRequest()->getAttribute("offer", array());
?>
<div class="help-block">Manage the assets including creatives, banners and links for this offer</div>
<br/>
<table id="offer_asset_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th style="width:145px;">Preview</th>
            <th>Name</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<form id="offer_asset_search_form" method="GET" action="/api">
    <input type="hidden" name="func" value="/offer/offer-asset">
    <input type="hidden" name="offer_id" value="<?php echo $offer->getId() ?>">
</form>
<script>
//<!--
$(document).ready(function() {
    $('#offer_asset_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: $('#offer_asset_search_form').serializeObject()
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "image_data", data: "image_data", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<div class="thumbnail" style="width: 140px; height: 140px;"><img class="img-rounded img-responsive" src="data:image/png;base64,' + cellData + '" /></div>');
            }},
            { name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
                if (rowData.asset_type == 2 || rowData.asset_type == 3) {
                    var cell = 'Preview<div class="well"><strong>' + rowData.ad_title + '</strong><div class="small">' + rowData.ad_description + '</div></div>';
                } else {
                    var cell = '';
                }
                $(td).html('<a href="/offer/offer-asset-wizard?_id=' + rowData._id + '"><strong>' + cellData + '</strong><div class="small">' + rowData.description + '</div></a><p /><hr />' + cell);
            }},
            { name: "asset_type", data: "asset_type", createdCell: function (td, cellData, rowData, row, col) {
                var asset_type_name = 'Banner';
                if (cellData == '1') {
                	asset_type_name = 'Banner';    
                } else if (cellData == '2') {
                	asset_type_name = 'Offer Wall Image';    
                } else if (cellData == '3') {
                	asset_type_name = 'Path Image';    
                } else if (cellData == '4') {
                	asset_type_name = 'Email Creative (HTML)';    
                } else if (cellData == '5') {
                	asset_type_name = 'Email Creative (Text)';    
                }
                $(td).html(asset_type_name);
            }}
      	]
    });
});
//-->
</script>