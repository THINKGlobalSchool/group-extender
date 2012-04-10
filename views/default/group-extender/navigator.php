<?php
/**
 * Group-Extender Group Navigator Sidebar
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group = elgg_get_page_owner_entity();

if (!elgg_instanceof($group, 'group')) {
	return TRUE;
}

// Get logged in user groups
$groups = elgg_get_entities_from_relationship_count(array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => elgg_get_logged_in_user_guid(),
	'inverse_relationship' => FALSE,
	'full_view' => FALSE,
));

if (count($groups)) {
	$groups_array = array();

	// Add each group to group array for dropdown
	foreach ($groups as $g) {
		$groups_array[$g->getURL()] = $g->name;
	}

	$navigator_content = elgg_view('input/dropdown', array(
		'name' => 'group_navigator_select',
		'id' => 'group-navigator-select',
		'value' => $group->getURL(),
		'options_values' => $groups_array,
	));
} else {
	$navigator_content = elgg_view('output/url', array(
		'text' => elgg_echo('group-extender:nogroups'), 
		'href' => elgg_get_site_url() . 'groups/all',
	));
}

$navigator_label = elgg_echo('group-extender:navigator');

echo elgg_view_module('aside', $navigator_label, $navigator_content);