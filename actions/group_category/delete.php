<?php
/**
 * Group-Extender group category delete action
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Get inputs
$category_guid = get_input('guid');

$category = get_entity($category_guid);

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

if (elgg_instanceof($category, 'object', 'group_category') && $category->delete()) {
	// Success
	system_message(elgg_echo('group-extender:success:deletecategory'));
	forward('admin/groupextender/categories');	
}

access_show_hidden_entities($access_status);

// Error
register_error(elgg_echo('group-extender:error:deletecategory'));
forward(REFERER);
