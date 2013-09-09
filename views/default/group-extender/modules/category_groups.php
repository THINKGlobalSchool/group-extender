<?php
/**
 * Group-Extender list groups in category module
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = elgg_extract('guid', $vars, NULL);

// Load All Groups
if ($guid == 'all' || $guid == 'mine' || $guid == 'owned') {
	$options = array(
		'types' => 'group',
		'full_view' => FALSE,
		'limit' => 15,
	);

	// If we have an archived category, exclude those groups from the all listing
	if ((int)elgg_get_plugin_setting('archive_category', 'group-extender')) {
		global $CONFIG;

		// MD info for excluding archived
		$archived = get_metastring_id('archived');
		$one_id = get_metastring_id(1);

		if ($archived && $one_id) {
					$options['wheres'] = "
		  			NOT EXISTS (
						SELECT 1 FROM {$CONFIG->dbprefix}metadata md
						WHERE md.entity_guid = e.guid
						AND md.name_id = $archived
						AND md.value_id = $one_id)";
		}
	}

	// Add options for mine/owned
	if ($guid == 'mine') {
		$options['relationship'] = 'member';
		$options['relationship_guid'] = elgg_get_logged_in_user_guid();
		$options['inverse_relationship'] = false;
	} else if ($guid == 'owned') {
		$options['owner_guid'] = elgg_get_logged_in_user_guid();
	}

	$content = elgg_list_entities_from_relationship($options);
} else {
	$category = get_entity($guid);

	if ($category) {
		$content = elgg_list_entities_from_relationship(array(
			'relationship' => GROUP_CATEGORY_RELATIONSHIP,
			'relationship_guid' => $category->guid,
			'inverse_relationship' => TRUE,
			'types' => 'group',
			'full_view' => FALSE,
			'limit' => 15
		));
	} else {
		$content .= elgg_echo('group-extender:error:invalidcategory');
	}
}

if (!$content) {
	$content = elgg_echo('group-extender:label:nogroups');
}

echo $content;