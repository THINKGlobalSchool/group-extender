<?php
/**
 * Group-Extender admin list groups in category
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = elgg_extract('guid', $vars, NULL);

$category = get_entity($guid);

if (!$category) {
	return;
}

$category_groups = groupcategories_get_groups($category, 0);

if ($category_groups) {
	foreach($category_groups as $group) {
		$icon = elgg_view_entity_icon($group, 'tiny');
		
		$delete_button = elgg_view("output/confirmlink",array(
			'href' => "action/group-extender/removegroup?group_guid={$group->guid}&category_guid={$category->guid}",
			'text' => "<span class=\"elgg-icon elgg-icon-delete right\"></span>",
			'confirm' => elgg_echo('group-extender:removeconfirm'),
			'text_encode' => false,
			'id' => $group->guid,
			'class' => 'remove-from-category',
			'name' => $category->guid,
		));
		
		// Register the remove menu item
		$params = array(
			'name' => 'remove-from-category',
			'text' => $delete_button,
			'href' => FALSE,
		);
		
		elgg_register_menu_item('group-category-menu', $params);
		
		$metadata = elgg_view_menu('group-category-menu', array(
			'entity' => $group,
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		));
		

		$title = "<a href=\"" . $group->getUrl() . "\">" . $group->name . "</a>";
		$params = array(
			'entity' => $group,
			'title' => $title,
			'metadata' => $metadata,
		);

		$list_body = elgg_view('user/elements/summary', $params);

		$content .= elgg_view_image_block($icon, $list_body);
	}
} else {
	$content = elgg_echo('group-extender:label:nogroups');
} 

$group_form_label = elgg_echo('group-extender:label:addgroup');
$group_form = elgg_view_form('group_category/addgroup', array('id' => 'group-category-add-group-form'), array('category_guid' => $category->guid));
$group_form_module = elgg_view_module('inline', $group_form_label, $group_form);

$groups_label = elgg_echo('group-extender:label:groups');
$groups_module = elgg_view_module('inline', $groups_label, $content);

echo $group_form_module . $groups_module;