<?php
/**
 * Group-Extender Copy Tabs
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2016
 * @link http://www.thinkglobalschool.com/
 * 
 */

$copy_from_guid = get_input('copy_from_guid');
$copy_to_guid = get_input('copy_to_guid');

$copy_from = get_entity($copy_from_guid);
$copy_to = get_entity($copy_to_guid);

// Validate groups
if (!elgg_instanceof($copy_from, 'group') || !elgg_instanceof($copy_to, 'group')) {
	register_error(elgg_echo('group-extender:error:invalidgroup'));
	forward(REFERER);
}

// Make sure we have different groups
if ($copy_from_guid == $copy_to_guid) {
	register_error(elgg_echo('group-extender:error:copysamegroup'));
	forward(REFERER);
}

$copy_from_tabs = group_extender_get_tabs($copy_from);
$copy_to_tabs = group_extender_get_tabs($copy_to);

unset($copy_from_tabs['activity-default']);

foreach ($copy_from_tabs as $orig_uid => $tab) {	
	// Add to the group
	$uid = group_extender_add_tab($copy_to, $tab);

	if ($tab['type'] == 'static') {
		$old_meta = "tab_{$orig_uid}";
		$content = $copy_from->$old_meta;

		$meta_name = "tab_{$uid}";
		$tab['params']['static_content_meta'] = $meta_name;
		$copy_to->$meta_name = $content;
		unset($tab['params']['static_content']);
		group_extender_update_tab($copy_to, $uid, $tab);
	}
}
system_message(elgg_echo('group-extender:success:copytabs'));
