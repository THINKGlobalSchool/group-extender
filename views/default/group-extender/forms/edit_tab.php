<?php
/**
 * Group-Extender Edit tab form
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group_guid = elgg_extract('group_guid', $vars, get_input('group_guid'));
$tab_id = elgg_extract('tab_id', $vars, get_input('tab_id'));

$group = get_entity($group_guid);

if (!elgg_instanceof($group, 'group') || !$group->canEdit()) {  // Check valid group
	echo elgg_echo('group-extender:error:invalidgroup');
} else  {         
	// Module title
	$module_title = elgg_echo('group-extender:label:newtab');
	
	// Form type
	$form_type = "new";
	                                               
	if ($tab_id && group_extender_get_tab_by_id($group, $tab_id)) {
		$tab = group_extender_get_tab_by_id($group, $tab_id); // Editing
	
		$title = $tab['title'];

		$hidden = $tab['hidden'];
		
		$module_title = elgg_echo('group-extender:label:edittab');
		
		$form_type = $tab['type'];
		
		// Hidden tab input
		$tab_hidden = elgg_view('input/hidden', array(
			'name' => 'tab_id',
			'value' => $tab_id,
		));

		$edit_type_content = "<div>" . elgg_view("group-extender/forms/edit_{$form_type}", array('group_guid' => $group_guid, 'tab' => $tab)) . "</div>";
		$save_label = elgg_echo('save');
		
	} else { 
		// New Tab
		$type_label = elgg_echo('group-extender:label:type');
		
		$type_input = elgg_view('input/dropdown', array(
			'name' => 'tab_type',
			'id' => 'group-extender-tab-type-select',
			'options_values' => group_extender_get_tab_types(),
		));
		
		$type_content = "<div><label>$type_label</label><br />$type_input</div>";
		$edit_type_content = "<div id='group-extender-extended-type-content'></div>";
		$save_label = elgg_echo('group-extender:label:add');
	}

	// Can save title
	$title_label = elgg_echo('group-extender:label:title');
	$title_input = elgg_view('input/text', array(
		'name' => 'tab_title',
		'value' => $title,
	));

	// Hide tab input
	$hide_tab_label = elgg_echo('group-extender:label:hidetab');
	$hide_tab_input = elgg_view('input/dropdown', array(
		'name' => 'tab_hidden',
		'id' => 'group-extender-tab-hidden-select',
		'options_values' => array(
			0 => elgg_echo('group-extender:label:no'),
			1 => elgg_echo('group-extender:label:yes')
		), 
		'value' => $hidden
	));
	
	// Save input
	$save_input = elgg_view('input/submit', array(
		'name' => $form_type,
		'value' => $save_label,
		'class' => "group-extender-tab-save-submit elgg-button elgg-button-submit",
	));

	// Hidden group input
	$group_hidden = elgg_view('input/hidden', array(
		'name' => 'group_guid',
		'value' => $group->guid,
	));

	$body = <<<HTML
		<div>
			<label>$title_label</label>
			$title_input
		</div>
		$type_content
		$edit_type_content
		<div>
			<label>$hide_tab_label</label>
			$hide_tab_input
		</div>
		<div class='elgg-foot'>
			$save_input
			$refresh_input
			$group_hidden
			$tab_hidden
		</div>
HTML;

	$form = elgg_view("input/form", array(
		'body' => $body,
		'id' => "group-extender-tab-edit-form-{$form_type}",
		'action' =>  elgg_normalize_url("action/groupextender/save_tab"),
	));

	echo elgg_view_module('info', $module_title, $form, array(
		'class' => "group-extender-tab-form-{$form_type}",
	));
}