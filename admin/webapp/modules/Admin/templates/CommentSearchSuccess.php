<?php
	/* @var $comment \Flux\Comment */
	$comment = $this->getContext()->getRequest()->getAttribute("comment", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/admin/comment-search">Comments</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a data-toggle="modal" data-target="#edit_comment_modal" href="/admin/comment-wizard" class="btn btn-success"><span class="fa fa-plus"></span> Add New Comment</a>
		</div>
	   <h1>Comments</h1>
	</div>
	<div class="help-block">These random comments are used to post comments on forums and blogs.  They are also used for pingbacks.</div>
	<div class="panel panel-primary">
		<div id='comment-header' class='grid-header panel-heading clearfix'>
			<form id="comment_search_form" method="GET" action="/admin/comment">
				<input type="hidden" name="format" value="json" />
				<input type="hidden" id="page" name="page" value="1" />
				<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
				<input type="hidden" id="sort" name="sort" value="name" />
				<input type="hidden" id="sord" name="sord" value="asc" />
				<div class="pull-right">
					<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="name" value="" />
				</div>
			</form>
		</div>
		<div id="comment-grid"></div>
		<div id="comment-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit domain group modal -->
<div class="modal fade" id="edit_comment_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'comment', name:'comment', field:'comment', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<a data-toggle="modal" data-target="#edit_comment_modal" href="/admin/comment-wizard?_id=' + dataContext._id + '">' + value + '</a>';
		}}
	];

  	slick_grid = $('#comment-grid').slickGrid({
		pager: $('#comment-pager'),
		form: $('#comment_search_form'),
		columns: columns,
		useFilter: false,
		cookie: '<?php echo $_SERVER['PHP_SELF'] ?>',
		pagingOptions: {
			pageSize: <?php echo \Flux\Preferences::getPreference('items_per_page', 25) ?>,
			pageNum: 1
		},
		slickOptions: {
			defaultColumnWidth: 150,
			forceFitColumns: true,
			enableCellNavigation: false,
			width: 800,
			rowHeight: 48
		}
	});

	$("#txtSearch").keyup(function(e) {
   		// clear on Esc
   		if (e.which == 27) {
   			this.value = "";
   		} else if (e.which == 13) {
   			$('#comment_search_form').trigger('submit');
   		}
   	});
		  	
	$('#comment_search_form').trigger('submit');

	$('#edit_comment_modal').on('hide.bs.modal', function(e) {
 		$(this).removeData('bs.modal');
	});
});
//-->
</script>