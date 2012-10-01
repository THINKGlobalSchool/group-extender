<?php
/**
 * Group-Extender Dashboard Settings Save Action
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */

$categories = get_input('categories_list');
$enable_dashboard = get_input('enable_dashboard');

$categories = serialize($categories);

elgg_set_plugin_setting('dashboard_categories', $categories, 'group-extender');
elgg_set_plugin_setting('enable_dashboard', $enable_dashboard, 'group-extender');

system_message(elgg_echo('group-extender:success:dashboardsettings'));
forward(REFERER);