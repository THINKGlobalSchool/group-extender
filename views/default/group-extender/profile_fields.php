<?php
/**
 * Group-Extender profile fields extender
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */

$group = $vars['entity'];
$owner = $group->getOwnerEntity();

echo "<div class='{$even_odd}'>";
echo "<b>" . elgg_echo("groups:owner") . ": </b>";
echo elgg_view('output/url', array(
	'text' => $owner->name,
	'value' => $owner->getURL(),
	'is_trusted' => true,
));
echo "</div>";

$even_odd = ($even_odd == 'even') ? 'odd' : 'even';