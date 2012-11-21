<?php
/**
 * Group-Extender Group Move Content Form
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$entity = elgg_extract('entity', $vars);

$options = array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => elgg_get_logged_in_user_guid(),
	'inverse_relationship' => FALSE,
	'limit' => 0,
);

// Admins can access all groups
if (elgg_is_admin_logged_in()) {
	unset($options['relationship']);
	unset($options['relationship_guid']);
	unset($options['inverse_relationship']);
}

$groups = new ElggBatch('elgg_get_entities_from_relationship', $options);

$group_options = array();

foreach ($groups as $group) {
	$group_options[$group->guid] = $group->name;
}


$select_group_label = elgg_echo('group-extender:label:selectgroup');
$select_group_input = elgg_view('input/dropdown', array(
	'name' => 'group_guid',
	'options_values' => $group_options,
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit', 
	'value' => elgg_echo('group-extender:label:move'),
	'class' => 'elgg-button elgg-button-action ge-move-to-group-submit',
	'id' => 'ge-move-to-group-submit-' . $entity->guid,
));

$entity_hidden = elgg_view('input/hidden', array(
	'name' => 'entity_guid',
	'value' => $entity->guid,
));

$content = <<<HTML
	<div>
		<label>$select_group_label</label><br />
		$select_group_input
	</div>
	<div class='elgg-foot'>
		$submit_input
		$entity_hidden
	</div>
HTML;

echo $content;