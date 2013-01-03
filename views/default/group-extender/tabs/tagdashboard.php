<?php
/**
 * Group-Extender Tagdashboard Tab
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['group'] Group to populate tab from
 * @uses $vars['tab_id'] Which tab we're displaying
 */

elgg_load_js('elgg.tagdashboards');
elgg_load_css('elgg.tagdashboards');

$group = elgg_extract('group', $vars);
$tab_id = elgg_extract('tab_id', $vars);
$tab = group_extender_get_tab_by_id($group, $tab_id);

$custom = string_to_tag_array($tab['params']['custom_tags']);
$one_column = $tab['params']['one_column'];

if ($one_column == 'on') {
	$no_float = 'no-float';
}

// Get subtypes
$subtypes = tagdashboards_get_entity_subtypes_from_metadata(array(
	'container_guid' => $group->guid,
));

// Tag Dashboard Content inputs
$td_type_input = elgg_view('input/hidden', array(
	'name' => 'type', 
	'id' => 'type', 
	'value' => 'custom',
));

$td_subtypes_input = elgg_view('input/hidden', array(
	'name' => 'subtypes', 
	'id' => 'subtypes', 
	'value' => json_encode($subtypes)
));

$td_custom_input = elgg_view('input/hidden', array(
	'name' => 'custom_tags',
	'id' => 'custom_tags',
	'value' => json_encode($custom)
));

$td_search_input = elgg_view('input/hidden', array(
	'name' => 'search', 
	'id' => 'search', 
	'value' => NULL,
));

$td_container_guid_input = elgg_view('input/hidden', array(
	'name' => 'container_guid', 
	'id' => 'container_guid', 
	'value' => $group->guid,
));

$content .= <<<HTML
	<div id='tagdashboard-tab-$tab_id' class='tagdashboard-tab-container portfolio-left'>
		<div class='tagdashboard-options'>
			$td_type_input
			$td_subtypes_input
			$td_search_input
			$td_container_guid_input
			$td_custom_input
		</div>
		<div class='tagdashboards-content-container $no_float'></div>
	</div>
HTML;


echo $content;