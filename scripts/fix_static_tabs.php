<?php
/** 
 * Migrate static content tabs to metadata
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
admin_gatekeeper();

echo "<h1>FIX STATIC CONTENT TABS</h1>";

$groups = elgg_get_entities(array(
	'type' => 'group',
	'limit' => 0
));

$go = get_input('go', false);

foreach ($groups as $group) {
	$tabs = group_extender_get_tabs($group);
	echo "<br />GROUP: " . $group->guid . "<br />";
	foreach ($tabs as $idx => $tab) {
		if ($tab['type'] == 'static') {
			if ($go && isset($tab['params']['static_content'])) {
				$migrated = ' - MIGRATED!';
				$content = $tab['params']['static_content'];

				$meta_name = "tab_{$idx}";

				$tab['params']['static_content_meta'] = $meta_name;

				$group->$meta_name = $content;

				unset($tab['params']['static_content']);

				group_extender_update_tab($group, $idx, $tab);
			}

			echo "Static Tab: {$idx} - Name: " . $tab['title'] . "{$migrated}<br />";
			$content = $tab['params']['static_content'];			
		}
	}
}
