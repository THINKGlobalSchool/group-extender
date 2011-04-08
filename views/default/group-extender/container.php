<?php
/**
 * Group-Extender content container
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$tab = get_input('tab', 'activity');


echo "<div id='group_tools_latest' class='clearfloat group-extend'>";

echo 	"<div class='group_tool_widget activity clearfloat'>";
echo 		elgg_view('group-extender/activity', array('entity' => $vars['entity']));
echo 	"</div>";

echo 	elgg_view("groups/forum_latest",array('entity' => $vars['entity']));

echo 	elgg_view("groups/tool_latest",array('entity' => $vars['entity']));

echo "</div>";
