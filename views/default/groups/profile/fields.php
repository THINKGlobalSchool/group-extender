<?php
/**
 * Group profile fields
 * 
 * - This custom view removes the name and description
 */

$group = $vars['entity'];

$profile_fields = elgg_get_config('group');

if (is_array($profile_fields) && count($profile_fields) > 0) {

	$even_odd = 'odd';
	foreach ($profile_fields as $key => $valtype) {
		// do not show the name or brief description
		if ($key == 'name' || $key == 'briefdescription') {
			continue;
		}

		$value = $group->$key;
		if (empty($value)) {
			continue;
		}

		$options = array('value' => $group->$key);
		if ($valtype == 'tags') {
			$options['tag_names'] = $key;
		}

		echo "<div class=\"{$even_odd}\">";
		
		// Don't show the 'description' label
		if ($key != 'description') {
			echo "<b>";
			echo elgg_echo("groups:$key");
			echo ": </b>";
		}

		echo elgg_view("output/$valtype", $options);
		echo "</div>";

		$even_odd = ($even_odd == 'even') ? 'odd' : 'even';
	}
}
