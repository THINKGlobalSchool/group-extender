<?php
/**
 * Group-Extender Group Move Content Form
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$entity_guid = get_input('entity_guid');
$group_guid = get_input('group_guid');
$reset_owner = get_input('reset_owner');

$entity = get_entity($entity_guid);
$group = get_entity($group_guid);

// Check for valid entity and that user can edit (or is admin)
if (!elgg_instanceof($entity, 'object') || ($entity->owner_guid != elgg_get_logged_in_user_guid() && !elgg_is_admin_logged_in())) {
	register_error(elgg_echo('group-extender:error:invalidentity'));
	forward(REFERER);
}


// If group guid was supplied
if ($group_guid) {
	// Check for valid group and that user is a member (or is admin)
	if (!elgg_instanceof($group, 'group') || (!$group->isMember()) && !elgg_is_admin_logged_in()) {
		register_error(elgg_echo('group-extender:error:invalidgroup'));
		forward(REFERER);
	}

	// Update access
	group_extender_update_moved_entity_access($entity, $group->guid);
	
	// Set new container
	$entity->container_guid = $group->guid;
	$success = elgg_echo('group-extender:success:move', array($group->name));
	$error = elgg_echo('group-extender:error:move', array($group->name));
} else if ($reset_owner) {
	// Update access
	group_extender_update_moved_entity_access($entity, $entity->owner_guid);

	// Set container to owner
	$entity->container_guid = $entity->owner_guid;
	$success = elgg_echo('group-extender:success:moveout');
	$error = elgg_echo('group-extender:error:moveout');
} else {
	// No group guid, or reset flag.. bail..
	register_error(elgg_echo('group-extender:error:requiredfields'));
	forward(REFERER);
}

// Save
if ($entity->save() && elgg_trigger_plugin_hook('groupmove', 'entity', array('entity' => $entity), TRUE)) {
	system_message($success);
} else {
	register_error($error);
}

forward(REFERER);