<?php
/**
 * Group-Extender topbar view
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 * 
 */

$group = elgg_extract('entity', $vars);

$icon_url = $group->getIconURL('tiny');

$icon = elgg_view('output/img', array(
	'src' => $icon_url,
	'alt' => $group->name,
	'title' => $group->name,
	'class' => ''
));

echo elgg_view_image_block($icon, $group->name, array('class' => 'group-extender-my-groups-item'));