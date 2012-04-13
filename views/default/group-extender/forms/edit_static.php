<?php
/**
 * Group-Extender Edit Static Form
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

// Static content
$content_label = elgg_echo('group-extender:label:staticcontent');
$content_input = elgg_view('input/longtext', array(
	'id' => 'static-content',
	'name' => 'static_content',
	'value' => $tab['params']['static_content'],
));

// Hidden param input
$param_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param',
	'value' => 'static_content',
));

echo "<div><label>$content_label</label>$content_input</div>$param_hidden";