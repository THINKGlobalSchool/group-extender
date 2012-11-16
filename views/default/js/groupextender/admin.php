<?php
/**
 * Group Extender Admin JS
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */
?>
//<script>
elgg.provide('elgg.groupextender.admin');

elgg.groupextender.admin.getGroupsURL = 'ajax/view/group-extender/admin/category_groups';

// General init
elgg.groupextender.admin.init = function() {
	// Click event for remove button
	$('a.remove-from-category').live('click', elgg.groupextender.admin.remove_group);
	
	// Click event for add button
	$('.add-to-category').live('click', elgg.groupextender.admin.add_group);
}

elgg.groupextender.admin.populated_module = function(event, type, params, value) {
	var category_module = $('#category-list');
	category_module.find('li.elgg-item').each(function() {
		// Extract guid from list item
		var id = $(this).attr('id');
		var guid = id.substring(id.lastIndexOf('-') + 1);
		
		$(this).bind('click', function(event) {
			if ($(event.target).parents(".elgg-menu-item-entity-actions").length == 0) {
				// Load groups
				elgg.groupextender.admin.load_groups(guid);
			
				// Remove selected
				category_module.find('li.elgg-item').each(function() {
					$(this).removeClass('category-state-selected');
				});
			
				// Select this category
				$(this).addClass('category-state-selected');
			}
		});
	});
}

// Load group by category
elgg.groupextender.admin.load_groups = function(category_guid) {
	// Spinner
	$('#group-list').addClass('elgg-ajax-loader');
	$('#group-list').html('');
	// Load
	elgg.get(elgg.groupextender.admin.getGroupsURL, {
		data: {guid: category_guid}, 
		success: function(data) {
			$('#group-list').removeClass('elgg-ajax-loader');
			$('#group-list').html(data);
		},
	});
}

// Click handler for remove links
elgg.groupextender.admin.remove_group = function(event) {
	var confirmText = $(this).attr('rel') || elgg.echo('question:areyousure');
	if (confirm(confirmText)) {
		// Grab group ID and category ID
		var group_guid = $(this).attr('id');
		var category_guid = $(this).attr('name');
		var _this = $(this);

		elgg.action('group_category/removegroup', {
			data: {
				group_guid: group_guid,
				category_guid: category_guid
			},
			success: function(data) {
				if (data.status == -1) {
					//console.log('error: ' + data.system_messages.error);
				} else {
					// Remove element from DOM
					_this.closest('div.elgg-image-block').fadeOut('slow');
				}
			}
		});
	} 
	event.preventDefault();
}

// Click handler for remove links
elgg.groupextender.admin.add_group = function(event) {
	var data = $('#group-category-add-group-form').serialize();
	var category_guid = $('#group-category-add-group-form input[name=category_guid]').val();

	elgg.action('group_category/addgroup', {
		data: data,
		success: function(data) {
			if (data.status == -1) {
				//console.log('error: ' + data.system_messages.error);
			} else {
				elgg.groupextender.admin.load_groups(category_guid);
			}
		}
	});
	
	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.groupextender.admin.init);
elgg.register_hook_handler('populated', 'modules', elgg.groupextender.admin.populated_module);