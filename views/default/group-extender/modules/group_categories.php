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

elgg_push_context('widgets');

$content = elgg_list_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'group_category',
	'full_view'  => FALSE,
	'limit' => 15,
));

elgg_pop_context();

if (!$content) {
	$content = elgg_echo('group-extender:label:nocategories');
}

echo $content;