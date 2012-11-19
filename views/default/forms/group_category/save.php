<?php
/**
 * Group-Extender edit group category
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Map values
$title = elgg_extract('title', $vars, '');
$guid = elgg_extract('guid', $vars, NULL);
$description = elgg_extract('description', $vars, '');
$enabled = elgg_extract('enabled', $vars, 'yes');

// Check if we've got an entity, if so, we're editing.
if ($guid) {
	$entity_hidden  = elgg_view('input/hidden', array(
		'name' => 'category_guid', 
		'value' => $guid,
	));
} 

// Labels/Input
$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'value' => $title
));

$description_label = elgg_echo("description");
$description_input = elgg_view("input/longtext", array(
	'name' => 'description', 
	'value' => $description
));

$hidden_label = elgg_echo('group-extender:label:enabled');
$hidden_input = elgg_view('input/dropdown', array(
	'name' => 'enabled', 
	'value' => $enabled,
	'options_values' => array(
		'yes' => elgg_echo('option:yes'),
		'no' => elgg_echo('option:no'),
	)
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit', 
	'value' => elgg_echo('save')
));	

// Build Form Body
$form_body = <<<HTML

<div class='margin_top'>
	<div>
		<label>$title_label</label><br />
        $title_input
	</div><br />
	<div>
		<label>$description_label</label><br />
        $description_input
	</div><br />
	<div>
		<label>$hidden_label</label><br />
        $hidden_input
	</div><br />
	<div class='elgg-foot'>
		$submit_input
		$entity_hidden
	</div>
</div>
HTML;

echo $form_body;
