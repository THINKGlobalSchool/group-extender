<?php
/**
 * Group-Extender list groups in category
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = elgg_extract('guid', $vars, NULL);

// Groups Module
$ajaxmodule = elgg_view('modules/genericmodule', array(
	'view' => 'group-extender/modules/category_groups',
	'module_id' => 'groups-all-groups-ajaxmodule',
	'view_vars' => array('guid' => $guid),
));

echo $ajaxmodule;

echo "<script>elgg.modules.genericmodule.init();</script>";