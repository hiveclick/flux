<?php 
	/* @var $offer_page_path \Flux\OfferPagePath */
	$offer_page_path = $this->getContext()->getRequest()->getAttribute('offer_page_path', array());
	$offers = $this->getContext()->getRequest()->getAttribute('offers', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Manage Offer Asset</h4>
</div>
<form id="offer_page_path_offer_modal_form" method="POST" action="">
	<input type="hidden" id="offer_page_path_offer_modal_rule_position" name="offer_page_paths[<?php echo $offer_page_path->getPosition() ?>][position]" value="<?php echo $offer_page_path->getPosition() ?>" />
	<div class="modal-body">
		<div class="form-group">
			<label>
				Select an offer
			</label>
			<select id="offer_page_path_offer_modal_<?php echo $offer_page_path->getPosition() ?>_offer_id" name="offer_page_paths[<?php echo $offer_page_path->getPosition() ?>][offer_id]" class="span2">
				<?php 
					/* @var $offer \Flux\Offer */
					foreach ($offers as $offer) {
				?>
					<option value="<?php echo $offer->getId() ?>"><?php echo $offer->getName() ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading"><h3 class="panel-title">Choose the asset to display:</h3></div>
			<div class="panel-body" id="destination_page_local">
				<select id="offer_page_path_offer_modal_<?php echo $offer_page_path->getPosition() ?>_offer_asset_id" name="offer_page_paths[<?php echo $offer_page_path->getPosition() ?>][offer_asset_id]">
				</select>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$selectize_options = {
		valueField: '_id',
		labelField: 'name',
		searchField: ['name', 'description'],
		render: {
			item: function(item, escape) {
				return '<div>' +
					'<div class="media">' +
					'<span class="pull-left thumbnail">' + 
					(item.image_data ? '<img width="75" src="data:image/png;base64,' + escape(item.image_data) + '" border="0" />' : '<img src="/images/no_preview.png" border="0" />') +
			  	   '</span>' + 
			  		'<div class="media-body">' +
					'<div class="title">' + 
					'<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
					'</div>' +
					'<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
					'</div>' +
					'</div>' + 
					'</div>';
			},
			option: function(item, escape) {
				return '<div>' +
					'<div class="media">' +
					'<span class="pull-left thumbnail">' + 
			 		(item.image_data ? '<img width="75" src="data:image/png;base64,' + escape(item.image_data) + '" border="0" />' : '<img src="/images/no_preview.png" border="0" />') +
			  		'</span>' + 
			  		'<div class="media-body">' +
					'<div class="title">' + 
					'<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
					'</div>' +
					'<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
					'</div>' +
					'</div>' + 
					'</div>';
			}
		}
	};

	var $asset_selectize = $('#offer_page_path_offer_modal_<?php echo $offer_page_path->getPosition() ?>_offer_asset_id').selectize($selectize_options);
	
	$('#offer_page_path_offer_modal_<?php echo $offer_page_path->getPosition() ?>_offer_id').selectize().on('change', function() {
		$.rad.get('/api', { func: '/offer/offer-asset', offer_id: $(this).val() }, function(data) {
			$('#offer_page_path_offer_modal_<?php echo $offer_page_path->getPosition() ?>_offer_asset_id').html('');
			if (data.entries) {
				$.each(data.entries, function(i, item) {
					var option = $('<option />').val(item._id).html(item.name).attr('data-data', '{"name": "' + item.name + '", "description":"' + item.description + '", "image_data":"' + item.image_data + '"}');
					$('#offer_page_path_offer_modal_<?php echo $offer_page_path->getPosition() ?>_offer_asset_id').append(option);
				});
			}
			$asset_selectize[0].selectize.refreshOptions();

		});
	});

	
	

	/* Handle a form submit by converting it to a text representative and hidden input fields on the main page */
	$('#offer_page_path_offer_modal_form').on('submit', function(event) {
		var position = '<?php echo $offer_page_path->getPosition() ?>';
		var input_html = $('<div></div>');
		var nav_text = '';
		// Generate the setter text
		$.each($(this).find(':input'), function(i, item) {
			input_html.append($('<input type="hidden" />').attr('name', $(item).attr('name')).attr('value', $(item).val()));
			if ($(item).hasClass('navigation_type')) {
				if ($(item).val() == '1') {
					nav_text += 'Redirect to another page in this offer:<p />';
				} else if ($(item).val() == '2') {
					nav_text += 'Redirect to an external url:<p />';
				}
			} else if ($(item).hasClass('offer_page_flow_navigation')) {
				if ($('#navigation_type').val() == '1') {
					nav_text += '<div><div class="media">';
					nav_text += $(item)[0].selectize.getItem($(item)[0].selectize.getValue()).find('.media').html()
					nav_text += '</div></div>';
				}
			} else if ($(item).hasClass('offer_page_flow_navigation_remote_url')) {
				if ($('#navigation_type').val() == '2') {
					nav_text += '<div><div class="media"><span class="pull-left thumbnail">';
					nav_text += '<img width="128" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=150x150&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=' + $(item).val() + '" border="0" />';
					nav_text += '</span></div><div class="media-body">';
					nav_text += '<div style="word-break: break-all;" class="small">' + $(item).val() + '</div>';
					nav_text += '</div></div>';					
				}
			}
		});
		// Save the hidden form elements into the setter div on the main page
		$('.offer_page_path_navigation_div-' + position).html(input_html.html() + nav_text);
		// Serialize this form and change the add/modify setter button to pass the serialized values
		$('.add_navigation_btn-' + position).attr('href', '/offer/offer-page-pane-path-offer-modal?' + $(this).serialize() + '&offer_page_id=<?php echo $offer_page_path->getOfferPageId() ?>');
		// Hide the modal
		$('#flow_navigation_modal').modal('hide');
		$('#changes_alert').show();
		event.preventDefault();
	});
});
//-->
</script>