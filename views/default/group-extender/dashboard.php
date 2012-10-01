<?php
/**
 * Group-Extender Dashboard
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group_select_form = elgg_view_form('group_dashboard/groups', array('action' => NULL));

$label = elgg_echo('group-extender:label:groupactivity');

$content = <<<HTML
	<script>
		$(document).ready(function() {
			elgg.groupextender.handle_hash();
		});
	</script>
	<div id="group-dashboard">
		<div id="group-dashboard-group-select-form-container">
			$group_select_form
		</div>
		<label>$label</label><br /><br />
		<div id="group-dashboard-groups-container">
		</div>
	</div>
HTML;

echo $content;