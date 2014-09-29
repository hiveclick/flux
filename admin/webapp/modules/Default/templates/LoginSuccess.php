<center>
	<legend>
	<img class="thumbnail" style="width:128px;" src="/images/logo.png" border="0" />
		Flux - Please Sign In
	</legend>
</center>
<?php foreach ($this->getErrors()->getErrors() as $error) { ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#">x</a><?php echo $error->getMessage() ?>
    </div>
<?php } ?>
<form name="login_form" method="POST" action="/login" class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="name">Email</label>
        <div class="col-sm-10">
            <input type="username" id="username" class="form-control" name="username" required placeholder="Email" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="name">Password</label>
        <div class="col-sm-10">
            <input type="password" id="password" class="form-control" name="password" required placeholder="Password" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-5 col-sm-3">
            <input type="submit" name="submit" class="btn btn-lg btn-info" value="Sign In" />
        </div>
    </div>
</form>