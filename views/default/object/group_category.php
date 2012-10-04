<?php
/**
 * Group Extender group category object view
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$full = elgg_extract('full_view', $vars, FALSE);

$category = (isset($vars['entity'])) ? $vars['entity'] : FALSE;

if (!$category) {
	return '';
}

$linked_title = "<h3 style='padding-top: 14px;'><a href=\"{$category->getURL()}\" title=\"" . htmlentities($category->title) . "\">{$category->title}</a></h3>";

$metadata = elgg_view_menu('entity', array(
	'entity' => $category,
	'handler' => 'group_category',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

if ($full) {
	$description = elgg_view('output/longtext', array('value' => $category->description));

	$groups_module = elgg_view('group-extender/groups_categories.php', array('guid' => $category->guid));
		
	// brief view
	$params = array(
		'entity' => $category,
		'metadata' => $metadata,
	);
	$list_body = elgg_view('object/elements/summary', $params);


	$category_info = elgg_view_image_block('', $list_body);
	
	echo <<<HTML
		$category_info
		$description
		$groups_module
HTML;
} else {
	// brief view
	$params = array(
		'title' => $category->title,
		'entity' => $category,
		'metadata' => $metadata,
	);
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block('', $list_body);
}