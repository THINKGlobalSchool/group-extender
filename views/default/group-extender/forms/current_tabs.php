<?php
/**
 * Group-Extender Current tabs form component
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['group_guid'] Group to grab tabs from
 */

$group_guid = elgg_extract('group_guid', $vars);

$group = get_entity($group_guid);

if (!$group || !$group->canEdit()) {
	return FALSE;
}

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

$homepage = $group->homepage;

// Build tabs content
$i = 0;
foreach ($group_tabs as $uid => $tab) {

	$tab_title = $group_tabs[$uid]['title'];
	$tab_priority = $group_tabs[$uid]['priority'];
	$tab_type = $group_tabs[$uid]['type'];
	$tab_type_text = elgg_echo("group-extender:tab:" . $tab_type); 
	$tab_default = $group_tabs[$uid]['default'] ? 'Yes' : 'No'; 

	$actions = '';

	$actions = elgg_view('output/url', array(
		'text' => elgg_echo('edit'),
		'class' => 'group-extender-tab-editor',
		'href' => "ajax/view/group-extender/forms/edit_tab?group_guid={$group->guid}&tab_id={$uid}",
	)) . "&nbsp;";
	
	// Only show delete if there is more than one tab
	if (count($group_tabs) > 1) {
		$delete_url = elgg_add_action_tokens_to_url("action/groupextender/delete_tab?tab_id={$uid}&group_guid={$group->guid}");
		
		$actions .= elgg_view('output/url', array(
			'class' => 'group-extender-delete-link',
			'text' => elgg_echo('delete') . "&nbsp;",
			'href' => $delete_url,
		));
	}
	
	$move_url = "action/groupextender/move_tab?group_guid={$group->guid}&tab_id={$uid}";
	
	$up_url = elgg_add_action_tokens_to_url($move_url . "&priority=" . ($tab_priority - 1));
	$down_url = elgg_add_action_tokens_to_url($move_url . "&priority=" . ($tab_priority + 1));
	
	$up_link = elgg_view('output/url', array(
		'text' => elgg_echo('group-extender:label:up'),
		'href' => $up_url,
		'class' => 'group-extender-move-link',
	));
	
	$down_link = "&nbsp;" . elgg_view('output/url', array(
		'text' => elgg_echo('group-extender:label:down'),
		'href' => $down_url,
		'class' => 'group-extender-move-link',
	));

	if ($uid != $homepage) {
		$make_default = elgg_view('output/url', array(
			'text' => elgg_echo('group-extender:label:make_default'),
			'href' => elgg_add_action_tokens_to_url('action/groups/homepage' . "?group_guid={$group->guid}&page={$uid}"),
			'class' => 'group-extender-homepage-link'
		));
	} else {
		$make_default = null;
	}

	$actions .= $make_default;

	if ($tab['type'] == 'activity') {
		$up_link = $down_link = $actions = null;

		// Show default on activity item if possible
		if (count($group_tabs) != 1 && $homepage && $homepage != 'activity-default') {
			$actions = $make_default;
		} 
	}

	// Add 'up/down' links
	if ($i == 0 || count($group_tabs) == 2) {
		// None;
	} else if ($i + 1 == count($group_tabs) && count($group_tabs) > 2) {
		$move_links = $up_link;
	} else if (count($group_tabs) > 2 && $i == 1) {
		$move_links = $down_link;
	} else {
		$move_links = $down_link . " " .  $up_link;
	}

	$current_tabs .= <<<HTML
		<tr>
			<td>$tab_type_text</td>
			<td>$tab_title</td>
			<td class='group-extender-tab-priority'>$tab_priority <span style='float: right;'>$move_links</span></td>
			<td class='group-extender-tab-actions'>$actions</td>
		</tr>
HTML;

	$i++;
}

$current_tabs .= "</tbody></table>";

echo $current_tabs;