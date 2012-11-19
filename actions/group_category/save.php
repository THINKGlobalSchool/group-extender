<?php
/**
 * Group-Extender group category edit action
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Get inputs
$title = get_input('title');
$description = get_input('description');
$hidden = get_input('hidden', 0);
$category_guid = get_input('category_guid', NULL);
$enabled = get_input('enabled', 'yes');

// Create Sticky form
elgg_make_sticky_form('category-edit-form');

// Check inputs
if (!$title) {
	register_error(elgg_echo('group-extender:error:requiredfields'));
	forward(REFERER);
}

$access_status = access_get_show_hidden_status();

// New Category
if (!$category_guid) {
	$category = new ElggObject();
	$category->access_id = ACCESS_PUBLIC;
	$category->subtype = 'group_category';
} else { // Editing
	access_show_hidden_entities(true);
	
	$category = get_entity($category_guid);
	$category->access_id = ACCESS_PUBLIC;
	if (!elgg_instanceof($category, 'object', 'group_category')) {
		register_error(elgg_echo('group-extender:error:editcategory'));
		forward(REFERER);
	}
}

$category->title = $title;
$category->description = $description;

// Try saving
if (!$category->save()) {
	// Error.. say so and forward
	register_error(elgg_echo('group-extender:error:savecategory'));
	forward(REFERER);
} 

// Disable the category
if ($enabled == 'no') {
	$category->disable('gcdisabled', FALSE);
} else if ($enabled == 'yes') {
	$category->enable(); // Enable it
}

access_show_hidden_entities($access_status);

// Clear Sticky form
elgg_clear_sticky_form('category-edit-form');

system_message(elgg_echo('group-extender:success:savecategory'));
forward(elgg_get_site_url() . 'admin/groupextender/categories');
