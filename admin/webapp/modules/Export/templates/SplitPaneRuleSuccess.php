<?php
    $split = $this->getContext()->getRequest()->getAttribute("split", array());
    $exports = $this->getContext()->getRequest()->getAttribute("exports", array());
?>
<div class="help-block">Rules define how data is collected and which exports should receive the data</div>
<br/>
<form name="split_form" method="POST" class="form-horizontal" autocomplete="off">
    <input type="hidden" name="_id" value="<?php echo $split->getId() ?>" />
    <div id="flow_master_container">



    </div>
    <div class="form-group">
        <div class="col-xs-12">
            <input type="submit" name="__saveSplitNodes" class="btn btn-success" value="Save Rules" />
        </div>
    </div>
</form>
<div style="display:none;" id="dummy_form_group">
    <div class="flow-node">
        <div class="form-group">
            <div class="col-xs-4">
                <input type="hidden" name="flow_nodes[][guid]" class="input_flow_nodes_guid" value="" />
                <input type="hidden" name="flow_nodes[][ref]" class="input_flow_nodes_ref" value="" />
                <input type="hidden" name="flow_nodes[][type]" class="input_flow_nodes_type" value="<?php echo \Gun\Split::SPLIT_NODE_TYPE_RULE_FIRST; ?>" />
                <input type="hidden" name="flow_nodes[][active]" class="input_flow_nodes_active" value="1" />
                <div class="input-group flow-node-type">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon"></span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu node-type-ul" role="menu">
                            <li><a href="#" data-node-type="<?php echo \Gun\Split::SPLIT_NODE_TYPE_RULE; ?>" data-glyphicon-type="glyphicon glyphicon-align-left">Rule</a></li>
                            <li><a href="#" data-node-type="<?php echo \Gun\Split::SPLIT_NODE_TYPE_EXPORT; ?>" data-glyphicon-type="glyphicon glyphicon-import">Export</a></li>
                        </ul>
                    </div>
                    <input type="text" name="flow_nodes[][label]" class="form-control input_flow_nodes_label" placeholder="" value="" />
                </div>
            </div>
            <div class="col-xs-4">
                <div class="input-group-btn-bag">
                    <select name="flow_nodes[][export_id]" class="form-control row_offer_type_<?php echo \Gun\Split::SPLIT_NODE_TYPE_EXPORT; ?>" style="display:none;">
                        <?php foreach($exports AS $export) { ?>
                            <option value="<?php echo $export->retrieveValueHtml('_id'); ?>"><?php echo $export->retrieveValueHtml('name'); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-4">
                <div  class="btn-group" data-toggle="buttons">
                    <button type="button" class="btn btn-info btn-options" data-toggle="button">Options</button>
                    <button type="button" class="btn btn-info btn-filters" data-toggle="button">Filters</button>
                    <div class="btn-group btn-group-actions">
                        <button type="button" class="btn btn-primary dropdown-toggle btn-actions" data-toggle="dropdown">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu action-type-ul" role="menu">
                            <li><a href="#" data-action-type="1">Add sibling before</a></li>
                            <li><a href="#" data-action-type="2">Add sibling after</a></li>
                            <li class="divider"></li>
                            <li><a href="#" data-action-type="4">Inactivate</a></li>
                            <li class="divider"></li>
                            <li><a href="#" data-action-type="3">Remove</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-container">
            <div class="panel panel-info collapse collapse-filters">
                <div class="panel-heading">
                    <h3 class="panel-title">Filters</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-primary btn-add-filter">Add Filter</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-info collapse collapse-options">
                <div class="panel-heading">
                    <h3 class="panel-title">Options</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group form-group-exclusive">
                        <div class="col-xs-12">
                            <label class="btn btn-default btn-exclusive">
                                <input type="hidden" name="flow_nodes[][exclusive]" value="0" /><span class="glyphicon glyphicon-forward"></span> <span class="btn-text">Not Exclusive</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group form-group-cap">
                        <div class="col-xs-4">
                            <div class="input-group input-group-cap">
                                <input type="number" min="0" name="flow_nodes[][cap]" class="form-control cap-toggle" value="" placeholder="Amount" />
                                <span class="input-group-addon">per</span>
                            </div>
                            <span class="label label-success label-cap-on">Cap On</span>
                            <span class="label label-success label-cap-count"></span>
                        </div>
                        <div class="col-xs-4" style="vertical-align:bottom;">
                            <input type="number" min="0" name="flow_nodes[][cap_time_amount]" class="form-control" value="" placeholder="" />
                        </div>
                        <div class="col-xs-4">
                            <select name="flow_nodes[][cap_time]" class="form-control">
                                <?php foreach(\Gun\Flow::retrieveCapTimes() AS $cap_time_id => $cap_time) { ?>
                                    <option value="<?php echo $cap_time_id; ?>"><?php echo $cap_time['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-weight">
                        <div class="col-xs-12">
                            <input type="number" min="0" name="flow_nodes[][weight]" class="form-control weight-toggle" value="" placeholder="Weight" />
                            <span class="label label-success label-weight-on">
                            Weighted
                            </span>
                            <span class="label label-success label-weight-count" style="display:none;">
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
//<!--
function generateGUID() {
    var guid = 'xxxxxxxxxxxx4xxxyxxxxxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
        return v.toString(16);
    });
    return guid;
}

$(function() {
    $('#flow_master_container').on('redraw_table', function (e) {
        e.preventDefault();
        var $flow_master_container = $(this);

        var $flow_nodes = $flow_master_container.find('.flow-node');
        if($flow_nodes.size() <= 0) {
            $('#flow_master_container').trigger('add_default_node');
            //somehow the last node got deleted, which should be prevented, so add it
        }

        //get the flow nodes again, just in case we just added one in the above code
        $flow_nodes = $flow_master_container.find('.flow-node');
        $flow_nodes.each(function(idx, flow_node) {
            var $flow_node = $(flow_node);
            /*
            if(idx == 0) {
                $flow_node.find('.btn-actions').addClass('disabled');
                $flow_node.find('.btn-group-actions').hide();
                $flow_node.find('.form-group-weight').hide();
            }
            */
            //$flow_node.trigger('redraw_node');
        });
        //updateRefValues();
    });

    $('#flow_master_container').on('add_default_node', function(e) {
        e.preventDefault();
        var guid = generateGUID();

        var $new_node = $('#dummy_form_group div:first').clone(true);

        var node_ref = '0';

        $new_node.find('.input_flow_nodes_ref').val(node_ref);
        $new_node.find('.input_flow_nodes_guid').val(guid);
        $new_node.find('.input_flow_nodes_type').val('<?php echo json_encode(\Gun\Split::SPLIT_NODE_TYPE_EXPORT); ?>');
        $(this).append($new_node);
        //$('#flow_master_container').trigger('redraw_table');
    });

    $('#flow_master_container').trigger('redraw_table');
});
//-->
</script>
