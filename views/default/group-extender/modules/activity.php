<?php
/**
 * Group-Extender Activity Module (For genericmodule)
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
$tab_id = elgg_extract('tab_id', $vars);

$db_prefix = elgg_get_config('dbprefix');
$content = elgg_list_river(array(
	'base_url' => "ajax/view/group-extender/modules/activity?t=1&group_guid={$group_guid}&tab_id={$tab_id}",
	'limit' => 10,
	'pagination' => TRUE,
	'joins' => array(
		"JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid",
		"JOIN {$db_prefix}entities ec ON ec.guid = e1.container_guid"
	),
	'wheres' => array("(e1.container_guid = $group->guid || ec.container_guid = $group->guid)"),
));

if (!$content) {
	echo "<h3 class='center'>" . elgg_echo('group-extender:label:noresults') . "</h3>"; 
} else {
	echo $content;
}