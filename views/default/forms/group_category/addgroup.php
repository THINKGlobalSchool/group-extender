<?php
/**
 * Group-Extender add group form
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */
elgg_load_js('elgg.grouppicker');

// Get category guid
$category_guid = elgg_extract('category_guid', $vars);

// Labels/Input
$group_label = elgg_echo('group-extender:label:groups');
$group_input = elgg_view('input/grouppicker');

$category_input = elgg_view('input/hidden', array(
	'name' => 'category_guid',
	'value' => $category_guid,
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit', 
	'value' => elgg_echo('save'),
	'class' => 'add-to-category',
));

$form_body = <<<HTML
	<script>
		elgg.grouppicker.init();
	</script>
	<label>$group_label</label>
	$group_input 
	$submit_input
	$category_input
HTML;

echo $form_body;