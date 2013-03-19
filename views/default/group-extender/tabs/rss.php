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
		$options = array(
			'type' => 'object',
			'subtype' => 'rss_feed',
			'container_guid' => $group->guid,
			'limit' => 0,
		);

		// If a tag is supplied, restrict it
		if (!empty($tab['params']['tag'])) {
			$options['metadata_name_value_pairs'] = array('name' => 'tags', 'value' => $tab['params']['tag']);
		}

		$rss_feeds = elgg_get_entities_from_metadata($options);

		if (count($rss_feeds)) {
			if ($tab['params']['consolidate_all'] != 'on') {
				foreach ($rss_feeds as $feed) {
					$feed_output = elgg_view('rss/feed', array(
						'sources' => array($feed->title => $feed->feed_url),
						'class' => 'elgg-rss-feed-module',
						'max' => 5,
					));

					$module_title = elgg_view('output/url', array(
						'text' => $feed->title,
						'href' => $feed->feed_link,
						'target' => '_blank',
					));

					$module_title .= elgg_view('output/url', array(
						'text' => elgg_echo('group-extender:label:viewall'),
						'class' => 'right',
						'href' => $feed->getURL(),
					));

					$feed_content .= elgg_view_module('featured', $module_title, $feed_output, array(
						'class' => 'group-extender-rss-module',
					));
				}

				$feed_content = "<div class='group-extender-rss-modules-container'>$feed_content</div>";

			} else {
				$sources = array();
				foreach ($rss_feeds as $feed) {
					$sources[$feed->title] = $feed->feed_url;
				}

				$feed_content = elgg_view('rss/feed', array('sources' => $sources));
			}

			
		} else {
			$feed_content = "<h3 class='center'>" . elgg_echo('rss:label:noresults') . "</h3>";
		}
		break;
	case 'url':
		$feed_content = elgg_view('rss/feed', array(
			'sources' => array($tab['title'] => $tab['params']['feed_url'])
		));
		break;
	case 'group_feed':
		$rss_feed = get_entity($tab['params']['rss_feed_guid']);
		$feed_content = elgg_view('rss/feed', array(
			'sources' => array($rss_feed->title => $rss_feed->feed_url)
		));
		break;
}

echo "<div class='group-extender-rss-container'>$feed_content</div>";