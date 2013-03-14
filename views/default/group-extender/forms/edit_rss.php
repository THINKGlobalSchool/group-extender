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
 * @uses $vars['tab'] Individual tab to edit
 */

$tab = elgg_extract('tab', $vars);

// Feed url label
$url_label = elgg_echo('rss:label:url');
$url_input = elgg_view('input/url', array(
	'name' => 'feed_url',
	'value' => $tab['params']['feed_url'],
	'id' => 'rss-url',
));

// Hidden param inputs
$param_url_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'feed_url',
));

$content = <<<HTML
	<div>
		<label>$url_label</label><br />
		$url_input
	</div>
	$param_url_hidden
HTML;

echo $content;