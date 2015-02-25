<?php
/**
 * Group-Extender Group Copy Content Popup
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 * 
 */

$guid = get_input('guid');

$entity = get_entity($guid);

$params = array('entity' => $entity);

// Trigger a hook to allow plugins to customize the copy action
$copy_action = elgg_trigger_plugin_hook('groupcopyaction', 'entity', $params, elgg_normalize_url('action/groups/copycontent'));

$form_vars = array();
$form_vars['action'] = $copy_action;

if (elgg_instanceof($entity, 'object')) {
	$content = elgg_view_form('groups/copycontent', $form_vars, array('entity' => $entity));
} else {
	$content = elgg_echo('group-extender:label:invalidentity');
}

$copy_text = elgg_echo('group-extender:label:copytogroup');

$content = elgg_view_module('info', $copy_text, $content);

echo "<div class='group-extender-cb-popup'>{$content}</div>";