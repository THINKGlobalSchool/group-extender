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
elgg.provide('elgg.groupextender.tabs');

// Tabs init
elgg.groupextender.tabs.init = function() {
	// Click handler for custom group tabs
	$(document).delegate('.group-extender-tab-menu-item', 'click', elgg.groupextender.tabs.customTabClick);

	// Click handler for all tab save submit inputs
	$(document).delegate('#group-extender-tab-save-submit', 'click', elgg.groupextender.tabs.tabSaveClick);
	
	// Click handler for refreshing group tabs
	$(document).delegate('#group-extender-tab-refresh-submit', 'click', elgg.groupextender.tabs.tabRefreshClick);

	// Click handler for move up/down links
	$(document).delegate('.group-extender-move-link', 'click', elgg.groupextender.tabs.refreshableClick);

	// Click handler for delete links
	$(document).delegate('.group-extender-delete-link', 'click', elgg.groupextender.tabs.refreshableClick);
	
	// Change handler for tab type change
	$(document).delegate('#group-extender-tab-type-select', 'change', elgg.groupextender.tabs.tabTypeChange);

	// Set up submission dialog
	$(".group-extender-lightbox").fancybox({
		'onComplete': function() {
			// Fix tinymce control
			if (typeof(tinyMCE) !== 'undefined') {
				tinyMCE.EditorManager.execCommand('mceAddControl', false, 'static-content');
			}
			
			// Init lightbox embed if it exists
			if (typeof(elgg.tgsembed) != 'undefined') {
				elgg.tgsembed.initLightbox();
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
	$(document).undelegate('#group-extender-tab-save-submit', 'click');
	$(document).undelegate('#group-extender-tab-refresh-submit', 'click');
	$(document).undelegate('.group-extender-move-link', 'click');
	$(document).undelegate('.group-extender-delete-link', 'click');
	$(document).undelegate('#group-extender-tab-type-select', 'change');
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
	
	elgg.trigger_hook('geTabClicked', 'clicked', params);
	
	event.preventDefault();
}

// Hook into the clicked hook and perform extra processing for custom search tabs
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

// Hook into the clicked hook and perform extra processing for tag dashboard tabs
elgg.groupextender.tabs.tagdashboardTabClicked = function(hook, type, params, options) {
	var $container = $(params.target_id).find('.tagdashboard-tab-container');
	if ($container.length) {
		elgg.tagdashboards.init_dashboards_with_container($container);
	}
}


// Master click handler for all save events
elgg.groupextender.tabs.tabSaveClick = function(event) {
	// Get form inputs
	var $form = $(this).closest('form');
	var values = {};
	$.each($form.serializeArray(), function(i, field) {
	    values[field.name] = field.value;
	});

	// Could possible be more than one add_param input.. so get all of them
	if ($form.find("input[name='add_param[]']").length > 0) {
		var multiple_add_params = [];

		// Grab and store each param
		$form.find("input[name='add_param[]']").each(function() {
			multiple_add_params.push($(this).val());
		});
		
		// Set value
		values['add_param'] = multiple_add_params;
		
		// Remove add_param[]
		delete values['add_param[]'];
	}
	
	var params = {
		'add_param' : values['add_param']
	};

	// Allow modifications of form values (if there are extended values)
	values = elgg.trigger_hook('geGetFormValues', 'values', params, values);

	var $_this = $(this);
	
	$(this).replaceWith("<div class='elgg-ajax-loader' id='ge-loader'></div>");

	// Fire save action
	elgg.action('groupextender/save_tab', {
		data: values,
		success: function(data) {
			if (data.status != -1) {
				// Close if we're in a fancybox
				$.fancybox.close();

				elgg.groupextender.tabs.refreshCurrentTabs(values['group_guid']);
				
				// Trigger a hook to provide extra cleanup after successful save
				elgg.trigger_hook('geTabSaved', 'cleanup', {'add_param' : values['add_param']}, values);
			}
			$('#ge-loader').replaceWith($_this);
		}
	});

	event.preventDefault();
}

// Click handler for refresh tab click
elgg.groupextender.tabs.tabRefreshClick = function(event) {
	// Get form inputs
	var $form = $('#groupextender-tab-admin').find('form');

	var values = {};
	$.each($form.serializeArray(), function(i, field) {
	    values[field.name] = field.value;
	});

	var url = elgg.normalize_url('ajax/view/group-extender/group_tabs?group_guid=' + values['group_guid']);
	
	$("#group-extender-group-tabs").html("<div class='elgg-ajax-loader'></div>");
	
	$("#group-extender-group-tabs").load(url, function() {
		elgg.groupextender.tabs.destroy();
		elgg.groupextender.tabs.init();
		
		elgg.modules.genericmodule.init();
		elgg.tagdashboards.init();
	});

	event.preventDefault();
}

// Click handler for static tab save
elgg.groupextender.tabs.staticSaveClick = function(event) {
	// Get form inputs
	var $inputs = $("#group-extender-tab-edit-form-static :input");

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

// Click handler for refreshable clicks
elgg.groupextender.tabs.refreshableClick = function(event) {
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

// Click handler for tab type change clicks
elgg.groupextender.tabs.tabTypeChange = function(event) {
	var type = $(this).val();
	
	// Params for tab type change hook
	var params = {
		source: $(this),
		tab_type: type
	};
	
	// Allow further customization of tab type content on change
	elgg.trigger_hook('geTabTypeChanged', 'geChanged', params);
	
	var url = elgg.normalize_url('ajax/view/group-extender/forms/edit_' + type);
	
	var $container = $('#group-extender-extended-type-content');
	
	$container.html("<div class='elgg-ajax-loader'></div>");
	
	$container.load(url, function() {
		// Params for tab type loaded hook
		var params = {
			target_id: $container.attr('id'),
			source: $(this)
		};
		
		// Allow further customization of tab type content on load
		elgg.trigger_hook('geTabTypeLoaded', type, params);
	});
	
	event.preventDefault();
}

// Hook handler for tab type select for static content
elgg.groupextender.tabs.tagTypeChanged = function(hook, type, params, options) {
	if (params.tab_type != 'static') {
		// Fix tinymce control
		if (typeof(tinyMCE) !== 'undefined') {
    		tinyMCE.EditorManager.execCommand('mceRemoveControl', false, 'static-content');
		}
	}
	return options;
}

// Hook handler for tab type changed
elgg.groupextender.tabs.staticContentSelected = function(hook, type, params, options) {
	if (type == 'static') {
		// Fix tinymce control
		if (typeof(tinyMCE) !== 'undefined') {
			tinyMCE.EditorManager.execCommand('mceAddControl', false, 'static-content');
		}
		
		// Init lightbox embed if it exists
		if (typeof(elgg.tgsembed) != 'undefined') {
			elgg.tgsembed.initLightbox();
		}
	}
	return options;
}

// Hook handler for customizing the form value when submitting static content
elgg.groupextender.tabs.staticContentFormValue = function(hook, type, params, options) {
	if (params['add_param'] == 'static_content') {
		// Get static content value from tinymcecontrol
		if (typeof(tinyMCE) !== 'undefined') {
			var static_content = tinyMCE.get('static-content').getContent();
			options['static_content'] = static_content;
		}
	}
	return options;
}


// Hook handler for cleaning up any tinymce inputs after saving
elgg.groupextender.tabs.staticContentCleanup = function(hook, type, params, options) {
	if (params['add_param'] == 'static_content') {
		// Cleanup tinymce
		if (typeof(tinyMCE) !== 'undefined') {
    		tinyMCE.EditorManager.execCommand('mceRemoveControl', false, 'static-content');
		}
	}
	return options;
}

// Hook handler for cleaning up the new tab save form
elgg.groupextender.tabs.newFormCleanup = function(hook, type, params, options) {
	$new_content = $('#group-extender-extended-type-content');
	
	if ($new_content.length != 0) {
		$new_content.html('');
		$('select#group-extender-tab-type-select').val('activity');
		$('#group-extender-tab-edit-form-new').find('input[name="tab_title"]').val('');
	}
	
	return options;
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

// Hook into ajaxmodule should_display to handler multiple tagdashboards on the same page
elgg.groupextender.tabs.multipleTagdashboards = function(hook, type, params, options) {
	var $group_extender_container = params.closest('.group-extender-tab-content-container');
	if ($group_extender_container.length !== 0 && !$group_extender_container.is(':visible')) {
		return false;
	}
	return true;
}

elgg.register_hook_handler('init', 'system', elgg.groupextender.tabs.init);
elgg.register_hook_handler('geTabClicked', 'clicked', elgg.groupextender.tabs.customSearchTabClicked);
elgg.register_hook_handler('geTabClicked', 'clicked', elgg.groupextender.tabs.tagdashboardTabClicked);
elgg.register_hook_handler('geTabTypeChanged', 'geChanged', elgg.groupextender.tabs.tagTypeChanged);
elgg.register_hook_handler('geTabTypeLoaded', 'static', elgg.groupextender.tabs.staticContentSelected);
elgg.register_hook_handler('geGetFormValues', 'values', elgg.groupextender.tabs.staticContentFormValue);
elgg.register_hook_handler('geTabSaved', 'cleanup', elgg.groupextender.tabs.staticContentCleanup);
elgg.register_hook_handler('geTabSaved', 'cleanup', elgg.groupextender.tabs.newFormCleanup);
elgg.register_hook_handler('should_display', 'ajaxmodule', elgg.groupextender.tabs.multipleTagdashboards);