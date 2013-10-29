<?php
/**
 * Group-Extender Groups Homepage Module Replacement
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$user = elgg_get_logged_in_user_entity();

// Get db prefix for custom joins
$db_prefix = elgg_get_config('dbprefix');

$limit = elgg_extract('limit', $vars, 10);
$offset = elgg_extract('offset', $vars, 0);

$params = array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $user->guid,
	'inverse_relationship' => FALSE,
	'full_view' => FALSE,
	'group_by' => 'ec.container_guid',
	'order_by' => 'ec.time_updated DESC',
	'limit' => $limit,
	'offset' => $offset,
);

// Need to throw in a new select as well
$params['selects'][] = 'ec.time_updated';

// This is the magic join that grabs the entities of a group ordered by time_updated
$params['joins'][] = "JOIN (
	SELECT DISTINCT xyz.container_guid, xyz.time_updated
	FROM {$db_prefix}entities xyz
	ORDER BY xyz.time_updated DESC
) ec on ec.container_guid = e.guid";



// Category relationship
$class_category_guid = elgg_get_plugin_setting('class_category', 'group-extender');
$relationship = GROUP_CATEGORY_RELATIONSHIP;

// SQL to determine if this group is NOT in classes category
$params['wheres'][] = "NOT EXISTS (
			SELECT 1 FROM {$db_prefix}entity_relationships
				WHERE guid_one = e.guid
				AND relationship = '$relationship'
				AND guid_two = '$class_category_guid'
)";

$other_groups = elgg_get_entities_from_relationship($params);

// Get class groups
$params['wheres'] = NULL;

// SQL to determine if this class IS in the class category
$params['wheres'][] = "EXISTS (
			SELECT 1 FROM {$db_prefix}entity_relationships
				WHERE guid_one = e.guid
				AND relationship = '$relationship'
				AND guid_two = '$class_category_guid'
)";

$class_groups = elgg_get_entities_from_relationship($params);

// Build 'other' groups content
foreach($other_groups as $group) {
	$other_content .= elgg_view('tgstheme/group_listing', array('group' => $group));
}

// Build 'class' groups content
foreach($class_groups as $group) {
	$class_content .= elgg_view('tgstheme/group_listing', array('group' => $group));
}

// No results content
$no_results = "<h3 class='center'>" . elgg_echo('group-extender:label:noresults') . "</h3>"; 

if (!$class_content) {
	$class_content = $no_results;
}

if (!$other_content) {
	$other_content = $no_results;
}

$hidden_style = "style='display: none;'";

// Only show tabs if there are class groups
if (count($class_groups)) {
	// Main content
	$content = elgg_view_menu('groups_class_other_menu', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default'
	));
	$hide_other_groups = $hidden_style;
} else {
	$hide_class_groups = $hidden_style;
}

$content .= "<div id='other-groups' class='groups-class-filter-container' $hide_other_groups>$other_content</div>";
$content .= "<div id='class-groups' class='groups-class-filter-container' $hide_class_groups>$class_content</div>";

echo $content;