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

// Group by tags content
$custom_label = elgg_echo('group-extender:label:customtags');
$custom_input = elgg_view('input/text', array(
	'name' => 'custom_tags',
	'id' => 'custom-tags',
	'value' => $tab['params']['custom_tags'],
));

$column_label = elgg_echo('tagdashboards:label:columns');
$column_input = elgg_view('input/checkbox', array(
	'name' => 'one_column',
	'checked' => $tab['params']['one_column'] == 'on',
	'class' => 'tagdashboards-check-column',
));

// Hidden params inputs
$custom_param_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'custom_tags',
));

$column_param_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'one_column',
));

$content = <<<HTML
	<div>
		<label>$custom_label</label>
		$custom_input
	</div><br />
	<div>
		<label>$column_label</label>
		$column_input
	</div>
	$custom_param_hidden
	$column_param_hidden
HTML;

echo $content;