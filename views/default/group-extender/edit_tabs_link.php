<?php
/**
 * Group-Extender Edit Tabs Link
 *
 * @TODO Should probably make this a little cleaner.. I just don't want to hook into the 3rd party group_tools plugin
 * at the moment.
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group = elgg_get_page_owner_entity();

$url_label = elgg_echo('group-extender:label:editgrouptabs');

$url = elgg_get_site_url() . "groups/edit/{$group->guid}/tabs";

$content = <<<HTML
	<a class='right' href='$url'>$url_label</a>
HTML;

echo $content;