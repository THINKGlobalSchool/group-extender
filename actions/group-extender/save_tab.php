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

$tab_title = get_input('tab_title');
$tab_type = get_input('tab_type');
$group_guid = get_input('group_guid');
$tab_id = get_input('tab_id'); // For editing
$group = get_entity($group_guid);

// Check for valid group
if (!elgg_instanceof($group, 'group') || !$group->canEdit()) {
	register_error(elgg_echo('group-extender:error:invalidgroup'));
	forward(REFERER);
}

// Check for tab id
if ($tab_id) {	// Updating tab
	$current_tabs = group_extender_get_tabs($group);
	
	// Get tab extended params
	$tab_params = get_input('tab_params');
	
	// Try to grab tab
	$tab = group_extender_get_tab_by_id($group, $tab_id);

	if (!$tab) {
		register_error(elgg_echo('group-extender:error:invalidtab'));
		forward(REFERER);
	}
	
	$tab['title'] = $tab_title;
	$tab['params'] = $tab_params;
	
	// Try to update tab
	if (!group_extender_update_tab($group, $tab_id, $tab)) {
		register_error(elgg_echo('group-extender:error:savetab'));
		forward(REFERER);	
	}
	
} else { // Adding new tab
	$uid = group_extender_add_tab($group, array(
		'title' => $tab_title,
		'type' => $tab_type,
		'priority' => group_extender_get_highest_tab_priority($group) +1,
	));
}

// All good, display success and return
system_message(elgg_echo('group-extender:success:savetab'));
forward(REFERER);

