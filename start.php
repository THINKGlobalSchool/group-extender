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
	define('GROUP_CATEGORY_RELATIONSHIP', 'group_memberof_category');
	
	// Register library
	elgg_register_library('elgg:groupextender', elgg_get_plugins_path() . "group-extender/lib/groupextender.php");
	elgg_load_library('elgg:groupextender');
	
	// Register group extender JS
	$ge_js = elgg_get_simplecache_url('js', 'groupextender/extender');
	elgg_register_simplecache_view('js/groupextender/extender');
	elgg_register_js('elgg.groupextender', $ge_js);

	// Register group extender tabs JS
	$tabs_js = elgg_get_simplecache_url('js', 'groupextender/tabs');
	elgg_register_simplecache_view('js/groupextender/tabs');
	elgg_register_js('elgg.groupextender.tabs', $tabs_js);
	
	// Register group extender admin JS
	$ga_js = elgg_get_simplecache_url('js', 'groupextender/admin');
	elgg_register_simplecache_view('js/groupextender/admin');
	elgg_register_js('elgg.groupextender.admin', $ga_js);
	
	// Register tabs css
	$t_css = elgg_get_simplecache_url('css', 'groupextender/tabs');
	elgg_register_simplecache_view('css/groupextender/tabs');
	elgg_register_css('elgg.groupextender.tabs', $t_css);
	
	// Register admin css
	$ga_css = elgg_get_simplecache_url('css', 'groupextender/admin');
	elgg_register_simplecache_view('css/groupextender/admin');
	elgg_register_css('elgg.groupextender.admin', $ga_css);
	
	// Groups picker js
	$gp_js = elgg_get_simplecache_url('js', 'groupextender/grouppicker');
	elgg_register_simplecache_view('js/groupextender/grouppicker');
	elgg_register_js('elgg.grouppicker', $gp_js);

	// Register my own page handler
	elgg_register_page_handler('groups','group_extender_page_handler');

	// CSS
	elgg_extend_view('css/elgg', 'css/groupextender/css');
	
	//elgg_extend_view("groups/edit", "group-extender/edit_tabs_link", 400);
	//elgg_extend_view("groups/edit", "group-extender/forms/edit_tabs", 1000);

	// Extend owner_block for easy group navigator
	if (elgg_is_logged_in()) {
		elgg_extend_view('page/elements/owner_block', 'group-extender/navigator', 499);
	}
	
	elgg_extend_view('groups/edit', 'group-extender/group_tools_extra_js', 9999999999);
	
	elgg_extend_view('groups/sidebar/find', 'group-extender/sidebar/find_name');
	
	// Fix group profile ECML
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'group_extender_ecml_views_hook');
	
	// Group categories entity menu hook
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'groupcategories_setup_entity_menu', 9999);
	
	// Hook into filter menu
	elgg_register_plugin_hook_handler('register', 'menu:filter', 'group_extender_setup_group_filter_menu', 9999);

	// extend groups page handler
	elgg_register_plugin_hook_handler('route', 'groups', 'group_extender_route_groups_handler');

	// Tab actions
	$action_base = elgg_get_plugins_path() . 'group-extender/actions/group-extender';
	elgg_register_action("groupextender/save_tab", "$action_base/save_tab.php");
	elgg_register_action("groupextender/delete_tab", "$action_base/delete_tab.php");
	elgg_register_action("groupextender/move_tab", "$action_base/move_tab.php");
	elgg_register_action("group_dashboard/dashboard", "$action_base/dashboard.php");

	// Replace the group_tools mail action if it's enabled
	if (elgg_is_active_plugin('group_tools')) {
		// Unregister existing action
		elgg_unregister_action("group_tools/mail");
	
		// Register new action
		elgg_register_action("group_tools/mail", "$action_base/mail.php");
	}

	// Group category actions
	$action_base = elgg_get_plugins_path() . 'group-extender/actions/group_category';
	elgg_register_action("group_category/save", "$action_base/save.php");
	elgg_register_action("group_category/delete", "$action_base/delete.php");
	elgg_register_action("group_category/addgroup", "$action_base/addgroup.php");
	elgg_register_action("group_category/removegroup", "$action_base/removegroup.php");	
	
	// Pagesetup event handler
	elgg_register_event_handler('pagesetup', 'system', 'group_extender_submenus');
	
	// Whitelist ajax views
	elgg_register_ajax_view('group-extender/modules/activity');
	elgg_register_ajax_view('group-extender/modules/subtype');
	elgg_register_ajax_view('group-extender/forms/edit_tab');
	elgg_register_ajax_view('group-extender/forms/edit_subtype');
	elgg_register_ajax_view('group-extender/forms/edit_static');
	elgg_register_ajax_view('group-extender/forms/edit_tagdashboard');
	elgg_register_ajax_view('group-extender/forms/edit_activity');
	elgg_register_ajax_view('group-extender/forms/edit_customsearch');
	elgg_register_ajax_view('group-extender/forms/current_tabs');
	elgg_register_ajax_view('group-extender/group_tabs');
	elgg_register_ajax_view('group-extender/admin/category_groups');
	elgg_register_ajax_view('group-extender/modules/groups');
	elgg_register_ajax_view('group-extender/modules/group');
	elgg_register_ajax_view('group-extender/modules/category_groups');
	elgg_register_ajax_view('group-extender/modules/group_categories');
}

/**
 * Group extender page handler, loads the JS and calls the regular group page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function group_extender_page_handler($page) {
		// Load extender JS
		elgg_load_js('elgg.groupextender');		
		elgg_load_js('elgg.groupextender.tabs');		
		
		// Load tab CSS
		elgg_load_css('elgg.groupextender.tabs');
		
		// Load up tinymce
		elgg_load_js('tinymce');
		elgg_load_js('elgg.tinymce');
		
		// Load tgsembed JS/CSS
		if (elgg_is_active_plugin('tgsembed')) {
			elgg_load_js('colorbox');
			elgg_load_js('jQuery-File-Upload');
			elgg_load_js('elgg.tgsembed');
			elgg_load_css('elgg.tgsembed');
		}
		
		// Going to hack in a better group activity handler
		if ($page[0] == 'activity') {
			groups_extender_handle_activity_page($page[1]);
		} else if ($page[0] == 'edit' && $page[2] == 'tabs') {
			//groups_extender_handle_edit_tabs_page($page[1]);
		} else if ($page[0] == 'search' && get_input('name')) {
			group_extender_get_name_search();
		} else if ($page[0] == 'dashboard'){
			group_extender_get_dashboard();
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
 * Group categories entity plugin hook
 */
function groupcategories_setup_entity_menu($hook, $type, $return, $params) {

	$entity = $params['entity'];
	
	if (!elgg_instanceof($entity, 'object', 'group_category')) {
		return $return;
	}

	$return = array();

	$options = array(
		'name' => 'edit',
		'text' => elgg_echo('edit'),
		'href' => elgg_get_site_url() . 'admin/groupextender/editcategory?guid=' . $entity->guid,
		'priority' => 2,
	);
	$return[] = ElggMenuItem::factory($options);
	
	$options = array(
		'name' => 'delete',
		'text' => elgg_view_icon('delete'),
		'title' => elgg_echo('delete:this'),
		'href' => "action/{$params['handler']}/delete?guid={$entity->getGUID()}",
		'confirm' => elgg_echo('deleteconfirm'),
		'priority' => 3,
	);

	$return[] = ElggMenuItem::factory($options);

	return $return;
}

/**
 * Hook into menu filter to find and extend the groups nav menu
 */
function group_extender_setup_group_filter_menu($hook, $type, $return, $params) {
	// 'filter' menu is used elsewhere, so check for groups context, also check for filter flag (set in route handler)
	if (elgg_in_context('groups') && get_input('groups_all_filter_extend')) {
		// Add categories option
		$options = array(
			'name' => 'groups_categories',
			'text' => elgg_echo('admin:groupextender:categories'),
			'href' => "groups/all?filter=categories",
			'priority' => 100,
		);

		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

// Hook into group routing to provide extra content
function group_extender_route_groups_handler($hook, $type, $return, $params) {
	set_input('groups_all_filter_extend', true);

	// Make categories the default
	if (!get_input('filter')) {
		forward('groups/all?filter=categories');
	}

	// Check if we're in the 'categories' filter
	if (is_array($return['segments']) && $return['segments'][0] == 'all' && get_input('filter') == 'categories') {
		// Load JS
		elgg_load_js('elgg.groupextender');	
		
		// all groups doesn't get link to self
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('groups'));

		elgg_register_title_button();
		
		$selected_tab = get_input('filter', 'categories');
		
		$filter = elgg_view('groups/group_sort_menu', array('selected' => $selected_tab));

		$sidebar = elgg_view('groups/sidebar/find');
		$sidebar .= elgg_view('groups/sidebar/featured');

		$content = elgg_view('group-extender/group_categories');

		$params = array(
			'content' => $content,
			'sidebar' => $sidebar,
			'filter' => $filter,
		);

		$body = elgg_view_layout('content', $params);

		echo elgg_view_page(elgg_echo('groups:all'), $body);
		
		return FALSE;
	}
	return $return;
}

/**
 * Setup Group Extender Submenus
 */
function group_extender_submenus() {
	if (elgg_in_context('admin')) {
		elgg_register_admin_menu_item('administer', 'categories', 'groupextender');
		elgg_register_admin_menu_item('administer', 'dashboard', 'groupextender');
	}
	
	// Display group dashboard sidebar menu item
	$page_owner = elgg_get_page_owner_entity();

	if (elgg_is_logged_in() && elgg_get_context() == 'groups' && !elgg_instanceof($page_owner, 'group') && elgg_get_plugin_setting('enable_dashboard', 'group-extender')) {
		$url =  "groups/dashboard";
		$item = new ElggMenuItem('zz-groupdashboard', elgg_echo('group-extender:title:groupdashboard'), $url);
		elgg_register_menu_item('page', $item);
	}
}

/**
 * Parse ECML on group profiles
 */
function group_extender_ecml_views_hook($hook, $type, $return, $params) {
	$return['groups/profile/fields'] = elgg_echo('groups:ecml:groupprofile');

	return $return;
}