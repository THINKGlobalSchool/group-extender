<?php
/**
 * Group-Extender activity view
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$limit = get_input("limit", 10);
$offset = get_input("offset", 0);
$group_guid = $vars['entity']->getGUID();

// Sanitise variables -- future proof in case they get sourced elsewhere
$limit = (int) $limit;
$offset = (int) $offset;
$group_guid = (int) $group_guid;

$entities = elgg_get_entities(array(
	'container_guids' => $group_guid,
	'group_by' => 'e.guid', 
	'limit' => 0,
));

$entity_guids = array();
foreach ($entities as $entity) {
	$entity_guids[] = $entity->getGUID();
}

if (count($entity_guids) > 0) {
	$river_items = elgg_view_river_items('', $entity_guids, '', '', '', '', $limit);
	$river_items .= elgg_view('riverdashboard/js');
} else {
	$river_items .= elgg_echo('groups:no_activity');
}

set_context('activity');	
$title .= "<h3>" . elgg_echo('group-extender:groupactivity') . "</h3>";
$thewire .= elgg_view("thewire/forms/add", array('group' => $vars['entity'], 'location' => 'referer'));
set_context($context);

echo $title . $thewire . $river_items;