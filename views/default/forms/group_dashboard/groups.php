<?php
/**
 * Group-Extender Dashboard Group Select Form
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Get options
$options = array(
	'type' => 'group',
	'limit' => 0,
);

$groups = new ElggBatch('elgg_get_entities', $options);

$options_values = array();

foreach ($groups as $group) {
	$options_values[$group->guid] = $group->name;
}

$group_select_input = elgg_view('input/dropdown', array(
	'name' => 'group_guids',
	'id' => 'groups-dashboard-group-select',
	//'value' => '',
	'options_values' => $options_values,
	'multiple' => 'multiple',
	'size' => 6,
));

$group_select_label = elgg_echo('group-extender:label:groupselect');
$categories_label = elgg_echo('item:object:group_category');

$categories = elgg_get_plugin_setting('dashboard_categories', 'group-extender');

$category_links = '';

if ($categories) {
	$categories = unserialize($categories);
	foreach ($categories as $category_guid) {
		$category = get_entity($category_guid);
		if (elgg_instanceof($category, 'object', 'group_category')) {
			$groups_url = '';
			$groups = groupcategories_get_groups($category, 0);

			foreach ($groups as $group) {
				$groups_url .= $group->guid . ',';
			}
				
			$category_links .= "<a href='{$groups_url}' class='groupdashboard-preset-link'>{$category->title}</a><br />";
		}
	}
}

$content = <<<HTML
	<table style='width: 100%;'>
		<tr>
			<td style='width: 50%'>
				<label>$group_select_label</label><br /><br />
				$group_select_input
			</td>
			<td style='width: 50%'>
				<label>$categories_label</label><br /><br />
				$category_links
			</td>
		</tr>
	</table>
HTML;

echo $content;