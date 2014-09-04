<?php
    /* @var $domain_group Gun\DomainGroup */
    $domain_group = $this->getContext()->getRequest()->getAttribute("domain_group", array());
?>
<div id="header">
   <h2><a href="/admin/domain-group-search">Domain Groups</a> <small>New Domain Group</small></h2>
</div>
<div class="help-block">Create a new domain group that you can use to organize emails</div>
<br/>
<form class="form-horizontal" name="domain_group_form" method="POST" action="" autocomplete="off" role="form">
    <input type="hidden" name="status" value="<?php echo \Gun\DomainGroup::DOMAIN_GROUP_STATUS_ACTIVE ?>" />
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
        <label class="col-sm-2 control-label hidden-xs" for="domains">Domains</label>
        <div class="col-sm-10">
            <input type="text" name="domains" id="domains" class="form-control" placeholder="Enter Domains..." value="<?php echo implode(",", $domain_group->getDomains()) ?>" />
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="__save" class="btn btn-success" value="Save" />
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
});
//-->
</script>