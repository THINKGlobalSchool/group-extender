<?php
/**
 * Group-Extender Group Category Picker
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$value = elgg_extract('value', $vars);
$label = elgg_extract('label', $vars, elgg_echo('item:object:group_category'));
$name = elgg_extract('name', $vars, 'categories_list');

// Get all site categories
$category_entities = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'group_category',
	'limit' => 0,
));


$categories = array();

foreach($category_entities as $category) {
	$categories[$category->title] = $category->guid;
}

if (empty($value)) {
	$value = array();
}

if (!empty($categories)) {
	if (!is_array($categories)) {
		$categories = array($categories);
	}

	$categories_input = elgg_view('input/checkboxes', array(
		'options' => $categories,
		'value' => $value,
		'name' => $name,
		'align' => 'horizontal',
	));

	echo <<<HTML
		<div class="categories">
			<label>$label</label><br />
			$categories_input
		</div>
HTML;
}
