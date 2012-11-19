<?php
/**
 * Group-Extender plugin settings
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// General catgory options
$options = array(
	'types' => 'object',
	'subtypes' => 'group_category',
	'limit' => 0,
);

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$categories = elgg_get_entities($options);

$category_option = array();

foreach ($categories as $category) {
	$category_options[$category->guid] = $category->title;
}

access_show_hidden_entities($access_status);

$class_category_label = elgg_echo('group-extender:label:classcategory');
$class_category_input = elgg_view('input/dropdown', array(
		'name' => 'params[class_category]',
		'options_values' => $category_options,
	'value' => $vars['entity']->class_category,
));

$content = <<<HTML
	<div>
		<label>$class_category_label</label><br />
		$class_category_input
	</div>
HTML;

echo $content;