<?php
/**
 * Group status for logged in user
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] Group entity
 */

$group = elgg_extract('entity', $vars);
$user = elgg_get_logged_in_user_entity();
$subscribed = elgg_extract('subscribed', $vars);

if (!elgg_is_logged_in() || !$group->canEdit()) {
	return true;
}

// Invite
elgg_register_menu_item('groups:admin', array(
	'name' => 'invite_users',
	'text' => elgg_echo('groups:invite'),
	'href' => "groups/invite/{$group->getGUID()}"
));

// Edit
elgg_register_menu_item('groups:admin', array(
	'name' => 'edit_group',
	'text' => elgg_echo('groups:edit'),
	'href' => "groups/edit/{$group->getGUID()}"
));

// Mail
elgg_register_menu_item('groups:admin', array(
	'name' => 'mail',
	'text' => elgg_echo('group_tools:menu:mail'),
	'href' => "groups/mail/{$group->getGUID()}"
));

// Export
elgg_register_menu_item('groups:admin', array(
	'name' => 'export',
	'text' => elgg_echo('group-extender:label:export'),
	'href' => "groups/export/{$group->getGUID()}"
));


// Join requests
$count = elgg_get_entities_from_relationship(array(
	'type' => 'user',
	'relationship' => 'membership_request',
	'relationship_guid' => $group->getGUID(),
	'inverse_relationship' => true,
	'count' => true,
));

if ($count) {
	$text = elgg_echo('groups:membershiprequests:pending', array($count));
} else {
	$text = elgg_echo('groups:membershiprequests');
}

elgg_register_menu_item('groups:admin', array(
	'name' => 'membership_requests',
	'text' => $text,
	'href' => "groups/requests/{$group->getGUID()}"
));

if ($group->new_layout) {
	elgg_register_menu_item('groups:admin', array(
		'name' => 'admin_edit_nav',
		'text' => elgg_echo('group-extender:tab:admin'),
		'href' => "#groupextender-tab-admin",
		'class' => 'group-extender-customize-nav-link',
		'data-group_url' => $group->getURL()
	));
}


$body = elgg_view_menu('groups:admin');
echo elgg_view_module('aside', elgg_echo('group-extender:label:group_tools'), $body);
