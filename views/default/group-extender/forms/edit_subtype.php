<?php
/**
 * Group-Extender Edit Subtype Form
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['tab'] Individual tab to edit
 */

$tab = elgg_extract('tab', $vars);

// Subtype content
$show_type_label = elgg_echo('group-extender:label:showsubtype');
$show_type_input = elgg_view('input/dropdown', array(
	'name' => 'subtype',
	'options_values' => group_extender_get_group_subtypes(),
	'value' => $tab['params']['subtype'],
));

$sort_by_label = elgg_echo('group-extender:label:sortby');
$sort_by_input = elgg_view('input/dropdown', array(
	'name' => 'sortby',
	'options_values' => array(
		'create_date' => elgg_echo('group-extender:label:create_date'),
		'name' => elgg_echo('group-extender:label:name')
	),
	'value' => $tab['params']['sortby'],
));

$tag_label = elgg_echo('group-extender:label:showtag');
$tag_input = elgg_view('input/text', array(
	'name' => 'tag',
	'value' => $tab['params']['tag'],
));

$all_content_options = array(
	'name' => 'all_content'
);

if (!$tab['params']['tag'] || empty($tab['params']['tag'])) {
	$all_content_options['disabled'] = 'DISABLED';
}

if ($tab['params']['all_content'] == 'on') {
	$all_content_options['value'] = 'on';
	$all_content_options['checked'] = 'CHECKED';	
}

$include_all_content_label = elgg_echo('group-extender:label:includeallcontent');
$include_all_content_input = elgg_view('input/checkbox', $all_content_options);

// Hidden param inputs
$param_subtype_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'subtype',
));

$param_sortby_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'sortby',
));

$param_tag_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'tag',
));

$param_include_hidden = elgg_view('input/hidden', array(
	'name' => 'add_param[]',
	'value' => 'all_content',
));

$content = <<<HTML
	<div>
		<label>$show_type_label</label><br />
		$show_type_input
	</div><br />
	<div>
		<label>$sort_by_label</label><br />
		$sort_by_input
	</div><br />
	<div>
		<label>$tag_label</label><br />
		$tag_input
	</div>
	<div>
		<br />
		<label>$include_all_content_label</label>
		$include_all_content_input
	</div>
	$param_tag_hidden
	$param_subtype_hidden
	$param_sortby_hidden
	$param_include_hidden
HTML;

$script = <<<JAVASCRIPT
	<script type='text/javascript'>
		$(document).ready(function() {
			$(document).delegate('input[name="tag"]', 'keyup input paste', function() {
				if (!$(this).val()) {
					$('input[name="all_content"]').attr('disabled','DISABLED').removeAttr('checked').removeAttr('value');
				} else {
					$('input[name="all_content"]').removeAttr('disabled');
				}
			});

		});
	</script>
JAVASCRIPT;

echo $content;
echo $script;