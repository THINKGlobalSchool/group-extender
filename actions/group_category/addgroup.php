<?php
/**
 * Group-Extender group category add group action
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Get group/category inputs
$groups = get_input('members');
$category_guid = (int)sanitise_string(get_input('category_guid'));

// Check for group
if (empty($groups)) {
	register_error(elgg_echo('group-extender:error:grouprequired'));
	forward(REFERER);
}

// Get category entity
$category = get_entity($category_guid);

// Check for category
if (!$category || !elgg_instanceof($category, 'object', 'group_category')) {
	register_error(elgg_echo('group-extender:error:invalidcategory'));
	forward(REFERER);
}

// Loop and add groups
foreach ($groups as $guid) {
	$group = get_entity($guid);
	if (elgg_instanceof($group, 'group')) {
		// Check if group is already a member, don't try to add it again
		if (groupcategories_is_group_member($category, $group)) {
			register_error(elgg_echo('group-extender:error:existingcategory', array($group->name, $category->title)));
		} else {
			// Try to add
			if (groupcategories_add_group($category, $group)) {
				// All good!
				system_message(elgg_echo('group-extender:success:addgroup', array($category->title)));
			} else {
				// There was an error
				register_error(elgg_echo('group-extender:error:addgroup'));
			}
		}	
	} else {
		register_error(elgg_echo('group-extender:error:invalidgroup', array($guid)));
	}
}
forward(REFERER);