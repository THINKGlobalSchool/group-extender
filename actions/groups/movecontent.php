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

$entity = get_entity($entity_guid);
$group = get_entity($group_guid);

// Check for valid entity and that user can edit (or is admin)
if (!elgg_instanceof($entity, 'object') || ($entity->owner_guid != elgg_get_logged_in_user_guid() && !elgg_is_admin_logged_in())) {
	register_error(elgg_echo('group-extender:error:invalidentity'));
	forward(REFERER);
}

// Check for valid group and that user is a member (or is admin)
if (!elgg_instanceof($group, 'group') || (!$group->isMember()) && !elgg_is_admin_logged_in()) {
	register_error(elgg_echo('group-extender:error:invalidgroup'));
	forward(REFERER);
}

// Set new container
$entity->container_guid = $group->guid;

// Save
if ($entity->save() && elgg_trigger_plugin_hook('groupmove', 'entity', array('entity' => $entity), TRUE)) {
	system_message(elgg_echo('group-extender:success:move', array($group->name)));
} else {
	register_error(elgg_echo('group-extender:error:move', array($group->name)));
}

forward(REFERER);