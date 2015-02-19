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

$category_options = array(0 => 'None');

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


$archive_category_label = elgg_echo('group-extender:label:archivecategory');
$archive_category_input = elgg_view('input/dropdown', array(
		'name' => 'params[archive_category]',
		'options_values' => $category_options,
		'value' => $vars['entity']->archive_category,
));

$hidden_category_label = elgg_echo('group-extender:label:hiddencategory');
$hidden_category_input = elgg_view('input/dropdown', array(
		'name' => 'params[hidden_category]',
		'options_values' => $category_options,
		'value' => $vars['entity']->hidden_category,
));

$enable_dropdown_label = elgg_echo('group-extender:label:enablegroupdropdown');
$enable_dropdown_input = elgg_view('input/dropdown', array(
		'name' => 'params[enable_topbar_dropdown]',
		'options_values' => array(
			0 => elgg_echo('option:no'),
			1 => elgg_echo('option:yes'),
		),
		'value' => $vars['entity']->enable_topbar_dropdown,
));

$content = <<<HTML
	<div>
		<label>$class_category_label</label><br />
		$class_category_input
	</div>
	<div>
		<label>$archive_category_label</label><br />
		$archive_category_input
	</div>
	<div>
		<label>$hidden_category_label</label><br />
		$hidden_category_input
	</div>
	<div>
		<label>$enable_dropdown_label</label><br />
		$enable_dropdown_input
	</div>
HTML;

echo $content;