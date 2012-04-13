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
		
		$module_title = elgg_echo('group-extender:label:edittab');
		
		$form_type = $tab['type'];
		
		// Hidden tab input
		$tab_hidden = elgg_view('input/hidden', array(
			'name' => 'tab_id',
			'value' => $tab_id,
		));

		$edit_type_content = elgg_view("group-extender/forms/edit_{$form_type}", array('tab' => $tab));
		
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
	}

	// Can save title
	$title_label = elgg_echo('group-extender:label:title');
	$title_input = elgg_view('input/text', array(
		'name' => 'tab_title',
		'value' => $title,
	));
	
	// Save input
	$save_input = elgg_view('input/submit', array(
		'name' => $form_type,
		'value' => elgg_echo('save'),
		'id' => "group-extender-tab-save-submit",
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
		<div class='elgg-foot'>
			$save_input
			$group_hidden
			$tab_hidden
		</div>
HTML;

	$form = elgg_view("input/form", array(
		'body' => $body,
		'id' => "group-extender-tab-edit-form-{$form_type}",
		'action' => $vars["url"] . "action/groupextender/save_tab",
	));

	echo elgg_view_module('info', $module_title, $form, array(
		'class' => "group-extender-tab-form-{$form_type}",
	));
}