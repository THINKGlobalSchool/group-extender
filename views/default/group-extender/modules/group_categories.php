<?php
/**
 * Group-Extender list group categories
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$options = array(
	'type' => 'object',
	'subtype' => 'group_category',
	'limit' => 0,
	'full_view' => FALSE,
	'order_by_metadata' => array('name' => 'order_priority'),
);

$content = elgg_list_entities_from_metadata($options);

echo $content;