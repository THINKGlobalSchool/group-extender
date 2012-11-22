<?php
/**
 * Group Extender Group Icon View
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

// Only display group hover for admins (at the moment)
if (elgg_is_admin_logged_in()) {
	echo "<div class='elgg-avatar'>";

	echo elgg_view("icon/default", $vars);

	$params = array(
		'entity' => $vars['entity'],
	);

	if (!$vars['hide_menu']) {
		echo elgg_view_icon('hover-menu');
		echo elgg_view_menu('group_hover', $params);	
	}

	echo "</div>";
}