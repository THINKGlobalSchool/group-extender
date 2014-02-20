<?php
/**
 * Group-Extender Group Homepage action
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */

$page = get_input('page', false);
$group_guid = get_input('group_guid', false);

$group = get_entity($group_guid);

$fwd = elgg_normalize_url("groups/edit/{$group_guid}#other");

if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group-extender:error:invalidgroup'));
	forward($fwd);
}

if (!$page) {
	register_error(elgg_echo('group-extender:error:invalidtab'));
	forward($fwd);
}

$group_url = $group->getURL();

if ($page == 'default') {

	$tabs = group_extender_get_tabs($group);

	foreach ($tabs as $uid => $tab) {
		if ($tab['type'] == 'activity') {
			$homepage = $uid;
			break;
		}
	}

	if ($homepage) {
		$group->homepage = $homepage;
	} else {
		$group->homepage = null;
	}

} else {
	$group->homepage = $page;
}

if ($group->save()) {
	system_message(elgg_echo('group-extender:success:homepage'));
} else {
	register_error(elgg_echo('group-extender:error:homepage'));
}

forward($fwd);