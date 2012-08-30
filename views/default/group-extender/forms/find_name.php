<?php
/**
 * Group-Extender Name-based search form body
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$name_string = elgg_echo('group-extender:search:namestring');

$params = array(
	'name' => 'tag',
	'class' => 'elgg-input-search mbm',
	'value' => $name_string,
	'onclick' => "if (this.value=='$name_string') { this.value='' }",
);
echo elgg_view('input/text', $params);

echo elgg_view('input/submit', array('value' => elgg_echo('search:go')));
