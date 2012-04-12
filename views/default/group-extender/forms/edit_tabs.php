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

if (!$group || !$group->canEdit()) {
	forward(REFERER);
}

$current_tabs_label = elgg_echo('group-extender:label:currenttabs');

$current_tabs_content = elgg_view('group-extender/forms/current_tabs', array('group_guid' => $group->guid));

$current_tabs_container = "<div id='group-extender-current-tabs-form'>{$current_tabs_content}</div>";

$current_tabs_module = elgg_view_module('info', $current_tabs_label, $current_tabs_container);

// New tab
$title_input = elgg_view('input/text', array(
	'name' => 'tab_title'
));

$type_input = elgg_view('input/dropdown', array(
	'name' => 'tab_type',
	'options_values' => group_extender_get_tab_types(),
));

$save_input = elgg_view('input/submit', array(
	'name' => 'save_new_tab',
	'value' => elgg_echo('save'),
));

$group_hidden = elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $group->guid,
));

$new_tab = <<<HTML
	<div>
		<label>$title_label</label>
		$title_input
	</div>
	<div>
		<label>$type_label</label><br />
		$type_input
	</div>
	<div class='elgg-foot'>
		$save_input
		$group_hidden
	</div>
HTML;

$new_tab_form = elgg_view("input/form", array(
	'body' => $new_tab,
	'action' => $vars["url"] . "action/groupextender/save_tab",
));

$new_tab_label = elgg_echo('group-extender:label:newtab');

$new_tab_module = elgg_view_module('info', $new_tab_label, $new_tab_form);

echo $current_tabs_module;
echo $new_tab_module;
