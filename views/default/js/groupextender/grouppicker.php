<?php
/**
 * Group Extender Group Picker JS
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.com/
 */
?>
//<script>
elgg.provide('elgg.grouppicker');

/**
 * Grouppicker initialization
 *
 * The grouppicker is an autocomplete library for selecting multiple groups
 * It works in concert with the view input/grouppicker.
 *
 * @return void
 */
elgg.grouppicker.init = function() {
	// binding autocomplete.
	// doing this as an each so we can pass this to functions.
	$('.elgg-input-group-picker').each(function() {

		$(this).autocomplete({
			source: function(request, response) {

				var params = elgg.grouppicker.getSearchParams(this);
				
				elgg.get('livesearch', {
					data: params,
					dataType: 'json',
					success: function(data) {
						response(data);
					}
				});
			},
			minLength: 2,
			html: "html",
			select: elgg.grouppicker.addGroup,
			messages: {
				noResults: '',
				results: function() {}
			}
		})
	});

	$('.elgg-input-group-picker').live('click', elgg.grouppicker.removeGroup);
};

/**
 * Adds a group to the select group list
 *
 * elgg.grouppicker.groupList is defined in the input/grouppicker view
 *
 * @param {Object} event
 * @param {Object} ui    The object returned by the autocomplete endpoint
 * @return void
 */
elgg.grouppicker.addGroup = function(event, ui) {
	var info = ui.item;

	// do not allow groups to be added multiple times
	if (!(info.guid in elgg.grouppicker.groupList)) {
		elgg.grouppicker.groupList[info.guid] = true;
		var groups = $(this).siblings('.elgg-group-picker-list');
		var li = '<input type="hidden" name="members[]" value="' + info.guid + '" />';
		li += elgg.grouppicker.viewGroup(info);
		$('<li>').html(li).appendTo(groups);
	}

	$(this).val('');
	event.preventDefault();
};

/**
 * Remove a group from the selected group list
 *
 * @param {Object} event
 * @return void
 */
elgg.grouppicker.removeGroup = function(event) {
	var item = $(this).closest('.elgg-group-picker-list > li');
	
	var guid = item.find('[name="members[]"]').val();
	delete elgg.grouppicker.groupList[guid];

	item.remove();
	event.preventDefault();
};

/**
 * Render the list item for insertion into the selected group list
 *
 * The html in this method has to remain synced with the input/grouppicker view
 *
 * @param {Object} info  The object returned by the autocomplete endpoint
 * @return string
 */
elgg.grouppicker.viewGroup = function(info) {

	var deleteLink = "<a href='#' class='elgg-input-group-picker'>X</a>";

	var html = "<div class='elgg-image-block'>";
	html += "<div class='elgg-image'>" + info.icon + "</div>";
	html += "<div class='elgg-image-alt'>" + deleteLink + "</div>";
	html += "<div class='elgg-body'>" + info.name + "</div>";
	html += "</div>";
	
	return html;
};

/**
 * Get the parameters to use for autocomplete
 *
 * This grabs the value of the friends checkbox.
 *
 * @param {Object} obj  Object for the autocomplete callback
 * @return Object
 */
elgg.grouppicker.getSearchParams = function(obj) {
	if (obj.element.siblings('[name=match_on]').attr('checked')) {
		return {'match_on[]': 'friends', 'term' : obj.term};
	} else {
		return {'match_on[]': 'groups', 'term' : obj.term};
	}
};

elgg.register_hook_handler('init', 'system', elgg.grouppicker.init);