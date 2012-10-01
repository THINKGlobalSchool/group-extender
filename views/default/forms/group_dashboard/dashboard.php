<?php
/**
 * Group-Extender group dashboard admin form
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$categories = elgg_get_plugin_setting('dashboard_categories', 'group-extender');
$enable_dashboard = elgg_get_plugin_setting('enable_dashboard', 'group-extender');

if ($categories) {
	$categories = unserialize($categories);
}

$enable_dashboard_label = elgg_echo('group-extender:label:enabledashboard');
$enable_dashboard_input = elgg_view('input/dropdown', array(
	'name' => 'enable_dashboard',
	'options_values' => array(
		0 => 'No',
		1 => 'Yes',
	),
	'value' => $enable_dashboard,
));

$categories_input = elgg_view('input/groupcategories', array(
	'label' => elgg_echo('group-extender:label:showondashboard'),
	'value' => $categories,
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit', 
	'value' => elgg_echo('save')
));

$content = <<<HTML
	<div>
		<label>$enable_dashboard_label</label><br />
		$enable_dashboard_input
	</div><br />
	<div>
		$categories_input
	</div>
	<div>
		$submit_input
	</div>
HTML;

echo $content;