<?php
/**
 * Layout of the groups profile page
 *
 * - Override displays the custom tabs interface
 * 
 * @uses $vars['entity']
 */

echo elgg_view('groups/profile/summary', $vars);
if (group_gatekeeper(false)) {
	echo "<div id='group-extender-group-tabs'>";
	echo elgg_view('group-extender/group_tabs', array('group_guid' => $vars['entity']->guid));
	echo "</div>";
} else {
	echo elgg_view('groups/profile/closed_membership');
}
