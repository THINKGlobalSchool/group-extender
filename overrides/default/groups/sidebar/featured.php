<?php
/**
 * Featured groups
 *
 * OVERRIDE: 
 * - Do not display featured groups on individual group pages
 *
 * @package ElggGroups
 */

if (!elgg_get_page_owner_guid()) {
	$featured_groups = elgg_get_entities_from_metadata(array(
		'metadata_name' => 'featured_group',
		'metadata_value' => 'yes',
		'type' => 'group',
		'limit' => 10,
	));

	if ($featured_groups) {

		elgg_push_context('widgets');
		$body = '';
		foreach ($featured_groups as $group) {
			$body .= elgg_view_entity($group, array('full_view' => false));
		}
		elgg_pop_context();

		echo elgg_view_module('aside', elgg_echo("groups:featured"), $body);
	}
}