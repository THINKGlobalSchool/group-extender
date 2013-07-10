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
 * OVERRIDES:
 *   * groups/sidebar/find
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
	elgg_load_js('elgg.groupextender');

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

	// Register colorbox JS
	$cb_js = elgg_get_simplecache_url('js', 'colorbox');
	elgg_register_simplecache_view('js/colorbox');
	elgg_register_js('colorbox', $cb_js);
	elgg_load_js('colorbox');
	
	// Register my own page handler
	elgg_register_page_handler('groups','group_extender_page_handler');

	// CSS
	elgg_extend_view('css/elgg', 'css/groupextender/css');
	
	//elgg_extend_view("groups/edit", "group-extender/edit_tabs_link", 400);
	//elgg_extend_view("groups/edit", "group-extender/forms/edit_tabs", 1000);

	// Extend owner_block for easy group navigator
	if (elgg_is_logged_in()) {
		//elgg_extend_view('page/elements/owner_block', 'group-extender/navigator', 499);
	}
	
	elgg_extend_view('groups/edit', 'group-extender/group_tools_extra_js', 9999999999);

	// Extend featured sidebar
	elgg_extend_view('groups/sidebar/featured', 'group-extender/featured', 499);
	
	//elgg_extend_view('groups/sidebar/find', 'group-extender/sidebar/find_name');
	
	// Fix group profile ECML
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'group_extender_ecml_views_hook');

	// General Group Extender entity menu hook
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'group_extender_setup_entity_menu');
	
	// Group categories entity menu hook
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'groupcategories_setup_entity_menu', 9999);
	
	// Hook into filter menu
	elgg_register_plugin_hook_handler('register', 'menu:filter', 'group_extender_setup_group_filter_menu');

	// Hook into title menu
	elgg_register_plugin_hook_handler("register", "menu:title", "group_extender_menu_title_handler");

	// extend groups page handler
	elgg_register_plugin_hook_handler('route', 'groups', 'group_extender_route_groups_handler', 100);
	
	// Set up group admin hover menu
	elgg_register_plugin_hook_handler('register', 'menu:group_hover', 'group_extender_hover_menu_setup');
	
	// Register a handler for core subtype's group move functionality
	elgg_register_plugin_hook_handler('groupmove', 'entity', 'group_extender_group_move_handler');
	
	if (elgg_is_logged_in() && (int)elgg_get_plugin_setting('enable_topbar_dropdown', 'group-extender')) {
		elgg_register_plugin_hook_handler('register', 'menu:topbar', 'group_extender_topbar_menu_setup', 9001);
	}

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
	elgg_register_action("group_category/save", "$action_base/save.php", 'admin');
	elgg_register_action("group_category/delete", "$action_base/delete.php", 'admin');
	elgg_register_action("group_category/addgroup", "$action_base/addgroup.php", 'admin');
	elgg_register_action("group_category/removegroup", "$action_base/removegroup.php", 'admin');
	
	// Group Content Actions
	$action_base = elgg_get_plugins_path() . 'group-extender/actions/groups';
	elgg_register_action("groups/movecontent", "$action_base/movecontent.php");
	elgg_register_action("groups/copycontent", "$action_base/copycontent.php");	
	elgg_register_action("groups/archive", "$action_base/archive.php", 'admin');
	elgg_register_action("groups/unarchive", "$action_base/unarchive.php", 'admin');
	
	// Pagesetup event handler
	elgg_register_event_handler('pagesetup', 'system', 'group_extender_submenus');
	
	// Whitelist ajax views
	elgg_register_ajax_view('group-extender/modules/activity');
	elgg_register_ajax_view('group-extender/modules/subtype');
	elgg_register_ajax_view('group-extender/modules/groups');
	elgg_register_ajax_view('group-extender/modules/group');
	elgg_register_ajax_view('group-extender/modules/category_groups');
	elgg_register_ajax_view('group-extender/modules/group_categories');
	elgg_register_ajax_view('group-extender/modules/filter_class_groups');
	elgg_register_ajax_view('group-extender/forms/edit_tab');
	elgg_register_ajax_view('group-extender/forms/edit_subtype');
	elgg_register_ajax_view('group-extender/forms/edit_rss');
	elgg_register_ajax_view('group-extender/forms/edit_static');
	elgg_register_ajax_view('group-extender/forms/edit_tagdashboard');
	elgg_register_ajax_view('group-extender/forms/edit_activity');
	elgg_register_ajax_view('group-extender/forms/edit_customsearch');
	elgg_register_ajax_view('group-extender/forms/current_tabs');
	elgg_register_ajax_view('group-extender/group_tabs');
	elgg_register_ajax_view('group-extender/admin/category_groups');
	elgg_register_ajax_view('group-extender/category_groups');
	elgg_register_ajax_view('group-extender/popup/move');
	elgg_register_ajax_view('group-extender/popup/copy');
	
	// Override plugin views if we have a class category defined
	if ((int)elgg_get_plugin_setting('class_category', 'group-extender')) {
		// Set up group class filter menu
		elgg_register_plugin_hook_handler('register', 'menu:groups_class_other_menu', 'group_extender_class_filter_menu_setup');

		// Override groups home page module
		elgg_set_view_location('tgstheme/modules/groups', elgg_get_plugins_path() . "group-extender/overrides/");

		// Override parentportal child groups module
		elgg_set_view_location('parentportal/child_groups', elgg_get_plugins_path() . "group-extender/overrides/");
	}

	// Override some group view locations (to get around group_tools plugin)
	elgg_set_view_location('groups/sidebar/featured', elgg_get_plugins_path() . "group-extender/overrides/");	
}

/**
 * Group extender page handler, loads the JS and calls the regular group page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function group_extender_page_handler($page) {
		// Load extender JS		
		elgg_load_js('elgg.groupextender.tabs');		
		
		// Load tab CSS
		elgg_load_css('elgg.groupextender.tabs');
		
		// Load up tinymce
		elgg_load_js('tinymce');
		elgg_load_js('elgg.tinymce');
		
		// Load tgsembed JS/CSS
		if (elgg_is_active_plugin('tgsembed')) {
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
 * Customize entity menu and add group extender actions
 */
function group_extender_setup_entity_menu($hook, $type, $return, $params) {
	$entity = $params['entity'];
	
	// Ignore these subtypes for move/copy
	$exceptions = array(
		'forum',
		'forum_topic',
		'forum_reply',
		'image',
		'announcement',
		'book',
		'page', // Only parent pages can be moved to group
	);

	// Check to make sure we can move/copy this entity
	if (elgg_instanceof($entity, 'object') && !in_array($entity->getSubtype(), $exceptions) && (elgg_is_admin_logged_in() || $entity->owner_guid == elgg_get_logged_in_user_guid())) {	
		
		// Determine which entity subtypes can be copied
		$params = array('entity' => $entity);
		$copy_subtypes = elgg_trigger_plugin_hook('cangroupcopy', 'entity', $params, array());	
		
		if (elgg_instanceof($entity->getContainerEntity(), 'group')) {
			$move_text = elgg_echo('group-extender:label:movetoanothergroup');
		} else {
			$move_text = elgg_echo('group-extender:label:movetogroup');
		}
		
		// Move menu item	
		$options = array(
			'name' => 'move_to_group',
			'text' => $move_text,
			'title' => $move_text,
			'href' => elgg_get_site_url() . 'ajax/view/group-extender/popup/move?guid=' . $entity->guid,
			'class' => 'ge-move-to-group',
			'link_class' => 'group-extender-move-copy-lightbox',
			'section' => 'actions',
			'priority' => 800,
			'id' => "ge-move-to-group-{$entity->guid}",
		);
		$return[] = ElggMenuItem::factory($options);
		
		if (in_array($entity->getSubtype(), $copy_subtypes)) {
			// Entity if allowed to copy, so add copy menu item
			$options = array(
				'name' => 'copy_to_group',
				'text' => elgg_echo('group-extender:label:copytogroup'),
				'title' => elgg_echo('group-extender:label:copytogroup'),
				'href' => elgg_get_site_url() . 'ajax/view/group-extender/popup/copy?guid=' . $entity->guid,
				'class' => 'ge-copy-to-group',
				'link_class' => 'group-extender-move-copy-lightbox',
				'section' => 'actions',
				'priority' => 800,
				'id' => "ge-copy-to-group-{$entity->guid}",
			);
			$return[] = ElggMenuItem::factory($options);
		}
	} else if (elgg_instanceof($entity, 'group')) {
		// Modify menu for archived groups
		if ($entity->archived) {
			foreach ($return as $idx => $item) {
				if ($item->getName() == 'membership') {
					$item->setText(elgg_echo('group-extender:label:archivedgroup'));
				}

				// Unset members and feature items
				if ($item->getName() == 'members' || $item->getName() == 'feature') {
					unset($return[$idx]);
				}
			}
		}
	}
	
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

/**
 * Hook into title menu to modify group buttons
 */
function group_extender_menu_title_handler($hook, $type, $return, $params) {	
	$page_owner = elgg_get_page_owner_entity();
	$user = elgg_get_logged_in_user_entity();

	if (!empty($return) && is_array($return)){
		if (elgg_in_context("groups") && elgg_instanceof($page_owner, 'group') && $page_owner->archived) {
			// Only hide menu items if not an admin
			if (!elgg_is_admin_logged_in()) {
				foreach ($return as $idx => $item) {
					if (in_array($item->getName(), array('groups:invite', 'groups:join', 'groups:edit', 'groups:leave'))) {
						unset($return[$idx]);
					}
				}
			}

			// Add 'archived' to menu
			$options = array(
				'name' => 'archived',
				'text' => elgg_echo('group-extender:label:archived'),
				'href' => FALSE,
				'priority' => 0,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}
	
	return $return;
}

// Hook into group routing to provide extra content
function group_extender_route_groups_handler($hook, $type, $return, $params) {
	// Add filter extend context
	if (is_array($return['segments']) && $return['segments'][0] == 'all') {
		set_input('groups_all_filter_extend', true); 
	}

	// Make categories the default
	if (is_array($return['segments']) && $return['segments'][0] == 'all' && !get_input('filter')) {
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
 * Set up the group hover menu
 */
function group_extender_hover_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_admin_logged_in()) {
		
		$group = $params['entity'];
		
		// General catgory options
		$options = array(
			'types' => 'object',
			'subtypes' => 'group_category',
			'limit' => 0,
		);

		// Group category relationship
		$relationship = GROUP_CATEGORY_RELATIONSHIP;
		$db_prefix = elgg_get_config('dbprefix');

		// SQL to get categories this group DOESN'T belong too
		$options['wheres'][] = "NOT EXISTS (
					SELECT 1 FROM {$db_prefix}entity_relationships
						WHERE guid_one = '$group->guid'
						AND relationship = '$relationship'
						AND guid_two = e.guid
				)";

		$access_status = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$categories = elgg_get_entities($options);
	
		// Add 'add to category' menu items
		foreach ($categories as $category) {
			// Skip archived if configured
			if ($category->guid == (int)elgg_get_plugin_setting('archive_category', 'group-extender')) {
				continue;
			}

			$options = array(
				'name' => 'add_to_category_' . $category->guid,
				'text' => elgg_echo('group-extender:label:addtocategory', array($category->title)),
				'data-group_guid' => $group->guid,
				'data-category_guid' => $category->guid,
				'section' => 'admin',
				'class' => 'group-category-add-hover-menu-item',
				'priority' => 200,
			);
			$return[] = ElggMenuItem::factory($options);
		}
		
		$options['wheres'] = NULL;
		$options['relationship'] = GROUP_CATEGORY_RELATIONSHIP;
		$options['relationship_guid'] = $group->guid;
		$options['inverse_relationship'] = FALSE;
		
		$categories = elgg_get_entities_from_relationship($options);
		
		// Add 'remove from category' menu items
		foreach ($categories as $category) {
			// Skip archived if configured
			if ($category->guid == (int)elgg_get_plugin_setting('archive_category', 'group-extender')) {
				continue;
			}

			$options = array(
				'name' => 'remove_from_category_' . $category->guid,
				'text' => elgg_echo('group-extender:label:removefromcategory', array($category->title)),
				'data-group_guid' => $group->guid,
				'data-category_guid' => $category->guid,
				'section' => 'admin',
				'class' => 'group-category-remove-hover-menu-item',
				'priority' => 200,
			);
			$return[] = ElggMenuItem::factory($options);
		}
		
		access_show_hidden_entities($access_status);

		// Show Archive item if configured
		if ((int)elgg_get_plugin_setting('archive_category', 'group-extender')) {
			if (!$group->archived) {
				$options = array(
					'name' => 'archive_group',
					'text' => elgg_echo('group-extender:label:archivegroup', array($category->title)),
					'href' => '#' . $group->guid,
					'section' => 'admin',
					'priority' => 100,
				);
				$return[] = ElggMenuItem::factory($options);
			} else {
				$options = array(
					'name' => 'unarchive_group',
					'text' => elgg_echo('group-extender:label:unarchivegroup', array($category->title)),
					'href' => '#' . $group->guid,
					'section' => 'admin',
					'priority' => 105,
				);
				$return[] = ElggMenuItem::factory($options);
			}
		}
	}
	
	return $return;
}

/**
 * Set up the groups class/other filter menu
 */
function group_extender_class_filter_menu_setup($hook, $type, $return, $params) {
	$options = array(
		'name' => 'class_groups',
		'text' => elgg_echo('group-extender:label:classgroups'),
		'href' => '#class-groups',
		'priority' => 1,
		'class' => 'groups-class-filter-menu-item',
		'selected' => TRUE,
	);
	$return[] = ElggMenuItem::factory($options);

	$options = array(
		'name' => 'other_groups',
		'text' => elgg_echo('group-extender:label:othergroups'),
		'href' => '#other-groups',
		'priority' => 2,
		'class' => 'groups-class-filter-menu-item',
	);
	
	$return[] = ElggMenuItem::factory($options);
	
	return $return;
}

/**
 * Set up intitial (core) group copyable subtypes
 */
function group_extender_can_group_copy_handler($hook, $type, $return, $params) {
	// Core subtypes
	$return[] = 'blog';
	$return[] = 'bookmarks';

	return $return;
}

/**
 * Set up intitial (core) group copyable subtypes
 */
function group_extender_group_copy_handler($hook, $type, $return, $params) {
	$new_entity = $params['new_entity'];
	
	if ($new_entity->getSubtype() == 'page_top') {
		//$original_page = $params['entity'];
		//group_extender_clone_sub_pages_recursive($original_page->guid, $new_entity->guid, $new_entity->container_guid);
		//$new_entity->delete();
		//return false;
	}

	return $return;
}

/**
 * Handle extra tasks after moving core entities
 */
function group_extender_group_move_handler($hook, $type, $return, $params) {
	$entity = $params['entity'];
	
	// Need to move all page subpages
	if ($entity->getSubtype() == 'page_top') {
		return group_extender_move_sub_pages_recursive($entity);
	}

	return $return;
}

/**
 * Set up groups topbar items
 */
function group_extender_topbar_menu_setup($hook, $type, $return, $params) {
	// Link to my groups
	$href = elgg_get_site_url() . 'groups/member/' . elgg_get_logged_in_user_entity()->username;

	// Add 'groups' topbar menu
	$text = "<span class='elgg-icon elgg-icon-users'></span>" . elgg_echo("group-extender:label:mygroups");	
	$text .= elgg_view('group-extender/groupshover');

	// Add todo item
	$options = array(
		'name' => 'groups_topbar_hover_menu',
		'href' => $href,
		'text' => $text,
		'priority' => 2000,
		'title' => elgg_echo("groups")
		//'title' => elgg_echo("group-extender:label:mygroups"),
	);
	$return[] = ElggMenuItem::factory($options);
	
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
	$return['group-extender/tabs/static'] = elgg_echo('static_tab');

	return $return;
}