<?php
/**
 * Group-Extender Dashboard Invidual Group 
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group_guid = elgg_extract('group_guid', $vars);

$db_prefix = elgg_get_config('dbprefix');

elgg_push_context('widgets');
$content = elgg_list_river(array(
	'limit' => 5,
	'pagination' => TRUE,
	'joins' => array(
		"JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid",
		"JOIN {$db_prefix}entities ec ON ec.guid = e1.container_guid"
	),
	'wheres' => array("(e1.container_guid = $group_guid || ec.container_guid = $group_guid)"),
));
elgg_pop_context();

if ($content) {
	echo $content;
} else {
	$no_activity = elgg_echo('group-extender:label:noactivity');
	echo "<strong><center>{$no_activity}</center></strong>";
}