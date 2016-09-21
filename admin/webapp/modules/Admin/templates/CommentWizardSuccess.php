<?php
	/* @var $comment Flux\Comment */
	$comment = $this->getContext()->getRequest()->getAttribute("comment", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($comment->getId()) ? 'Edit' : 'Add' ?> Comment</h4>
</div>
<form class="" id="comment_form_<?php echo $comment->getId() ?>" method="<?php echo \MongoId::isValid($comment->getId()) ? 'PUT' : 'POST' ?>" action="/admin/comment" autocomplete="off" role="form">
	<?php if (\MongoId::isValid($comment->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $comment->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<div class="help-block">Enter the comment details so you can use it to build SEO backlinks</div>
		<div class="form-group">
			<label class="control-label" for="comment">Comment</label>
			<textarea name="comment" id="comment" rows="7" class="form-control" placeholder="Enter Comment..."><?php echo $comment->getComment() ?></textarea>
		</div>
		<div class="form-group">
			<input type="hidden" name="is_multicomment" value="0" />
			<label><input type="checkbox" name="is_multicomment" value="1" /> Split the comments by lines and insert multiple comments</label>
		</div>
		<div class="help-block">
			You can inject placeholders that will be used within the comment.
			<table class="table table-responsive">
				<tr>
					<td><code>[keyword]</code></td>
					<td>SEO Keyword that you want to promote</td>
				</tr>
				<tr>
					<td><code>[url]</code></td>
					<td>URL to the site you want to promote</td>
				</tr>
				<tr>
					<td><code>[anchor]</code></td>
					<td>Full anchor including the keyword and url.  Shortcut for &lt;a href="<code>[url]</code>"&gt;<code>[keyword]</code>&lt;/a&gt;</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($comment->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete Comment" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#comment_form_<?php echo $comment->getId() ?>').form(function(data) {
		$.rad.notify('Comment Updated', 'The comment has been added/updated in the system');
		$('#comment_search_form').trigger('submit');
		$('#edit_comment_modal').modal('hide');
	}, {keep_form:1});
});

<?php if (\MongoId::isValid($comment->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this comment from the system?')) {
		$.rad.del('/admin/comment/<?php echo $comment->getId() ?>', { }, function() {
			$.rad.notify('You have deleted this comment', 'You have deleted this comment.  You will need to refresh this page to see your changes.');
			$('#comment_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>