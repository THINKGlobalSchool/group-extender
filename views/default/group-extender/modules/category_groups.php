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
if ($guid == 'all') {
	$content = elgg_list_entities(array(
		'types' => 'group',
		'full_view' => FALSE,
		'limit' => 15,
	));
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