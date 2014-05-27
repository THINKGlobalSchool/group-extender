<?php
/**
 * Group-Extender Navigation Settings Form
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */


$group = elgg_extract('entity', $vars);

if ($group->new_layout) {
	$new_layout = 1;
} else {
	$new_layout = 0;
}

// Can save title
$new_layout_label = elgg_echo('group-extender:label:enable_new_layout');
$new_layout_input = elgg_view('input/dropdown', array(
	'name' => 'new_layout',
	'value' => $new_layout,
	'options_values' => array(
		1 => elgg_echo('group-extender:label:yes'),
		0 => elgg_echo('group-extender:label:no')
	)
));

// Save input
$save_input = elgg_view('input/submit', array(
	'name' => 'save',
	'value' => elgg_echo('save'),
));

// Hidden group input
$group_hidden = elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $group->guid,
));

$body = <<<HTML
	<div>
		<label>$new_layout_label</label>
		$new_layout_input
	</div>
	<div class='elgg-foot'>
		$save_input
		$group_hidden
	</div>
HTML;

$form = elgg_view("input/form", array(
	'body' => $body,
	'id' => "",
	'action' => elgg_normalize_url("action/groupextender/nav_settings"),
));

echo elgg_view_module('info', elgg_echo('group-extender:label:nav_settings'), $form);