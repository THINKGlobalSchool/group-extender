<?php
/**
 * Group-Extender Save Tab Action
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$tab_id = get_input('tab_id');
$group_guid = get_input('group_guid');
$group = get_entity($group_guid);

// Check for valid group
if (!elgg_instanceof($group, 'group') || !$group->canEdit()) {
	register_error(elgg_echo('group-extender:error:invalidgroup'));
	forward(REFERER);
}

// Check for a tab id, and that it exists in the metadata
if (!$tab_id || !group_extender_get_tab_by_id($group, $tab_id)) {
	register_error(elgg_echo('group-extender:error:invalidtab'));
	forward(REFERER);
}

// Remove tab
group_extender_remove_tab($group, $tab_id);

system_message(elgg_echo('group-extender:success:deletetab'));
forward(REFERER);