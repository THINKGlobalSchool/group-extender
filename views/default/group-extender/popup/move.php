<?php
/**
 * Group-Extender Group Move Content Popup
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = get_input('guid');

$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object')) {
	$content = elgg_view_form('groups/movecontent', array(), array('entity' => $entity));
} else {
	$content = elgg_echo('group-extender:label:invalidentity');
}

echo "<div class='group-extender-cb-popup'>{$content}</div>";