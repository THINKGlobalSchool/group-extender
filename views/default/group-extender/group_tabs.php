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

$group = elgg_extract('entity', $vars);

$group_tabs = group_extender_get_tabs($group);

$tab_content = '';

// Build tabs content
foreach ($group_tabs as $uid => $tab) {

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
		'class' => 'group-extender-tab-menu-item',
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

echo $menu;
echo $tab_content;
