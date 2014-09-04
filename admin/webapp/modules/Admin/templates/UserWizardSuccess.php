<?php
    /* @var $user Gun\User */
    $user = $this->getContext()->getRequest()->getAttribute("user", array());
    $clients = $this->getContext()->getRequest()->getAttribute("clients", array());
?>
<div id="header">
   <h2><a href="/admin/user-search">Users</a> <small>New User</small></h2>
</div>
<div class="help-block">Create a new user with access to log into the system</div>
<br/>
<form class="form-horizontal" name="user_form" method="POST" action="" autocomplete="off">
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
        <div class="col-sm-10">
            <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $user->getName() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
        <div class="col-sm-10">
            <select class="form-control" name="status" id="status" required placeholder="Status">
                <?php foreach(\Gun\User::retrieveStatuses() AS $status_id => $status_name) { ?>
                <option value="<?php echo $status_id; ?>"<?php echo $user->getStatus() == $status_id ? ' selected="selected"' : ''; ?>><?php echo $status_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="type">Type</label>
        <div class="col-sm-10">
            <select class="form-control" name="user_type" id="type" required placeholder="Type">
                <?php foreach(\Gun\User::retrieveUserTypes() AS $type_id => $type_name) { ?>
                <option value="<?php echo $type_id; ?>"<?php echo $user->getUserType() == $type_id ? ' selected="selected"' : ''; ?>><?php echo $type_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="client_id">Client</label>
        <div class="col-sm-10">
            <select class="form-control" name="client_id" id="client_id" required placeholder="Client">
                <?php foreach($clients AS $client_id => $client) { ?>
                <option value="<?php echo $client_id; ?>"<?php echo $user->retrieveValue('client_id') == $client->retrieveValue('_id') ? ' selected="selected"' : ''; ?>><?php echo $client->retrieveValue('name'); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="email">Email</label>
        <div class="col-sm-10">
            <input type="email" id="email" name="email" class="form-control" required placeholder="Email" value="<?php echo $user->getEmail() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="password">Password</label>
        <div class="col-sm-10">
            <input type="text" id="password" name="password" class="form-control" required placeholder="Password" value="<?php echo $user->getPassword() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="type">Timezone</label>
        <div class="col-sm-10">
            <select class="form-control" name="timezone" id="timezone" required placeholder="Timezone">
                <?php foreach(\Gun\Timezone::retrieveTimezonesFormatted() AS $timezone_id => $timezone_string) { ?>
                <option value="<?php echo $timezone_id; ?>"<?php echo $user->getTimezone() == $timezone_id ? ' selected="selected"' : ''; ?>><?php echo $timezone_string; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="__save" class="btn btn-success" value="Save User" />
        </div>
    </div>

</form>