<?php
/**
 * Group extender group homepage form
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */


$group = elgg_extract('entity', $vars);

if (!elgg_instanceof($group, 'group')) {
	return;
}


$group_tabs = group_extender_get_tabs($group);

$group_tabs_options = array();

$group_tabs_options['default'] = elgg_echo('group-extender:label:default');

foreach ($group_tabs as $uid => $tab) {
	$group_tabs_options[$uid] = $tab['title'];
}

// Get current homepage if set
$homepage = $group->homepage;

if (!$homepage || $homepage == 'default') {
	$homepage_value == 'default';
} else {
	$homepage_value = $homepage;
}

// Labels
$select_label = elgg_echo('group-extender:label:selecttab');

// Inputs
$select_input = elgg_view('input/dropdown', array(
	'name' => 'page',
	'options_values' => $group_tabs_options,
	'value' => $homepage_value
));

$group_input = elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $group->guid,
));

$submit = elgg_view('input/submit', array(
	'name' => 'submit',
	'value' => elgg_echo('group-extender:label:sethomepage'),
));

$form_content = <<<HTML
	<div>
		<label>$select_label</label>: 
		$select_input
	</div>
	<div class='elgg-foot'>
		$submit
		$group_input
	</div>
HTML;


$form = elgg_view("input/form", array("body" => $form_content,
	"action" => elgg_get_site_url() . "action/groups/homepage",
	"id" => "group-extender-homepage-form"
));

echo elgg_view_module('info', elgg_echo('group-extender:label:grouphomepage'), $form);