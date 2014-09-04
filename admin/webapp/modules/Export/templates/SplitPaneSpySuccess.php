<?php
    /* @var $split \Gun\Export */
    $split = $this->getContext()->getRequest()->getAttribute("split", array());
    $offers = $this->getContext()->getRequest()->getAttribute("offers", array());
    $datafields = $this->getContext()->getRequest()->getAttribute("datafields", array());
    $selected_columns = array();
?>
<div class="help-block">View a sample of the data included in this split</div>
<br/>
<div class="panel-group" id="accordion">
    <div class="panel panel-default" style="overflow:visible;">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Search Filters</a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
                <form id="split_queue_search_form" method="GET" action="/api">
                    <input type="hidden" name="items_per_page" value="100">
                    <input type="hidden" name="func" value="/export/split-queue">
                    <input type="hidden" name="split_id" value="<?php echo $split->getId() ?>">
                    <div class="form-group">
                        <div>
                            <input type="text" name="keywords" class="form-control" placeholder="search by name or id" value="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label hidden-xs" for="name">Offer</label>
                        <div class="">
                            <select class="form-control selectize" name="offer_id_array[]" id="split_queue_spy_offer_id" multiple placeholder="All Offers">
                                <?php foreach ($offers as $offer) { ?>
                                    <option value="<?php echo $offer->getId() ?>"><?php echo $offer->getName() ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label hidden-xs" for="name">Require Fields</label>
                        <div class="">
                            <select class="form-control selectize" name="required_fields[]" id="required_fields" multiple placeholder="No Fields">
                                <?php foreach($datafields AS $datafield) { ?>
                                    <?php if ($datafield->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
                                        <option value="<?php echo $datafield->getKeyName() ?>"<?php echo in_array($datafield->getKeyName(), $selected_columns) ? ' selected' : ''; ?>><?php echo $datafield->getName() ?></option>
                                    <?php } else if ($datafield->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
                                        <option value="<?php echo $datafield->getKeyName() ?>"<?php echo in_array($datafield->getKeyName(), $selected_columns) ? ' selected' : ''; ?>><?php echo $datafield->getName() ?></option>
                                    <?php } else if ($datafield->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
                                        <option value="<?php echo $datafield->getKeyName() ?>"<?php echo in_array($datafield->getKeyName(), $selected_columns) ? ' selected' : ''; ?>><?php echo $datafield->getName() ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label hidden-xs" for="name">Show/Hide Columns</label>
                        <div class="">
                            <select class="form-control selectize" name="column_id[]" id="column_id" multiple placeholder="No Columns">
                                <?php foreach($datafields AS $datafield) { ?>
                                    <?php if ($datafield->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
                                        <option value="<?php echo \Gun\DataField::DATA_FIELD_DEFAULT_CONTAINER ?>.<?php echo $datafield->getKeyName() ?>"<?php echo in_array($datafield->getKeyName(), $selected_columns) ? ' selected' : ''; ?>><?php echo $datafield->getName() ?></option>
                                    <?php } else if ($datafield->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
                                        <option value="<?php echo \Gun\DataField::DATA_FIELD_EVENT_CONTAINER ?>.<?php echo $datafield->getKeyName() ?>"<?php echo in_array($datafield->getKeyName(), $selected_columns) ? ' selected' : ''; ?>><?php echo $datafield->getName() ?></option>
                                    <?php } else if ($datafield->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
                                        <option value="<?php echo \Gun\DataField::DATA_FIELD_TRACKING_CONTAINER ?>.<?php echo $datafield->getKeyName() ?>"<?php echo in_array($datafield->getKeyName(), $selected_columns) ? ' selected' : ''; ?>><?php echo $datafield->getName() ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-info" name="btn_submit" value="filter results" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<table id="split_queue_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Id</th>
            <?php
            	foreach ($datafields as $key => $datafield) {
			?>
                <th><?php echo $datafield->getName() ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="<?php echo count($datafields) + 1 ?>">
                <div class="alert alert-default text-center"><span class="fa fa-spinner fa-spin"></span> Please wait, loading data...</div>
            </td>
        </tr>
    </tbody>
</table>
<script>
//<!--
$(document).ready(function() {

	$('#required_fields').selectize();
	$('#split_queue_spy_offer_id').selectize();
	
    $('#split_queue_search_form').on('submit', function(e) {
        $('#split_queue_table').DataTable().clearPipeline().draw();
        e.preventDefault();
    });
	
    $('#split_queue_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: function() {
                return $('#split_queue_search_form').serializeObject();
            },
            method: 'POST'
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
  	            { name: "_id", data: "_id", createdCell: function (td, cellData, rowData, row, col) {
	                $(td).html('<a href="/lead/lead?_id=' + cellData + '">' + cellData + '</a>');
	            }},
	            <?php foreach($datafields AS $datafield) { ?>
	    	        <?php if ($datafield->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
	    	        	{ name: "_d.<?php echo $datafield->getKeyName() ?>", data: "_d.<?php echo $datafield->getKeyName() ?>" },
	    	        <?php } else if ($datafield->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
	    	        	{ name: "_t.<?php echo $datafield->getKeyName() ?>", data: "_t.<?php echo $datafield->getKeyName() ?>", createdCell: function (td, cellData, rowData, row, col) {
		        	        <?php if ($datafield->getKeyName() == \Gun\DataField::DATA_FIELD_REF_CLIENT_ID) { ?>
		        	        	if (cellData.name) {
		                    		$(td).html('<a href="/client/client?_id=' + cellData._id + '">' + cellData.name + '</a>');
		        	        	} else {
									$(td).html('<em class="text-muted">-- not set --</em>');
		        	        	}
		        	        <?php } else if ($datafield->getKeyName() == \Gun\DataField::DATA_FIELD_REF_OFFER_ID) { ?>
		        	        	if (cellData.name) {
		        	        		$(td).html('<a href="/offer/offer?_id=' + cellData._id + '">' + cellData.name + '</a>');
		        	        	} else {
		        	        		$(td).html('<em class="text-muted">-- not set --</em>');
		        	        	}
		        	        <?php } else { ?>
		        	        	$(td).html(cellData);
		        	        <?php } ?>
	                	}},
	    	        <?php } else { ?>
		    	        { name: "_e.<?php echo $datafield->getKeyName() ?>", data: function data( row, type, set, meta ) {
		        	        var ret_val;
		        	        $.each(row._e, function(i, item) {
		        	            if (item.n == '<?php echo $datafield->getKeyName() ?>') {
		        	                // This is our event
		        	                <?php if ($datafield->getFieldType() == \Gun\DataField::DATA_FIELD_TYPE_DATETIME) { ?>
		        	                	ret_val = moment.unix(item.t.sec).calendar();
		        	                <?php } else { ?>
		        	                	ret_val = item.v;
		        	                <?php } ?>
		        	            }
		        	        });
		        	        return ret_val;
		    	        } },
	    	        <?php } ?>
	            <?php } ?>
	      	],
            columnDefs: [
                {
                    targets: [ 0 ], // Show the first column by default
                    visible: true,
                    orderable: true
                    
                },
                {
                     targets: "_all", // Hide all other columns by default
                     visible: false,
                     defaultContent: '',
                     orderable: true
                }
           ]
    });

    /*
     * Load the default columns from the LocalStorage
     */
    var selectize_object = $('#column_id').selectize();
    var selectize_control = selectize_object[0].selectize;
    var spyColumnStorageName = <?php echo json_encode('split_queue_search_column'); ?>;
    var lastColumnStorageSetting = localStorage.getItem(spyColumnStorageName);
    lastColumnStorageSetting = JSON.parse(lastColumnStorageSetting);
    if (lastColumnStorageSetting && (lastColumnStorageSetting instanceof Array)) {
        $.each(lastColumnStorageSetting, function(key, value) {
            // Add the column to the selectize control
            selectize_control.addItem(value);
            // Enable the column for datatables

            col = $('#split_queue_table').DataTable().column(value + ":name");
            col.visible(true);
        });
    }

    /*
     * Handle when columns are added or removed in the selectize
     */
    $('#column_id').on('update_columns', function(e) {
        e.preventDefault();
        var columns_checked_array = $(this).val();
        if (columns_checked_array && (columns_checked_array instanceof Array)) {
            // Hide all the columns, then reshow the ones that are selected
        	$('#split_queue_table').DataTable().columns().visible(false);
        	// Alwasy enable the first column
        	col = $('#split_queue_table').DataTable().column(0);
            col.visible(true);
            $.each(columns_checked_array, function(index, value) {
            	// Enable the column for datatables
                col = $('#split_queue_table').DataTable().column(value + ":name");
                col.visible(true);
            });
        }
        localStorage.setItem(spyColumnStorageName, JSON.stringify(columns_checked_array));
    }).change(function(value) {
            $('#column_id').trigger('update_columns');
    });
});
//-->
</script>
