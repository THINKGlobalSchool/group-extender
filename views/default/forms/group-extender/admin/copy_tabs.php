<?php
/**
 * Group-Extender Copy Tabs Form
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2016
 * @link http://www.thinkglobalschool.com/
 * 
 */

$groups = elgg_get_entities(array(
	'type' => 'group',
	'limit' => 0,
	'joins' => array("JOIN " . elgg_get_config("dbprefix") . "groups_entity ge ON e.guid = ge.guid"),
	'order_by' => 'ge.name ASC'
));

if (count($groups)) {
	// Add each group to group array for dropdown
	foreach ($groups as $g) {
		$groups_array[$g->guid] = $g->name;
	}

	$copy_from_group_select = elgg_view('input/dropdown', array(
		'name' => 'copy_from_guid',
		'options_values' => $groups_array,
	));

	$copy_from_group_label = elgg_echo('group-extender:label:copyfromgroup');

	$copy_to_group_select = elgg_view('input/dropdown', array(
		'name' => 'copy_to_guid',
		'options_values' => $groups_array,
	));

	$copy_to_group_label = elgg_echo('group-extender:label:copytogroup');

	$submit_input = elgg_view('input/submit', array(
		'name' => 'submit', 
		'value' => elgg_echo('group-extender:label:copy')
	));

	$content = <<<HTML
	<div>
		<label>$copy_from_group_label</label><br />
		$copy_from_group_select
	</div><br />
	<div>
		<label>$copy_to_group_label</label><br />
		$copy_to_group_select
	</div><br />
	$submit_input
HTML;

	$copy_tabs_label = elgg_echo('group-extender:label:copytabs');

	$module = elgg_view_module('inline', $copy_tabs_label, $content);

	echo $module;
}