<legend>Flux - Please Sign In</legend>
<?php foreach ($this->getErrors()->getErrors() as $error) { ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#">x</a><?php echo $error->getMessage() ?>
    </div>
<?php } ?>
<form name="login_form" method="POST" action="/login" autocomplete="off" class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="name">Email</label>
        <div class="col-sm-10">
            <input type="email" id="email" class="form-control" name="email" required placeholder="Email" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="name">Password</label>
        <div class="col-sm-10">
            <input type="password" id="password" class="form-control" name="password" required placeholder="Password" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="submit" class="btn btn-lg btn-info" value="Sign In" />
        </div>
    </div>
</form>