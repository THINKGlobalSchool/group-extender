<?php
/**
 * Group-Extender Featured sidebar extension
 *
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 * 
 */

$user = elgg_get_logged_in_user_entity();
if ($user) {
	$url = "groups/invitations/$user->username";
	$invitations = groups_get_invited_groups($user->getGUID());
	if (is_array($invitations) && !empty($invitations)) {
		$invitation_count = count($invitations);
		$text = elgg_echo('groups:invitations:pending', array($invitation_count));
	} else {
		$text = elgg_echo('groups:invitations');
	}

	echo elgg_view('output/url', array(
		'text' => $text,
		'href' => $url,
		'class' => 'elgg-button elgg-button-action',
		'style' => 'width: 172px; margin-bottom: 10px;'
	));
}
