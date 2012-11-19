<?php
/**
 * Group-Extender group category admin
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Load Admin JS
elgg_load_js('elgg.groupextender');		
elgg_load_js('elgg.groupextender.admin');
elgg_load_js('elgg.grouppicker');

// Load Admin CSS
elgg_load_css('elgg.groupextender.admin');

$add_category = elgg_view('output/url', array(
	'text' => elgg_echo('group-extend:label:newcategory'),
	'href' => elgg_get_site_url() . 'admin/groupextender/addcategory',
	'class' => 'elgg-button elgg-button-action',
));

$module =  elgg_view('modules/ajaxmodule', array(
	'title' => elgg_echo('group-extender:label:currentcategories'),
	'subtypes' => array('group_category'),
	'limit' => 0,
	'module_type' => 'inline',
	'module_class' => 'group-categories-module',
	'module_id' => 'category-list',
	'access_show_hidden_entities' => TRUE,
));

$content = <<<HTML
	$add_category
	<div style='clear: both;'></div>
	$module
	<div id='group-list' class='group-categories-module'></div>
HTML;

echo $content;