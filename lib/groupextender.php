<?php
/**
 * Group-Extender Helper Library
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

/**
 * Get group tabs
 * 
 * @param ElggEntity $group
 * @return array 
 */
function group_extender_get_tabs($group) {
	$group_tabs = unserialize($group->custom_tabs);

	if ($group->new_layout) {
		$activity_tab_title = elgg_echo('group-extender:label:about');
	} else {
		$activity_tab_title = elgg_echo('group-extender:label:activity');
	}


	$activity_tab = array(
		'title' => $activity_tab_title,
		'type' => 'activity',
		'priority' => 0
	);

	// If no tabs are set, include default activity
	if (!$group_tabs) {
		$group_tabs['activity-default'] = $activity_tab;
	} else {
		// We've got tabs, make sure there's only one activity/about tab
		foreach ($group_tabs as $idx => $tab) {
			if ($tab['type'] == 'activity') {
				unset($group_tabs[$idx]);
			}
		}

		$group_tabs['activity-default'] = $activity_tab;
	}

	uasort($group_tabs, 'group_extender_compare_tabs');

	return $group_tabs;
}

/**
 * Helper function to grab a specific tab
 * 
 * @param ElggEntity $group  Group to grab tags from
 * @param string     $tab_id Tab to grab
 * @return mixed
 */
function group_extender_get_tab_by_id($group, $tab_id) {
	$current_tabs = group_extender_get_tabs($group);
	return $current_tabs[$tab_id];
}

/**
 * Helper function to add a new group custom tab
 * 
 * @param ElggEntity $group Group to add tab to
 * @param array      $tab   Tab to add
 * @return string uid
 */
function group_extender_add_tab($group, $tab) {
	$current_tabs = group_extender_get_tabs($group);
	
	$uid = uniqid();
	
	$current_tabs[$uid] = $tab;

	$group->custom_tabs = serialize($current_tabs);

	return $uid;
}

/**
 * Helper function update an existing group tab
 *
 * @param ElggEntity $group  Group to grab tabs from
 * @param string     $tab_id Tab id to update
 * @param array      $tab    Tab content array
 * @return bool
 */
function group_extender_update_tab($group, $tab_id, $tab) {
	$current_tabs = group_extender_get_tabs($group);
	
	if (!$current_tabs[$tab_id]) {
		return FALSE; // Non existant tab
	}
	
	$current_tabs[$tab_id] = $tab;
	
	$group->custom_tabs = serialize($current_tabs);

	return TRUE;
}

/**
 * Helper function to add a new group custom tab
 * 
 * @param ElggEntity $group Group to add tab to
 * @param string     $tab   Tab ID
 */
function group_extender_remove_tab($group, $tab_id) {
	$current_tabs = group_extender_get_tabs($group);

	if ($current_tabs[$tab_id]['type'] == 'static') {
		elgg_delete_metadata(array(
			'guid' => $group->guid,
			'metadata_name' => $current_tabs[$tab_id]['params']['static_content_meta'],
		));
	}

	unset($current_tabs[$tab_id]);
	
	group_extender_reprioritize_tabs($current_tabs);
	
	$group->custom_tabs = serialize($current_tabs);
}

/**
 * Helper function to grab the lowest priority set in tabs
 *
 * @param ElggEntity $group Which to group to grab tabs for
 */
function group_extender_get_lowest_tab_priority($group) {
	$current_tabs = group_extender_get_tabs($group);
	uasort($current_tabs, 'group_extender_compare_tabs');
	$first = array_shift($current_tabs);
	return $first['priority'];
}

/**
 * Helper function to grab the highest priority set in tabs
 *
 * @param ElggEntity $group Which to group to grab tabs for
 */
function group_extender_get_highest_tab_priority($group) {
	$current_tabs = group_extender_get_tabs($group);
	uasort($current_tabs, 'group_extender_compare_tabs');
	$last = array_pop($current_tabs);
	return $last['priority'];
}

/**
 * Callback for usort (sort tabs by priority)
 *
 * @param int $a
 * @param int $b
 */
function group_extender_compare_tabs($a, $b) {
	if ($a['priority'] == $b['priority']) {
		return 0;
	}
	return ($a['priority'] < $b['priority']) ? -1 : 1;
}

/**
 * Helper function re-prioritize tabs
 *
 * @param array &$tabs
 */
function group_extender_reprioritize_tabs(&$tabs) {
	$i = 1;
	foreach ($tabs as $uid => $tab) {
		$tabs[$uid]['priority'] = $i;
		$i++;
	}
}

/**
 * Helper function to change the priority of a tab
 */
function group_extender_change_tab_priority($group, $tab_id, $priority) {
	$current_tabs = group_extender_get_tabs($group);
	$change_tab = $current_tabs[$tab_id];

	$priority = (int)$priority;

	foreach ($current_tabs as $uid => $tab) {
		if ($tab['priority'] == $priority) {
			// Swap priority
			$current_tabs[$uid]['priority'] = $change_tab['priority'];
		}
		if ($uid == $tab_id) {
			// Set new priority
			$current_tabs[$tab_id]['priority'] = $priority;
		}
	} 

	$group->custom_tabs = serialize($current_tabs);
}

/**
 * Get available tab types
 * 
 * @return array
 */
function group_extender_get_tab_types() {
	// Default types
	$types = array(
		//'activity' => elgg_echo('group-extender:tab:activity'), 
		'subtype' => elgg_echo('group-extender:tab:subtype'),
		'static' => elgg_echo('group-extender:tab:static')
	);

	// Include google custom search if enabled
	if (elgg_is_active_plugin('googlesearch')) {
		$types['customsearch'] = elgg_echo('group-extender:tab:customsearch');
	}
	
	// Include tagdashboards if enabled
	if (elgg_is_active_plugin('tagdashboards')) {
		$types['tagdashboard'] = elgg_echo('group-extender:tab:tagdashboard');
	}

	// Include tagdashboards if enabled
	if (elgg_is_active_plugin('rss')) {
		$types['rss'] = elgg_echo('group-extender:tab:rss');
	}
	
	return $types;
}

/**
 * Group extender get enabled group tool subtypes
 * 
 * @param ElggEntity $group Which to group to grab subtypes from
 * @return Array
 */
function group_extender_get_group_subtypes($group) {
	// @TODO This isn't cool at all.. for the same reason we have an admin area for tagdashboards
	// theres no way to get a list of entity subtypes that are enabled for a group..

	// Set up some exceptions @TODO change this to whitelist
	$exceptions = array(
		'achievement',
		'custommenu',
		'custommenu_item',
		'embedimage',
		'forum_reply',
		'forum_topic',
		'google_cal',
		'groupforumtopic',
		'admin_notice',
		'book_review',
		'connected_blog_activity',
		'launchpad_item',
		'launchpad_item_icon',
		'messages',
		'role',
		'school',
		'site_activity',
		'submissionannotationfile',
		'ta_sticky_note',
		'thewire',
		'batch',
		'tidypics_batch',
		'todosubmission',
		'todosubmissionfile',
		'videolist',
		'plugin', 
		'widget', 
		'sitepages_page', 
		'page', 
		'test_subtype',
		'site',
		'sitemessage',
		'ubertag',
		'shared_access',
		'kaltura_video',
		'resourcerequest',
		'resourcerequesttype',
		'reportcard_import_container',
		'reportcardfile',
		'feedback', 
		'group_category',
	);

	// Allow exceptions to be modified
	$exceptions = elgg_trigger_plugin_hook('grouptabsubtype','exceptions', array(), $exceptions);

	// Query to grab subtypes
	$query = "SELECT subtype FROM elgg_entity_subtypes WHERE type = 'object';";

	// Execute
	$subtypes = get_data($query, 'group_extender_get_group_subtypes_callback');

	// Filter exceptions	
	$filtered_subtypes = array_diff($subtypes, $exceptions);
	
	$options_array = array();
	
	foreach ($filtered_subtypes as $subtype) {
		$options_array[$subtype] = elgg_echo("item:object:{$subtype}");
	}

	return $options_array;
}

/**
 *  Callback to handle results from the group_extender_get_group_subtypes() query 
 *  turns the result from a stdClass to a string
 */
function group_extender_get_group_subtypes_callback($data) {
	return $data->subtype;
}

/**
 * Get group name search content
 */
function group_extender_get_name_search() {
	$name = sanitize_string(get_input('name'));

	$title = elgg_echo('group-extender:search:name', array($name));

	$db_prefix = elgg_get_config('dbprefix');
	$params = array(
		'type' => 'group',
		'full_view' => false,
	);

	$params['joins'] = array("JOIN {$db_prefix}groups_entity oe ON e.guid= oe.guid");
	$params['wheres'] = array("oe.name LIKE \"%{$name}%\"");

	$content = elgg_list_entities($params);
	
	if (!$content) {
		$content = elgg_echo('groups:search:none');
	}

	$params = array(
		'title' => $title,
		'content' => $content,
		'sidebar' => elgg_view('groups/sidebar/find') . elgg_view('groups/sidebar/featured'),
		'filter' => FALSE,
	);

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Helper function that recursively clones sub pages
 * NOTE: This is unused, and untested
 * 
 * @param int $parent_guid      The original parent guid
 * @param int $new_parent_guid  New parent guid
 * @param int $container_guid   New container guid (optional)
 * @return bool 
 */
/*
function group_extender_clone_sub_pages_recursive($parent_guid, $new_parent_guid, $container_guid = NULL) {
	// Get sub pages for given parent_guid
	$sub_pages = elgg_get_entities_from_metadata(array(
		'type' => 'object', 
		'subtype' => 'page',
		'limit' => 0,
		'metadata_name' => 'parent_guid',
		'metadata_value' => $parent_guid,
	));
	
	$return = TRUE;

	foreach ($sub_pages as $page) {
		$new_page = clone $page;
		
		// If we're setting a new container guid, update it
		if ($container_guid) {
			$new_page->container_guid = $container_guid;
		}
		
		// Save new page and trigger groupcopy hook
		$params = array('entity' => $page, 'new_entity' => $new_page);
		$return &= $new_page->save() && elgg_trigger_plugin_hook('groupcopy', 'entity', $params, TRUE);

		// All good? Recursively clone subpages
		if ($return) {
			group_extender_clone_sub_pages_recursive($page->guid, $new_page->guid, $container_guid);
		}
	}
	
	return $return;
}*/

/**
 * Helper function that recursively moves sub pages to a new container guid
 * 
 * @param ElggObject $parent Page parent entity
 * @return bool 
 */
function group_extender_move_sub_pages_recursive($parent) {	
	$parent_guid = $parent->guid;
	$container_guid = $parent->container_guid;

	// Get sub pages for given parent_guid
	$sub_pages = elgg_get_entities_from_metadata(array(
		'type' => 'object', 
		'subtype' => 'page',
		'limit' => 0,
		'metadata_name' => 'parent_guid',
		'metadata_value' => $parent_guid,
	));

	$return = TRUE;

	foreach ($sub_pages as $page) {	
		// Update access
		group_extender_update_moved_entity_access($page, $container_guid);

		$page->container_guid = $container_guid;
		
		// Save new page and trigger groupcopy hook
		$params = array('entity' => $page);
		$return &= $page->save() && elgg_trigger_plugin_hook('groupmove', 'entity', $params, TRUE);

		// All good? Recursively move subpages
		if ($return) {
			group_extender_move_sub_pages_recursive($page);
		}
	}

	return $return;
}

/**
 * Update an entities access level upon moveing to/from a group
 * 
 * @param ElggEntity the entity being moved
 * @param int        the guid we're moving to
 * @return bool
 */
function group_extender_update_moved_entity_access($entity, $new_container) {
	$core_access = array(
		ACCESS_LOGGED_IN,
		ACCESS_PUBLIC,
		ACCESS_PRIVATE,
		ACCESS_FRIENDS
	);

	$new_container = get_entity($new_container);

	// If the access level is something other than a core access level
	if (!in_array($entity->access_id, $core_access)) {
		// If we're moving to a gorup
		if (elgg_instanceof($new_container, 'group')) {
			// Set access level to that groups acl
			$entity->access_id = $new_container->group_acl;
		} else {
			// Moving back to entity, set to logged in
			$entity->access_id = ACCESS_LOGGED_IN;
		}
		$entity->save();
	}
}

/** Group Dashboard Content **/

function group_extender_get_dashboard() {
	elgg_set_context('groups');
	
	$params['title'] = elgg_echo('group-extender:title:groupdashboard');
	$params['content'] = elgg_view('group-extender/dashboard');
	
	$body = elgg_view_layout('one_sidebar', $params);

	echo elgg_view_page($params['title'], $body);
}

/** End Group Dashboard Content **/

/** Extended page handler stuff **/
/**
 * Group extender members page
 *
 * @param int $guid Group entity GUID
 */
function group_extender_handle_members_page($guid) {

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid);
	if (!$group || !elgg_instanceof($group, 'group')) {
		forward();
	}

	group_gatekeeper();

	$title = elgg_echo('groups:members:title', array($group->name));

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb(elgg_echo('groups:members'));

	$content = elgg_list_entities_from_relationship(array(
		'relationship' => 'member',
		'relationship_guid' => $group->guid,
		'inverse_relationship' => true,
		'type' => 'user',
		'limit' => 28,
	));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Group profile page
 *
 * @param int $guid Group entity GUID
 */
function group_extender_handle_profile_page($guid) {
	elgg_set_page_owner_guid($guid);

	// turn this into a core function
	global $autofeed;
	$autofeed = true;

	elgg_push_context('group_profile');

	$group = get_entity($guid);
	if (!elgg_instanceof($group, 'group')) {
		forward('', '404');
	}

	elgg_push_breadcrumb($group->name);

	//groups_register_profile_buttons($group);

	$content = elgg_view('groups/profile/layout', array('entity' => $group));
	$sidebar = '';

	// Group admin tools
	if ($group->canEdit()) {
		$sidebar .= elgg_view('groups/sidebar/admin', array(
			'entity' => $group,
			'subscribed' => $subscribed
		));
	}

	$sidebar .= elgg_view('groups/sidebar/my_status', array(
		'entity' => $group,
		'subscribed' => $subscribed
	));

	if (group_gatekeeper(false)) {
		$subscribed = false;
		if (elgg_is_active_plugin('notifications')) {
			global $NOTIFICATION_HANDLERS;

			foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
				$relationship = check_entity_relationship(elgg_get_logged_in_user_guid(),
						'notify' . $method, $guid);

				if ($relationship) {
					$subscribed = true;
					break;
				}
			}
		}

		$sidebar .= elgg_view('groups/sidebar/members', array('entity' => $group));

		if (elgg_is_active_plugin('search')) {
			$sidebar .= elgg_view('groups/sidebar/search', array('entity' => $group));
		}
	}

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'title' => $group->name,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($group->name, $body);
}

/**
 * Export group member list as a CSV
 */
function group_extender_handle_export($guid) {
	$group = get_entity($guid);
	if (!elgg_instanceof($group, 'group')) {
		register_error(elgg_echo('group-extender:error:invalidgroup'));
		forward(REFERER);
	}

	if (!$group->canEdit()) {
		register_error(elgg_echo('group-extender:error:accessdenied'));
		forward(REFERER);
	}

	$members = $group->getMembers(0);



	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename={$group->guid}_members.csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	echo "Name,Email\r\n";

	foreach ($members as $member) {
		$name = addslashes($member->name);
		$email = addslashes($member->email);
		echo "{$name},{$email}\r\n";
	}
}


/** End group content management **/


/** Group Categories Content **/

function groupcategories_get_edit_content($type, $guid = NULL) {	
	elgg_push_breadcrumb(elgg_echo('admin:groupextender:categories'), elgg_get_site_url() . 'admin/groupextender/categories');
	if ($type == 'edit') {

		$access_status = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		
		$category = get_entity($guid);
		elgg_push_breadcrumb($category->title, $category->getURL());
		elgg_push_breadcrumb(elgg_echo('edit'));
		if (!elgg_instanceof($category, 'object', 'group_category')) {
			forward(REFERER);
		}
	} else {
		elgg_push_breadcrumb(elgg_echo('Add'));
		$category = null;
	}
	
	$form_vars = groupcategories_prepare_form_vars($category);
	
	$content = elgg_view('navigation/breadcrumbs');
	
	$content .= elgg_view_form('group_category/save', array('name' => 'category-edit-form', 'id' => 'category-edit-form'), $form_vars);

	access_show_hidden_entities($access_status);

	echo $content;
}

/** End Group Categories Content **/

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $category
 * @return array
 */
function groupcategories_prepare_form_vars($category = NULL) {
	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'guid' => NULL,
		'enabled' => '',
		'order_priority' => '',
	);

	if ($category) {
		foreach (array_keys($values) as $field) {
			$values[$field] = $category->$field;
		}
	}

	if (elgg_is_sticky_form('category-edit-form')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('category-edit-form', $field);
		}
	}

	elgg_clear_sticky_form('category-edit-form');

	return $values;
}

/**
 * Get groups belonging to given category
 * 
 * @param ElggObject $category
 * @param int        $limit 
 * @param int        $offset
 * @param bool       $count
 * @return array
 */
function groupcategories_get_groups($category, $limit = 10, $offset = 0, $count = FALSE) {
	return elgg_get_entities_from_relationship(array(
		'relationship' => GROUP_CATEGORY_RELATIONSHIP,
		'relationship_guid' => $category->guid,
		'inverse_relationship' => TRUE,
		'types' => 'group',
		'limit' => $limit,
		'offset' => $offset,
		'count' => $count,
	));
}

/**
 * Determine if group is a member of the category
 * 
 * @param ElggObject $category
 * @param ElggGroup $group
 */
function groupcategories_is_group_member($category, $group) {
	$object = check_entity_relationship($group->guid, GROUP_CATEGORY_RELATIONSHIP, $category->guid);
	if ($object) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Add a group to given category
 *
 * @param ElggObject $category
 * @param ElggGroup $group
 */
function groupcategories_add_group($category, $group) {
	try {
		$result = add_entity_relationship($group->guid, GROUP_CATEGORY_RELATIONSHIP, $category->guid);
	} catch (DatabaseException $e) {
		$result = FALSE;
	}

	return $result;
}

/**
 * Remove a group from given category
 *
 * @param ElggObject $category
 * @param ElggGroup $group
 */
function groupcategories_remove_group($category, $group) {
	$result = remove_entity_relationship($group->guid, GROUP_CATEGORY_RELATIONSHIP, $category->guid);
	return $result;
}