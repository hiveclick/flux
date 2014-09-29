<?php
    /* @var $domain_group Flux\DomainGroup */
    $domain_group = $this->getContext()->getRequest()->getAttribute("domain_group", array());
?>
<div id="header">
   <h2><a href="/admin/domain-group-search">Domain Groups</a> <small><?php echo $domain_group->getName() ?></small></h2>
</div>
<div class="help-block">Create a new domain group that you can use to organize emails</div>
<br/>
<form class="form-horizontal" name="domain_group_form" method="POST" action="" autocomplete="off" role="form">
    <input type="hidden" name="_id" value="<?php echo $domain_group->getId() ?>" />
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
        <div class="col-sm-10">
            <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $domain_group->getName() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="description">Description</label>
        <div class="col-sm-10">
            <textarea name="description" id="description" class="form-control" placeholder="Enter Description..."><?php echo $domain_group->getDescription() ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
        <div class="col-sm-10">
            <select class="form-control" name="status" id="status" required placeholder="Status">
                <?php foreach(\Flux\DomainGroup::retrieveStatuses() AS $status_id => $status_name) { ?>
                <option value="<?php echo $status_id; ?>"<?php echo $domain_group->getStatus() == $status_id ? ' selected' : ''; ?>><?php echo $status_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="domains">Domains</label>
        <div class="col-sm-10">
            <input type="text" name="domains" id="domains" class="form-control" placeholder="Enter Domains..." value="<?php echo implode(",", $domain_group->getDomains()) ?>" />
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="__save" class="btn btn-success" value="Save" />
            <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Domain Group" />
        </div>
    </div>
</form>
<script>
//<!--
$(document).ready(function() {
    $('#domains').selectize({
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });

    $('#btn_delete').click(function() {
        if (confirm('Are you sure you want to delete this domain group and completely remove it from the system?')) {
            $.rad.del('/api', { func: '/admin/domain-group/<?php echo $domain_group->getId() ?>' }, function(data) {
                $.rad.notify('Domain Group Removed', 'This domain group has been removed from the system.');
            });
        }
    });
});
//-->
</script>