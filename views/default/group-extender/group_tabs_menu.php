<?php
/**
 * Group-Extender Group Tabs Menu View
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

// New layout switch
if (!$group->new_layout) {
	return;
}

$group_tabs = group_extender_get_tabs($group);

$count = 0;

// Determine default tab
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
	// Skip hidden tabs
	if ($tab['hidden'] && !$group->canEdit()) {
		continue;
	}

	$count++;
	
	$title = $group_tabs[$uid]['title'];
	$priority = $group_tabs[$uid]['priority'];
	$type = $group_tabs[$uid]['type'];

	// Set selected
	if (strpos(current_page_url(), 'groups/profile') !== false) {
		if (!$default) {
			$selected = $group_tabs[$uid]['priority'] == group_extender_get_lowest_tab_priority($group);	
			if ($selected) {
				$default = $uid;
			}
		} else {
			$selected = $uid == $default ? true : false;
		}
	}

	// Simple tab interface for switching between feed lookup and manual entry
	elgg_register_menu_item('group-extender-tab-menu', array(
		'name' => "group_extender_{$title}_{$uid}",
		'text' => $title,
		'href' => "#groupextender-tab-{$uid}",
		'priority' => $priority,
		'item_class' => $selected ? 'elgg-state-selected' : '',
		'class' => "group-extender-tab-menu-item",
		'id' => $uid,
		'data-group_url' => $group->getURL()
	));
}

$menu = elgg_view_menu('group-extender-tab-menu', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu elgg-menu-owner-block elgg-menu-owner-block-default'
));

echo <<<HTML
	<div id='group-extender-group-tabs-menu'>
		$menu
	</div>
HTML;

// Tabs JS won't be loaded anywhere but on the profile page, make links elsewhere behave
$js = <<<JAVASCRIPT
	<script type="text/javascript">
		$(document).ready(function() {
			if (elgg.groupextender.tabs == undefined) {
				$('.group-extender-tab-menu-item, .group-extender-customize-nav-link').click(function(event) {
					event.preventDefault();
					var go_url = $(this).data('group_url') + "#tab:" + $(this).attr('id');
					window.location.href = go_url;
				});
			}
		});
	</script>
JAVASCRIPT;

echo $js;