<?php
/**
 * Group-Extender Group Tabs View
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group_guid = elgg_extract('group_guid', $vars);

$group = get_entity($group_guid);

$group_tabs = group_extender_get_tabs($group);

// Add admin tab
if ($group->canEdit()) {
	$group_tabs['admin'] = array(
		'title' => elgg_echo('group-extender:tab:admin'),
		'type' => 'admin',
		'priority' => 999,
		'params' => NULL,
	);
}

$tab_content = '';

$count = 0;

// Build tabs content
foreach ($group_tabs as $uid => $tab) {
	if ($tab['type'] == 'tagdashboard' && $count == 0) {
		// Need to trigger load event if tagdashboard is the first tab
		$tab_js = "<script type='text/javascript'>
			$(document).ready(function() {
				$('#{$uid}').trigger('click');
			});
		</script>";
	}
	$count++;
	
	$title = $group_tabs[$uid]['title'];
	$priority = $group_tabs[$uid]['priority'];
	$type = $group_tabs[$uid]['type'];
	$default = $group_tabs[$uid]['priority'] == group_extender_get_lowest_tab_priority($group);

	// Simple tab interface for switching between feed lookup and manual entry
	elgg_register_menu_item('group-extender-tab-menu', array(
		'name' => "group_extender_{$title}_{$uid}",
		'text' => $title,
		'href' => "#groupextender-tab-{$uid}",
		'priority' => $priority,
		'item_class' => $default ? 'elgg-state-selected' : '',
		'class' => "group-extender-tab-menu-item",
		'id' => $uid,
	));

	$display = !$default ? "style='display: none;'" : '';

	$tab_content .= "<div $display id='groupextender-tab-{$uid}' class='group-extender-tab-content-container'>";

	$tab_content .= elgg_view("group-extender/tabs/{$type}", array(
		'group' => $group,
		'tab_id' => $uid,
	)) . "</div>";
}


$menu = elgg_view_menu('group-extender-tab-menu', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default'
));

echo $menu . $tab_content . $tab_js;