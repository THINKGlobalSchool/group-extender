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
	
	// Init
	function group_extender_init() {
	
		global $CONFIG;
		
		// Un-register the groups page handler so I can mess with it
		unregister_page_handler('groups');
		
		// Register my own page handler
		register_page_handler('groups','group_extender_page_handler');
		
		// CSS
		elgg_extend_view('css', 'group-extender/css');
	}
	
	/**
	 * Group extender page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function group_extender_page_handler($page) {
		global $CONFIG;

		if (isset($page[0])) {	
			// If we've got a int (guid), load up custom group page
			if ($group = get_entity((int)$page[0])) {
				
				set_page_owner($group->getGUID());
				
				// Hide some items from closed groups when the user is not logged in.
				$view_all = true;

				$groupaccess = group_gatekeeper(false);
				if (!$groupaccess) { 
					$view_all = false;
				}
				
				$title = $group->name;
				$sidebar = elgg_view('group-extender/sidebar', array('view_all' => $view_all, 'entity' => $group));
				
				$content = elgg_view('navigation/breadcrumbs') . elgg_view('group-extender/maincontent', array('view_all' => $view_all, 'entity' => $group));
				
				$body = elgg_view_layout('one_column_with_sidebar', $content, $sidebar);
				
				echo elgg_view_page($title, $body);
			} else {
				groups_page_handler($page);
			}
		}
	}
		
	register_elgg_event_handler('init', 'system', 'group_extender_init');
?>