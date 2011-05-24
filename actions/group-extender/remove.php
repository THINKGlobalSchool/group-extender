<?php
/**
 * Remove a user from a group
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 */

$user_guid = get_input('user_guid');
$group_guid = get_input('group_guid');

$user = get_entity($user_guid);
$group = get_entity($group_guid);

elgg_set_page_owner_guid($group->guid);

if (($user instanceof ElggUser) && ($group instanceof ElggGroup) && $group->canEdit()) {
	if ($group->getOwnerGUID() != $user->getGUID()) {
		if ($group->leave($user)) {
			system_message(elgg_echo("group-extender:removed"));
		} else {
			register_error(elgg_echo("group-extender:cantremove"));
		}
	} else {
		register_error(elgg_echo("group-extender:cantremove"));
	}
} else {
	register_error(elgg_echo("group-extender:cantremove"));
}

forward(REFERER);
