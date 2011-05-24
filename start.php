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
	$extender_js = elgg_get_simplecache_url('js', 'group-extender/extender');
	elgg_register_js('elgg.groupextender', $extender_js);
	
	// Register my own page handler
	elgg_register_page_handler('groups','group_extender_page_handler');
	
	// CSS
	elgg_extend_view('css/elgg', 'css/group-extender/css');
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
	