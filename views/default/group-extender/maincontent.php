<?php
	/**
	 * Group-Extender custom profile main content
	 * 
	 * @package Group-Extender
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Jeff Tilson
	 * @copyright THINK Global School 2010
	 * @link http://www.thinkglobalschool.com/
	 * 
	 */

	$content = elgg_view_entity($vars['entity'], TRUE) . "<br />";

	if ($vars['view_all']) {
		// Display everything
		$context = get_context();
		$content .= elgg_view('group-extender/navigation');
		$content .= elgg_view('group-extender/container', array('entity' => $vars['entity']));
		
	} else {
		$content .= elgg_view('groups/closedmembership', array('entity' => $vars['entity'], 'user' => $_SESSION['user'], 'full' => true));
	}
	
	echo $content;
?>