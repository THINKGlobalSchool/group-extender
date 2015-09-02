<?php
/**
 * Group-Extender list groups in category module
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = elgg_extract('guid', $vars, NULL);

// If we have an archived category, exclude those groups from the all listing
$archive_category = (int)elgg_get_plugin_setting('archive_category', 'group-extender');

// If we have a hidden category, exclude them from the all listing
$hidden_category = (int)elgg_get_plugin_setting('hidden_category', 'group-extender');

if ($hidden_category) {
	access_show_hidden_entities(true);
	$hidden_groups = groupcategories_get_groups(get_entity($hidden_category), 0);
	access_show_hidden_entities(false);

	$hidden_guids = array();
	foreach ($hidden_groups as $hidden) {
		$hidden_guids[] = $hidden->guid;
	}

	if (count($hidden_guids)) {
		$hidden_where = "e.guid NOT IN (" . implode(',', $hidden_guids) . ")";
	}
}

if ($archive_category) {
	global $CONFIG;

	// MD info for excluding archived
	$archived = elgg_get_metastring_id('archived');
	$one_id = elgg_get_metastring_id(1);

	if ($archived && $one_id) {
				$archived_options = "
	  			NOT EXISTS (
					SELECT 1 FROM {$CONFIG->dbprefix}metadata md
					WHERE md.entity_guid = e.guid
					AND md.name_id = $archived
					AND md.value_id = $one_id)";
	}
}

$base_url = "ajax/view/group-extender/modules/category_groups?t=1&guid={$guid}";

// Load All Groups
if ($guid == 'all' || $guid == 'mine' || $guid == 'owned') {
	$options = array(
		'types' => 'group',
		'full_view' => FALSE,
		'limit' => 15
	);

	// Add options for mine/owned
	if ($guid == 'mine') {
		$options['relationship'] = 'member';
		$options['relationship_guid'] = elgg_get_logged_in_user_guid();
		$options['inverse_relationship'] = false;
	} else if ($guid == 'owned') {
		$options['owner_guid'] = elgg_get_logged_in_user_guid();
	}

	if ($archived_options) {
		$options['wheres'][] = $archived_options;
	}

	$options['base_url'] = $base_url;
	$options['wheres'][] = $hidden_where;

	$content = elgg_list_entities_from_relationship($options);
} else {
	$category = get_entity($guid);

	if ($category) {
		$options = array(
			'relationship' => GROUP_CATEGORY_RELATIONSHIP,
			'relationship_guid' => $category->guid,
			'inverse_relationship' => TRUE,
			'types' => 'group',
			'full_view' => FALSE,
			'base_url' => $base_url
		);

		// Check for archive options
		if ($archived_options && $category->guid != $archive_category) {
			$options['wheres'][] = $archived_options;
		}

		//$options['wheres'][] = $hidden_where;

		$content = elgg_list_entities_from_relationship($options);
	} else {
		$content .= elgg_echo('group-extender:error:invalidcategory');
	}
}

if (!$content) {
	$content = elgg_echo('group-extender:label:nogroups');
}

echo $content;