<?php
/**
 * Group-Extender Static Tab
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['group'] Group to populate tab from
 * @uses $vars['tab_id'] Which tab we're displaying
 */

$group = elgg_extract('group', $vars);
$tab_id = elgg_extract('tab_id', $vars);
$tab = group_extender_get_tab_by_id($group, $tab_id);

echo elgg_view('output/longtext', array(
	'value' => $group->$tab['params']['static_content_meta'],
));