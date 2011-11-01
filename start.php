<?php
/**
 * Group-Extender start.php
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Register init
elgg_register_event_handler('init', 'system', 'group_extender_init');

// Init
function group_extender_init() {
	
	// Register typeaheadtags JS
	$extender_js = elgg_get_simplecache_url('js', 'groupextender/extender');
	elgg_register_simplecache_view('js/groupextender/extender');
	elgg_register_js('elgg.groupextender', $extender_js);
	
	// Register my own page handler
	//elgg_register_page_handler('groups','group_extender_page_handler');
	
	// Hook into user entitiy menu
	//elgg_register_plugin_hook_handler('register', 'menu:entity', 'group_extender_users_setup_entity_menu', 502);
	
	// CSS
	elgg_extend_view('css/elgg', 'css/group-extender/css');
	
	// Register new actions
	//$action_base = elgg_get_plugins_path() . 'group-extender/actions/group-extender';
	//elgg_register_action("groups/remove", "$action_base/remove.php");
}

/**
 * Group extender page handler, loads the JS and calls the regular group page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function group_extender_page_handler($page) {
		// Load extender JS
		elgg_load_js('elgg.groupextender');
		groups_page_handler($page);
		return true;
}

function group_extender_users_setup_entity_menu($hook, $type, $value, $params) {
	if (elgg_in_context('widgets')) {
		return $value;
	}
	
	$group = elgg_get_page_owner_entity();

	if (!elgg_instanceof($group, 'group')) {
		return $value;
	}
	
	$entity = $params['entity'];
	if (!elgg_instanceof($entity, 'user')) {
		return $value;
	}

	if ($group->canEdit() && $group->getOwnerGUID() != $entity->guid) {
		

		$remove = elgg_view('output/confirmlink', array(
			'href' => "action/groups/remove?user_guid={$entity->guid}&group_guid={$group->guid}",
			'text' => elgg_echo('group-extender:removeuser'),
		));

		$options = array(
			'name' => 'removeuser',
			'text' => $remove,
			'priority' => 999,
		);
		$value[] = ElggMenuItem::factory($options);
	} 

	return $value;
}
	