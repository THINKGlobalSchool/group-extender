<?php
/**
 * Group-Extender Archive Action
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Get group guid
$group_guid = get_input('group_guid');

// Get group entity
$group = get_entity($group_guid);

// Check for valid group
if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group-extender:error:invalidgroup'));
	forward(REFERER);
}

// Archive category may be hidden
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

// Get group archive category
$category = get_entity(elgg_get_plugin_setting('archive_category', 'group-extender'));

// Check for valid category
if (!$category || !elgg_instanceof($category, 'object', 'group_category')) {
	access_show_hidden_entities($access_status);
	register_error(elgg_echo('group-extender:error:invalidcategory'));
	forward(REFERER);
}

// Check if group is already a member, don't try to add it again
if (groupcategories_is_group_member($category, $group)) {
	access_show_hidden_entities($access_status);
	register_error(elgg_echo('group-extender:error:existingcategory', array($group->name, $category->title)));
	forward(REFERER);
} else {
	// Try to add to group category
	if (!groupcategories_add_group($category, $group)) {
		access_show_hidden_entities($access_status);
		register_error(elgg_echo('group-extender:error:addgroup'));
		forward(REFERER);
	}
}	

// Get group members
$members = $group->getMembers(array('limit' => 0));

// Set success flag
$success = TRUE;

// Create an array for current group membership
$group_last_members = array();

// Remove all group members
foreach ($members as $member) {
	$group_last_members[] = $member->guid;
	$success &= $group->leave($member);
}

// Store members in case we need to revert
$group->pre_archived_membership = serialize($group_last_members);

// Store wether or not this group was featured before it was archived
$group->pre_archived_featered = $group->featured_group;

// Make sure featured metadata is set to 'no'
$group->featured_group = 'no';

// Make sure we successfully removed all the users
if (!$success) {
	access_show_hidden_entities($access_status);
	register_error('group-extender:error:removeusers');
	forward(REFERER);
}

// Set archived status, this will remove join, etc, options
$group->archived = TRUE;

// All good
access_show_hidden_entities($access_status);
system_message(elgg_echo('group-extender:success:grouparchived'));
forward(REFERER);