<?php
/**
 * Group Extender JS
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */
?>
//<script>
elgg.provide('elgg.groupextender');

elgg.groupextender.getCategoryGroupsURL = 'ajax/view/group-extender/category_groups';

// General init
elgg.groupextender.init = function() {
	// Register change handler for group select
	$('#groups-dashboard-group-select').change(elgg.groupextender.groupSelectChange);

	// Preset links
	$('.groupdashboard-preset-link').live('click', elgg.groupextender.presetClick);
	
	// Register click handler for group category hover menu items
	$(document).delegate('.group-category-add-hover-menu-item, .group-category-remove-hover-menu-item', 'click', elgg.groupextender.groupCategoryHoverClick);
}

// Change handler for group select 
elgg.groupextender.groupSelectChange = function(event) {
	window.location.hash = $(this).val();

	// Ajax view URL
	var url = elgg.normalize_url('ajax/view/group-extender/modules/groups');

	// Create querystring from select
	var params = $.param($(this).serializeArray());

	// Load it in
	$('#group-dashboard-groups-container').load(url + "?" + params, {
		'group_guids': $(this).val(),
	});
}

// Click handler for presets
elgg.groupextender.presetClick = function(event) {
	var guids = $(this).attr('href');
	var guids_array = guids.split(",");
	if (guids_array) {
		elgg.groupextender.setGroupSelectValues(guids_array);
	}
	event.preventDefault();
}

// Do stuff with the window hash
elgg.groupextender.handle_hash = function() {
	if (window.location.hash) {
		var valuesArray = window.location.hash.replace("#", "").split(",");

		if (valuesArray) {
			elgg.groupextender.setGroupSelectValues(valuesArray);
		}
	}
}

// Helper function to set the group select values, and trigger the update
elgg.groupextender.setGroupSelectValues = function(values) {
	$('#groups-dashboard-group-select').val(values).trigger('change');
}

// Categories module populated handler
elgg.groupextender.categories_populated_module = function(event, type, params, value) {
	var category_module = $('#groups-all-categories-ajaxmodule');
	
	if (category_module.length) {
		var count = 0;
		
		var $entity_list = category_module.find('ul.elgg-list-entity');
		
		var allgroups_item = "<li id='elgg-object-groups-all' class='elgg-item category-state-selected'><div class='elgg-image-block clearfix'><div class='elgg-body'><h3>" + elgg.echo('group-extender:label:allgroups') + "</h3><div class='elgg-subtext'></div></div></div></li>";
		
		if ($entity_list.length) {
			$entity_list.prepend(allgroups_item);
		} else {
			category_module.find('div.content').html("<ul>" + allgroups_item + "</ul>");
		}

		category_module.find('li.elgg-item').each(function() {
			// Extract guid from list item
			var id = $(this).attr('id');
			var guid = id.substring(id.lastIndexOf('-') + 1);
		
			$(this).bind('click', function(event) {
				if ($(event.target).parents(".elgg-menu-item-entity-actions").length == 0) {
					// Load groups
					elgg.groupextender.category_load_groups(guid);
			
					// Remove selected
					category_module.find('li.elgg-item').each(function() {
						$(this).removeClass('category-state-selected');
					});
			
					// Select this category
					$(this).addClass('category-state-selected');
				}
			});
			
			// Click first item
			if (count == 0) {
				$(this).trigger('click');
			}
			count++;
		});
	}
}

// Load group by category
elgg.groupextender.category_load_groups = function(category_guid) {
	// Spinner
	$('#groups-all-group-list').addClass('elgg-ajax-loader');
	$('#groups-all-group-list').html('');
	// Load
	elgg.get(elgg.groupextender.getCategoryGroupsURL, {
		data: {guid: category_guid}, 
		success: function(data) {
			$('#groups-all-group-list').removeClass('elgg-ajax-loader');
			$('#groups-all-group-list').html(data);
		},
	});
}

// Click handler for group category hover items
elgg.groupextender.groupCategoryHoverClick = function(event) {
	var action = '';
	var data = '';
	var old_class = '';
	var new_class = '';
	
	// HREF is in the format: #group_guid:category_guid
	var href = $(this).attr('href');
	var group_guid = href.substring(1, href.indexOf(':'));
	var category_guid = href.substring(href.indexOf(':') + 1);
	
	if ($(this).hasClass('group-category-add-hover-menu-item')) {
		action = elgg.get_site_url() + 'action/group_category/addgroup';
		data = {'members[]' : group_guid, category_guid : category_guid};
		new_class = 'group-category-remove-hover-menu-item';
		old_class = 'group-category-add-hover-menu-item';
		new_text = $(this).html().replace("Add to:", "Remove from:");
	} else if ($(this).hasClass('group-category-remove-hover-menu-item')) {
		action = elgg.get_site_url() + 'action/group_category/removegroup';
		field_name = 'group_guid';
		data = {group_guid : group_guid, category_guid : category_guid};
		new_class = 'group-category-add-hover-menu-item';
		old_class = 'group-category-remove-hover-menu-item';
		new_text = $(this).html().replace("Remove from:", "Add to:");
	}
	
	var $_this = $(this);

	// Add/remove the group
	elgg.action(action, {
		data: data,
		success: function(json) {
			// Replace link 
			if (json.status >= 0) {
				// Move link to add/remove section
				$_this.closest('.elgg-menu-hover-admin')
					.find("." + new_class + ":last")
					.parent()
					.after($_this.parent());
				
				// Replace classes and update text
				$_this.removeClass(old_class).addClass(new_class).html(new_text);
			}
		}
	});

	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.groupextender.init);
elgg.register_hook_handler('populated', 'modules', elgg.groupextender.categories_populated_module);