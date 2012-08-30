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

	// If no tabs are set, include default activity
	if (!$group_tabs) {
		$group_tabs = array(
			uniqid() => array(
				'title' => elgg_echo('groups:activity'),
				'type' => 'activity',
				'priority' => 1,
			)
		);
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
		'activity' => elgg_echo('group-extender:tab:activity'), 
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

	$params = array(
		'title' => $title,
		'content' => $content,
		'sidebar' => elgg_view('groups/sidebar/find') . elgg_view('groups/sidebar/featured'),
	);

	$body = elgg_view_layout('one_sidebar', $params);

	echo elgg_view_page($title, $body);
}