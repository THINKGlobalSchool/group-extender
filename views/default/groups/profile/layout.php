<?php
/**
 * Layout of the groups profile page
 *
 * - Override displays the custom tabs interface
 * 
 * @uses $vars['entity']
 */
?>
<!-- Hide the group title in full view/tweak breadcrumbs -->
<style type='text/css'>
	.elgg-head > h2.elgg-heading-main {
		display: none;
	}
	.elgg-head > .elgg-menu-title {
		float: left;
	}

	.elgg-menu.elgg-breadcrumbs {
		width: 90%;
		top: 0;
		float: left;
	}
</style>
<?php

if (elgg_is_active_plugin('tagdashboards')) {
	elgg_load_js('elgg.tagdashboards');
	elgg_load_css('elgg.tagdashboards');
}

// Add a view to extend the top of the group profile page
echo elgg_view('groups/profile/layout/top/extend');

if (!$vars['entity']->new_layout) {
	echo elgg_view('groups/profile/summary', $vars);
}

if (group_gatekeeper(false)) {
	echo elgg_view('group-extender/group_tabs_content', $vars);
} else {
	echo elgg_view('groups/profile/closed_membership');
}