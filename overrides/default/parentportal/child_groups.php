<?php
/**
 * Group-Extender Parent Portal Child Groups Override
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Other groups module				
$other_content .= elgg_view('modules/genericmodule', array(
	'view' => 'group-extender/modules/filter_class_groups',
	'module_id' => 'pp-groups-module',
	'module_class' => 'pp-groups-module',
	'view_vars' => array('guid' => $vars['child_guid'], 'filter' => 'off'), 
));

// Class groups module				
$class_content .= elgg_view('modules/genericmodule', array(
	'view' => 'group-extender/modules/filter_class_groups',
	'module_id' => 'pp-groups-module',
	'module_class' => 'pp-groups-module',
	'view_vars' => array('guid' => $vars['child_guid'], 'filter' => 'on'), 
));

// Main content
$content = elgg_view_menu('groups_class_other_menu', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default'
));

$content .= "<div id='other-groups' class='groups-class-filter-container' style='display: none;'>$other_content</div>";
$content .= "<div id='class-groups' class='groups-class-filter-container'>$class_content</div>";

echo elgg_view_module('featured', elgg_echo("parentportal:title:childgroups"), $content, array(
	'id' => 'parentportal-module-child-groups',
	'class' => 'parentportal-module',
));