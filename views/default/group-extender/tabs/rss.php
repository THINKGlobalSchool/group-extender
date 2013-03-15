<?php
/**
 * Group-Extender RSS Feed Tab
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['group'] Group to populate tab from
 * @uses $vars['tab_id'] Which tab we're displaying
 */

$group = elgg_extract('group', $vars);
$tab_id = elgg_extract('tab_id', $vars);

$tab = group_extender_get_tab_by_id($group, $tab_id);

switch ($tab['params']['feed_tab_type']) {
	case 'all':
		$rss_feeds = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'rss_feed',
			'container_guid' => $group->guid,
			'limit' => 0
		));

		if (count($rss_feeds)) {
			foreach ($rss_feeds as $feed) {
				$sources[$feed->title] = $feed->feed_url;
			}
		} else {
			// Nothing here.. just let feeds display no results
			$sources = array(null);
		}
		break;
	case 'url':
		$sources = array($tab['title'] => $tab['params']['feed_url']);
		break;
	case 'group_feed':
		$rss_feed = get_entity($tab['params']['rss_feed_guid']);
		$sources = array($rss_feed->title => $rss_feed->feed_url);
		break;
}

echo "<div class='group-extender-rss-container'>" . elgg_view('rss/feed', array(
	'sources' => $sources,
)) . '</div>';