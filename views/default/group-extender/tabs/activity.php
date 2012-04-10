<?php
/**
 * Group-Extender Activity Tab
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['group'] Group to populate tab for 
 */

$group = elgg_extract('group', $vars);

echo elgg_view('modules/genericmodule', array(
	'view' => "group-extender/modules/activity",
	'view_vars' => array('group_guid' => $group->guid), 
));