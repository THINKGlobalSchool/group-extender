<?php
/**
 * Group-Extender Subtype Module (For genericmodule)
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['group_guid'] Group to grab tab from
 * @uses $vars['tab_id'] Which tab to display
 */

$group_guid = elgg_extract('group_guid', $vars);
$tab_id = elgg_extract('tab_id', $vars);
$group = get_entity($group_guid);

$tab = group_extender_get_tab_by_id($group, $tab_id);

$options = array(
	'type' => 'object',
	'subtype' => $tab['params']['subtype'],
	'container_guid' => $group->guid,
	'limit' => 10,
	'pagination' => TRUE,
	'full_view' => FALSE,
);

$content = elgg_list_entities($options);

if (!$content) {
	echo "<h3 class='center'>" . elgg_echo('group-extender:label:noresults') . "</h3>"; 
} else {
	echo $content;
}