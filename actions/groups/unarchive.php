<?php
/**
 * Group-Extender Unarchive Action
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

// Try to remove from archive category
if (!groupcategories_remove_group($category, $group)) {
	// There was an error
	access_show_hidden_entities($access_status);
	register_error(elgg_echo('group-extender:error:removegroup'));
	$error = TRUE;
}

// If group was previously featured, re-feature it
$group->featured_group = $group->pre_archived_featered;

// Get group previous members
$members_array = unserialize($group->pre_archived_membership);

// Set success flag
$success = TRUE;

// Add all group members
elgg_set_page_owner_guid($group->guid);

// access ignore so user can be added to access collection of invisible group
$ia = elgg_get_ignore_access();
elgg_set_ignore_access(TRUE);

foreach ($members_array as $member_guid) {
	$member = get_entity($member_guid);
	if (elgg_instanceof($member, 'user')) {
		$success &= $group->join($member);	
	}
}

elgg_set_ignore_access($ia);

// Make sure we successfully added all the users
if (!$success) {
	access_show_hidden_entities($access_status);
	register_error(elgg_echo('group-extender:error:addusers'));
	$error = TRUE;
}

// Unset archived
$group->archived = FALSE;

access_show_hidden_entities($access_status);

// Display success message if there were no errors
if (!$error) {
	system_message(elgg_echo('group-extender:success:groupunarchived'));
}

forward(REFERER);