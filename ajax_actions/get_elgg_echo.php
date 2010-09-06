<?php
	/**
	 * Group-Extender ajax endpoint to return the result of elgg echo
	 * 
	 * @package Group-Extender
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Jeff Tilson
	 * @copyright THINK Global School 2010
	 * @link http://www.thinkglobalschool.com/
	 * 
	 */
	
	// start the elgg engine
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');
	$value = get_input('value');
	echo elgg_echo('group-extender:nav:' . $value);
?>