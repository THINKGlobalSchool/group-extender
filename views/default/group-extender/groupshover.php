<?php
/**
 * Group-Extender Groups Topbar hover
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$user = elgg_get_logged_in_user_entity();

// Group member params
$options = array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $user->guid,
	'inverse_relationship' => FALSE,
	'full_view' => FALSE,
	'pagination' => FALSE,
	'limit' => 0,
);

$groups = elgg_get_entities_from_relationship($options);

foreach ($groups as $group) {
	$icon = elgg_view_entity_icon($group, 'tiny', array('hide_menu' => true));
	
	$params = array(
		'entity' => $group,
		'metadata' => '',
		//'subtitle' => $group->briefdescription,
	);
	$params = $params + $vars;
	$list_body = elgg_view('group/elements/summary', $params);
	
	$group_url = $group->getURL();

	$group_content .= "<li onclick='javascript:window.location.href=\"$group_url\"' class='groups-hover-pointer'>" . elgg_view_image_block($icon, $list_body, $vars) . "</li>";
}

$content = <<<HTML
	<span id='groups-topbar-hover'>
		<table>
			<tr>
				<td>
					<ul>
						$group_content
					</ul>
				</td>
			</tr>
		</table>
	</span>
HTML;

echo $content;