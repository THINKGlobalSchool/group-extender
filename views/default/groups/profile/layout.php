<?php
/**
 * Layout of the groups profile page
 *
 * - Override displays the custom tabs interface
 * 
 * @uses $vars['entity']
 */
?>
<!-- Hide the group title in full view -->
<style type='text/css'>
	.elgg-head > h2.elgg-heading-main {
		display: none;
	}
	.elgg-head > .elgg-menu-title {
		float: left;
	}
</style>
<?php
elgg_load_js('elgg.tagdashboards');
elgg_load_css('elgg.tagdashboards');

//echo elgg_view('groups/profile/summary', $vars);
if (group_gatekeeper(false)) {
	echo elgg_view('group-extender/group_tabs_content', $vars);
} else {
	echo elgg_view('groups/profile/closed_membership');
}