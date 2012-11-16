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
$module =  elgg_view('modules/ajaxmodule', array(
	'title' => '',
	'subtypes' => array('group_category'),
	'limit' => 15,
	'module_type' => 'inline',
	'module_class' => 'group-categories-module',
	'module_id' => 'groups-all-categories-ajaxmodule',
));

echo $module;

echo "<div id='groups-all-group-list'></div>";