<?php
/**
 * Group-Extender Group Tabs View
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */


$group = elgg_extract('entity', $vars);

if (!elgg_instanceof($group, 'group')) {
	return;
}

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

$count = 0;

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
}


$menu = elgg_view_menu('group-extender-tab-menu', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu elgg-menu-owner-block elgg-menu-owner-block-default'
));

echo "<div id='group-extender-group-tabs-menu'>";
echo $menu;
echo "</div>";