<?php
/**
 * Group-Extender Dashboard Groups
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group_guids = elgg_extract('group_guids', $vars);

// Get options
$options = array(
	'type' => 'group',
	'guids' => $group_guids,
	'limit' => 0,
);

$groups = new ElggBatch('elgg_get_entities', $options);

foreach ($groups as $group) {	
	// Create group module				
	$module = elgg_view('modules/genericmodule', array(
		'view' => 'group-extender/modules/group',
		'module_id' => 'group-dashboard-group-module-' . $group->guid,
		'view_vars' => array('group_guid' => $group->guid), 
	));

	$title_link = "<a href='{$group->getURL()}'>{$group->name}</a>";

	echo elgg_view_module('featured', $title_link, $module, array(
		'class' => 'group-dashboard-module',
	));
}
	
echo "<script>elgg.modules.genericmodule.init();</script>";

?>