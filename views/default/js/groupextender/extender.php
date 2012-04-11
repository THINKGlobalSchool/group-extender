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
			}
			else $('#ge-loader').replaceWith($_this);
		}
	});
	
	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.groupextender.init);
elgg.register_hook_handler('init', 'system', elgg.groupextender.tabs.init);
elgg.register_hook_handler('groupextender', 'tab_clicked', elgg.groupextender.tabs.customSearchTabClicked);