<?php
/**
 * Group-Extender Custom Search Tab
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['group'] Group to populate tab for 
 */

$group = elgg_extract('group', $vars);

elgg_load_css('elgg.googlesearch');
elgg_load_js('elgg.googlesearch');

echo elgg_view('googlesearch/group_search', array('group' => $group));
