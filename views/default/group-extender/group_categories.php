<?php
/**
 * Group-Extender Group Category list for groups filter tab
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Categories Module
$ajaxmodule = elgg_view('modules/genericmodule', array(
	'view' => 'group-extender/modules/group_categories',
	'module_id' => 'groups-all-categories-ajaxmodule',
	'view_vars' => array(),
));

$categories_module = elgg_view_module(
	'info', 
	'', 
	$ajaxmodule,
	array(
		'id' => 'groups-all-categories-module'
	));

echo $categories_module;

echo "<div id='groups-all-group-list'></div>";