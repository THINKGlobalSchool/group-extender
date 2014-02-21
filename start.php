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
 *   * group/default
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

	// Extend owner block navigation menu
	elgg_extend_view('navigation/menu/owner_block', 'group-extender/group_tabs_menu', 499);

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

	// Hook into page menu
	elgg_register_plugin_hook_handler('prepare', 'menu:page', 'group_extender_page_menu_handler');
	elgg_register_plugin_hook_handler('register', 'menu:page', 'group_extender_register_page_menu_handler');

	// extend groups page handler
	elgg_register_plugin_hook_handler('route', 'groups', 'group_extender_route_groups_handler', 100);
	
	// Set up group admin hover menu
	elgg_register_plugin_hook_handler('register', 'menu:group_hover', 'group_extender_hover_menu_setup');

	// Modify todo dashboard menu
	if (elgg_is_active_plugin('todo')) {
		elgg_register_plugin_hook_handler('register', 'menu:todo_dashboard', 'group_extender_todo_dashboard_menu_setup');
		elgg_register_plugin_hook_handler('get_options', 'todo', 'group_extender_todo_get_options_handler');
	}

	// Register a handler for core subtype's group move functionality
	elgg_register_plugin_hook_handler('groupmove', 'entity', 'group_extender_group_move_handler');
	
	if (elgg_is_logged_in() && (int)elgg_get_plugin_setting('enable_topbar_dropdown', 'group-extender')) {
		elgg_register_plugin_hook_handler('register', 'menu:topbar', 'group_extender_topbar_menu_setup', 9001);
	}

	// Hook into search improved results for groups
	elgg_register_plugin_hook_handler('searchimproved_results', 'groups', 'group_extender_searchimproved_results_hook');

	// Define core save forms
	$core_save_forms = array(
		'forms/bookmarks/save',
		'forms/blog/save',
		// 'forms/file/upload',
		// 'forms/pages/edit'
	);

	// Hook into core plugin save forms
	foreach ($core_save_forms as $form) {
		elgg_register_plugin_hook_handler('view', $form, 'group_extender_group_picker_handler');
	}

	// Unregister groups pagesetup event handler
	elgg_unregister_event_handler('pagesetup', 'system', 'groups_setup_sidebar_menus');

	// Register new pagesetup event handler
	elgg_register_event_handler('pagesetup', 'system', 'group_extender_setup_sidebar_menus');

	// Modify group owner block menu
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'group_extender_owner_block_menu');

	// Tab actions
	$action_base = elgg_get_plugins_path() . 'group-extender/actions/group-extender';
	elgg_register_action("groupextender/save_tab", "$action_base/save_tab.php");
	elgg_register_action("groupextender/delete_tab", "$action_base/delete_tab.php");
	elgg_register_action("groupextender/move_tab", "$action_base/move_tab.php");
	elgg_register_action("group_dashboard/dashboard", "$action_base/dashboard.php");
	elgg_register_action("groups/homepage", "$action_base/homepage.php");

	// Manage content action
	//elgg_register_action("group-extender/manage_content", "$action_base/manage_content.php", 'admin');

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
	elgg_register_ajax_view('group-extender/group_tabs_menu');
	elgg_register_ajax_view('group-extender/group_tabs_content');
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
	elgg_set_view_location('groups/sidebar/members', elgg_get_plugins_path() . "group-extender/overrides/");	
}

/**
 * Group extender page handler, loads the JS and calls the regular group page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function group_extender_page_handler($page) {
		// Load up tinymce
		elgg_load_js('tinymce');
		elgg_load_js('elgg.tinymce');
	
		// Load tab CSS
		elgg_load_css('elgg.groupextender.tabs');	

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
		} else if ($page[0] == 'members') {
			group_extender_handle_members_page($page[1]);
		} else {
			$hide_owner_block = array(
				'member',
				'owner',
				'invitations'
			);
			if (in_array($page[0], $hide_owner_block)) {
				set_input('owner_block_force_hidden', 1);
			}

			if ($page[0] == 'profile') {
				// Load extender JS		
				elgg_load_js('elgg.groupextender.tabs');
			}

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
			'text' => elgg_echo('group-extender:label:browse'),
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

		// Add group_tools mail members button to actions
		if (elgg_in_context("groups") && elgg_is_active_plugin('group_tools') && $page_owner->canEdit()) {
			$options = array(
				'name' => 'mail',
				'text' => elgg_echo('group_tools:menu:mail'),
				'href' => "groups/mail/" . $page_owner->getGUID(),
				'priority' => 1,
				'class' => 'elgg-button elgg-button-action',
			);

			$return[] = ElggMenuItem::factory($options);
		}
	}
	
	return $return;
}

/**
 * Hook into page menu to fix selected issues
 */
function group_extender_page_menu_handler($hook, $type, $return, $params) {	
	if (elgg_get_context() == 'groups') {
		foreach ($return['default'] as $item) {
			if ($item->getName() == 'groups:all') {
				if (strstr(full_url(),'groups/all')) {
					$item->setSelected(true);
				}
			}
		}
	}

	return $return;
}

/**
 * Hook into page menu to modify items
 */
function group_extender_register_page_menu_handler($hook, $type, $return, $params) {
	foreach ($return as $idx => $item) {
		// Remove mail members from page
		if ($item->getName() == 'mail') {
			unset($return[$idx]);
		}
	}

	$page_owner = elgg_get_page_owner_entity();

	if (elgg_instanceof($page_owner, 'group') && $page_owner->canEdit()) {

		$options = array(
			'name' => 'admin_edit_nav',
			'text' => elgg_echo('group-extender:tab:admin'),
			'href' => "#groupextender-tab-admin",
			'class' => 'group-extender-customize-nav-link',
			'id' => 'admin',
			'data-group_url' => $page_owner->getURL()

		);
		$return[] = ElggMenuItem::factory($options);

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
 * Add group extender options to todo dashboard menu
 */
function group_extender_todo_dashboard_menu_setup($hook, $type, $return, $params) {
	$page_owner = elgg_get_page_owner_entity();

	// Add a group category dropwdown for todo admins
	if (is_todo_admin() || elgg_is_admin_logged_in()) {
		// Only show category when not viewing a group
		if (!elgg_instanceof($page_owner, 'group')) {
			$category = get_input('category');

			// Get all site categories
			$category_entities = elgg_get_entities(array(
				'type' => 'object',
				'subtype' => 'group_category',
				'limit' => 0,
			));

			$categories = array();

			if (count($category_entities) >= 1) {
				$categories[0] = '';

				foreach($category_entities as $category) {
					$categories[$category->guid] = $category->title;
				}
			} else {
				$categories[''] = elgg_echo('group-extender:label:nocategories');
			}

			$category_filter_input = elgg_view('input/chosen_dropdown', array(
				'id' => 'todo-group-categories-filter',
				'options_values' => $categories,
				'value' => $category,
				'class' => 'todo-dashboard-filter',
				'data-param' => 'category',
				'data-disables' => '["#todo-group-filter"]',
				'data-placeholder' => elgg_echo('group-extender:label:categoryselect')
			));

			$options = array(
				'name' => 'group-categories-filter',
				'href' => false,
				'label' => elgg_echo('group-extender:label:groupcategory'),
				'text' => $category_filter_input,
				'encode_text' => false,
				'section' => 'advanced',
				'priority' => 500
			);

			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Allow getting todos by group category
 */
function group_extender_todo_get_options_handler($hook, $type, $return, $params) {
	$category_guid = get_input('category', false);

	$category = get_entity($category_guid);

	// If we have a category
	if (elgg_instanceof($category, 'object', 'group_category')) {
		// Clear the container guid option
		unset($return['container_guid']);

		// Get
		$groups = groupcategories_get_groups($category, 0);

		$group_guids = array();

		foreach ($groups as $group) {
			$group_guids[] = $group->guid;
		}

		$return['container_guids'] = $group_guids;

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

	// Group member params
	$options = array(
		'type' => 'group',
		'relationship' => 'member',
		'relationship_guid' => elgg_get_logged_in_user_guid(),
		'inverse_relationship' => FALSE,
		'full_view' => FALSE,
		'pagination' => FALSE,
		'limit' => 0,
	);

	$groups = elgg_get_entities_from_relationship($options);

	// If user has no groups, bail
	if (!count($groups)) {
		return $return;
	}

	foreach ($groups as $group) {
		$icon = elgg_view_entity_icon($group, 'tiny', array('hide_menu' => true));
		
		$icon_url = get_entity_icon_url($group, 'tiny');

		$params = array(
			'entity' => $group,
			'metadata' => '',
			//'subtitle' => $group->briefdescription,
		);
	
		$list_body = elgg_view('group/elements/summary', $params);
		
		$group_url = $group->getURL();

		elgg_register_menu_item('groups_topbar', array(
			'name' => elgg_get_friendly_title($group->name) . "_{$group->guid}",
			'href' => $group->getURL(),
			'text' => "<div><img src='{$icon_url}'><span>{$group->name}</span></img></div>",
		));

		//$group_content .= "<li onclick='javascript:window.location.href=\"$group_url\";return false;' class='groups-hover-pointer'>" . elgg_view_image_block($icon, $list_body, $vars) . "</li>";
	}

	global $CONFIG;

	$groups_menu = $CONFIG->menus['groups_topbar'];

	// Use ElggMenuBuilder to sort menu alphabetically
	$builder = new ElggMenuBuilder($groups_menu);
	$groups_menu = $builder->getMenu('name');

	$text = elgg_echo("group-extender:label:mygroups");
	$groups_link = "<a href=\"#\" class='tgstheme-topbar-dropdown'>$text</a>";

	$groups_item = ElggMenuItem::factory(array(
		'name' => 'my_groups',
		'href' => false,
		'text' => $groups_link . elgg_view('navigation/menu/elements/section', array(
			'class' => 'elgg-menu elgg-menu-topbar-dropdown',
			'items' => $groups_menu['default'],
		)), 
		'priority' => 99998,
	));

	$return[] = $groups_item;

	return $return;
}

/**
 * Set up search improved results
 */
function group_extender_searchimproved_results_hook($hook, $type, $return, $params) {
	$dbprefix = elgg_get_config('dbprefix');
	$name_metastring_id = get_metastring_id('archived');
	if (!$name_metastring_id) {
		return $return;
	}

	$return['wheres'] = "NOT EXISTS (
		SELECT 1 FROM {$dbprefix}metadata md
		WHERE md.entity_guid = e.guid
		AND md.name_id = $name_metastring_id)";
	
	return $return;
}

/**
 * Hook into core save forms to add group picker
 */
function group_extender_group_picker_handler($hook, $type, $return, $params) {
	$foot_pos = strpos($return, '<div class="elgg-foot">');
	return substr_replace($return, elgg_view('forms/group-extender/group_picker'), $foot_pos, 0);
}

/**
 * Setup Group Extender Submenus
 */
function group_extender_submenus() {
	if (elgg_in_context('admin')) {
		elgg_register_admin_menu_item('administer', 'categories', 'groupextender');
		elgg_register_admin_menu_item('administer', 'dashboard', 'groupextender');
		elgg_register_admin_menu_item('administer', 'content', 'groupextender');
	}
	
	// Display group dashboard sidebar menu item
	$page_owner = elgg_get_page_owner_entity();

	if (elgg_is_logged_in() && elgg_get_context() == 'groups' && !elgg_instanceof($page_owner, 'group') && elgg_get_plugin_setting('enable_dashboard', 'group-extender')) {
		$url =  "groups/dashboard";
		$item = new ElggMenuItem('zz-groupdashboard', elgg_echo('group-extender:title:groupdashboard'), $url);
		//elgg_register_menu_item('page', $item);
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

/**
 * Configure the groups sidebar menu. Triggered on page setup
 *
 */
function group_extender_setup_sidebar_menus() {

	// Get the page owner entity
	$page_owner = elgg_get_page_owner_entity();

	if (elgg_in_context('group_profile')) {
		if (elgg_is_logged_in() && $page_owner->canEdit() && !$page_owner->isPublicMembership()) {
			$url = elgg_get_site_url() . "groups/requests/{$page_owner->getGUID()}";

			$count = elgg_get_entities_from_relationship(array(
				'type' => 'user',
				'relationship' => 'membership_request',
				'relationship_guid' => $page_owner->getGUID(),
				'inverse_relationship' => true,
				'count' => true,
			));

			if ($count) {
				$text = elgg_echo('groups:membershiprequests:pending', array($count));
			} else {
				$text = elgg_echo('groups:membershiprequests');
			}

			elgg_register_menu_item('page', array(
				'name' => 'membership_requests',
				'text' => $text,
				'href' => $url,
			));
		}
	}
}

/**
 * Modify group owner block menu
 */
function group_extender_owner_block_menu($hook, $type, $return, $params) {
	foreach ($return as $idx => $item) {
		// Remove activity item
		if ($item->getName() == 'activity') {
			unset($return[$idx]);
		}
	}
	return $return;
}