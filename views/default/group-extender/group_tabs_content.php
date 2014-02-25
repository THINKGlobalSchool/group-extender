<?php
/**
 * Group-Extender Group Tabs Content
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group = $vars['entity'];

if (!elgg_instanceof($group, 'group')) {
	return;
}

$group_tabs = group_extender_get_tabs($group);
$count = 0;

if ($group->canEdit()) {
	$group_tabs['admin'] = array(
		'title' => elgg_echo('group-extender:tab:admin'),
		'type' => 'admin',
		'priority' => 999,
		'params' => NULL,
	);
}

$homepage = $group->homepage;

// Check if this is the default/homepage
if ($homepage) {
	$default = $homepage;
} else {
	// No homepage set, try to find the first activity tab
	foreach ($group_tabs as $i => $t) {
		if ($t['type'] == 'activity') {
			$default = $i;
			break;
		}
	}
}

// Build tabs menu
foreach ($group_tabs as $uid => $tab) {
	if ($tab['type'] == 'tagdashboard' && $count == 0) {
		// Need to trigger load event if tagdashboard is the first tab
		$tab_js = "<script type='text/javascript'>
			$(document).ready(function() {
				var tagdb = $('#groupextender-tab-{$uid}').find('.tagdashboard-tab-container');
				elgg.tagdashboards.init_dashboards_with_container(tagdb);
			});
		</script>";
	}
	$count++;

	$type = $group_tabs[$uid]['type'];

	// Set selected
	if (!$default) {
		$selected = $group_tabs[$uid]['priority'] == group_extender_get_lowest_tab_priority($group);	
	} else {
		$selected = $uid == $default ? true : false;
	}
	
	// If new layout isn't enabled, show the old tabs
	if (!$group->new_layout) {
		$title = $group_tabs[$uid]['title'];
		$priority = $group_tabs[$uid]['priority'];

		// Simple tab interface for switching between feed lookup and manual entry
		elgg_register_menu_item('group-extender-tab-menu', array(
			'name' => "group_extender_{$title}_{$uid}",
			'text' => $title,
			'href' => "#groupextender-tab-{$uid}",
			'priority' => $priority,
			'item_class' => $selected ? 'elgg-state-selected' : '',
			'class' => "group-extender-tab-menu-item",
			'id' => $uid,
		));
	}

	$display = !$selected ? "style='display: none;'" : '';

	$tab_content .= "<div $display id='groupextender-tab-{$uid}' class='group-extender-tab-content-container'>";

	$tab_content .= elgg_view("group-extender/tabs/{$type}", array(
		'group' => $group,
		'tab_id' => $uid,
	)) . "</div>";
}

echo "<div id='group-extender-group-tabs'>";

// Output tabs menu
if (!$group->new_layout) {
	$menu = elgg_view_menu('group-extender-tab-menu', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default'
	));
	echo $menu;
} 

echo $tab_content;
echo "</div>";