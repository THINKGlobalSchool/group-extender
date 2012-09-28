<?php
/**
 * Group-Extender add group category admin
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */
// Load Admin CSS
elgg_load_css('elgg.groupextender.admin');
echo groupcategories_get_edit_content('edit', get_input('guid'));
