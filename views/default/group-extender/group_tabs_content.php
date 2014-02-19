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
	$default = $group_tabs[$uid]['priority'] == group_extender_get_lowest_tab_priority($group);

	$display = !$default ? "style='display: none;'" : '';

	$tab_content .= "<div $display id='groupextender-tab-{$uid}' class='group-extender-tab-content-container'>";

	$tab_content .= elgg_view("group-extender/tabs/{$type}", array(
		'group' => $group,
		'tab_id' => $uid,
	)) . "</div>";
}

echo "<div id='group-extender-group-tabs'>";
echo $tab_content;
echo "</div>";