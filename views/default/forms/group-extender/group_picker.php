<?php
/**
 * Group extender group picker input
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */

// only in bookmarklet context for now
if (!elgg_in_context('bookmarklet')) {
	return;
}

$guid = elgg_extract('guid', $vars, null);

if (!elgg_instanceof(elgg_get_page_owner_entity(), 'group') && !$guid) {

	echo <<<JAVASCRIPT
		<script type='text/javascript'>
			// Nuke existing hidden input
			$('input[name="container_guid"]').remove();
		</script>
JAVASCRIPT;

	$group_label = elgg_echo('group-extender:label:postgroup');

	$groups = elgg_get_entities_from_relationship(array(
		'type' => 'group',
		'relationship' => 'member',
		'relationship_guid' => elgg_get_logged_in_user_guid(),
		'inverse_relationship' => FALSE,
		'joins' => array("JOIN " . elgg_get_config("dbprefix") . "groups_entity ge ON e.guid = ge.guid"),
		'order_by' => 'ge.name ASC',
		'limit' => 0
	));

	if (count($groups)) {
		$groups_array = array('' => elgg_echo('file-extender:none'));

		// Add each group to group array for dropdown
		foreach ($groups as $g) {
			$groups_array[$g->guid] = $g->name;
		}
	
		$group_select = elgg_view('input/dropdown', array(
			'name' => 'container_guid',
			'options_values' => $groups_array,
		));
	
		$content = <<<HTML
		<div>
			<label>$group_label</label><br />
			$group_select
		</div>
HTML;
		echo $content;
	}
}