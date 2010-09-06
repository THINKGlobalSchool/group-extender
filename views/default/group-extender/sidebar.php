<?php
	/**
	 * Group-Extender custom profile sidebar
	 * 
	 * @package Group-Extender
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Jeff Tilson
	 * @copyright THINK Global School 2010
	 * @link http://www.thinkglobalschool.com/
	 * 
	 */
	
	// Some content not available for non-members
	if ($vars['view_all']) {
		$content = elgg_view('groups/members',array('entity' => $vars['entity']));
	}
	
	echo $content;
?>