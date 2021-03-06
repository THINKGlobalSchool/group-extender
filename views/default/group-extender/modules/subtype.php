<?php
/**
 * Group-Extender Subtype Module (For genericmodule)
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['group_guid'] Group to grab tab from
 * @uses $vars['tab_id'] Which tab to display
 */

$group_guid = elgg_extract('group_guid', $vars);
$tab_id = elgg_extract('tab_id', $vars);
$group = get_entity($group_guid);

$tab = group_extender_get_tab_by_id($group, $tab_id);

$sort_by = $tab['params']['sortby'];

$options = array(
	'type' => 'object',
	'subtype' => $tab['params']['subtype'],
	'container_guid' => $group->guid,
	'limit' => 10,
	'pagination' => TRUE,
	'full_view' => FALSE,
);

// If sort by name supplied, add necessary SQL
if ($sort_by == "name") {
	$dbprefix = elgg_get_config('dbprefix');
	$options['joins'] = "JOIN {$dbprefix}objects_entity oe on oe.guid = e.guid";
	$options['order_by'] = "oe.title ASC";
}

if ($tab['params']['all_content'] == 'on') {
	unset($options['container_guid']);
}

// Workaround for photo/album views
if ($options['subtype'] == 'album' || $options['subtype'] == 'image') {
	set_input('search_viewtype', 'gallery'); 
	$options['list_type'] = 'gallery';

	// Get photos from albums contained by this group
	if ($options['subtype'] == 'image') {
		$dbprefix = elgg_get_config('dbprefix');

		unset($options['container_guid']);
		
		$options['joins'] = array("JOIN {$dbprefix}entities e1 ON e1.guid = e.container_guid");
		
		if ($tab['params']['all_content'] != 'on') {
			$options['wheres'] = array("(e1.container_guid = $group_guid)");
		}
	}
} else if ($options['subtype'] == 'book') {	
	$options = array(
		'type' => 'object', 	
		'subtype' => 'book', 
		'full_view' => false, 
		'relationship' => READING_LIST_RELATIONSHIP,
		'relationship_guid' => $group->guid,
		'inverse_relationship' => TRUE,
	);
}

// If a tag is supplied, restrict it
if (!empty($tab['params']['tag'])) {
	$options['metadata_name_value_pairs'] = array('name' => 'tags', 'value' => $tab['params']['tag']);
	$options['metadata_case_sensitive'] = FALSE;
}

$content = elgg_list_entities_from_relationship($options);

if (!$content) {
	echo "<h3 class='center'>" . elgg_echo('group-extender:label:noresults') . "</h3>"; 
} else {
	echo $content;
}

// init move/copy lightboxes
$js = "<script type='text/javascript'>elgg.groupextender.initMoveCopyLightbox();</script>";

echo $js;
