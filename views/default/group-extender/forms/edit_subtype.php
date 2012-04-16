<?php
/**
 * Group-Extender Edit Subtype Form
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['tab'] Individual tab to edit
 */

$tab = elgg_extract('tab', $vars);

// Subtype content
$show_type_label = elgg_echo('group-extender:label:showsubtype');
$show_type_input = elgg_view('input/dropdown', array(
	'name' => 'subtype',
	'options_values' => group_extender_get_group_subtypes(),
	'value' => $tab['params']['subtype'],
));

$tag_label = elgg_echo('group-extender:label:showtag');
$tag_input = elgg_view('input/text', array(
	'name' => 'tag',
	'value' => $tab['params']['tag'],
));

// Hidden param inputs
$param_subtype_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'subtype',
));

$param_tag_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'tag',
));

$content = <<<HTML
	<div>
		<label>$show_type_label</label><br />
		$show_type_input
	</div><br />
	<div>
		<label>$tag_label</label><br />
		$tag_input
	</div>
	$param_tag_hidden
	$param_subtype_hidden
HTML;

echo $content;