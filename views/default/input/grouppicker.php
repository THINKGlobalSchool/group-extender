<?php
/**
 * Group Picker.  Sends an array of group guids.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] Array of group guids for already selected groups or null
 *
 * The name of the hidden fields is members[]
 *
 * @warning Only a single input/grouppicker is supported per web page.
 *
 * Defaults to lazy load group lists in alphabetical order. User needs
 * to type two characters before seeing the group popup list.
 *
 * As groups are selected they move down to a "groups" box.
 * When this happens, a hidden input is created with the
 * name of members[] and a value of the GUID.
 */

elgg_load_js('elgg.grouppicker');
elgg_load_js('jquery.ui.autocomplete.html');

function group_picker_add_group($group_id) {
	$group = get_entity($group_id);
	if (!$group || !($group instanceof ElggGroup)) {
		return false;
	}
	
	$icon = elgg_view_entity_icon($group, 'tiny', array('use_hover' => false));

	// this html must be synced with the grouppicker.js library
	$code = '<li><div class="elgg-image-block">';
	$code .= "<div class='elgg-image'>$icon</div>";
	$code .= "<div class='elgg-image-alt'><a href='#' class='elgg-input-group-picker'>X</a></div>";
	$code .= "<div class='elgg-body'>" . $group->name . "</div>";
	$code .= "</div>";
	$code .= "<input type=\"hidden\" name=\"members[]\" value=\"$group_id\">";
	$code .= '</li>';
	
	return $code;
}

// loop over all values and prepare them so that "in" will work in javascript
$values = array();
if (!is_array($vars['value'])) {
	$vars['value'] = array($vars['value']);
}
foreach ($vars['value'] as $value) {
	$values[$value] = TRUE;
}

// convert the values to a json-encoded list
$json_values = json_encode($values);

// create an HTML list of groups
$group_list = '';
foreach ($vars['value'] as $group_id) {
	$group_list .= group_picker_add_group($group_id);
}

?>
<div class="elgg-group-picker">
	<input type="text" class="elgg-input-group-picker" size="30"/>
	<ul class="elgg-group-picker-list"><?php echo $group_list; ?></ul>
</div>
<script type="text/javascript">
	// @todo grab the values in the init function rather than using inline JS
	elgg.grouppicker.groupList = <?php echo $json_values ?>;
</script>