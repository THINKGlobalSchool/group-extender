<?php
/**
 * Group-Extender group category remove group action
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Get group/category inputs
$group_guid = (int)sanitise_string(get_input('group_guid'));
$category_guid = (int)sanitise_string(get_input('category_guid'));

// Get group entity
$group = get_entity($group_guid);

// Check for group
if (!$group) {
	register_error(elgg_echo('group-extender:error:invalidgroup'));
	forward(REFERER);
}

// Get category entity
$category = get_entity($category_guid);

// Check for category
if (!$category || !elgg_instanceof($category, 'object', 'group_category')) {
	register_error(elgg_echo('group-extender:error:invalidcategory'));
	forward(REFERER);
}

// Try to remove
if (groupcategories_remove_group($category, $group)) {
	// All good!
	system_message(elgg_echo('group-extender:success:removegroup', array($category->title)));
} else {
	// There was an error
	register_error(elgg_echo('group-extender:error:removegroup'));
}
forward(REFERER);