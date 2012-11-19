<?php
/**
 * Group-Extender Class Groups Generic module view
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 *
 */

// Determine if we're showing classes, or other groups
if ($vars['filter'] == 'off') {
	$exists_filter = 'NOT EXISTS';
} else {
	$exists_filter = 'EXISTS';	
}

// Group member params
$params = array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $vars['guid'],
	'inverse_relationship' => FALSE,
	'full_view' => FALSE,
	'pagination' => TRUE,
);

// Get db prefix for custom joins
$db_prefix = elgg_get_config('dbprefix');

// Category relationship
$class_category_guid = elgg_get_plugin_setting('class_category', 'group-extender');
$relationship = GROUP_CATEGORY_RELATIONSHIP;

// SQL to determine if this class is/isn't in the class category
$params['wheres'][] = "$exists_filter (
			SELECT 1 FROM {$db_prefix}entity_relationships
				WHERE guid_one = e.guid
				AND relationship = '$relationship'
				AND guid_two = '$class_category_guid'
)";

$content = elgg_list_entities_from_relationship($params);

if (!$content) {
	echo "<h3 class='center'>" . elgg_echo('parentportal:label:noresults') . "</h3>"; 
} else {
	echo $content;
}