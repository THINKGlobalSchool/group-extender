<?php
/**
 * Group-Extender Group Nav Settings Action
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group_guid = get_input('group_guid', false);
$new_layout = get_input('new_layout', 0);

$group = get_entity($group_guid);

if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group-extender:error:invalidgroup'));
	forward(REFERER);
}

$group->new_layout = $new_layout;

if ($group->save()) {
	system_message(elgg_echo('group-extender:success:nav_settings'));
} else {
	register_error(elgg_echo('group-extender:error:nav_settings'));
}

forward(elgg_normalize_url("groups/edit/{$group_guid}") . "#other");