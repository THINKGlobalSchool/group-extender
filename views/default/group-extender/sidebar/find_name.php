<?php
/**
 * Group-Extender Name search
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 *
 */
$url = elgg_get_site_url() . 'groups/search';
$body = elgg_view_form('groups/find_name', array(
	'action' => $url,
	'method' => 'get',
	'disable_security' => true,
));

echo elgg_view_module('aside', elgg_echo('group-extender:searchname'), $body);
