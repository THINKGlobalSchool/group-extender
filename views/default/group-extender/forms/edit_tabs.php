<?php
/**
 * Group-Extender Edit tabs form
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */


$group = elgg_get_page_owner_entity();

if (!$group) {
	$group = elgg_extract('group', $vars);
}

if (!$group || !$group->canEdit()) {
	forward(REFERER);
}

$module_title = elgg_echo('group-extender:label:currenttabs');

// Refresh link
$module_title .= elgg_view('output/url', array(
	'text' => elgg_echo('group-extender:label:refresh'),
	'href' => '#',
	'id' => "group-extender-tab-refresh-submit",
));

$current_tabs_content = elgg_view('group-extender/forms/current_tabs', array('group_guid' => $group->guid));

$current_tabs_container = "<div id='group-extender-current-tabs-form'>{$current_tabs_content}</div>";

$current_tabs_module = elgg_view_module('info', $module_title, $current_tabs_container);

$add_tab_button = elgg_view('input/submit', array(
	'name' => 'add_tab_button',
	'id' => 'add-tab-button',
	'class' => 'group-extender-tab-editor elgg-button elgg-button-action',
	'value' => elgg_echo('group-extender:label:addtab'),
	'href' => 'ajax/view/group-extender/forms/edit_tab?group_guid=' . $group->guid
));

$new_tab_form = elgg_view('group-extender/forms/edit_tab', array('group_guid' => $group->guid));

// Trigger a change when the new tab form loads (for first tab type)
echo <<<JAVASCRIPT
<script type='text/javascript'>
	elgg.register_hook_handler('init', 'system', function() {
		$('#group-extender-tab-type-select').change();
	});
</script>
JAVASCRIPT;

// Hidden group input
$group_hidden = elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $group->guid,
));

echo $current_tabs_module;
echo $group_hidden;
echo $add_tab_button;
echo "<div id='group-extender-tab-editor-container'></div>";

