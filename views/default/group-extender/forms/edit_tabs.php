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

$group_tabs = group_extender_get_tabs($group);

$type_label = elgg_echo('group-extender:label:type');
$priority_label = elgg_echo('group-extender:label:priority');
$title_label = elgg_echo('group-extender:label:title');
$default_label = elgg_echo('group-extender:label:default');
$actions_label = elgg_echo('group-extender:label:actions');

// Current tabs table
$current_tabs = <<<HTML
	<table class='elgg-table group-extender-edit-tab-table'>
		<thead>
			<tr>
				<th>$type_label</th>
				<th>$title_label</th>
				<th>$priority_label</th>
				<th>$actions_label</th>
			</tr>
		</thead>
		<tbody>
HTML;

$editable_types = array(
	'static',
	'subtype',
);

// Build tabs content
foreach ($group_tabs as $uid => $tab) {

	$tab_title = $group_tabs[$uid]['title'];
	$tab_priority = $group_tabs[$uid]['priority'];
	$tab_type = $group_tabs[$uid]['type'];
	$tab_type_text = elgg_echo("group-extender:tab:" . $tab_type); 
	$tab_default = $group_tabs[$uid]['default'] ? 'Yes' : 'No'; 

	$actions = '';

	// Show 'edit' for editable types
	if (in_array($tab_type, $editable_types)) {
		$actions = elgg_view('output/url', array(
			'text' => elgg_echo('edit'),
			'class' => 'group-extender-lightbox',
			'href' => "ajax/view/group-extender/forms/edit_{$tab_type}?group_guid={$group->guid}&tab_id={$uid}",
		)) . "&nbsp;";
	}
	
	// Only show delete if there is more than one tab
	if (count($group_tabs) > 1) {
		$delete_url = elgg_add_action_tokens_to_url("action/groupextender/delete_tab?tab_id={$uid}&group_guid={$group->guid}");
		
		$actions .= elgg_view('output/url', array(
			'text' => elgg_echo('delete'),
			'href' => $delete_url,
		));
	}

	$current_tabs .= <<<HTML
		<tr>
			<td>$tab_type_text</td>
			<td>$tab_title</td>
			<td>$tab_priority</td>
			<td class='group-extender-tab-actions'>$actions</td>
		</tr>
HTML;
}

$current_tabs .= "</tbody></table>";
$current_tabs_label = elgg_echo('group-extender:label:currenttabs');

$current_tabs_module = elgg_view_module('info', $current_tabs_label, $current_tabs);

echo $current_tabs_module;


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

echo $new_tab_module;
