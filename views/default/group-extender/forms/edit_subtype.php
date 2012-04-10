<?php
/**
 * Group-Extender Edit Subtype Form
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group_guid = get_input('group_guid');
$tab_id = get_input('tab_id');
$group = get_entity($group_guid);

if (!elgg_instanceof($group, 'group') || !$group->canEdit()) {  // Check valid group
	echo elgg_echo('group-extender:error:invalidgroup');
} else if (!$tab_id || !group_extender_get_tab_by_id($group, $tab_id)) { // Check valid tab
	echo elgg_echo('group-extender:error:invalidtab');
} else {                                                        // Good to go
	// Grab tab
	$tab = group_extender_get_tab_by_id($group, $tab_id);

	// Can edit title
	$title_label = elgg_echo('group-extender:label:title');
	$title_input = elgg_view('input/text', array(
		'name' => 'tab_title',
		'value' => $tab['title'],
	));
	
	// Can edit displayed subtype
	$show_type_label = elgg_echo('group-extender:label:showsubtype');
	$show_type_input = elgg_view('input/dropdown', array(
		'name' => 'tab_selected_subtype',
		'options_values' => group_extender_get_group_subtypes(),
		'value' => $tab['params']['subtype'],
	));
	
	// Save input
	$save_input = elgg_view('input/submit', array(
		'value' => elgg_echo('save'),
		'id' => 'group-extender-save-subtype-submit',
	));

	// Hidden group input
	$group_hidden = elgg_view('input/hidden', array(
		'name' => 'group_guid',
		'value' => $group->guid,
	));

	// Hidden tab input
	$tab_hidden = elgg_view('input/hidden', array(
		'name' => 'tab_id',
		'value' => $tab_id,
	));

	$body = <<<HTML
		<div>
			<label>$title_label</label>
			$title_input
		</div>
		<div>
			<label>$show_type_label</label><br />
			$show_type_input
		</div>
		<div class='elgg-foot'>
			$save_input
			$group_hidden
			$tab_hidden
		</div>
HTML;

	$subtype_form = elgg_view("input/form", array(
		'body' => $body,
		'id' => 'group-extender-edit-subtype-form',
		'action' => $vars["url"] . "action/groupextender/save_tab",
	));
	
	echo elgg_view_module('info', elgg_echo('group-extender:label:edittab'), $subtype_form, array(
		'class' => 'group-extender-tab-form-module',
	));
}