<?php
/**
 * Group-Extender start.php
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Register init
elgg_register_event_handler('init', 'system', 'group_extender_init');

// Init
function group_extender_init() {
	
	// Register library
	elgg_register_library('elgg:groupextender', elgg_get_plugins_path() . "group-extender/lib/groupextender.php");
	elgg_load_library('elgg:groupextender');
	
	// Register group extender JS
	$extender_js = elgg_get_simplecache_url('js', 'groupextender/extender');
	elgg_register_simplecache_view('js/groupextender/extender');
	elgg_register_js('elgg.groupextender', $extender_js);
	
	// Register tabs css
	$t_css = elgg_get_simplecache_url('css', 'groupextender/tabs');
	elgg_register_simplecache_view('css/groupextender/tags');
	elgg_register_css('elgg.groupextender.tabs', $t_css);

	// Register my own page handler
	elgg_register_page_handler('groups','group_extender_page_handler');

	// CSS
	elgg_extend_view('css/elgg', 'css/groupextender/css');
	
	elgg_extend_view("groups/edit", "group-extender/edit_tabs_link", 400);

	// Extend owner_block for easy group navigator
	elgg_extend_view('page/elements/owner_block', 'group-extender/navigator', 499);
	
	// Fix group profile ECML
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'group_extender_ecml_views_hook');
	
	// Actions
	$action_base = elgg_get_plugins_path() . 'group-extender/actions/group-extender';
	elgg_register_action("groupextender/save_tab", "$action_base/save_tab.php");
	elgg_register_action("groupextender/delete_tab", "$action_base/delete_tab.php");
	elgg_register_action("groupextender/move_tab", "$action_base/move_tab.php");
	
	// Whitelist ajax views
	elgg_register_ajax_view('group-extender/modules/activity');
	elgg_register_ajax_view('group-extender/modules/subtype');
	elgg_register_ajax_view('group-extender/forms/edit_subtype');
	elgg_register_ajax_view('group-extender/forms/edit_static');
	elgg_register_ajax_view('group-extender/forms/edit_tagdashboard');
}

/**
 * Group extender page handler, loads the JS and calls the regular group page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function group_extender_page_handler($page) {
		// Load extender JS
		elgg_load_js('elgg.groupextender');		
		
		// Load tab CSS
		elgg_load_css('elgg.groupextender.tabs');
		
		// Load up tinymce
		elgg_load_js('tinymce');
		elgg_load_js('elgg.tinymce');
		
		// Going to hack in a better group activity handler
		if ($page[0] == 'activity') {
			groups_extender_handle_activity_page($page[1]);
		} else if ($page[0] == 'edit' && $page[2] == 'tabs') {
			groups_extender_handle_edit_tabs_page($page[1]);
		} else {
			groups_page_handler($page);
		}	
		return true;
}

/**
 * Group activity page
 *
 * @param int $guid Group entity GUID
 */
function groups_extender_handle_activity_page($guid) {

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid);
	if (!$group || !elgg_instanceof($group, 'group')) {
		forward();
	}

	group_gatekeeper();

	$title = elgg_echo('groups:activity');

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($title);

	$db_prefix = elgg_get_config('dbprefix');

	$content = elgg_list_river(array(
		'joins' => array(
			"JOIN {$db_prefix}entities e ON e.guid = rv.object_guid",
			"JOIN {$db_prefix}entities ec ON ec.guid = e.container_guid"
		),
		'wheres' => array("(e.container_guid = $guid || ec.container_guid = $guid)")
	));
	if (!$content) {
		$content = '<p>' . elgg_echo('groups:activity:none') . '</p>';
	}
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Handle edit tabs page
 * 
 * @param int $guid Group entity GUID
 */
function groups_extender_handle_edit_tabs_page($guid) {
	$title = elgg_echo('group-extender:label:editgrouptabs');
	$group = get_entity($guid);

	if ($group && $group->canEdit()) {
		elgg_set_page_owner_guid($group->getGUID());
		elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");
		elgg_push_breadcrumb($group->name, $group->getURL());
		elgg_push_breadcrumb(elgg_echo('groups:edit'), elgg_get_site_url() . 'groups/edit/' . $group->guid);
		elgg_push_breadcrumb($title);
		$content = elgg_view("group-extender/forms/edit_tabs", array('entity' => $group));
	} else {
		$content = elgg_echo('groups:noaccess');
	}
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Parse ECML on group profiles
 */
function group_extender_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['groups/profile/fields'] = elgg_echo('groups:ecml:groupprofile');

	return $return_value;
}
	