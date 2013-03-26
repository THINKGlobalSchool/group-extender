<?php
/**
 * Group-Extender Edit RSS FeedTab Form
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['tab']        Individual tab to edit
 * @uses $vars['group_guid'] Group guid
 */

$tab = elgg_extract('tab', $vars);
$group_guid = elgg_extract('group_guid', $vars);

$feed_tab_type = $tab['params']['feed_tab_type'];

// Default to 'all' for new tabs
if (!$feed_tab_type) {
	$feed_tab_type = 'all';
}

$group_feed_input_class = $feed_tab_type == 'group_feed' ? '' : 'hidden';
$feed_url_input_class = $feed_tab_type == 'url' ? '' : 'hidden';
$all_feeds_input_class = $feed_tab_type == 'all' ? '' : 'hidden';

// Get group rss feeds
$group_rss_feeds = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'rss_feed',
	'container_guid' => $group_guid,
	'limit' => 0
));

$feed_tab_type_dropdown = array(
	'all' => elgg_echo('group-extender:label:allgroupfeeds'),
	'url' => elgg_echo('group-extender:label:useurl'),
);

// Show feed select dropdown if this group has rss feeds
if (count($group_rss_feeds)) {
	$feed_tab_type_dropdown['group_feed'] = elgg_echo('group-extender:label:selectrssfeed');

	$group_rss_feeds_dropdown = array();

	foreach ($group_rss_feeds as $rss_feed) {
		$group_rss_feeds_dropdown[$rss_feed->guid] = $rss_feed->title;
	}

	// Group feed select input
	$group_rss_feed_label = elgg_echo('group-extender:label:selectfeed');
	$group_rss_feed_input = elgg_view('input/dropdown', array(
		'name' => 'rss_feed_guid',
		'options_values' => $group_rss_feeds_dropdown,
		'value' => $tab['params']['rss_feed_guid'],
	));

	$select_feed_content = "<div class='_rsstabtype_group_feed _rsstabtype $group_feed_input_class'><br /><label>$group_rss_feed_label</label>&nbsp;$group_rss_feed_input</div><br />";
}

// Main select type dropdown input
$feed_tab_type_label = elgg_echo('group-extender:label:whichfeed');
$feed_tab_type_input = elgg_view('input/dropdown', array(
	'name' => 'feed_tab_type',
	'options_values' => $feed_tab_type_dropdown,
	'value' => $feed_tab_type,
));

// Set up options for url input
$url_options = array(
	'name' => 'feed_url',
	'value' => $tab['params']['feed_url'],
	'id' => 'rss-url',
);

// Feed url label/input
$url_label = elgg_echo('group-extender:label:useurl');
$url_input = elgg_view('input/url', $url_options);

$consolidate_label = elgg_echo('group-extender:label:consolidatefeeds');
$consolidate_input = elgg_view('input/checkbox', array(
	'name' => 'consolidate_all',
	'checked' => $tab['params']['consolidate_all'] == 'on',
));

// Tag label/input
$tag_label = elgg_echo('group-extender:label:showtag');
$tag_input = elgg_view('input/text', array(
	'name' => 'tag',
	'value' => $tab['params']['tag'],
));

// Hidden param inputs
$param_url_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'feed_url',
));

$param_consolidate_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'consolidate_all',
));

$param_guid_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'rss_feed_guid',
));

$param_tab_type_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'feed_tab_type',
));

$param_tag_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'tag',
));

$content = <<<HTML
<div>
	<label>$feed_tab_type_label</label>
	$feed_tab_type_input
</div>
<div class='_rsstabtype_url _rsstabtype $feed_url_input_class'>
	<br />
	<label>$url_label</label>
	$url_input
</div>
<div class='_rsstabtype_all _rsstabtype $all_feeds_input_class'>
	<br />
	<label>$consolidate_label</label>
	$consolidate_input
	<br /><br />
	<label>$tag_label</label>
	$tag_input
</div>
$select_feed_content
$param_url_hidden
$param_consolidate_hidden
$param_guid_hidden
$param_tab_type_hidden
$param_tag_hidden
HTML;

echo $content;