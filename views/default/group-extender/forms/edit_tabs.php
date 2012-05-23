<?php
/**
 * Group-Extender Edit tabs form
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */


$group = elgg_get_page_owner_entity();

if (!$group) {
	$group = elgg_extract('group', $vars);
}

if (!$group || !$group->canEdit()) {
	forward(REFERER);
}

$current_tabs_label = elgg_echo('group-extender:label:currenttabs');

$current_tabs_content = elgg_view('group-extender/forms/current_tabs', array('group_guid' => $group->guid));

$current_tabs_container = "<div id='group-extender-current-tabs-form'>{$current_tabs_content}</div>";

$current_tabs_module = elgg_view_module('info', $current_tabs_label, $current_tabs_container);

$new_tab_form = elgg_view('group-extender/forms/edit_tab', array('group_guid' => $group->guid));

echo $current_tabs_module;
echo $new_tab_form;

