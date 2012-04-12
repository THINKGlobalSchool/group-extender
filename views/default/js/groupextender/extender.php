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
elgg.provide('elgg.groupextender.tabs');

// General init
elgg.groupextender.init = function() {
	// Change handler for group navigator
	$(document).delegate('#group-navigator-select', 'change', elgg.groupextender.switchGroup);
}

// Change handler for group navigator select
elgg.groupextender.switchGroup = function(event) {
	window.location = $(this).val();
	event.preventDefault();
}

// Tabs init
elgg.groupextender.tabs.init = function() {
	// Click handler for custom group tabs
	$(document).delegate('.group-extender-tab-menu-item', 'click', elgg.groupextender.tabs.customTabClick);

	// Click handler for subtype tab save
	$(document).delegate('#group-extender-save-subtype-submit', 'click', elgg.groupextender.tabs.subtypeSaveClick);

	// Click handler for dashboard tab save
	$(document).delegate('#group-extender-save-dashboard-submit', 'click', elgg.groupextender.tabs.dashboardSaveClick);

	// Click handler for static tab save
	$(document).delegate('#group-extender-save-static-submit', 'click', elgg.groupextender.tabs.staticSaveClick);
	
	// Click handler for activity tab save
	$(document).delegate('#group-extender-save-activity-submit', 'click', elgg.groupextender.tabs.activitySaveClick);
	
	// Click handler for customsearch tab save
	$(document).delegate('#group-extender-save-customsearch-submit', 'click', elgg.groupextender.tabs.customsearchSaveClick);
	
	// Click handler for move up/down links
	$(document).delegate('.group-extender-move-link', 'click', elgg.groupextender.tabs.moveLinkClick);

	// Set up submission dialog
	$(".group-extender-lightbox").fancybox({
		'onComplete': function() {
			// Fix tinymce control
			if (typeof(tinyMCE) !== 'undefined') {
				tinyMCE.EditorManager.execCommand('mceAddControl', false, 'static-content');
			}
		},
		'onCleanup': function() {
			// Fix tinymce control
			if (typeof(tinyMCE) !== 'undefined') {
	    		tinyMCE.EditorManager.execCommand('mceRemoveControl', false, 'static-content');
			}
		}
	});
}

// Teardown function
elgg.groupextender.tabs.destroy = function() {
	// Undelegate events
	$(document).undelegate('.group-extender-tab-menu-item', 'click');
	$(document).undelegate('#group-extender-save-subtype-submit', 'click');
	$(document).undelegate('#group-extender-save-dashboard-submit', 'click');
	$(document).undelegate('#group-extender-save-static-submit', 'click');
	$(document).undelegate('#group-extender-save-activity-submit', 'click');
	$(document).undelegate('#group-extender-save-customsearch-submit', 'click');
	$(document).undelegate('.group-extender-move-link', 'click');
}

// Click handler for group custom tabs
elgg.groupextender.tabs.customTabClick = function(event) {
	$('.group-extender-tab-menu-item').parent().removeClass('elgg-state-selected');
	$(this).parent().addClass('elgg-state-selected');

	$('.group-extender-tab-content-container').hide();
	$($(this).attr('href')).show();
	
	// Trigger a hook to watch for tab changes
	var params = {
		target_id: $(this).attr('href'),
		source: $(this)
	};
	
	elgg.trigger_hook('groupextender', 'tab_clicked', params);
	
	event.preventDefault();
}

// Hook into the tab_clicked hook and perform extra processing for custom search tabs
elgg.groupextender.tabs.customSearchTabClicked = function(hook, type, params, options) {
	// @TODO this will change when we have multple searches (probably)
	if ($(params.target_id).find('.googlesearch-module').length) {
		// If we have a googlesearch module, fire the description positioner.. this is hacky..
		$('.googlesearch-module').resize(function(event) {
			if ($('.googlesearch-desc').is(':visible')) {
				$('.googlesearch-desc').hide();
			}
		});
	}
}

// Click handler for subtype tab save
elgg.groupextender.tabs.subtypeSaveClick = function(event) {
	// Get form inputs
	var $inputs = $("#group-extender-edit-subtype-form :input");

	var values = {};
	$inputs.each(function() {
		values[this.name] = $(this).val();
	});
	
	var params = {};
	params['subtype'] = values['tab_selected_subtype'];
	
	var $_this = $(this);
	
	$(this).replaceWith("<div class='elgg-ajax-loader' id='ge-loader'></div>");
	
	// Fire save action
	elgg.action('groupextender/save_tab', {
		data: {
			tab_id: values['tab_id'],
			tab_title: values['tab_title'],
			group_guid: values['group_guid'],
			tab_params: params,
		},
		success: function(data) {
			if (data.status != -1) {
				$.fancybox.close();
				elgg.groupextender.tabs.refreshCurrentTabs(values['group_guid']);
			}
			else $('#ge-loader').replaceWith($_this);
		}
	});
	
	event.preventDefault();
}

// Click handler for subtype tab save
elgg.groupextender.tabs.dashboardSaveClick = function(event) {
	// Get form inputs
	var $inputs = $("#group-extender-edit-dashboard-form :input");

	var values = {};
	$inputs.each(function() {
		values[this.name] = $(this).val();
	});
	
	var params = {};
	params['custom_tags'] = values['tab_custom_tags'];
	
	var $_this = $(this);
	
	$(this).replaceWith("<div class='elgg-ajax-loader' id='ge-loader'></div>");
	
	// Fire save action
	elgg.action('groupextender/save_tab', {
		data: {
			tab_id: values['tab_id'],
			tab_title: values['tab_title'],
			group_guid: values['group_guid'],
			tab_params: params,
		},
		success: function(data) {
			if (data.status != -1) {
				$.fancybox.close();
				elgg.groupextender.tabs.refreshCurrentTabs(values['group_guid']);
			}
			else $('#ge-loader').replaceWith($_this);
		}
	});
	
	event.preventDefault();
}

// Click handler for static tab save
elgg.groupextender.tabs.staticSaveClick = function(event) {
	// Get form inputs
	var $inputs = $("#group-extender-edit-static-form :input");

	var values = {};
	$inputs.each(function() {
		values[this.name] = $(this).val();
	});

	if (typeof(tinyMCE) !== 'undefined') {
		var static_content = tinyMCE.get('static-content').getContent();
		$("textarea#static-content").val(static_content);
	} else {
		var static_content = $("textarea#static-content").val();
	}

	var params = {};
	params['static_content'] = static_content;
	
	var $_this = $(this);
	
	$(this).replaceWith("<div class='elgg-ajax-loader' id='ge-loader'></div>");
	
	// Fire save action
	elgg.action('groupextender/save_tab', {
		data: {
			tab_id: values['tab_id'],
			tab_title: values['tab_title'],
			group_guid: values['group_guid'],
			tab_params: params,
		},
		success: function(data) {
			if (data.status != -1) {
				$.fancybox.close();
				elgg.groupextender.tabs.refreshCurrentTabs(values['group_guid']);
			}
			else $('#ge-loader').replaceWith($_this);
		}
	});
	
	event.preventDefault();
}

// Click handler for activity tab save
elgg.groupextender.tabs.activitySaveClick = function(event) {
	// Get form inputs
	var $inputs = $("#group-extender-edit-activity-form :input");

	var values = {};
	$inputs.each(function() {
		values[this.name] = $(this).val();
	});

	var $_this = $(this);
	
	$(this).replaceWith("<div class='elgg-ajax-loader' id='ge-loader'></div>");
	
	// Fire save action
	elgg.action('groupextender/save_tab', {
		data: {
			tab_id: values['tab_id'],
			tab_title: values['tab_title'],
			group_guid: values['group_guid'],
		},
		success: function(data) {
			if (data.status != -1) {
				$.fancybox.close();
				elgg.groupextender.tabs.refreshCurrentTabs(values['group_guid']);
			}
			else $('#ge-loader').replaceWith($_this);
		}
	});
	
	event.preventDefault();
}

// Click handler for customsearch tab save
elgg.groupextender.tabs.customsearchSaveClick = function(event) {
	// Get form inputs
	var $inputs = $("#group-extender-edit-customsearch-form :input");

	var values = {};
	$inputs.each(function() {
		values[this.name] = $(this).val();
	});

	var $_this = $(this);
	
	$(this).replaceWith("<div class='elgg-ajax-loader' id='ge-loader'></div>");
	
	// Fire save action
	elgg.action('groupextender/save_tab', {
		data: {
			tab_id: values['tab_id'],
			tab_title: values['tab_title'],
			group_guid: values['group_guid'],
		},
		success: function(data) {
			if (data.status != -1) {
				$.fancybox.close();
				elgg.groupextender.tabs.refreshCurrentTabs(values['group_guid']);
			}
			else $('#ge-loader').replaceWith($_this);
		}
	});
	
	event.preventDefault();
}

// Click handler for move up/down links
elgg.groupextender.tabs.moveLinkClick = function(event) {
	var action_url = $(this).attr('href');

	var $_this = $(this);
	
	var group_guid = elgg.groupextender.tabs.extractParamByName(action_url, 'group_guid');
	
	$(this).replaceWith("<span id='ge-loader'>" + $(this).html() + "</span>");
	
	// Fire move action
	elgg.action(action_url, {
		data: {},
		success: function(data) {
			if (data.status != -1) {
				elgg.groupextender.tabs.refreshCurrentTabs(group_guid);
			}
			else $('#ge-loader').replaceWith($_this);
		}
	});
	
	event.preventDefault();
}

// Helper function to refresh the current tabs form
elgg.groupextender.tabs.refreshCurrentTabs = function(group_guid) {
	var url = elgg.normalize_url('ajax/view/group-extender/forms/current_tabs?group_guid=' + group_guid);
	
	$("#group-extender-current-tabs-form").load(url, function() {
		elgg.groupextender.tabs.destroy();
		elgg.groupextender.tabs.init();
	});
}

// Helper function to extract querystring values
elgg.groupextender.tabs.extractParamByName = function(string, name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(string);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

elgg.register_hook_handler('init', 'system', elgg.groupextender.init);
elgg.register_hook_handler('init', 'system', elgg.groupextender.tabs.init);
elgg.register_hook_handler('groupextender', 'tab_clicked', elgg.groupextender.tabs.customSearchTabClicked);