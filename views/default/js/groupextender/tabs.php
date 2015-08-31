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
	$(document).on('click', '.group-extender-tab-menu-item, .group-extender-customize-nav-link', elgg.groupextender.tabs.customTabClick);

	// Click handler for all tab save submit inputs
	$(document).on('click', '.group-extender-tab-save-submit', elgg.groupextender.tabs.tabSaveClick);
	
	// Click handler for refreshing group tabs
	$(document).on('click', '#group-extender-tab-refresh-submit', elgg.groupextender.tabs.tabRefreshClick);

	// Click handler for move up/down links
	$(document).on('click', '.group-extender-move-link', elgg.groupextender.tabs.refreshableClick);

	// Click handler for delete links
	$(document).on('click', '.group-extender-delete-link', elgg.groupextender.tabs.refreshableClick);

	// Click handler for homepage links
	$(document).on('click', '.group-extender-homepage-link', elgg.groupextender.tabs.refreshableClick);
	
	// Change handler for tab type change
	$(document).on('change', '#group-extender-tab-type-select', elgg.groupextender.tabs.tabTypeChange);

	// Change handler for rss feed tab type change
	$(document).on('change', 'select[name="feed_tab_type"]', elgg.groupextender.tabs.rssTabTypeChange);

	$('#group-extender-tab-type-select').trigger('change');

	// Set up group extender lightboxen
	$(".group-extender-lightbox").colorbox({
		'trapFocus': false,
		'onComplete': function() {
			// Attempt to load google doc pickers
			if (elgg.google != undefined && elgg.google.apiLoaded == true) {
				elgg.google.initPickers();
			}

			// Init lightbox embed if it exists
			if (typeof elgg.tgsembed !== "undefined") {
				elgg.tgsembed.initLightbox();
			}
			
			if (typeof CKEDITOR !== "undefined") {
				CKEDITOR.on('instanceReady', function(event) {
					$.colorbox.resize();
				});	
			}
		},
		'hideOnOverlayClick':false,
    	'hideOnContentClick':false
	});
		
	elgg.groupextender.tabs.processHash();

	// Hide the google search description on google search click
	$("input.gsc-search-button").live('click', elgg.groupextender.tabs.googleSearchSubmit);
}

// Teardown function
elgg.groupextender.tabs.destroy = function() {
	// Undelegate events
	$(document).off('click', '.group-extender-tab-menu-item');
	$(document).off('click', '.group-extender-tab-save-submit');
	$(document).off('click', '#group-extender-tab-refresh-submit');
	$(document).off('click', '.group-extender-move-link');
	$(document).off('click', '.group-extender-homepage-link');
	$(document).off('click', '.group-extender-delete-link');
	$(document).off('change', '#group-extender-tab-type-select');
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
		source: $(this),
	};
	
	elgg.trigger_hook('geTabClicked', 'clicked', params);
	
	// Put hash in url
	window.location.hash = '#tab:' + $(this).attr('id');
	
	event.preventDefault();
}

// Hook into google search submit
elgg.groupextender.tabs.googleSearchSubmit = function(event) {
	$('.googlesearch-desc').hide();
}

// Hook into the clicked hook and perform extra processing for tag dashboard tabs
elgg.groupextender.tabs.tagdashboardTabClicked = function(hook, type, params, options) {
	var $container = $(params.target_id).find('.tagdashboard-tab-container');
	if ($container.length) {
		elgg.tagdashboards.init_dashboards_with_container($container);
	}
}

// Hook into tab clicked for static tabs to load them dynamically
elgg.groupextender.tabs.staticTabClicked = function(hook, type, params, options) {
	var $container = $(params.target_id);
	var type = $container.data('type');

	// Check for static container
	if (type == 'static') {
		var group_guid = $container.data('tab_group');
		var tab_uid = $container.data('tab_uid');
		$container.addClass('elgg-ajax-loader');
		elgg.get('ajax/view/group-extender/tabs/static', {
			data: {
				group_guid: group_guid,
				tab_id: tab_uid
			}, 
			success: function(data) {
				$container.removeClass('elgg-ajax-loader');
				$container.html(data);
			},
		});
	}
}

// Master click handler for all save events
elgg.groupextender.tabs.tabSaveClick = function(event) {
	// Get form inputs
	var $form = $(this).closest('form');

	var params = {
		'trigger': $(this),
		'form': $form
	};

	// Trigger a hook to allow intercepting the save buttons
	if (!elgg.trigger_hook('geTabSave', 'clicked', params, true)) {
		event.preventDefault();
		return false;
	}

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
	$.colorbox.resize();

	// Fire save action
	elgg.action('groupextender/save_tab', {
		data: values,
		success: function(data) {
			if (data.status != -1) {
				// Close if we're in a lightbox
				$.colorbox.close();

				elgg.groupextender.tabs.refreshCurrentTabs(values['group_guid']);

				$('#group-extender-tab-menu-itemtype-select').change();
				
				// Trigger a hook to provide extra cleanup after successful save
				elgg.trigger_hook('geTabSaved', 'cleanup', {'add_param' : values['add_param']}, values);
			}
			$('#ge-loader').replaceWith($_this);
		}
	});

	event.preventDefault();
}

// Hook into rss tab save click and validate RSS feed
elgg.groupextender.tabs.rssTabSaveClick = function(hook, type, params, value) {
	switch (params.form.find('select[name="feed_tab_type"]').val()) {
		case 'all':
		case 'group_feed':
			return value;
			break;
		case 'url':
			var $feed_url_input = params.form.find('input[name="feed_url"]');
			if ($feed_url_input.length && !$feed_url_input.data('valid_feed')) {
				elgg.action('rss/validate', {
					data: {
						feed_url: $feed_url_input.val()
					}, 
					success: function(result) {
						if (result.status == 0) {
							$feed_url_input.data('valid_feed', 1);
							params.trigger.trigger('click');
						} else {
							// Invalid feed
						}
					}
				});
				return false;
			}
			break;
	}

	return value;
}

// Click handler for refresh tab click
elgg.groupextender.tabs.tabRefreshClick = function(event) {
	// Get form inputs
	var $form = $('#groupextender-tab-admin').find('form');

	var values = {};
	$.each($form.serializeArray(), function(i, field) {
	    values[field.name] = field.value;
	});

	var content_url = elgg.normalize_url('ajax/view/group-extender/group_tabs_content?guid=' + values['group_guid']);
	var menu_url = elgg.normalize_url('ajax/view/group-extender/group_tabs_menu?guid=' + values['group_guid']);

	$("#group-extender-group-tabs").html("<div class='elgg-ajax-loader'></div>");
	
	$("#group-extender-group-tabs").load(content_url, function() {
		elgg.groupextender.tabs.destroy();
		elgg.groupextender.tabs.init();
		
		elgg.modules.genericmodule.init();

		if (elgg.rss) {
			elgg.rss.initFeeds();
		}

		if (elgg.tagdashboards != undefined) {
			elgg.tagdashboards.init();
		}
	});

	$("#group-extender-group-tabs-menu").html("<div class='elgg-ajax-loader'></div>");
	$("#group-extender-group-tabs-menu").load(menu_url);

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
				$.colorbox.close();
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

	// Add confirmation for delete click
	if ($_this.is('.group-extender-delete-link')) {
		if (!confirm(elgg.echo('group-extender:label:deleteconfirm'))) {
			return false;
		}
	}
	
	var group_guid = elgg.groupextender.tabs.extractParamByName(action_url, 'group_guid');
	
	$(this).replaceWith("<span id='ge-loader'>" + $(this).html() + "</span>");
	
	// Fire action
	elgg.action(action_url, {
		data: {},
		success: function(data) {
			if (data.status != -1) {
				elgg.groupextender.tabs.refreshCurrentTabs(group_guid);
			}
			else {
				$('#ge-loader').replaceWith($_this);
			}
		}
	});
	
	event.preventDefault();
}

// Change handler for tab type select
elgg.groupextender.tabs.tabTypeChange = function(event) {
	// Get tab type
	var type = $(this).val();

	// Get group guid to pass to forms
	var group_guid = $(this).closest('form').find('input[name="group_guid"]').val();
	
	// Params for tab type change hook
	var params = {
		source: $(this),
		tab_type: type
	};
	
	// Allow further customization of tab type content on change
	elgg.trigger_hook('geTabTypeChanged', 'geChanged', params);
	
	var url = elgg.normalize_url('ajax/view/group-extender/forms/edit_' + type + '?group_guid=' + group_guid);
	
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

// Change handler for rss feed tab type change
elgg.groupextender.tabs.rssTabTypeChange = function(event) {
	var type = $(this).val();
		
	$(this).closest('form').find('._rsstabtype').hide();

	$(this).closest('form').find('._rsstabtype_' + type).show();

	event.preventDefault();
}

// Hook handler for tab type select for static content
elgg.groupextender.tabs.staticTabTypeChanged = function(hook, type, params, options) {
	if (params.tab_type != 'static') {
		// Nada, leaving here just in case
	}
	return options;
}

// Hook handler for tab type changed
elgg.groupextender.tabs.staticContentSelected = function(hook, type, params, options) {
	if (type == 'static') {		
		// Init lightbox embed if it exists
		if (typeof(elgg.tgsembed) != 'undefined') {
			elgg.tgsembed.initLightbox();
		}

		// Attempt to load google doc pickers
		if (elgg.google != undefined && elgg.google.apiLoaded == true) {
			elgg.google.initPickers();
		}
	}
	return options;
}

// Hook handler for customizing the form value when submitting static content
elgg.groupextender.tabs.staticContentFormValue = function(hook, type, params, options) {
	if (params['add_param'] == 'static_content') {
		// In case we need something extra from the longtext editor
	}
	return options;
}


// Hook handler for cleaning up any longtext inputs after saving
elgg.groupextender.tabs.staticContentCleanup = function(hook, type, params, options) {
	if (params['add_param'] == 'static_content') {
		// text editor cleanup
	}
	return options;
}

// Hook handler for cleaning up the new tab save form
elgg.groupextender.tabs.newFormCleanup = function(hook, type, params, options) {
	$new_content = $('#group-extender-extended-type-content');

	if ($new_content.length != 0) {
		$new_content.html('');
		$('select#group-extender-tab-type-select').val('subtype').trigger('change');
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

/**
 * Check for and process any supplied hash paramaters
 * 
 * - This doesn't do any kind of validation, it simple tries to click 
 * the link that would be on the page if a submission exists. If there's no 
 * match, nothing happens. (Desired behaviour)
 */
elgg.groupextender.tabs.processHash = function(todo_guid) {
	// Check for hash
	if (window.location.hash) {
		// Grab the tab ID
		var tab_id = window.location.hash.replace('#tab:', '');	

		// Click the given tab_id
		$('a#' + tab_id).trigger('click');
	}
}

elgg.register_hook_handler('init', 'system', elgg.groupextender.tabs.init);
elgg.register_hook_handler('geTabClicked', 'clicked', elgg.groupextender.tabs.tagdashboardTabClicked);
elgg.register_hook_handler('geTabClicked', 'clicked', elgg.groupextender.tabs.staticTabClicked);
elgg.register_hook_handler('geTabSave', 'clicked', elgg.groupextender.tabs.rssTabSaveClick);
//elgg.register_hook_handler('geTabTypeChanged', 'geChanged', elgg.groupextender.tabs.staticTabTypeChanged);
elgg.register_hook_handler('geTabTypeLoaded', 'static', elgg.groupextender.tabs.staticContentSelected);
elgg.register_hook_handler('geGetFormValues', 'values', elgg.groupextender.tabs.staticContentFormValue);
elgg.register_hook_handler('geTabSaved', 'cleanup', elgg.groupextender.tabs.staticContentCleanup);
elgg.register_hook_handler('geTabSaved', 'cleanup', elgg.groupextender.tabs.newFormCleanup);
elgg.register_hook_handler('should_display', 'ajaxmodule', elgg.groupextender.tabs.multipleTagdashboards);