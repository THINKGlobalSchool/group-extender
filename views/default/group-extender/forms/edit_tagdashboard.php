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

// Hidden param input
$param_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param',
	'value' => 'custom_tags',
));

echo "<div><label>$custom_label</label>$custom_input</div>$param_hidden";