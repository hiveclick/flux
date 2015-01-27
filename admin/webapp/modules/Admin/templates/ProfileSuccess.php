<?php
	/* @var $user Flux\User */
	$user = $this->getContext()->getRequest()->getAttribute("user", array());
?>
<div id="header">
	<div class="pull-right visible-xs">
		<button class="navbar-toggle collapsed visible-xs" type="button" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<h2><a href="/admin/user-search">Profile</a> <small><?php echo $user->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
	<ul id="user_tabs" class="nav nav-pills">
		<li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">My Profile</a></li>
		<li><a id="tabs-a-report" href="#tabs-reports" data-toggle="tab">My Saved Reports</a></li>
	</ul>
</div>
<div id="tab-content-container" class="tab-content">
	<div id="tabs-main" class="tab-pane active">
		<div class="help-block">Change your profile settings</div>
		<br />
		<form class="form-horizontal" name="user_form" method="POST" action="" autocomplete="off" enctype="multipart/form-data">
			<input type="hidden" name="_id" value="<?php echo $user->getId() ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
				<div class="col-sm-10">
					<input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $user->getName() ?>" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label hidden-xs" for="type">Timezone</label>
				<div class="col-sm-10">
					<select class="form-control" name="timezone" id="timezone" placeholder="Timezone">
						<?php foreach(\Flux\Timezone::retrieveTimezonesFormatted() AS $timezone_id => $timezone_string) { ?>
						<option value="<?php echo $timezone_id; ?>"<?php echo $user->getTimezone() == $timezone_id ? ' selected="selected"' : ''; ?>><?php echo $timezone_string; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<hr />

			<div class="form-group">
				<label class="col-sm-2 control-label hidden-xs" for="email">Email</label>
				<div class="col-sm-10">
					<input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?php echo $user->getEmail() ?>" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label hidden-xs" for="password">Password</label>
				<div class="col-sm-10">
					<input type="text" id="password" name="password" class="form-control" placeholder="Password" value="<?php echo $user->getPassword() ?>" />
				</div>
			</div>

			<hr />

			<div class="form-group">
				<label class="col-sm-2 control-label hidden-xs" for="image_data">Profile Image</label>
				<div class="col-sm-10">
					<input type="file" id="image_data" name="image_data" class="form-control" placeholder="Upload image" />
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" name="__save" class="btn btn-success" value="Save User" />
					<input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete User" />
				</div>
			</div>
		</form>
	</div>
	<div id="tabs-reports" class="tab-pane">
		<br/>
		<form class="form-horizontal" name="user_form" method="POST" action="" autocomplete="off">
			<input type="hidden" name="_id" value="<?php echo $user->getId() ?>" />
			<div class="form-group">
			</div>
		</form>
	</div>
</div>

<script>
//<!--
$(document).ready(function() {
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		e.preventDefault();
		var hash = this.hash;
		if ($(this).attr("data-url")) {
			// only load the page the first time
			if ($(hash).html() == '') {
				// ajax load from data-url
				$(hash).load($(this).attr("data-url"));
			}
		}
	}).on('show.bs.tab', function (e) {
		try {
			sessionStorage.setItem(localTabStorageName, $(e.target).attr('href'));
		} catch (err) { }
	});

	$('#btn_delete').click(function() {
		if (confirm('Are you sure you want to delete this user and completely remove it from the system?')) {
			$.rad.del('/api', { func: '/admin/user/<?php echo $user->getId() ?>' }, function(data) {
				$.rad.notify('User Removed', 'This user has been removed from the system.');
			});
		}
	});

	// Store the last clicked tab so it can be loaded on page refreshes
	var localTabStorageName = <?php echo json_encode('user_tab_' . $user->getId()); ?>;
	var lastTab = sessionStorage.getItem(localTabStorageName);
	if (lastTab) {
		$('a[href='+lastTab+']').tab('show');
	} else {
		$('ul.nav-pills a:first').tab('show');
	}
});
//-->
</script>