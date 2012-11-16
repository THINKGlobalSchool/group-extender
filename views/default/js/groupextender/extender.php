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

elgg.groupextender.getCategoryGroupsURL = 'ajax/view/group-extender/modules/category_groups';

// General init
elgg.groupextender.init = function() {
	// Register change handler for group select
	$('#groups-dashboard-group-select').change(elgg.groupextender.groupSelectChange);

	// Preset links
	$('.groupdashboard-preset-link').live('click', elgg.groupextender.presetClick);
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
	});
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


elgg.register_hook_handler('init', 'system', elgg.groupextender.init);
elgg.register_hook_handler('generic_populated', 'modules', elgg.groupextender.categories_populated_module);