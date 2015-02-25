<?php
/**
 * Group members sidebar
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] Group entity
 * @uses $vars['limit']  The number of members to display
 */
$group = elgg_extract("entity", $vars);

if(!empty($group) && elgg_instanceof($group, "group")){
	if($group->getPrivateSetting("group_tools:cleanup:members") != "yes"){
		$limit = elgg_extract('limit', $vars, 14);

		$members_label = elgg_echo('groups:members:more');
		
		$all_link = elgg_view('output/url', array(
			'href' => 'groups/members/' . $group->getGUID(),
			'text' => $members_label,
			'is_trusted' => true,
		));
		
		$body = elgg_list_entities_from_relationship(array(
			'relationship' => 'member',
			'relationship_guid' => $group->getGUID(),
			'inverse_relationship' => true,
			'types' => 'user',
			'limit' => $limit,
			'list_type' => 'gallery',
			'gallery_class' => 'elgg-gallery-users',
			'pagination' => false
		));
		
		$body .= "<div class='center mts'>$all_link</div>";

		$count = $group->getMembers(array('limit' => 0, 'count' => TRUE));
		
		echo elgg_view_module('aside', elgg_echo('groups:members') . " ({$count})", $body);
	}
}