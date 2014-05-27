<?php
/**
 * Group-Extender css
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>
/** <style> /**/
#group-navigator-select {
	width: 200px;
}

#group-dashboard-groups-container {
	column-count: 2;
	-moz-column-count: 2;
	-webkit-column-count: 2;
}

#group-dashboard-group-select-form-container {
	margin-top: 5px;
	margin-bottom: 8px;
}

.group-dashboard-module {
	display: inline-block;
	margin-right: 10px;
}

/* Fix alignment for 'image blocks' without an image */
.group-extender-tab-content-container .elgg-body:only-child {
	margin-left: 10px;	
}

#group-extender-tab-refresh-submit {
	float: right;
	font-size: 90%;
}

/** Group Categories on 'All' page **/
#groups-all-categories-ajaxmodule {
	float: left;
	width: 30%;
}

#groups-all-categories-ajaxmodule li.elgg-item:hover {
	cursor: pointer;
	background: #eee;
}

#groups-all-categories-ajaxmodule li.elgg-item {
	border-bottom: 0;
	margin: 0;
	padding-left: 4px;
}

#groups-all-categories-ajaxmodule li.elgg-item .elgg-subtext {
	margin-bottom: 0;
}

#groups-all-categories-ajaxmodule .elgg-list {
	border-top: 0;
}

#groups-all-categories-ajaxmodule .tgstheme-entity-menu {
	display: none;
}

#groups-all-group-list {
	float: right;
	width: 69%;
}

.category-state-selected, .category-state-selected:hover {
	background: #ccc !important;
}

/** Copy/move entity action items **/
.elgg-menu-item-copy-to-group {
	background-position: 0 -36px !important;
}

.elgg-menu-item-move-to-group {
	background-position: 0 -18px !important;
}

/** Colorbox popup content **/

.group-extender-cb-popup {
	width: 300px;
	overflow: hidden;
}

.group-extender-cb-popup select {
	width: 150px;
}

.ge-move-out-of-group {
	float: right;
	margin-top: 6px;
}

/* Groups topbar item and hover */
.elgg-menu-item-groups-topbar-hover-menu {
	height: 40px;
}

.elgg-menu-item-groups-topbar-hover-menu > a { 
	border-left: 1px solid #DB1730; 
	padding: 2px 15px 0 10px !important;
}

.elgg-menu-item-groups-topbar-hover-menu:hover #groups-topbar-hover {
	display: block;
}

#groups-topbar-hover {
	position: absolute;
	top: 32px;
	left: 0;
	background: #ffffff;
	-webkit-border-radius: 0 0 4px 4px;
	-moz-border-radius: 0 0 4px 4px;
	border-radius: 0 0 4px 4px;
	border: 1px solid #999;
	border-top: 0px;
	min-width: 300px;
	display: none;
}

#groups-topbar-hover ul li {
	border-bottom: 1px dotted #CCC;
}

#groups-topbar-hover ul li:hover {
	background: #2D3F46
}

#groups-topbar-hover ul li:hover  .elgg-body a {
	color: #FFFFFF;
	text-decoration: none;
}

#groups-topbar-hover ul li:last-child {
	-webkit-border-radius: 0 0 4px 4px;
	-moz-border-radius: 0 0 4px 4px;
	border-radius: 0 0 4px 4px;
}

#groups-topbar-hover .elgg-image-block {
	padding: 4px 0 6px;
	margin: 0px;
}

.groups-hover-pointer {
	cursor: pointer;
}

/* Group tab gallery override */
.group-extender-tab-content-container ul.elgg-gallery {

}

.group-extender-tab-content-container ul.elgg-gallery .elgg-item {
	padding: 4px 10px;
}

/* Group title menu styles */

.elgg-menu-title .elgg-menu-item-archived {
	color: #AAAAAA;
}


/* General tab styles */

.elgg-menu-page li > a.group-extender-customize-nav-link {
	background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
	border: medium none;
	border-radius: 0;
	color: #91131E;
	margin: 0;
	padding: 0;
	text-transform: none;
	font-family: "Lucida Grande",​Arial,​Tahoma,​Verdana,​sans-serif;
	text-align: center;
}

.elgg-menu-page li > a.group-extender-customize-nav-link:hover {
	text-decoration: underline;
}

/** Tab/page sidebar **/
.elgg-owner-block .elgg-menu-group-extender-tab-menu li {
	border-top: 1px solid #DDD;
}

.elgg-owner-block .elgg-menu-group-extender-tab-menu li:first-child {
	border-top: 0;
}

.elgg-owner-block .elgg-menu-group-extender-tab-menu li:last-child {
	border-bottom: 1px solid #DDD;
}

/** Group tools menu **/
.elgg-menu-group-tools li a {
	display: block;

	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;

	background-color: white;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.elgg-menu-group-tools li a:hover {
	background-color: #2D3F46;
	color: white;
	text-decoration: none;
}
.elgg-menu-group-tools li.elgg-state-selected > a {
	background: url(<?php echo elgg_get_site_url(); ?>mod/tgstheme/_graphics/badge-back.png) repeat-x bottom left #DD2036;
	color: white;
}